<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

use Ticket\Ticket;
class BookingModel extends App_Model {

	private $pjBookingModel;
	private $pjShowModel;
	private $pjBookingShowModel;
	private $pjBookingTicketModel;
	private $pjVenueModel;
	private $pjEventModel;
	private $pjMultiLangModel;
	private $pjInvoiceModel;

	private $bookingShowArray = array();
	private $bookingTicketArray = array();
	private $showIdArray = array();
	private $showArray = array();
	private $priceArray = array();
	private $priceIdArray = array();
	private $ticketArray = array();
	private $optionArray = array();
	private $creditCardInfo = array();
	private $billingAddress = array();
	
	private $table;

	// ### Ticket PDF
	private $hash = '';
	

	public function __construct() {
		parent::__construct();
		$this->pjShowModel 				= pjShowModel::factory();
		$this->pjBookingModel 			= pjBookingModel::factory();
		$this->pjBookingShowModel 		= pjBookingShowModel::factory();
		$this->pjBookingTicketModel 	= pjBookingTicketModel::factory();
		$this->pjEventModel 			= pjEventModel::factory();
		$this->pjMultiLangModel 		= pjMultiLangModel::factory();
		$this->pjInvoiceModel 			= pjInvoiceModel::factory();
		$this->pjVenueModel 			= pjVenueModel::factory();

		mt_srand();
		$this->hash = mt_rand(1000, 9999);

		
		

	}
	
	
	
	
	
	
	
	public function generatePdf($params)
	{
		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		
		require_once($dm->getPath('tcpdf') . 'tcpdf.php');
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$pdf->SetFont('dejavusans', '', 8);
		
		$uuid = '';
		
		foreach($params as $v)
		{
			$ticket = $this->generateTicket($v['ticket_img'], $v['ticket_id']);
			
			$pdf->AddPage();
			$pdf->Image($ticket, 10, 10, '', '', 'PNG', '', 'T', false, 300, '', false, false, 0, true, false, true);
			$pdf->Ln(100);
			
			$html = '<p style="color: #000; border:none;">' . preg_replace('/\r\n|\n/', '<br />', $v['ticket_info']) . '</p>';
			$pdf->writeHTMLCell(87, 19, 13, 68, $html, 0);
			
			$uuid = $v['uuid'];
		}
		
		$pdf->Output(PJ_INSTALL_PATH . PJ_UPLOAD_PATH . 'tickets/pdfs/p_'. $uuid .'.pdf', 'F');
		$filename = PJ_UPLOAD_PATH . 'tickets/pdfs/p_'. $uuid . '.pdf';
		return $filename;
	}
	
