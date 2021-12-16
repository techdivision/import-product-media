<?php

/**
 * TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueToEntityRepository
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Repositories;

use TechDivision\Import\Dbal\Collection\Repositories\AbstractRepository;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Media\Utils\SqlStatementKeys;

/**
 * Repository implementation to load product media gallery value to entity data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaGalleryValueToEntityRepository extends AbstractRepository implements ProductMediaGalleryValueToEntityRepositoryInterface
{

    /**
     * The prepared statement to load an existing product media gallery value to entity entity.
     *
     * @var \PDOStatement
     */
    protected $productMediaGalleryValueToEntityStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->productMediaGalleryValueToEntityStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY));
    }

    /**
     * Load's the product media gallery with the passed value/entity ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to entity to load
     * @param integer $entityId The entity ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function findOneByValueIdAndEntityId($valueId, $entityId)
    {

        // initialize the params
        $params = array(
            MemberNames::VALUE_ID  => $valueId,
            MemberNames::ENTITY_ID => $entityId
        );

        // load and return the prodcut media gallery value to entity with the passed value/entity ID
        $this->productMediaGalleryValueToEntityStmt->execute($params);
        return $this->productMediaGalleryValueToEntityStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
