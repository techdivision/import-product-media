<?php

/**
 * TechDivision\Import\Product\Media\Utils\SqlStatements
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

namespace TechDivision\Import\Product\Media\Utils;

/**
 * Utility class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class SqlStatementKeys extends \TechDivision\Import\Product\Utils\SqlStatementKeys
{

    /**
     * The SQL statement to load an existing product media gallery by attribute ID + value.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY = 'product_media_gallery';

    /**
     * The SQL statement to load all existing product media galleries
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERIES = 'product_media_galleries';

    /**
     * The SQL statement to load an existing product media gallery entities by their SKU.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERIES_BY_SKU = 'product_media_galleries.by.sku';

    /**
     * The SQL statement to load an existing product media gallery by value/store/entity ID.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY_VALUE = 'product_media_gallery_value';

    /**
     * The SQL statement to load all existing product media gallery values.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY_VALUES = 'product_media_gallery_values';

    /**
     * The SQL statement to load an existing product media gallery value to entity by value/entity ID.
     *
     * @var string
     */
    const PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'product_media_gallery_value_to_entity';

    /**
     * The SQL statement to create a new product media gallery entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY = 'create.product_media_gallery';

    /**
     * The SQL statement to update an existing product media gallery entry.
     *
     * @var string
     */
    const UPDATE_PRODUCT_MEDIA_GALLERY = 'update.product_media_gallery';

    /**
     * The SQL statement to delete an existing product media gallery entry.
     *
     * @var string
     */
    const DELETE_PRODUCT_MEDIA_GALLERY = 'delete.product_media_gallery';

    /**
     * The SQL statement to create a new product media gallery value entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE = 'create.product_media_gallery_value';

    /**
     * The SQL statement to update an existing product media gallery value entry.
     *
     * @var string
     */
    const UPDATE_PRODUCT_MEDIA_GALLERY_VALUE = 'update.product_media_gallery_value';

    /**
     * The SQL statement to create a new product media gallery value to entity entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'create.product_media_gallery_value_to_entity';

    /**
     * The SQL statement to create a new product media gallery value vidoe entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE_VIDEO = 'create.product_media_gallery_value_video';
}
