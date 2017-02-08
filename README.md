# M2IF - Product Media Import

[![Latest Stable Version](https://img.shields.io/packagist/v/techdivision/import-product-media.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-media) 
 [![Total Downloads](https://img.shields.io/packagist/dt/techdivision/import-product-media.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-media)
 [![License](https://img.shields.io/packagist/l/techdivision/import-product-media.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-media)
 [![Build Status](https://img.shields.io/travis/techdivision/import-product-media/master.svg?style=flat-square)](http://travis-ci.org/techdivision/import-product-media)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/techdivision/import-product-media/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-product-media/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/techdivision/import-product-media/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-product-media/?branch=master)

## Introduction

This module provides the functionality to import the product media files. Actually only images
are supported.

## Configuration

In case that the [M2IF - Simple Console Tool](https://github.com/techdivision/import-cli-simple) 
is used, the funcationality can be enabled by adding the following snippets to the configuration 
file

```json
{
  "magento-edition": "CE",
  "magento-version": "2.1.2",
  "operation-name" : "replace",
  "installation-dir" : "/var/www/magento",
  "utility-class-name" : "TechDivision\\Import\\Utils\\SqlStatements",
  "database": { ... },
  "operations" : [
    {
      "name" : "replace",
      "subjects": [
        { ... },
        {
          "class-name": "TechDivision\\Import\\Product\\Media\\Subjects\\MediaSubject",
          "processor-factory" : "TechDivision\\Import\\Cli\\Services\\ProductMediaProcessorFactory",
          "utility-class-name" : "TechDivision\\Import\\Product\\Media\\Utils\\SqlStatements",
          "prefix": "media",
          "source-dir": "projects/sample-data/tmp",
          "target-dir": "projects/sample-data/tmp",
          "params" : [
            {
              "root-directory" : "/",
              "media-directory" : "/opt/appserver/webapps/magento2_ce212/pub/media/catalog/product",
              "images-file-directory" : "projects/sample-data/magento2-sample-data/pub/media/catalog/product"
            }
          ],
          "observers": [
            {
              "pre-import" : [
                "TechDivision\\Import\\Product\\Media\\Observers\\FileUploadObserver"
              ],
              "import": [
                "TechDivision\\Import\\Product\\Media\\Observers\\MediaGalleryObserver",
                "TechDivision\\Import\\Product\\Media\\Observers\\MediaGalleryValueObserver"
              ]
            }
          ]
        }
      ]
    },
    {
      "name" : "add-update",
      "subjects": [
        { ... },
        {
          "class-name": "TechDivision\\Import\\Product\\Media\\Subjects\\MediaSubject",
          "processor-factory" : "TechDivision\\Import\\Cli\\Services\\ProductMediaProcessorFactory",
          "utility-class-name" : "TechDivision\\Import\\Product\\Media\\Utils\\SqlStatements",
          "prefix": "media",
          "source-dir": "projects/sample-data/tmp",
          "target-dir": "projects/sample-data/tmp",
          "params" : [
            {
              "root-directory" : "/",
              "media-directory" : "/opt/appserver/webapps/magento2_ce212/pub/media/catalog/product",
              "images-file-directory" : "projects/sample-data/magento2-sample-data/pub/media/catalog/product"
            }
          ],
          "observers": [
            {
              "pre-import" : [
                "TechDivision\\Import\\Product\\Media\\Observers\\FileUploadObserver"
              ],
              "import": [
                "TechDivision\\Import\\Product\\Media\\Observers\\MediaGalleryUpdateObserver",
                "TechDivision\\Import\\Product\\Media\\Observers\\MediaGalleryValueUpdateObserver"
              ]
            }
          ]
        }
      ]
    }
  ]
}
```

# Missing Index

As the M2IF functionality differs from the Magento 2 standard, for performance reasons, it is 
necessary to manually add a missing index.

To do that, open a MySQL command line and enter the following SQL statement

```sql
mysql$ ALTER TABLE `catalog_product_entity_media_gallery` ADD INDEX `CATALOG_PRODUCT_ENTITY_MEDIA_GALLERY_VALUE` (`value`);
```

> This also improves performance of the Magento 2 standard import functionality, but not at
> same level as for M2IF.