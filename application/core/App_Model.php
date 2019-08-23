<?php 
class App_Model extends CI_Model {
    protected $data;
    protected $errors;
    protected $response;
    protected $post;
    private $model;
	protected $options;
	protected $common;
    public function __construct()
    {
		parent::__construct();
		$this->load->library('Common_lib');
		$this->common = new Common_lib();
    }

    public static function pjBookingModel() {
		return pjBookingModel::factory();
    }
    
    public static function UUID() {
        return pjUtil::uuid();
    }

    public static function ClientIP() {
        return pjUtil::getClientIp();
    }
    public function getInsertedId($model, $data) {		
		$this->model = $model;
		$this->data = $data;
		return $this->model->setAttributes($this->data)->insert()->getInsertedId();
    }
    
    public function setPost($value) {
        $this->post = $value;
        return $this;
    }
    public function getPost() {
        return $this->post;
    }
    public function setModel($value) {
        $this->model = $value;
        return $this;
    }
    public function getModel() {
        return $this->model;
    }

    public static function CalculatePrice($ticket_arr, $chosen_ar, $o_tax_payment, $o_deposit_payment)
	{
		$price_arr = array();
		$sub_total = 0;
		$tax = 0;
		$total = 0;
		$deposit = 0;
		
		foreach($ticket_arr as $v)
		{
				//App::dd($chosen_ar);
			if(isset($chosen_ar[$v['id']][$v['price_id']]) && $chosen_ar[$v['id']][$v['price_id']] > 0)
			{
				//echo ">";
				$sub_total += $chosen_ar[$v['id']][$v['price_id']] * $v['price'];
			}
		}
		if($sub_total > 0)
		{
			$tax = ($sub_total * $o_tax_payment) / 100;
			$total = $sub_total + $tax;
			$deposit = ($total * $o_deposit_payment) / 100;
		} 
		return compact('sub_total', 'tax', 'total', 'deposit');
    }

    public function getSession($key)
	{
		return $this->session->userdata($key);
	}
	
	public function hasSession($key)
	{
		if($this->getSession($key)) {
			return true;
		}
		return false;
	}
	
	public function setSession($key, $value)
	{
		$this->session->set_userdata($key, $value);
		return $this;
	}
    


}