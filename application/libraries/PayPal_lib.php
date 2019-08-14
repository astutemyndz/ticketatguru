<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PayPal_lib
{
    public function __construct()
    {
        require_once APPPATH.'third_party/PayPal.php';
    }
    
}