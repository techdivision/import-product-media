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

use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Repositories\AbstractRepository;

/**
 * Repository implementation to load product media gallery data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaGalleryRepository extends AbstractRepository
{

    /**
     * The prepared statement to load an existing product media gallery entity.
     *
     * @var \PDOStatement
     */
    protected $productMediaGalleryStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // load the utility class name
        $utilityClassName = $this->getUtilityClassName();

        // initialize the prepared statements
        $this->productMediaGalleryStmt =
            $this->getConnection()->prepare($this->getUtilityClass()->find($utilityClassName::PRODUCT_MEDIA_GALLERY));
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
        $this->productMediaGalleryStmt->execute($params);
        return $this->productMediaGalleryStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