	public function save($data, $options = null) {
		$this->data = array();
		$this->response = array();
		$this->data = $data;
		$this->optionArray = $options;
		//App::dd($this->optionArray);
		
		if(is_array($this->data) && count($this->data) > 0) {
			if(is_array($this->data['creditCardInfo']) && count($this->data['creditCardInfo']) > 0) {
				$this->creditCardInfo = array($this->data['creditCardInfo']);
				foreach($this->creditCardInfo as $creditCardInfo) {
					///App::dd($creditCardInfo);
					$this->data['booking']['cc_num'] 		= $creditCardInfo['cc_num'];
					$this->data['booking']['cc_type'] 		= $creditCardInfo['cc_type'];
					$this->data['booking']['cc_exp_month'] = $creditCardInfo['cc_exp_month'];
					$this->data['booking']['cc_exp_year'] 	= $creditCardInfo['cc_exp_year'];
					$this->data['booking']['cc_code'] 		= $creditCardInfo['cc_code'];
				}
			}
			if(is_array($this->data['billingAddress']) && count($this->data['billingAddress']) > 0) {
				$this->billingAddress = array($this->data['billingAddress']);
				foreach($this->billingAddress as $billingAddress) {

					$this->data['booking']['c_name'] 		= $billingAddress['c_firstName'] . " " . $billingAddress['c_lastName'];
					$this->data['booking']['c_phone'] 		= $billingAddress['c_phone'];
					$this->data['booking']['c_email'] 		= $billingAddress['c_email'];
					$this->data['booking']['c_country'] 	= $billingAddress['c_country'];
					//$this->data['c_state'] 		= $billingAddress['state'];
					$this->data['booking']['c_city'] 		= $billingAddress['c_city'];
					$this->data['booking']['c_address'] 	= $billingAddress['c_address'];
					$this->data['booking']['c_zip'] 		= $billingAddress['c_zip'];
				}
			}
			
		}
		$this->data['booking']['event_id'] 			= $this->data['event_id'];
		$this->data['booking']['sub_total'] 		= $this->data['sub_total'];
		$this->data['booking']['total'] 			= $this->data['total'];
		$this->data['booking']['payment_method'] 	= $this->data['payment_method'];
		$this->data['booking']['description'] 		= $this->data['description'];
		$this->data['booking']['txn_id'] 			= $this->data['txn_id'];
		$this->data['booking']['status'] 			= $this->data['status'];
		$this->data['booking']['processed_on'] 		= $this->data['processed_on'];
		$this->data['booking']['created'] 			= $this->data['created'];
		$this->data['booking']['uuid'] = self::uuid();
		$this->data['booking']['ip'] = self::getClientIp();
		$insertId = $this->pjBookingModel->setAttributes($this->data['booking'])->insert()->getInsertId();
		
		$this->data['date_time'] = $this->data['date_time'];
		$arr = array();
		if($insertId !== false && (int) $insertId > 0) {
			
			$this->ticketArray = $this->pjShowModel
									->join('pjPrice', "t1.price_id=t2.id", 'left outer')
									->select("t1.id, t1.price_id, t1.price")
									->where('t1.event_id', $this->data['event_id'])
									->where("t1.date_time = '". $this->data['date_time'] . "'")
									->where("t1.venue_id", $this->data['venue_id'])
									->findAll()
									->getData();

			foreach($this->ticketArray as $v) {
				$this->showIdArray[$v['price_id']] = $v['id'];
				$this->priceIdArray[$v['price_id']] = $v['price'];
			}
			// App::dd($this->priceIdArray, false);
			// App::dd($this->data['seat_id']);
			foreach($this->data['seat_id'] as $priceId => $seatArray) {

				$this->bookingShowArray['booking_id'] 	= $insertId;
				$this->bookingShowArray['show_id'] 		= $this->showIdArray[$priceId];
				$this->bookingShowArray['price_id'] 	= $priceId;
				$this->bookingShowArray['price'] 		= $this->priceIdArray[$priceId];
				$this->bookingTicketArray['booking_id'] = $insertId;
				$this->bookingTicketArray['price_id'] = $priceId;
				$this->bookingTicketArray['unit_price'] = $this->priceIdArray[$priceId];
				$this->bookingTicketArray['is_used'] = 'F';

				foreach($seatArray as $seat_id => $cnt) {

					$this->bookingShowArray['seat_id'] = $seat_id;
					$this->bookingShowArray['cnt'] = $cnt;
					$this->pjBookingShowModel->reset()->setAttributes($this->bookingShowArray)->insert();
					$this->bookingTicketArray['seat_id'] = $seat_id;

					for($i = 1; $i <= $cnt; $i++)
					{
						$this->bookingTicketArray['ticket_id'] = $this->data['booking']['uuid'] . '-' . $seat_id . '-' . $i;
						$this->pjBookingTicketModel->reset()->setAttributes($this->bookingTicketArray)->insert();
					}
				}
			}
			$optionArr = $this->optionArray;
			$this->data = array_merge($optionArr, $this->data);
			//$this->data['o_ticket_image'] = $this->optionArray['o_ticket_image'];

			// ### fetch booking details
			$arr = 	$this->pjActionGetBookingDetails($insertId);
			// ### generate ticket
			//App::dd($arr);
			$this->buildPdfTickets($arr, $this->data);
			// ### generate invoice
			$this->pjActionGenerateInvoice($arr);
					   
			exit;
			// $this->response['error'] = 'AR03'; 
			// $this->response['data'] = $this->data; 
			// $this->response['arr'] = $arr; 
			// $this->response['insertId'] = $insertId;
			// return $this->response;
		} else {
			$this->response['error'] = 'AR04'; 
			$this->response['data'] = $this->data; 
			$this->response['arr'] = $arr; 
			$this->response['insertId']  = 0;
		}
		return $this->response;
	}


