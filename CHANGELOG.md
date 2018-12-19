# Version 4.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 5.0.* version as dependency

# Version 3.0.0

## Bugfixes

* None

## Features

* Compatibility for Magento 2.3.x

# Version 2.0.0

## Bugfixes

* None

## Features

* Compatibility for Magento 2.2.x

# Version 1.0.0

## Bugfixes

* None

## Features

* Move PHPUnit test from tests to tests/unit folder for integration test compatibility reasons

# Version 1.0.0-beta14

## Bugfixes

* None

## Features

* Add missing interfaces for actions and repositories
* Replace class type hints for ProductMediaProcessor with interfaces

# Version 1.0.0-beta13

## Bugfixes

* None

## Features

* Configure DI to pass event emitter to subjects constructor

# Version 1.0.0-beta12

## Bugfixes

* None

## Features

* Refactored DI + switch to new SqlStatementRepositories instead of SqlStatements

# Version 1.0.0-beta11

## Bugfixes

* None

## Features

* Switch log level from debug to warning when images are removed from media gallery
* Append filename + linenumber for log message when a image will be remove from the media gallery

# Version 1.0.0-beta10

## Bugfixes

* None

## Features

* Refactor check if the flag to clean-up the media gallery has been set

# Version 1.0.0-beta9

## Bugfixes

* None

## Features

* Make functionality to clean-up media gallery when images has been removed configurable

# Version 1.0.0-beta8

## Bugfixes

* None

## Features

* Add functionality to clean-up media gallery when images has been removed
* Remove observer with file upload functionality (now in techdivision/import-product) library

# Version 1.0.0-beta7

## Bugfixes

* None

## Features

* Make image types configurable

# Version 1.0.0-beta6

## Bugfixes

* None

## Features

* Refactor filesystem handling

# Version 1.0.0-beta5

## Bugfixes

* None

## Features

* Refactor to optimize DI integration

# Version 1.0.0-beta4

## Bugfixes

* None

## Features

* Switch to new plugin + subject factory implementations

# Version 1.0.0-beta3

## Bugfixes

* None

## Features

* Use Robo for Travis-CI build process 
* Refactoring for new ConnectionInterface + SqlStatementsInterface

# Version 1.0.0-beta2

## Bugfixes

* None

## Features

* Dynamically load media image EAV attribute instead of using hardcoded ID

# Version 1.0.0-beta1

## Bugfixes

* None

## Features

* Integrate Symfony DI functionality

# Version 1.0.0-alpha16

## Bugfixes

* Remove FilesytemTrait use statement from MediaSubject to avoid PHP 5.6 PHPUnit error

## Features

* None

# Version 1.0.0-alpha15

## Bugfixes

* Remove FilesytemTrait use statement from FileUploadTrait to avoid PHP 5.6 PHPUnit error

## Features

* None

# Version 1.0.0-alpha14

## Bugfixes

* None

## Features

* Refactoring for DI integration

# Version 1.0.0-alpha13

## Bugfixes

* None

## Features

* Optimise error messages

# Version 1.0.0-alpha12

## Bugfixes

* None

## Features

* Switch to AbstractFileUploadObserver class to make component more generic

# Version 1.0.0-alpha11

## Bugfixes

* Fixed invald access on not configured params

## Features

* None

# Version 1.0.0-alpha10

## Bugfixes

* None

## Features

* Initialize media-directory and images-file-directory only if set to avoid unnecessary exceptions

# Version 1.0.0-alpha9

## Bugfixes

* None

## Features

* Make file upload functionality configurable

# Version 1.0.0-alpha8

## Bugfixes

* Fixed concatenation of image filenames to also support relative paths

## Features

* None

# Version 1.0.0-alpha7

## Bugfixes

* None

## Features

* ConfigurationKeys now extends from techdivision/import ConfigurationKeys

# Version 1.0.0-alpha6

## Bugfixes

* None

## Features

* Implement add/update operation

# Version 1.0.0-alpha5

## Bugfixes

* None

## Features

* Switch to new create/delete naming convention

# Version 1.0.0-alpha4

## Bugfixes

* None

## Features

* MediaSubject now extends AbstractProductSubject
* Add Robo.li composer dependeny + task configuration
* ProductMediaProcessorInterface now extends ProductProcessorInterface

# Version 1.0.0-alpha3

## Bugfixes

* None

## Features

* Fixed typos + move FileUploadObserver to Observers directory

# Version 1.0.0-alpha2

## Bugfixes

* None

## Features

* Refactoring to allow multiple prepared statements per CRUD processor instance

# Version 1.0.0-alpha1

## Bugfixes

* None

## Features

* Refactoring + Documentation to prepare for Github release