<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryUpdateObserver
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

use TechDivision\Import\Product\Media\Utils\MemberNames;

/**
 * Observer that creates/updates the product's media gallery information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryUpdateObserver extends MediaGalleryObserver
{

    /**
     * Initialize the product media gallery with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery attributes
     *
     * @return array The initialized product media gallery
     */
    protected function initializeProductMediaGallery(array $attr)
    {

        // load the value and the attribute ID
        $value = $attr[MemberNames::VALUE];
        $attributeId = $attr[MemberNames::ATTRIBUTE_ID];

        // query whether the product media gallery entity already exists or not
        if ($entity = $this->loadProductMediaGallery($attributeId, $value)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
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

        // load the value/entity ID
        $valueId = $attr[MemberNames::VALUE_ID];
        $entityId = $attr[MemberNames::ENTITY_ID];

        // query whether the product media gallery value to entity entity already exists or not
        if ($this->loadProductMediaGalleryValueToEntity($valueId, $entityId)) {
            return;
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the product media gallery with the passed attribute ID + value.
     *
     * @param integer $attributeId The attribute ID of the product media gallery to load
     * @param string  $value       The value of the product media gallery to load
     *
     * @return array The product media gallery
     */
    protected function loadProductMediaGallery($attributeId, $value)
    {
        return $this->getSubject()->loadProductMediaGallery($attributeId, $value);
    }

    /**
     * Load's the product media gallery with the passed value/entity ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to entity to load
     * @param integer $entityId The entity ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    protected function loadProductMediaGalleryValueToEntity($valueId, $entityId)
    {
        return $this->getSubject()->loadProductMediaGalleryValueToEntity($valueId, $entityId);
    }
}
