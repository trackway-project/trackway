#set :deploy_config_path, 'capistrano/deploy.rb'
#set :stage_config_path, 'capistrano/deploy'

# Load capistrano dependencies
require 'capistrano/setup'
require 'capistrano/deploy'
require 'capistrano/symfony'
require 'capistrano/copy_files'

# Loads custom tasks from `lib/capistrano/tasks' if you have any defined.
Dir.glob('lib/capistrano/tasks/*.rake').each { |r| import r }
