# Changelog
All notable changes to this project will be documented in this file.

## [1.3.0] - 2017-07-25

### Added
- support for auto_item for reader modules
- link templates for bootstrap sm and lg

## [1.2.18] - 2017-06-08

### Fixed
- composer.json for contao 4

## [1.2.17] - 2017-05-16

### Fixed
- old `heimrichhannot/contao-calendar_plus`, `heimrichhannot/contao-news_plus` modal behavior restored 

## [1.2.16] - 2017-05-12

### Fixed
- replace Inserttags within ajax modal 

## [1.2.15] - 2017-05-12

### Changed
- redirect non ajax links with `data-toggle="modal"` synchronous 

## [1.2.14] - 2017-05-12

### Changed
- invokation of useModal field

## [1.2.13] - 2017-05-09

### Fixed
- php 7 support

## [1.2.12] - 2017-04-28

### Changed
- varchar lengths to reduce db size

## [1.2.11] - 2017-03-24

## Fixed
- set back link for ajax modals from $_GET parameter location, provided by $.ajax from window.location.href

## [1.2.10] - 2017-03-24

## Fixed
- unset modal parameter for terminal42/contao-changelanguage navigation, currently no support for fallback language modal available 

### Fixed
- permission bug

## [1.2.9] - 2017-02-27

### Fixed
- permission bug

## [1.2.8] - 2016-12-14

### Added
- added new options to prevent closing of the modal window.

## [1.2.7] - 2016-11-21

### Fixed
- If page has no modal index page anyways and do not disable cache. This has been a major issue, as contao search index did not work anymore.
