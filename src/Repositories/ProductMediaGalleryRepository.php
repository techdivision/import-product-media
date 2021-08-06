<?php

/**
 * TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryRepository
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
 * Repository implementation to load product media gallery data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaGalleryRepository extends AbstractFinderRepository implements ProductMediaGalleryRepositoryInterface
{
    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERIES));
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERY));
        $this->addFinder($this->finderFactory->createFinder($this, SqlStatementKeys::PRODUCT_MEDIA_GALLERIES_BY_SKU));
    }

    /**
     * Load's the product media gallery with the passed attribute ID + value.
     *
     * @param integer $attributeId The attribute ID of the product media gallery to load
     * @param string  $value       The value of the product media gallery to load
     *
     * @return array The product media gallery
     */
    public function findOneByAttributeIdAndValue($attributeId, $value)
    {

        // initialize the params
        $params = array(
            MemberNames::ATTRIBUTE_ID => $attributeId,
            MemberNames::VALUE        => $value
        );

        // load and return the prodcut media gallery with the passed attribute ID + value
        return $this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERY)->find($params);
    }

    /**
     * Load's the product media gallery entities with the passed SKU.
     *
     * @param string $sku The SKU to load the media gallery entities for
     *
     * @return array The product media gallery entities
     */
    public function findAllBySku($sku)
    {

        // initialize the params
        $params = array(MemberNames::SKU => $sku);

        // load and return the prodcut media gallery entities with the passed SKU
        foreach ($this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERIES_BY_SKU)->find($params) as $result) {
            yield $result;
        }
    }

    /**
     * @return array|null The country region data
     */
    public function findAll()
    {
        foreach ($this->getFinder(SqlStatementKeys::PRODUCT_MEDIA_GALLERIES)->find() as $result) {
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
        return EntityTypeCodes::CATALOG_PRODUCT_MEDIA_GALLERY;
    }
}
