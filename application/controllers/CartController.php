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

    /**
     * Add item to cart
     * @return json data
     */
    public function pjActionCart()
	{
        $this->setAjax(true);
       
		if ($this->isXHR())
		{
           
			$response = array();
			//mt_srand();
			//$id         = mt_rand(1, 9999);
			//$tickets    = ($this->has('tickets')) ? $this->post('tickets') : [];
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

           
            if(count($data) > 0) {
                if($this->cart->insert($data)){
                    $response['cart'] = TRUE;
                    $response['carItems'] = $this->cart->contents();
                    $response['responseText'] = 'Item added to cart';
                }else{
                    $response['cart'] = FALSE;
                    $response['carItems'] = [];
                    $response['responseText'] = 'Item not add to cart';
                }
            } else {
                $response['responseText'] = 'data is NULL';

            }
            $response['code'] = 200;
            $response['option_arr'] = $this->option_arr;
			pjAppController::jsonResponse($response);
			exit;
		}
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
     * Update header cart count button
     * @return json data
     */
    public function updateCartPini() {
        $cartItems = $this->cart->contents();
        $inCartItem = (count($cartItems) > 0) ? TRUE : FALSE;
        $li = NULL;
        $response = array();
        if($inCartItem) {
            $c = ($this->cart->contents()) ? count($this->cart->contents()) : 0;
            $li = '<a href="'.base_url().'cart">'.$c.'</a>';
            $response['cart'] = TRUE;
            $response['li'] = $li;
            //$response['responseText'] = 'Item added to cart';
            pjAppController::jsonResponse($response);
			exit;
        }

        return false;
    }
    public function pjActionCartDestroy() {
        $this->cart->destroy();
        return 1;
    }

	
}
