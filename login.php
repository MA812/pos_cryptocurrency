<?php
ini_set('display_errors',1);
require('functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['connexion']))  {
	if (isset($_POST['email'],$_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
	  $email = htmlentities($_POST['email']);
	  $password = hash('sha512',$_POST['password']); 
	  $db = bdd_connect();
	  $req = $db->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
	  $req->bindParam(':email',$email);
	  $req->bindParam(':password',$password);
	  $req->execute(); 
	  $res = $req->fetch(PDO::FETCH_ASSOC);
	  
	  if (isset($res) && !empty($res)):$_SESSION['login']['connected']=true;$_SESSION['login']['email']=$res['email'];$_SESSION['login']['uniqid']=$res['uniqueid']; header('Location: /index.php?'.$res['uniqueid'] );  else: $errors['login'] = 'Identifiants incorrect, Veuillez reessayer'; endif;
	  
	}
  }
  
    if (isset($_POST['deconnexion']))  {unset($_SESSION['login']);}
}


?>
<!DOCTYPE html>

<head>
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css"/>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page de connexion</title>
</head>

<body>
  <h4>Page de connexion</h4>
  <?php if (isset($errors['login']) && !empty($errors['login']) ): ?>
  <ul><li><?= $errors['login'].' '.date('H:m',time())?></li></ul>
  <?php endif; ?>
  <form action="" method="POST" >
	<label for="email">email</label>
	<input type="email" id="email" name="email" required>
	<label for="password">password</label>
	<input type="password" id="password" name="password" required>
	<button type="submit" name="connexion">Connexion</button>
  </form>
  
  <hr>
  
  <?php
if (isset($_SESSION['login']['connected']) && $_SESSION['login']['connected']===true){
  	  $db = bdd_connect();
	  $req = $db->prepare('SELECT * FROM users WHERE email = :email');
	  $req->bindParam(':email',$_SESSION['login']['email']);
	  $req->execute(); 
	  $res = $req->fetch(PDO::FETCH_ASSOC);
	  if (isset($res) && !empty($res)):
?>
  <h4>Tableau de bord</h4>
  	
  <ul>
	<li>
	  <form action="" method="POST" >
		<button type="submit" name="deconnexion">Deconnexion</button>
	  </form>
	</li>
  </ul>
  
  <h5>Lieux de ventes - (A mettre en favori et enregistrer les informations de connexion.)</h5>
  <ul>
	<li><a  href="index.php?<?=$res['uniqueid']?>">/<?=$res['uniqueid']?></a></li>
  </ul>
 <?php endif; } ?>
  
  
  
  
  
  
  
  
  
  
  
</body>

<footer>
  made in France.
</footer>
</html>