<?php

use PayPal\Api\BillingAddress;
use PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Payment\CreditCardPayment;


use Honeycrisp\Mail\Exception\MailException;
use Honeycrisp\Mail\Mailer;

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
    private $tickets = array();
    private $loggedInUserEmail;
    public function __construct()
    {
       parent::__construct();
        //exit;
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
        if($this->hasSession('loggedIn')) {
            $this->loggedInUserEmail = $this->getSession('email');
        }
         // ### get post data
        $this->data = $this->getRequest();
         // ### set billing address
        $this->setShippingAddress($this->data['billingAddress']);
        $this->shippingAddress = $this->getShippingAddress();
        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        $shippingAddress = new ShippingAddress();
        
        $shippingAddress->setLine1('line1')
                        ->setLine2('line2')
                        //->setState($this->billingAddress['state'])
                        ->setPostalCode($this->shippingAddress['c_zip'])
                        ->setCountryCode($this->shippingAddress['c_country'])
                        ->setState($this->shippingAddress['c_state'])
                        ->setCity($this->shippingAddress['c_city'])
                        ->setPhone($this->shippingAddress['c_phone'])
                        //->setDefaultAddress($this->shippingAddress['c_address'])
                        ->setRecipientName($this->shippingAddress['c_firstName']. " ". $this->shippingAddress['c_lastName']);
        // ### set credit card info
        $this->setCreditCardInfo($this->data['creditCardInfo']);
        $this->creditCardInfo = $this->getCreditCardInfo();
        $expiry = explode('/', $this->creditCardInfo['formatCardExpiry']);

        // $this->creditCardInfo['cc_exp_month'] = '';
        // $this->creditCardInfo['cc_exp_year'] = '';

        if(count($expiry) > 0) {
            $this->creditCardInfo['cc_exp_month'] = (int)$expiry[0];
            $this->creditCardInfo['cc_exp_year'] = (int)$expiry[1];
        }
        if(strlen($this->creditCardInfo['cc_exp_year']) === 2) {
            if($this->creditCardInfo['cc_exp_year'] < 70) {
                $this->creditCardInfo['cc_exp_year'] = "20" . $this->creditCardInfo['cc_exp_year'];
            } else {
                $this->creditCardInfo['cc_exp_year'] = "19" . $this->creditCardInfo['cc_exp_year'];
            }
        }
        $cc_exp_year = (int) $this->creditCardInfo['cc_exp_year'];
        // ### CreditCard
        $this->creditCard = new \PayPal\Api\CreditCard();
        $this->creditCard->setNumber(preg_replace('/\s+/', '', $this->creditCardInfo['cc_num']))
                        ->setType($this->creditCardInfo['cc_type'])
                        ->setExpireMonth($this->creditCardInfo['cc_exp_month'])
                        ->setExpireYear($cc_exp_year)
                        ->setCvv2($this->creditCardInfo['cc_code']);
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
        // get cart items
       // $this->cartItems = $this->getCartItems();
        $arr = [];
        $newArr = [];
        $i = 0;
        if(count($this->getCartItems()) > 0) {
            foreach($this->getCartItems() as $cartItem) {
                // create a new item object 
                $this->item[$i] = new \PayPal\Api\Item();
                // set value an item object
                $this->item[$i]->setName($cartItem['name'])
                                ->setDescription('Ticket(s)')
                                ->setQuantity($cartItem['qty'])
                                ->setPrice($cartItem['price'])
                                ->setCurrency($cartItem['o_currency']);
                
                $this->setCurrency($cartItem['o_currency']);
                $this->total += $this->format_number($cartItem['subtotal']);
                $this->data['event_id'] = $cartItem['event_id'];
                $this->data['venue_id'] = $cartItem['venue_id'];
                

                if(is_array($cartItem['tickets']) && count($cartItem['tickets']) > 0) {
                    foreach($cartItem['tickets'] as $ticketType => $price_id) {
                        if(in_array($price_id, $arr)) {
                            $newArr[$price_id] = $newArr[$price_id] += 1;
                        } else {
                            array_push($arr, $price_id);
                            $newArr[$price_id] = 1;
                        }
                        $this->tickets[$ticketType] = array(
                            $price_id => $newArr[$price_id]
                        );
                    }
                }
                $i++;
            }
        }
        $this->data['sub_total']    = $this->total;
        $this->data['total']        = $this->total; // excluded tax
        $this->data['tickets']      = $this->tickets;

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
        $redirectUrls->setReturnUrl(base_url().'/redirect_url')
                      ->setCancelUrl(base_url().'/cancel_url');
        $payment = new \PayPal\Api\Payment();

        $payment->setIntent("authorize")
                ->setPayer($payer)
                ->setTransactions(array($this->transaction))
                ->setRedirectUrls($redirectUrls);
        
        $this->_api_context = $this->creditCardPayment->apiContext();
        try {
            $payment->create($this->_api_context);
            $this->response = $payment->toArray();
            //App::dd($this->response);
            if(is_array($this->response) && count($this->response) > 0) {
                if($this->response['state'] === "approved") {
                    
                    if(is_array($this->response['payer']) && count($this->response['payer']) > 0) {
                        $this->data['payment_method'] = str_replace('_', '',$this->response['payer']['payment_method']);
                    }
                    if(is_array($this->response['transactions']) && count($this->response['transactions']) > 0) {
                        $this->data['sub_total'] = $this->response['transactions'][0]['amount']['details']['subtotal'];
                        $this->data['total'] = $this->response['transactions'][0]['amount']['total'];
                        $this->data['description'] = $this->response['transactions'][0]['description'];
                        $this->data['invoice_number'] = $this->response['transactions'][0]['invoice_number'];
                    }
                    $this->data['txn_id'] = $this->response['id'];
                   // $this->data['date_time'] = //show date time $this->response['create_time'];
                    $this->data['processed_on'] = $this->response['create_time'];
                    $this->data['created'] = $this->response['create_time'];
                    $this->data['status'] = 'confirmed';
                    $this->data['date_time'] = $this->getSession($this->defaultStore)['selected_date_time'];
                    $this->load->model('BookingModel');
                    $this->data['localeId'] = $this->getLocaleId();
                    $this->response = $this->BookingModel->save($this->data, $this->option_arr);
                     if($this->response['error'] === 'AR03') {
                        // Send booking details by email
                        try {
                            
                            //$this->mailer->setSMTPDebug(2);
                            $this->loggedInUserEmail = $this->shippingAddress['c_email'];
                            /*
                            $mailer = new Mailer();
                            $mailer->isSMTP();
                            $mailer->setHost($this->getSMTPHost());
                            $mailer->setSMTPAuth(true);
                            $mailer->setUsername($this->getSMTPUser());
                            $mailer->setPassword($this->getSMTPPass());
                            $mailer->setSMTPSecure('tls');
                            $mailer->setPort($this->getSMTPPort());
                            
                            $mailer->setFrom($this->getSMTPEmailAddress(), 'Ticket At Guru');
                            $mailer->addAddress($this->loggedInUserEmail, $this->loggedInUserEmail); // Name is optional
                            $mailer->addReplyTo($mailer->From, $mailer->FromName);
                            
                            
                            $mailer->addAttachment($this->BookingModel->getAttachmentTicket());
                            $mailer->isHTML(true);                                  // Set email format to HTML
                            $mailer->setSubject('Here is the subject');
                            $mailer->setBody('This is the HTML message body <b>in bold!</b>');
                            $mailer->setAltBody('This is the body in plain text for non-HTML mail clients');
                            */
                            
                            $mailer = new Mailer();
                            $mailer->isSMTP();
                            $mailer->setHost('smtp.mailtrap.io');
                            $mailer->setSMTPAuth(true);
                            $mailer->setUsername('f56d0696931ad0');
                            $mailer->setPassword('a47d3fed4ffdac');
                            $mailer->setSMTPSecure('tls');
                            $mailer->setPort(465);
    
                            $mailer->setFrom($this->getSMTPEmailAddress(), 'Ticket At Guru');
                            $mailer->addAddress($this->loggedInUserEmail, $this->loggedInUserEmail); // Name is optional
                            $mailer->addReplyTo($mailer->From, $mailer->FromName);

                            $mailer->addAttachment($this->BookingModel->getAttachmentTicket());         // Add attachments

                            $mailer->isHTML(true);                                  // Set email format to HTML
                            $mailer->Subject = 'Here is the subject';
                            $mailer->Body    = 'Your booking has been successfully <b>Confirmed</b>. Please download your attachment file.';
                            
                            
                            if(!$mailer->send()) {
                                $this->response['status_code'] = '500';
                                $this->response['message'] = $mailer->getErrorInfo();
                                
                            } else {
                                $this->reset();
                                $this->response['status_code'] = '202';
                                $this->response['message'] = 'Payment has been made successfully';
                                $this->response['att'] = $this->BookingModel->getAttachmentTicket();
                                
                            }

                        } catch(MailException $e) {
                            $this->response['status_code'] = '500';
                            $this->response['message'] = "Message could not be sent. Mailer Error: {$e->getErrorMessage()}";
                        }
                     } else {
                        $this->response['status_code'] = 500;
                        $this->response['message'] = 'Payment failed! try again later';
                     }
                    //  pjAppController::jsonResponse($this->response);
                    //  exit;
                } else {
                    $this->response['status_code'] = 500;
                    $this->response['message'] = 'Payment failed! try again later';
                    $this->response['data'] = $this->response;
                }
            }
            //$this->session->set_userdata('status_code', $this->response['status_code']);
            // pjAppController::jsonResponse($this->response);
            // exit;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            /*
            if ($this->isDebug()) {
                pjAppController::jsonResponse(["Exception" => $ex->getMessage(), "data" => $ex->getData()]);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
            */
            pjAppController::jsonResponse(["Exception" => $ex->getMessage(), "data" => $ex->getData()]);
            exit;
        }
        pjAppController::jsonResponse($this->response);
        exit;
    }

    private function reset() {
        $this->cart->destroy();
        $this->unsetSession($this->defaultStore);
        $this->unsetSession('selected_date');
        $this->unsetSession('venue_id');
    }

    public function sendMailWithAttachmentTicket() {
        try {
            
            $this->mailer->addAddress('info@honeycrisp.com', 'Honeycrisp'); // Add a recipient
            /*
            $mailer->addAddress('ellen@example.com');               // Name is optional
            $mailer->addReplyTo('info@example.com', 'Information');
            $mailer->addCC('cc@example.com');
            $mailer->addBCC('bcc@example.com');
            */

            // Attachments
            //$mailer->addAttachment($this->BookingModel->getAttachmentTicket());         // Add attachments
            ///$mailer->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        
            // Content
            $this->mailer->isHTML(true);                                  // Set email format to HTML
            $this->mailer->setSubject('Here is the subject');
            $this->mailer->setBody('This is the HTML message body <b>in bold!</b>');
            $this->mailer->setAltBody('This is the body in plain text for non-HTML mail clients');
            if(!$this->mailer->send()) {
                $this->response['message'] = $this->mailer->getErrorInfo();
                $this->response['statusCode'] = '500';
            } else {
                $this->response['message'] = 'Message has been sent';
                $this->response['statusCode'] = '200';
            }
            //App::dd($this->mailer);
          
        } catch(MailException $e) {
            $this->response['statusCode'] = '500';
            $this->response['message'] = "Message could not be sent. Mailer Error: {$e->getErrorMessage()}";
        }
        return $this->response;
    }
}