<?php

/**
 * TechDivision\Import\Product\Media\Services\ProductMediaProcessor
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

namespace TechDivision\Import\Product\Media\Services;

/**
 * A SLSB providing methods to load product data using a PDO connection.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ProductMediaProcessor implements ProductMediaProcessorInterface
{

    /**
     * A PDO connection initialized with the values from the Doctrine EntityManager.
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * The action with the product media gallery CRUD methods.
     *
     * @var \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction
     */
    protected $productMediaGalleryAction;

    /**
     * The action with the product media gallery value CRUD methods.
     *
     * @var \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryValueAction
     */
    protected $productMediaGalleryValueAction;

    /**
     * The action with the product media gallery value to entity CRUD methods.
     *
     * @var \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryValueToEntityAction
     */
    protected $productMediaGalleryValueToEntityAction;

    /**
     * The action with the product media gallery video CRUD methods.
     *
     * @var \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryVideoAction
     */
    protected $productMediaGalleryVideoAction;

    /**
     * Set's the passed connection.
     *
     * @param \PDO $connection The connection to set
     *
     * @return void
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \PDO The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Turns off autocommit mode. While autocommit mode is turned off, changes made to the database via the PDO
     * object instance are not committed until you end the transaction by calling ProductProcessor::commit().
     * Calling ProductProcessor::rollBack() will roll back all changes to the database and return the connection
     * to autocommit mode.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.begintransaction.php
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits a transaction, returning the database connection to autocommit mode until the next call to
     * ProductProcessor::beginTransaction() starts a new transaction.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.commit.php
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rolls back the current transaction, as initiated by ProductProcessor::beginTransaction().
     *
     * If the database was set to autocommit mode, this function will restore autocommit mode after it has
     * rolled back the transaction.
     *
     * Some databases, including MySQL, automatically issue an implicit COMMIT when a database definition
     * language (DDL) statement such as DROP TABLE or CREATE TABLE is issued within a transaction. The implicit
     * COMMIT will prevent you from rolling back any other changes within the transaction boundary.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * Set's the action with the product media gallery CRUD methods.
     *
     * @param \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction $productMediaGalleryAction The action with the product media gallery CRUD methods
     *
     * @return void
     */
    public function setProductMediaGalleryAction($productMediaGalleryAction)
    {
        $this->productMediaGalleryAction = $productMediaGalleryAction;
    }

    /**
     * Return's the action with the product media gallery CRUD methods.
     *
     * @return \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction The action with the product media gallery CRUD methods
     */
    public function getProductMediaGalleryAction()
    {
        return $this->productMediaGalleryAction;
    }

    /**
     * Set's the action with the product media gallery valueCRUD methods.
     *
     * @param \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction $productMediaGalleryValueAction The action with the product media gallery value CRUD methods
     *
     * @return void
     */
    public function setProductMediaGalleryValueAction($productMediaGalleryValueAction)
    {
        $this->productMediaGalleryValueAction = $productMediaGalleryValueAction;
    }

    /**
     * Return's the action with the product media gallery valueCRUD methods.
     *
     * @return \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction The action with the product media gallery value CRUD methods
     */
    public function getProductMediaGalleryValueAction()
    {
        return $this->productMediaGalleryValueAction;
    }

    /**
     * Set's the action with the product media gallery value to entity CRUD methods.
     *
     * @param \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction $productMediaGalleryValueToEntityAction The action with the product media gallery value to entity CRUD methods
     *
     * @return void
     */
    public function setProductMediaGalleryValueToEntityAction($productMediaGalleryValueToEntityAction)
    {
        $this->productMediaGalleryValueToEntityAction = $productMediaGalleryValueToEntityAction;
    }

    /**
     * Return's the action with the product media gallery value to entity CRUD methods.
     *
     * @return \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction $productMediaGalleryAction The action with the product media gallery value to entity CRUD methods
     */
    public function getProductMediaGalleryValueToEntityAction()
    {
        return $this->productMediaGalleryValueToEntityAction;
    }

    /**
     * Set's the action with the product media gallery value video CRUD methods.
     *
     * @param \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction $productMediaGalleryValueVideoAction The action with the product media gallery value video CRUD methods
     *
     * @return void
     */
    public function setProductMediaGalleryValueVideoAction($productMediaGalleryValueVideoAction)
    {
        $this->productMediaGalleryValueVideoAction = $productMediaGalleryValueVideoAction;
    }

    /**
     * Return's the action with the product media gallery value video CRUD methods.
     *
     * @return \TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction The action with the product media gallery value video CRUD methods
     */
    public function getProductMediaGalleryValueVideoAction()
    {
        return $this->productMediaGalleryValueVideoAction;
    }

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array       $productMediaGallery The product media gallery data to persist
     * @param string|null $name                The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductMediaGallery($productMediaGallery, $name = null)
    {
        return $this->getProductMediaGalleryAction()->persist($productMediaGallery, $name);
    }

    /**
     * Persist's the passed product media gallery value data.
     *
     * @param array       $productMediaGalleryValue The product media gallery value data to persist
     * @param string|null $name                     The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductMediaGalleryValue($productMediaGalleryValue, $name = null)
    {
        $this->getProductMediaGalleryValueAction()->persist($productMediaGalleryValue, $name);
    }

    /**
     * Persist's the passed product media gallery value to entity data.
     *
     * @param array       $productMediaGalleryValuetoEntity The product media gallery value to entity data to persist
     * @param string|null $name                             The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductMediaGalleryValueToEntity($productMediaGalleryValuetoEntity, $name = null)
    {
        $this->getProductMediaGalleryValueToEntityAction()->persist($productMediaGalleryValuetoEntity, $name);
    }

    /**
     * Persist's the passed product media gallery value video data.
     *
     * @param array       $productMediaGalleryValueVideo The product media gallery value video data to persist
     * @param string|null $name                          The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductMediaGalleryValueVideo($productMediaGalleryValueVideo, $name = null)
    {
        $this->getProductMediaGalleryValueVideoAction()->persist($productMediaGalleryValueVideo, $name);
    }
}
