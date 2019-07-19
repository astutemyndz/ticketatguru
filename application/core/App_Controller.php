<?php 
//use App\Controllers\pjAppController;
//use App\Models\pjAppModel;

class App_Controller extends CI_Controller
{
	public 		$models 				= array();
	public 		$defaultLocale 			= 'frontend_locale_id';
	public 		$defaultFields 			= 'fields';
	public 		$defaultFieldsIndex 	= 'fields_index';
	public 		$ajax 					= FALSE;

	public 		$defaultLangMenu 		= 'pjTicketBooking_LangMenu';

	public function __construct()
    {
		parent::__construct();
		$this->pjActionLoad();
		$this->beforeFilter();
		$this->afterFilter();
	}
	private function setSetOptionArrayInSession() {
		$OptionModel = pjOptionModel::factory();
		$this->option_arr = $OptionModel->getPairs($this->getForeignId());
		$this->setSession($this->optionArr, $this->option_arr);
		$this->setTime();
	}
	protected function beforeFilter() {
		$this->setSetOptionArrayInSession();
		if (!$this->hasSession($this->defaultLocale)) {
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1) {
				$this->setLocaleId($locale_arr[0]['id']);
			}
		}
		$this->loadSetFields();
	}

	protected function afterFilter()
	{		
		$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC')->findAll()->getData();
		$this->setSession($this->locale_arr, $locale_arr);
	}
	
	protected function pjActionLoad()
	{
		//ob_start();
		//header("Content-Type: text/javascript; charset=utf-8");
		/*
		$terms_conditions = pjMultiLangModel::factory()->select('t1.*')
			->where('t1.model','pjOption')
			->where('t1.locale', $this->getLocaleId())
			->where('t1.field', 'o_terms')
			->limit(0, 1)
			->findAll()->getData();
		$this->set('terms_conditions', $terms_conditions[0]['content']);
		*/
		if($this->getSession('locale') && $this->getSession('locale') > 0)
		{
			$this->setSession($this->defaultLocale, (int) $this->getSession('locale'));
			$this->setSession($this->defaultLangMenu, 'hide');
			//$this->loadSetFields(true);
		} else {
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->setSession($this->defaultLocale, $locale_arr[0]['id']);
			}
			$this->setSession($this->defaultLangMenu, 'show');
			//$this->setSession($this->pjActionSeatsAjaxResponse, []);
		}
	}

	public function getLocaleId() {
		return ($this->hasSession($this->defaultLocale)) && (int) $this->getSession($this->defaultLocale) > 0 ? (int) $this->getSession($this->defaultLocale) : false;
	}
	public function setLocaleId($locale_id) {
		$this->setSession($this->defaultLocale, (int) $locale_id);
	}
	protected function get($key)
	{
		if ($this->has($key))
		{
			return $this->input->get($key);
		}
		return false;
	}
	protected function post($key)
	{
		if ($this->has($key))
		{
			return $this->input->post($key);
		}
		return false;
	}

	protected function setCookie($array = array(), $XSSFilter  = TRUE)
	{
		$this->input->cookie($array, $XSSFilter); // with XSS filter
		return $this;
	}

	
	
	protected function has($key)
	{
		return (!empty($key) && $key !== NULL);
	}


	protected function getSession($key)
	{
		return $this->session->userdata($key);
	}
	
	protected function hasSession($key)
	{
		if($this->getSession($key)) {
			return true;
		}
		return false;
	}
	
	protected function setSession($key, $value)
	{
		$this->session->set_userdata($key, $value);
		return $this;
	}
	protected function unsetSession($key) {
		if($this->hasSession($key)) {
			return $this->session->unset_userdata($key);
		}
		return false;
	}

	public function isXHR()
    {
       
        return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
	public function getForeignId()
    {
    	return 1;
	}
	public function setAjax($value)
    {
        
        $this->ajax = (bool) $value;
        return $this;
    }
	public function getShowsInGrid($arr)
	{
		$show_arr = array();
		$time_arr = array();
		$all_show_arr = array();
		foreach($arr as $v)
		{
			$_time = date('H:00', strtotime($v['date_time']));
			$time = date('H:i', strtotime($v['date_time']));
			if(empty($show_arr))
			{
				$show_arr[$v['event_id']][] = $time;
				$all_show_arr[$v['event_id']][] = $v['date_time'];
			}else{
				if(array_key_exists($v['event_id'], $show_arr))
				{
					if(!in_array($time, $show_arr[$v['event_id']]))
					{
						$show_arr[$v['event_id']][] = $time;
					}
					if(!in_array($v['date_time'], $all_show_arr[$v['event_id']]))
					{
						$all_show_arr[$v['event_id']][] = $v['date_time'];
					}
				}else{
					$show_arr[$v['event_id']][] = $time;
					$all_show_arr[$v['event_id']][] = $v['date_time'];
				}
			}
			if(empty($time_arr))
			{
				$time_arr[] = $_time;
			}else{
				if(!in_array($_time, $time_arr))
				{
					$time_arr[] = $_time;
				}
			}
		}
			
		$time = array();
		foreach ($time_arr as $key => $val) {
			$time[$key] = $val[0];
		}
		array_multisort($time, SORT_ASC, $time_arr);
		
		return compact("show_arr", 'time_arr', 'all_show_arr');
	}
	public static function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }

	public static function setMySQLServerTime($offset="-0:00")
    {
		pjAppModel::factory()->prepare("SET SESSION time_zone = :offset;")->exec(compact('offset'));
		pjAppModel::factory()->prepare("SET SESSION group_concat_max_len = 100000;")->exec();
    }
    
	public function setTime()
	{
		if (isset($this->option_arr['o_timezone']))
		{
			$offset = $this->option_arr['o_timezone'] / 3600;
			if ($offset > 0)
			{
				$offset = "-".$offset;
			} elseif ($offset < 0) {
				$offset = "+".abs($offset);
			} elseif ($offset === 0) {
				$offset = "+0";
			}
	
			self::setTimezone('Etc/GMT' . $offset);
			if (strpos($offset, '-') !== false)
			{
				$offset = str_replace('-', '+', $offset);
			} elseif (strpos($offset, '+') !== false) {
				$offset = str_replace('+', '-', $offset);
			}
			self::setMySQLServerTime($offset . ":00");
		}
	}
	
	
	
	protected function loadSetFields($force = FALSE, $locale_id = NULL, $fields = NULL)
	{
		if (is_null($locale_id))
		{
			$locale_id = $this->getLocaleId();
		}
		
		if (is_null($fields))
		{
			$fields = $this->defaultFields;
		}
	
		$registry = pjRegistry::getInstance();
		if ($force
				|| !$this->hasSession($this->defaultFieldsIndex)
				|| $this->getSession($this->defaultFieldsIndex) != $this->option_arr['o_fields_index']
				|| !$this->hasSession($fields))
		{
			
			$this->setFields($locale_id);
		
			# Update session
			if ($this->hasSession('fields'))
			{
				// echo "<pre>";
				// print_r($locale_id);
				// exit;
				$this->setSession($fields, $this->getSession('fields'));
			}
			$this->setSession($this->defaultFieldsIndex, $this->option_arr['o_fields_index']);
		}
	
		if ($this->hasSession($fields))
		{
			# Load fields from session
			$registry->set('fields', $this->getSession($fields));
		}
		
		return TRUE;
	}
	/*
	public function isCountryReady()
    {
    	return $this->isAdmin();
    }
    
	public function isOneAdminReady()
    {
    	return $this->isAdmin();
    }
    
    public function isInvoiceReady()
    {
    	return $this->isAdmin() || $this->isEditor();
    }
	
    
    
    
	public function isEditor()
    {
    	return $this->getRoleId() == 2;
    }
    
    
    */
    public function setFields($locale)
    {
    	if($this->hasSession('lang_show_id') && (int) $this->getSession('lang_show_id') == 1)
		{
			$fields = pjMultiLangModel::factory()
				->select('CONCAT(t1.content, CONCAT(":", t2.id, ":")) AS content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}else{
			$fields = pjMultiLangModel::factory()
				->select('t1.content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}  
		$registry = pjRegistry::getInstance();
		$tmp = array();
		if ($registry->is('fields'))
		{
			$tmp = $registry->get('fields');
		}
		$arrays = array();
		foreach ($fields as $key => $value)
		{
			if (strpos($key, '_ARRAY_') !== false)
			{
				list($prefix, $suffix) = explode("_ARRAY_", $key);
				if (!isset($arrays[$prefix]))
				{
					$arrays[$prefix] = array();
				}
				$arrays[$prefix][$suffix] = $value;
			}
		}
		require PJ_CONFIG_PATH . 'settings.inc.php';
		$fields = array_merge($tmp, $fields, $settings, $arrays);
		$registry->set('fields', $fields);
    }



}