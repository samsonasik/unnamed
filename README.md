
|   Info  |                                                   Badges                                                                                                                                                                                                                                                                                                                      |
|:-------:|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Versions | [![PHP minimum version](https://img.shields.io/badge/php-5.5.28-8892BF.svg)](https://php.net/) [![Unnamed version](https://img.shields.io/badge/Unnamed-v0.0.25-brightgreen.svg)](https://bitbucket.org/StanimirDim92/unnamed/overview) [![NPM minimum version](https://img.shields.io/badge/npm-v2.14.3-brightgreen.svg)](https://www.npmjs.com/) [![NodeJS minimum version](https://img.shields.io/badge/NodeJS-v0.12.17-brightgreen.svg)](https://www.nodejs.org/)                                                                                                                            |
| License | [![Unnamed license](https://img.shields.io/badge/license-MIT-blue.svg)](https://bitbucket.org/StanimirDim92/unnamed/raw/master/LICENSE)                                                                                                                                                                                                                                       |
| Testing | [![Codacy Badge](https://api.codacy.com/project/badge/grade/ea9be9b2dbdc44b787f05ec438ca08b1)](https://www.codacy.com/app/stanimirdim92/unnamed_2) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stanimirdim92/unnamed/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stanimirdim92/unnamed/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/Stanimirdim92/unnamed/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Stanimirdim92/unnamed/build-status/master) [![Build Status](https://travis-ci.org/Stanimirdim92/unnamed.svg)](https://travis-ci.org/Stanimirdim92/unnamed) [![Build Status](https://circleci.com/gh/Stanimirdim92/unnamed.svg?style=shield&circle-token=506bdf4bc3268408b050924c915fd3c988c5b0fd)](https://circleci.com/gh/Stanimirdim92/unnamed) [![Build Status](https://semaphoreci.com/api/v1/projects/9d3d2d3a-9692-4f73-ae2d-643fbdf256d6/596060/shields_badge.svg)](https://semaphoreci.com/stanimir/unnamed)                                                                                                                                                                                                           |
| Quality | [![Code Climate](https://codeclimate.com/github/Stanimirdim92/unnamed/badges/gpa.svg)](https://codeclimate.com/github/Stanimirdim92/unnamed) [![Dependency Status](https://www.versioneye.com/user/projects/5638c2d2e935640017000385/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5638c2d2e935640017000385) [![Dependency Status](https://www.versioneye.com/user/projects/5638c359e93564001a00036f/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5638c359e93564001a00036f) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/dbe0fb70-00d3-49ca-99b8-90dfcf688c2b/mini.png)](https://insight.sensiolabs.com/projects/dbe0fb70-00d3-49ca-99b8-90dfcf688c2b) [![StyleCI](https://styleci.io/repos/45452188/shield)](https://styleci.io/repos/45452188) |

### Unnamed CMS ###

* Building a new CMS on top of Zend Framework 2. Why? Because I can, it's fun and because the internet needs something better.
* Version 0.0.25
* Huge thanks to [Abdul Malik Ikhsan](https://samsonasik.wordpress.com/) a.k.a [samsonasik](https://twitter.com/samsonasik?lang=en) for being so helpful and patient

### Current features ###

* Installation script in 2 steps!
* Login with reset password.
* Registration.
* Menu creations for front end and back end with font icons attachements.
* Content creations - can be attached to menus. Multiple contents can be attached to one menu.
* Add administrators.
* Translations with interface and active cache.
* AJAX gallery with image upload and validation.
* Contact form.
* SEO optimized.
* Accesibility optimized.
* User page with account disable option
* Flexbox
* Zend OPcache
* Font Awesome icons support
* DoctrineORM

### Requirements ###

* PHP 5.5.28+
* Extensions:
    - mcrypt - for encryption
    - mbstring - for proper string encoding
    - intl for translations
    - GD2 - for image manipulation
    - fileinfo for image mimetype detection
    - pdo_X for database driver
    - Zend OPcache for caching
* Apache 2.4.4+ or nginx
* Zend Framework 2.5+ will be installed from script

### Requirements for development ###

* APPLICATION_ENV must be set to development via .htaccess
* ZendDeveloperTools (built in via composer)
* BjyProfiler (built in via composer)
* SanSessionToolbar (built in via composer)
* PHP CodeSniffer (built in via composer)
* PHP Unit (built in via composer)
* Codeception (built in via composer)
* PHP Coveralls (built in via composer)
* Gulp (requires NodeJS 0.12.7 or later). node_modules folder is not included. You will have to run **npm install** and **npm install gulp** from the root directory

### LICENSE ###

The files in this project are released under the MIT license. You can find a copy of this license in [LICENSE.md](https://bitbucket.org/StanimirDim92/unnamed/raw/master/LICENSE).

### Contacts ###

* For questions, send an email to stanimirdim92 at gmail dot com
