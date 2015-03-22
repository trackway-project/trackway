Master: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=master)](https://travis-ci.org/trackway-project/trackway) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/trackway-project/trackway/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/trackway-project/trackway/?branch=master)
Develop: [![Build Status](https://travis-ci.org/trackway-project/trackway.svg?branch=develop)](https://travis-ci.org/trackway-project/trackway) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/trackway-project/trackway/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/trackway-project/trackway/?branch=develop)

Trackway
========================
The simple on-premise open source time tracker.

Enjoy!

## Requirements
* PHP 5.5+
* Bower
* Composer
* gulp

## Installation
* `composer install`
* `php app/console doctrine:database:create`
* `php app/console doctrine:schema:create`
* `php app/console doctrine:fixtures:load`
* `sudo npm install`
* `bower install`
* `gulp`

## Reset Database
* `php app/console doctrine:database:drop --force`
* `php app/console doctrine:database:create`
* `php app/console doctrine:schema:create`
* `php app/console doctrine:fixtures:load -n`

## Development
* Changed composer.json? Run `composer update`
* Changed package.json? Run `npm update`
* Changed bower.json? Run `bower update`
* Changed anything in src/AppBundle/Resources/private? Run `gulp`
* Changed anything in src/AppBundle/Entity? Run `php app/console doctrine:schema:update`

## Vagrant and Provisioning
Take a look at https://github.com/trackway-project/trackway-vagrant

## Capistrano Deployment
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