	private function pjActionGetBookingDetails($id, $options = null) {
		try {
			$this->optionArray = $options;
			$arr = $this->pjBookingModel
						->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.event_id AND t2.field='title' AND t2.locale='".$this->optionArray['localeId']."'", 'left outer')
						->join('pjMultiLang', "t3.model='pjCountry' AND t3.foreign_id=t1.c_country AND t3.field='name' AND t3.locale='".$this->optionArray['localeId']."'", 'left outer')
						->select(sprintf("t1.*, 
							AES_DECRYPT(t1.cc_type, '%1\$s') AS `cc_type`,
							AES_DECRYPT(t1.cc_num, '%1\$s') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp_month, '%1\$s') AS `cc_exp_month`,
							AES_DECRYPT(t1.cc_exp_year, '%1\$s') AS `cc_exp_year`,
							AES_DECRYPT(t1.cc_code, '%1\$s') AS `cc_code`, 
							t2.content as event_title, t3.content as country_title", PJ_SALT))
						->find($id)
						->getData();
			
			$_show_arr = $this->pjBookingShowModel
								->join('pjMultiLang', "t2.model='pjPrice' AND t2.foreign_id=t1.price_id AND t2.field='price_name' AND t2.locale='".$this->optionArray['localeId']."'", 'left outer')
								->join('pjShow', "t3.id = t1.show_id", "left outer")
								->join('pjSeat', "t4.id = t1.seat_id", "left outer")
								->select('t1.*, t2.content as price_name, t3.date_time, t4.name as seat_name')
								->where('t1.booking_id', $id)
								->findAll()
								->getData();
		
			$bt_arr = $this->pjBookingTicketModel
							->select("t2.venue_id")
							->join("pjSeat", "t2.id=t1.seat_id", "left")
							->where("booking_id", $id)
							->limit(1)
							->findAll()
							->getData();
			
			$arr['booking_date_time'] = $arr['date_time'];
			$arr['date_time'] = null;
			$arr['tickets'] = null;
			$arr['cnt_tickets'] = 0;
			$arr['hall'] = null;
			$seat_name_arr = array();
			$t_arr = array();
			$p_arr = array();
			$_tickets = array();
			
			if(count($bt_arr) > 0)
			{
				$venue_id = $bt_arr[0]['venue_id'];
				$venue_arr = $this->pjVenueModel
									->select('t1.*, t2.content AS hall')
									->join('pjMultiLang', "t2.model='pjVenue' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->optionArray['localeId']."'", 'left outer')
									->find($venue_id)
									->getData();
				$arr['hall'] = $venue_arr['hall'];
			}
			foreach($_show_arr as $v)
			{
				$seat_name_arr[] = $v['seat_name'];
				$arr['date_time'] = date($this->optionArray['o_date_format'], strtotime($v['date_time'])) . ', ' . date($this->optionArray['o_time_format'], strtotime($v['date_time']));
				$t_arr[$v['price_id']] = $v['price_name'] . '('.pjUtil::formatCurrencySign(number_format($v['price'], 2), $this->optionArray['o_currency']).')';
				if(!isset($p_arr[$v['price_id']]))
				{
					$p_arr[$v['price_id']] = $v['cnt'];
				}else{
					$p_arr[$v['price_id']] += $v['cnt'];
				}
				$arr['cnt_tickets'] += $v['cnt'];
			}
			foreach($t_arr as $price_id => $v)
			{
				$_tickets[] = $v . ' x ' . $p_arr[$price_id];
			}
		
			$arr['seats'] = join(', ', $seat_name_arr);
			$arr['tickets'] = join('<br/>', $_tickets);
			
			return $arr;
		} catch(\Exception $ex) {
			echo "error in pjActionGetBookingDetails".$ex->getMessage();
		}
		
	}
	
	private function buildPdfTickets($arr, $options = null) {
		try{
		$this->optionArray = $options;
		
		$event_arr = $this->pjEventModel
			->find($arr['event_id'])
			->getData();
		if(is_file($this->optionArray['o_ticket_image']))
		{
			//App::dd($this->optionArray['o_ticket_image']);
			$bt_arr = pjBookingTicketModel::factory()
				->join('pjBooking', 't1.booking_id = t2.id', 'left')
				->join('pjMultiLang', "t3.model='pjPrice' AND t3.foreign_id=t1.price_id AND t3.field='price_name' AND t3.locale='".$this->optionArray['localeId']."'", 'left outer')
				->join('pjSeat', 't1.seat_id = t4.id', 'left')
				->join('pjMultiLang', "t5.model='pjEvent' AND t5.foreign_id=t2.event_id AND t5.field='title' AND t5.locale='".$this->optionArray['localeId']."'", 'left outer')
				->join('pjMultiLang', "t6.model='pjCoutnry' AND t6.foreign_id=t2.c_country AND t6.field='name' AND t6.locale='".$this->optionArray['localeId']."'", 'left outer')
				->join('pjMultiLang', "t7.model='pjVenue' AND t7.foreign_id=t4.venue_id AND t7.field='name' AND t7.locale='".$this->optionArray['localeId']."'", 'left outer')
				->select("t2.*, t1.ticket_id, t1.seat_id, t1.price_id, t1.unit_price as price, t3.content as price_name, t4.name as seat_name, t5.content as event_title, t6.content as country_title, t7.content as hall")
				->where('t1.booking_id', $arr['id'])
				->findAll()
				->getData();
				//App::dd($bt_arr);
			$ticket_template = pjMultiLangModel::factory()->select('t1.*')
				->where('t1.model','pjOption')
				->where('t1.locale', $this->optionArray['localeId'])
				->where('t1.field', 'o_ticket_data')
				->limit(0, 1)
				->findAll()->getData();
				
			foreach($bt_arr as $k => $v)
			{
				$v['seats'] = $v['seat_name'];
				$v['tickets'] = $v['price_name'] . '('.pjUtil::formatCurrencySign($v['price'], $this->optionArray['o_currency']).')';
				$v['ticket_template'] = $this->optionArray['o_ticket_image'];
				$v['PJ_UPLOAD_PATH'] = PJ_UPLOAD_PATH;
				// $v['PJ_INSTALL_URL'] = PJ_INSTALL_URL;
				$v['ticket_info'] = '';
				$v['cnt_tickets'] = 0;
				$v['date_time'] = date($this->optionArray['o_date_format'], strtotime($v['date_time'])) . ', ' . date($this->optionArray['o_time_format'], strtotime($v['date_time']));
				
				$tokens = $this->getData($this->optionArray, $v, PJ_SALT, $this->optionArray['localeId']);
				if (count($ticket_template) === 1)
				{
					$ticket_info = str_replace($tokens['search'], $tokens['replace'], $ticket_template[0]['content']);
					$ticket_info = preg_replace('/\r\n|\n/', '<br />', $ticket_info);
					$v['ticket_info'] = $ticket_info;
				}
				$bt_arr[$k] = $v;
			}
			$ticket = new Ticket();
			$ticket->generatePdf($bt_arr);
		}
		} catch(\Exception $ex) {
			echo "error in buildPdfTickets".$ex->getMessage();
		}
	}
	private function getData($option_arr, $booking_arr, $salt, $locale_id)
	{
		$personal_titles = __('personal_titles', true, false);
		$cancelURL = PJ_INSTALL_URL . 'admin.php?controller=pjFront&action=pjActionCancel&id='.@$booking_arr['id'].'&hash='.sha1(@$booking_arr['id'].@$booking_arr['created'].$salt);
		$cancelURL = '<a href="'.$cancelURL.'">'.$cancelURL.'</a>';
		
		$PDFticket = '';
		if(is_file(PJ_INSTALL_PATH . PJ_UPLOAD_PATH . 'tickets/pdfs/p_' . $booking_arr['uuid'] . '.pdf'))
		{
			$PDFticket = PJ_INSTALL_URL . PJ_UPLOAD_PATH . 'tickets/pdfs/p_' . $booking_arr['uuid'] . '.pdf';
			$PDFticket = '<a href="'.$PDFticket.'">'.$PDFticket.'</a>';
		}
		
		$payment_methods = __('payment_methods', true, false);
		
		$search = array(
				'{Title}', 
				'{Name}', 
				'{Email}', 
				'{Phone}', 
				'{Country}', 
				'{City}', 
				'{State}', 
				'{Zip}', 
				'{Address}',
				'{Company}', 
				'{Notes}', 
				'{Movie}', 
				'{MovieID}', 
				'{Showtime}',
				'{BookingID}', 
				'{CinemaHall}',
				'{BookingSeats}',
				'{Tickets}',
				'{Deposit}', 
				'{Tax}',
				'{Total}',
				'{PaymentMethod}', 
				'{CCType}', 
				'{CCNum}', 
				'{CCExp}',
				'{CCSec}', 
				'{CancelURL}',
				'{TicketPrice}',
				'{PDFticket}'
				
		);
		$replace = array(
				(!empty($booking_arr['c_title']) ? $personal_titles[$booking_arr['c_title']] : null), 
				$booking_arr['c_name'], 
				$booking_arr['c_email'], 
				$booking_arr['c_phone'], 
				$booking_arr['country_title'], 
				$booking_arr['c_city'],
				$booking_arr['c_state'], 
				$booking_arr['c_zip'], 
				$booking_arr['c_address'],
				$booking_arr['c_company'],
				$booking_arr['c_notes'],
				$booking_arr['event_title'],
				$booking_arr['event_id'],
				$booking_arr['date_time'],
				$booking_arr['uuid'],
				$booking_arr['hall'],
				$booking_arr['seats'],
				$booking_arr['cnt_tickets'],
				pjUtil::formatCurrencySign($booking_arr['deposit'], $option_arr['o_currency']),
				pjUtil::formatCurrencySign($booking_arr['tax'], $option_arr['o_currency']),
				pjUtil::formatCurrencySign($booking_arr['total'], $option_arr['o_currency']),
				@$payment_methods[$booking_arr['payment_method']],
				@$booking_arr['cc_type'],
				@$booking_arr['cc_number'], 
				(@$booking_arr['payment_method'] == 'creditcard' ? @$booking_arr['cc_exp'] : NULL),
				@$booking_arr['cc_code'],
				$cancelURL,
				$booking_arr['tickets'],
				$PDFticket
				
		);
		
		return compact('search', 'replace');
	}
	private function pjActionGenerateInvoice($arr) {
		$map = array(
				'confirmed' => 'paid',
				'cancelled' => 'cancelled',
				'pending' => 'not_paid'
		);

		$last_id = 1;
		$invoice_arr = $this->pjInvoiceModel
							->limit(1)
							->orderBy("id DESC")
							->findAll()
							->getData();

		if(!empty($invoice_arr))
		{
			//App::dd($invoice_arr);
			$last_id = $invoice_arr[0]['id'] + 1;
		}
		/*
		$response = $this->requestAction(
				array(
					'controller' => 'pjInvoice',
					'action' => 'pjActionCreate',
					'params' => array(
					'key' => md5($this->option_arr['private_key'] . PJ_SALT),
					// -------------------------------------------------
					'uuid' => $last_id,
					'order_id' => $arr['uuid'],
					'foreign_id' => 1,
					'issue_date' => ':CURDATE()',
					'due_date' => ':CURDATE()',
					'created' => ':NOW()',
					// 'modified' => ':NULL',
					'status' => @$map[$arr['status']],
					'payment_method' => $arr['payment_method'],
					'cc_type' => $arr['cc_type'],
					'cc_num' => $arr['cc_num'],
					'cc_exp_month' => $arr['cc_exp_month'],
					'cc_exp_year' => $arr['cc_exp_year'],
					'cc_code' => $arr['cc_code'],
					'subtotal' => $arr['sub_total'],
					// 'discount' => $arr['discount'],
					'tax' => $arr['tax'],
					// 'shipping' => $arr['shipping'],
					'total' => $arr['total'],
					'paid_deposit' => $arr['deposit'],
					'amount_due' => $arr['total'] - $arr['deposit'],
					'currency' => $this->option_arr['o_currency'],
					'notes' => $arr['c_notes'],
					// 'y_logo' => $arr[''],
					// 'y_company' => $arr[''],
					// 'y_name' => $arr[''],
					// 'y_street_address' => $arr[''],
					// 'y_city' => $arr[''],
					// 'y_state' => $arr[''],
					// 'y_zip' => $arr[''],
					// 'y_phone' => $arr[''],
					// 'y_fax' => $arr[''],
					// 'y_email' => $arr[''],
					// 'y_url' => $arr[''],
					'b_billing_address' => $arr['c_address'],
					// 'b_company' => ':NULL',
					'b_name' => $arr['c_name'],
					'b_address' => $arr['c_address'],
					'b_street_address' => $arr['c_address'],
					'b_city' => $arr['c_city'],
					'b_state' => $arr['c_state'],
					'b_zip' => $arr['c_zip'],
					'b_phone' => $arr['c_phone'],
					// 'b_fax' => ':NULL',
					'b_email' => $arr['c_email'],
					// 'b_url' => $arr['url'],
					// 's_shipping_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
					// 's_company' => ':NULL',
					// 's_name' => (int) $arr['same_as'] === 1 ? $arr['b_name'] : $arr['s_name'],
					// 's_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
					// 's_street_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_2'] : $arr['s_address_2'],
					// 's_city' => (int) $arr['same_as'] === 1 ? $arr['b_city'] : $arr['s_city'],
					// 's_state' => (int) $arr['same_as'] === 1 ? $arr['b_state'] : $arr['s_state'],
					// 's_zip' => (int) $arr['same_as'] === 1 ? $arr['b_zip'] : $arr['s_zip'],
					// 's_phone' => $arr['phone'],
					// 's_fax' => ':NULL',
					// 's_email' => $arr['email'],
					// 's_url' => $arr['url'],
					// 's_date' => ':NULL',
					// 's_terms' => ':NULL',
					// 's_is_shipped' => ':NULL',
					'items' => array(
							array(
									'name' => $arr['event_title'],
									'description' => $arr['tickets'],
									'qty' => 1,
									'unit_price' => $arr['total'],
									'amount' => $arr['total']
							)
						)
					// -------------------------------------------------
					)
				),
				array('return')
		);
	
		return $response;*/
	}


	public function saveToBookingTable($data){
		$this->db->insert('tk_cbs_bookings', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	 }

	public static function uuid()
	{
		return chr(rand(65,90)) . chr(rand(65,90)) . time();
	}
	public static function getClientIp()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			return $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])) {
			return $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return 'UNKNOWN';
	}
	

	
}





