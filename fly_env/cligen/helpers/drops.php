<?php

function createConnection($storeName, $modelName,$query,$app_reg)
{
    $sql = new SQLCreateModel($storeName,$query,[
        'host'     => $app_reg['list']['SERVER_CONFIG_HOSTS']['default'],
        'user'     => $app_reg['list']['SERVER_CONFIG_USERS']['default'],
        'password' => $app_reg['list']['SERVER_CONFIG_PASSWORDS']['default'],
        'database' => $modelName     
    ]);
    return $sql;
}

function dropStore($storeName,$modelName,$mainPath) 
{
    $storePath = "{$mainPath}app/models/{$modelName}/ds/{$storeName}.php";
    if(file_exists($storePath)) {
        unlink($storePath);
    }
}

function dropStores($modelName,$mainPath,$app_reg) 
{
    $path = "{$mainPath}app/models/{$modelName}/ds/";
    $payload = get_model_files($path);

    foreach($payload as $data) {
        if(file_exists($data)) unlink($data);
    }
    unmountModel($modelName,$mainPath,$app_reg);
}

function unmountModel($modelName,$mainPath,$app_reg)
{
    $modelRegs = $app_reg['list']['APP_MODELS'];
    foreach($modelRegs as $key => $value) {
        if(trim($value) === trim($modelName)) {
            unset($modelRegs[$key]);
        }
    }
    $app_reg['list']['APP_MODELS'] = $modelRegs;
    RegistryGen::setAppReg($app_reg,$mainPath);
    RegistryGen::createINI();
}

function dropModelStore($cmd,$mainPath)
{
    $cmd = isset($cmd[0]) ? explode('@',$cmd[0]): [];

    if(isset($cmd[0]) && count($cmd) === 2 && !empty($cmd[0]) && !empty($cmd[1])) {
        $storeName = $cmd[0];
        $modelName = $cmd[1];
        $app_reg = fetch_app_reg($mainPath.'app/.app_reg');
        if(validateAppRegFile($app_reg['list'],$mainPath)) {
            $query = "DROP TABLE ".$storeName;
            
            $flag = createConnection(
                $storeName,
                $modelName,
                $query,
                $app_reg
            )->drop_store();
            if($flag) dropStore($storeName,$modelName,$mainPath);
        }
    } else die("\n>> fly-env: drop_table: command expects a table name and it's corresponding database name.\nHINT: drop_table [TABLE NAME HERE]@[DATABASE NAME HERE]\n\n");
}

function dropModel($cmd, $mainPath) 
{
    if(isset($cmd[0]) && !empty($cmd[0])) {
        $modelName = $cmd[0];
        $app_reg = fetch_app_reg($mainPath.'app/.app_reg');
        if(validateAppRegFile($app_reg['list'],$mainPath)) {
            $query = "DROP DATABASE ".$modelName;
            
            $flag = createConnection(
                '',
                $modelName,
                $query,
                $app_reg
            )->drop_model();
            if($flag) dropStores($modelName,$mainPath,$app_reg);
        }
    } else die("\n>> fly-env: drop_db or drop_database: command expects a database name.\nHINT: drop_db [DATABASE NAME HERE] or drop_database [DATABASE NAME HERE]\n\n");
}