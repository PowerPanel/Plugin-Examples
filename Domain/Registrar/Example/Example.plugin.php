<?php

//Namespace is required. Always ends with the class name
namespace Powerpanel\Plugins\Domain\Registrar\Example;

//Class name is case sensitive. Always starts with a capital letter, then lower letters. Numbers are allowed
Class Example {

	private $server_address = '';
	private $server_port = '';
	private $login = '';
	private $pass = '';
	private $hash = '';

	private $responses = array();
	private $requests = array();

	/**
	 * Constructor for this Plugin class. $settings will have the array full with the plugin settings. "address" "login", "password"
	 *
	 * @param array $settings
	 * @return null
	 */
	public function __construct($settings) {

		$this->setCredentials(
			$settings['address'],
			$settings['login'],
			isset($settings['password']) ? $settings['password'] : null,
			isset($settings['hash']) ? $settings['hash'] : null
		);

	}

	public function __destruct() {
		//Perhaps a logout() / closeConnection() function (if needed)
	}

	/**
	 * Private function to set the API credentials that are given in the __construct()
	 *
	 * @param string $server
	 * @param string $login
	 * @param string $pass
	 * @param string $hash
	 * @return void
	 */
  private function setCredentials($server, $login, $pass, $hash) {

		$this->server_address = $server;
		$this->login = $login;
		$this->pass = $pass;
		$this->hash = $hash;
	}

	/**
	 * Function for setting the Response-logging
	 *
	 * @param string $response
	 * @return void
	 */
	protected function setResponse($response) {
		$response = str_replace($this->pass, '******', $response);
		$response = str_replace($this->hash, '*******************', $response);

		$this->responses[] = $response;
	}

	/**
	 * Getting the response-logging. So PowerPanel can see what has been sent from the 3rd Party API for debugging
	 *
	 * @return mixed (Usually json/xml string)
	 */
	public function getResponse() {
		return $this->responses;
	}

	/**
	 * Function for setting the Request(s)-logging
	 *
	 * @param string $request
	 * @return void
	 */
	protected function setRequest($request) {
		$request = str_replace($this->pass, '******', $request);
		$request = str_replace($this->hash, '*******************', $request);

		$this->requests[] = $request;
	}

	/**
	 * Getting the request-logging. So PowerPanel can see what has been sent through the 3rd Party API for debugging
	 *
	 * @return mixed (Usually json/xml string)
	 */
	public function getRequest() {
		return $this->requests;
	}

	//======= Start of Commands that are called from user actions ================ //

	/**
	 * Tests the connection to the 3rd party API. SIDN/eNom/etc
	 * Could be any kind of command, as long login works.
	 * Called when the plugin is activated in the Control Panel
	 *
	 * @return boolean
	 */
	public function testConnection() {

		//Custom call (curl, EPP, etc) to an API
		$this->custom_api_object->setCall(
			'listCustomers', // We don't need the actual result. Only if logging in works
			array(
				'limit' => 1
			)
		);

		//Example function
		if( $this->custom_api_object->run() === true ) {
			$this->setResult( "Authentication successful" );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Grabbing all domain information. Expire-date, Status, Nameservers, Dnssec, etc
	 *
	 * @param array $data
	 * @return boolean
	 */
  public function getDomainInfo($data = array()) {

		//Information given in $data:
		$data['tld'];								// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];				// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'


		//Example function
		if( $this->custom_api_object->run() === true ) {

			$domain_info = array(
				'domainname' => 'test.com', //always the complete domainname. in utf8 or ascii (both supported)
				'status' => 'ok', // ok, failed, quarantaine, failed, transfered_elsewhere, unknown
				'contact_handles' => array(
					'owner' => 'ABCDE-FGH123', //always needs to be a string. There's always only one registrant
					'admin' => 'ABCDE-FGH123', //Array or string are both supported by the API
					'tech' => 'ABCDE-FGH123', //Array or string are both supported by the API
					'billing' => 'ABCDE-FGH123' //Array or string are both supported by the API
				),
				'nameservers' => array(
					array(
						'id' => '456',//Not required
						'hostname' => 'ns1.nameserver.com', // Required
						'ipaddresses' => '8.8.1.1' //Not required
					),
					array(
						'id' => '473',//Not required
						'hostname' => 'ns2.nameserver.com', // Required
						'ipaddresses' => '8.8.1.2' //Not required
					)
				),
				'authcode' => 'ABC123-ABCD123', // If this registrar/tld supports a transfer (EPP) authcode
				'creation_date' => '2017-07-01 13:21:18',
				'updated_date' => '2017-07-01 13:21:18',
				'expire_date' => '2018-07-01 13:21:18',
				'renewal_date' => '2018-07-01 13:21:18', // Not needed
				'internal_authcode' => 'AfoHfe*hfewo', // Only if it's supported. Not required
				'is_premium_domain' => false, // Not required. Will treat this domain as a 'premium domain' if set to true
				'use_domicile' => false, // Not required. Domicile/Trustee flag will be set to true, if this value is true.
				'autorenew' => 'on', // Not required. If this registrar supports autorenew, we'll grab the status: off / on / default
				'locked' => false, // Not required. If the registrar/tld supports (transfer)locking. We set a flag in PowerPanel that lock is turned on if set to true
				'dnssec' => array( // Can be multiple arrays with DNSSEC Keys
					array(
						'flags' => $dnssec_data['flags'],
						'protocol' => $dnssec_data['protocol'],
						'alg' => $dnssec_data['alg'],
						'public_key' => $dnssec_data['pubKey']
					)
				)
			);

			$this->setResult($domain_info);

			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
  }

	/**
	 * Grab the contact-handle information
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function getContactHandleInfo($data = array()) {
		//Information given in $data:
		$data['contact_id'];				// string. The contact_id that's used at the Registrar. Example: 'ABC-876543'


		//Sample if you want to do more checks:
		if(!isset($data['contact_id']) || $data['contact_id'] == '') {
			$this->setError( "Plugin: Contact_id is empty!" );
			return false;
		}

		//Example function
		if( $this->custom_api_object->run() === true ) {

			$contact_info = array();
			$contact_info['id'] = $value['id'];
			$contact_info['contact_id'] = 'ABC-876543';
			$contact_info['name'] = 'Frank Underwood';
			$contact_info['initials'] = 'F'; //Not needed
			$contact_info['first_name'] = 'Frank';
			$contact_info['prefix'] = '';
			$contact_info['last_name'] = 'Underwood';

			$contact_info['org'] = 'White House';
			$contact_info['gender'] = 'm'; // m / f
			$contact_info['phone'] = '+31.111111111'; // Any formatted phone number. Usually: '+31.111111111'

			$contact_info['address'] = '1600 Pennsylvania Ave NW';
			$contact_info['street'] = 'Pennsylvania Ave NW'; //if more information is available, we explode the data for better support
			$contact_info['house_number'] = '1600'; //if more information is available, we explode the data for better support

			if(isset($value['address']['state'])) {
				// In case if there's a state
				$contact_info['state'] = 'DC';
			}
			$contact_info['city'] = 'Washington';
			$contact_info['zip'] = '20500';
			$contact_info['country'] = 'US';

			$contact_info['email'] = 'email@customer.com';
			$contact_info['comments'] = '';

			//Not supported yet, but in case there's more information than above:
			$contact_info['additional_data'] = array();
			$contact_info['additional_data']['birth'] = array();
			$contact_info['additional_data']['birth']['address'] = '';
			$contact_info['additional_data']['birth']['city'] = '';
			$contact_info['additional_data']['birth']['country'] = '';
			$contact_info['additional_data']['birth']['date'] = '';
			$contact_info['additional_data']['birth']['state'] = '';
			$contact_info['additional_data']['birth']['zipcode'] = '';

			$contact_info['additional_data']['company_registration'] = array();
			$contact_info['additional_data']['company_registration']['city'] = '';
			$contact_info['additional_data']['company_registration']['number'] = '';
			$contact_info['additional_data']['company_registration']['subscription_date'] = '';

			$contact_info['additional_data']['headquarters'] = array();
			$contact_info['additional_data']['headquarters']['address'] = '';
			$contact_info['additional_data']['headquarters']['city'] = '';
			$contact_info['additional_data']['headquarters']['country'] = '';
			$contact_info['additional_data']['headquarters']['state'] = '';
			$contact_info['additional_data']['headquarters']['zipcode'] = '';

			$contact_info['additional_data']['passport_number'] = '';
			$contact_info['additional_data']['socialsecurity_number'] = '';

			$this->setResult( $contact_info );

			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Register / create a domain name
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function createDomain($data = array()) {

		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['period'];															// integer. The period to register the domain with in months. 12 = 1 year. 24 = 2 years. Some registrars support 1 month

		//Contact Handles
		$data['contact_handles']['owner'];						// string. The owner contact_id. Example: 'ABC-123456'
		$data['contact_handles']['admin'];						// array. with string(s). Usually contains 1 string. example: array('ABC-123456')
		$data['contact_handles']['tech'];							// array. with string(s). Usually contains 1 string. example: array('ABC-123456')
		$data['contact_handles']['billing'];					// array. with string(s). Usually contains 1 string. example: array('ABC-123456')

		$data['nameservers'];													// array with 2 or more nameservers arrays. See the next lines for which info it contains
		$data['nameservers'][0]['hostname'];					// string of the hostname. Example: 'ns1.nameserver.com'
		$data['nameservers'][0]['ip_addresses'];			// array of ip-addresses-info arrays See the next lines for which info it contains
		$data['nameservers'][0]['ip_addresses'][0];		// array. Example: array('type' => 'v4', 'ip' => '12.23.23.45')
		$data['nameservers'][0]['ip_addresses'][1];		// array. Example: array('type' => 'v6', 'ip' => 'fe80:0:0:0:200:f8ff:fe21:67cf')

		// Information that is filled in manually (or by default) when registering a domainname in the Control Panel. Or given through the API
		if(isset($data['additional_data']) && count($data['additional_data']) > 0) {
			foreach( $data['additional_data'] AS $key => $data ) {

				$data['fieldname'];												// string. For example: 'domicile' / 'passport_number' / 'whoisprivacy'
				$data['value'];														// mixed. Depending on what fieldname is filled in, this will be that result. For example for domicile: 1, for 'passport_number': 'UFHAKAG14'
				$data['applied_for'];											// string. 'domain' or 'contact'. Some information needs to be applied to a contact (given with the domain) or simply for the domain itself, like whoisprivacy
			}
		}


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {


			$result['pending'] = true; //Pending default is true. We assume that the plugin has polling + it takes time to transfer/register a domain
			if($result['status'] == 'ACT') {
				$result['pending'] = false; //Set pending to false. The domain is active right away.
			}

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Transfer a domain name
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function transferDomain($data) {

		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['period'];															// integer. The period to register the domain with in months. 12 = 1 year. 24 = 2 years. Some registrars support 1 month

		/*
			*	The following fields don't have to be used, for example: SIDN does not support sending this during a transfer. But OpenProvider does.
			* If the plugin doesn't use these, set $result['modified'] = true; --> $result['modified'] = false; And PowerPanel will modify the domain right after the domain has been successfully transfered.
		*/

		//Contact Handles
		$data['contact_handles']['owner'];						// string. The owner contact_id. Example: 'ABC-123456'
		$data['contact_handles']['admin'];						// array. with string(s). Usually contains 1 string. example: array('ABC-123456')
		$data['contact_handles']['tech'];							// array. with string(s). Usually contains 1 string. example: array('ABC-123456')
		$data['contact_handles']['billing'];					// array. with string(s). Usually contains 1 string. example: array('ABC-123456')

		$data['nameservers'];													// array with 2 or more nameservers arrays. See the next lines for which info it contains
		$data['nameservers'][0]['hostname'];					// string of the hostname. Example: 'ns1.nameserver.com'
		$data['nameservers'][0]['ip_addresses'];			// array of ip-addresses-info arrays See the next lines for which info it contains
		$data['nameservers'][0]['ip_addresses'][0];		// array. Example: array('type' => 'v4', 'ip' => '12.23.23.45')
		$data['nameservers'][0]['ip_addresses'][1];		// array. Example: array('type' => 'v6', 'ip' => 'fe80:0:0:0:200:f8ff:fe21:67cf')

		// Information that is filled in manually (or by default) when registering a domainname in the Control Panel. Or given through the API
		if(isset($data['additional_data']) && count($data['additional_data']) > 0) {
			foreach( $data['additional_data'] AS $key => $data ) {

				$data['fieldname'];												// string. For example: 'domicile' / 'passport_number' / 'whoisprivacy'
				$data['value'];														// mixed. Depending on what fieldname is filled in, this will be that result. For example for domicile: 1, for 'passport_number': 'UFHAKAG14'
				$data['applied_for'];											// string. 'domain' or 'contact'. Some information needs to be applied to a contact (given with the domain) or simply for the domain itself, like whoisprivacy
			}
		}

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			$result['modified'] = true; // We give this value if the modification is already done during transfer (Nameservers/contacts/etc) Default is false
			$result['pending'] = true; //Pending default is true. We assume that the plugin has polling + it takes time to transfer/register a domain

			if($result['status'] == 'ACT') {
				$result['pending'] = false; //Set pending to false. The domain is active right away.
			}

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}

		$this->setResult( $result );
		return true;
	}

	/**
	 * Delete a domain name, usually
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function deleteDomain($data) {

		//Information given in $data:
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example result
			$result = array(
				'msg' => 'Domain has been successfully deleted',
				'status' => 'quarantine'
			);

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Modifies a domain. This could be nameservers / dnssec / contact-handles / or any other values
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function modifyDomain($data) {

		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'

		// if isset() these values are set to change:
		$data['nameservers'];													// array with 2 or more nameservers arrays. See the next lines for which info it contains
		$data['nameservers'][0]['hostname'];					// string of the hostname. Example: 'ns1.nameserver.com'
		$data['nameservers'][0]['ip_addresses'];			// array of ip-addresses-info arrays See the next lines for which info it contains
		$data['nameservers'][0]['ip_addresses'][0];		// array. Example: array('type' => 'v4', 'ip' => '12.23.23.45')
		$data['nameservers'][0]['ip_addresses'][1];		// array. Example: array('type' => 'v6', 'ip' => 'fe80:0:0:0:200:f8ff:fe21:67cf')

		// if isset() these values are set to change:
		$data['contact_handles']['owner'];
		$data['contact_handles']['admin'];						// array.
		$data['contact_handles']['tech'];							// array.
		$data['contact_handles']['billing'];					// array.

		// if isset() these values are set to change:
		if($data['dnssec']['off'] && $data['dnssec']['off'] == true) {
			// Turn dnssec off
		}
		elseif(isset($data['dnssec']['keys']) && isset($data['dnssec']['keys'][0]['public_key'])) {
			foreach($data['dnssec']['keys'] AS $dnssec) {

				//These are the given key/values:
				$dnssec['flags'];
				$dnssec['protocol'];
				$dnssec['alg'];
				$dnssec['public_key'];
			}
		}


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example result
			$result = array(
				'msg' => 'Domain has been successfully deleted',
				'status' => 'quarantine'
			);

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Create a contact handle. Used for registrant/technical/billing handles on a domainname
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function createContactHandle($data) {

		//Information given in $data:

		$data['name']; // Stewie Griffin
		$data['company_name']; // Company B.v.
		$data['address'];	// Somestreetname 34
		$data['address_data']; //array (Not fully supported)
			$data['address_data']['street']; // Somestreetname
			$data['address_data']['house_number']; //34
			$data['address_data']['city']; // Amsterdam
			$data['address_data']['zipcode']; // 2041GB
			$data['address_data']['country_code']; // NL
		$data['city']; // Amsterdam
		$data['zipcode']; // 2041GB
		$data['country_code']; // NL
		$data['phone_number']; // +31.1234567890
		$data['fax_number']; // +31.1234567890
		$data['email_address']; // stewie@griffin.com

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example result
			$result = array(
				'contact_id' => 'ABCD-1234567', // The unique contact-id that's given from the registrar
			);

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Modify a contact handle. Used for registrant/technical/billing handles on a domainname
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function modifyContactHandle($data) {

		//Information given in $data:

		$data['name']; // Stewie Griffin
		$data['company_name']; // Company B.v.
		$data['address'];	// Somestreetname 34
		$data['address_data']; //array (Not fully supported)
			$data['address_data']['street']; // Somestreetname
			$data['address_data']['house_number']; //34
			$data['address_data']['city']; // Amsterdam
			$data['address_data']['zipcode']; // 2041GB
			$data['address_data']['country_code']; // NL
		$data['city']; // Amsterdam
		$data['zipcode']; // 2041GB
		$data['country_code']; // NL
		$data['phone_number']; // +31.1234567890
		$data['fax_number']; // +31.1234567890
		$data['email_address']; // stewie@griffin.com

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example result
			$result = array(
				'msg' => 'Contact modified' //This could be the 1:1 message from the 3rd party API (not needed)
			);

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Availabily check. Checks if the domainname is available for registration or not
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function checkAvailable($data) {

		//Information given in $data:
		foreach($data['domainnames'] AS $domainname_info) {
			$domainname_info['domainname'];
			$domainname_info['tld'];
		}


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example result
			foreach($values AS $value) {
				$result[] = array(
					'domainname' => $value['domain'],
					'available' => true, // Boolean. True / false
					'reason' => $reason // String. "Unavailable", "Already active", etc
				);
			}

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * This will reactive/restore a domainname out of Quarantaine
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function reactivate($data) {

		//Information given in $data:
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {
			$result = array();
			$result['msg'] = 'Domain has been successfully restored';

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * This will (transfer)lock a domainname. Usually called when a domain name has been registered / transfered
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function lock($data = array()) {

		//Information given in $data:
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['status'];															// string. 'on' / 'off'. Set the lock on or off.

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {
			$result = array();
			$result['msg'] = 'Domain lock is now '.$data['status']; // Just a message if it's turned on or off

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Function that removes all (Transfer) locks and sets autorenew OFF, auto-approve ON
	 * Maybe also refresh the authcode/transfertoken?
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function prepareTransferOut($data) {

		//Information given in $data:
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {
			$result = array();
			$result['msg'] = 'Domain has been modified to prepare transfer Out';

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Request the authcode / epp code / transfer token
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function requestAuthCode($data) {
		//Information given in $data:
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			//Example function
			$domain_info = $this->getDomainInformation();

			if(isset($domain_info['authcode']) && ($domain_info['authcode'] != '')) {
				$result = array(
					'authcode' => $domain_info['authcode'],
				);
				$this->setResult( $result );
				return true;
			}
			else {
				$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
				return false;
			}
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Domain trade
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function trade($data = array()) {

		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['period'];															// integer. The period to trade the domain with in months. 12 = 1 year. 24 = 2 years. Some registrars support 1 month

		$data['nameservers'];													// array with 2 or more nameservers arrays. See the next lines for which info it contains
		$data['nameservers'][0]['hostname'];					// string of the hostname. Example: 'ns1.nameserver.com'
		$data['nameservers'][0]['ip_addresses'];			// array of ip-addresses-info arrays See the next lines for which info it contains
		$data['nameservers'][0]['ip_addresses'][0];		// array. Example: array('type' => 'v4', 'ip' => '12.23.23.45')
		$data['nameservers'][0]['ip_addresses'][1];		// array. Example: array('type' => 'v6', 'ip' => 'fe80:0:0:0:200:f8ff:fe21:67cf')

		$data['contact_handles']['owner'];
		$data['contact_handles']['admin'];						// array.
		$data['contact_handles']['tech'];							// array.
		$data['contact_handles']['billing'];					// array.

		$data['authcode'];														// String. Authcode

		// Additional data. Still work in progress.

		if(isset($data['additional_data']) && count($data['additional_data']) > 0) {
			foreach( $data['additional_data'] AS $key => $extra ) {
				if(is_array($extra)) {
					if($extra['fieldname'] == 'domicile') {

						// $extra['value'] --> boolean true/false
					}
					if($extra['fieldname'] == 'whoisprivacy') {
						// $extra['value'] --> boolean true/false
					}
					if($extra['applied_for'] == 'domain') {
						//Only for domain. Some fields are for contacts only
						$args[$extra['fieldname']] = $extra['value'];
					}
				}
			}
		}


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			// call success

			$result['pending'] = true; //Pending default is true. We assume that the plugin has polling + it takes time to transfer a domain
			// Set this to false if you know the trade was finished right away, or the registrar does not support polling

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Renews a domain
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function renew($data = array()) {
		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['period'];															// integer. The period to trade the domain with in months. 12 = 1 year. 24 = 2 years. Some registrars support 1 month


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			// call success
			$result = array(); // Info of the domain renewal. Not used for setting any info in PP

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Setting the (auto)renewal of a domain. Some Registrars don't support this (SIDN) RRPProxy & OpenProvider support: on/off/default
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function setRenewal($data = array()) {
		//Information given in $data:
		$data['tld'];																	// string. The Domain extension, tld without a dot. Example: 'com'
		$data['domainname'];													// string. The domainname itself (including tld). As UTF8/Unicode. If needed, use idn_to_ascii(). Example: 'test.com'
		$data['renewal'];															// string. 'default', 'on', 'off'

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			// call success
			$result = array(); // Info of the domain setRenewal. Not used for setting any info in PP

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Registering nameservers. Used for GLUE
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function registerNameserver($data = array()) {

		$data['nameserver'];

		$data['nameserver'];													// array with 2 or more nameservers arrays. See the next lines for which info it contains
		$data['nameserver'][0]['hostname'];					// string of the hostname. Example: 'ns1.nameserver.com'
		$data['nameserver'][0]['ip_addresses'];			// array of ip-addresses-info arrays See the next lines for which info it contains
		$data['nameserver'][0]['ip_addresses'][0];		// array. Example: array('type' => 'v4', 'ip' => '12.23.23.45')
		$data['nameserver'][0]['ip_addresses'][1];		// array. Example: array('type' => 'v6', 'ip' => 'fe80:0:0:0:200:f8ff:fe21:67cf')

		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {

			// call success
			$result = array(); // Info of the nameserver perhaps. Not used for setting any information in PowerPanel

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

	/**
	 * Function that retrieves all domainnames as an export. It will import it into PowerPanel.
	 * Remove this function completely if the Registrar does not support importing through an API (Like SIDN)
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function retrieveDomains($data = array()) {

		//Information given in $data:
		// None


		//Example function to check if the call succeeded
		if( $this->custom_api_object->run() === true ) {
			$result = array();
			//foreach
			$result[] = array(
				'domainname' => 'test.com',
				'expire_date' => '2018-08-01 16:21:11'
			);
			$result[] = array(
				'domainname' => 'test2.com',
				'expire_date' => '2018-09-01 15:31:23'
			);
			//etc

			$this->setResult( $result );
			return true;
		}
		else {
			$this->setError( "Here the error message on why it failed" ); //This could be the 1:1 error of the 3rd party API
			return false;
		}
	}

}
