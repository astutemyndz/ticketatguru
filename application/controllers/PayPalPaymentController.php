<?php

use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Payment\CreditCardPayment;
class PayPalPaymentController extends App_Controller {

    private $_api_context;
    private $accessToken;
    private $payPal = false;
    private $defaultConfig;
    private $billingAddress;
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
         $this->setBillingAddress($this->data['billingAddress']);
         $this->billingAddress = $this->getBillingAddress();

        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        $billingAddress = $this->creditCardPayment->billingAddress();
        $billingAddress
            ->setCity($this->billingAddress['city'])
            //->setState($this->billingAddress['state'])
            ->setPostalCode($this->billingAddress['postalCode'])
            ->setCountryCode($this->billingAddress['countryCode'])
            ->setPhone($this->billingAddress['phone'])
            ->setRecipientName($this->billingAddress['firstName']. " ". $this->billingAddress['lastName']);

        // ### set credit card info
        $this->setCreditCardInfo($this->data['creditCardInfo']);
        $this->creditCardInfo = $this->getCreditCardInfo();

        // ### CreditCard
        $this->creditCard = $this->creditCardPayment->creditCard();
        $this->creditCard->setNumber($this->creditCardInfo['number']);
        $this->creditCard->setType($this->creditCardInfo['type']);
        $this->creditCard->setExpireMonth($this->creditCardInfo['expireMonth']);
        $this->creditCard->setExpireYear($this->creditCardInfo['expireYear']);
        $this->creditCard->setCvv2($this->creditCardInfo['cvv2']);
        $this->creditCard->setBillingAddress($billingAddress);

       

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // Use a Payer ID (A unique identifier of the payer generated
        // and provided by the facilitator. This is required when
        // creating or using a tokenized funding instrument)
        // and the `CreditCardDetails`
        $fi = $this->creditCardPayment->fundingInstrument();
        $fi->setCreditCard($this->creditCard);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = $this->creditCardPayment->payer();
        $payer->setPaymentMethod("credit_card");
            //->setFundingInstruments([$fi]);
            //App::dd($payer);
        // get cart items
        $this->cartItems = $this->getCartItems();
        

        if($this->cartItems) {
            $i = 0;
            foreach($this->cartItems as $cartItem) {
                // create a new item object 
                $this->item[$i] = $this->creditCardPayment->item();
                // set value an item object
                $this->item[$i]->setName($cartItem['name'])
                            ->setDescription('Ground Coffee 40 oz')
                            ->setQuantity($cartItem['qty'])
                            ->setPrice($cartItem['price'])
                            ->setCurrency($cartItem['options']['o_currency']);
                
                $this->setCurrency($cartItem['options']['o_currency']);
                $this->total += $this->format_number($cartItem['subtotal']);
                $i++;
            }
        }
        $this->setTotal($this->total);
        //App::dd($this->item);
        $this->itemList = $this->creditCardPayment->itemList();
        if($this->item) {
            $this->itemList->setItems(array($this->item))
                            ->setBillingAddress($billingAddress);
        }
        
        $details = $this->creditCardPayment->details();
        $details
                // ->setShipping("1.2")
                // ->setTax("1.3")
                //total of items prices
                ->setSubtotal("17.5");
        
        //Payment Amount
        $amount = $this->creditCardPayment->amount();
        $amount->setCurrency($this->getCurrency())
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal($this->getTotal())
                ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $this->transaction = $this->creditCardPayment->transaction();
        $this->transaction->setAmount($amount)
            ->setItemList($this->itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("https://example.com/your_redirect_url.html")
            ->setCancelUrl("https://example.com/your_cancel_url.html");
            
            

        $payment = $this->creditCardPayment->payment();

        $payment->setIntent("authorize")
            ->setPayer($payer)
            ->setTransactions([$this->transaction])
            ->setRedirectUrls($redirectUrls);
       
        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            
            //App::dd($this->creditCardPayment->apiContext());
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    'AZZI7nBmRypZF4H7ajNM4G8JnbxGTor8OpxdwXmKUWh2R-zfKfYAX9Us1idmQ_6-zltNgcIeZCZWVakG',     // ClientID
                    'EJ4gqjUBn0B8xf1SxlK5z4aFsxjOTaZ20AFn6BpVrX52BP1WYd-S7z0oqnYjFBHnEBYpNsKAdE4TWD_D'      // ClientSecret
                )
            );

            //App::dd($apiContext);
            $payment->create($apiContext);
            echo $payment;

            echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
            //$execution = new PaymentExecution(); 
            //$result = $payment->execute($execution, $this->creditCardPayment->apiContext());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            pjAppController::jsonResponse(["error" => $ex->getData()]);
            //echo '<pre>';print_r(json_decode($ex->getData()));exit;
            exit;
        }
        pjAppController::jsonResponse([$payment->toArray()]);
        exit;
    }
     
}