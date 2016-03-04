<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define("COSUMER_KEY","ac296876f846b14697e6d3535d78174b2f81bdfe");
	define("CONSUMER_SECRET","0b35ea4ccdd6ae5da7df297b83b7ae542a5a4100");
class Dummy extends CI_Controller {

	function __construct(){
		parent::__construct();
		
		$this->load->model('opportunity_model');
		$this->load->model('client_model');
		$this->load->model('acquisition_model');
		$this->load->model('user_model');
		$this->load->model('notification_model');
		$this->load->model('client_model');
		$this->load->model('general_model');
		$this->layout->auto_render=false;	
		$this->layout->layout='default';		
	}
	public $http_code;
	public $http_info;
	public $url;
	public $request_url = 'https://www.lucidchart.com/oauth/requestToken';
	public	$authorize_url = 'https://www.lucidchart.com/oauth/authorize';
	public	$accesstoken_url = 'https://www.lucidchart.com/oauth/accessToken';
	
	
	function index(){
		if(!isset($_SESSION)){
				session_start();
			}
		$this->load->library('OAuthConsumer',array('key'=>COSUMER_KEY,'secret'=>CONSUMER_SECRET));		
		$OAuthServer = new OAuthServer("");
		$sha1_method =  new OAuthSignatureMethod_HMAC_SHA1();
		$OAuthRequest = new OAuthRequest("GET",$this->request_url);
		/*$token = OAuthUtil::parse_parameters($OAuthRequest);*/
		$parameters['oauth_callback'] = $this->config->base_url()."dummy/check_authentication_from_illustrator";
		$request = OAuthRequest::from_consumer_and_token($this->oauthconsumer, null, "GET", $this->request_url, $parameters);
		$request->sign_request($sha1_method, $this->oauthconsumer, null);
		$_SESSION['oauth_signature'] = $request->get_parameter('oauth_signature');
		$content =  $this->http($request->to_url(), 'GET');
		if($content){			
			 switch ($this->http_code) {
			  case 200:
			   
				$request_token = OAuthUtil::parse_parameters($content);
				$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
				
				$url = $this->authorize_url."?oauth_token={$token}";
				header('Location: ' . $url); 
				break;
			  default:
				/* Show notification if something went wrong. */
				echo 'Could not connect to Tumblr. Refresh the page or try again later.';
			}

		}
		
		die;
		
	}
	
	function check_authentication_from_illustrator() {
		if(!isset($_SESSION)){
				session_start();
			}	
		$this->load->library('OAuthConsumer',array('key'=>COSUMER_KEY,'secret'=>CONSUMER_SECRET));		
		$sha1_method =  new OAuthSignatureMethod_HMAC_SHA1();
		$consumer = new OAuthConsumer(array('key'=>COSUMER_KEY,'secret'=>CONSUMER_SECRET));
		if (!empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
			$token = new OAuthConsumer(array('key'=>$_SESSION['oauth_token'],'secret'=>$_SESSION['oauth_token_secret']));
		}else {
			$token = NULL;
		}		
		$parameters = array();
		if (isset($_GET['oauth_verifier'])) {
			$parameters['oauth_verifier'] = $this->input->get('oauth_verifier');	
		}
		$request = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $this->accesstoken_url, $parameters);
		$request->sign_request($sha1_method, $consumer, $token);
		$newRequest = $this->http($request->to_url(), 'GET');
		$token = OAuthUtil::parse_parameters($newRequest);
		
		$access_token = new OAuthConsumer(array('key'=>$token['oauth_token'],'secret'=>$token['oauth_token_secret']));
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $token;
		if($_SESSION['access_token']){
			$this->createDocument();
		}		
		die;
		
	}
	
	function createDocument(){	
		if(!isset($_SESSION)){
				session_start();
			}
		if (!empty($_SESSION['access_token'])) {				
			$this->load->library('OAuthConsumer',array('key'=>COSUMER_KEY,'secret'=>CONSUMER_SECRET));		
			$sha1_method =  new OAuthSignatureMethod_HMAC_SHA1();
			$consumer = new OAuthConsumer(array('key'=>COSUMER_KEY,'secret'=>CONSUMER_SECRET));
			$token = new OAuthConsumer(array('key'=>$_SESSION['access_token']['oauth_token'],'secret'=>$_SESSION['access_token']['oauth_token_secret']));
			$parameters = array();
			$parameters['callback'] = urlencode($this->config->base_url()."dummy/doclist");
			$parameters['app'] = "SynPat1";
			$request = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', "https://www.lucidchart.com/api/newDoc", $parameters);
			$request->sign_request($sha1_method, $consumer, $token);
			echo "<a href='".$request->to_url()."'>".$request->to_url()."</a>";
			//$newRequest = $this->http($request->to_url(), 'GET');
			//echo "<pre>";
			//print_r($newRequest);
		} else {
			redirect('dummy/index');
		}
		
	}
	
	function doclist(){
		
		die;
	}
	function get_authorise(){
		$oauth_token = $this->input->get('oauth_token');
		$oauth_verifier = $this->input->get('oauth_verifier');
		
		die;
	}
	
	function http($url, $method, $postfields = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */
   /* curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);*/
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ci, CURLOPT_TIMEOUT, 30);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
    return $response;
  }
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
	

	/*function index(){
		define("COSUMER_KEY","ac296876f846b14697e6d3535d78174b2f81bdfe");
		define("CONSUMER_SECRET","0b35ea4ccdd6ae5da7df297b83b7ae542a5a4100");
		$request_url = 'https://www.lucidchart.com/oauth/requestToken';
		$consumer_key = COSUMER_KEY;
		$consumer_secret = CONSUMER_SECRET;
		$oauth_key = $this->urlencode_rfc3986($consumer_secret) . '&';
		$time = time();
		$nonce = md5(microtime() . mt_rand());
		$callback_url = '';
		$params = array('oauth_consumer_key' => $consumer_key,
						'oauth_signature_method' => 'HMAC-SHA1',
						'oauth_timestamp' => $time,
						'oauth_nonce' => $nonce,
						'oauth_version' => '1.0');             
		uksort($params, 'strcmp');

		$oauth_header = 'Authorization: OAuth ';

		$raw_signature = 'GET&' . $this->urlencode_rfc3986($request_url) . '&';
		foreach($params as $key => $param)
		{
			$raw_signature .= $key . '=' . urlencode($param) . '&';
			$oauth_header  .= $key . '=' . $param . ',';
		}

		$raw_signature = rtrim($raw_signature, '&');
		$oauth_signature = base64_encode(hash_hmac('sha1', $raw_signature, $oauth_key));
		$oauth_header .= 'oauth_signature=' . $oauth_signature;


		$raw_signature .= $this->urlencode_rfc3986($raw_signature);
		$oauth_signature = $this->urlencode_rfc3986($oauth_signature);

		$params['oauth_signature'] = $oauth_signature;
		uksort($params, 'strcmp');
		$session = curl_init($request_url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_HEADER, true);
		curl_setopt($session, CURLOPT_HTTPHEADER, array($oauth_header));
		$response = curl_exec($session);
		curl_close($session);
		echo $response;
		die;
	}*/
	
	function urlencode_rfc3986($input)
{
    return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
}
}