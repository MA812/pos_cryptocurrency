<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
require('qr.php');
require('functions.php');
session_start();

$url = htmlentities($_SERVER['REQUEST_URI']); 
if (parse_url($url, PHP_URL_QUERY) == null || empty(parse_url($url, PHP_URL_QUERY))):exit('url incorrecte. Login ?');endif;
if (!isset($_SESSION['login'])):exit('Connectez vous pour acceder a cette page');endif;
if (parse_url($url, PHP_URL_QUERY) != $_SESSION['login']['uniqid']):exit('Connectez vous pour acceder a cette page');endif;

$db = bdd_connect();
$req = $db->prepare('SELECT * FROM users WHERE uniqueid = :uniqueid');
$req->bindValue(':uniqueid', parse_url($url, PHP_URL_QUERY));
$req->execute(); 
$res = $req->fetch(PDO::FETCH_ASSOC);
if ($res===null || empty($res)): die('url incorrecte. Login ?'); endif;

$db = bdd_connect();
$req = $db->prepare('SELECT * FROM transactions order by ID desc limit 25');
$req->execute(); 
$txs = $req->fetchAll(PDO::FETCH_ASSOC);


# TRAITEMENT 
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
if (isset($_POST)&& !empty($_POST)): 
if (isset($_POST['montant']) && !empty($_POST['montant']) && is_numeric($_POST['montant'])):
switch ($_POST['monnaie']):
case ('monero'):
if (monero_init()==true) {
  $amount = $_POST['montant'];
  $address = NewAddressMonero(time().'_'.$amount,$res['id_base_monero']);
  $label = CreateLabel();
  
  #$_SESSION['QR']['address'] = $address;
  #$_SESSION['QR']['amount'] = $amount;
  #$_SESSION['QR']['label'] = $label;
  
  $svg = 'monero:'.$address.'?tx_amount='.EURtoXMR($amount).'&tx_description='.date("m/Y_").'Exemple_text';
  
  $db = bdd_connect();
  $req = $db->prepare('INSERT INTO transactions (label,time,currency,status,address,tx_hash,amount) VALUES (:label, :time, :currency, :status, :address, :tx_hash, :amount)');
  $req->bindValue(':label',$label);
  $req->bindValue(':time',time());
  $req->bindValue(':currency',htmlentities($_POST['monnaie']));
  $req->bindValue(':status',0,PDO::PARAM_BOOL);
  $req->bindParam(':address',$address);
  $req->bindValue(':tx_hash',null); 
  $req->bindValue(':amount',$amount); 
  $res = $req->execute();
  if (isset($res) && !empty($res) && $res === true): header('Location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']); endif;
} else {$erreur = '1.Le service n\'est pas en ligne, merci de contacter l\'administrateur de cette page';}
break;

case('bitcoin'):
if (bitcoin_init()==true) {
  $amount = $_POST['montant'];
  $address = NewAddressBitcoin($res['uniqueid']);
  $label = CreateLabel();
  
  #$_SESSION['QR']['address'] = $address;
  #$_SESSION['QR']['amount'] = $amount;
  #$_SESSION['QR']['label'] = $label;
  
  $svg = 'bitcoin:'.$address.'?amount='.number_format(EURtoBTC($amount),8).'?label='.$label.'?message='.date("m/Y_").'Exemple_text';
  
  $db = bdd_connect();
  $req = $db->prepare('INSERT INTO transactions (label,time,currency,status,address,tx_hash,amount) VALUES (:label, :time, :currency, :status, :address, :tx_hash, :amount)');
  $req->bindValue(':label',$label);
  $req->bindValue(':time',time());
  $req->bindValue(':currency',htmlentities($_POST['monnaie']));
  $req->bindValue(':status',0,PDO::PARAM_BOOL);
  $req->bindParam(':address',$address);
  $req->bindValue(':tx_hash',null); 
  $req->bindValue(':amount',$amount); 
  $res = $req->execute();
  if (isset($res) && !empty($res) && $res === true): header('Location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']); endif;
} else {$erreur = '1.2Le service n\'est pas en ligne, merci de contacter l\'administrateur de cette page';}
break;
endswitch;
else: $erreur = '2.Veuillez indiquer un montant.'; endif;
endif;
endif;

