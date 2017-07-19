<?php
namespace Powerpanel\Plugins\Invoice\Twinfield;

/*


$vatcode_data_example = array(
    'vat_code' => 'TESTBLA',
    'country_code' => 'NL',
    'percentage' => 21,
    'shortname' => 'BTW_NL',
    'name' => 'BTWtest',
    'ledger_code' => 1500,
    'vat_group' => 'NL5B'
);

$invoicedata_example = array(
    'transaction_number' => '201600010'
);
*/
/**
 * @package     Powerpanel Twinfield API
 * @author      E. Maurits
 * Filename:    Twinfield.plugin.php
 * Author:
 * Created:     25-8-2015
 *
 * Description: De base class voor het gebruik van Twinfield met Powerpanel
 *
 */
class Twinfield {
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

        /*
            //example of the credentials and settings that you will recieve
            $credentials = array(
                'username' => '',
                'password' => '',
                'vat_domestic' => '',
                'vat_eu_private' => '',
                'vat_eu_company' => '',
                'vat_outside_eu' => '',
                'subcription_types' => array(
                    '0' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '1' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '2' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '3' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '4' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '5' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '6' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '7' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '8' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    ),
                    '10' => array(
                        'subscription_type_name' => '',
                        'subscruption_ledger_code' => '8020'
                    )
                )
            );
        */

    }

    // -- Logging functions for PowerPanel --

    /**
	 * Getting/Setting the Request data that's send to the 3rd party API. For debugging perpose
	 *
	 * @param	mixed $request data. This can be pure raw XML/json or an array POST data for example
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
	 * @param	mixed $request data. This can be pure raw XML/json or an array POST data for example
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


    /**
	* Test the API connection. Used only for testing if the credentials are valid.
	*
	* @param	array $input Input data package
	* @return	bool True if connectiontest was successfull, false on error
	*/
    public function testConnection($settings){

    }

    /**
    * Add a customer to the accounting software
    *
    * @param	array $data Input data package. At https://api.powerpanel.io/docs/Customer/get you will see the array you will receive
    * @return	bool True if customer was succesfully added, if not, return false. The errors can be set with the function setError()
    */
    public function addCustomer($data){

    }

    /**
    * @param	array $data Input data package. At https://api.powerpanel.io/docs/Customer/get you will see the array you will receive
    * @return	bool True if customer was succesfully updated, if not, return false. The errors can be set with the function setError()
    */
    public function updateCustomer($data){

    }

    /**
     * retrieve all the customers from the accounting software
     * @return bool true/false
     */
    public function getAllCustomers(){
        //retrieve the data and set it in the right format

        //create an array for all the customers that you retrieve
        //example
          foreach($result as $customer){
                     $temparray = array(
                         'customer_id' => '',
                         'name' => '',
                         'address' => '',
                         'zipcode' => '',
                         'city' => '',
                         'country' => '',
                         'country_code' => '',
                         'email_address' => '',
                         'phonenumber' => '',
                         'mobilenumber' => '',
                         'faxnumber' => '',
                         'coc_number' => '',
                         'tax_number' => '',
                         'gender' => '',
                         'companyname' => '',
                         'website' => '',
                         'comment' => '',
                         'invoice_method' => '',
                         'invoice_authorisation' => '',
                         'account_iban' => '',
                         'account_bic' => '',
                         'bankaccount_holder_name' => '',
                         'account_name' => '',

                     );
                     $customer_array[] = $temparray;
                 }

        //set the result
        $this->setResult($customer_array);
        return true;
    }

    /**
     * Add a transaction to the accounting software
     * @param array $data, array with all the data needed for an invoice
     * @return bool set the result with the function setResult()
     */
    public function addInvoice($data){
        //the data that you will recieve
        $data = array(
            'id' => '',
            'number' => '',
            'status_id' => 1,
            'date' => "2017-03-01",
            'date_readable' => '01 mrt 2017',
            'paid_date' => '',
            'sent_date' => '2017-03-01',
            'hash' => 'hashhashhash12345',
            'is_credit' => false,
            'expiry_date' => '2017-03-15',
            'expiry_date_readable' => '15 mrt 2017',
            'dates' => array(
                'invoice' => array(
                    'year' => 2017,
                    'month' => '03',
                    'month_full' => 'March',
                    'month_short' => 'Mar',
                    'day' => '01',
                    'day_of_month' => 1,
                    'day_full' => 'Wednesday',
                    'day_short' => 'Wed',
                    'number_of_days_this_month' => 31
                ),
                'paid' => array(),
                'sent' => array(
                    'year' => 2017,
                    'month' => '03',
                    'month_full' => 'March',
                    'month_short' => 'Mar',
                    'day' => '01',
                    'day_of_month' => 1,
                    'day_full' => 'Wednesday',
                    'day_short' => 'Wed',
                    'number_of_days_this_month' => 31
                ),
                'expiry' => array(
                    'year' => 2017,
                    'month' => '03',
                    'month_full' => 'March',
                    'month_short' => 'Mar',
                    'day' => '01',
                    'day_of_month' => 15,
                    'day_full' => 'Wednesday',
                    'day_short' => 'Wed',
                    'number_of_days_this_month' => 31
                )
            ),
            'price' => array(
                'gross_amount' => '69.87',
                'netto_amount' => '69.87',
                'tax_amount' => '14.67',
                'total_amount' => '84.54',
                'gross' => '69.87',
                'netto' => '69.87',
                'vat' => '14.67',
                'total' => '84.54',
                'per_subscription_type' => array(
                    0 => array(
                        'type_id' => 1,
                        'ledger_code' => '8007',
                        'name' => 'Domeinregistratie',
                        'total' => '9.99'
                    ),
                    1 => array(
                        'type_id' => 4,
                        'ledger_code' => '8008',
                        'name' => 'Public cloud',
                        'total' => '59.88'
                    )
                )
            ),
            'lines' => array(
                0 => array(
                    'id' => '',
                    'name' => '',
                    'description' => '',
                    'product_name' => '',
                    'gross_amount' => '',
                    'netto_amount' => '',
                    'discount_amount' => '',
                    'price' => array(
                        'unit' => '',
                    'gross' => '',
                    'netto' => '',
                    'discount' => '',
                    ),
                    'units' => '',
                    'ledger_code' => '',
                    'discount_percentage' => '',
                    'subscription_type_id' => '',
                    'period' => '',
                    'period_id' => '',
                    'subscription_type' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'subscription_id' => '',
                    'invoice_id' => '',
                    'order_id' => '',
                    'is_order' => '',
                    'amount' => '',
                    'number' => ''
                ),
                1 => array(
                    'id' => '',
                    'name' => '',
                    'description' => '',
                    'product_name' => '',
                    'gross_amount' => '',
                    'netto_amount' => '',
                    'discount_amount' => '',
                    'price' => array(
                        'unit' => '',
                    'gross' => '',
                    'netto' => '',
                    'discount' => '',
                    ),
                    'units' => '',
                    'ledger_code' => '',
                    'discount_percentage' => '',
                    'subscription_type_id' => '',
                    'period' => '',
                    'period_id' => '',
                    'subscription_type' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'subscription_id' => '',
                    'invoice_id' => '',
                    'order_id' => '',
                    'is_order' => '',
                    'amount' => '',
                    'number' => ''
                )
            ),
            'vat' => array(
                'percentage' => '',
                'value' => '',
            ),
            'customer' => array(
                'id' => '',
                'custom_id' => '',
                'name' => '',
                'vendor_id' => '',
                'is_reseller' => '',
                'is_end_customer' => '',
                'address_data' => array(
                    'address' => '',
                    'zipcode' => '',
                    'city' => '',
                    'country_code' => '',
                    'country' => ''
                ),
                'phonenumbers' => array(
                    array(
                        'original' => '',
                        'international' => '',
                        'country_code' => '',
                        'region' => '',
                        'carrier' => '',
                        'type' => ''
                    )
                ),
                'registered_date' => '',
                'terms_of_payment_days' => '',
                'company_number' => '',
                'bankaccount_number' => '',
                'custom_attribute' => '',
                'users' => array(
                    0 => array(
                        'id' => '',
                        'cu_id' => '',
                        'status' => '',
                        'status_id' => '',
                        'name' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'email' => '',
                        'role' => '',
                        'language' => '',
                        'gender' => '',
                        'last_login' => ''
                    )
                ),
                '' => '',
                '' => '',
                '' => ''
            ),
            'vendor' => array(
                'id' => '',
                'custom_id' => '',
                'name' => '',
                'vendor_id' => '',
                'address_data' => array(
                    'address' => '',
                    'zipcode' => '',
                    'city' => '',
                    'country_code' => '',
                    'country' => ''
                ),
                'phonenumbers' => array(
                    array(
                        'original' => '',
                        'international' => '',
                        'country_code' => '',
                        'region' => '',
                        'carrier' => '',
                        'type' => ''
                    )
                ),
                'support_phonenumber' => '',
                'website_url' => '',
                'registered_date' => '',
                'vat_number' => '',
                'terms_of_payment_days' => '',
                'company_number' => '',
                'bankaccount_number' => '',
                'cp_url' => '',
                'default_email' => '',
                'email' => array(
                    'domain' => '',
                    'invoice' => ''
                )
            ),
            'STR_FACTUUR' => '',
            'STR_FACTUUR_NUMMER' => '',
            'STR_KLANT_NUMMER' => '',
            'STR_FACTUUR_DATUM' => '',
            'STR_VERVALDATUM' => '',
            'STR_INCASSODATUM' => '',
            'STR_SUBTOTAAL' => '',
            'STR_BTW' => '',
            'STR_TOTAAL' => '',
            'STR_BRUTO' => '',
            'STR_NETTO' => '',
            'STR_OMSCHRIJVING' => '',
            'STR_PERIODE' => '',
            'STR_FACTUUR_OVERMAKING' => '',
            'STR_FACTUUR_BETALINGSTERMIJN' => ''
        );

        //logging
        $this->setRequest($request_fields);

        //add the data to the accounting software
        $direct_response = $addinvoice;

        //logging
        $this->setResponse($direct_response);

        //check the result
        if($direct_response == $succesfull){
            $result = $this->cleanupFunction($direct_response);
            //clean up result
            $this->setResult(array(
                    'message' => '',
                    'invoice_id' => '',
                    'transaction_number' => '', //unique transaction number in the accounting software, will be needed to retrieve the payment status or to delete the request
                    'raw' => $result
                ));
            return true;
        } else {
            $this->setError($error);
            return false;
        }

    }

    /**
     * Add transactions by bulk to the accounting software
     * @param array $data, array with all the data needed for an invoice
     * @return bool set the result with the function setResult()
     */
    public function addInvoiceBulk($data){
        //same as invoice only you will recieve the $data in another array
        $data = array(
            'invoice' => array(
                0 => array(
                    'invoicedata' => 'same as addInvoice()'
                ),
                1 => array(
                    'invoicedata' => 'same as addInvoice()'
                )
            )
        );

        //return true/false based on the result
        //use setRequest, setResponse, setResult, setError for logging
        foreach($result as $resultmessage){
            $resultarray[] = array(
                'message' => '',
                    'invoice_id' => '',
                    'transaction_number' => '', //unique transaction number in the accounting software, will be needed to retrieve the payment status or to delete the request
                    'raw' => $result
            );
        }

        $this->setResult($resultarray);

    }

    /**
     * Delete a transaction from the accounting software
     * @param array $invoicedata array with the transaction_number you have returned in the addInvoice function
     * @return bool set the result with the function setResult()
     */
    public function deleteInvoice($invoicedata){
        //you will get the transaction_number you have returned at the addInvoice function like this:
        //$invoicedata["transaction_number"]

        //logging

        //delete the transaction if possible

        //logging

        //set the result or error
    }

    /**
     * Get the paymentstatus of a single invoice
     * @param array $invoicedata array with the transaction_number you have returned in the addInvoice function
     * @return bool set the result with the function setResult()
     */
    public function getInvoicePaymentstatusSingle($invoicedata){
        //you will get the transaction_number you have returned at the addInvoice function like this:
        //$invoicedata["transaction_number"]

        //logging

        //get the payment status of the invoice

        //logging

        //set the result or error
        $this->setResult(array(
            'message' => 'message string',
            'paid' => true //boolean
        ));
    }

    /**
     * Get the paymentstatus of the invoices from a specific time set
     * @param array $data with the start and enddate to look between
     * @return bool set the result with the function setResult()
     */
    public function getInvoicePaymentstatusBulk($data){
        //you will recieve a start date and an end date to check if payments in that period have been paid
        //Only return the transaction_number of invoices that are fully paid
        $data = array(
            'startyear' => '',
            'startmonth' => '',
            'endyear' => '',
            'endmonth' => ''
        );

        //logging

        //make request

        //logging

        //result
        foreach($result as $result){
                $resultarray[] = $result["transaction_number"];
        }

        //set the result
        $this->setResult($resultarray);
        return true;
    }
}
