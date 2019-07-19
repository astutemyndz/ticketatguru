<?php
//use App\Models\pjOptionModel;

defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends pjAppController {
	
	public $defaultCaptcha = 'pjTicketBooking_Captcha';
	
	public $defaultLocale = 'pjTicketBooking_LocaleId';
	
	public $defaultLangMenu = 'pjTicketBooking_LangMenu';
	
	public $defaultStore = 'pjTicketBooking_Store';
	
	public $defaultForm = 'pjTicketBooking_Form';

	private $option_arr = array();


	function __construct() {
		parent::__construct();
		self::allowCORS();
		$pjOptionModel = pjOptionModel::factory();
		$this->option_arr = $pjOptionModel->getPairs($this->getForeignId());
		$this->setTime();
    }

	private function _get($key)
	{
		if ($this->_is($key))
		{
			return $_SESSION[$this->defaultStore][$key];
		}
		return false;
	}
	
	private function _is($key)
	{
		return isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore][$key]);
	}
	
	private function _set($key, $value)
	{
		$_SESSION[$this->defaultStore][$key] = $value;
		return $this;
	}

	public function index()	{

		
		// $data =  array();
		// $data['title'] = 'Ticket at Guru';
		// $table['name'] = 'tk_cbs_events';
		// $select = 'tk_cbs_events.*,tk_cbs_multi_lang.content,tk_cbs_shows.*';
        // $condition = array('tk_cbs_events.status' => 'T','tk_cbs_multi_lang.field' => 'title','tk_cbs_multi_lang.source' => 'data','tk_cbs_multi_lang.model' => 'pjEvent','tk_cbs_shows.event_id!=' => '');

        
        
        // $join[0] = array('table'=>'tk_cbs_multi_lang','field'=>'foreign_id','table_master'=>'tk_cbs_events','field_table_master'=>'id','type'=>'left');
        // $join[1] = array('table'=>'tk_cbs_shows','field'=>'event_id','table_master'=>'tk_cbs_events','field_table_master'=>'id','type'=>'left');
        // $group_by[0] = 'tk_cbs_events.id';
        // $order_by[0] = array('field'=>'tk_cbs_shows.date_time','type'=>'ASC');
        // $data['event_lists'] = $this->Common_model->find_data($table,'array','',$condition,$select,$join,$group_by,$order_by);
       	
		// $this->template->set_partial('head','layouts/elements/head');
		// $this->template->set_partial('header','layouts/elements/header');
		// $this->template->set_layout('website');
		// $this->template->set_partial('footer','layouts/elements/footer');
        // $this->template->build('home/index', $data);
	}

	public function details($id) {
		$hash_date = date('Y-m-d');
		if(isset($_GET['date']) && !empty($_GET['date']) && pjUtil::checkFormatDate($_GET['date'], $this->option_arr['o_date_format']) == TRUE)
		{
			$hash_date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);
		}
		
		$selected_date = $hash_date;
		$this->_set('selected_date', $selected_date);
		
		if($this->_is('tickets'))
		{
			unset($_SESSION[$this->defaultStore]['tickets']);
		}
		if($this->_is('seat_id'))
		{
			unset($_SESSION[$this->defaultStore]['seat_id']);
		}
		
		$pjEventModel = pjEventModel::factory();
			
		$arr = $pjEventModel
			->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->join('pjMultiLang', "t3.model='pjEvent' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
			->select('t1.*, t2.content as title, t3.content as description')
			->find($id)
			->getData();
		
		$show_arr = pjShowModel::factory()
			->where('t1.event_id', $id)
			->where("(DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '$selected_date') AND (t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T') )")
			->orderBy("date_time ASC")
			->findAll()
			->getData();
		$time_arr = array();
		foreach($show_arr as $v)
		{
			$time = date('H:i', strtotime($v['date_time']));
			if(strtotime($v['date_time']) > time() + $this->option_arr['o_booking_earlier'] * 60)
			{
				if(!in_array($time, $time_arr))
				{
					$time_arr[] = $time;
				}
			}
		}

		


		// $this->set('arr', $arr);
		// $this->set('hash_date', $hash_date);
		// $this->set('selected_date', $selected_date);
		// $this->set('time_arr', $time_arr);


		$this->data['arr'] = $arr;
		$this->data['hash_date'] = $hash_date;
		$this->data['selected_date'] = $selected_date;
		$this->data['time_arr'] = $time_arr;


		echo "<pre>";
		print_r($this->data);
		exit;

		$this->data['title'] = 'Ticket at Guru';
		$this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/event/details', $this->data);
		$this->load->view('frontend/layout/footer');
	
	}

	

	
	
	

	
}

