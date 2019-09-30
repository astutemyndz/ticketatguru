<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartController extends App_Controller {

    public $defaultStore = 'pjTicketBooking_Store';
	public $cartItems = array();
    private $isCart = false;
    private $country;

    function __construct() {
        parent::__construct();
        $this->cart->product_name_safe = FALSE;
        $this->isCart = (count($this->cart->contents()) > 0) ? true : false;
        $this->load->model('LocationModel');
        
    }
    public function setCountry($value) {
        $this->country = $value;
        return $this;
    }
   
    /**
     * List of cart items or Cart page
     * @return view
     */
    public function index()	{
        $this->data['title'] 		= 'Cart';
        $this->data['page_heading'] 		= 'Cart';
        $this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/event/cart');
		$this->load->view('frontend/layout/footer');
    }
   
    
    public function pjActionLoadMap() {
       
        $seatComponents = $this->LoadMapComponent();
        pjAppController::jsonResponse($seatComponents);
        exit;
    }
    /*
	public function TableComponent($props = array()) {
        return '<div class="table-responsive">
					<table class="table">
							'.$this->TableTheadComponent($props).'		
                            '.$this->TableTbodyComponent($props).'
					</table>
				</div>';
    }
    private function TableTbodyComponent($props = array()) {
        $tbody = '<tbody id="'.$props['tbody']['id'].'" class="'.$props['tbody']['className'].'">';
        $tr = '';
            $counter = 0;
            if(isset($props['tbody']['body']) && is_array($props['tbody']['body']) && count($props['tbody']['body']) > 0 ) {
                foreach($props['tbody']['body'] as $th) {
                    $tr .= $this->TableBodyRowComponent($props, $counter);//<th>
                    $counter++;
                }
            }
        $tbody .= $tr; 
        $tbody .= '</tbody>';	
        return $tbody;

    }
    private function TableBodyRowComponent($props = array(), $counter) {
        return '<tr id='.$props['tbody']['tr']['id'].' class='.$props['tbody']['tr']['className'].'>'.$this->TableTdComponent($props, $counter).'</tr>';
    }
    private function TableTdComponent($props, $counter) {
        return '<td id="'.$props['tbody']['td']['id'].'" class="'.$props['tbody']['td']['className'].'">'.$props['tbody']['td']['component'].$props['tbody']['body'][$counter].'</td>';
    }
   
   
    private function TableTheadComponent($props = array()) {
        $thead = '<thead id="'.$props['thead']['id'].'" class="'.$props['thead']['className'].'"><tr>';
        $th = '';
            if(isset($props['thead']['th']) && is_array($props['thead']['th']) && count($props['thead']['th']) > 0 ) {
                $counter = 0;
                foreach($props['thead']['th']['columns'] as $ths) {
                    $th .= $this->TableThComponent($props, $counter);
                    $counter++;
                }
            }

        $thead .= $th.'</tr></thead>';	
        return $thead;

    }
    // private function TableTheadRowComponent($props = array()) {
    //     return '<tr id="'.$props['thead']['tr']['id'].'" class="'.$props['thead']['tr']['className'].'">'.$this->TableThComponent($props).'</tr>';
    // }
    private function TableThComponent($props, $counter) {
        return '<th id="'.$props['thead']['th']['id'].'" class="'.$props['thead']['th']['className'].'">'.$props['thead']['th']['component'].$props['thead']['th']['columns'][$counter].'</th>';
    }
   */
    
    /**
     * Load 
     */
    public function loadCart() {
        $rows = array();
        $total = 0;
        if($this->isCart) {
            $items = $this->cart->contents();
          //  App::dd($items);
            if(count($items) > 0) {
                foreach($items as $rowid => $item) {
                    $rows[] = '<tr class="cartTr" data-id="'.$rowid.'">
                                    <td>'.$item['name'].'</td>
                                    <td>'.(float)$item['price'].'.00</td>
                                    <td class="text-right"><button class="btn btn-sm btn-danger" data-id="'.$rowid.'"><i class="fa fa-trash"></i> </button> </td>
                                </tr>';
                    $total += (float) $this->cart->format_number($item['subtotal']); 
                   
                }
                //$this->cart->format_number($items['subtotal']);
            } else {
                $rows = [];
            }


            
        }
        // echo $total;
        // exit;
            $this->data['rows'] = $rows;
            $this->data['cart'] = ($this->cart->contents()) ? count($this->cart->contents()) : 0;
            $this->data['li'] = $this->LIComponent();
            $this->data['subtotal'] = ($total) ? $total.".00" : "0.00";
            // echo "<pre>";
            // print_r($this->data);
            pjAppController::jsonResponse($this->data);
			exit;
        //return false;
    }

    public function loadCartSummery() {
        $rows = array();
        $total = 0;
        if($this->isCart) {
            $items = $this->cart->contents();
            
            if(count($items) > 0) {
                foreach($items as $rowid => $item) {
                    $rows[] = '<tr>
                                    <td>'.$item['name'].'</td>
                                    <td>'.$item['price'].'</td>
                                </tr>';
                    $total += $this->cart->format_number($item['subtotal']); 
                   
                }
            } else {
                $rows = [];
            }
        }
            $this->data['rows'] = $rows;
            $this->data['cart'] = ($this->cart->contents()) ? count($this->cart->contents()) : 0;
            $this->data['li'] = $this->LIComponent();
            $this->data['subtotal'] = ($total) ? $total : 0;
            pjAppController::jsonResponse($this->data);
			exit;
    }
    /**
     * @method GET
     * @return Array of span element
     */
	private function LoadMapComponent() {
		$this->setAjax(true);
		if ($this->isXHR())
		{
			$this->defaultStore = ($this->session->userdata($this->defaultStore)) ? $this->session->userdata($this->defaultStore) : [];
            
			$class = 'tbAssignedNoMap';
			if(isset($this->defaultStore['venue_arr']))
			{
				if (is_file($this->defaultStore['venue_arr']['map_path']))
				{
					$class = 'tbAssignedSeats';
				}
			} 
			$ticket_name_arr = array();
            $ticket_tooltip_arr = array();
            //App::dd($this->defaultStore['ticket_arr']);
			if($this->defaultStore['ticket_arr'] && count($this->defaultStore['ticket_arr']) > 0)
			{
				foreach($this->defaultStore['ticket_arr'] as $v)
				{
					$ticket_tooltip_arr['ticketAttr'][$v['price_id']] = $v['id'];
					$ticket_name_arr['show_id'] = $v['id'];
					$ticket_name_arr[$v['price_id']] = pjSanitize::html($v['ticket']);
					$ticket_tooltip_arr['tooltip'][$v['price_id']] = pjSanitize::html($v['ticket']);// . ', ' .  pjUtil::formatCurrencySign($v['price'], $this->option_arr['o_currency']);
					$ticket_tooltip_arr['tooltip']['price'][$v['price_id']] = $v['price'];
					$ticket_tooltip_arr[$v['price_id']] = $v['price'];
					$ticket_tooltip_arr['tooltip']['price']['currency'][$v['price_id']] = $this->option_arr['o_currency'];
				}
			}
            
			$seatComponents = array();

			if($this->isCart) {
				$cartItems = array();
				$cartItems = $this->cart->contents();

				if(count($cartItems) > 0 ) {
                    $unique_ids = array_unique(array_column($this->cart->contents(), 'id'));
                    //App::dd($this->defaultStore['seat_arr']);
                    foreach($this->defaultStore['seat_arr'] as $seat) {
                        $is_selected = false;
                        $is_available = true;
                        $_arr = explode("~:~", $seat['price_id']);
                        $tooltip = array();
                        $price 		= 100;
                        if(in_array($seat['id'], $unique_ids)) {
                            $is_selected = true;
                            $is_available = false;
                            
                            $avail_seats = $seat['seats'] - $seat['cnt_booked'];
                            $className = "tbSeatRect";
                            $className .= ($avail_seats <= 0) ? ' tbSeatBlocked' : ($is_available == true ? ' tbSeatAvailable' : null);
                            $className .= $is_selected == true ? ' tbSeatSelected' : null;
                            $style 	 = "";
                            $style 	.= "width:".$seat['width']."px;";
                            $style	.= "height:".$seat['height']."px;";
                            $style 	.= "left: ".$seat['left']."px;";
                            $style 	.= "top:".$seat['top']."px;"; 
                            $style 	.= "line-height:".$seat['height']."px;";
                            $price = $ticket_tooltip_arr[$seat['price_id']];
                            $tooltip = $ticket_tooltip_arr['tooltip'][$seat['price_id']];
                            $ticketAttr = $ticket_tooltip_arr['ticketAttr'][$seat['price_id']];
                            $seatComponents[] = self::SeatComponent(array('props' => array('ticketAttr' => $ticketAttr, 'id' => $seat['id'], 'show' => $ticket_name_arr['show_id'], 'venue' => $seat['venue_id'], 'name' => $seat['name'], 'price' => $price, 'price_id' => $seat['price_id'], 'tooltip' => $seat['name'], 'className' => $className, 'style' => $style)));
                                        
                        } else {

                            $is_selected = false;
                            $is_available = true;
                            
                            $avail_seats = $seat['seats'] - $seat['cnt_booked'];
                            $className = "tbSeatRect";
                            $className .= ($avail_seats <= 0) ? ' tbSeatBlocked' : ($is_available == true ? ' tbSeatAvailable' : null);
                            $className .= $is_selected == true ? ' tbSeatSelected' : null;
                            $style 	 = "";
                            $style 	.= "width:".$seat['width']."px;";
                            $style	.= "height:".$seat['height']."px;";
                            $style 	.= "left: ".$seat['left']."px;";
                            $style 	.= "top:".$seat['top']."px;"; 
                            $style 	.= "line-height:".$seat['height']."px;";
                            $price = $ticket_tooltip_arr[$seat['price_id']];
                            $tooltip = $ticket_tooltip_arr['tooltip'][$seat['price_id']];
                            $ticketAttr = $ticket_tooltip_arr['ticketAttr'][$seat['price_id']];
                            $seatComponents[] = self::SeatComponent(array('props' => array('ticketAttr' => $ticketAttr,'id' => $seat['id'], 'show' => $ticket_name_arr['show_id'], 'venue' => $seat['venue_id'],'name' => $seat['name'],'price' => $price, 'price_id' => $seat['price_id'], 'tooltip' => $seat['name'],'className' => $className, 'style' => $style)));
                        }
                    }
                } 
                

			} else {
              
                foreach($this->defaultStore['seat_arr'] as $seat) {
                    $is_selected = false;
                    $is_available = true;
                    
                    $avail_seats = $seat['seats'] - $seat['cnt_booked'];
                    $className = "tbSeatRect";
                    $className .= ($avail_seats <= 0) ? ' tbSeatBlocked' : ($is_available == true ? ' tbSeatAvailable' : null);
                    $className .= $is_selected == true ? ' tbSeatSelected' : null;
                    $style 	 = "";
                    $style 	.= "width:".$seat['width']."px;";
                    $style	.= "height:".$seat['height']."px;";
                    $style 	.= "left: ".$seat['left']."px;";
                    $style 	.= "top:".$seat['top']."px;"; 
                    $style 	.= "line-height:".$seat['height']."px;";
                    $price = $ticket_tooltip_arr[$seat['price_id']];
                    $tooltip = $ticket_tooltip_arr['tooltip'][$seat['price_id']];
                    $ticketAttr = $ticket_tooltip_arr['ticketAttr'][$seat['price_id']];
                   // $seatComponents[] = self::SeatComponent(array('props' => array('id' => $seat['id'], 'name' => $seat['name'], 'price' => $price, 'tooltip' => $seat['name'],'price_id' => $seat['price_id'], 'className' => $className, 'style' => $style)));
                    $seatComponents[] = self::SeatComponent(array('props' => array('ticketAttr' => $ticketAttr, 'id' => $seat['id'], 'show' => $ticket_name_arr['show_id'], 'venue' => $seat['venue_id'],'name' => $seat['name'],'price' => $price, 'price_id' => $seat['price_id'], 'tooltip' => $seat['name'],'className' => $className, 'style' => $style)));
                }
              
            }
			// pjAppController::jsonResponse(array('status' => TRUE, 'code' => 200, 'data' => $seatComponents));
            // exit;
            return $seatComponents;
		}
    }
    /**
     * Add item to cart
     * @method POST
     * @return json data
     */
    public function pjActionCart()
	{
        $this->setAjax(true);
		if ($this->isXHR())
		{
            $response = array();
            $price_id      = ($this->has('price_id')) ? $this->post('price_id') : '';
            $seat_id         = ($this->has('seat_id')) ? $this->post('seat_id') : ''; // seat_id
            $show_id      = ($this->has('show_id')) ? $this->post('show_id') : '';
			$venue_id      = ($this->has('venue_id')) ? $this->post('venue_id') : '';
			$ticketType      = ($this->has('type')) ? $this->post('type') : '';
			
            
            $name       = ($this->has('name')) ? $this->post('name') : '';
            $price      = ($this->has('price')) ? (float)$this->post('price') : '';
            $event      = $this->getSession($this->defaultStore)['arr'];
            
            ///$seatId = array();
            $event_id         = ($event) ? $event['id'] : '';
            
			$data = array(
				'id'		=>	$seat_id,
				'qty' 		=>	1,
				'price' 	=>	$price,
				'name'		=>	$name,
                'options' 	=> 	array(),
                'event_id'  => $event_id, 
                'venue_id'  => $venue_id, 
                'show_id'   => $show_id,
                'tickets'   => array($ticketType => $price_id), 
                'seat_id'   => array($price_id => $seat_id), 
                'price_id'   => $price_id, 
                'o_currency'=> $this->option_arr['o_currency'],
                'event'     => $event
            );
            //App::dd($data);
            // try {

            // } catch( \)
            if($this->cart->insert($data)) {
                $this->session->set_userdata('show', $show_id);
                $response['cart'] = TRUE;
                $response['responseText'] = 'Item added to cart';
                $response['spanArray'] = $this->LoadMapComponent();
                $response['li'] = $this->LIComponent();
            }else{
                $response['cart'] = FALSE;
                $response['responseText'] = 'Item not add to cart';
                $response['spanArray'] = $this->LoadMapComponent();
                $response['li'] = $this->LIComponent();
            }
           
            $response['code'] = 200;
            $response['option_arr'] = $this->option_arr;
			pjAppController::jsonResponse($response);
			exit;
		}
    }
    /**
     * Update header cart count button
     * @return li component
     */
    private function LIComponent() {
        $cartItems = $this->cart->contents();
        $inCartItem = (count($cartItems) > 0) ? TRUE : FALSE;
        $li = NULL;
        if($inCartItem) {
            $c = ($this->cart->contents()) ? count($this->cart->contents()) : 0;
            $li = '<a href="'.base_url().'cart">'.$c.'</a>';
            //return $li;
        } else {
            $li = '<a href="'.base_url().'cart">0</a>';
        }
        return $li;
    }
    /**
     * @return array of span
     */
    private static function SeatComponent($args = array()) {
		return "<span data-type=".$args['props']['ticketAttr']." data-show=".$args['props']['show']." data-venue=".$args['props']['venue']." title='".$args['props']['tooltip']."' data-id=".$args['props']['id']." data-name='".$args['props']['name']."' data-price_id=".$args['props']['price_id']." data-price=".$args['props']['price']." class='".$args['props']['className']."' style='".$args['props']['style']."'></span>";
	}
    public function pjActionCartEmpty() {
        //$this->unsetSession('show_id');
       // $this->unsetSession('price_id');
        $this->cart->destroy();
        return 1;
    }

    public function removeCartItemOnClickEventListener() {
        $this->setAjax(true);
		if ($this->isXHR())
		{
            $rowid = ($this->input->post('rowId')) ? $this->input->post('rowId') : '';
            $this->cart->update(['rowid' => $rowid, 'qty' => 0]);
            $this->loadCart();
        }
        return false;
    }
    // Checkout Page start of code
    public function checkout() {
       // App::dd($_SESSION);
        $this->load->library(array('ion_auth'));
        if (!$this->ion_auth->logged_in())
        {
          redirect('auth/login');
        }

        if (!$this->cart->contents())
        {
          redirect('cart');
        }

        $this->load->model('UserModel');
        if($this->ion_auth->logged_in()) {
            $this->UserModel->setUserId($this->getSession('user_id'));
            $this->data['user'] = $this->UserModel->getUser();
        }
        $this->setCountry($this->LocationModel->countries());
        foreach($this->country as $c) {
            $this->data['countries'][] = array(
                'value' => $c['sortname'],
                'text' => $c['name'],
                'id' => $c['id'],
            ); 
        }
        //App::dd($this->data);
        $this->data['title'] 		= 'Checkout Page';
        $this->data['page_heading'] 		= 'Checkout';
        $this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/cart/checkout', $this->data);
		$this->load->view('frontend/layout/footer');
      
    }

    public function pjActionCheckout() {
        if (!$this->ion_auth->logged_in())
        {
          redirect('auth/login');
        }
        $this->data['page_heading'] 		= 'Checkout';
    }
	
}
