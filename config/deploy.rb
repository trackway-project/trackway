set :application, 'trackway'
set :repo_url, 'https://github.com/MewesK/trackway.git'

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
  before :finishing, 'symfony:assetic:dump'
end
