Please copy the below file(s) from download zip to your project folder

Source                              -->                         Destination Path(s)
------------------------------------------------------------------------------------

application/third_party/omnipay/*  --> application/third_party/omnipay/*

application/libraries/Omnipay_payment.php  --> application/libraries/

Steps to use the library

Step1:

Load the omnipay library in your controller
$this->load->library("omnipay_payment");

Step2:

Call the do_onsite_payment method or do_offsite_payment for onsite or offsite payment methods respectively for making a purchase.
The do_offsite_payment method will redirect you to the respective payment page of the payment gateway, while the do_onsite_payment method would straightaway make a purchase call and fetch the response.

Please take a note that while calling the above methods, the function will require two parameters i.e. the payment method to be used and user parameters i.e. arrray of amount, currency, credit card number, cvv number, etc.. (specify example)

Step3:

If a successful payment is captured, the above method will return an array of the result in case of onsite payments or would redirect to the specified link in case of offsite payments. Else it would return array containing error response codes and messages.

Please take a note while handling offsite response, we need to reinitialize the payment library since the page is refreshed (redirected from payment gateway). 

You may reinitialize the gateway by using the below code:
$gateway_Obj = $this->omnipay_payment->set_gateway("METHOD_NAME");
and can continue further process using the object.


General Note:
There is a module named payment made to demonstrate the working of library. If that module is needed then include that module in libraries/Authenticate.php
in $front_allow_arr of authenticate() function.

"payment" => array(
                'test_payment' => array('index', 'do_payment', 'get_user_params','paypal_express_response','payment_test'),
            )



http://192.168.30.42/sandeep/base_test_2_2/payment/test_payment/payment_test/paypal_express

http://192.168.30.43/apigen/api/index.html



specify config steps
payment config seperate file
specify all methods required fields (parameters)
specify all methods sandbox reg url, 


