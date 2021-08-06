<?php

/**
 * TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueRepository
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

use TechDivision\Import\Dbal\Collection\Repositories\AbstractFinderRepository;
use TechDivision\Import\Product\Media\Utils\EntityTypeCodes;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Media\Utils\SqlStatementKeys;

/**
 * Repository implementation to load product media gallery value data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaGalleryValueRepository extends AbstractFinderRepository implements ProductMediaGalleryValueRepositoryInterface
{

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {
        // initialize the prepared statements
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE));
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUES));
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
    public function findOneByValueIdAndStoreIdAndEntityId($valueId, $storeId, $entityId)
    {

        // initialize the params
        $params = array(
            MemberNames::VALUE_ID  => $valueId,
            MemberNames::STORE_ID  => $storeId,
            MemberNames::ENTITY_ID => $entityId
        );

        // load and return the prodcut media gallery value with the passed value/store/parent ID
        return $this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE)->find($params);
    }

    /**
     * @return array|null The country region data
     */
    public function findAll()
    {
        foreach ($this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUES)->find() as $result) {
            yield $result;
        }
    }

    /**
     * Return's the primary key name of the entity.
     *
     * @return string The name of the entity's primary key
     */
    public function getPrimaryKeyName()
    {
        return MemberNames::VALUE_ID;
    }

    /**
     * Return's the finder's entity name.
     *
     * @return string The finder's entity name
     */
    public function getEntityName()
    {
        return EntityTypeCodes::CATALOG_PRODUCT_MEDIA_GALLERY_VALUE;
    }
}
