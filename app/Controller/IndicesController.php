<?php
class IndicesController extends AppController {
	private $config;

	private $view_only = true;
	private $cookie_auth_user;

	private $date_info = array();
	private $holiday_array = array();
	private $season_info = array();

	public function beforeFilter()
	{
		if(!isset($config)) $this->config = Configure::read();
	}

	public function index()
	{
		$this->view_only = false;
		return $this->setAction('top');
	}

	public function top()
	{
		if(!$this->view_only)
		{
			$this->auth();
		}

		$this->makeDateData();
		$this->makeDaysDaysOfBeforeAndAfter();
		$this->makeHolidayArray();
		$this->getSeason();

		$calendar_main = array();
		$calendar_row_count = 0;
		$calendaer_last_date = '';
		foreach($this->date_info['calendar_before_month_days'] as $key => $val)
		{
		    $calendar_main[$calendar_row_count][$key]['type'] = 'before';
		    $calendar_main[$calendar_row_count][$key]['day'] = $val;
		    $calendar_main[$calendar_row_count][$key]['day_full'] = $this->date_info['before_y'].'-'.$this->date_info['before_n'].'-'.$val;
		}
		for($i=1;$i<=$this->date_info['today_lj'];$i++)
		{
		    $calendar_main[$calendar_row_count][date('w',strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-'.$i))]['type'] = 'main';
		    $calendar_main[$calendar_row_count][date('w',strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-'.$i))]['day'] = $i;
		    $calendar_main[$calendar_row_count][date('w',strtotime($this->date_info['today_y'].'-'.$this->date_info['today_n'].'-'.$i))]['day_full'] = $this->date_info['today_y'].'-'.$this->date_info['today_n'].'-'.$i;
		    $calendaer_last_date = $this->date_info['today_y'].'-'.$this->date_info['today_m'].'-'.$i;
		    if(date('w',strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-'.$i)) == 6) $calendar_row_count++;
		}
		foreach($this->date_info['calendar_after_month_days'] as $key => $val)
		{
		    $calendar_main[$calendar_row_count][$key]['type'] = 'after';
		    $calendar_main[$calendar_row_count][$key]['day'] = $val;
		    $calendar_main[$calendar_row_count][$key]['day_full'] = $this->date_info['after_y'].'-'.$this->date_info['after_n'].'-'.$val;
		    $calendaer_last_date = $this->date_info['after_y'].'-'.$this->date_info['after_n'].'-'.$val;
		}

		$db_data = $this->getDbData($calendar_main,$calendaer_last_date);

		$this->setDateInfo();
		$this->set(array(
			'view_only' => $this->view_only,
			'calendar_main' => $calendar_main,
			'holiday_array' => $this->holiday_array,
			'season' => $this->season_info['name'],
			'season_no' => $this->season_info['no'],
			'db_data' => $db_data,
		));
		return;
	}

	public function ajax()
	{
		$this->auth();
		$shift = $this->request->data('shift');
		$target_date = $this->request->data('date');
		$del_flg = $this->request->data('del');
		$ret = '';
		if(!isset($this->cookie_auth_user)) exit;
		$user = $this->cookie_auth_user;

		$mongo = new MongoClient();
		$db = $mongo->selectDB('calendar');
		$shift_collection = $db->shift;
		$select_query = array('date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0,'user'=>$user);
		$ret_count = $shift_collection->count($select_query);
		if($ret_count == 0)
		{
			$ret = $shift_collection->insert(array('_id'=>$shift_collection->count()+1,'shift'=>$shift,'user'=>$user,'date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0,'created_at'=>new MongoDate(),'updated_at'=>new MongoDate()));
		}
		if($ret_count >= 1)
		{
			if($del_flg != 'del')
			{
				$ret = $shift_collection->update($select_query,array('$set' => array('shift'=>$shift,'date'=>new MongoDate(strtotime($target_date.' 00:00:00 +0900')),'status'=>0,'updated_at'=>new MongoDate())));
			} else {
				$ret = $shift_collection->update($select_query,array('$set' => array('status'=>9,'updated_at'=>new MongoDate())));
				echo $del_flg;
				exit;
			}
		}
		echo (is_array($ret))? '1':'';
		exit;
	}

	private function auth()
	{
		$auth['id'][] = 'mao';
		$auth['id'][] = 'ryota';
		$auth['id'][] = 'test';

		$auth['pass']['mao'] = 'ryota';
		$auth['pass']['ryota'] = 'mao';
		$auth['pass']['test'] = 'test';

		if($this->request->query('logout') == 1)
		{
			unset($_SERVER['PHP_AUTH_USER']);
			unset($_SERVER['PHP_AUTH_PW']);
			setcookie('r_m_33','',0,'/');
			setcookie('r_m_33_u','',0,'/');
			header('Location: ?date=');
			exit;
		}
		if(@$_COOKIE['r_m_33'] !== '1')
		{
			if(!isset($_SERVER['PHP_AUTH_USER']))
			{
				header('WWW-Authenticate: Basic realm="Private Page"');
				header('HTTP/1.0 401 Unauthorized');

				die('このページを見るにはログインが必要です');
			} else {
				if (!in_array($_SERVER['PHP_AUTH_USER'],$auth['id'])
				|| $_SERVER['PHP_AUTH_PW'] != $auth['pass'][$_SERVER['PHP_AUTH_USER']]){

					header('WWW-Authenticate: Basic realm="Private Page"');
					header('HTTP/1.0 401 Unauthorized');

					die('このページを見るにはログインが必要です');
				}
			}
			setcookie('r_m_33','1',strtotime('today + 6 month'),'/');
			setcookie('r_m_33_u',$_SERVER['PHP_AUTH_USER'],strtotime('today + 6 month'),'/');
		}
		if(@$_COOKIE['r_m_33'] == '1')
		{
			setcookie('r_m_33',$_COOKIE['r_m_33'],strtotime('today + 6 month'),'/');
			setcookie('r_m_33_u',$_COOKIE['r_m_33_u'],strtotime('today + 6 month'),'/');
		}
		$this->cookie_auth_user = (isset($_COOKIE['r_m_33_u']))? $_COOKIE['r_m_33_u']:'mao';
		return;
	}

	private function makeDateData()
	{
		$this->date_info['this_day'] = ($this->request->query('date') && strtotime($this->request->query('date')) > strtotime('2010-01-01'))? $this->request->query('date'):date('Y-m-d');
		$this->date_info['this_day_strtotime'] = strtotime($this->date_info['this_day']);
		$this->date_info['real_today'] = date('Y-n-j');

		$this->date_info['today_y'] = date('Y',$this->date_info['this_day_strtotime']);
		$this->date_info['today_m'] = date('m',$this->date_info['this_day_strtotime']);
		$this->date_info['today_d'] = date('d',$this->date_info['this_day_strtotime']);
		$this->date_info['today_n'] = date('n',$this->date_info['this_day_strtotime']);
		$this->date_info['today_j'] = date('j',$this->date_info['this_day_strtotime']);

		$minus_1_month = strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-01 -1 month');
		$this->date_info['before_y'] = date('Y',$minus_1_month);
		$this->date_info['before_m'] = date('m',$minus_1_month);
		$this->date_info['before_d'] = date('d',$minus_1_month);
		$this->date_info['before_n'] = date('n',$minus_1_month);
		$this->date_info['before_j'] = date('j',$minus_1_month);

		$plus_1_month = strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-01 +1 month');
		$this->date_info['after_y'] = date('Y',$plus_1_month);
		$this->date_info['after_m'] = date('m',$plus_1_month);
		$this->date_info['after_d'] = date('d',$plus_1_month);
		$this->date_info['after_n'] = date('n',$plus_1_month);
		$this->date_info['after_j'] = date('j',$plus_1_month);

		$minus_1_day = strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-01 -1 day');
		$last_day = strtotime($this->date_info['after_y'].'-'.$this->date_info['after_m'].'-01 -1 day');
		$this->date_info['before_l'] = date('d',$minus_1_day);
		$this->date_info['today_l'] = date('d',$last_day);
		$this->date_info['before_lj'] = date('j',$minus_1_day);
		$this->date_info['today_lj'] = date('j',$last_day);
		$this->date_info['before_lw'] = date('w',$minus_1_day);
		$this->date_info['today_lw'] = date('w',$last_day);

		$this->date_info['today_month_first_weekend'] = date('w',strtotime($this->date_info['today_y'].'-'.$this->date_info['today_m'].'-01'));
		$this->date_info['today_month_first_weekend_str'] = $this->config['WEEKEND'][$this->date_info['today_month_first_weekend']];
		return;
	}

	private function makeDaysDaysOfBeforeAndAfter()
	{
		$this->date_info['calendar_before_month_days'] = array();
		for($i = 0;$i<$this->date_info['today_month_first_weekend'];$i++)
		{
		    $this->date_info['calendar_before_month_days'][$i] = $this->date_info['before_lj']-($this->date_info['today_month_first_weekend']-1-$i);
		}

		$this->date_info['calendar_after_month_days'] = array();
		for($i = $this->date_info['today_lw'];$i<6;$i++)
		{
		    $this->date_info['calendar_after_month_days'][$this->date_info['today_lw']+(count($this->date_info['calendar_after_month_days'])+1)] = count($this->date_info['calendar_after_month_days'])+1;
		}
		return;
	}

	private function makeHolidayArray()
	{
		$filenames = array();
		$filenames['today'] = WWW_ROOT.'files/json/'.$this->date_info['today_y'].$this->date_info['today_m'].'.js';
		$filenames['before'] = WWW_ROOT.'files/json/'.$this->date_info['before_y'].$this->date_info['before_m'].'.js';
		$filenames['after'] = WWW_ROOT.'files/json/'.$this->date_info['after_y'].$this->date_info['after_m'].'.js';

		$jsons = array();
		$handles = array();
		foreach($filenames as $when => $filename)
		{
		    $jsons[$when] = json_decode('['.file_get_contents($filename,true).']',true);
		}

		foreach ($jsons as $json_ret)
		{
		    foreach($json_ret as $json_val)
		    {
		        if(!strlen($json_val['jHoliday'])) continue;
		        if($json_val['jHoliday'] == '振替') $json_val['jHoliday'] .= '休日';
		        $this->holiday_array[$json_val['jYear'].'-'.$json_val['jMonth'].'-'.$json_val['jDay']] = $json_val['jHoliday'];
		    }
		}
		unset($jsons);
		return;
	}

	private function getSeason()
	{
		$this->season_info['name'] = 'season';
		$this->season_info['no'] = '';
		$this->season_info['name'] .= ' '.$this->config['SEASON'][$this->date_info['today_n']];
		$this->season_info['no'] = ($this->date_info['today_j']%3 == 0)? '03':'';
		if(!strlen($this->season_info['no']))
		{
			$this->season_info['no'] = ($this->info['today_j']%2 == 0)? '02':'01';
		}
		return;
	}

	private function setDateInfo()
	{
		foreach($this->date_info as $key => $val)
		{
			$this->set($key,$val);
		}
		return;
	}

	private function getDbData($calendar_main,$calendaer_last_date)
	{
		$this->loadModel('Index');
		$user = (isset($this->cookie_auth_user))? $this->cookie_auth_user:'guest';
		if($this->view_only) $user = 'mao';
		if($user == 'ryota') $user = 'mao';
		$db_data = array();
		$db_data = $this->Index->getMongoData($user,array('$gte'=>new MongoDate(strtotime($calendar_main[0][0]['day_full'].' 00:00:00 +0900')),'$lte'=> new MongoDate(strtotime($calendaer_last_date.' 00:00:00 +0900'))));

		return $db_data;
	}
}
