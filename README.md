Master: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=master)](https://travis-ci.org/trackway-project/trackway)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/trackway-project/trackway/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/trackway-project/trackway/?branch=master)
Develop: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=develop)](https://travis-ci.org/trackway-project/trackway)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/trackway-project/trackway/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/trackway-project/trackway/?branch=develop)

Trackway
========================

The simple on-premise open source time tracker.

Enjoy!

## Requirements
* PHP 5.5+
* composer
* gulp
* bower

## Installation
* composer install
* php app/console doctrine:database:create
* php app/console doctrine:schema:create
* php app/console doctrine:fixtures:load
* sudo npm install
* bower install
* gulp

## Reset Database
* php app/console doctrine:database:drop --force
* php app/console doctrine:database:create
* php app/console doctrine:schema:create
* php app/console doctrine:fixtures:load -n

## Development
* After changes to composer.json: composer update
* After changes to package.json: npm update
* After changes to bower.json bower update
* After changes to src/AppBundle/Resources/public: gulp
* After changes to src/AppBundle/Entity: php app/console doctrine:schema:update

## Vagrant and provisioning
Take a look at https://github.com/trackway-project/trackway-vagrant

## Capistrano deployment
Take a look at https://github.com/trackway-project/trackway-deploy

## Thanks

* http://bootswatch.com
* http://bower.io/
* http://getbootstrap.com/
* http://gulpjs.com/
* http://nodejs.org/
* http://symfony.com/

## Contributors
* Mewes Kochheim
* Markus Wanjura
* Felix Peters

## Copyright and License

Code released under the MIT License.
