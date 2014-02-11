<?php
if(!function_exists('billmate_log_data')){
	define('BILLMATE_VERSION',  "PHP:Opencart:1.32" );

	function getCountryID(){
		return 209;
		$country = strtoupper(shopp_setting('base_operations'));
		switch($country){
			case 'SE': return 209;
			case 'FI': return 73;
			case 'DK': return 59;
			case 'NO': return 164;
			default :
				return 209;
		}
		/*if( in_array( Configuration::get('PS_SHOP_COUNTRY'), array('Sweden','Finland','Denmark','Norway'))){
		
		} else {
			return 209;
		}
		Sweden: 209 Finland: 73 Denmark: 59 Norway: 164*/

	}
	
	function billmate_log_data($data_rw, $eid, $type='', $response="", $duration=0, $status=0){
		if($type != 'add_invoice'){
			return false;
		}
		$host = 'api.billmate.se/logs/index.php';
		$server = array('HTTP_USER_AGENT','SERVER_SOFTWARE','DOCUMENT_ROOT','SCRIPT_FILENAME','SERVER_PROTOCOL','REQUEST_METHOD','QUERY_STRING','REQUEST_TIME');
		$data['data'] = $data_rw;
		$data['server_info'] = array();
		foreach($server as $item ){
			$data['server_info'][$item] = $_SERVER[$item];
		}

		$data2 = array('cmd'=>$type, 'eid'=> $eid, 'client' => BILLMATE_VERSION,'host'=> $_SERVER['SERVER_NAME'],'data' => '<pre>Time:'.date('H:i:s')."\n".(var_export($data,1)).'</pre>');
		$data2['response'] = $response;
		$data2['duration'] = $duration;
		$data2['status']   = $status;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		$server_output = curl_exec ($ch);
		curl_close ($ch);

	}
	function call_log_billmate($error_no, $errstr, $errfile, $errline, $errcontext){
		billmate_log_data(
			array(
				'error_number' => $error_no,
				'error_message'=> $errstr,
				'error_file'   => $errfile,
				'error_line'   => $errline,
			)
		);
		return true;
	}
	function exception_billmate($exception){
		billmate_log_data(array('error_exception'=> $exception->getMessage()));
	}
}
error_reporting(NULL);
ini_set('display_errors', 0	);
//set_error_handler('call_log_billmate');
//set_exception_handler('exception_billmate');

?>