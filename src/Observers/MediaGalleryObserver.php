<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaObserver
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

        // load the product SKU
        $parentSku = $row[$headers[ColumnKeys::IMAGE_PARENT_SKU]];

        // load parent/option ID
        $parentId = $this->mapSkuToEntityId($parentSku);

        // query whether or not, the parent ID have changed
        if (!$this->isParentId($parentId)) {
            // reset the position counter for the product media gallery value
            $this->resetPositionCounter();
            // preserve the parent ID
            $this->setParentId($parentId);

            // initialize the gallery data
            $attributeId = 90;
            $value = $row[$headers[ColumnKeys::IMAGE_PATH]];
            $mediaType = 'image';
            $disabled = 0;

            // persist the product media gallery data
            $valueId = $this->persistProductMediaGallery(array($attributeId, $value, $mediaType, $disabled));

            // persist the product media gallery to entity data
            $this->persistProductMediaGalleryValueToEntity(array($valueId, $parentId));

            // temporarily persist the value ID
            $this->setParentValueId($valueId);
        }

        // returns the row
        return $row;
    }

    /**
     * Set's the value ID of the created media gallery entry.
     *
     * @param integer $parentValueId The ID of the created media gallery entry
     *
     * @return
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
     * Return's TRUE if the passed ID is the parent one.
     *
     * @param integer $parentID The parent ID to check
     *
     * @return boolean TRUE if the passed ID is the parent one
     */
    public function isParentId($parentId)
    {
        return $this->getParentId() === $parentId;
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
     * Return's the ID of the parent product to relate the variant with.
     *
     * @return integer The ID of the parent product
     */
    public function getParentId()
    {
        return $this->getSubject()->getParentId();
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
