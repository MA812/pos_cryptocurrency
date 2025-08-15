<?php
function bdd_connect(){
  try {
	// CLOUD SERVER
	$bdd = new PDO('mysql:host=bdd_info_here', 'user', 'passt');
	$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (Exception $e) {die('Erreur(s) : ' . $e->getMessage());}
  return $bdd;
}


$config__MoneroWalletAddress = '';
$config__MoneroNodeAddress = '';
// monero wallet auth
$config__MoneroAuthRpc = '';
// monero node auth
$config__MoneroNodeAuth = '';

# bitcoin node
$config__BitcoinNodeAddress = '';
# bitcoin auth
$config__BitcoinNodeAuth = '';

# Monero
function monero_init() {
  global $config__MoneroWalletAddress;
  global $config__MoneroNodeAddress;
  global $config__MoneroAuthRpc;
  global $config__MoneroNodeAuth;
  //global  $config__MoneroWalletAddress,$config__MoneroNodeAddress,$config__MoneroAuthRpc;
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroNodeAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"get_info\"}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json','Authorization: Basic '. base64_encode($config__MoneroNodeAuth)],
	CURLOPT_CONNECTTIMEOUT => 7,
	CURLOPT_FOLLOWLOCATION => true
  ]);
  
  $result = json_decode(curl_exec($ch));
  if (curl_errno($ch)) {
	// echo 'Error:' . curl_error($ch);
	// echo curl_strerror(curl_errno($ch));
	return false;
  }
  curl_close($ch);
  if (isset($result) && $result != null && $result != false && !isset($result->error) && $result->result->mainnet === true && $result->result->status === 'OK' &&  $result->result->offline === false):
  $daemon_ok = true; // daemon ok 
  else:
  $daemon_ok = false;
  endif;
  
  if (isset($daemon_ok) && $daemon_ok === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"get_version\"}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc
  ]);
  
  $result1 = json_decode(curl_exec($ch));
  if (curl_errno($ch)) {
	echo 'Error:' . curl_error($ch). ' '.curl_errno($ch);
	// return 'wallet not ok ';
	return false;
  }
  curl_close($ch);
  
  if ($result1 != null && $result1 != false && !isset($result1->error) && isset($result1->result->version)):
  return true;
  else:
  return false;
  endif;
  
  else:
  return false;
  endif;
}


function NewAddressMonero($label,$account) {
  global $config__MoneroWalletAddress;
  global $config__MoneroAuthRpc;
  if (monero_init() === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"create_address\",\"params\":{\"account_index\":".$account.",\"label\":\"".$label."\"}}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc]);
  
  $result = json_decode(curl_exec($ch));
  if (curl_errno($ch)):
  return false;
  //echo 'Error:' . curl_error($ch);
  else:
  if (!isset($result->error)) {
	return $result->result->address;
  } else { return false; /* echo $result->error */ }
  endif;
  curl_close($ch);
  else:
  return false;
  endif;
  
}

function GetBalanceMonero($address) {
  if (monero_init() === true):
  global  $config__MoneroWalletAddress,$config__MoneroAuthRpc;
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"get_address_index\",\"params\":{\"address\":\"".$address."\"}}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc]);
  
  $result = json_decode(curl_exec($ch));
  if (curl_errno($ch)) {
	// echo 'Error:' . curl_error($ch);
	return false;
  } else {
	if (!isset($result->error)) {
	  $account_number = $result->result->index->major;
	  $address_number = $result->result->index->minor;
	} else {return false;}
  }
  curl_close($ch);
  
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"get_balance\",\"params\":{\"account_index\":".$account_number.",\"address_indices\":[".$address_number."]}}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc]);
  
  $result1 = json_decode(curl_exec($ch), true);
  if (curl_errno($ch)) {
	//echo 'Error:' . curl_error($ch);
	return false;
  } else {
	if (!isset($result1->error)) {
	  return $result1['result']['per_subaddress']['0']['balance'];
	} else {return false;}
  }
  curl_close($ch);
  else:
  return false;
  endif;
}

function CreateAccountMonero($label) {
  global $config__MoneroWalletAddress;
  global $config__MoneroAuthRpc;
  if (monero_init() === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"create_account\",\"params\":{\"label\":\"".$label."\"}}",
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc]);
  
  $result = json_decode(curl_exec($ch));
  if (curl_errno($ch)): echo 'Error:' . curl_error($ch); //return false;
  else:
  curl_close($ch);
  if (!isset($result->error)) {
	curl_setopt_array($ch, [
		CURLOPT_URL => $config__MoneroWalletAddress,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"tag_accounts\",\"params\":{\"tag\":\"".$label."\",\"accounts\":[".$result->result->account_index."]}}",
		CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
		CURLOPT_USERPWD => $config__MoneroAuthRpc]);
       		$result1 = json_decode(curl_exec($ch));
  		if (curl_errno($ch)): echo 'Error:' . curl_error($ch); 
		else: if(!isset($result1->error)): return ['id'=>$result->result->account_index,'address'=>$result->result->address]; else: return false; endif;
		endif;
  } else { return false; /* echo $result->error */ }
  endif; // end first curl errno
  curl_close($ch);
  else: return false; endif; // end monero init
  
}


