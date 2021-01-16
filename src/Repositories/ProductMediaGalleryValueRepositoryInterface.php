<?php

/**
 * TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueRepositoryInterface
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

namespace TechDivision\Import\Product\Media\Repositories;

use TechDivision\Import\Dbal\Repositories\RepositoryInterface;

/**
 * Repository implementation to load product media gallery value data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
interface ProductMediaGalleryValueRepositoryInterface extends RepositoryInterface
{

    /**
     * Load's the product media gallery value with the passed value/store/parent ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to load
     * @param string  $storeId  The store ID of the product media gallery value to load
     * @param string  $entityId The entity ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    public function findOneByValueIdAndStoreIdAndEntityId($valueId, $storeId, $entityId);
}
