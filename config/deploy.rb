# config valid only for Capistrano 3.3.5
lock '3.3.5'

set :application, 'trackway'
set :scm, :rsync
set :repo_url, "."

set :rsync_options, %w[
  --recursive --delete --delete-excluded
  --exclude .git*
  --exclude vagrant
  --exclude .vagrant
  --exclude projectfiles/
  --exclude Vagrantfile
  --exclude Capfile
  --exclude Gemfile
  --exclude Gemfile.lock
  --exclude .gitattributes
  --exclude .gitignore
  --exclude .capistrano
  --exclude README.md
  --exclude ./config*
  --exclude .travis.yml
  --exclude .idea
  --exclude ansible
  --exclude package.json
  --exclude bower.json
  --exclude gulpfile.js
  --exclude bower_components
  --exclude node_modules
]

# Default branch is :master
set :branch, ENV['branch'] || (ask :branch, proc { `echo master`.chomp }.call)

set :deploy_to,                 fetch(:deploy_to)
set :format,                    :pretty
set :log_level,                 :debug
set :pty,                       true
set :keep_releases,             5
set :permission_method,         :chmod
set :use_set_permissions,       false

set :symfony_env,               fetch(:symfony_target_env)
set :app_path,                  "app"
set :web_path,                  "web"
set :log_path,                  fetch(:app_path) + "/logs"
set :cache_path,                fetch(:app_path) + "/cache"
set :app_config_path,           fetch(:app_path) + "/config"
set :controllers_to_clear,      ["app_*.php"]

set :linked_files,              [fetch(:app_path)+"/config/parameters.yml"]
set :linked_dirs,               [fetch(:log_path)]
set :copy_files,                ["vendor"]

set :symfony_console_flags,     "--no-debug"
set :assets_install_path,       fetch(:web_path)
set :assets_install_flags,      '--symlink'
set :assetic_dump_flags,        ''
set :composer_install_flags,    '--no-interaction --prefer-dist --optimize-autoloader'
set :symfony_console_flags,     fetch(:symfony_console_flags)

fetch(:default_env).merge!(symfony_env: fetch(:symfony_env))

namespace :deploy do

  task :precompile do
    Dir.chdir fetch(:build_dir) do
      system "npm install"
      system "bower install"
      system "gulp"
    end
  end

  before :finishing, 'symfony:assetic:dump'
end

after "rsync:stage", "deploy:precompile"
