<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Hash;
use Session;
use Stripe;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;

class StripeDigiPaymentController extends Controller
{
	protected $frontEndUsersDB;
	protected $memberAllDB_model;

	public function __construct()
	{

		$this->frontEndUsersDB = new \App\Models\Frontend\FrontEndUser;
		$this->memberAllDB_model = new \App\Models\MemberAllDB;

	}
	
	public function stripePostCheckout(Request $request){
		
		if(!empty(Session::get('memberId'))){ 
			$userId = Session::get('memberId');
		    $userType = 2;
		}else if(!empty(Session::get('clientId'))){ 
			$userId = Session::get('clientId');
		    $userType = 1;
		}
		$token = $request->stripeToken;
			
			if(!empty($token)){
				try{
				 // SECURITY FIX: Use environment variable instead of hardcoded API key
				 // CRITICAL: Rotate the exposed API key immediately!
				 $stripeSecret = env('STRIPE_SECRET');
				 $stripeCurrency = env('STRIPE_CURRENCY', 'usd');

				 if (empty($stripeSecret)) {
					 throw new \Exception('Stripe API key not configured');
				 }

				 Stripe\Stripe::setApiKey($stripeSecret);
				 
				 		 $newCustomer = 0;
		 
						$customers = Stripe\Customer::all();
						foreach ($customers->autoPagingIterator() as $customer) {


						  if(strcmp($customer['email'],$_POST['stripeEmail'])==0)
						  {
							 $customerId =  $customer['id'];
							 $newCustomer = 1;
						  }
						 
						}
					if($newCustomer==0){
						// Create a Customer:
						$customer = Stripe\Customer::create(array(
						  "email" => $_POST['stripeEmail'],
						  "source" => $token,
						));
						$customerId = $customer->id;
						
						// SECURITY FIX: Verify amount server-side, never trust client input
						// Get the actual package price from session (set when user selects package)
						$serverAmount = Session::get('digicoin_package_stripe_price');
						if (empty($serverAmount) || $serverAmount != $request->input('amount')) {
							throw new \Exception('Payment amount mismatch - possible tampering detected');
						}

						$charge = Stripe\Charge::create(array(
						"customer" => $customerId,
						"amount" => $serverAmount, // Use server-side validated amount
						"currency" => $stripeCurrency,
						"description" => "Digicoin Package Purchase",
						));
						
					}else{
							// SECURITY FIX: Verify amount server-side for existing customers too
							$serverAmount = Session::get('digicoin_package_stripe_price');
							if (empty($serverAmount) || $serverAmount != $request->input('amount')) {
								throw new \Exception('Payment amount mismatch - possible tampering detected');
							}

							$charge = Stripe\Charge::create(array(
							"customer" => $customerId,
							"amount" => $serverAmount, // Use server-side validated amount
							"currency" => $stripeCurrency,
							"description" => "Digicoin Package Purchase",
							));
					}
				 //echo '<pre>';print_r($_POST); die('--INNN');
				 
				 $result = $this->frontEndUsersDB->confirmStripeDigicoinsPayment($userId,$userType,$_POST);
				 $amount = Session::get('digicoin_package_price');
				 if($userType == 1){
					 
					 $clientInfo = $this->frontEndUsersDB->getClientInfo(Session::get('clientId'));
					 
					 $email = urldecode($clientInfo['data'][0]->email);
					 //$email = 'sgtechqa@yopmail.com';
					 
					$name = urldecode($clientInfo['data'][0]->name);					
					
					$data = array('emailId' => $email, 'name' => $name, 'amount'=> $amount);
					Mail::send('mails.buyDigiCoins',['data' => $data], function ($message) use ($data) {
					  $message->to($data['emailId']);
					  $message->subject('Confirmation Buy Digicoins at Digiwaxx');
					  $message->from('business@digiwaxx.com','Digiwaxx');
				   });
					 
				 }else{
					$memberInfo = $this->memberAllDB_model->getMemberInfo(Session::get('memberId')); 
					
					$email = urldecode($memberInfo['data'][0]->email);
					//$email = 'sgtechqa@yopmail.com';
					
					$name = urldecode($memberInfo['data'][0]->fname);
					
					$data = array('emailId' => $email, 'name' => $name, 'amount'=> $amount);
					Mail::send('mails.buyDigiCoins',['data' => $data], function ($message) use ($data) {
					  $message->to($data['emailId']);
					  $message->subject('Confirmation Buy Digicoins at Digiwaxx');
					  $message->from('business@digiwaxx.com','Digiwaxx');
				   });
				   
				 }
					Session::put('paymentDone', 1);
					return Redirect::to("Buy_digicoins_stripe_response");   exit;
				}catch(\Stripe\Error\Card $e){
					$error = $e->getMessage();
					echo $error;
					Session::put('paymentError', 1);
					
					return Redirect::to("Buy_digicoins_stripe_response");   exit;
				}
			}		
	}
	
