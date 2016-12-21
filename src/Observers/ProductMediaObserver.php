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
     * Will be invoked by the action on the events the listener has been registered for.
     *
     * @param array $row The row to handle
     *
     * @return array The modified row
     * @see \TechDivision\Import\Product\Observers\ImportObserverInterface::handle()
     */
    public function handle(array $row)
    {

        // load the header information
        $headers = $this->getHeaders();

        // initialize the array for the product media
        $artefacts = array();

        // load the store view code
        $storeViewCode = $row[$headers[ColumnKeys::STORE_VIEW_CODE]];

        // load the parent SKU from the row
        $parentSku = $row[$headers[ColumnKeys::SKU]];

        // query whether or not, we've a base image
        if (!empty($row[$headers[ColumnKeys::BASE_IMAGE]])) {
            // load the base image data
            $baseImage = $row[$headers[ColumnKeys::BASE_IMAGE]];

            // prepare and append the base image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $baseImage,
                ColumnKeys::IMAGE_PATH_NEW   => $baseImage,
                ColumnKeys::IMAGE_LABEL      => isset($row[$headers[ColumnKeys::BASE_IMAGE_LABEL]]) ? $row[$headers[ColumnKeys::BASE_IMAGE_LABEL]] : 'Image'
            );
        }

        // query whether or not, we've a small image
        if (!empty($row[$headers[ColumnKeys::SMALL_IMAGE]])) {
            // load the small image data
            $smallImage = $row[$headers[ColumnKeys::SMALL_IMAGE]];

            // prepare and append the small image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $smallImage,
                ColumnKeys::IMAGE_PATH_NEW   => $smallImage,
                ColumnKeys::IMAGE_LABEL      => isset($row[$headers[ColumnKeys::SMALL_IMAGE_LABEL]]) ? $row[$headers[ColumnKeys::SMALL_IMAGE_LABEL]] : 'Image'
            );
        }

        // query whether or not, we've a small thumbnail
        if (!empty($row[$headers[ColumnKeys::THUMBNAIL_IMAGE]])) {
            // load the thumbnail image data
            $thumbnailImage = $row[$headers[ColumnKeys::THUMBNAIL_IMAGE]];

            // prepare and append the thumbnail image to the artefacts
            $artefacts[] = array(
                ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                ColumnKeys::IMAGE_PATH       => $thumbnailImage,
                ColumnKeys::IMAGE_PATH_NEW   => $thumbnailImage,
                ColumnKeys::IMAGE_LABEL      => isset($row[$headers[ColumnKeys::THUMBNAIL_IMAGE_LABEL]]) ? $row[$headers[ColumnKeys::THUMBNAIL_IMAGE_LABEL]] : 'Image'
            );
        }

        // query whether or not, we've additional images
        if (!empty($row[$headers[ColumnKeys::ADDITIONAL_IMAGES]])) {
            // query whether or not, we've additional images
            if ($additionalImages = $row[$headers[ColumnKeys::ADDITIONAL_IMAGES]]) {
                // expand the additional image labels, if available
                $additionalImageLabels = array();
                if (isset($row[$headers[ColumnKeys::ADDITIONAL_IMAGE_LABELS]])) {
                    $additionalImageLabels = explode(',', $row[$headers[ColumnKeys::ADDITIONAL_IMAGE_LABELS]]);
                }

                // initialize the images with the found values
                foreach (explode(',', $additionalImages) as $key => $additionalImage) {
                    // prepare and append the additional image to the artefacts
                    $artefacts[] = array(
                        ColumnKeys::STORE_VIEW_CODE  => $storeViewCode,
                        ColumnKeys::IMAGE_PARENT_SKU => $parentSku,
                        ColumnKeys::IMAGE_PATH       => $additionalImage,
                        ColumnKeys::IMAGE_PATH_NEW   => $additionalImage,
                        ColumnKeys::IMAGE_LABEL      => isset($additionalImageLabels[$key]) ? $additionalImageLabels[$key] : 'Image'
                    );
                }
            }
        }

        // append the images to the subject
        $this->addArtefacts($artefacts);

        // returns the row
        return $row;
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
    public function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(ProductMediaObserver::ARTEFACT_TYPE, $artefacts);
    }
}
