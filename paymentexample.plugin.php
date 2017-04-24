<?php

namespace Powerpanel\Plugins\Payment\Examplepayment;

/**
 * Payment plugin for PowerPanel
 *
 * @copyright  2017 PowerPanel BV
 * @author	   xxxx
 * @version    1.0.0 (2017-04-21)
 * @since      File available since 1.0.0 (2017-04-21)
 */

class Examplepayment{
    private $result;
    private $request;
    private $response;
    private $errors;
    private $settings;

    /**
	 * Passes the initiation values to the base class
	 *
	 * @param	array $credentials Control panel credentials
	 * @return	void
	 */
    function __construct($credentials){
        // You receieve the credentials here in array('username' => 'test', 'password' => 'passwordHere');
        // Store it in $this->setSettings() so you can use it later when needed
    }

    // -- Logging functions for PowerPanel --

    /**
	 * Getting/Setting the Request data that's send to the 3rd party API. For debugging perpose
	 *
	 * @param	mixed $request data. This can be pure raw XML or an array POST data for example
	 * @return	void
	 */
    private function setRequest($req){
        $this->request = $req;
    }

    public function getRequest(){
        return $this->request;
    }

    /**
	 * Getting/Setting the Response data from the 3rd party API. For debugging perpose
	 *
	 * @param	mixed $request data. This can be pure raw XML or an array POST data for example
	 * @return	void
	 */
    private function setResponse($resp){
        $this->response = $resp;
    }

    public function getResponse(){
        return $this->response;
    }

    private function setResult($res){
        $this->result = $res;
    }

    /**
	 * Getting/Setting the Result that is needed in PowerPanel
	 *
	 * @param	array $result data
	 * @return	void
	 */
    public function getResult(){
        return $this->result;
    }

    /**
	 * Getting/Setting the Error(s).
	 *
	 * @param	array $result data
	 * @return	void
	 */
    private function setError($err){
        $this->errors[] = $err;
    }

    public function getError($all = false){
        if($all === true) {
            //Return full array with messages
            return $this->errors;
        }
        else {
            if(isset($this->errors[0])) {
                return $this->errors[0];
            }
            else {
                return 'Unknown error';
            }
        }
    }


    // -- Payment Plugin functions --

    /**
	 * Test the API connection. Used only for testing if the credentials are valid.
	 *
	 * @param	array $input Input data package
	 * @return	bool True if connectiontest was successfull, false on error
	 */
    public function testConnection(){

    }

    /**
	 * Create a payment request.
	 *
	 * @param	array $data Input data package
	 * @return	bool True if connectiontest was successfull, false on error
	 */
    public function paymentRequest($data){
        //data that will be given in the plugin
        $data["amount"];
        $data["description"];
        $data["redirecturl"];
        $data["purchaseid"];
        $data["method"];

        //logging
        $this->setRequest();

        //perform request

        //logging
        $this->setResponse();

        //set the result array
        $result = array(
            'trxid' => '', //unique id of the payment
            'issueurl' => '', //issueurl where the customers needs to be redirected to, to perform the payment
        );

        //set the result
        $this->setResult($result);
        //return true if call was successfull, return false if it wasnt with the set errors
        return true;
    }

    /**
	 * In case of ideal, get all the issuers that are available and return in an array
	 *
	 * @return	bool True if issuerRequest was successfull, false on error
	 */
    public function issuerRequest(){

        //logging
        $this->setRequest();

        //perform request

        //logging
        $this->setResponse();


        $returnarray[] = array(
            'issuerid' => '',
            'issuername' => '',
            'resource' => '',
            'method' => ''
        );

        $this->setResult($returnarray);
        return true;

    }

    /**
	 * Check the status of a payment
	 *
	 * @param	array $input Input data package
	 * @return	bool True if paymentStatusRequest was successfull, false on error
	 */
    public function paymentStatusRequest($data){
        $data["trxid"]; //we give the unique id that was returned in the paymentRequest to check this payments status



        //logging
        $this->setRequest();

        //make a request with the given trxid

        //logging
        $this->setResponse();

        $status = '';
        //possible status returns
        //'Success' if the payment was successfull
        //'Expired' if the customer took to long to pay
        //'Cancelled' if the customer cancelled thepayment
        //'Failure' if the payment failed
        //'Pending' if the payment is still pending
        //'Reversed' if the payment was reversed

        $resultarray = array(
            'trxid' => '',
            'status' => $status,
            'amount' => '',
            'purchaseid' => '',
            'description' => '',
            'entrancecode' => '',
            'issuerid' => '',
            'timestamp' => '',
            'sha1' => '',
            'bic' => '',
            'account' => '',
            'name' => '',
            'city' => ''
        );

        $this->setResult($resultarray);
        return true;
    }
}
