<?php

use PayPal\Api\BillingAddress;
use PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Payment\CreditCardPayment;
class PayPalPaymentController extends App_Controller {

    private $_api_context;
    private $accessToken;
    private $payPal = false;
    private $defaultConfig;
    private $billingAddress;
    private $shippingAddress;
    private $cartItems;
    private $creditCardInfo;
    private $creditCard;
    private $item;
    private $itemList;
    private $amount;
    private $currency;
    private $total;
    private $transaction;
    private $creditCardPayment;

    public function __construct()
    {
       parent::__construct();

       $this->defaultConfig = array();
       $this->billingAddress = array();
       $this->shippingAddress = array();
       $this->cartItems = array();
       $this->creditCardInfo = array();
       $this->data = array();
       
       $this->option_arr['paypal'] = false;
       $this->option_arr['debug'] = true;

       $this->creditCardPayment = new CreditCardPayment($this->config->item('paypal'));
       
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
        return $this->shippingAddress;
    }
    public function setShippingAddress($value) {
        $this->shippingAddress = $value;
        return $this;
    }
    public function getShippingAddress() {
        return $this->shippingAddress;
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
    private function isDebug() {
        return ($this->option_arr['debug']) ? $this->option_arr['debug'] : false;
    }
    
    private function getRequest() {
        return ($this->input->post()) ? $this->input->post() : [];
    }
    private function setCurrency($value) {
        $this->currency = $value;
        return $this;
    } 
    private function getCurrency() {
        return $this->currency;
    }
    private function setTotal($value) {
        $this->total = $value;
        return $this;
    } 
    private function getTotal() {
        return $this->total;
    }
    /*
    * Process payment using credit card
    */
    public function payWithCreditCard()
    {
        
        
         // ### get post data
         $this->data = $this->getRequest();
         //App::dd($this->data);
         // ### set billing address
         $this->setShippingAddress($this->data['billingAddress']);
         $this->shippingAddress = $this->getShippingAddress();

        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        $shippingAddress = new ShippingAddress();
        $shippingAddress
            ->setLine1('line1')
            ->setLine2('line2')
            ->setCity($this->shippingAddress['city'])
            //->setState($this->billingAddress['state'])
            ->setPostalCode($this->shippingAddress['postalCode'])
            ->setCountryCode($this->shippingAddress['countryCode'])
            ->setPhone($this->shippingAddress['phone'])
            ->setRecipientName($this->shippingAddress['firstName']. " ". $this->shippingAddress['lastName']);
        // ### set credit card info
        $this->setCreditCardInfo($this->data['creditCardInfo']);
        $this->creditCardInfo = $this->getCreditCardInfo();

        // ### CreditCard
        $this->creditCard = new \PayPal\Api\CreditCard();
        $this->creditCard->setNumber($this->creditCardInfo['number']);
        $this->creditCard->setType($this->creditCardInfo['type']);
        $this->creditCard->setExpireMonth($this->creditCardInfo['expireMonth']);
        $this->creditCard->setExpireYear($this->creditCardInfo['expireYear']);
        $this->creditCard->setCvv2($this->creditCardInfo['cvv2']);

       

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // Use a Payer ID (A unique identifier of the payer generated
        // and provided by the facilitator. This is required when
        // creating or using a tokenized funding instrument)
        // and the `CreditCardDetails`
        $fi = new \PayPal\Api\FundingInstrument();
        $fi->setCreditCard($this->creditCard);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = new \PayPal\Api\Payer();//$this->creditCardPayment->payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments([$fi]);
            //App::dd($payer);
        // get cart items

        $this->cartItems = $this->getCartItems();
        
        
        if($this->cartItems) {
            $i = 0;
            foreach($this->cartItems as $cartItem) {
                // create a new item object 
                $this->item[$i] = new \PayPal\Api\Item();
                // set value an item object
                $this->item[$i]->setName($cartItem['name'])
                            ->setDescription('Ticket(s)')
                            ->setQuantity($cartItem['qty'])
                            ->setPrice($cartItem['price'])
                            ->setCurrency($cartItem['options']['o_currency']);
                
                $this->setCurrency($cartItem['options']['o_currency']);
                $this->total += $this->format_number($cartItem['subtotal']);
                $i++;
            }
        }
        

        $this->setTotal($this->total);
        
       
        
        $this->itemList = new \PayPal\Api\ItemList();
        $this->itemList->setItems($this->item)
                       ->setShippingAddress($shippingAddress);
        
        $details = new \PayPal\Api\Details();
        $details
                 //->setShipping("1.2")
                // ->setTax("1.3")
                //total of items prices
                ->setSubtotal($this->getTotal());
        
        //Payment Amount
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency($this->getCurrency())
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal($this->getTotal())
                ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $this->transaction = new \PayPal\Api\Transaction();
        $this->transaction->setAmount($amount)
                        ->setItemList($this->itemList)
                        ->setDescription("Payment description")
                        ->setInvoiceNumber(uniqid());

            // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl("https://example.com/your_redirect_url.html")
                    ->setCancelUrl("https://example.com/your_cancel_url.html");
        $payment = new \PayPal\Api\Payment();

        $payment->setIntent("authorize")
                ->setPayer($payer)
                ->setTransactions(array($this->transaction))
                ->setRedirectUrls($redirectUrls);
        
        $this->_api_context = $this->creditCardPayment->apiContext();
        try {
            $payment->create($this->_api_context);
            $response = $payment->toArray();
            pjAppController::jsonResponse(['response' => $response]);
            exit;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            if ($this->isDebug()) {
                pjAppController::jsonResponse(["Exception" => $ex->getMessage(), "data" => $ex->getData()]);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }
        pjAppController::jsonResponse([$payment->toArray()]);
        exit;
    }
}