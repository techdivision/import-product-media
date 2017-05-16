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
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Observer that creates/updates the product's media gallery information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryObserver extends AbstractProductImportObserver
{

    /**
     * The media gallery attribute code.
     *
     * @var string
     */
    const ATTRIBUTE_CODE = 'media_gallery';

    /**
     * The ID of the parent product the media is related to.
     *
     * @var integer
     */
    protected $parentId;

    /**
     * The ID of the persisted media gallery entity.
     *
     * @var integer
     */
    protected $valueId;

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not, the image changed
        if ($this->isParentImage($this->getValue(ColumnKeys::IMAGE_PATH))) {
            return;
        }

        try {
            // try to load the product SKU and map it the entity ID
            $this->parentId = $this->getValue(ColumnKeys::IMAGE_PARENT_SKU, null, array($this, 'mapParentSku'));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::IMAGE_PARENT_SKU), $e);
        }

        // reset the position counter for the product media gallery value
        $this->resetPositionCounter();

        // initialize and persist the product media gallery
        $productMediaGallery = $this->initializeProductMediaGallery($this->prepareProductMediaGalleryAttributes());
        $this->valueId = $this->persistProductMediaGallery($productMediaGallery);

        // persist the product media gallery to entity data
        if ($productMediaGalleryValueToEntity = $this->initializeProductMediaGalleryValueToEntity($this->prepareProductMediaGalleryValueToEntityAttributes())) {
            $this->persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity);
        }

        // temporarily persist parent/value ID
        $this->setParentId($this->parentId);
        $this->setParentValueId($this->valueId);
    }

    /**
     * Prepare the product media gallery that has to be persisted.
     *
     * @return array The prepared product media gallery attributes
     */
    protected function prepareProductMediaGalleryAttributes()
    {

        // load the attribute ID of the media gallery EAV attribute
        $mediaGalleryAttribute = $this->getEavAttributeByAttributeCode(MediaGalleryObserver::ATTRIBUTE_CODE);
        $attributeId = $mediaGalleryAttribute[MemberNames::ATTRIBUTE_ID];

        // initialize the gallery data
        $disabled = 0;
        $mediaType = 'image';
        $image = $this->getValue(ColumnKeys::IMAGE_PATH_NEW);

        // initialize and return the entity
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID => $attributeId,
                MemberNames::VALUE        => $image,
                MemberNames::MEDIA_TYPE   => $mediaType,
                MemberNames::DISABLED     => $disabled
            )
        );
    }

    /**
     * Prepare the product media gallery value to entity that has to be persisted.
     *
     * @return array The prepared product media gallery value to entity attributes
     */
    protected function prepareProductMediaGalleryValueToEntityAttributes()
    {

        // initialize and return the entity
        return $this->initializeEntity(
            array(
                MemberNames::VALUE_ID  => $this->valueId,
                MemberNames::ENTITY_ID => $this->parentId
            )
        );
    }

    /**
     * Initialize the product media gallery with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery attributes
     *
     * @return array The initialized product media gallery
     */
    protected function initializeProductMediaGallery(array $attr)
    {
        return $attr;
    }

    /**
     * Initialize the product media gallery value to entity with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value to entity attributes
     *
     * @return array|null The initialized product media gallery value to entity, or NULL if the product media gallery value to entity already exists
     */
    protected function initializeProductMediaGalleryValueToEntity(array $attr)
    {
        return $attr;
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
     * Set's the value ID of the created media gallery entry.
     *
     * @param integer $parentValueId The ID of the created media gallery entry
     *
     * @return void
     */
    protected function setParentValueId($parentValueId)
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
    protected function mapSkuToEntityId($sku)
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
    protected function setParentId($parentId)
    {
        $this->getSubject()->setParentId($parentId);
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    protected function resetPositionCounter()
    {
        $this->getSubject()->resetPositionCounter();
    }

    /**
     * Return's the EAV attribute with the passed attribute code.
     *
     * @param string $attributeCode The attribute code
     *
     * @return array The array with the EAV attribute
     * @throws \Exception Is thrown if the attribute with the passed code is not available
     */
    protected function getEavAttributeByAttributeCode($attributeCode)
    {
        return $this->getSubject()->getEavAttributeByAttributeCode($attributeCode);
    }

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array $productMediaGallery The product media gallery data to persist
     *
     * @return string The ID of the persisted entity
     */
    protected function persistProductMediaGallery($productMediaGallery)
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
    protected function persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity)
    {
        $this->getSubject()->persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity);
    }
}