	public function buyDigicoinsStripeResponse(Request $request){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax - Digiwax Client Payment';
		$output['logo'] = $get_logo;	

		if(!empty(Session::get('memberId'))){

		   $userId = Session::get('memberId');

		   $userType = 2;	   

		   // subscription status

		 $output['subscriptionStatus'] = 0;

		 $output['package'] = '';

		 $output['packageId'] = '';

		 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($userId); 	

			 if($subscriptionInfo['numRows']>0){

			  $output['subscriptionStatus'] = 1;

			  $output['packageId'] = $subscriptionInfo['data'][0]->package_Id;

			   

			  if($subscriptionInfo['data'][0]->package_Id==1)

			  {

				$output['package'] = 'Silver Subscription';			

			  }

			  else if($subscriptionInfo['data'][0]->package_Id==2)

			  {

				$output['package'] = 'Gold Subscription';

			  }

			  else if($subscriptionInfo['data'][0]->package_Id==3)

			  {

				$output['package'] = 'Purple Subscription';

			  }

			}
		}else if(Session::get('clientId')){

		  $userId = Session::get('clientId');

		  $userType = 1;	

		}
		
		if(!empty(Session::get('paymentDone'))){

		   $output['alertClass'] = 'alert alert-primary';

		   $output['alertMessage'] = 'THANK YOU! <br> YOUR PAYMENT HAS BEEN PROCESSED. <br> YOU WILL RECEIVE A CONFIRMATION EMAIL SHORTLY.';
			
		   $output['response_status'] = 'Completed';
			Session::forget('paymentDone');
		   if(Session::has('buyId')){

			 Session::forget('buyId');

		   }			   

		   if(Session::has('digicoin_package_id')){

			 Session::forget('digicoin_package_id');

		   }
		   
		   if(Session::has('digicoin_package_price')){

			 $output['response_price'] = Session::get('digicoin_package_price');
			 
			 Session::forget('digicoin_package_price');

		   }	   

		   if(Session::has('digicoin_package_tittle')){

			 $output['response_tittle'] = Session::get('digicoin_package_tittle');

			 Session::forget('digicoin_package_tittle');

		   }		   

		   if(Session::has('digicoin_package_stripe_price')){

			 Session::forget('digicoin_package_stripe_price');

		   }

		}else if(Session::has('paymentError')){

		   $output['alertClass'] = 'alert alert-danger';

		   $output['alertMessage'] = 'Error occured, please try again!';

		   Session::forget('paymentError');

		 }else{

			return Redirect::to("/");   exit;

		 }
		 
		/*  pArr($output);die(); */
		return view('members.dashboard.BuyDigiCoinStripeResponse', $output);	
	}
	
	
// 	----------------------------------------------------------------------------------------

	public function package_payment(Request $request){
		

		$token = $request->stripeToken;
			
			if(!empty($token)){
				try{
				 // SECURITY FIX: Use environment variable instead of hardcoded API key
				 // CRITICAL: Rotate the exposed API key immediately!
				 $stripeSecret = env('STRIPE_SECRET');
				 $stripeCurrency = env('STRIPE_CURRENCY', 'usd');

				 if (empty($stripeSecret)) {
					 throw new \Exception('Stripe API key not configured');
				 }

				 Stripe\Stripe::setApiKey($stripeSecret);
				 
				 		 $newCustomer = 0;
		 
						$customers = Stripe\Customer::all();
						foreach ($customers->autoPagingIterator() as $customer) {


						  if(strcmp($customer['email'],$_POST['stripeEmail'])==0)
						  {
							 $customerId =  $customer['id'];
							 $newCustomer = 1;
						  }
						 
						}
					if($newCustomer==0){
						// Create a Customer:
						$customer = Stripe\Customer::create(array(
						  "email" => $_POST['stripeEmail'],
						  "source" => $token,
						));
						$customerId = $customer->id;
						
						$charge = Stripe\Charge::create(array(
						"customer" => $customerId,
						"amount" => $_POST['amount']*100, // Amount in cents
						"currency" => "inr", //usd
						"description" => "Example charge",
						));
						
					}else{
							$charge = Stripe\Charge::create(array(
							"customer" => $customerId,
							"amount" => $_POST['amount']*100, // Amount in cents
							"currency" => "inr", // usd
							"description" => "Example charge",
							));
					}
					
	
			        if($charge->status=='succeeded'){
					Session::put('packagepaymentDone', 1);
			         	return Redirect::to("Member_registration_step4");   exit;   
			        }
			        else{				
			           
			           Session::put('paymentError', 1);
					
				    	return Redirect::to("Member_registration_step4");   exit;
			            
			        }
				// 	die();
				
				}catch(\Stripe\Error\Card $e){
					$error = $e->getMessage();
					echo $error;
					Session::put('paymentError', 1);
					
					return Redirect::to("Member_registration_step4");   exit;
				}
			}		
	}
	
// 	----------------------------------------------

