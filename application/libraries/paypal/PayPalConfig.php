<?php
/**
 * Name:    PayPalConfig
 *
 * Requirements: PHP5 or above
 *
 * @package    Paypal
 * @author     Rakesh Maity
 * @link       http://github.com/
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class PayPalConfig
 */

class PayPalConfig
{
    protected static $clientID;
    protected $secret;
    private $url;
    private $mode;
    private $diz;

    public function __construct()
    {
        $this->diz = self;
    }
    public static function ClientID() {
        $this->diz->clientID = ($this->config->item('api_paypal')['CLIENT_ID']) ? $this->config->item('api_paypal')['CLIENT_ID'] : $this->config->item('api_sandbox_paypal')['CLIENT_ID'];
        $this->diz;
    }
    public static function Secret() {
        $this->diz->secret = ($this->config->item('api_paypal')['SECRET']) ? $this->config->item('api_paypal')['SECRET'] : $this->config->item('api_sandbox_paypal')['SECRET'];
        $this->diz;
    }
    public static function URL() {
        $this->diz->url = ($this->config->item('api_paypal')['URL']) ? $this->config->item('api_paypal')['URL'] : $this->config->item('api_sandbox_paypal')['URL'];
        $this->dix;
    }
    public static function Mode() {
        $this->diz->url = ($this->config->item('api_paypal')['mode']) ? $this->config->item('api_paypal')['mode'] : $this->config->item('api_sandbox_paypal')['mode'];
        $this->dix;
    }
}