Trackway
========================

The simple on-premise open source time tracker.

## Requirements
* PHP 5.5+
* composer
* gulp
* bower

## Installation
* composer install
* php app/console doctrine:database:create
* php app/console doctrine:schema:create
* sudo npm install
* bower install
* gulp

## Development
* After changes to composer.json: composer update
* After changes to package.json: npm update
* After changes to bower.json bower update
* After changes to src/AppBundle/Resources/public: gulp
* After changes to src/AppBundle/Entity: php app/console doctrine:schema:update

Enjoy!