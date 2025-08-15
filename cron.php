<?php
/*LAUNCH THIS CRON EVERY 10 MIN IF VERY HIGH VOLUME*/

// liester utilisateurs acitf ['email','un ique id']
// lister monreo where label account = unique id
//lister bitcoin where adresses label = unique id


// get deposit address of correpspondants sub accounts , each currency, add to array 
//['email','un ique id', 'currency', 'adress deposit']
// send to correspondant deposit address
// get tx id, if posible

//once recceived is good, open form currency to eur 
// transfer sub account eur to customer iban


ini_set('display_errors',1);
require('functions.php');


function GetAccountsMonero($label) {
  global $config__MoneroWalletAddress;
  global $config__MoneroAuthRpc;
  if (monero_init() === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"id\":\"0\",\"method\":\"get_accounts\",\"params\":{\"tag\":\"".$label."\"}}",
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
	return $result;
	//var_dump($result);
  } else { return false; /* echo $result->error */ }
  endif;
  curl_close($ch);
  else:
  return false;
  endif;
  
}

function SendMonero($address_to,$amount,int $account) { 
  global $config__MoneroWalletAddress;
  global $config__MoneroAuthRpc;
  if (monero_init() === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => '{"jsonrpc":"2.0","id":"0","method":"transfer","params":{"destinations":[{"amount":'.$amount*0.000000000001.',"address":"'.$address_to.'"}],"account_index":'.$account.',"subaddr_indices":[0],"priority":2,"get_tx_key": true,"get_tx_hex":true}}',
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
	return $result;
	//var_dump($result);
  } else { return false; /* echo $result->error */ }
  endif;
  curl_close($ch);
  else: return false;endif;
  
}

function GetAmountMonero(int $account_index) { 
  global $config__MoneroWalletAddress;
  global $config__MoneroAuthRpc;
  if (monero_init() === true):
  $ch = curl_init();
  curl_setopt_array($ch, [
	CURLOPT_URL => $config__MoneroWalletAddress,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => '{"jsonrpc":"2.0","id":"0","method":"get_balance","params":{"account_index":'.$account_index.'}}',
	CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
	CURLOPT_USERPWD => $config__MoneroAuthRpc]);
  
  $result = json_decode(curl_exec($ch));
  if (curl_errno($ch)):return false; //echo 'Error:' . curl_error($ch);
  else:
  if (!isset($result->error)) {
	return $result;
	var_dump($result); //atomic unit?
  } else { return false; /* echo $result->error */ }
  endif;
  curl_close($ch);
  else:  return false;  endif;
  
}


function GetAddressesBitcoin($label) {
  if (bitcoin_init() === true) {
	global $config__BitcoinNodeAddress,$config__BitcoinNodeAuth;  
	$ch = curl_init();
	curl_setopt_array($ch,[
	  CURLOPT_URL => $config__BitcoinNodeAddress,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => 1,
	  CURLOPT_POSTFIELDS => '{"jsonrpc": "1.0", "id": "curltest", "method": "getaddressesbylabel", "params": ["'.$label.'"]}',
	  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	  CURLOPT_CONNECTTIMEOUT => 5,
	  CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	  CURLOPT_USERPWD => $config__BitcoinNodeAuth,
	]);
	$result = json_decode(curl_exec($ch), true);
	if (curl_errno($ch)) { return false;} 
	else {
	  if (isset($result['result']) && !empty($result['result']) && $result['error'] === null) {return $result;} else {return false; }
	}
	curl_close($ch);
	
  } else {return false;}
  
}

function SendBitcoin(array $addresses_amounts ) {
  if (bitcoin_init() === true) {
	global $config__BitcoinNodeAddress,$config__BitcoinNodeAuth;  
	$ch = curl_init();
	curl_setopt_array($ch,[
	  CURLOPT_URL => $config__BitcoinNodeAddress,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => 1,
	  CURLOPT_POSTFIELDS => '{"jsonrpc": "1.0", "id": "curltest", "method": "sendmany", "params": ["",'.json_encode($addresses_amounts).'   ]}',
	  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
	  CURLOPT_CONNECTTIMEOUT => 5,
	  CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
	  CURLOPT_USERPWD => $config__BitcoinNodeAuth,
	]);
	$result = json_decode(curl_exec($ch), true);
	if (curl_errno($ch)) { return false;} 
	else {
	  if (isset($result['result']) && !empty($result['result']) && $result['error'] === null) {return $result;} else {return false; }
	}
	curl_close($ch);
	
  } else {return false;}
  
}




$db = bdd_connect();
//$req = $db->prepare('SELECT * FROM users WHERE status=:status');
$req = $db->prepare('SELECT * FROM users');
//$req->bindValue(':status', true);
$req->execute(); 
$res = $req->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $k => $v) {
$users[] = ['email'=>$v['email'],'uniqueid'=>$v['uniqueid'],'monero_account_index'=>$v['monero_base_index']];
}

foreach($users as $u){
	//optional
  // monero
  // send to correspondant deposit address
  //ValidateAddressMonero(); if good send 
// get tx id, if posible
  
  // SendMonero($address_to,GetAmountMonero($u['monero_account_index']),int $account);
  // write file result

  
  
  
  //bitcoin
  // get all adresses for tis account with label ( label == uniqueid in bdd)
  // for each adresses, sned to deposit addrres kraken or other...
  //GetAddressesBitcoin($label)
  
}






