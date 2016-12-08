<?php

/**
 * TechDivision\Import\Product\Media\Utils\ColumnKeys
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

use TechDivision\Import\Product\Utils\ColumnKeys as FallbackColumnKeys;

/**
 * Utility class containing the CSV column names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ColumnKeys extends FallbackColumnKeys
{

    /**
     * This is a utility class, so protect it against direct
     * instantiation.
     */
    private function __construct()
    {
    }

    /**
     * This is a utility class, so protect it against cloning.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Name for the column 'base_image'.
     *
     * @var string
     */
    const BASE_IMAGE = 'base_image';

    /**
     * Name for the column 'base_image'.
     *
     * @var string
     */
    const BASE_IMAGE_LABEL = 'base_image_label';

    /**
     * Name for the column 'small_image'.
     *
     * @var string
     */
    const SMALL_IMAGE = 'small_image';

    /**
     * Name for the column 'small_image_label'.
     *
     * @var string
     */
    const SMALL_IMAGE_LABEL = 'small_image_label';

    /**
     * Name for the column 'thumbnail_image'.
     *
     * @var string
     */
    const THUMBNAIL_IMAGE = 'thumbnail_image';

    /**
     * Name for the column 'thumbnail_image_label'.
     *
     * @var string
     */
    const THUMBNAIL_IMAGE_LABEL = 'thumbnail_image_label';

    /**
     * Name for the column 'image_parent_sku'.
     *
     * @var string
     */
    const IMAGE_PARENT_SKU = 'image_parent_sku';

    /**
     * Name for the column 'image_path'.
     *
     * @var string
     */
    const IMAGE_PATH = 'image_path';

    /**
     * Name for the column 'image_path_new'.
     *
     * @var string
     */
    const IMAGE_PATH_NEW = 'image_path_new';

    /**
     * Name for the column 'image_label'.
     *
     * @var string
     */
    const IMAGE_LABEL = 'image_label';

    /**
     * Name for the column 'additional_images'.
     *
     * @var string
     */
    const ADDITIONAL_IMAGES = 'additional_images';

    /**
     * Name for the column 'additional_image_labels'.
     *
     * @var string
     */
    const ADDITIONAL_IMAGE_LABELS = 'additional_image_labels';
}