function CreateLabel() {
  $db = bdd_connect();
  $req = $db->prepare('SELECT * FROM transactions order by ID desc limit 1');
  $req->execute();
  $res = $req->fetch(PDO::FETCH_ASSOC);
  
  if (empty($res['label']) OR $res['label'] === NULL) {
	return 1;
  } else {
	return $res['label']+1;
  }
  
}


function EURtoXMR($amount_eur){ 
  // coinlayer, update all 5 minutes
  $db = bdd_connect();
  $req = $db->prepare('SELECT * FROM info WHERE currency = :currency');
  $req->bindValue(':currency','monero');
  $req->execute();
  $res = $req->fetch(PDO::FETCH_ASSOC);
  
  $url = 'https://api.coinlayer.com/api/live?access_key=xxx&target=EUR&symbols=XMR';
  
  if (empty($res['price']) OR $res['price'] === NULL OR $res['price'] === 0):
  
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_RETURNTRANSFER=>1,
	CURLOPT_URL=>$url,
	CURLOPT_FOLLOWLOCATION=>true,
	CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
	CURLOPT_HTTPHEADER=>['Content-Type: application/json']
  ]);
  $contents = json_decode(curl_exec( $ch ),true);
  
  if($contents != null && $contents != false && isset($contents['success']) && $contents['success'] === true):
  
  $db = bdd_connect(); 
  $req = $db->prepare('DELETE FROM info WHERE currency = :currency');
  $req->bindValue(':currency','monero');
  $req->execute();
  $req->closeCursor();
  
  
  $req = $db->prepare('INSERT INTO info (price, timestamp, currency) VALUES (:price, :timestamp, :currency) ');
  $req->bindParam(':price',$contents['rates']['XMR']);
  $req->bindValue(':timestamp',time());
  $req->bindValue(':currency','monero');
  $res = $req->execute();
  //$res = $req->fetch(PDO::FETCH_ASSOC);
  
  if (isset($res) && $res != null && $res != false):
  return (1*$amount_eur)/(float)$contents['rates']['XMR'];
  else : return false; endif;
  
  
  else: return false;
  endif;
  
  else:
  if (time() > strtotime('+5 minutes',$res['timestamp']) ) {
	
	$ch = curl_init();
	curl_setopt_array($ch, [
	  CURLOPT_RETURNTRANSFER=>1,
	  CURLOPT_URL=>$url,
	  CURLOPT_FOLLOWLOCATION=>true,
	  CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
	  CURLOPT_HTTPHEADER=>['Content-Type: application/json']
	]);
	
	
	$contents = json_decode(curl_exec( $ch ),true);
	if($contents != null && $contents != false && !isset($contents['error'])):
	$db = bdd_connect();
	$req = $db->prepare('UPDATE info SET price = :price AND timestamp = :timestamp WHERE currency = :currency');
	$req->bindParam(':price',$contents['rates']['XMR']);
	$req->bindValue(':timestamp',time());
	$req->bindValue(':currency','monero');
	$res = $req->execute();
	
	if (isset($res) && $res != null && $res != false):
	return (1*$amount_eur)/(float)$contents['rates']['XMR'];
	else : return false; endif;
	
	else: return false; endif;
	
  } else {
	return (1*$amount_eur)/(float)$res['price'];
  }
  endif;
  curl_close( $ch );
}


