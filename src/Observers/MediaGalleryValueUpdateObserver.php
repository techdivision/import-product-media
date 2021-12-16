<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryValueUpdateObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\MemberNames;

/**
 * Observer that creates/updates the product's media gallery value information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryValueUpdateObserver extends MediaGalleryValueObserver
{

    /**
     * Initialize the product media gallery value with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value attributes
     *
     * @return array The initialized product media gallery value
     */
    protected function initializeProductMediaGalleryValue(array $attr)
    {

        // load the value/store/parent ID
        $valueId = $attr[MemberNames::VALUE_ID];
        $storeId = $attr[MemberNames::STORE_ID];
        $entityId = $attr[MemberNames::ENTITY_ID];

        // query whether the product media gallery value already exists or not
        if ($entity = $this->loadProductMediaGalleryValue($valueId, $storeId, $entityId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the product media gallery value with the passed value/store/parent ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to load
     * @param string  $storeId  The store ID of the product media gallery value to load
     * @param string  $entityId The entity ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    protected function loadProductMediaGalleryValue($valueId, $storeId, $entityId)
    {
        return $this->getProductMediaProcessor()->loadProductMediaGalleryValue($valueId, $storeId, $entityId);
    }
}
