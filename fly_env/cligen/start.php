<?php
require_once 'generators/cva.gen.php';
require_once 'generators/md.gen.php';
require_once 'generators/sqlcreate.gen.php';
require_once 'generators/sqlcreatehand.gen.php';
require_once 'generators/classmd.gen.php';
require_once 'generators/registry.gen.php';
require_once 'helpers/create_cva.php';
require_once 'helpers/migration.php';
require_once 'helpers/patterns.php';
require_once 'helpers/drops.php';

define('FLY_ENV_CLI_PATH','fly_env'.DIRECTORY_SEPARATOR.'cligen');

function init_cmds($argc="", array $argv=[]) {
    array_shift($argv);
    $general_path = str_replace(FLY_ENV_CLI_PATH,'',__DIR__);
    if(!empty($argv)) {
        route_cli_commands($argc,$argv,$general_path);
    }
}

function route_cli_commands($argc, $argv,$general_path)
{   $argv_length = count($argv);
    $unshift_argvs = ['migrate_models'];
    $command_name = $argv[0];

    if(!in_array($command_name,$unshift_argvs)) array_shift($argv);

    switch($command_name) {
        case 'migrate_models':
            migrate_models($argv_length,$general_path);
        break;
        case 'migrate':
            migrate($argv,$general_path);
        break;
        case 'create_cv':
            create_controller_view($argv_length,$argv,$general_path);
        break;
        case 'create_c':
            create_controller($argv_length,$argv,$general_path);
        break;
        case 'create_v':
            create_view($argv_length,$argv,$general_path);
        break;
        case 'create_activity': case 'create_a':
            create_activity($argv_length,$argv,$general_path);
        break;
        case 'create_class':
            create_class($argv_length,$argv,$general_path);
        break;
        case 'drop_table':
            dropModelStore($argv,$general_path);
        break;
        case 'drop_db': case 'drop_database':
            dropModel($argv,$general_path);
        break;
        case 'make_model':
            make_model($argv,$general_path);
        break;
        default:
          echo PHP_EOL.">> fly-env: $argv[0]: command not found".PHP_EOL.PHP_EOL;
        break;
    }
}