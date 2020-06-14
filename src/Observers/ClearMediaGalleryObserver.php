<?php

/**
 * TechDivision\Import\Product\Media\Observers\ClearMediaGalleryObserver
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
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

/**
 * Observer that cleaned up a product's media gallery information.
 *
 * @author     Tim Wagner <t.wagner@techdivision.com>
 * @copyright  2020 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/import-product-media
 * @link       http://www.techdivision.com
 * @deprecated Sinces 25.0.0 use \TechDivision\Import\Product\Media\Observers\CleanUpMediaGalleryObserver instead
 */
class ClearMediaGalleryObserver extends CleanUpMediaGalleryObserver
{
}
