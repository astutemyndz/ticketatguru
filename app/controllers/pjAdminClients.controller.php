<?php 

if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}


class pjAdminClients extends pjAdmin
{
    public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminClients.js');
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjEventModel = pjEventModel::factory();
			
			if (isset($_POST['booking_create']))
			{
				$data = array();
				
				$pjBookingModel = pjBookingModel::factory();
				
				$data['uuid'] = pjUtil::uuid();
				$data['ip']= pjUtil::getClientIp();
				
				$post = array_merge($_POST, $data);

				if (!$pjBookingModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=AR04");
				}
				
				$insert_id = $pjBookingModel->setAttributes($post)->insert()->getInsertId();
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					$ticket_arr = pjShowModel::factory()
						->join('pjPrice', "t1.price_id=t2.id", 'left outer')
						->select("t1.id, t1.price_id, t1.price")
						->where('t1.event_id', $_POST['event_id'])
						->where("t1.date_time = '". $_POST['date_time'] . "'")
						->where("t1.venue_id", $_POST['venue_id'])
						->findAll()
						->getData();
					$price_arr = $this->calculatePrice($ticket_arr, $_POST['tickets']);
					
					$show_id_arr = array();
					$_price_arr = array();
					foreach($ticket_arr as $v)
					{
						$show_id_arr[$v['price_id']] = $v['id'];
						$_price_arr[$v['price_id']] = $v['price'];
					}
					$pjBookingShowModel = pjBookingShowModel::factory();
					$pjBookingTicketModel = pjBookingTicketModel::factory();
					foreach($_POST['seat_id'] as $price_id => $seat_arr)
					{
						$bs_data = array();
						$bt_data = array();
						
						$bs_data['booking_id'] = $insert_id;
						$bs_data['show_id'] = $show_id_arr[$price_id];
						$bs_data['price_id'] = $price_id;
						$bs_data['price'] = $_price_arr[$price_id];
						
						$bt_data['booking_id'] = $insert_id;
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
								$bt_data['ticket_id'] = $data['uuid'] . '-' . $seat_id . '-' . $i;
								$pjBookingTicketModel->reset()->setAttributes($bt_data)->insert();
							}
						}
					}
					$arr = pjAppController::pjActionGetBookingDetails($insert_id);
					pjAppController::buildPdfTickets($arr);
					pjAppController::pjActionGenerateInvoice($arr);
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=AR03");
				} else {
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=AR04");
				}
			}else{
				$pjBookingModel = pjBookingModel::factory();
				$pjEventModel = pjEventModel::factory();
				
				$country_arr = pjCountryModel::factory()
					->select('t1.id, t2.content AS country_title')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy('`country_title` ASC')
					->findAll()
					->getData();
				
				$event_arr = pjEventModel::factory()
					->join('pjMultiLang', "t2.model='pjEvent' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->select(" t1.*, t2.content as title")
					->where("t1.status", "T")
					->where("(t1.id IN (SELECT TS.event_id FROM `".pjShowModel::factory()->getTable()."` AS TS WHERE TS.date_time >= NOW()) )")
					->orderBy("t1.created DESC")
					->findAll()
					->getData();
				
				if(isset($_GET['event_id']) && (int) $_GET['event_id'] > 0 )
				{
					$pjShowModel = pjShowModel::factory();
					
					$event_id = $_GET['event_id'];
					$date_time = date('Y-m-d H:i:s', $_GET['ts']);
					
					$show_arr = $pjShowModel
						->select("DISTINCT t1.date_time")
						->where('t1.event_id', $event_id)
						->where("t1.date_time >= NOW()")
						->orderBy("t1.date_time ASC")
						->findAll()
						->getData();
					
					$_show_arr = $pjShowModel
						->reset()
						->where('t1.event_id', $event_id)
						->where("t1.date_time = '". $date_time . "'")
						->limit(1)
						->findAll()
						->getData();
					if(count($_show_arr) > 0)
					{
						$venue_id = $_show_arr[0]['venue_id'];
					}
					if($venue_id != null)
					{
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
											FROM `".pjBookingShowModel::factory()->getTable()."` AS `TBS`
											WHERE TBS.show_id=t1.id AND TBS.booking_id IN (SELECT TB.id FROM `".pjBookingModel::factory()->getTable()."` AS TB WHERE TB.event_id=".$event_id." AND TB.status='confirmed') ), 0)										
										)
								  ) as cnt_tickets")
							->where('t1.event_id', $event_id)
							->where("t1.date_time = '". $date_time . "'")
							->where("t1.venue_id", $venue_id)
							->findAll()
							->getData();
						
						$venue_arr = pjVenueModel::factory()->find($venue_id)->getData();
						$has_map = 1;
						if (empty($venue_arr['map_path']))
						{
							$has_map = 0;
						}
						$seat_arr = pjSeatModel::factory()
							->select("t1.*, (SELECT GROUP_CONCAT( TS.price_id SEPARATOR '~:~') FROM `".pjShowModel::factory()->getTable()."` AS TS WHERE TS.event_id='".$event_id."' AND TS.date_time = '". $date_time . "' AND TS.id IN (SELECT TSS.show_id FROM `".pjShowSeatModel::factory()->getTable()."` AS TSS WHERE TSS.seat_id=t1.id) ) AS price_id,
												(
													IFNULL((SELECT SUM(TBS.cnt)
													FROM `".pjBookingShowModel::factory()->getTable()."` AS `TBS`
													WHERE TBS.show_id IN (SELECT TS.id FROM `".pjShowModel::factory()->getTable()."` AS TS WHERE TS.event_id='".$event_id."' AND TS.date_time = '". $date_time . "') AND TBS.seat_id=t1.id AND TBS.booking_id IN (SELECT TB.id FROM `".pjBookingModel::factory()->getTable()."` AS TB WHERE TB.event_id=".$event_id." AND TB.status='confirmed')), 0)
												)
											AS cnt_booked ")
							->where('t1.venue_id', $venue_id)
							->where("t1.id IN (SELECT TSS.seat_id FROM `".pjShowSeatModel::factory()->getTable()."` AS TSS WHERE TSS.show_id IN (SELECT TS.id FROM `".pjShowModel::factory()->getTable()."` AS TS WHERE TS.event_id='".$event_id."' AND TS.date_time = '". $date_time . "') )")
							->findAll()
							->getData();
					
						$seat_name_arr = array();
						foreach($seat_arr as $v)
						{
							$seat_name_arr[$v['id']] = $v['name'];
						}
						$ticket_name_arr = array();
						foreach($ticket_arr as $v)
						{
							$ticket_name_arr[$v['price_id']] = pjSanitize::html($v['ticket']);
						}
					
						$this->set('seat_arr', $seat_arr);
						$this->set('seat_name_arr', $seat_name_arr);
						$this->set('ticket_arr', $ticket_arr);
						$this->set('date_time', $date_time);
						$this->set('ticket_name_arr', $ticket_name_arr);
						$this->set('show_arr', $show_arr);
						$this->set('has_map', $has_map);
					}
				}
				
				$this->set('event_arr', $event_arr);
				$this->set('country_arr', $country_arr);
				
				$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdminBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

}


?>