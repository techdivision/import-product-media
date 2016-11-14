<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * A SLSB that handles the process to import product media.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class MediaGalleryObserver extends AbstractProductImportObserver
{

    /**
     * {@inheritDoc}
     * @see \Importer\Csv\Actions\Listeners\Row\ListenerInterface::handle()
     */
    public function handle(array $row)
    {

        // load the header information
        $headers = $this->getHeaders();

        // query whether or not, the image changed
        if ($this->isParentImage($row[$headers[ColumnKeys::IMAGE_PATH]])) {
            return $row;
        }

        // load the product SKU
        $parentSku = $row[$headers[ColumnKeys::IMAGE_PARENT_SKU]];

        // load parent/option ID
        $parentId = $this->mapSkuToEntityId($parentSku);

        // reset the position counter for the product media gallery value
        $this->resetPositionCounter();

        // preserve the parent ID
        $this->setParentId($parentId);

        // initialize the gallery data
        $disabled = 0;
        $attributeId = 90;
        $mediaType = 'image';
        $image = $row[$headers[ColumnKeys::IMAGE_PATH_NEW]];

        // persist the product media gallery data
        $valueId = $this->persistProductMediaGallery(array($attributeId, $image, $mediaType, $disabled));

        // persist the product media gallery to entity data
        $this->persistProductMediaGalleryValueToEntity(array($valueId, $parentId));

        // temporarily persist the value ID
        $this->setParentValueId($valueId);

        // returns the row
        return $row;
    }

    /**
     * Return's the name of the created image.
     *
     * @return string The name of the created image
     */
    public function getParentImage()
    {
        return $this->getSubject()->getParentImage();
    }

    /**
     * Return's TRUE if the passed image is the parent one.
     *
     * @param string $image The imageD to check
     *
     * @return boolean TRUE if the passed image is the parent one
     */
    public function isParentImage($image)
    {
        return $this->getParentImage() === $image;
    }

    /**
     * Set's the value ID of the created media gallery entry.
     *
     * @param integer $parentValueId The ID of the created media gallery entry
     *
     * @return void
     */
    public function setParentValueId($parentValueId)
    {
        $this->getSubject()->setParentValueId($parentValueId);
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToEntityId($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Set's the ID of the parent product to relate the variant with.
     *
     * @param integer $parentId The ID of the parent product
     *
     * @return void
     */
    public function setParentId($parentId)
    {
        $this->getSubject()->setParentId($parentId);
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    public function resetPositionCounter()
    {
        $this->getSubject()->resetPositionCounter();
    }

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array $productMediaGallery The product media gallery data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductMediaGallery($productMediaGallery)
    {
        return $this->getSubject()->persistProductMediaGallery($productMediaGallery);
    }

    /**
     * Persist's the passed product media gallery value to entity data.
     *
     * @param array $productMediaGalleryValueToEntity The product media gallery value to entity data to persist
     *
     * @return void
     */
    public function persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity)
    {
        $this->getSubject()->persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity);
    }
}
