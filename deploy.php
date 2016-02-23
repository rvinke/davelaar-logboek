<?php

// All Deployer recipes are based on `recipe/common.php`.
require_once 'recipe/common.php';

//set('shared_dirs', ['public/documenten']);
//set('writable_dirs', ['public/documenten', 'public/.cache']);

// Define a server for deployment.
// Let's name it "prod" and use port 22.
server('acceptatie', 'mijn-boetiek.nl', 22)
    ->user('logboek')
    ->password('rvmmdnw1')
    ->stage('acceptatie')
    ->env('deploy_path', '/home/logboek/public_html') // Define the base path to deploy your project to.
    ->env('branch', 'acceptatie');

server('productie', 'site.davelaar.ronaldvinke.nl', 22)
    ->user('logboek')
    ->password('rvmmdnw1')
    ->stage('productie')
    ->env('deploy_path', '/home/logboek/public_html') // Define the base path to deploy your project to.
    ->env('branch', 'master');

// Specify the repository from which to download your project's code.
// The server needs to have git installed for this to work.
// If you're not using a forward agent, then the server has to be able to clone
// your project from this repository.
set('repository', 'git@codebasehq.com:ronald-vinke-webontwikkeling/davelaar-logboek/logboek.git');

/**
 * Shared dirs
 */
set('shared_dirs', [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'public/documenten',
    'public/.cache',
]);
/**
 * Shared Files
 */
set('shared_files', ['.env']);
/**
 * Chmod stuff
 */
set('writable_dirs', ['storage', 'vendor', 'public/documenten', 'public/.cache']);


task('test', function () {
    runLocally('cd /vagrant/logboek && php vendor/bin/phpunit');
});

/**
 * Run migrations
 */
task('migration', function() {
    run('php {{release_path}}/artisan migrate --force');
})->desc('Artisan migrations');
/**
 * Main task (deploy)
 */
task('deploy', [
    'test',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:writable',
    'deploy:shared',
    'deploy:symlink',
    'migration',
    'cleanup'
])->desc('Deploy the app');
after('deploy', 'success');