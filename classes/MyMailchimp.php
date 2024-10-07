<?php
class MyMailchimp
{
	// config values
	protected $apiKey = '28afa7341b199fb7aacf383dd0027e4a-us3';
	protected $listId = 'e39b3faedd';
	protected $guideLink;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// pass parameters to ajax script
		add_action('my_localize_scripts', function() {
			global $wp_query;
			wp_localize_script( 'my-scripts', 'my_mailchimp_subscribe_params', array(
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
			) );
		}, 999);

		// handler for ajax call
		add_action('wp_ajax_my_mailchimp_subscribe', [$this, 'ajax_handler']);
		add_action('wp_ajax_nopriv_my_mailchimp_subscribe', [$this, 'ajax_handler']);

		// prepare guide link from ACF
		if(function_exists('get_field')) {
			$this->guideLink = get_field('download_file_url', 'option');
		}
	}

	/**
	 * Ajax handler for subscribe form
	 */
	function ajax_handler(){
  	    $data = [
	  	    'name' => $_POST['fields']['name'],
    		'email' => $_POST['fields']['email'],
    		'list' => $this->listId,
		    'status' => 'subscribed'
    	];

		$subscribe_status = $this->syncMailchimp($data);

		if($subscribe_status) {
			$res = [
				'status' => 'success',
				'msg' => ($this->guideLink ? 'Thank you for your interest, please <a href="' . $this->guideLink . '" target="_blank">download&nbsp;the&nbsp;guide&nbsp;here</a>.' : 'Thank you, you have been subscribed'),
			];
		} else {
			$res = [
				'status' => 'error',
				'msg' => 'There was an error subscribing you, please try again.',
			];
		}

		echo json_encode($res);
		exit;
	}


	/**
	 * Push changes to Mailchimp
	 */
	function syncMailchimp($data) {
		$url_action = 'lists/' . $data['list'] . '/members/' . md5(strtolower($data['email']));

		$params = [
			'email_address' => $data['email'],
			'name' 			=> $data['name'],
			'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
		];

		$res = $this->curlConnect( $url_action, 'PUT', $params );
		return ($res['http_code'] == 200);
	}


	/**
	 * cURL call
	 */
	protected function curlConnect( $url_action, $request_type, $data = array() ) {
		$dataCenter = substr($this->apiKey,strpos($this->apiKey,'-')+1);
		$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/' . $url_action;

		if( $request_type == 'GET' ) {
			$url .= '?' . http_build_query($data);
		}


		$mch = curl_init();
		$headers = [
			'Content-Type: application/json',
			'Authorization: Basic '.base64_encode( 'user:'. $this->apiKey ),
		];

		curl_setopt($mch, CURLOPT_URL, $url );
		curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
		curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
		curl_setopt($mch, CURLOPT_TIMEOUT, 10);
		curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

		if( $request_type != 'GET' ) {
			curl_setopt($mch, CURLOPT_POST, true);
			curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
		}

		$result = curl_exec($mch);
		$httpCode = curl_getinfo($mch, CURLINFO_HTTP_CODE);
		curl_close($mch);

		return [
			'result' => $result,
			'http_code' => $httpCode,
		];
	}

}
