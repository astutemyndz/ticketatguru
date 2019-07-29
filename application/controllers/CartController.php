<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartController extends App_Controller {

    public $defaultStore = 'pjTicketBooking_Store';
	public $cartItems = array();

    function __construct() {
        parent::__construct();
    }
    /**
     * List of cart items or Cart page
     * @return view
     */
    public function index()	{
        
        $this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/event/cart');
		$this->load->view('frontend/layout/footer');
    }
    public function isCart() {
		return (count($this->cart->contents()) > 0) ? true : false;
	}	

    
    public function pjActionLoadMap() {
       
        $seatComponents = $this->LoadMapComponent();
        pjAppController::jsonResponse($seatComponents);
        exit;
    }
	
    
    /**
     * Load 
     */
    public function loadCartPage() {
        $cartItems = $this->cart->contents();
        $inCartItem = (count($cartItems) > 0) ? TRUE : FALSE;

        if($inCartItem) {
            pjAppController::jsonResponse($cartItems);
			exit;
        }

        return false;
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
            // echo "<pre>";
            // print_r($option_arr);
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
			if($this->defaultStore['ticket_arr'] && count($this->defaultStore['ticket_arr']) > 0)
			{
				foreach($this->defaultStore['ticket_arr'] as $v)
				{
					$ticket_name_arr[$v['price_id']] = pjSanitize::html($v['ticket']);
					$ticket_tooltip_arr['tooltip'][$v['price_id']] = pjSanitize::html($v['ticket']);// . ', ' .  pjUtil::formatCurrencySign($v['price'], $this->option_arr['o_currency']);
					$ticket_tooltip_arr['tooltip']['price'][$v['price_id']] = $v['price'];
					$ticket_tooltip_arr[$v['price_id']] = $v['price'];
					$ticket_tooltip_arr['tooltip']['price']['currency'][$v['price_id']] = $this->option_arr['o_currency'];
				}
			}
            // echo "<pre>";
            // print_r($ticket_tooltip_arr);
			$seatComponents = array();

			if($this->isCart()) {
				$cartItems = array();
				$cartItems = $this->cart->contents();

				if(count($cartItems) > 0 ) {
					$unique_ids = array_unique(array_column($this->cart->contents(), 'id'));
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
                            $seatComponents[] = self::SeatComponent(array('props' => array('id' => $seat['id'], 'name' => $seat['name'], 'price' => $price, 'price_id' => $seat['price_id'], 'tooltip' => $seat['name'], 'className' => $className, 'style' => $style)));
                                        
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
                            $seatComponents[] = self::SeatComponent(array('props' => array('id' => $seat['id'], 'name' => $seat['name'],'price' => $price, 'price_id' => $seat['price_id'], 'tooltip' => $seat['name'],'className' => $className, 'style' => $style)));
                        }
                    }
                } 
                

			} else {
                // echo "<pre>";
                // print_r($this->defaultStore['seat_arr']);
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
                    $tooltip[] = $ticket_tooltip_arr['tooltip'][$seat['price_id']];

                    $seatComponents[] = self::SeatComponent(array('props' => array('id' => $seat['id'], 'name' => $seat['name'], 'price' => $price, 'tooltip' => $seat['name'],'price_id' => $seat['price_id'], 'className' => $className, 'style' => $style)));
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
			$id         = ($this->has('id')) ? $this->post('id') : [];
			$price      = ($this->has('price')) ? (float)$this->post('price') : '';
			$name       = ($this->has('name')) ? $this->post('name') : '';
            $event      = $this->getSession($this->defaultStore)['arr'];
            


			$data = array(
				'id'		=>	$id,
				'qty' 		=>	1,
				'price' 	=>	$price,
				'name'		=>	$name,
				'options' 	=> 	array('event' => $event)
            );
          
            if($this->cart->insert($data)){
                $response['cart'] = TRUE;
                $response['responseText'] = 'Item added to cart';
                $response['spanArray'] = $this->LoadMapComponent();
                $response['li'] = $this->LIComponent();
            }else{
                $response['cart'] = FALSE;
                $response['responseText'] = 'Item not add to cart';
                $response['spanArray'] = $this->LoadMapComponent();
                $response['liy'] = $this->LIComponent();
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
            return $li;
        }
        return false;
    }
    /**
     * @return array of span
     */
    private static function SeatComponent($args = array()) {
		return "<span title=".$args['props']['tooltip']." data-id=".$args['props']['id']." data-name=test data-price_id=".$args['props']['price_id']." data-price=".$args['props']['price']." class='".$args['props']['className']."' style='".$args['props']['style']."'>".stripslashes($args['props']['name'])."</span>";
	}
    public function pjActionCartEmpty() {
        $this->cart->destroy();
        return 1;
    }


	
}
