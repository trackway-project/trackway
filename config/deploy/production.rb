############################################################
# All variables that are independent per env goes here
############################################################

role :app, %w{your_host}
role :web, %w{}
role :db,  %w{}

set :user, "your_ssh_user"
set :ssh_options, {
    keys: "~/.ssh/your_ssh_key_file",
    forward_agent: false,
    port: 22,
    auth_methods: %w(publickey),
    user: "your_ssh_user",
}

set :webserver_user,                    "your_webserver_user"
set :webserver_group,                   "your_webserver_group"
set :rails_env,                         "production"
set :deploy_to,                         "/var/www/"
set :domain,                            "your.domain.tld"
set :symfony_env_prod,                  "prod"
set :symfony_target_env,                "prod"
set :symfony_console_flags,             "--env=prod --no-debug"
set :symfony_taget_env_frontcontroller, "app.php"
set :scm_command,                       "/usr/local/bin/git"
set :composer_options,                  "--verbose --prefer-dist --optimize-autoloader"
set :ssh_options, {:keys => ["#{ENV['HOME']}/.ssh/your_ssh_key_file"] }
SSHKit.config.command_map[:composer] = "composer"

set :file_permission_exclude_files, [
    fetch(:deploy_to)+'repo',
    fetch(:deploy_to)+'shared/vendor',
    fetch(:deploy_to)+'current/vendor',
]