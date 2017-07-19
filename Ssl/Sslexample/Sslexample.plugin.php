<?php
namespace Powerpanel\Plugins\Ssl\SslExample;
/**
 * SSL plugin for PowerPanel
 *
 * @requires   Powerpanel\Plugins\Ssl\PluginAbstract class; PluginAbstract.php
 * @requires   Powerpanel\Plugins\Ssl\PluginInterface interface; PluginInterface.php
 *
 * @copyright  2017 PowerPanel BV
 * @author	   xxxx
 * @version    1.0.0 (2017-03-01)
 * @since      File available since 1.0.0 (2017-03-01)
 */
class SslExample
{
	/**
	 * Passes the initiation values to the base class
	 *
	 * @param	array $credentials Control panel credentials
	 * @return	void
	 */
	public function __construct(array $credentials)
	{
    // You receieve the credentials here in array('username' => 'test', 'password' => 'passwordHere');
    // You can store it in $this->credentials for example.
	}
  // == Internal Functions for PowerPanel Debugging ==
	/**
	 * Getting/Setting the Request data that's send to the 3rd party API. For debugging perpose
	 *
	 * @param	mixed $request data. This can be pure raw XML or an array POST data for example
	 * @return	void
	 */
  public function setRequest($req)
  {
    $this->request = $req;
  }
  public function getRequest()
  {
    return $this->request;
  }
	/**
	 * Getting/Setting the Response data from the 3rd party API. For debugging perpose
	 *
	 * @param	mixed $request data. This can be pure raw XML or an array POST data for example
	 * @return	void
	 */
  public function setResponse($resp)
  {
    $this->response = $resp;
  }
  public function getResponse()
  {
    return $this->response;
  }
	/**
	 * Getting/Setting the Result.
	 *
	 * @param	array $result data
	 * @return	void
	 */
  public function setResult($result)
  {
    $this->result = $result;
  }
  public function getResult()
  {
    return $this->result;
  }
	/**
	 * Getting/Setting the Error(s).
	 *
	 * @param	array $result data
	 * @return	void
	 */
  public function setError($error)
  {
    $this->errors[] = $error;
  }
  public function getError($all = false)
  {
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
  // == Start of SSL Module functions ==
	/**
	 * Searching for available SSL certificate products
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function listProducts(array $input = array())
	{
    // Available:
    //  $input['limit'];
    // Call Api here --> curl GET /products
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result:
    $this->setResult(
      array(
        array(
          'product_id' => '', //Required
          'product_name' => '', //Required. Example: "RapidSSL Certificate"
          'brand_name' => '', //Example: Comodo
          'max_period' => '', //Maximum period which can be ordered. Not required.
          'validation_type' => '', //Which type of validation is required. 'domain', 'extended', 'organization'
          'max_domains' => '', //How many domains can be linked on this product. Standard: 1
          'ssl_type' // wildcard, multi_domain, standard
          //Any other information, as much as the API returns. Above are standards (mostly requires)
        ),
        array(
          'product_id' => '', //Required
          'product_name' => '', //Required. Example: "RapidSSL Certificate"
          'brand_name' => '', //Example: Comodo
          'max_period' => '', //Maximum period which can be ordered. Not required.
          'validation_type' => '', //Which type of validation is required. 'domain', 'extended', 'organization'
          'max_domains' => '', //How many domains can be linked on this product. Standard: 1
          'ssl_type' // wildcard, multi_domain, standard
          //Any other information, as much as the API returns. Above are standards (mostly requires)
        )
      )
    );
    return true;
	}
	/**
	 * Get information from a single SSL certificate product
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function getProductInfo(array $input = array())
	{
    // Available:
    //  $input['product_id'];
    // Call  Api here --> curl GET /products/{id}
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array(
        'product_id' => 25, //Required
        'product_name' => 'RapidSSL Certificate', //Required. Example: "RapidSSL Certificate"
        'brand_name' => 'Comodo', //Example: Comodo
        'max_period' => '10', //Maximum period which can be ordered. Not required. For example: 10 years
        'validation_type' => 'domain', //Which type of validation is required. 'domain', 'extended', 'organization'
        'max_domains' => '1', //How many domains can be linked on this product. Standard: 1
        'ssl_type' => 'standard' // wildcard, multi_domain, standard
        //Any other information, as much as the API returns. Above are standards (mostly requires)
      )
    );
    return true;
	}
	/**
	 * List all available approver email addresses
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function listApproverEmailAddresses(array $input = array())
	{
    // Available:
    //  $input['product_id'];
		//	$input['domain'] / $input['domainname']
    // Call Api here --> curl GET /approver-email-addresses
     //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array(
        'personal-name@example.org',
        'owner@example.org',
        'admin@example.org',
        'domain-admin@example.org'
      )
    );
    return true;
	}
	/**
	 * Get details on a specific certificate
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function getCertificateInfo(array $input = array())
	{
    //Available:
    //  $input['certificate_id'];
    // Call Api here --> curl GET /certificates/{id}
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example) for DNS validation:
    $this->setResult(
      array(
        'certificate_id' => '29843678',
        'product_id' => '32',
        'common_name' => 'example.org',
        'validation_methods' => array(
          'host_name' => array(
            'example.org'
          ),
          'method' => 'dns'
        ),
				'dns' => array(
          'name' => '',
					'type' => 'CNAME', //CNAME, TXT, etc
					'content' => 'somegeneratedhashhere.comodoca.com',
        ),
        'csr' => '-----BEGIN CERTIFICATE REQUEST-----\nMIICyTCC.......',
        'certificate' => '-----BEGIN CERTIFICATE-----\nMIIFXDCCBESg......',
        'intermediate_certificate' => '-----BEGIN CERTIFICATE-----\r\nMII.....',
        'root_certificate' => '-----BEGIN CERTIFICATE-----\r\nMIIEN....'
        //More info info here that we "save" in PowerPanel as raw-data or for debugging (or later use)
      )
    );
    return true;
	}
	/**
	 * List existing certificates and certificate requests
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function listCertificates(array $input = array())
	{
    //Available:
    //  $input['limit'];
    //  $input['common_name_pattern'];
    // Call Api here --> curl GET /certificates
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example), This is an array with multiple certificateInfo's'
    $this->setResult(
      array(
        array(
          'certificate_id' => '29843678',
          'product_id' => '32',
          'common_name' => 'example.org',
          'validation_methods' => array(
            'host_name' => array(
              'example.org'
            ),
            'method' => 'dns'
          ),
          'dns' => array(
            'name' => '',
            'type' => 'CNAME', //CNAME, TXT, etc
            'content' => 'somegeneratedhashhere.comodoca.com',
          ),
          'csr' => '-----BEGIN CERTIFICATE REQUEST-----\nMIICyTCC.......',
          'certificate' => '-----BEGIN CERTIFICATE-----\nMIIFXDCCBESg......',
          'intermediate_certificate' => '-----BEGIN CERTIFICATE-----\r\nMII.....',
          'root_certificate' => '-----BEGIN CERTIFICATE-----\r\nMIIEN....'
          //More info info here that we "save" in PowerPanel as raw-data or for debugging (or later use)
        )
      )
    );
    return true;
	}
	/**
	 * Create a new certificate
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function createCertificate(array $input = array())
	{
    //Available:
    //  $input['period']; // Number of months. 12 = 1 year (default: 1year)
    //  $input['product_id']; // The product ID internally used for the API.
    //  $input['csr']; //string
    //  $input['email_address']; // Approvers email-address
    //  $input['validation_method']; // 'dns', 'email', 'http' (default should be: 'dns')
    /*
        $input['contact_data'] => Array
          ['organization''] => Array
            (
              ['country_code'] => 'NL'
              ['organization'] => 'Test Designer B.v'
              ['department'] =>
              ['state_or_province'] => 'ZH'
              ['city'] => 'Voorhout'
              ['email_address'] => 'admin@testdesigner.com'
              ['name'] => Array
                (
                  ['initials'] => 'T.'
                  ['first_name'] => 'Test'
                  ['last_name'] => 'User'
                )
              ['gender'] => 'M'
              ['address'] => Array
                (
                  ['city'] => 'Amsterdam'
                  ['country'] => 'NL'
                  ['street'] => 'Examplestreet'
                  ['number'] => '55'
                  ['zipcode'] => '2000AB'
                )
              ['phonenumber'] => Array
                (
                  ['country_code'] => '+31'
                  ['area_code'] => '6'
                  ['number'] => '12345678'
                )
              )
    */
    // Call Api here --> curl POST /requests
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array('certificate_id' => '12345') //required
    );
    return true;
	}
	/**
	 * Cancel an existing certificate or certificate request
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function cancelCertificate(array $input = array())
	{
    //Available:
    //  $input['certificate_id'];
    // Call Api here --> curl POST /certificates/{id}/cancel
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array('certificate_id' => '12345') //required
    );
    return true;
	}
	/**
	 * Modify an existing certificate request. Mostly used to change the email address or the validation method (dns,http)
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function modifyCertificate(array $input = array())
  {
    //Available:
    //  $input['certificate_id'];
    //  $input['validation_method'];
    //  $input['email_address'];
    //  $input['hostname'];
    // Call Api here --> curl GET /certificates/{id}
    // $this->setResult($result);
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array('certificate_id' => '12345') //required
    );
    return true;
	}
	/**
	 * Reissue an approved certificate
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function reissueCertificate(array $input = array())
	{
    //Available:
    //  $input['certificate_id']; // The certificate ID used
    //  $input['period']; // Number of months. 12 = 1 year (default: 1year)
    //  $input['product_id']; // The product ID internally used for the API.
    //  $input['csr']; //string
    //  $input['email_address']; // Approvers email-address
    //  $input['validation_method']; // 'dns', 'email', 'http' (default should be: 'dns')
    //  $input['host_names']; // An array of hostnames used for this certificate.
    /*
        $input['contact_data'] => Array
          ['organization''] => Array
            (
              ['country_code'] => 'NL'
              ['organization'] => 'Test Designer B.v'
              ['department'] =>
              ['state_or_province'] => 'ZH'
              ['city'] => 'Voorhout'
              ['email_address'] => 'admin@testdesigner.com'
              ['name'] => Array
                (
                  ['initials'] => 'T.'
                  ['first_name'] => 'Test'
                  ['last_name'] => 'User'
                )
              ['gender'] => 'M'
              ['address'] => Array
                (
                  ['city'] => 'Amsterdam'
                  ['country'] => 'NL'
                  ['street'] => 'Examplestreet'
                  ['number'] => '55'
                  ['zipcode'] => '2000AB'
                )
              ['phonenumber'] => Array
                (
                  ['country_code'] => '+31'
                  ['area_code'] => '6'
                  ['number'] => '12345678'
                )
              )
    */
    // Call Api here --> curl POST /certificates/{id}/reissue
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array('certificate_id' => '12345') //required
    );
    return true;
	}
	/**
	 * SingleSignOn call. If it's not supported we disable this function and internal code will give "Does not support".
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	//public function singleSignOn(array $input = array()) {}
	/**
	 * Renew certificate call.
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function renewCertificate(array $input = array())
	{
    //Available:
    //  $input['certificate_id'];
    // Call Api here --> curl POST /certificates/{id}/renew
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example):
    $this->setResult(
      array(
        'certificate_id' => '12345', //required
        'expiration_date' => '2017-12-18 13:01:40' //required
      )
    );
    return true;
	}
	/**
	 * Test the API connection. Used only for testing if the credentials are valid.
	 *
	 * @param	array $input Input data package
	 * @return	bool True if action was successfull, false on error
	 */
	public function testConnection()
	{
    //Available:
    // none
    // Call Api here --> curl with GET /products for example. And see if that succeeds. If this fails, then the credentials is most likely invalid
    //Return this for any error:
    $this->setError("Not available yet");
		return false;
    //return this for a valid result (example)
    $this->setResult(
      array(
        'connection' => true
      )
    );
    return true;
	}
}
