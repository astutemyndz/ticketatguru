<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class EventController extends App_Controller
{	
	
	//public $defaultStore = 'pjTicketBooking_Store';
	public $cartItems = array();
	public $defaultForm = 'pjTicketBooking_Form';
	public $pjActionSeatsAjaxResponse = 'pjActionSeatsAjaxResponse';
	/*
	protected 	$option_arr 			= array();
	protected 	$optionArr 				= 'option_arr';
	protected 	$locale_arr 			= 'locale_arr';
	*/
	public function __construct() {
		parent::__construct();
		self::allowCORS();
		// App::dd($this->option_arr);
	}
	
	private function pjActionSetLocale($locale) {
		if ((int) $locale > 0) {
			$this->setSession($this->defaultLocale, (int) $locale);
		}
		return $this;
	}
	
	public function pjActionGetLocale() {
		return ($this->getSession($this->defaultLocale)) && (int) $this->getSession($this->defaultLocale) > 0 ? (int) $this->getSession($this->defaultLocale) : FALSE;
	}
	public function pjActionGetHide() {
		return ($this->getSession('hide')) ? (int) $this->getSession('locale') : 0;
	}
		
	public function index() {
		$this->data = $this->pjActionEvents();
		$pjSponsorsModel = pjSponsorsModel::factory();
        $pjSponsorsModel->join('pjMultiLang', "t2.model='pjSponsor' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
		$pjSponsorsModel->where('t1.status', 'T');
		$pjSponsorsModel->where('t1.sponsor_year', date('Y'));
		$this->data['sponsorsData'] = $pjSponsorsModel
							->select(" t1.id, t1.sponsor_image,t1.sponsor_year, t1.created, t1.sponsor_link, t1.status, t2.content as name")
							->orderBy("t1.id desc")
							->findAll()
							->getData();
		
		$pjVideoModel = pjVideoModel::factory();
		$pjVideoModel->join('pjMultiLang', "t2.model='pjVideo' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
		$pjVideoModel->where('t1.id', 1);
		$this->data['video']  = $pjVideoModel
				->select(" t1.id, t1.video_path, t1.mime_type, t1.created, t1.status, t2.content as name")
				->orderBy("t1.id desc")
				->find(1)
				->getData();

		$pjMultiLangModel = pjMultiLangModel::factory();
		$this->data['Cms_About'] = pjCmsModel::factory()->find(4)->getData();
		$this->data['Cms_About']['i18n'] = $pjMultiLangModel->getMultiLang($this->data['Cms_About']['id'], 'pjCms');
		
		$pjImageGalleryModel = pjImageGalleryModel::factory();
		$pjImageGalleryModel->join('pjMultiLang', "t2.model='pjImageGallery' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
		$pjImageGalleryModel->join('pjMultiLang', "t3.model='pjImageGallery' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer');
		$pjImageGalleryModel->where('t1.status', 'T');
		$this->data['gallery'] = $pjImageGalleryModel
							->select(" t1.id, t1.gallery_image, t1.created, t1.status, t2.content as title, t3.content as description")
							->orderBy("t1.id desc")
							->limit(0, 8)
							->findAll()
							->getData();
		//  echo "<pre>"; print_r($this->data['gallery']);		
		//echo "<pre>"; print_r($this->data['Cms_About']);

		
		$this->data['title'] 	= 'Ticket At Guru :: Home';
 		$this->load->view('frontend/layout/head', $this->data);
        $this->load->view('frontend/layout/header',$this->data);
        $this->load->view('frontend/pages/home', $this->data);
		$this->load->view('frontend/layout/footer');
	}
	/**
	 * Get all events
	 * @return Array
	 */

	private function pjActionEvents()
	{
		$ts = time();
		$hash_date = date('Y-m-d', $ts);
		
		$from_ts = $ts;
		
		if(isset($_GET['from_date']) && !empty($_GET['from_date']))
		{
			// echo "testuuuuuuuuuuuuuuuuu";
			$from_ts = strtotime(pjUtil::formatDate($_GET['from_date'], $this->option_arr['o_date_format']));
		}
		$end_ts = $from_ts + (86400 * 7);
		
		if(isset($_GET['date']) && !empty($_GET['date']))
		{
			// echo "ttttestuuuuuuuuuuuuuuuuu";
			$hash_date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);
		}
		if(strtotime($hash_date) < $from_ts || strtotime($hash_date) > $end_ts)
		{
			// echo "tkkkestuuuuuuuuuuuuuuuuu";
			$hash_date = date('Y-m-d', $from_ts);
		}
		$pjEventModel = pjEventModel::factory();
        $pjShowModel = pjShowModel::factory();
		// echo $hash_date."kkkkkkkkkkkkkkkkkkkkkkkkkkkkkk";
		$pjEventModel->where("t1.id IN(SELECT TS.event_id FROM `".$pjShowModel->getTable()."` AS TS WHERE DATE_FORMAT(TS.date_time,'%Y-%m-%d') = '".$hash_date."')");
		//$pjShowModel->where("(DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '$hash_date') AND (t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T') )");
		
		$arr = $pjEventModel
			->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->join('pjMultiLang', "t3.model='pjEvent' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
			->select('t1.*, t2.content as title, t3.content as description')
			->where('status', 'T')
			->findAll()
			->getData();
        
       // $_arr = $pjShowModel->orderBy("t1.date_time ASC")->findAll()->getData();
	   $show_arr = $pjShowModel
				->where("(DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '$hash_date') AND (t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T') )")
				->where("t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T')")
				->orderBy("t1.date_time ASC")
				->findAll()
				->getData();
		$grid = $this->getShowsInGrid($show_arr);
		// echo "<pre>";
		// print_r($show_arr);
		$time_arr = array();
		$showTimes = array();
		foreach($grid['show_arr'] as $eventId => $v)
		{
			for($l=0; $l < count($v); $l++) {
				$time_arr[] = $v[$l];
				$date_time_iso = $hash_date . ' ' . $v[$l] . ':00';
				$date_time_ts = strtotime($hash_date . ' ' . $v[$l] . ':00');
				$showTime = date($this->option_arr['o_time_format'], strtotime($date_time_iso));
				
				if($date_time_ts >= strtotime(date('Y-m-d H:00')) + ($this->option_arr['o_booking_earlier'] * 60 )) {
					$showTimes[]  = array(
						'showTime' => $showTime,
						'dataTime' => $v[$l],
						'event' => $this->pjGetEvent($eventId)
					);
				}  else {
					$showTimes = [];
				}
			}
		}
		
		$events = array();
		foreach($arr as $event) {
			$events[] = array(
				'event' => $event,
				'shows' => $this->pjShowDatesByEventId($event['id']),
				'Price' => $this->pjGetEventPrice($show_arr, $eventId)
			); 
		}
	
		$this->data['showTimes'] 	= (count($showTimes) > 0) ? $showTimes : [];
		$this->data['events'] 		= $events;
		$ts 						= time();
		$today 						= date('Y-m-d', $ts);
		
		$this->data['today'] 		= $today;
		$this->data['hashDate'] 	= $today;
		
	
       
		
		return $this->data;
	}



	public function eventList(){
		$this->data = $this->pjActionEvents();
		$this->data['title'] 		= 'Event Lists';
		$this->data['page_heading'] = 'Events List';
        $this->load->view('frontend/layout/head', $this->data);
        $this->load->view('frontend/layout/header');
        $this->load->view('frontend/pages/event/event_list', $this->data);
        $this->load->view('frontend/layout/footer');
	}
	public function galleryList(){
		
		$pjImageGalleryModel = pjImageGalleryModel::factory();
		$pjImageGalleryModel->join('pjMultiLang', "t2.model='pjImageGallery' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
		$pjImageGalleryModel->join('pjMultiLang', "t3.model='pjImageGallery' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer');
		$pjImageGalleryModel->where('t1.status', 'T');
		$this->data['gallery'] = $pjImageGalleryModel
							->select(" t1.id, t1.gallery_image, t1.created, t1.status, t2.content as title, t3.content as description")
							->orderBy("t1.id desc")
							->findAll()
							->getData();
		//echo "<pre>"; print_r($this->data['gallery']);
		$this->data['title'] 		= 'Gallery Lists';
		$this->data['page_heading'] = 'Gallery';
        $this->load->view('frontend/layout/head', $this->data);
        $this->load->view('frontend/layout/header');
        $this->load->view('frontend/pages/event/gallery', $this->data);
        $this->load->view('frontend/layout/footer');
	}
	public function partnersList(){
		$pjSponsorsYear = pjSponsorsModel::factory();
        
		
		$sponsorsYear = $pjSponsorsYear
		->where('t1.status', 'T')
		->select("t1.sponsor_year")
		->orderBy("t1.sponsor_year asc")
		->groupBy("t1.sponsor_year")
		->findAll()
		->getData();
		$this->data['sponsor_year'] = array();
		// echo "<pre>"; print_r($sponsorsYear);
		foreach($sponsorsYear as $sponsorsYearVal){
			$pjSponsorsModel = pjSponsorsModel::factory();
			$pjSponsorsModel->join('pjMultiLang', "t2.model='pjSponsor' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
			$pjSponsorsModel->where('t1.status', 'T');
			$pjSponsorsModel->where('t1.sponsor_year', $sponsorsYearVal['sponsor_year']);
			$this->data['sponsorsData'] = $pjSponsorsModel
								->select(" t1.id, t1.sponsor_image,t1.sponsor_year, t1.created, t1.sponsor_link, t1.status, t2.content as name")
								->orderBy("t1.id desc")
								->findAll()
								->getData();
			$this->data['sponsor_year'][$sponsorsYearVal['sponsor_year']] = $this->data['sponsorsData'];
			
		}
		// echo "<pre>"; print_r($this->data['sponsor_year']);
		$this->data['title'] 		= 'Partners';
		$this->data['page_heading'] = 'Main sponsors';
        $this->load->view('frontend/layout/head', $this->data);
        $this->load->view('frontend/layout/header');
        $this->load->view('frontend/pages/event/partners', $this->data);
        $this->load->view('frontend/layout/footer');
	}
	private function pjGetEvent($id) {
		$pjEventModel = pjEventModel::factory();
		$arr = $pjEventModel
				->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t3.model='pjEvent' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->select('t1.*, t2.content as title, t3.content as description')
				->find($id)
				->getData();
			return $arr;
	}
	private function pjGetEventPrice($show_arr, $id) {
		$price = array();
		if(count($show_arr) > 0) {
			foreach ($show_arr as $value) {
				if($value['event_id'] == $id) {
					$price[] = $value['price'];
					//break;
				}
			}
		}
		return $price;
	}
	private function pjShowDatesByEventId($id) {
		$pjShowModel = pjShowModel::factory();
		$show_arr = $pjShowModel
					->where('t1.event_id', $id)
					->where("t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T')")
					->orderBy("t1.date_time ASC")
					->findAll()
					->getData();
		// echo "<pre>"; print_r($show_arr);
		$grid = $this->getShowsInGrid($show_arr);
		$show_date_arr = array();
			foreach($show_arr as $v)
			{
				$date = date($this->option_arr['o_date_format'], strtotime($v['date_time']));
				if(strtotime($v['date_time']) > time() + $this->option_arr['o_booking_earlier'] * 60)
				{
					if(!in_array($date, $show_date_arr))
					{
						$show_date_arr[] = $date;
					}
				}
			}
		return $show_date_arr;
		
	}
	
	
	public function pjActionDetails($id)
	{
			$hash_date = NULL;
			$selected_date = NULL;
			$hash_date = date('Y-m-d');
			if($this->get('date'))
			{
				$hash_date = $this->get('date');//pjUtil::formatDate($this->get('date'), $this->option_arr['o_date_format']);
			}
			if($hash_date) {
				$selected_date = date($this->option_arr['o_date_format'], strtotime($hash_date));
			} 
		
			$today = date($this->option_arr['o_date_format'], strtotime(date('Y-m-d')));
		
			$this->setSession('selected_date', $selected_date);
			if($this->hasSession($this->defaultStore)['tickets'])
			{
				$this->unsetSession($this->getSession($this->defaultStore)['tickets']);
			}
			if($this->hasSession($this->defaultStore)['seat_id'])
			{
				$this->unsetSession($this->getSession($this->defaultStore)['seat_id']);
			}
			$pjEventModel = pjEventModel::factory();
			$pjShowModel = pjShowModel::factory();
			//echo $this->getLocaleId();
			//exit;
			$arr = $pjEventModel
				->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t3.model='pjEvent' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->select('t1.*, t2.content as title, t3.content as description')
				->find($id)
				->getData();
			
			
			$show_arr = $pjShowModel
				->where('t1.event_id', $id)
				->where("t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T')")
				->orderBy("t1.date_time ASC")
				->findAll()
				->getData();
				// echo "<pre>";
				// print_r($show_arr);
				// exit;
			$grid = $this->getShowsInGrid($show_arr);
		
			$time_arr = array();
			foreach($show_arr as $v)
			{
				
				//echo $v['date_time'];
				$time = date('H:i', strtotime($v['date_time']));
				if(strtotime($v['date_time']) > time() + $this->option_arr['o_booking_earlier'] * 60)
				{
					if(!in_array($time, $time_arr))
					{
						$time_arr[] = $time;
					}
				}
			}
			// echo "<pre>";
			// print_r($time_arr);
			// exit;
			$show_date_arr = array();
			foreach($show_arr as $v)
			{
			
				$date = date($this->option_arr['o_date_format'], strtotime($v['date_time']));
				if(strtotime($v['date_time']) > time() + $this->option_arr['o_booking_earlier'] * 60)
				{
					if(!in_array($date, $show_date_arr))
					{
						$show_date_arr[] = $date;
					}
				}
			}
			
			$this->data['arr'] = $arr;
			$this->data['all_show_arr'] =  $grid['all_show_arr'];
			$this->data['selected_date'] = $selected_date;
			$this->data['today'] = $today;
			$this->data['time_arr'] = $grid['time_arr'];
	
			$this->data['selected_date_format'] = date("jS M, Y", strtotime($selected_date));
			$this->data['show_date_arr'] = $show_date_arr;
			$this->data['show_arr'] = $grid['show_arr'];
			$this->data['title'] = 'Ticket at Guru';
			$this->data['page_heading'] = 'Ticket at Guru details';
			
			$this->load->view('frontend/layout/head', $this->data);
			$this->load->view('frontend/layout/header');
			$this->load->view('frontend/pages/event/details', $this->data);
			$this->load->view('frontend/layout/footer');
		//}
	}
	/**
	 * http://103.121.156.221/projects/ticketatguru/event/details/5
	 */
	
	public function pjEventsTimesDateWise() {
		$this->setAjax(true);
		if ($this->isXHR()) {
			$hash_date = date('Y-m-d');
			$id = ($this->has('id')) ? $this->post('id') : '';
			if($this->post('date') && pjUtil::checkFormatDate($this->post('date'), $this->option_arr['o_date_format']) == TRUE)
			{
				$hash_date = pjUtil::formatDate($this->post('date'), $this->option_arr['o_date_format']);
			}
			$pjEventModel = pjEventModel::factory();
			$pjShowModel = pjShowModel::factory();
			
			$pjShowModel->where("(DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '$hash_date') AND (t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T') )");
			$show_arr = $pjShowModel
						->where('t1.event_id', $id)
						->where("t1.venue_id IN (SELECT TV.id FROM `".pjVenueModel::factory()->getTable()."` AS TV WHERE TV.status='T')")
						->orderBy("t1.date_time ASC")
						->findAll()
						->getData();
			$grid = $this->getShowsInGrid($show_arr);
			$time_arr = array();
			if($id)
			{
				foreach($grid['show_arr'][$id] as $k => $time)
				{
					
					$date_time_iso = $hash_date . ' ' . $time . ':00';
					$date_time_ts = strtotime($hash_date . ' ' . $time . ':00');
					
					$show_time = date($this->option_arr['o_time_format'], strtotime($date_time_iso));
					
					if($date_time_ts >= strtotime(date('Y-m-d H:00')) + ($this->option_arr['o_booking_earlier'] * 60 )) {
						$time_arr[] = array(
							'time' => $time,
							'show_time' => $show_time
						);
						
					} 
				}
			} 
			pjAppController::jsonResponse(array('status' => TRUE, 'code' => 200, 'time_arr' => $time_arr));
		}
	}
	
	public function pjActionSeatsAjax()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$hash_date = date('Y-m-d');
			if($this->getSession($this->defaultStore) && count($this->getSession($this->defaultStore)) > 0) {
				
				$hash_date 		= ($this->has('date')) ? pjUtil::formatDate($this->post('date'), $this->option_arr['o_date_format']) : '';
				$time 			= ($this->has('time')) ? $this->post('time') : '';
				$id 			= ($this->has('id')) ? $this->post('id') : '';
				$selected_date 	= $hash_date;
				$selected_time = $time;
				//$selected_date 	= $hash_date;
				if($this->hasSession('selected_date'))
				{
					$selected_date = pjUtil::formatDate($this->getSession('selected_date'), $this->option_arr['o_date_format']);
					//echo $selected_date;
				} 
				
				$arr = pjEventModel::factory()
					->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->select('t1.*, t2.content as title')
					->find($id)
					->getData();
			
				$venue_id 			= null;
				$pjShowModel 		= pjShowModel::factory();
				$pjBookingShowModel = pjBookingShowModel::factory();
				$pjBookingModel 	= pjBookingModel::factory();
				$_show_arr		 	= $pjShowModel
										->select('DISTINCT t1.venue_id, t2.content as venue_name, t1.date_time')
										->join('pjMultiLang', "t2.model='pjVenue' AND t2.foreign_id=t1.venue_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
										->where("(t1.venue_id IN (SELECT `TV`.id FROM `".pjVenueModel::factory()->getTable()."` AS `TV` WHERE `TV`.`status`='T'))")
										->where('t1.event_id', $id)
										->where("t1.date_time = '". $selected_date . ' ' . $selected_time . ":00'")					
										->findAll()
										->getData();
				
				
				if(count($_show_arr) > 0)
				{
					$venue_id = $_show_arr[0]['venue_id'];
					$this->session->set_userdata('venue_id', $venue_id);
					
				}
				// echo $venue_id;
				// 	exit;
					
				if($venue_id != null)
				{
					
					//$this->setSession('venue_id', $venue_id);
					
					$ticket_arr = $pjShowModel->reset()
						->join('pjMultiLang', "t2.model='pjPrice' AND t2.foreign_id=t1.price_id AND t2.field='price_name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjPrice', "t1.price_id=t3.id", 'left outer')
						->select("t1.id, t1.price_id, t1.price, t2.content as ticket,
								  (     (
											SELECT SUM(TS.seats) 
											FROM `".pjSeatModel::factory()->getTable()."` AS `TS` 
											WHERE TS.venue_id='".$venue_id."' AND 
								              	TS.id IN ( SELECT(TSS.seat_id) FROM `".pjShowSeatModel::factory()->getTable()."` AS `TSS` WHERE TSS.show_id=t1.id)) - 
									    (
											IFNULL((SELECT SUM(TBS.cnt) 
											FROM `".$pjBookingShowModel->getTable()."` AS `TBS`
											WHERE TBS.show_id=t1.id AND TBS.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.event_id=".$id." AND TB.status<>'cancelled') ), 0)										
										)
								  ) as cnt_tickets")
						->where('t1.event_id', $id)
						->where("t1.date_time = '". $selected_date . ' ' . $selected_time . ":00'")
						->where("t1.venue_id", $venue_id)
						->findAll()
						->getData();
					
					$this->data['ticket_arr'] = $ticket_arr;
				
					$venue_arr = pjVenueModel::factory()->find($venue_id)->getData();
				
					
					$seat_arr = pjSeatModel::factory()
						->select("t1.*, (SELECT GROUP_CONCAT( TS.price_id SEPARATOR '~:~') FROM `".pjShowModel::factory()->getTable()."` AS TS WHERE TS.event_id='".$id."' AND TS.date_time = '". $selected_date . ' ' . $selected_time . ":00' AND TS.id IN (SELECT TSS.show_id FROM `".pjShowSeatModel::factory()->getTable()."` AS TSS WHERE TSS.seat_id=t1.id) ) AS price_id,
										(
											IFNULL((SELECT SUM(TBS.cnt)
											FROM `".$pjBookingShowModel->getTable()."` AS `TBS`
											WHERE TBS.show_id IN (SELECT TS.id FROM `".$pjShowModel->getTable()."` AS TS WHERE TS.event_id='".$id."' AND TS.date_time = '". $selected_date . ' ' . $selected_time . ":00') AND TBS.seat_id=t1.id AND TBS.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.event_id=".$id." AND TB.date_time='".$selected_date . ' ' . $selected_time.":00' AND TB.status<>'cancelled')), 0)
										) 
									AS cnt_booked ")
						->where('t1.venue_id', $venue_id)
						->where("t1.id IN (SELECT TSS.seat_id FROM `".pjShowSeatModel::factory()->getTable()."` AS TSS WHERE TSS.show_id IN (SELECT TS.id FROM `".$pjShowModel->getTable()."` AS TS WHERE TS.event_id='".$id."' AND TS.date_time = '". $selected_date . ' ' . $selected_time . ":00') )")
						->findAll()
						->getData();
		
					$total_available_seats = $total_remaining_avaliable_seats = $total_booked_seats = 0;
					$seat_name_arr = array();
					foreach($seat_arr as $v)
					{
						$seat_name_arr[$v['id']] 	= $v['name'];
						$total_available_seats 		= $total_available_seats + $v['seats'];
						$total_booked_seats 		+= $v['cnt_booked'];
					}
					$total_remaining_avaliable_seats = $total_available_seats - $total_booked_seats;
					$bs_arr = $pjBookingShowModel
								->reset()
								->select("SUM(t1.cnt) AS cnt_booked_seats")
								->where("(t1.booking_id IN (SELECT TB.id FROM `".$pjBookingModel->getTable()."` AS TB WHERE TB.status='confirmed' AND TB.date_time='".$selected_date . ' ' . $selected_time.":00' AND TB.event_id='".$id."'))")
								->where("(t1.show_id IN (SELECT `TS`.`id` FROM `".$pjShowModel->getTable()."` AS `TS` WHERE `TS`.venue_id='".$venue_id."'))")
								->limit(1)
								->findAll()
								->getData();
					$cnt_booked_seats = 0;
					if(count($bs_arr) == 1)
					{
						$cnt_booked_seats = $bs_arr[0]['cnt_booked_seats'];
					}
					$this->data['venue_arr'] 						= $venue_arr;
					$this->data['seat_arr'] 						= $seat_arr;
					$this->data['seat_name_arr'] 					= $seat_name_arr;
					$this->data['seats_available'] 					= $cnt_booked_seats >= $total_available_seats ? false: true;
					$this->data['total_remaining_avaliable_seats'] 	= $total_remaining_avaliable_seats;
				} 
					$this->data['arr'] 				= $arr;
					$this->data['hash_date'] 		= $hash_date;
					$this->data['selected_date'] 	= $selected_date;
					$this->data['selected_time'] 	= $selected_time;
					$this->data['selected_date_time'] = $_show_arr[0]['date_time'];
					$this->data['hall_arr'] 		= $_show_arr;
					$this->data['status'] 			= 'OK';
					$this->data['title'] 			= 'Ticket at Guru';
					$this->data['id'] 				= $id;
			} else {
				$selected_date 	= $hash_date;
				$this->data['status'] 			= 'ERR';
			}
			//$this->setSession($this->defaultStore, $this->data);
			$this->session->set_userdata($this->defaultStore, $this->data);
			pjAppController::jsonResponse(array('status' => TRUE, 'code' => 200, 'data' => $this->data));
		}
	}


	
	
	public function pjActionSeats() {
		$this->data['title'] = 'Ticket at Guru';
		$this->data['page_heading'] = 'Select Your Seat(s)';
		$this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/event/seats');
		$this->load->view('frontend/layout/footer');
	}
	public function pjActionCart() {
		
		
	}
	public function pjActionCheckout()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				if(isset($_POST['tb_checkout']))
				{
					if ((int) $this->option_arr['o_bf_include_captcha'] === 3 && (!isset($_POST['captcha']) ||
							!pjCaptcha::validate($_POST['captcha'], $_SESSION[$this->defaultCaptcha]) ))
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110, 'text' => __('system_212', true)));
					}
						
					$_SESSION[$this->defaultForm] = $_POST;
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 211, 'text' => __('system_211', true)));
				}else{
					$hash_date = date('Y-m-d');
					if(isset($_GET['date']) && !empty($_GET['date']))
					{
						$hash_date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);
					}
					$selected_date = $hash_date;
					if($this->_is('selected_date'))
					{
						$selected_date = $this->_get('selected_date');
					}
		
					$arr = pjEventModel::factory()
						->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select('t1.*, t2.content as title')
						->find($this->_get('id'))
						->getData();
		
					
					$ticket_arr = pjShowModel::factory()
						->join('pjMultiLang', "t2.model='pjPrice' AND t2.foreign_id=t1.price_id AND t2.field='price_name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjPrice', "t1.price_id=t3.id", 'left outer')
						->select("t1.id, t1.price_id, t1.price, t2.content as ticket")
					  	->where('t1.event_id', $this->_get('id'))
					  	->where("t1.date_time = '". $selected_date . ' ' . $this->_get('selected_time') . ":00'")
					  	->where("t1.venue_id", $this->_get('venue_id'))
					  	->findAll()
					  	->getData();
					
					$this->set('ticket_arr', $ticket_arr);
					
					$price_arr = $this->calculatePrice($ticket_arr, $this->_get('tickets'));
					$this->set('price_arr', $price_arr);
					
					$seat_arr = pjSeatModel::factory()
						->where('t1.venue_id', $this->_get('venue_id'))
						->findAll()
						->getData();
					$seat_name_arr = array();
					foreach($seat_arr as $v)
					{
						$seat_name_arr[$v['id']] = $v['name'];
					}
					$this->set('seat_name_arr', $seat_name_arr);
		
					$this->set('country_arr', pjCountryModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.status', 'T')
						->orderBy('`name` ASC')
						->findAll()
						->getData()
					);
					
					$this->set('arr', $arr);
					$this->set('hash_date', $hash_date);
					$this->set('selected_date', $selected_date);
				}
				$this->set('status', 'OK');
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionPreview()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
			{
				if(isset($_POST['tb_checkout']))
				{
					$_SESSION[$this->defaultForm] = $_POST;
						
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 211, 'text' => __('system_211', true)));
				}else{
					$hash_date = date('Y-m-d');
					if(isset($_GET['date']) && !empty($_GET['date']))
					{
						$hash_date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);
					}
					$selected_date = $hash_date;
					if($this->_is('selected_date'))
					{
						$selected_date = $this->_get('selected_date');
					}
	
					$arr = pjEventModel::factory()
						->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->select('t1.*, t2.content as title')
						->find($this->_get('id'))
						->getData();
	
					$ticket_arr = pjShowModel::factory()
						->join('pjMultiLang', "t2.model='pjPrice' AND t2.foreign_id=t1.price_id AND t2.field='price_name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjPrice', "t1.price_id=t3.id", 'left outer')
						->select("t1.id, t1.price_id, t1.price, t2.content as ticket")
						->where('t1.event_id', $this->_get('id'))
						->where("t1.date_time = '". $selected_date . ' ' . $this->_get('selected_time') . ":00'")
						->where("t1.venue_id", $this->_get('venue_id'))
						->findAll()
						->getData();
					$this->set('ticket_arr', $ticket_arr);
					$price_arr = $this->calculatePrice($ticket_arr, $this->_get('tickets'));
					$this->set('price_arr', $price_arr);
					
					$seat_arr = pjSeatModel::factory()
						->where('t1.venue_id', $this->_get('venue_id'))
						->findAll()
						->getData();
					$seat_name_arr = array();
					foreach($seat_arr as $v)
					{
						$seat_name_arr[$v['id']] = $v['name'];
					}
					$this->set('seat_name_arr', $seat_name_arr);
					
					$country_arr = pjCountryModel::factory()
						->select('t1.id, t2.content AS country_title')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->find($_SESSION[$this->defaultForm]['c_country'])
						->getData();
					
					$this->set('arr', $arr);
					$this->set('hash_date', $hash_date);
					$this->set('selected_date', $selected_date);
					$this->set('country_arr', $country_arr);
				}
				$this->set('status', 'OK');
			}else{
				$this->set('status', 'ERR');
			}
		}
	}
	
	public function pjActionCancel()
	{
		$this->setLayout('pjActionCancel');
	
		$pjBookingModel = pjBookingModel::factory();
	
		if (isset($_POST['booking_cancel']))
		{
			$booking_arr = pjBookingModel::factory()->find($_POST['id'])->getData();
			if (count($booking_arr) > 0)
			{
				$sql = "UPDATE `".$pjBookingModel->getTable()."` SET status = 'cancelled' WHERE SHA1(CONCAT(`id`, `created`, '".PJ_SALT."')) = '" . $_POST['hash'] . "'";
	
				$pjBookingModel->reset()->execute($sql);
	
				$booking_arr = pjAppController::pjActionGetBookingDetails($_POST['id']);
	
				pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, PJ_SALT, 'cancel');
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFront&action=pjActionCancel&err=200');
			}
		}else{
			if (isset($_GET['hash']) && isset($id))
			{
				$arr = pjAppController::pjActionGetBookingDetails($id);
				if (count($arr) == 0)
				{
					$this->set('status', 2);
				}else{
					if ($arr['status'] == 'cancelled')
					{
						$this->set('status', 4);
					}else{
						$hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
						if ($_GET['hash'] != $hash)
						{
							$this->set('status', 3);
						}else{
								
							$this->set('arr', $arr);
						}
					}
				}
			}else if (!isset($_GET['err'])) {
				$this->set('status', 1);
			}
		}
	}
	
	public function pjActionGetTime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$selected_date = date('Y-m-d');
			if(isset($_GET['date']) && !empty($_GET['date']))
			{
				$selected_date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);
			}
			$show_arr = pjShowModel::factory()
				->where('t1.event_id', $id)
				->where("DATE_FORMAT(t1.date_time,'%Y-%m-%d') = '$selected_date' AND t1.date_time >= NOW()")
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
			$this->set('selected_date', $selected_date);
			$this->set('time_arr', $time_arr);
		}
	}
	
	public function pjActionSaveDateTime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			$this->_set('id', $_POST['id']);
			$this->_set('selected_date', pjUtil::formatDate($_POST['selected_date'], $this->option_arr['o_date_format']));
			$this->_set('selected_time', $_POST['selected_time']);
			$this->_set('back_to', $_POST['back_to']);
			if($this->_is('venue_id'))
			{
			    unset($_SESSION[$this->defaultStore]['venue_id']);
			}
			$response['code'] = 200;
			pjAppController::jsonResponse($response);
			exit;
		}
	}
	
	
	
	public function pjActionSetVenue()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			$this->_set('venue_id', $_GET['venue_id']);
			if($this->_is('tickets'))
			{
				unset($_SESSION[$this->defaultStore]['tickets']);
			}
			if($this->_is('seat_id'))
			{
				unset($_SESSION[$this->defaultStore]['seat_id']);
			}
			$response['code'] = 200;
			pjAppController::jsonResponse($response);
			exit;
		}
	}
	
	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			
			if (!isset($_POST['tb_preview']) || !isset($_SESSION[$this->defaultForm]) || empty($_SESSION[$this->defaultForm]) || !isset($_SESSION[$this->defaultStore]) || empty($_SESSION[$this->defaultStore]))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 109, 'text' => __('system_109', true)));
			}
				
			if ((int) $this->option_arr['o_bf_include_captcha'] === 3 && (!isset($_SESSION[$this->defaultForm]['captcha']) ||
					!pjCaptcha::validate($_SESSION[$this->defaultForm]['captcha'], $_SESSION[$this->defaultCaptcha]) ))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110, 'text' => __('system_110', true)));
			}
			if(isset($_SESSION[$this->defaultCaptcha]))
			{
			    unset($_SESSION[$this->defaultCaptcha]);
			    unset($_SESSION[$this->defaultForm]['captcha']);
			}
			
			$ticket_arr = pjShowModel::factory()
				->join('pjPrice', "t1.price_id=t2.id", 'left outer')
				->select("t1.id, t1.price_id, t1.price")
				->where('t1.event_id', $this->_get('id'))
				->where("t1.date_time = '". $this->_get('selected_date') . ' ' . $this->_get('selected_time') . ":00'")
				->where("t1.venue_id", $this->_get('venue_id'))
				->findAll()
				->getData();
			
			$show_id_arr = array();
			$_price_arr = array();
			foreach($ticket_arr as $v)
			{
				$show_id_arr[$v['price_id']] = $v['id'];
				$_price_arr[$v['price_id']] = $v['price'];
			}
			
			$STORE = @$_SESSION[$this->defaultStore];
			$FORM = @$_SESSION[$this->defaultForm];
			
			$pjBookingShowModel = pjBookingShowModel::factory();
				
			$booking_event_id = $this->_get('id');
			$booking_date_time = $this->_get('selected_date') . ' ' . $this->_get('selected_time') . ':00';
				
			$booking_id_arr = pjBookingModel::factory()
				->where('event_id', $booking_event_id)
				->where('date_time', $booking_date_time)
				->where('status <>', 'cancelled')
				->where(sprintf("(t1.id IN(SELECT `TBS`.booking_id FROM `%s` AS `TBS` WHERE `TBS`.`seat_id` IN(SELECT `TS`.id FROM `%s` AS `TS` WHERE `TS`.venue_id='%u') ))", $pjBookingShowModel->getTable(), pjSeatModel::factory()->getTable(), $this->_get('venue_id')))
				->findAll()
				->getDataPair(null, 'id');
			$all_seats_arr = pjSeatModel::factory()
		        ->where('t1.venue_id', $this->_get('venue_id'))
				->findAll()
				->getData();
			
			$all_seats_cnt = array();
			foreach($all_seats_arr as $kk=>$vv){
				$all_seats_cnt[$vv['id']] = $vv['seats'];
			}
			
			if(!empty($booking_id_arr))
			{
				foreach($STORE['seat_id'] as $price_id => $seat_arr)
				{
					foreach($seat_arr as $seat_id => $cnt)
					{
						$cnt_booked = $pjBookingShowModel
							->reset()
							->join('pjShow', 't2.id = t1.show_id')
							->whereIn('t1.booking_id', $booking_id_arr)
							->where('t1.seat_id', $seat_id)
							->where('t2.event_id', $booking_event_id)
							->where('t2.date_time', $booking_date_time)
							->where('t2.venue_id', $this->_get('venue_id'))
							->findCount()
							->getData();
						if($all_seats_cnt[$seat_id]==1 && $cnt_booked > 0)
						{
							$system_text = __('system_118', true);
							$system_text = str_replace("[STAG]", "<a href='#' class='tbStartOverButton'>", $system_text);
							$system_text = str_replace("[ETAG]", "</a>", $system_text);
							pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 118, 'text' => $system_text));
							exit;
						} elseif($all_seats_cnt[$seat_id]>1 && $cnt_booked > $all_seats_cnt[$seat_id]) {
							$system_text = __('system_118', true);
							$system_text = str_replace("[STAG]", "<a href='#' class='tbStartOverButton'>", $system_text);
							$system_text = str_replace("[ETAG]", "</a>", $system_text);
							pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 118, 'text' => $system_text));
							exit;
						}
					}
				}
			}
			
			$price_arr = $this->calculatePrice($ticket_arr, $this->_get('tickets'));
			
			$data = array();
			
			$uuid = pjUtil::uuid();
			$data['uuid'] = $uuid;
			$data['event_id'] = $booking_event_id;
			$data['date_time'] = $booking_date_time;
			$data['sub_total'] = $price_arr['sub_total'];
			$data['tax'] = $price_arr['tax'];
			$data['total'] = $price_arr['total'];
			$data['deposit'] = $price_arr['deposit'];
			$data['status'] = $this->option_arr['o_booking_status'];
			$data['ip'] = pjUtil::getClientIp();
			
			$payment = 'none';
			if(isset($FORM['payment_method']))
			{
				if (isset($FORM['payment_method'])){
					$payment = $FORM['payment_method'];
				}
			}
			
			$pjBookingModel = pjBookingModel::factory();
			
			$id = $pjBookingModel->setAttributes(array_merge($FORM, $data))->insert()->getInsertId();
			if ($id !== false && (int) $id > 0)
			{
				
				$pjBookingTicketModel = pjBookingTicketModel::factory();
				foreach($STORE['seat_id'] as $price_id => $seat_arr)
				{
					$bs_data = array();
					$bt_data = array();
					
					$bs_data['booking_id'] = $id;
					$bs_data['show_id'] = $show_id_arr[$price_id];
					$bs_data['price_id'] = $price_id;
					$bs_data['price'] = $_price_arr[$price_id];
					
					$bt_data['booking_id'] = $id;
					$bt_data['price_id'] = $price_id;
					$bt_data['unit_price'] = $_price_arr[$price_id];
					$bt_data['is_used'] = 'F';
					
					foreach($seat_arr as $seat_id => $cnt)
					{
						$bs_data['seat_id'] = $seat_id;
						$bs_data['cnt'] = $cnt;
						$pjBookingShowModel->reset()->setAttributes($bs_data)->insert();
						
						$bt_data['seat_id'] = $seat_id;
						for($i = 1; $i <= $cnt; $i++)
						{
							$bt_data['ticket_id'] = $uuid . '-' . $seat_id . '-' . $i;
							$pjBookingTicketModel->reset()->setAttributes($bt_data)->insert();
						}
					}
				}
				
				$arr = pjAppController::pjActionGetBookingDetails($id);
				
				$pdata = array();
				$pdata['booking_id'] = $id;
				$pdata['payment_method'] = $payment;
				$pdata['payment_type'] = 'online';
				$pdata['amount'] = $arr['deposit'];
				$pdata['status'] = 'notpaid';
				pjBookingPaymentModel::factory()->setAttributes($pdata)->insert();
				pjAppController::buildPdfTickets($arr);
				$this->pjActionGenerateInvoice($arr);
				pjFront::pjActionConfirmSend($this->option_arr, $arr, PJ_SALT, 'confirm');
				
				unset($_SESSION[$this->defaultStore]);
				unset($_SESSION[$this->defaultForm]);
								
				$json = array('code' => 200, 'text' => '', 'booking_id' => $id, 'payment' => $payment);
				pjAppController::jsonResponse($json);
			}else {
				pjAppController::jsonResponse(array('code' => 'ERR', 'code' => 119, 'text' => __('system_119', true)));
			}
		}
	}
	
	public function pjActionGetPaymentForm()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$arr = pjBookingModel::factory()
				->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.event_id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->select('t1.*, t2.content as title')
				->find($_GET['booking_id'])
				->getData();
			
			$invoice_arr = pjInvoiceModel::factory()->where('t1.order_id', $arr['uuid'])->findAll()->limit(1)->getData();
			if (!empty($invoice_arr))
			{
				$invoice_arr = $invoice_arr[0];
				
				switch ($arr['payment_method'])
				{
					case 'paypal':
						$this->set('params', array(
							'name' => 'tbPaypal',
							'id' => 'tbPaypal',
							'business' => $this->option_arr['o_paypal_address'],
							'item_name' => pjSanitize::html($arr['title']),
							'custom' => $invoice_arr['uuid'],
							'amount' => $invoice_arr['paid_deposit'],
							'currency_code' => $this->option_arr['o_currency'],
							'return' => $this->option_arr['o_thank_you_page'],
							'notify_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmPaypal',
							'target' => '_self',
							'charset' => 'utf-8'
						));
						break;
					case 'authorize':
						$this->set('params', array(
							'name' => 'tbAuthorize',
							'id' => 'tbAuthorize',
							'target' => '_self',
							'timezone' => $this->option_arr['o_authorize_timezone'],
							'transkey' => $this->option_arr['o_authorize_transkey'],
							'x_login' => $this->option_arr['o_authorize_merchant_id'],
							'x_description' => pjSanitize::html($arr['title']),
							'x_amount' => $invoice_arr['paid_deposit'],
							'x_invoice_num' => $invoice_arr['uuid'],
							'x_receipt_link_url' => $this->option_arr['o_thank_you_page'],
							'x_relay_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmAuthorize'
						));
						break;
				}
			}
			$this->set('arr', $arr);
			$this->set('get', $_GET);
		}
	}
	
	public function pjActionConfirmAuthorize()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjAuthorize') === NULL)
		{
			$this->log('Authorize.NET plugin not installed');
			exit;
		}
		
		$pjInvoiceModel = pjInvoiceModel::factory();
		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['x_invoice_num'])
			->limit(1)
			->findAll()
			->getData();
		
		if (!empty($invoice_arr))
		{						
			$invoice_arr = $invoice_arr[0];
			$booking_arr = pjBookingModel::factory()
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			if (!empty($booking_arr))
			{
				$arr = pjAppController::pjActionGetBookingDetails($booking_arr[0]['id']);
				if (count($arr) == 0)
				{
					$this->log('No such booking');
					pjUtil::redirect($this->option_arr['o_thank_you_page']);
				}					
				
				if (count($arr) > 0)
				{
					$params = array(
						'transkey' => $this->option_arr['o_authorize_transkey'],
						'x_login' => $this->option_arr['o_authorize_merchant_id'],
						'md5_setting' => $this->option_arr['o_authorize_md5_hash'],
						'key' => md5($this->option_arr['private_key'] . PJ_SALT)
					);
					
					$response = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
					if ($response !== FALSE && $response['status'] === 'OK')
					{
						pjBookingModel::factory()
							->setAttributes(array('id' => $response['transaction_id']))
							->modify(array('status' => $this->option_arr['o_payment_status'], 'processed_on' => ':NOW()'));
		
						pjBookingPaymentModel::factory()->setAttributes(array('booking_id' => $response['transaction_id'], 'payment_type' => 'online'))
														->modify(array('status' => 'paid'));
						$pjInvoiceModel
							->reset()
							->set('id', $invoice_arr['id'])
							->modify(array('status' => 'paid', 'modified' => ':NOW()'));
							
						pjFront::pjActionConfirmSend($this->option_arr, $arr, PJ_SALT, 'payment');
						
					} elseif (!$response) {
						$this->log('Authorization failed');
					} else {
						$this->log('Booking not confirmed. ' . $response['response_reason_text']);
					}
					pjUtil::redirect($this->option_arr['o_thankyou_page']);
				}
			}else{
				$this->log('No such booking');
			}
		}else{
			$this->log('Invoice not found');
		}
	}
	public function pjActionConfirmPaypal()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjPaypal') === NULL)
		{
			$this->log('Paypal plugin not installed');
			exit;
		}
		
		$pjInvoiceModel = pjInvoiceModel::factory();
		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['custom'])
			->limit(1)
			->findAll()
			->getData();
		if (!empty($invoice_arr))
		{
			$invoice_arr = $invoice_arr[0];
			$booking_arr = pjBookingModel::factory()
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			if (!empty($booking_arr))
			{
				$arr = pjAppController::pjActionGetBookingDetails($booking_arr[0]['id']);
				
				if (count($arr) == 0)
				{
					$this->log('No such booking');
					pjUtil::redirect($this->option_arr['o_thank_you_page']);
				}					
				
				$params = array(
					'txn_id' => @$arr['txn_id'],
					'paypal_address' => $this->option_arr['o_paypal_address'],
					'deposit' => @$invoice_arr['paid_deposit'],
					'currency' => $this->option_arr['o_currency'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);
				$response = $this->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				
				if ($response !== FALSE && $response['status'] === 'OK')
				{
					$this->log('Booking confirmed');
					pjBookingModel::factory()->setAttributes(array('id' => $arr['id']))->modify(array(
						'status' => $this->option_arr['o_payment_status'],
						'txn_id' => $response['transaction_id'],
						'processed_on' => ':NOW()'
					));
					pjBookingPaymentModel::factory()->setAttributes(array('booking_id' => $arr['id'], 'payment_type' => 'online'))
													->modify(array('status' => 'paid'));
					
					$pjInvoiceModel
						->reset()
						->set('id', $invoice_arr['id'])
						->modify(array('status' => 'paid', 'modified' => ':NOW()'));
					
					pjFront::pjActionConfirmSend($this->option_arr, $arr, PJ_SALT, 'payment');
					
				} elseif (!$response) {
					$this->log('Authorization failed');
				} else {
					$this->log('Booking not confirmed');
				}
			}else{
				$this->log('No such booking');
			}
		}else{
			$this->log('Invoice not found');
		}
		pjUtil::redirect($this->option_arr['o_thankyou_page']);
	}	
	public function pjActionConfirmSend($option_arr, $booking_arr, $salt, $opt)
	{
		$Email = new pjEmail();
		if ($option_arr['o_send_email'] == 'smtp')
		{
			$Email
				->setTransport('smtp')
				->setSmtpHost($option_arr['o_smtp_host'])
				->setSmtpPort($option_arr['o_smtp_port'])
				->setSmtpUser($option_arr['o_smtp_user'])
				->setSmtpPass($option_arr['o_smtp_pass'])
				->setSender($option_arr['o_smtp_user'])
			;
		}
		$Email->setContentType('text/html');
		
		$admin_email = $this->getAdminEmail();
		$admin_phone = $this->getAdminPhone();
		$from_email = $this->getFromEmail($option_arr);
		
		$tokens = pjAppController::getData($option_arr, $booking_arr, PJ_SALT, $this->getLocaleId());
		
		$pjMultiLangModel = pjMultiLangModel::factory();
		
		$locale_id = isset($booking_arr['locale_id']) && (int) $booking_arr['locale_id'] > 0 ? (int) $booking_arr['locale_id'] : $this->getLocaleId();
		
		if ($option_arr['o_email_payment'] == 1 && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_payment_subject')
				->limit(0, 1)
				->findAll()->getData();
		
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send($message);
			}
		}
		if ($option_arr['o_admin_email_payment'] == 1 && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_payment_subject')
				->limit(0, 1)
				->findAll()->getData();
		
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
				->setTo($admin_email)
				->setFrom($from_email)
				->setSubject($lang_subject[0]['content'])
				->send($message);
			}
		}
		if(!empty($admin_phone) && $opt == 'payment')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_sms_payment_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $admin_phone;
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		
		if ($option_arr['o_email_confirmation'] == 1 && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_confirmation_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
					
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send($message);
			}
		}
		if ($option_arr['o_admin_email_confirmation'] == 1 && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_confirmation_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				$Email
					->setTo($admin_email)
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send($message);
			}
		}
		if(!empty($booking_arr['c_phone']) && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_sms_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $booking_arr['c_phone'];
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		if(!empty($admin_phone) && $opt == 'confirm')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_sms_confirmation_message')
				->limit(0, 1)
				->findAll()->getData();
			if (count($lang_message) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
				if($message != '')
				{
					$params = array(
							'text' => $message,
							'key' => md5($option_arr['private_key'] . PJ_SALT)
					);
					$params['number'] = $admin_phone;
					$this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}
			}
		}
		
		if ($option_arr['o_email_cancel'] == 1 && $opt == 'cancel')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_cancel_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_email_cancel_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($booking_arr['c_email'])
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send($message);
			}
		}
		if ($option_arr['o_admin_email_cancel'] == 1 && $opt == 'cancel')
		{
			$lang_message = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_cancel_message')
				->limit(0, 1)
				->findAll()->getData();
			$lang_subject = $pjMultiLangModel->reset()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $locale_id)
				->where('t1.field', 'o_admin_email_cancel_subject')
				->limit(0, 1)
				->findAll()->getData();
				
			if (count($lang_message) === 1 && count($lang_subject) === 1)
			{
				$message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
		
				$Email
					->setTo($admin_email)
					->setFrom($from_email)
					->setSubject($lang_subject[0]['content'])
					->send($message);
			}
		}
	}
	
	public function isXHR()
	{
		return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
	}
	
	static protected function allowCORS()
	{
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
		header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
		header("Access-Control-Allow-Origin: $origin");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With");
	}
}
?>