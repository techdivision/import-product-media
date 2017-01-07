<?php

/**
 * TechDivision\Import\Product\Media\Observers\ProductMediaObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Observer that extracts theproduct's media data from a CSV file to be added to media specifi CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaObserver extends AbstractProductImportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'media';

    /**
     * The default image label.
     *
     * @var string
     */
    const DEFAULT_IMAGE_LABEL = 'Image';

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // initialize the array for the product media
        $artefacts = array();

        // load the store view code
        $storeViewCode = $this->getValue(ColumnKeys::STORE_VIEW_CODE);

        // load the parent SKU from the row
        $parentSku = $this->getValue(ColumnKeys::SKU);

        // query whether or not, we've a base image
        if ($baseImage = $this->getValue(ColumnKeys::BASE_IMAGE)) {
            // prepare and append the base image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $baseImage,
                ColumnKeys::IMAGE_PATH_NEW   => $baseImage,
                ColumnKeys::IMAGE_LABEL      => $this->hasValue(ColumnKeys::BASE_IMAGE_LABEL) ?
                                                $this->getValue(ColumnKeys::BASE_IMAGE_LABEL) :
                                                $this->getDefaultImageLabel()
            );
        }

        // query whether or not, we've a small image
        if ($smallImage = $this->getValue(ColumnKeys::SMALL_IMAGE)) {
            // prepare and append the small image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $smallImage,
                ColumnKeys::IMAGE_PATH_NEW   => $smallImage,
                ColumnKeys::IMAGE_LABEL      => $this->hasValue(ColumnKeys::SMALL_IMAGE_LABEL) ?
                                                $this->getValue(ColumnKeys::SMALL_IMAGE_LABEL) :
                                                $this->getDefaultImageLabel()
            );
        }

        // query whether or not, we've a small thumbnail
        if ($thumbnailImage = $this->getValue(ColumnKeys::THUMBNAIL_IMAGE)) {
            // prepare and append the thumbnail image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $thumbnailImage,
                ColumnKeys::IMAGE_PATH_NEW   => $thumbnailImage,
                ColumnKeys::IMAGE_LABEL      => $this->hasValue(ColumnKeys::THUMBNAIL_IMAGE_LABEL) ?
                                                $this->getValue(ColumnKeys::THUMBNAIL_IMAGE_LABEL) :
                                                $this->getDefaultImageLabel()
            );
        }

        // query whether or not, we've additional images
        if ($additionalImages = $this->getValue(ColumnKeys::ADDITIONAL_IMAGES, null, array($this, 'explode'))) {
            // expand the additional image labels, if available
            $additionalImageLabels = $this->getValue(ColumnKeys::ADDITIONAL_IMAGE_LABELS, array(), array($this, 'explode'));

            // initialize the images with the found values
            foreach ($additionalImages as $key => $additionalImage) {
                // prepare and append the additional image to the artefacts
                $artefacts[] = array(
                    ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                    ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                    ColumnKeys::IMAGE_PATH       => $additionalImage,
                    ColumnKeys::IMAGE_PATH_NEW   => $additionalImage,
                    ColumnKeys::IMAGE_LABEL      => isset($additionalImageLabels[$key]) ?
                                                    $additionalImageLabels[$key] :
                                                    $this->getDefaultImageLabel()
                );
            }
        }

        // append the images to the subject
        $this->addArtefacts($artefacts);
    }

    /**
     * Return's the default image label.
     *
     * @return string The default image label
     */
    protected function getDefaultImageLabel()
    {
        return ProductMediaObserver::DEFAULT_IMAGE_LABEL;
    }

    /**
     * Add the passed product type artefacts to the product with the
     * last entity ID.
     *
     * @param array $artefacts The product type artefacts
     *
     * @return void
     * @uses \TechDivision\Import\Product\Media\Subjects\MediaSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(ProductMediaObserver::ARTEFACT_TYPE, $artefacts);
    }
}