    public function package_payment_client(Request $request){
        	

		$token = $request->stripeToken;
			
			if(!empty($token)){
				try{
				 // SECURITY FIX: Use environment variable instead of hardcoded API key
				 // CRITICAL: Rotate the exposed API key immediately!
				 $stripeSecret = env('STRIPE_SECRET');
				 $stripeCurrency = env('STRIPE_CURRENCY', 'usd');

				 if (empty($stripeSecret)) {
					 throw new \Exception('Stripe API key not configured');
				 }

				 Stripe\Stripe::setApiKey($stripeSecret);
				 
				 		 $newCustomer = 0;
		 
						$customers = Stripe\Customer::all();
						foreach ($customers->autoPagingIterator() as $customer) {


						  if(strcmp($customer['email'],$_POST['stripeEmail'])==0)
						  {
							 $customerId =  $customer['id'];
							 $newCustomer = 1;
						  }
						 
						}
					if($newCustomer==0){
						// Create a Customer:
						$customer = Stripe\Customer::create(array(
						  "email" => $_POST['stripeEmail'],
						  "source" => $token,
						));
						$customerId = $customer->id;
						
						$charge = Stripe\Charge::create(array(
						"customer" => $customerId,
						"amount" => $_POST['amount']*100, // Amount in cents
						"currency" => "inr", //usd
						"description" => "Example charge",
						));
						
					}else{
							$charge = Stripe\Charge::create(array(
							"customer" => $customerId,
							"amount" => $_POST['amount']*100, // Amount in cents
							"currency" => "inr", // usd
							"description" => "Example charge",
							));
					}
					
	
			        if($charge->status=='succeeded'){
					Session::put('packagepaymentDone', 1);
			         	return Redirect::to("Client_registration_step3");   exit;   
			        }
			        else{				
			           
			           Session::put('paymentError', 1);
					
				    	return Redirect::to("Client_registration_step3");   exit;
			            
			        }
				// 	die();
				
				}catch(\Stripe\Error\Card $e){
					$error = $e->getMessage();
					echo $error;
					Session::put('paymentError', 1);
					
					return Redirect::to("Client_registration_step3");   exit;
				}
			}
        
        
    }
    
    
    
	public function upgrade_package_payment_member(Request $request){
		

		$token = $request->stripeToken;
			
			if(!empty($token)){
				try{
				 // SECURITY FIX: Use environment variable instead of hardcoded API key
				 // CRITICAL: Rotate the exposed API key immediately!
				 $stripeSecret = env('STRIPE_SECRET');
				 $stripeCurrency = env('STRIPE_CURRENCY', 'usd');

				 if (empty($stripeSecret)) {
					 throw new \Exception('Stripe API key not configured');
				 }

				 Stripe\Stripe::setApiKey($stripeSecret);
				 
				 		 $newCustomer = 0;
		 
						$customers = Stripe\Customer::all();
						foreach ($customers->autoPagingIterator() as $customer) {


						  if(strcmp($customer['email'],$_POST['stripeEmail'])==0)
						  {
							 $customerId =  $customer['id'];
							 $newCustomer = 1;
						  }
						 
						}
					if($newCustomer==0){
						// Create a Customer:
						$customer = Stripe\Customer::create(array(
						  "email" => $_POST['stripeEmail'],
						  "source" => $token,
						));
						$customerId = $customer->id;
						
						$charge = Stripe\Charge::create(array(
						"customer" => $customerId,
						"amount" => $_POST['amount']*100, // Amount in cents
						"currency" => "inr", //usd
						"description" => "Example charge",
						));
						
					}else{
							$charge = Stripe\Charge::create(array(
							"customer" => $customerId,
							"amount" => $_POST['amount']*100, // Amount in cents
							"currency" => "inr", // usd
							"description" => "Example charge",
							));
					}
					
	
			        if($charge->status=='succeeded'){
					Session::put('packagepaymentDone', 1);
					    $pck_id=$_POST['buyId'];
			         	return redirect()->intended("upgrade_package?package_id=$pck_id");   exit;   
			        }
			        else{				
			           
			           Session::put('paymentError', 1);
					
				    return redirect()->intended('member_manage_subscription?status=2');
			            
			        }
				// 	die();
				
				}catch(\Stripe\Error\Card $e){
					$error = $e->getMessage();
					echo $error;
					Session::put('paymentError', 1);
					
					return redirect()->intended('member_manage_subscription?status=2');
				}
			}		
	}
}
