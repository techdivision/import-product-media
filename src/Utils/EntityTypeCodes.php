<?php

/**
 * TechDivision\Import\Product\Media\Utils\EntityTypeCodes
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Utils;

/**
 * Utility class containing the entity type codes.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class EntityTypeCodes extends \TechDivision\Import\Product\Utils\EntityTypeCodes
{

    /**
     * Key for the product entity 'catalog_product_entity_media_gallery'.
     *
     * @var string
     */
    const CATALOG_PRODUCT_MEDIA_GALLERY = 'catalog_product_entity_media_gallery';

    /**
     * Key for the product entity 'catalog_product_entity_media_gallery_value'.
     *
     * @var string
     */
    const CATALOG_PRODUCT_MEDIA_GALLERY_VALUE = 'catalog_product_entity_media_gallery_value';

    /**
     * Key for the product entity 'catalog_product_entity_media_gallery_value_to_entity'.
     *
     * @var string
     */
    const CATALOG_PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'catalog_product_entity_media_gallery_value_to_entity';
}
