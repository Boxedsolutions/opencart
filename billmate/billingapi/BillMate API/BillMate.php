<?php
/**
 * BillMate
 *
 * This API provides a way to integrate with Billmate's services over the
 * XMLRPC protocol.
 *
 * LICENSE: This source file is part of BillMate, that is fully owned by Combain Mobile AB
 * This is not open source. For licensing queries, please contact at info@combain.com.
 *
 * @category MEXc
 * @package MEXc
 * @author Yuksel Findik <yuksel@combain.com>
 * @copyright 2007-2013 Combain Mobile AB
 * @license Proprietary and fully owned by Combain Mobile AB
 * @version 1.0
 * @link http://www.combain.com
 *
 * History:
 * 1.0 20130318 Yuksel Findik: First Version
 *
 * Dependencies:
 *
 *  xmlrpc-3.0.0.beta/lib/xmlrpc.inc
 *      from {@link http://phpxmlrpc.sourceforge.net/}
 *
 * xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc
 *      from {@link http://phpxmlrpc.sourceforge.net/}
 *
 */
 class BillMate{
 	var $SERVER = "1.0";
 	var $CLIENT = "PHP:Billmate:0.1";
 	var $URL = "api.billmate.se";
 	
 	protected $ssl;
 	protected $xmlrpc;
 	protected $debugmode;
 	function BillMate($eid,$key,$ssl=true,$debug=false){
 		$this->encoding = 2;
 		$this->eid = $eid;
 		$this->key = $key;
 		$this->ssl = $ssl;
 		$this->debugmode = $debug;
 		if ($this->ssl) $this->port = 443;
 		else $this->port = 80;
 		$this->xmlrpc = new xmlrpc_client("/",$this->URL,$this->port,$this->ssl?'https':'http');
        $this->xmlrpc->request_charset_encoding = 'ISO-8859-1';
 	}
 	function ActivateInvoice($no,$additionalInfo=array("pclass"=>-1,"shipInfo"=>array())) {
		$params = array(
            $this->eid,
            $no,
            $this->hash(array($this->eid, $no, $this->key)),
            $additionalInfo["pclass"],
            $additionalInfo["shipInfo"]
        );
        $result = $this->call('activate_invoice', $params);
        return $result;
 	}
 	function ActivateReservation($reservationno,$pno,$billing,$shipping,$articles,$additionalinfo){
 		$tobehashed = array($this->eid,$pno);
 		
 		foreach ($articles as $article){
 			$tobehashed[] = $article['goods']['artno'];
 			$tobehashed[] = $article["qty"];
 		}
 		$tobehashed[] = $this->key;
 		
 		$params = array(
 			$reservationno,
 			$additionalinfo["ocr"],
 		    $pno,
 		    $additionalinfo["gender"],
 		    $additionalinfo["reference"],
 		    $additionalinfo["reference_code"],
 		    $additionalinfo["order1"],
 		    $additionalinfo["order1"],
 		    $shipping,
 		    $billing,
 		    $this->IP(),
 		    $additionalinfo["flags"],
 		    $additionalinfo["currency"],
 		    $additionalinfo["country"],
 		    $additionalinfo["language"],
 		    $this->eid,
 		    $this->hash($tobehashed),
 		    $this->encoding,
 		    $additionalinfo["pclass"],
 		    $articles,
 		    $additionalinfo["comment"],
 		    $additionalinfo["shipInfo"],
 		    $additionalinfo["travelInfo"],
 		    $additionalinfo["incomeInfo"],
 		    $additionalinfo["bankInfo"],
 		    $additionalinfo["extraInfo"]
 		);
 		$result = $this->call('activate_reservation', $params);
 		return $result;
 	}
 	    
 	function AddInvoice($pno,$billing,$shipping,$articles,$additionalinfo){
 		$tobehashed = array();
 		foreach ($articles as $article) $tobehashed[] = $article['goods']['title'];
 		$tobehashed[] = $this->key;
 		$params = array(
 		    $pno,
 		    $additionalinfo["gender"],
 		    $additionalinfo["reference"],
 		    $additionalinfo["reference_code"],
 		    $additionalinfo["order1"],
 		    $additionalinfo["order1"],
 		    $shipping,
 		    $billing,
 		    $this->IP(),
 		    $additionalinfo["flags"],
 		    $additionalinfo["currency"],
 		    $additionalinfo["country"],
 		    $additionalinfo["language"],
 		    $this->eid,
 		    $this->hash($tobehashed),
 		    $this->encoding,
 		    $additionalinfo["pclass"],
 		    $articles,
 		    $additionalinfo["comment"],
 		    $additionalinfo["shipInfo"],
 		    $additionalinfo["travelInfo"],
 		    $additionalinfo["incomeInfo"],
 		    $additionalinfo["bankInfo"],
 		    $additionalinfo["sid"],
 		    $additionalinfo["extraInfo"]
 		);

		$result = $this->call('add_invoice', $params);
 		return $result;
 	}
 	function GetAddress($pno) {
 		$type = 5;
 	    $params = array($pno,$this->eid,$this->hash(array($this->eid, $pno, $this->key)),$this->encoding,$type,$this->IP());
        $result = $this->call('get_addresses', $params);
        return $result;
    }
    function CreditCheck($pno,$amount,$email,$phonenumber=""){
    	$params = array($pno,$this->eid,$this->hash(array($this->eid, $pno,$amount, $this->key)),$amount,$email,$phonenumber,$this->IP());
    	$result = $this->call('credit_check', $params);
    	return $result;
    }
    function FetchCampaigns($additionalinfo = array()) {
    	$params = array($this->eid,$additionalinfo["currency"],$this->hash(array($this->eid, $additionalinfo["currency"], $this->key)),$additionalinfo["country"],$additionalinfo["language"]);

    	$result = $this->call('get_pclasses', $params);
    	return $result;
    }
    
    function hash($args) {
    print_r($args);
    	$data = implode(":", $args);
    	
    	$this->debug("HASHED DATA",$data);
    	
    	$preferred = array(
            'sha512',
            'sha384',
            'sha256',
            'sha224',
            'md5'
        );

        $hashes = array_intersect($preferred, hash_algos());

        if (count($hashes) == 0) {
            throw new Exception(
                "No available hash algorithm supported!"
            );
        }
        $hash = array_shift($hashes);
    	return base64_encode(pack("H*", hash($hash, $data)));
    }
    function debug($name,$out) {
    	if (!$this->debugmode) return;
    	print "Name:$name Output:\n<br/>";
    	if(is_array($out) or  is_object($out)) print_r($out);
    	else print $out;
    	print "\n<br/>";
    }
    protected function call($method, $array) {
    	$this->debug($method,$array);
		$this->xmlrpc->verifypeer = false;
        $timestart = microtime(true);

        $msg = new xmlrpcmsg($method);
        $params = array_merge(array($this->SERVER, $this->CLIENT), $array);

        $msg = new xmlrpcmsg($method);
        foreach ($params as $p) $msg->addParam(php_xmlrpc_encode($p, array('extension_api')));
            
        $selectDateTime = microtime(true);
        if ($this->debugmode) $this->xmlrpc->setDebug(2);
           
        $xmlrpcresp = $this->xmlrpc->send($msg);
        //Calculate time and selectTime.
        $timeend = microtime(true);
        $time = (int) (($selectDateTime - $timestart) * 1000);
        $selectTime = (int) (($timeend - $timestart) * 1000);
        $status = $xmlrpcresp->faultCode();
        
        if ($status !== 0) print "Error: ".$xmlrpcresp->faultString()." Status:$status";
        $result = php_xmlrpc_decode($xmlrpcresp->value());
        $this->debug($method, $result);
        return $result;
        
    }
    function IP()
    {
    	global $REMOTE_ADDR, $HTTP_CLIENT_IP;
    	global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
    	global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
    
    	// Get some server/environment variables values
    	if (empty($REMOTE_ADDR)) {
    		if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
    			$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
    			$REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
    		}
    		else if (@getenv('REMOTE_ADDR')) {
    			$REMOTE_ADDR = getenv('REMOTE_ADDR');
    		}
    	} // end if
    
    	if (empty($HTTP_CLIENT_IP)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_CLIENT_IP'])) {
    			$HTTP_CLIENT_IP = $_SERVER['HTTP_CLIENT_IP'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_CLIENT_IP'])) {
    			$HTTP_CLIENT_IP = $_ENV['HTTP_CLIENT_IP'];
    		}
    		else if (@getenv('HTTP_CLIENT_IP')) {
    			$HTTP_CLIENT_IP = getenv('HTTP_CLIENT_IP');
    		}
    	} // end if
    
    	if (empty($HTTP_X_FORWARDED_FOR)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    			$HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
    			$HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
    		}
    		else if (@getenv('HTTP_X_FORWARDED_FOR')) {
    			$HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
    		}
    	} // end if
    
    	if (empty($HTTP_X_FORWARDED)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
    			$HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
    			$HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
    		}
    		else if (@getenv('HTTP_X_FORWARDED')) {
    			$HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
    		}
    	} // end if
    
    	if (empty($HTTP_FORWARDED_FOR)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    			$HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
    			$HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
    		}
    		else if (@getenv('HTTP_FORWARDED_FOR')) {
    			$HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
    		}
    	} // end if
    
    	if (empty($HTTP_FORWARDED)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
    			$HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
    			$HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
    		}
    		else if (@getenv('HTTP_FORWARDED')) {
    			$HTTP_FORWARDED = getenv('HTTP_FORWARDED');
    		}
    	} // end if
    
    	if (empty($HTTP_VIA)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
    			$HTTP_VIA = $_SERVER['HTTP_VIA'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
    			$HTTP_VIA = $_ENV['HTTP_VIA'];
    		}
    		else if (@getenv('HTTP_VIA')) {
    			$HTTP_VIA = getenv('HTTP_VIA');
    		}
    	} // end if
    	if (empty($HTTP_X_COMING_FROM)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
    			$HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
    			$HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
    		}
    		else if (@getenv('HTTP_X_COMING_FROM')) {
    			$HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
    		}
    	} // end if
    	if (empty($HTTP_COMING_FROM)) {
    		if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
    			$HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
    		}
    		else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
    			$HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
    		}
    		else if (@getenv('HTTP_COMING_FROM')) {
    			$HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
    		}
    	} // end if
    
    	// Gets the default ip sent by the user
    	if (!empty($REMOTE_ADDR)) {
    		$direct_ip = $REMOTE_ADDR;
    	}
    
    	// Gets the proxy ip sent by the user
    	$proxy_ip = '';
    	if (!empty($HTTP_X_FORWARDED_FOR)) {
    		$proxy_ip = $HTTP_X_FORWARDED_FOR;
    	} else if (!empty($HTTP_X_FORWARDED)) {
    		$proxy_ip = $HTTP_X_FORWARDED;
    	} else if (!empty($HTTP_FORWARDED_FOR)) {
    		$proxy_ip = $HTTP_FORWARDED_FOR;
    	} else if (!empty($HTTP_FORWARDED)) {
    		$proxy_ip = $HTTP_FORWARDED;
    	} else if (!empty($HTTP_VIA)) {
    		$proxy_ip = $HTTP_VIA;
    	} else if (!empty($HTTP_X_COMING_FROM)) {
    		$proxy_ip = $HTTP_X_COMING_FROM;
    	} else if (!empty($HTTP_COMING_FROM)) {
    		$proxy_ip = $HTTP_COMING_FROM;
    	} // end if... else if...
    
    	// Returns the true IP if it has been found, else ...
    	if (empty($proxy_ip)) {
    		// True IP without proxy
    		return $direct_ip;
    	} else {
    		$is_ip = ereg('^([0-9]{1,3}.){3,3}[0-9]{1,3}', $proxy_ip, $regs);
    
    		if ($is_ip && (count($regs) > 0)) {
    			// True IP behind a proxy
    			return $regs[0];
    		} else {
    
    			if (empty($HTTP_CLIENT_IP)) {
    				// Can't define IP: there is a proxy but we don't have
    				// information about the true IP
    				return "(unbekannt) " . $proxy_ip;
    			} else {
    				// better than nothing
    				return $HTTP_CLIENT_IP;
    			}
    		}
    	} // end if... else...
    }
 }
 
?>
