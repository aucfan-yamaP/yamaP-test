<?php
class IndicesController extends AppController {
	private $config;

	public $view_only = true;
	private $cookie_auth_user;

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
//		$this->set(array('a'=>'b','c'=>'d'));
//		echo $this->request->input(); exit;
//		echo CakeRequest::param('i'); exit;
//		print_r($this->request->query('i')); exit;
//		$this_day = (isset($this->request->query('date')) && strtotime($this->request->query('date')) > strtotime('2010-01-01'))? $this->request->query('date'):date('Y-m-d');

		if(!$this->view_only)
		{
			$this->auth();
		}

		$this_day = ($this->request->query('date') && strtotime($this->request->query('date')) > strtotime('2010-01-01'))? $this->request->query('date'):date('Y-m-d');
		$this_day_strtotime = strtotime($this_day);
		$real_today = date('Y-n-j');

		$today_y = date('Y',$this_day_strtotime);
		$today_m = date('m',$this_day_strtotime);
		$today_d = date('d',$this_day_strtotime);
		$today_n = date('n',$this_day_strtotime);
		$today_j = date('j',$this_day_strtotime);

		$before_y = date('Y',strtotime($this_day.' -1 month'));
		$before_m = date('m',strtotime($this_day.' -1 month'));
		$before_d = date('d',strtotime($this_day.' -1 month'));
		$before_n = date('n',strtotime($this_day.' -1 month'));
		$before_j = date('j',strtotime($this_day.' -1 month'));

		$after_y = date('Y',strtotime($this_day.' +1 month'));
		$after_m = date('m',strtotime($this_day.' +1 month'));
		$after_d = date('d',strtotime($this_day.' +1 month'));
		$after_n = date('n',strtotime($this_day.' +1 month'));
		$after_j = date('j',strtotime($this_day.' +1 month'));

		$before_l = date('d',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
		$today_l = date('d',strtotime($after_y.'-'.$after_m.'-01 -1 day'));
		$before_lj = date('j',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
		$today_lj = date('j',strtotime($after_y.'-'.$after_m.'-01 -1 day'));
		$before_lw = date('w',strtotime($today_y.'-'.$today_m.'-01 -1 day'));
		$today_lw = date('w',strtotime($after_y.'-'.$after_m.'-01 -1 day'));

		$today_month_first_weekend = date('w',strtotime($today_y.'-'.$today_m.'-01'));
		$today_month_first_weekend_str = $this->config['WEEKEND'][$today_month_first_weekend];

		$calendar_before_month_days = array();
		for($i = 0;$i<$today_month_first_weekend;$i++)
		{
		    $calendar_before_month_days[$i] = $before_lj-($today_month_first_weekend-1-$i);
		}

		$calendar_after_month_days = array();
		for($i = $today_lw;$i<6;$i++)
		{
		    $calendar_after_month_days[$today_lw+(count($calendar_after_month_days)+1)] = count($calendar_after_month_days)+1;
		}

		$calendar_main = array();
		$calendar_row_count = 0;
		$calendaer_last_date = '';
		foreach($calendar_before_month_days as $key => $val)
		{
		    $calendar_main[$calendar_row_count][$key]['type'] = 'before';
		    $calendar_main[$calendar_row_count][$key]['day'] = $val;
		    $calendar_main[$calendar_row_count][$key]['day_full'] = $before_y.'-'.$before_n.'-'.$val;
		}
		for($i=1;$i<=$today_lj;$i++)
		{
		    $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_m.'-'.$i))]['type'] = 'main';
		    $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_m.'-'.$i))]['day'] = $i;
		    $calendar_main[$calendar_row_count][date('w',strtotime($today_y.'-'.$today_n.'-'.$i))]['day_full'] = $today_y.'-'.$today_n.'-'.$i;
		    $calendaer_last_date = $today_y.'-'.$today_m.'-'.$i;
		    if(date('w',strtotime($today_y.'-'.$today_m.'-'.$i)) == 6) $calendar_row_count++;
		}
		foreach($calendar_after_month_days as $key => $val)
		{
		    $calendar_main[$calendar_row_count][$key]['type'] = 'after';
		    $calendar_main[$calendar_row_count][$key]['day'] = $val;
		    $calendar_main[$calendar_row_count][$key]['day_full'] = $after_y.'-'.$after_n.'-'.$val;
		    $calendaer_last_date = $after_y.'-'.$after_n.'-'.$val;
		}

		$holiday_array = array();
		$filenames = array();
		$filenames['today'] = WWW_ROOT.'files/json/'.$today_y.$today_m.'.js';
		$filenames['before'] = WWW_ROOT.'files/json/'.$before_y.$before_m.'.js';
		$filenames['after'] = WWW_ROOT.'files/json/'.$after_y.$after_m.'.js';

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
		        $holiday_array[$json_val['jYear'].'-'.$json_val['jMonth'].'-'.$json_val['jDay']] = $json_val['jHoliday'];
		    }
		}
		unset($jsons);

		$season = '';
		$season_no = '';
		if(strtotime('2015-05-01') <= strtotime($real_today))
		{
		    $season = 'season';
		    $season .= $this->config['SEASON'][$today_n];
		    $season_no = ($today_j%3 == 0)? '03':'';
		    if(!strlen($season_no))
		    {
		        $season_no = ($today_j%2 == 0)? '02':'01';
		    }
		}

		$db_data = $this->getDbData($calendar_main,$calendaer_last_date);

		$this->set(array(
			'view_only' => $this->view_only,
			'db_data' => $db_data,
			'this_day' => $this_day,
			'this_day_strtotime' => $this_day_strtotime,
			'real_today' => $real_today,

			'today_y' => $today_y,
			'today_m' => $today_m,
			'today_d' => $today_d,
			'today_n' => $today_n,
			'today_j' => $today_j,

			'before_y' => $before_y,
			'before_m' => $before_m,
			'before_d' => $before_d,
			'before_n' => $before_n,
			'before_j' => $before_j,

			'after_y' => $after_y,
			'after_m' => $after_m,
			'after_d' => $after_d,
			'after_n' => $after_n,
			'after_j' => $after_j,

			'before_l' => $before_l,
			'today_l' => $today_l,
			'before_lj' => $before_lj,
			'today_lj' => $today_lj,
			'before_lw' => $before_lw,
			'today_lw' => $today_lw,

			'today_month_first_weekend' => $today_month_first_weekend,
			'today_month_first_weekend_str' => $today_month_first_weekend_str,

			'calendar_before_month_days' => $calendar_before_month_days,

			'calendar_after_month_days' => $calendar_after_month_days,

			'calendar_main' => $calendar_main,

			'holiday_array' => $holiday_array,

			'season' => $season,
			'season_no' => $season_no,
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
	}

	private function getDbData($calendar_main,$calendaer_last_date)
	{
		$user = (isset($this->cookie_auth_user))? $this->cookie_auth_user:'guest';
		if($this->view_only) $user = 'mao';
		if($user == 'ryota') $user = 'mao';
		$mongo = new MongoClient();
		$db = $mongo->selectDB('calendar');
		$shift_collection = $db->shift;
		$ret = $shift_collection->find(array('status'=>0,'user'=>$user,'date'=>array('$gte'=>new MongoDate(strtotime($calendar_main[0][0]['day_full'].' 00:00:00 +0900')),'$lte'=> new MongoDate(strtotime($calendaer_last_date.' 00:00:00 +0900')))));
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
