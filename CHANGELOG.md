# Changelog
All notable changes to this project will be documented in this file.

## [1.7.3] - 2019-04-05

#### Fixed
- script modal issue

## [1.7.2] - 2019-04-02

#### Fixed
- do not redirect non ajax modal urls if modal already exists on current page (this will prevent to not show custom modals)

## [1.7.1] - 2019-04-02

#### Fixed
- asset js handling improved (performance and redundancy)

## [1.7.0] - 2019-03-19

#### Added
- basic bootstrap 4 support

## [1.6.0] - 2018-11-28

#### Added
- TL_COMPONENT support in order to disable js, css assets on demand

## [1.5.0] - 2018-06-21

#### Added
* option to add get parameter from current url to modal url

## [1.4.1] - 2018-03-26

#### Changed
- revoked set page title on ajax

## [1.4.0] - 2018-03-12

#### Changed
- on hide/show modal set proper page title

## [1.3.10] - 2018-03-05

#### Changed
- added minified version of js

## [1.3.9] - 2018-02-23

### Fixed
- `Teaser::generateModalTeaserLink()` check if teaser is of type modal

## [1.3.8] - 2018-02-16

### Fixed
- toggleVisibility

## [1.3.7] - 2018-02-16

### Added
- `tl_modal` index on `alias`

### Fixed
- insertag model caching implemented to reduce database queries

## [1.3.6] - 2017-11-24

### Fixed
- order issue

## [1.3.5] - 2017-10-11

### Fixed
- unchecked keepUrlParams not removed get params

## [1.3.4] - 2017-10-06

### Fixed
- modal show on undefined element

## [1.3.3] - 2017-07-31

### Fixed
- workaround for job market module

## [1.3.2] - 2017-07-27

### Fixed
- published field

### Added
- option for keeping GET params

## [1.3.1] - 2017-07-25

### Fixed
- enhanced notes

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
