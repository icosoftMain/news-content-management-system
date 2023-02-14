<?php
class ClassModelGen {

   static public function createModels($dirName,$className,$fields,$pk,$fk,$config,$path)
   {
       self::saveClass($dirName,$className,$fields,$pk,$fk,$config,$path);
   }

   static private function saveClass(string $dirName,string $className,array $fields,array $pk,array $fk,array $config,string $path)
   {

        self::add_to_app_ds(
            $path,
            $className,
            self::setClassContent(
                self::setClassNamespace($dirName),
                self::setClass(
                    $className,
                    self::setClassMethods(
                        self::setClassFields($fields),
                        self::setClassParams($fields),
                        self::initClassFields($fields),
                        self::setPrimaryKeys($pk),
                        self::setForeignKeys($fk),
                        $config
                    )
                )
            )
        );
        
   }

    static private function add_to_app_ds($path,$className,string $full_class)
    {
        $path = $path.'/ds';
        $destdir = $path;
        $path .= "/{$className}.php";

        if(!file_exists($destdir)) {
            mkdir($destdir);
        }
        $class_file = fopen($path,'w');
        fwrite($class_file, $full_class);
        fclose($class_file);
    }

   static private function setClassContent($namespace,$class) 
   {
       return <<<CT
$namespace
$class
CT;
   }

   static private function setClassNamespace($dirName)
   {
        $namespace = '<?php namespace App\Models'.'\\'.$dirName.'\DS;';
        $namespace .= PHP_EOL."use FLY\DSource\DSource_Model;";
        $namespace .= PHP_EOL."use FLY\Libs\Request;".PHP_EOL;
        return $namespace;
   }

   static private function setClassFields(array $fields) 
   {
       $field_vars = "";
        foreach($fields as $field) {
            $field_vars .="\t".'protected $'.$field.';'.PHP_EOL.PHP_EOL;
        }
       return $field_vars;
   }
   
   static private function setClassParams(array $fields)
   {
        $params = "";
        $fieldLen = count($fields);
        $counter  = 1;
        foreach($fields as $field) {
            $params .='$'."{$field}=".'""';
            if($counter++ <> $fieldLen) $params .= ",";
        }
        return $params;
   }

   static private function initClassFields(array $fields)
   {
       $inits = "";
        foreach($fields as $field) {
            $inits .= "\t\t".'$this->'.$field.' = $'."{$field};".PHP_EOL;
        }
        return $inits;
   }
   
   static private function setPrimaryKeys($pks)
   {
       $keyStr = "";
       $fieldLen = count($pks);
       $counter  = 1;
       foreach($pks as $key => $refKey) {
           $keyStr .= "'{$refKey}'";
           if($counter++ <> $fieldLen) $keyStr .= ",";
       }
       return '$this->pk_names=[ '.$keyStr.' ];';
   }

   static private function setForeignKeys($fks)
   {
       $keyStr = "";
       $fieldLen = count($fks);
       $counter  = 1;
       foreach($fks as $key => $refKey) {
           $keyStr .= "'{$key}'=>'{$refKey}'";
           if($counter++ <> $fieldLen) $keyStr .= ",";
       }
       return $keyStr <> "" ?'$this->fk_names=[ '.$keyStr.' ];' : "";
   }

   static private function assign_protocols($config)
   {
       $set  = '$this->init_protocols('.PHP_EOL;
       $set .= "\t\t\t/* host */\n\t\t\t'{$config['host']}',".PHP_EOL;
       $set .= "\t\t\t/* user */\n\t\t\t'{$config['username']}',".PHP_EOL;
       $set .= "\t\t\t/* password */\n\t\t\t'{$config['password']}',".PHP_EOL;
       $set .= "\t\t\t/* model */\n\t\t\t'{$config['database']}'".PHP_EOL;
       $set .= "\t\t".');'; 

       return <<<PTC
\t{$set}
PTC;

   }

   static private function setClass($className, $methods)
   {
       return "class {$className} extends DSource_Model {".PHP_EOL.$methods.PHP_EOL."}";
   }

    static private function setClassMethods($fields,$params,$setters,$pks,$fks,$config)
    {
        $gets_param = '$ids';
        $ds_model_var = '$ds_model';
        $data_model = '$data_model';
        $query = '$search_query';
        $request = '$request';
        $protocols = self::assign_protocols($config);
        $self = '$this';
        $currentModel = 'self::$currentModel';
        return <<<MTH

{$fields}\tpublic function __construct($params) 
\t{
    \tparent::__construct($self);
$setters
    \t{$pks}
    \t{$fks}
\t}

\tstatic public function get($gets_param): DSource_Model
\t{
	\treturn self::get_by_ids($gets_param, new Self);
\t}

\tstatic public function all(): array
\t{
	\tnew Self;
	\treturn self::all_records();
\t}

\tstatic public function count()
\t{
    \treturn count(self::all());
\t}

\tstatic public function fetch($query, DSource_Model $ds_model_var = null) 
\t{
    \t{$data_model} = (
        \t{$ds_model_var} !== null ? {$ds_model_var} :
        \t( $currentModel !== null ? $currentModel: new Self )
    \t);
	\treturn self::get_fetch($query, $data_model);
\t}

\tprotected function child_class(): string
\t{
    \treturn __CLASS__;
\t}
    
\tstatic public function save_request(Request $request, DSource_Model $ds_model_var = null)
\t{
    \t{$data_model} = {$ds_model_var} !== null ? {$ds_model_var} : new Self;
    \treturn self::save_request_payload($data_model, $request);
\t}

\tstatic public function set_request(Request $request, DSource_Model $ds_model_var = null)
\t{
    \t{$data_model} = {$ds_model_var} !== null ? {$ds_model_var} : new Self;
    \treturn self::set_request_payload($data_model, $request);
\t}

\tstatic public function edit_request(Request $request, DSource_Model $ds_model_var = null)
\t{
    \t{$data_model} = {$ds_model_var} !== null ? {$ds_model_var} : new Self;
    \treturn self::edit_request_payload($data_model, $request);
\t}

\tstatic public function first_id()
\t{
    \treturn self::get_first_id(new Self);
\t}

\tstatic public function first_record()
\t{
    \tnew Self;
    \treturn self::get_first_record();
\t}

\tstatic public function last_id()
\t{
    \treturn self::get_last_id(new Self);
\t}

\tstatic public function last_record()
\t{
    \tnew Self;
    \treturn self::get_last_record();
\t}

\tprotected function set_protocols()
\t{
    {$protocols}
\t}
MTH;
    }
}