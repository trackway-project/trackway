Master: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=master)](https://travis-ci.org/trackway-project/trackway)
Develop: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=develop)](https://travis-ci.org/trackway-project/trackway)

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

Enjoy!

## Thanks

### trackway foundation
* http://bootswatch.com
* http://getbootstrap.com/
* http://bower.io/
* http://gulpjs.com/
* http://www.vagrantup.com/
* http://www.ansible.com/
* http://nodejs.org/
* http://capistranorb.com/
* http://symfony.com/

### Contributors
* Mewes Kochheim
* Markus Wanjura
* Felix Peters

## Copyright and License

Code released under the MIT License.
