<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Transaction as Trans;
use App\MembershipPlanOrder as MPO;

/**
 * @description Paypal ApiContext
 * @Author Rakesh Maity
 */
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Notification;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use DateTime;
use Carbon\Carbon;
use App\Mail\ContactMailable;
use Illuminate\Support\Facades\Mail;
use Braintree\Transaction as BTransaction;

class PaymentController extends Controller
{
    private $data;
    private $result;
    private $transaction;
    private $_api_context;
    private $gateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->gateway = new \Braintree\Gateway([
            'environment'   => env('BT_ENVIRONMENT'),
            'merchantId'    => env('BT_MERCHANT_ID'),
            'publicKey'     => env('BT_PUBLIC_KEY'),
            'privateKey'    => env('BT_PRIVATE_KEY')
        ]);

    }

    /**
     * @desc set event Transaction data and event details
     * @param Request $request
     */
    public function setAdditionalTransactionData(Request $request) {
        $event                                  = DB::table('events')
                                                    ->select('events.title')
                                                    ->where('events.id', $request->event_id)
                                                    ->first();

        $this->data['event_id']                 = $request->event_id;
        $this->data['item_name']                = $event->title;
        $this->data['id_events_ticket_assigns'] = $request->id_events_ticket_assigns;
        $this->data['user_id']                  = $request->user_id;
        $this->data['member_ticket_quantity']   = $request->member_ticket_quantity;
        $this->data['member_ticket_price']      = $request->member_ticket_price;
        $this->data['quantity']                 = $request->quantity;
        $this->data['quest_ticket_quantity']    = $request->quest_ticket_quantity;
        $this->data['guest_ticket_price']       = $request->guest_ticket_price;
        $this->data['price']                    = $request->amount;
        $this->data['user_name']                = $request->user_name;
        $this->data['user_email']               = $request->user_email;
        $this->data['user_phone']               = $request->user_phone;
        $this->data['user_address']             = $request->user_address;

        $this->setKeyPairsValue('transaction_data', $this->data);
    }
    public function getAdditionalTransactionData() {
        return $this->data;
    }

    /**
     * @desc set event Transaction data and event details to database after complete Transaction
     */
    public function saveAdditionalTransactionData() {
        $value                                  = Session::get('transaction_data');
        ################# Init. Value ##################################
        $order_id                               = Session::get('order_id');
        $transaction_id                         = Session::get('transaction_id');
        $payment_method                         = Session::get('payment_method');
        $payment_status                         = Session::get('payment_status');
        $payment_state                          = Session::get('payment_state');
        $paypal_return_data                     = Session::get('paypal_return_data');
        ################# Init. Value ##################################

        ################# Save to database ##############################
        $transaction                            = new Trans();
        $transaction->order_id                  = $order_id;
        $transaction->transaction_id            = $transaction_id;
        $transaction->payment_method            = $payment_method;
        $transaction->payment_status            = $payment_status;
        $transaction->payment_state             = $payment_state;
        $transaction->paypal_return_data        = $paypal_return_data;
        $transaction->event_id                  = $value['event_id'];
        $transaction->user_id                   = $value['user_id'];
        $transaction->member_ticket_quantity    = $value['member_ticket_quantity'];
        $transaction->quest_ticket_quantity     = $value['quest_ticket_quantity'];
        
        $transaction->quantity                  = $value['quantity'];
        
        $transaction->member_ticket_price       = $value['member_ticket_price'];
        $transaction->guest_ticket_price        = $value['guest_ticket_price'];
        $transaction->price                     = $value['price'];
        $transaction->user_name                 = $value['user_name'];
        $transaction->user_email                = $value['user_email'];
        // $transaction->user_phone = $value['user_phone'];
        // $transaction->user_address = $value['user_address'];
        $transaction->save();
        ################# Save to database ##############################

    }

    /**
     * @desc save member subscription data to database
     */
    public function saveSubscriptionsData() {
        $value              = Session::get('subscriptions_data');
        // get the current time
        $current = Carbon::now();

        // add 30 days to the current time
        $trialExpires = $current->addDays($value['days']);
        ################# Init. Value ##################################
        $order_id           = Session::get('order_id');
        $transaction_id     = Session::get('transaction_id');
        $payment_method     = Session::get('payment_method');
        $payment_status     = Session::get('payment_status');
        $payment_state      = Session::get('payment_state');
        $paypal_return_data = Session::get('paypal_return_data');
        ################# Init. Value ##################################

        ################# Save to database ##############################
        $transaction = new MPO();
        $transaction->order_id              = $order_id;
        $transaction->transaction_id        = $transaction_id;
        $transaction->plan_id               = $value['plan_id'];
        $transaction->user_id               = $value['user_id'];
        $transaction->start_date            = new DateTime();
        $transaction->end_date              = $trialExpires;
        $transaction->price                 = $value['amount'];
        $transaction->subscribe_interval    = $value['subscribe_interval'];
        $transaction->payment_method        = $payment_method;
        $transaction->payment_status        = $payment_status;
        $transaction->payment_state         = $payment_state;
        $transaction->paypal_return_data    = $paypal_return_data;

        $transaction->save();
        ################# Save to database ##############################

    }
    ############# Forget Session Variables #########################
    public function reset() {
        Session::forget('transaction_data');
        Session::forget('subscriptions_data');
        Session::forget('PayerID');
        //Session::forget('order_id');
        //Session::forget('transaction_id');
        Session::forget('payment_method');
        Session::forget('payment_status');
        Session::forget('payment_state');
        Session::forget('paypal_return_data');
    }
    ############# Forget Session Variables #########################
    public function index()
    {
        return view('paywithpaypal');
    }
    public function payWithpaypal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        // dd($request->all());
        $this->setAdditionalTransactionData($request);
        //dd($this->getAdditionalTransactionData());
        $item_1 = new Item();
        $item_1->setName($request->get('item_name')) /** item name **/
        ->setCurrency(config('paypal.currency'))
            ->setQuantity(1)
            ->setPrice($request->get('amount')); /** unit price **/


        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency(config('paypal.currency'))
            ->setTotal($request->get('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
        ->setCancelUrl(URL::to('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        //dd($payment);
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::to('/thank-you');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::to('/thank-you');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        //dd($payment);
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::to('/thank-you');
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            \Session::put('error', 'Please try again later.');
            return Redirect::to('/thank-you');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        switch(\Session::get('action')) {
            case 'SUBSCRIBE_MEMBER_PLAN':
                if ($result->getState() == 'approved') {

                    \Session::put('order_id', $this->getOrderId());
                    \Session::put('transaction_id', Input::get('PayerID'));
                    \Session::put('payment_method', $result->getPayer()->getPaymentMethod());
                    \Session::put('payment_status', $result->getPayer()->getStatus());
                    \Session::put('payment_state', $result->getState());
                    \Session::put('paypal_return_data', $result->toJSON());
                    \Session::put('PayerID', Input::get('PayerID'));
                    \Session::put('success', 'Thank you for your subscriptions');
                    $this->saveSubscriptionsData();
                    $value = Session::get('subscriptions_data');

                    $order_id = Session::get('order_id');
                    $transaction_id = Session::get('transaction_id');

                    $plan_details = DB::table('membership_plan')
                        ->select('membership_plan.*')
                        ->where(id, $value['plan_id'])
                        ->first();


                    Mail::to(\Session::get('user_email'))->send(new ContactMailable([
                        'from'      => config('mail.from.address'),
                        'subject'   => 'Thank you for subscriptions'
                    ],[
                        'Your Order Id is' => $order_id,
                        'Your Paypal Transaction Id is' => $transaction_id,
                        'The subscriptions plan is' => $plan_details->plan_name,
                        'The subscriptions plan details' => $plan_details->small_desc,
                        'The subscriptions plan price' => $plan_details->plan_price,
                        'The subscriptions plan activate date time' => $plan_details->created_at,
                        'Total Amount' => $value['amount'],
                    ], 'SUBSCRIBE_MEMBER_PLAN'));
                    $this->reset();
                } else {
                    Session::put('error', 'Payment failed');
                }

                return Redirect::to('/thank-you');
                break;

            default:
                if ($result->getState() == 'approved') {

                    ################ Notification ############################
                    $notification = new Notification();
                    ################ Notification ############################
                    ############ Set value in session ####################################
                    \Session::put('order_id', $this->getOrderId());
                    \Session::put('transaction_id', Input::get('PayerID'));
                    \Session::put('payment_method', $result->getPayer()->getPaymentMethod());
                    \Session::put('payment_status', $result->getPayer()->getStatus());
                    \Session::put('payment_state', $result->getState());
                    \Session::put('paypal_return_data', $result->toJSON());
                    \Session::put('PayerID', Input::get('PayerID'));
                    ############ Set value in session ####################################
                    \Session::put('success', 'Payment success');
                    $this->saveAdditionalTransactionData();
                    $value = Session::get('transaction_data');
                    // dd($value);
                    /*******Update event_ticket_assign table for maintain stock**********/
                    $ticket_assigns = DB::table('events_ticket_assigns')
                        ->select('events_ticket_assigns.purchase_quantity','events_ticket_assigns.purchase_guest_ticket')
                        ->where('events_ticket_assigns.id',$value['id_events_ticket_assigns'])
                        ->first();
                    /* For guest ticket*/
                    $previous_guest_quantity = $ticket_assigns->purchase_guest_ticket;
                    $current_guest_stock = $previous_guest_quantity + $value['quantity'];
                    /* For guest ticket*/

                    /* For member ticket*/
                    $previous_member_quantity = $ticket_assigns->purchase_quantity;
                    $current_member_stock = $previous_member_quantity + $value['member_ticket_quantity'];
                    /* For member ticket*/
                    
                    $get_data= array();
                    $get_data['purchase_quantity'] = $current_member_stock;
                    $get_data['purchase_guest_ticket'] = $current_guest_stock;
                    DB::table('events_ticket_assigns')->where('id', $value['id_events_ticket_assigns'])->update($get_data);
                    
                    /*******Update event_ticket_assign table for maintain stock**********/

                    $order_id = Session::get('order_id');
                    $transaction_id = Session::get('transaction_id');
                    $value = Session::get('transaction_data');


                    $events_details = DB::table('events')
                        ->select('events.title')
                        ->where('events.id', $value['event_id'])
                        ->first();


                    Mail::to($value['user_email'])->send(new ContactMailable([
                        'from'      => config('mail.from.address'),
                        'subject'   => config('dynamicmail.frontend.email.subjects.booking')
                    ],[
                        'Hello '.\Session::get('user_name').",",
                        'You have been booked an event and the details are below.',
                        'Your Order Id is' => $order_id,
                        'Your Paypal Transaction Id is' => $transaction_id,
                        'The booking event is' => $events_details->title,
                        'Member ticket quantity is' => $value['member_ticket_quantity'],
                        'Member ticket price is' => $value['member_ticket_price'],
                        'Guest ticket quantity is' => $value['quantity'],
                        'Guest ticket price is' => $value['guest_ticket_price'],
                        'Total Amount' => $value['price'],
                    ], 'CONTACT'));
                    $this->reset();
                } else {
                    Session::put('error', 'Payment failed');
                }

                return Redirect::to('/thank-you');
                break;

        }


//        Session::put('error', 'Payment failed');
//        return Redirect::to('/thank-you');
    }

    /**
     * modified
     * @desc Book free event
     * @param Request $request
     * @return mixed
     */
    public function payWithFree(Request $request)
    {
        $orderId                                = $this->getOrderId();
        $event = ($request->has('event')) ? $request->get('event') : [];
        if($event) {
            $transaction                            = new Trans();
            $transaction->order_id                  = $orderId;
            $transaction->payment_method            = "Free";
            $transaction->payment_status            = "VERIFIED";
            $transaction->payment_state             = "approved";
            $transaction->event_id                  = $event['event_id'];
            $transaction->user_id                   = $event['user_id'];
            // $global_quantity = $request->quantity;
            $transaction->member_ticket_quantity    = $event['member_ticket_quantity'];
            $transaction->quest_ticket_quantity     = $event['quest_ticket_quantity'];
            $transaction->quantity                  = $event['quantity'];
            $transaction->member_ticket_price       = $event['member_ticket_price'];
            $transaction->guest_ticket_price        = $event['guest_ticket_price'];
            $transaction->price                     = $event['amount'];
            $transaction->user_name                 = $event['user_name'];
            $transaction->user_email                = $event['user_email'];
            $transaction->save();
            $events_details                         = DB::table('events')
                                                        ->select('events.title')
                                                        ->where('events.id',$event['event_id'])
                                                        ->first();
            /*mail to users*/
            Mail::to($request->user_email)->send(new ContactMailable([
                'from'                              => config('mail.from.address'),
                'subject'                           => config('dynamicmail.frontend.email.subjects.booking')
            ],[
                'Your Order Id is'                  => $orderId,
                'The booking event is'              => $events_details->title,
                'Member ticket quantity is'         => $request->member_ticket_quantity,
                'Member ticket price is'            => $request->member_ticket_price,
                'Guest ticket quantity is'          => $request->quest_ticket_quantity,
                'Guest ticket price is'             => $request->guest_ticket_price,
                'Total Amount'                      => $request->price,
            ], 'CONTACT'));
            /*mail to users*/

            /*mail to admin*/
            Mail::to(config('mail.from.address'))->send(new ContactMailable([
                'from'                              => $request->user_email,
                'subject'                           => config('dynamicmail.frontend.email.subjects.booking')
            ],[
                'Hello Admin,',
                $request->user_name.' booked your event and the details are below.',
                'Your Order Id is'                  => $orderId,
                'The booking event is'              => $events_details->title,
                'Member ticket quantity is'         => $request->member_ticket_quantity,
                'Member ticket price is'            => $request->member_ticket_price,
                'Guest ticket quantity is'          => $request->quest_ticket_quantity,
                'Guest ticket price is'             => $request->guest_ticket_price,
                'Total Amount'                      => $request->price,
            ], 'CONTACT'));
            /*mail to admin*/

            /*******Update event_ticket_assign table for maintain stock**********/
            $ticket_assigns                         = DB::table('events_ticket_assigns')
                ->select('events_ticket_assigns.purchase_quantity','events_ticket_assigns.purchase_guest_ticket')
                ->where('events_ticket_assigns.id',$request->id_events_ticket_assigns)
                ->first();
            /* For guest ticket*/
            $previous_guest_quantity                = $ticket_assigns->purchase_guest_ticket;
            $current_guest_stock                    = $previous_guest_quantity + $request->quest_ticket_quantity;
            /* For guest ticket*/

            /* For member ticket*/
            $previous_member_quantity               = $ticket_assigns->purchase_quantity;
            $current_member_stock                   = $previous_member_quantity + $request->member_ticket_quantity;
            /* For member ticket*/

            $get_data= array();
            $get_data['purchase_quantity']          = $current_member_stock;
            $get_data['purchase_guest_ticket']      = $current_guest_stock;

            DB::table('events_ticket_assigns')->where('id', $request->id_events_ticket_assigns)->update($get_data);

            /*******Update event_ticket_assign table for maintain stock**********/

            \Session::put('order_id', $orderId);
            \Session::put('success', 'Transaction successfully completed');

            $this->reset();
            return Redirect::to('/thank-you');
        }



    }

    /**
     * @desc generate a order id
     * @return string
     */
    public function getOrderId() {
        $today = date("Ymd");
        $rand = $today.strtoupper(substr(uniqid(sha1(time())),0,4));
        return $rand;
    }

    public function updatePurchaseQuantity($id_events_ticket_assigns, $quantity) {
        $ticket_assigns = DB::table('events_ticket_assigns')
                ->select('events_ticket_assigns.purchase_quantity')
                ->where('events_ticket_assigns.id',$id_events_ticket_assigns)
                ->first();
        $previous_quantity = $ticket_assigns->purchase_quantity;
        $current_stock = $previous_quantity + $quantity;
        
        $updatePurchaseQuantity = DB::table('events_ticket_assigns')
            ->where('id', $id_events_ticket_assigns)
            ->update([
                'purchase_quantity' => $current_stock
            ]);
        return true;
    }
    public function updatePurchaseGuestTicket($id_events_ticket_assigns, $quantity) {
        $ticket_assigns = DB::table('events_ticket_assigns')
                ->select('events_ticket_assigns.purchase_guest_ticket')
                ->where('events_ticket_assigns.id',$id_events_ticket_assigns)
                ->first();
        $previous_quantity = $ticket_assigns->purchase_guest_ticket;
        $current_stock = $previous_quantity + $quantity;
        $updatePurchaseGuestTicket = DB::table('events_ticket_assigns')
            ->where('id', $id_events_ticket_assigns)
            ->update([
                'purchase_guest_ticket' => $current_stock
            ]);
        return true;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        $value = Session::get('transaction_data');
        return $value['user_id'];
    }

    /**
     * @return mixed
     */
    public function getUserEmail() {
        $value = Session::get('transaction_data');
        return $value['user_email'];
    }

    /**
     * @return mixed
     */
    public function getQuantity() {
        $value = Session::get('transaction_data');
        return $value['quantity'];
    }
    /**
     * @return mixed
     */
    public function getMemberTicketQuantity() {
        $value = Session::get('transaction_data');
        return $value['member_ticket_quantity'];
    }

    /**
     * @return mixed
     */
    public function subscribe() {
        return view('frontend.template.subscribe');
    }

    /**
     * @return mixed
     */
    public function send() {
        $mail = new EmailService([
            'subject' => 'Test mail from laravel',
            'to' => 'rakesh@businessprocreations.com',
            'body' => [],
            'from' => 'info@businessprocreations.net',
        ]);
        if($mail->send()) {
            return response()->json([
                'status' => true,
                'message' => 'Mesage has been sent'
            ],200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed!'
            ],200);
        }
    }

    /**
     * @param Request $request
     */
    public function setMembershipPlanSubscriptionsData(Request $request) {
        $this->data['plan_id'] = $request->plan_id;
        $this->data['item_name'] = $request->item_name;
        $this->data['user_id'] = $request->user_id;
        $this->data['amount'] = $request->amount;
        $this->data['subscribe_interval'] = $request->subscribe_interval;
        $this->data['days'] = $request->days;
        \Session::put('subscriptions_data', $this->data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function membershipPlanSubscribePayment(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $this->setMembershipPlanSubscriptionsData($request);
        $item_1 = new Item();
        $item_1->setName($request->get('item_name')) /** item name **/
        ->setCurrency(config('paypal.currency'))
            ->setQuantity(1)
            ->setPrice($request->get('amount')); /** unit price **/


        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency(config('paypal.currency'))
            ->setTotal($request->get('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('enter you custom message');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
                        ->setCancelUrl(URL::to('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        //dd($payment);
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::to('/thank-you');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::to('/thank-you');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        if($payment->getId()) {
            Session::put('paypal_payment_id', $payment->getId());
            Session::put('action', 'SUBSCRIBE_MEMBER_PLAN');
        }


        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::to('/thank-you');
    }


    /**
     * @desc set value to checkout form or open checkout page
     * @param Request $request
     * @return mixed
     */
    public function checkout(Request $request) {
        $data['subscribe_plan'] = [];
        $data['event']          = [];
        if($request->has('subscribe_plan')) {
            $data['subscribe_plan'] = $request->input('subscribe_plan');
        }
        if($request->has('event')) {
            $data['event']          = $request->input('event');
        }


        return view('frontend.checkout.index', $data);
    }
    /**
     * @desc post request of checkout form
     * @param Request $request
     * @return array
     */
    public function handleCheckoutcallback(Request $request) {

        $action = ($request->has('action')) ? $request->get('action') : '';
       // dd($action);
        switch ($action) {
            case 'SUBSCRIBE_PLAN':
                $this->response = $this->subscribePlan($request);
                //dd($this->response);
                if(!$this->response['error'] && $this->response['processorResponseText'] == 'Approved') {

                    $this->setKeyPairsValue('order_id',             $this->response['order_id']);
                    $this->setKeyPairsValue('transaction_id',       $this->response['transaction_id']);
                    $this->setKeyPairsValue('payment_method',       $this->response['payment_method']);
                    $this->setKeyPairsValue('payment_status',       $this->response['payment_status']);
                    $this->setKeyPairsValue('payment_state',        $this->response['payment_state']);
                    $this->setKeyPairsValue('PayerID',              $this->response['transaction_id']);
                    $this->setKeyPairsValue('paypal_return_data',   $this->response['paypal_return_data']);
                    //$this->setKeyPairsValue('success',              $this->response['message']);


                    $this->setKeyPairsValue('subscriptions_data',   $this->response['subscriptions_data']);

                    $this->saveSubscriptionsData(); // save to database
                    $value = Session::get('subscriptions_data');

                    $order_id       = \Session::get('order_id');
                    $transaction_id = \Session::get('transaction_id');

                    $plan_details = DB::table('membership_plan')
                        ->select('membership_plan.*')
                        ->where(id, $value['plan_id'])
                        ->first();
                    # Membership plan subscribe
                    $subscribePlan = DB::table("membership_plan_orders")->where("user_id", \Session::get('user_id'))->first();
                    $membershipPlan = DB::table("membership_plan")->where("id", $subscribePlan->plan_id)->first();

                    if($membershipPlan) {
                        Session::put('subscribe', true);
                        Session::put('user_subscribe_plan', $subscribePlan);
                        Session::put('membership_plan', $membershipPlan);
                    } else {
                        Session::put('subscribe', false);
                    }

                    Mail::to(\Session::get('user_email'))->send(new ContactMailable([
                        'from'      => config('mail.from.address'),
                        'subject'   => 'Thank you for subscriptions'
                    ],[
                        'Your Order Id is'                          => $order_id,
                        'Your Paypal Transaction Id is'             => $transaction_id,
                        'The subscriptions plan is'                 => $plan_details->plan_name,
                        'The subscriptions plan details'            => $plan_details->small_desc,
                        'The subscriptions plan price'              => $plan_details->plan_price,
                        'The subscriptions plan activate date time' => $plan_details->created_at,
                        'Total Amount'                              => $value['amount'],
                    ], 'SUBSCRIBE_MEMBER_PLAN'));

                    $this->reset();
                    //return $this->response;
                    \Session::put('success', 'Thank you for your subscriptions');
                    return redirect('/thank-you');
                }
                \Session::put('success', $this->response['message']);
                return redirect('/thank-you');
                break;
            default:
                $this->response = $this->bookEvent($request);
                if(!$this->response['isFree']) {
                    if(!$this->response['error'] && $this->response['processorResponseText'] == 'Approved') {
                        $this->setKeyPairsValue('order_id',             $this->response['order_id']);
                        $this->setKeyPairsValue('transaction_id',       $this->response['transaction_id']);
                        $this->setKeyPairsValue('payment_method',       $this->response['payment_method']);
                        $this->setKeyPairsValue('payment_status',       $this->response['payment_status']);
                        $this->setKeyPairsValue('payment_state',        $this->response['payment_state']);
                        $this->setKeyPairsValue('PayerID',              $this->response['transaction_id']);
                        $this->setKeyPairsValue('paypal_return_data',   $this->response['paypal_return_data']);
                        //$this->setKeyPairsValue('success',              $this->response['message']);

                        // Save session value in database
                        $this->saveAdditionalTransactionData();
                        // get transaction data from session
                        $value                                  = $this->getKeyPairsValue('transaction_data');
                        /*******Update event_ticket_assign table for maintain stock**********/
                        $ticket_assigns                         = DB::table('events_ticket_assigns')
                            ->select('events_ticket_assigns.purchase_quantity','events_ticket_assigns.purchase_guest_ticket')
                            ->where('events_ticket_assigns.id',$value['id_events_ticket_assigns'])
                            ->first();
                        /* For guest ticket*/
                        $previous_guest_quantity                = $ticket_assigns->purchase_guest_ticket;
                        $current_guest_stock                    = $previous_guest_quantity + $value['quantity'];
                        /* For guest ticket*/

                        /* For member ticket*/
                        $previous_member_quantity               = $ticket_assigns->purchase_quantity;
                        $current_member_stock                   = $previous_member_quantity + $value['member_ticket_quantity'];
                        /* For member ticket*/

                        $get_data= array();
                        $get_data['purchase_quantity']          = $current_member_stock;
                        $get_data['purchase_guest_ticket']      = $current_guest_stock;

                        DB::table('events_ticket_assigns')->where('id', $value['id_events_ticket_assigns'])->update($get_data);

                        /*******Update event_ticket_assign table for maintain stock**********/

                        $order_id                               = Session::get('order_id');
                        $transaction_id                         = Session::get('transaction_id');



                        $events_details                         = DB::table('events')
                            ->select('events.title')
                            ->where('events.id', $value['event_id'])
                            ->first();

                        // send mail to customer
                        Mail::to($value['user_email'])->send(new ContactMailable([
                            'from'                          => config('mail.from.address'),
                            'subject'                       => config('dynamicmail.frontend.email.subjects.booking')
                        ],[
                            'Hello '.\Session::get('user_name').",",
                            'You have been booked an event and the details are below.',
                            'Your Order Id is'              => $order_id,
                            'Your Paypal Transaction Id is' => $transaction_id,
                            'The booking event is'          => $events_details->title,
                            'Member ticket quantity is'     => $value['member_ticket_quantity'],
                            'Member ticket price is'        => $value['member_ticket_price'],
                            'Guest ticket quantity is'      => $value['quantity'],
                            'Guest ticket price is'         => $value['guest_ticket_price'],
                            'Total Amount'                  => $value['price'],
                        ], 'CONTACT'));

                        $this->reset();
                        //return $this->response;
                        \Session::put('success', 'Transaction successfully completed');
                        return redirect('/thank-you');
                    }
                }
                break;
        }
    }

    /**
     * @desc subscribe_plan values come from checkout page
     * @param $request
     * @return array
     */
    public function subscribePlan($request) {
        $subscribe_plan = ($request->has('subscribe_plan')) ? $request->get('subscribe_plan') : [];

            $this->result = $this->gateway->transaction()->sale([
                'amount' => $subscribe_plan['amount'],
                'paymentMethodNonce' => ($request->has('payment_method_nonce')) ? $request->get('payment_method_nonce') : '',
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);
            if ($this->result->success || !is_null($this->result->transaction)) {
                $this->transaction = $this->getTransaction($this->result->transaction->id);
                // if success status in exists in getTransactionSuccessStatuses
                if (in_array($this->transaction->status, $this->getTransactionSuccessStatuses())) {
                    $this->response = [
                      'order_id'                =>  $this->getOrderId(),
                      'transaction_id'          =>  $this->transaction->id,
                      'payment_method'          =>  $this->transaction->paymentInstrumentType,
                      'payment_status'          =>  $this->transaction->status,
                      'payment_state'           =>  $this->transaction->processorResponseType,
                      'paypal_return_data'      =>  json_encode($this->result),
                      'subscriptions_data'      => $subscribe_plan,
                      'error'                   => false,
                      'status'                  => $this->transaction->status,
                      'message'                 => "Your transaction has been successfully processed. See the Braintree API response and try again.",
                        'processorResponseText'   => $this->transaction->processorResponseText,
                    ];
                } else {
                    $this->response = [
                        'order_id'            =>  $this->getOrderId(),
                        'transaction_id'      =>  $this->transaction->id,
                        'payment_method'      =>  $this->transaction->paymentInstrumentType,
                        'payment_status'      =>  $this->transaction->status,
                        'payment_state'       =>  $this->transaction->processorResponseType,
                        'paypal_return_data'  =>  json_encode($this->result),
                        'subscriptions_data'  => $subscribe_plan,
                        'error'               => true,
                        'status'              => $this->transaction->status,
                        'message'             => "Your test transaction has a status of " . $this->transaction->status . ". See the Braintree API response and try again.",
                         'processorResponseText'   => $this->transaction->processorResponseText,
                    ];
                }
            } else {
                $errorString = "";
                foreach($this->result->errors->deepAll() as $error) {
                    $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
                }
                $this->response = [
                    'order_id'            =>  $this->getOrderId(),
                    'transaction_id'      =>  $this->result->transaction->id,
                    'payment_method'      =>  $this->transaction->paymentInstrumentType,
                    'payment_status'      =>  $this->result->success,
                    'payment_state'       =>  $this->transaction->processorResponseType,
                    'paypal_return_data'  =>  json_encode($this->result),
                    'subscriptions_data'  => $subscribe_plan,
                    'error'               => true,
                    'status'              => $this->result->success,
                    'message'             => $errorString,
                    'processorResponseText'   => $this->transaction->processorResponseText,
                ];
            }
        return $this->response;
    }
    public function bookEvent($request) {
        $event = ($request->has('event')) ? $request->get('event') : [];
        //$extractEvent = [];

        $isPaid = 0;
        if($event) {
            //dd($event);
//            foreach ($event as $key => $value) {
//                $extractEvent[$key] = $value;
//            }
            $isPaid = ($event['paidEvent']) ? $event['paidEvent'] : ''; // true or false
        }

        if($isPaid) {
            $this->result = $this->gateway->transaction()->sale([
                'amount'                => $event['amount'],
                'paymentMethodNonce'    => ($request->has('payment_method_nonce')) ? $request->get('payment_method_nonce') : '',
                'options'               => [
                                            'submitForSettlement' => true
                ]
            ]);
            if ($this->result->success || !is_null($this->result->transaction)) {
                $this->transaction = $this->getTransaction($this->result->transaction->id);
                // if success status in exists in getTransactionSuccessStatuses
                if (in_array($this->transaction->status, $this->getTransactionSuccessStatuses())) {
                    // set form data value in session

                    $this->setTransaction($event);

                    $this->response = [
                        'order_id'                =>  $this->getOrderId(),
                        'transaction_id'          =>  $this->transaction->id,
                        'payment_method'          =>  $this->transaction->paymentInstrumentType,
                        'payment_status'          =>  $this->transaction->status,
                        'payment_state'           =>  $this->transaction->processorResponseType,
                        'paypal_return_data'      =>  json_encode($this->result),
                        'event_details'           =>  $event,
                        'error'                   =>  false,
                        'status'                  =>  $this->transaction->status,
                        'message'                 =>  "Your transaction has been successfully processed. See the Braintree API response and try again.",
                        'processorResponseText'   =>  $this->transaction->processorResponseText,
                        'isFree'                  =>  false,
                    ];
                } else {
                    $this->response = [
                        'order_id'            =>  $this->getOrderId(),
                        'transaction_id'      =>  $this->transaction->id,
                        'payment_method'      =>  $this->transaction->paymentInstrumentType,
                        'payment_status'      =>  $this->transaction->status,
                        'payment_state'       =>  $this->transaction->processorResponseType,
                        'paypal_return_data'  =>  json_encode($this->result),
                        'event_details'       =>  $extractEvent,
                        'error'               => true,
                        'status'              => $this->transaction->status,
                        'message'             => "Your test transaction has a status of " . $this->transaction->status . ". See the Braintree API response and try again.",
                        'processorResponseText'   => $this->transaction->processorResponseText,
                        'isFree'                  =>  false,
                    ];
                }
            } else {
                $errorString = "";
                foreach($this->result->errors->deepAll() as $error) {
                    $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
                }
                $this->response = [
                    'order_id'            =>  $this->getOrderId(),
                    'transaction_id'      =>  $this->result->transaction->id,
                    'payment_method'      =>  $this->transaction->paymentInstrumentType,
                    'payment_status'      =>  $this->result->success,
                    'payment_state'       =>  $this->transaction->processorResponseType,
                    'paypal_return_data'  =>  json_encode($this->result),
                    'event_details'       =>  $event,
                    'error'               => true,
                    'status'              => $this->result->success,
                    'message'             => $errorString,
                    'processorResponseText'   => $this->transaction->processorResponseText,
                    'isFree'                  =>  false,
                ];
            }
        }
        //dd($this->response);
        return $this->response;
    }
    public function setTransaction($data) {
        $event                                  = DB::table('events')
                                                    ->select('events.title')
                                                    ->where('events.id', $data['event_id'])
                                                    ->first();

        $this->data['event_id']                 = $data['event_id'];
        $this->data['item_name']                = $event->title;
        $this->data['id_events_ticket_assigns'] = $data['id_events_ticket_assigns'];
        $this->data['user_id']                  = $data['user_id'];
        $this->data['member_ticket_quantity']   = $data['member_ticket_quantity'];
        $this->data['member_ticket_price']      = $data['member_ticket_price'];
        $this->data['quantity']                 = $data['quantity'];
        $this->data['quest_ticket_quantity']    = $data['quest_ticket_quantity'];
        $this->data['guest_ticket_price']       = $data['guest_ticket_price'];
        $this->data['price']                    = $data['amount'];
        $this->data['user_name']                = $data['user_name'];
        $this->data['user_email']               = $data['user_email'];
        $this->data['user_phone']               = $data['user_phone'];
        $this->data['user_address']             = $data['user_address'];

        $this->setKeyPairsValue('transaction_data', $this->data);
    }

//    public function freeEvent(Request $request)
//    {
//        $orderId                                = $this->getOrderId();
//        $transaction                            = new Trans();
//        $transaction->order_id                  = $orderId;
//        $transaction->payment_method            = "Free";
//        $transaction->payment_status            = "VERIFIED";
//        $transaction->payment_state             = "approved";
//        $transaction->event_id                  = $request->event_id;
//        $transaction->user_id                   = $request->user_id;
//        $transaction->member_ticket_quantity    = $request->member_ticket_quantity;
//        $transaction->quest_ticket_quantity     = $request->quest_ticket_quantity;
//        $transaction->quantity                  = $request->quantity;
//        $transaction->member_ticket_price       = $request->member_ticket_price;
//        $transaction->guest_ticket_price        = $request->guest_ticket_price;
//        $transaction->price                     = $request->amount;
//        $transaction->user_name                 = $request->user_name;
//        $transaction->user_email                = $request->user_email;
//        $transaction->save();
//        $events_details                         = DB::table('events')
//                                                    ->select('events.title')
//                                                    ->where('events.id',$request->event_id)
//                                                    ->first();
//
//        /*******Update event_ticket_assign table for maintain stock**********/
//        $ticket_assigns                         = DB::table('events_ticket_assigns')
//                                                    ->select('events_ticket_assigns.purchase_quantity','events_ticket_assigns.purchase_guest_ticket')
//                                                    ->where('events_ticket_assigns.id',$request->id_events_ticket_assigns)
//                                                    ->first();
//        /* For guest ticket*/
//        $previous_guest_quantity                = $ticket_assigns->purchase_guest_ticket;
//        $current_guest_stock                    = $previous_guest_quantity + $request->quest_ticket_quantity;
//        /* For guest ticket*/
//
//        /* For member ticket*/
//        $previous_member_quantity               = $ticket_assigns->purchase_quantity;
//        $current_member_stock                   = $previous_member_quantity + $request->member_ticket_quantity;
//        /* For member ticket*/
//
//        $get_data= array();
//        $get_data['purchase_quantity']          = $current_member_stock;
//        $get_data['purchase_guest_ticket']      = $current_guest_stock;
//
//        DB::table('events_ticket_assigns')->where('id', $request->id_events_ticket_assigns)->update($get_data);
//
//        /*******Update event_ticket_assign table for maintain stock**********/
//        return $this->response = [
//            'order_id'                  =>  $orderId,
//            'transaction_id'            =>  '',
//            'payment_method'            =>  'Free',
//            'payment_status'            =>  'VERIFIED',
//            'payment_state'             =>  'approved',
//            'paypal_return_data'        =>  '',
//            'event_details'             =>  '',
//            'error'                     => true,
//            'status'                    => $this->result->success,
//            'message'                   => 'Free event',
//            'processorResponseText'     => '',
//            'events_details'            => $events_details,
//            'orderId'                   => $orderId,
//            'data'                      => $request,
//            'isFree'                    => true,
//        ];
//
//    }

    public function setKeyPairsValue($key, $data) {
        return \Session::put($key, $data);
    }
    public function getKeyPairsValue($key) {
        return \Session::get($key);
    }
    public function getTransaction($id) {
        return $this->gateway->transaction()->find($id);
    }
    public function getTransactionId() {
        return $this->data['transactionId'];
    }
    public function getTransactionSuccessStatuses() {
       return [
            \Braintree\Transaction::AUTHORIZED,
            \Braintree\Transaction::AUTHORIZING,
            \Braintree\Transaction::SETTLED,
            \Braintree\Transaction::SETTLING,
            \Braintree\Transaction::SETTLEMENT_CONFIRMED,
            \Braintree\Transaction::SETTLEMENT_PENDING,
            \Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT
        ];
    }

}