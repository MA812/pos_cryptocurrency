<?php
require ('functions.php');


// Handling data in JSON format on the server-side using PHP
header("Content-Type: application/json");
// build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));


switch ($v->message) :
	case(9):
		if (isset($v->id,$v->email,$v->subject,$v->message) && !empty($v->id) && !empty($v->email) && !empty($v->subject) && !empty($v->message) && $v->email == 6 && $v->subject == 4):
		$db = bdd_connect();
  		$req = $db->prepare('SELECT * FROM transactions WHERE id = :id');
		$req->bindParam('id', $v->id);
  		$req->execute();
$res = $req->fetch(PDO::FETCH_ASSOC); if (empty($res) || $res===null) {echo 0; die;}
		switch ($res['currency']):
			case('monero'):
				$s=['tx_amount'=>number_format(EURtoXMR($res['amount']),12),
					'tx_description'=>date("m/Y_").'Exemple_text',];
				$res['img'] = 'monero:'.$res['address'].'?'.http_build_query($s,'','&');
			break;
			case('bitcoin'):
				$s =['amount'=>number_format(EURtoBTC($res['amount']),8),
					'label'=>date("m/Y_").'Exemple_text',
					'message'=>$res['label'].'_'.date("m/Y_").'Exemple_text'];
				$res['img'] = 'bitcoin:'.$res['address'].'?'.http_build_query($s,'','&');
			break;
		endswitch;
  		echo json_encode($res);
		else: echo 0; endif;
	break;

	case(8):
		if(isset($v->id,$v->address,$v->crypto,$v->amount,$v->message) && !empty($v->id) && !empty($v->address) && !empty($v->crypto) && !empty($v->amount) && !empty($v->message) ):
			$db = bdd_connect();
  			$req = $db->prepare('SELECT * FROM transactions WHERE id = :id');
			$req->bindParam('id', $v->id);
  			$req->execute();
  			$res = $req->fetch(PDO::FETCH_ASSOC); if (empty($res) || $res===null) {echo 0; die;}
			if ($v->address == $res['address'] && $v->crypto == $res['currency'] && $v->amount == $res['amount']) {
				switch($v->crypto):
				case('monero'):
					$balance = GetBalanceMonero($v->address)*0.000000000001;
					$amount = $v->amount;

					if ($balance >= EURtoXMR($amount - ($amount*0.05)) ):
  					$db = bdd_connect();
  					$req = $db->prepare('UPDATE transactions SET status = :status WHERE id=:id AND currency=:currency AND address=:address AND amount=:amount ');
					#$req->bindValue(':status',1,PDO::PARAM_BOOL); // <= this method don' show if variable dont correct eg. $v->cccrpto, update query will execute without error
  					#$req->bindParam(':id',$v->id);
  					#$req->bindParam(':currency',$v->currency);
  					#$req->bindParam(':address',$v->address);
  					#$req->bindParam(':amount',$amount); 
					$res = $req->execute([':status'=>1,
					  					  ':id'=>$v->id,
					  					  ':currency'=>$v->crypto,
					  					  ':address'=>$v->address,
					  					  ':amount'=>$amount]);
					if(isset($res) && !empty($res) && $res === true){echo 1;} else {echo 0;}
					else: echo 0; endif;
				break;

				case('bitcoin'):
					$balance = number_format(GetBalanceBitcoin($v->address),8);
					$amount = $v->amount;

					if ($balance >= EURtoBTC($amount - ($amount*0.05)) ):
  					$db = bdd_connect();
  					$req = $db->prepare('UPDATE transactions SET status = :status WHERE id=:id AND currency=:currency AND address=:address AND amount=:amount ');
					#$req->bindValue(':status',1,PDO::PARAM_BOOL); // <= this method don' show if variable dont correct eg. $v->cccrpto, update query will execute without error
  					#$req->bindParam(':id',$v->id);
  					#$req->bindParam(':currency',$v->currency);
  					#$req->bindParam(':address',$v->address);
  					#$req->bindParam(':amount',$amount); 
					$res = $req->execute([':status'=>1,
					  					  ':id'=>$v->id,
					  					  ':currency'=>$v->crypto,
					  					  ':address'=>$v->address,
					  					  ':amount'=>$amount]);
					if(isset($res) && !empty($res) && $res === true){echo 1;} else {echo 0;}
					else: echo 0; endif;
				break;
				endswitch;
			} else {echo 0; /* end if all values are same */}			
		else: echo 0; endif;

	break;
endswitch;

 