function EURtoBTC( $amount_eur){ 
  // coinlayer, update all 5 minutes
  $db = bdd_connect();
  $req = $db->prepare('SELECT * FROM info WHERE currency = :currency');
  $req->bindValue(':currency','bitcoin');
  $req->execute();
  $res = $req->fetch(PDO::FETCH_ASSOC);
  
  $url = 'https://api.coinlayer.com/api/live?access_key=xxx&target=EUR&symbols=BTC';
  
  if (empty($res['price']) OR $res['price'] === NULL OR $res['price'] === 0):
  
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_RETURNTRANSFER=>1,
	CURLOPT_URL=>$url,
	CURLOPT_FOLLOWLOCATION=>true,
	CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
	CURLOPT_HTTPHEADER=>['Content-Type: application/json']
  ]);
  $contents = json_decode(curl_exec( $ch ),true);
  
  if($contents != null && $contents != false && isset($contents['success']) && $contents['success'] === true):
  
  $db = bdd_connect(); 
  $req = $db->prepare('DELETE FROM info WHERE currency = :currency');
  $req->bindValue(':currency','bitcoin');
  $req->execute();
  $req->closeCursor();
  
  
  $req = $db->prepare('INSERT INTO info (price, timestamp, currency) VALUES (:price, :timestamp, :currency) ');
  $req->bindParam(':price',$contents['rates']['BTC']);
  $req->bindValue(':timestamp',time());
  $req->bindValue(':currency','bitcoin');
  $res = $req->execute();
  //$res = $req->fetch(PDO::FETCH_ASSOC);
  
  if (isset($res) && $res != null && $res != false):
  return (1*$amount_eur)/(float)$contents['rates']['BTC'];
  else : return false; endif;
  
  
  else: return false;
  endif;
  
  else:
  if (time() > strtotime('+5 minutes',$res['timestamp']) ) {
	
	$ch = curl_init();
	curl_setopt_array($ch, [
	  CURLOPT_RETURNTRANSFER=>1,
	  CURLOPT_URL=>$url,
	  CURLOPT_FOLLOWLOCATION=>true,
	  CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
	  CURLOPT_HTTPHEADER=>['Content-Type: application/json']
	]);
	
	
	$contents = json_decode(curl_exec( $ch ),true);
	if($contents != null && $contents != false && !isset($contents['error'])):
	$db = bdd_connect();
	$req = $db->prepare('UPDATE info SET price = :price AND timestamp = :timestamp WHERE currency = :currency');
	$req->bindParam(':price',$contents['rates']['BTC']);
	$req->bindValue(':timestamp',time());
	$req->bindValue(':currency','bitcoin');
	$res = $req->execute();
	
	if (isset($res) && $res != null && $res != false):
	return (1*$amount_eur)/(float)$contents['rates']['BTC'];
	else : return false; endif;
	
	else: return false; endif;
	
  } else {
	return (1*$amount_eur)/(float)$res['price'];
  }
  endif;
  curl_close( $ch );
}



function bitcoin_init() {
  global $config__BitcoinNodeAddress,$config__BitcoinNodeAuth;  
  $ch = curl_init();
  curl_setopt_array($ch,[
	CURLOPT_URL => $config__BitcoinNodeAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => '{"jsonrpc": "1.0", "id": "curltest", "method": "getnetworkinfo", "params": []}',
	CURLOPT_HTTPHEADER => ['Content-Type: applicaiton/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	CURLOPT_USERPWD => $config__BitcoinNodeAuth,
  ]);
  $result = json_decode(curl_exec($ch), true);
  //echo 'Error:' . curl_error($ch);
  if (curl_errno($ch)) { return false; } else {
	if (isset($result['result']['networkactive'],$result['result']['warnings']) && $result['result']['networkactive'] == true && empty($result['result']['warnings']) && $result['error'] === null) {return true;} else {return false; }
  }
  curl_close($ch);
  
}


function NewAddressBitcoin($label) {
  if (bitcoin_init() === true) {
	global $config__BitcoinNodeAddress,$config__BitcoinNodeAuth;  
	$ch = curl_init();
	curl_setopt_array($ch,[
	  CURLOPT_URL => $config__BitcoinNodeAddress,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => 1,
	  CURLOPT_POSTFIELDS => '{"jsonrpc": "1.0", "id": "curltest", "method": "getnewaddress", "params": ["'.$label.'"]}',
	  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	  CURLOPT_CONNECTTIMEOUT => 5,
	  CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	  CURLOPT_USERPWD => $config__BitcoinNodeAuth,
	]);
	$result = json_decode(curl_exec($ch), true);
	var_dump($result);
	if (curl_errno($ch)) { return false;} 
	else {
	  if (isset($result['result']) && !empty($result['result']) && $result['error'] === null) {return $result['result'];} else {return false; }
	}
	curl_close($ch);
	
  } else {return false;}
  
}


function GetBalanceBitcoin($address) {
  if (bitcoin_init() === true) {
	global $config__BitcoinNodeAddress,$config__BitcoinNodeAuth;  
	$ch = curl_init();
	curl_setopt_array($ch,[
	  CURLOPT_URL => $config__BitcoinNodeAddress,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => 1,
	  CURLOPT_POSTFIELDS => '{"jsonrpc": "1.0", "id": "curltest", "method": "getreceivedbyaddress", "params": ["'.$address.'", 0]}',
	  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	  CURLOPT_CONNECTTIMEOUT => 5,
	  CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	  CURLOPT_USERPWD => $config__BitcoinNodeAuth,
	]);
	$result = json_decode(curl_exec($ch), true);
	if (curl_errno($ch)) { return false;} 
	else {
	  if (isset($result['result']) && !empty($result['result']) && $result['error'] === null) {return $result['result'];} else {return false; }
	}
	curl_close($ch);
	
  } else {return false;}
  
}

