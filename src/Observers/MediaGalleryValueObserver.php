<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryValueObserver
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Observer that creates/updates the product's media gallery value information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryValueObserver extends AbstractProductImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not, the image changed
        if ($this->isParentImage($imagePath = $this->getValue(ColumnKeys::IMAGE_PATH))) {
            return;
        }

        // initialize and persist the product media gallery value
        $productMediaGalleryValue = $this->initializeProductMediaGalleryValue($this->prepareAttributes());
        $this->persistProductMediaGalleryValue($productMediaGalleryValue);

        // temporarily persist the image name
        $this->setParentImage($imagePath);
    }

    /**
     * Prepare the product media gallery value that has to be persisted.
     *
     * @return array The prepared product media gallery value attributes
     */
    protected function prepareAttributes()
    {

        try {
            // try to load the product SKU and map it the entity ID
            $parentId= $this->getValue(ColumnKeys::IMAGE_PARENT_SKU, null, array($this, 'mapParentSku'));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::IMAGE_PARENT_SKU), $e);
        }

        // load the store ID
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // load the value ID and the position counter
        $valueId = $this->getParentValueId();
        $position = $this->raisePositionCounter();

        // load the image label
        $imageLabel = $this->getValue(ColumnKeys::IMAGE_LABEL);

        // prepare the media gallery value
        return $this->initializeEntity(
            array(
                MemberNames::VALUE_ID    => $valueId,
                MemberNames::STORE_ID    => $storeId,
                MemberNames::ENTITY_ID   => $parentId,
                MemberNames::LABEL       => $imageLabel,
                MemberNames::POSITION    => $position,
                MemberNames::DISABLED    => 0
            )
        );
    }

    /**
     * Initialize the product media gallery value with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value attributes
     *
     * @return array The initialized product media gallery value
     */
    protected function initializeProductMediaGalleryValue(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the store ID of the actual row, or of the default store
     * if no store view code is set in the CSV file.
     *
     * @param string|null $default The default store view code to use, if no store view code is set in the CSV file
     *
     * @return integer The ID of the actual store
     * @throws \Exception Is thrown, if the store with the actual code is not available
     */
    protected function getRowStoreId($default = null)
    {
        return $this->getSubject()->getRowStoreId($default);
    }

    /**
     * Map's the passed SKU of the parent product to it's PK.
     *
     * @param string $parentSku The SKU of the parent product
     *
     * @return integer The primary key used to create relations
     */
    protected function mapParentSku($parentSku)
    {
        return $this->mapSkuToEntityId($parentSku);
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSkuToEntityId($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Set's the name of the created image.
     *
     * @param string $parentImage The name of the created image
     *
     * @return void
     */
    protected function setParentImage($parentImage)
    {
        $this->getSubject()->setParentImage($parentImage);
    }

    /**
     * Return's the name of the created image.
     *
     * @return string The name of the created image
     */
    protected function getParentImage()
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
    protected function isParentImage($image)
    {
        return $this->getParentImage() === $image;
    }

    /**
     * Return's the value ID of the created media gallery entry.
     *
     * @return integer The ID of the created media gallery entry
     */
    protected function getParentValueId()
    {
        return $this->getSubject()->getParentValueId();
    }

    /**
     * Return's the store for the passed store code.
     *
     * @param string $storeCode The store code to return the store for
     *
     * @return array The requested store
     * @throws \Exception Is thrown, if the requested store is not available
     */
    protected function getStoreByStoreCode($storeCode)
    {
        return $this->getSubject()->getStoreByStoreCode($storeCode);
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    protected function raisePositionCounter()
    {
        return $this->getSubject()->raisePositionCounter();
    }

    /**
     * Persist's the passed product media gallery value data.
     *
     * @param array $productMediaGalleryValue The product media gallery value data to persist
     *
     * @return void
     */
    protected function persistProductMediaGalleryValue($productMediaGalleryValue)
    {
        $this->getSubject()->persistProductMediaGalleryValue($productMediaGalleryValue);
    }
}