/*if (isset($_SESSION['QR']) && !empty($_SESSION['QR'])) : ?>
	<blockquote>
	  <aside>
		<ul>
		  <li>Monnaie:Monero</li>
<li>Montant:<?= $_SESSION['QR']['amount']?> </li>
<li style="word-break: break-all;">Adresse:<?= $_SESSION['QR']['address']?> </li>
		  
		</ul>
	  </aside>
	  <p> 
<img src="qr.php?data=<?= $svg ?>" height="auto" weight="auto"/>	
	  </p>
	</blockquote>
<?php endif;?> */




# for modal
$i=0;
?>
<!DOCTYPE html>
<html>
  <head>
	<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css"/>
	<link href="https://emoji-css.afeld.me/emoji.css" rel="stylesheet">
	 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>dialog.dialog{
		text-align: center; 
	  }</style>
	<title>Page de paiement Crypto-monnaie. Point de vente physique</title>
	
  </head>
  <body>
	<header>
	  <nav>
		<img src=""/>
	  </nav>
	  <h4>Pay with Monero </h4>
	  <sub>Secure. Made in France <i class="em-svg em-fr" aria-role="presentation" aria-label="France Flag"></i></sub>
	  <hr>
	  <p class='highlight'>Bitcoin : <?php if(bitcoin_init()===true){echo 'Disponible';} else{echo'Maintenance';} ?></p>
	  <p class='highlight'>Monero : <?php if(monero_init()===true){echo 'Disponible';} else{echo'Maintenance';} ?></p>
	</header>
	
	<form action="" method="POST">
	  <p>
		<label for="currency">Selectionnez la monnaie :</label>
		<select id="currency" name="monnaie"> 
		  <optgroup label="Choix :">
			<option value="monero">Monero</option>
			<option value="bitcoin">Bitcoin</option>
		  </optgroup>
		</select>
	  </p>
	  
	  <p>
		<label for="nombre">Saisissez le Montant à payer :</label>
		<input type="number" id="nombre" min="1" step=".01" name="montant" required/>
	  </p>
	  
	  <button type="submit" name="ri">Generez le QR code de paiement</button>
	</form>
	
	<figure>
	<table>
	  <caption>Transactions :</caption>
	  <thead>
		<tr>
		  <th>Status :</th>
		  <th> </th>
		  <th>Label :</th>
		  <th>Montant (€):</th>
		  <th>Monnaie :</th>
		  <th>Adresse :</th>
		  <th>Date :</th>
		</tr>
	  </thead>
	  
	  <tbody>
		<?php foreach ($txs as $tx) : $i+=1;?>
		
		<tr>
		  <?php if ($tx['status']===1) : ?>
		  <td><i class="em em-white_check_mark" aria-role="presentation" aria-label="WHITE HEAVY CHECK MARK"></i></td>
		  <?php else : ?>
		  <td></td>
		  <?php endif; ?>
		  <td><i onclick="myFunc(<?=$tx['id']?>)" style="cursor:pointer;" class="em-svg em-mag_right click" aria-role="presentation" aria-label="RIGHT-POINTING MAGNIFYING GLASS"></i></td>
		  <td><?= $tx['label'] ?></td>
		  <td><?= $tx['amount'] ?></td>
		  <td><?= $tx['currency'] ?></td>
		  <td style="word-brea: break-all;"><?= $tx['address'] ?></td>
		  <td><?= date('d-m-Y H:i:s',$tx['time']);?></td>
		</tr> 
		
		<dialog id="dialog-text<?=$i;?>" class="dialog">
		  <h3 class='label'></h3>
		  <div class='text' style="overflow-wrap: anywhere;"></div>
		  <img class='image' src="" />		 
		  <form method="dialog"><button id="fermer">Fermer</button></form>
		</dialog>
		<?php endforeach; ?>  
	  </tbody>
	  <tr>
		<th>3</th>
		<th>3</th>
		<th>3</th>
	  </tr>
	  <tfooter>
	  </tfooter>
	</table>
	</figure>
	  
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script>
	  
	  
	  function VerifiezPaiement(id,address,crypto,amount){
		// if no updates of img.src during exec of this, (mean status change to 1) try ad check status before continue this func.
		var xhr1 = new XMLHttpRequest();
		xhr1.open('POST', 'details.php', true);
		xhr1.setRequestHeader("Content-Type", "application/json");		
		xhr1.onerror = function() {console.log('Connection Error!');};// There was a connection error of some sort
		xhr1.timeout = 4000;
		var data = JSON.stringify({"id": id, "address": address, "crypto": crypto, "amount": amount, "message": 8});
		xhr1.send(data);
		
		xhr1.onload = function() {	
		  //console.log(xhr1);
		  if (xhr1.status >= 200 && xhr1.status < 400) {
			var result1 = JSON.parse(xhr1.responseText);
			if (result1 == 1) {
			  const img = document.querySelector("img.image");
			  const label = document.querySelector("h3.label");
			  img.src = '';
			  label.innerText = '';
			  label.innerText = 'Reçu! Merci de votre confiance.';	
			  //img.setAttribute("src", "v.gif");
			  img.src = "v.gif";
			}
		  }
		}//end onload
		
		xhr1.ontimeout = (e) => {
		  alert('5. Impossible d\'afficher les details, merci de contacter l\'administrateur. Veuillez verifiez si la crypto-monnaie est en ligne.');
		  console.log('5');} 
		  
		}//end func
		  
		  
		  
		  function myFunc(x) {
		  const dialog = document.querySelector("dialog.dialog");
		  const text = document.querySelector("div.text");
		  const label = document.querySelector("h3.label");
		  const img = document.querySelector("img.image");
		  dialog.showModal(); 
		  
		  text.innerText = '';
		  label.innerText = 'Chargement...';
		  img.src = '';
		  
		  var xhr = new XMLHttpRequest();
		  xhr.open('POST', 'details.php', true);
		  xhr.setRequestHeader("Content-Type", "application/json");	
		  xhr.onerror = function() {console.log('Connection Error!');};// There was a connection error of some sort
		  xhr.timeout = 4000;
		  var data = JSON.stringify({"id": x, "email": 6, "subject": 4, "message": 9});
		xhr.send(data); 
		
		xhr.onload = function() {
		  if (xhr.status >= 200 && xhr.status < 400) {
			//console.log(xhr);
			var result = JSON.parse(xhr.responseText);
			const url = new URL(result.img);
			
			url.searchParams.forEach(function(value, name_item){	
			  if (value.length == 0 ) {
				alert('3. Impossible d\'afficher les details de ce qr code, merci de contacter l\'administrateur. Veuillez verifiez si la crypto-monnaie est en ligne.');
				console.log('3');}
			});
			
			//label.innerText = '';
			label.innerText = '#'+result.label;
			//text.innerText = 'Monnaie: ';
			text.innerHTML += '€'+result.amount+"<br/>";
			text.innerHTML += result.currency+"<br/>";
			//text.innerHTML += "<br/>";
			//text.innerText += 'Montant:(euro.s) ';
			//text.innerText += result.amount;
			//text.innerHTML += "<br/>";
			//text.innerText += 'Status: ';
			if (result.status==0){text.innerText += 'En attente'; }
			if (result.status==1){text.innerText += 'Reçu!'};
			//text.innerHTML += "<br/><br/>";
			//text.innerText += 'Adresse: ';
			//text.innerText += result.address;
			text.innerHTML += "<br/>";
			text.innerHTML += "<b><h5>Merci de mettre les frais en prioritaire!</h5></b>";

			//img.src = 'qr.php?data='+result.img;
			img.src = "qr.php?data="+btoa(result.img);
			
			if (result.status == 0) {
			  var fin = setInterval(() => VerifiezPaiement(result.id,result.address,result.currency,result.amount),2000);
			  document.getElementById('fermer').addEventListener("click", function (e) {	clearInterval(fin);});
			}
			
			if (result.status == 1) {img.src = "v.gif";}
			
			
		  } else { console.log('Server Error!');} // We reached our target server, but it returned an error
		}; //end onload
		
		xhr.ontimeout = (e) => {
		  alert('4. Impossible d\'afficher les details, merci de contacter l\'administrateur. Veuillez verifiez si la crypto-monnaie est en ligne.');
		  console.log('4');} 
		  
		  
		} // end func
		  
		  
		  
	</script>
	
  </body>
</html>