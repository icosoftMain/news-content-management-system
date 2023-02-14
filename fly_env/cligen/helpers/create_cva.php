<?php 

function create_controller_view($argv_length,$argv,$general_path)
{
    try {
        if($argv_length === 2) {
            CVA_Gen::createControllerView($argv[0],$general_path);
            echo PHP_EOL."# Controller and View of '$argv[0]' was successfully Created.".PHP_EOL;
            echo "- Controller Name: ".CVA_Gen::controllerName().PHP_EOL;
            echo "- View Name: ".CVA_Gen::viewName().PHP_EOL.PHP_EOL;
        } else throw new Exception(">> fly-env: create_cv: command expects class name. HINT: create_cv [CLASS NAME HERE]");
    } catch(Exception $err) {
        echo PHP_EOL.$err->getMessage().PHP_EOL.PHP_EOL;
    }
}

function create_controller($argv_length,$argv,$general_path)
{
    try {
        if($argv_length === 2) {
            CVA_Gen::createController($argv[0],$general_path);
            echo PHP_EOL."# Controller of '$argv[0]' was successfully Created.".PHP_EOL;
            echo "- Controller Name: ".CVA_Gen::controllerName().PHP_EOL.PHP_EOL;
        } else throw new Exception(">> fly-env: create_c: command expects controller name. HINT: create_c [CONTROLLER NAME HERE]");
    } catch(Exception $err) {
        echo PHP_EOL.$err->getMessage().PHP_EOL.PHP_EOL;
    }
}

function create_view($argv_length,$argv,$general_path)
{
    try {
        if($argv_length === 2) {
            CVA_Gen::createView($argv[0],$general_path);
            echo PHP_EOL."# View of '$argv[0]' was successfully Created.".PHP_EOL;
            echo "- View Name: ".CVA_Gen::viewName().PHP_EOL.PHP_EOL;
        } else throw new Exception(">> fly-env: create_v: command expects view name. HINT: create_v [VIEW NAME HERE]");
    } catch(Exception $err) {
        echo PHP_EOL.$err->getMessage().PHP_EOL.PHP_EOL;
    }
}

function create_activity($argv_length,$argv,$general_path)
{
    try {
        if($argv_length === 2) {
            CVA_Gen::createActivity($argv[0],$general_path);
            echo PHP_EOL."# Activity of '$argv[0]' was successfully Created.".PHP_EOL;
            echo "- Activity Name: ".CVA_Gen::activityName().PHP_EOL.PHP_EOL;
        } else throw new Exception(">> fly-env: create_activity or create_a: command expects activity name.\nHINT: create_activity [ACTIVITY NAME HERE] or create_a [ACTIVITY NAME HERE]");
    } catch(Exception $err) {
        echo PHP_EOL.$err->getMessage().PHP_EOL.PHP_EOL;
    }
}

function create_class($argv_length,$argv,$general_path)
{
    try {
        if($argv_length === 2) {
            CVA_Gen::createClass($argv[0],$general_path);
            echo PHP_EOL."# Class of '$argv[0]' was successfully Created.".PHP_EOL;
            echo "- Class Name: ".CVA_Gen::actionClassName().PHP_EOL.PHP_EOL;
        } else throw new Exception(">> fly-env: create_class: command expects class name. HINT: create_class [CLASS NAME HERE]");
    } catch(Exception $err) {
        echo PHP_EOL.$err->getMessage().PHP_EOL.PHP_EOL;
    }
}