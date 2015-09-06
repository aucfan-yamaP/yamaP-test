<?php

class Index extends AppModel
{
	public $name = 'IndexModel';

	public $db;
	public $shift_collection;

	public function __construct()
	{
		$mongo = new MongoClient();
		$this->db = $mongo->selectDB('calendar');
		$this->shift_collection = $this->db->shift;
	}

	public function getMongoData($where_user,$where_date)
	{
		$ret = $this->shift_collection->find(array('status'=>0,'user'=>$where_user,'date'=>$where_date));
		$db_data = array();
		foreach($ret as $r)
		{
			$db_date = (array) $r['date'];
			$db_data[date('Y-m-j',$db_date['sec'])] = $r['shift'];
			$db_data[date('Y-n-j',$db_date['sec'])] = $r['shift'];
		}
		return $db_data;
	}
}
