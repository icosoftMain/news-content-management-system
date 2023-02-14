<?php namespace App\Models\ilapi_pro_cms\DS;
use FLY\DSource\DSource_Model;
use FLY\Libs\Request;

class DsSidePagesLevel extends DSource_Model {

	protected $levelId;

	protected $spId;

	protected $levelName;

	protected $lType;

	protected $item;

	public function __construct($levelId="",$spId="",$levelName="",$lType="",$item="") 
	{
    	parent::__construct($this);
		$this->levelId = $levelId;
		$this->spId = $spId;
		$this->levelName = $levelName;
		$this->lType = $lType;
		$this->item = $item;

    	$this->pk_names=[ 'levelId' ];
    	$this->fk_names=[ 'spId'=>'id' ];
	}

	static public function get($ids): DSource_Model
	{
		return self::get_by_ids($ids, new Self);
	}

	static public function all(): array
	{
		new Self;
		return self::all_records();
	}

	static public function count()
	{
    	return count(self::all());
	}

	static public function fetch($search_query, DSource_Model $ds_model = null) 
	{
    	$data_model = (
        	$ds_model !== null ? $ds_model :
        	( self::$currentModel !== null ? self::$currentModel: new Self )
    	);
		return self::get_fetch($search_query, $data_model);
	}

	protected function child_class(): string
	{
    	return __CLASS__;
	}
    
	static public function save_request(Request $request, DSource_Model $ds_model = null)
	{
    	$data_model = $ds_model !== null ? $ds_model : new Self;
    	return self::save_request_payload($data_model, $request);
	}

	static public function set_request(Request $request, DSource_Model $ds_model = null)
	{
    	$data_model = $ds_model !== null ? $ds_model : new Self;
    	return self::set_request_payload($data_model, $request);
	}

	static public function edit_request(Request $request, DSource_Model $ds_model = null)
	{
    	$data_model = $ds_model !== null ? $ds_model : new Self;
    	return self::edit_request_payload($data_model, $request);
	}

	static public function first_id()
	{
    	return self::get_first_id(new Self);
	}

	static public function first_record()
	{
    	new Self;
    	return self::get_first_record();
	}

	static public function last_id()
	{
    	return self::get_last_id(new Self);
	}

	static public function last_record()
	{
    	new Self;
    	return self::get_last_record();
	}

	protected function set_protocols()
	{
    	$this->init_protocols(
			/* host */
			'default',
			/* user */
			'default',
			/* password */
			'default',
			/* model */
			'default'
		);
	}
}