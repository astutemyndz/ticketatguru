<?php
namespace Library;
/**
 * Name:    CreditCardPayment
 *
 * Requirements: PHP5 or above
 *
 * @package    Paypal
 * @author     Rakesh Maity
 * @link       http://github.com/
 * @filesource
 */
//defined('BASEPATH') OR exit('No direct script access allowed');


use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
/**
 * Class CreditCardPayment
 */

class CreditCardPayment
{
    private $_api_context;
    private $CI;
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $CI = &get_instance();
        /** PayPal api context **/
        $paypal_conf = $CI->config->item('api_sandbox_paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
        

    }
    public function ApiContext() {
        return $this->_api_context;
    }
    public static function factory($attr=array())
	{
		return new CreditCardPayment($attr);
	}
    
}