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
* mysql {database} < groups.sql
* sudo npm install
* bower install
* gulp

## Vagrant and provisioning
Need a local dev box? Use vagrant and ansible!
Ansible is used for provisioning.
You need to have virtualbox, vagrant and ansible installed.

### The easiest way to get up and runnning is homebrew:
* ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
* brew update
* brew install caskroom/cask/brew-cask 
* brew cask install virtualbox vagrant
* brew install ansible
* vagrant up
* Open your browser with http://trackway.vagrant/ and have fun

## Development
* After changes to composer.json: composer update
* After changes to package.json: npm update
* After changes to bower.json bower update
* After changes to src/AppBundle/Resources/public: gulp
* After changes to src/AppBundle/Entity: php app/console doctrine:schema:update

Enjoy!

## Thanks

http://bootswatch.com
http://getbootstrap.com/
http://bower.io/
http://gulpjs.com/
https://www.vagrantup.com/
http://www.ansible.com/home
https://nodejs.org/
http://capistranorb.com/
http://symfony.com/

## Copyright and License

Code released under the MIT License.