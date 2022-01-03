<?php

/**
 * TechDivision\Import\Product\Media\Observers\CleanUpMediaGalleryObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Utils\StoreViewCodes;

/**
 * Observer that cleaned up a product's media gallery information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class CleanUpMediaGalleryObserver extends AbstractProductImportObserver
{

    /**
     * The product media processor instance.
     *
     * @var \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
     */
    protected $productMediaProcessor;

    /**
     * Initialize the observer with the passed product media processor instance.
     *
     * @param \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface $productMediaProcessor The product media processor instance
     */
    public function __construct(ProductMediaProcessorInterface $productMediaProcessor)
    {
        $this->productMediaProcessor = $productMediaProcessor;
    }

    /**
     * Return's the product media processor instance.
     *
     * @return \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface The product media processor instance
     */
    protected function getProductMediaProcessor()
    {
        return $this->productMediaProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {
        if ($this->getSubject()->getStoreViewCode(StoreViewCodes::ADMIN) !== StoreViewCodes::ADMIN) {
            return;
        }

        // query whether or not the media gallery has to be cleaned up
        if ($this->getSubject()->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_MEDIA_GALLERY) &&
            $this->getSubject()->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_MEDIA_GALLERY)
        ) {
            // initialize the array for the actual images
            $actualImageNames = array();

            // iterate over the available image fields
            foreach (array_keys($this->getImageTypes()) as $imageColumnName) {
                if ($this->hasValue($imageColumnName) && !in_array($imageName = $this->getValue($imageColumnName), $actualImageNames)) {
                    $actualImageNames[] = $imageName;
                }
            }

            // query whether or not, we've additional images
            if ($additionalImages = $this->getValue(ColumnKeys::ADDITIONAL_IMAGES, null, array($this, 'explode'))) {
                foreach ($additionalImages as $additionalImageName) {
                    // do nothing if the image has already been added, e. g. it is the base image
                    if (in_array($additionalImageName, $actualImageNames)) {
                        continue;
                    }

                    // else, add the image to the array
                    $actualImageNames[] = $additionalImageName;
                }
            }

            // load the existing media gallery entities for the produdct with the given SKU
            $existingProductMediaGalleries = $this->getProductMediaProcessor()
                                                  ->getProductMediaGalleriesBySku($sku = $this->getValue(ColumnKeys::SKU));

            // remove the images that are NOT longer available in the CSV file
            foreach ($existingProductMediaGalleries as $existingProductMediaGallery) {
                // do nothing if the file still exists
                if (in_array($existingImageName = $existingProductMediaGallery[MemberNames::VALUE], $actualImageNames)) {
                    continue;
                }

                try {
                    // remove the old image from the database
                    $this->getProductMediaProcessor()
                         ->deleteProductMediaGallery(array(MemberNames::VALUE_ID => $existingProductMediaGallery[MemberNames::VALUE_ID]));

                    // log a debug message that the image has been removed
                    $this->getSubject()
                         ->getSystemLogger()
                         ->warning(
                             $this->getSubject()->appendExceptionSuffix(
                                 sprintf(
                                     'Successfully removed image "%s" from media gallery for product with SKU "%s"',
                                     $existingImageName,
                                     $sku
                                 )
                             )
                         );
                } catch (\Exception $e) {
                    // log a warning if debug mode has been enabled and the file is NOT available
                    if (!$this->getSubject()->isStrictMode()) {
                        $this->getSubject()
                             ->getSystemLogger()
                             ->warning($this->getSubject()->appendExceptionSuffix($e->getMessage()));
                        $this->mergeStatus(
                            array(
                                RegistryKeys::NO_STRICT_VALIDATIONS => array(
                                    basename($this->getFilename()) => array(
                                        $this->getLineNumber() => array(
                                            MemberNames::VALUE_ID =>  $e->getMessage()
                                        )
                                    )
                                )
                            )
                        );
                    } else {
                        throw $e;
                    }
                }
            }

            // log a message that the images has been cleaned-up
            $this->getSubject()
                 ->getSystemLogger()
                 ->debug(
                     $this->getSubject()->appendExceptionSuffix(
                         sprintf(
                             'Successfully cleaned-up media gallery for product with SKU "%s"',
                             $sku
                         )
                     )
                 );
            }
    }

    /**
     * Load's the product media gallery entities with the passed SKU.
     *
     * @param string $sku The SKU to load the media gallery entities for
     *
     * @return array The product media gallery entities
     */
    protected function getProductMediaGalleriesBySku($sku)
    {
        return $this->getProductMediaProcessor()->getProductMediaGalleriesBySku($sku);
    }

    /**
     * Return's the array with the available image types and their label columns.
     *
     * @return array The array with the available image types
     */
    protected function getImageTypes()
    {
        return $this->getSubject()->getImageTypes();
    }
}
