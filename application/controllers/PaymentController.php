<?php
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Config\Config;
use PayPal\Auth\OAuthTokenCredential;

class PaymentController extends App_Controller
{
   
    private $_api_context;
    private $accessToken;
    private $payPal = false;
    private $defaultConfig;
    private $billingAddress;
    private $cartItems;
    private $creditCardInfo;
    //prote $data;
    private $creditCard;
    private $item;
    private $itemList;
    private $amount;
    private $currency;
    private $total;
    private $transaction;
    public function __construct()
    {
       parent::__construct();

       $this->defaultConfig = array();
       $this->billingAddress = array();
       $this->cartItems = array();
       $this->creditCardInfo = array();
       $this->data = array();
       
       $this->option_arr['paypal'] = false;
       $this->option_arr['debug'] = true;
       
       if($this->getPayPalConfig()) {
           $this->defaultConfig = $this->getPayPalConfig();
       } else {
           $this->defaultConfig = $this->getSandBoxConfig();
       }

       $this->_api_context = new ApiContext(new OAuthTokenCredential($this->defaultConfig['client_id'], $this->defaultConfig['secret']));
       $this->_api_context->setConfig($this->defaultConfig['settings']);
    }

    private function getPayPalConfig() {
        return ($this->option_arr['paypal']) ? $this->option_arr['paypal'] : [];
    }

    private function getSandBoxConfig() {
        return Config::getSandBoxConfig();
    }
  
    
  

    public function getAccessToken() {
        $OAuthTokenCredential = new OAuthTokenCredential($this->defaultConfig['client_id'], $this->defaultConfig['secret']);
        return $this->getAccessToken = $OAuthTokenCredential->getAccessToken($this->defaultConfig);
    }

    public function setBillingAddress($value) {
        $this->billingAddress = $value;
        return $this;
    }
    public function getBillingAddress() {
        return $this->billingAddress;
    }

    public function getCartItems() {
        if($this->cart->contents()) {
            $this->cartItems = $this->cart->contents();
        } 
        return $this->cartItems;
    }

    public function setCreditCardInfo($value) {
        $this->creditCardInfo = $value;
        return $this;
    }

    public function getCreditCardInfo() {
        return $this->creditCardInfo;
    }

    public function creditCardPayment() {
        // get post data
        $this->data = ($this->input->post()) ? $this->input->post() : [];
        // set billing address
        $this->setBillingAddress($this->data['billingAddress']);
        $this->billingAddress = $this->getBillingAddress();

        // get cart items
        $this->cartItems = $this->getCartItems();

        // set credit card info
        $this->setCreditCardInfo($this->data['creditCardInfo']);
        $this->creditCardInfo = $this->getCreditCardInfo();
        
        
        $this->item = new Item();

        if($this->cartItems) {
            foreach($this->cartItems as $cartItem) {
                $this->item->setName($cartItem['name']);
                $this->item->setQuantity($cartItem['qty']);
                $this->item->setPrice($cartItem['price']);
                $this->item->setCurrency($cartItem['options']['o_currency']);
                
                $this->currency = $cartItem['options']['o_currency'];
                $this->total += $this->format_number($cartItem['subtotal']);
            }
        }
        $this->itemList = new ItemList();
        if($this->item) {
            $this->itemList->setItems(array($this->item));
        }
        
        $this->amount = new Amount();
        $this->amount->setCurrency($this->currency);
        $this->amount->setTotal($this->total);

        $this->transaction = new Transaction();
        $this->transaction->setItemList($this->itemList);
        $this->transaction->setAmount($this->amount);

        $this->creditCard = new CreditCard();
        $this->creditCard->setNumber($this->creditCardInfo['number']);
        $this->creditCard->setType($this->creditCardInfo['type']);
        $this->creditCard->setExpireMonth($this->creditCardInfo['expiryMonth']);
        $this->creditCard->setExpireYear($this->creditCardInfo['expiryYear']);
        $this->creditCard->setCvv2($this->creditCardInfo['cvv2']);
        $this->creditCard->setBillingAddress($this->billingAddress);

        
        try {
            $this->creditCard->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if ($this->isDebug()) {
                $this->setSession('error', 'Connection timeout');
            } else {
                $this->setSession('error', 'Some error occur, sorry for inconvenient');
            }
        }
    }

    private function isDebug() {
        return ($this->option_arr['debug']) ? $this->option_arr['debug'] : false;
    }

}