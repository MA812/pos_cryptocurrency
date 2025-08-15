<?php
ini_set('display_errors',1);
require('functions.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['inscription']))  {
	if (isset($_POST['email'],$_POST['password'],$_POST['password2']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
	  $email = htmlentities($_POST['email']);
	  $password = hash('sha512',$_POST['password']);
	  $password2 = hash('sha512',$_POST['password2']);
	  if ($password == $password2) {
		$db = bdd_connect();
		$req = $db->prepare('SELECT * FROM users WHERE email = :email');
		$req->bindParam(':email',$email);
		$req->execute(); 
		if($req->rowcount() > 1){echo  $errors['register'] = 'Un compte est deja present avec cette email.'; }
		else{
		  
		  $uniqueid = uniqid(true);
		  $info = CreateAccountMonero($uniqueid);
		  $db = bdd_connect();
		  $req = $db->prepare('INSERT INTO users (email, password,uniqueid,status,id_base_monero,address_base_monero) VALUES(:email,:password,:uniqueid,:status,:id_base_monero,:address_base_monero) ');
		  $req->bindParam(':email',$email);
		  $req->bindParam(':password',$password);
		  $req->bindParam(':uniqueid',$uniqueid);
		  $req->bindValue(':status',true);
		  $req->bindParam(':id_base_monero',$info['id']);
		  $req->bindParam(':address_base_monero',$info['address']);
		  $res = $req->execute(); 
		  if (isset($res) && !empty($res) && $res === true): 
		  $_SESSION['login']['connected']=true;
		  $_SESSION['login']['email']=$email;
		  $confirmation='Votre inscription a bien ete prise en compte';
		  header('Location: /login.php');
		  else: $errors['register'] = 'Il y a une erreur dans la creation du compte, veuillez ressayer'; endif;
		}
		
	  } else {$errors['register']='Les mots de passe ne sont pas identiques';}
	  
	}
  }
}


?>
<!DOCTYPE html>

<head>
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css"/>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page d'inscription</title>
</head>

<body>
  <h4>Page d'inscription</h4>
  <?php if (isset($errors['register']) && !empty($errors['register']) ): ?>
  <ul><li><?= $errors['register'] ?></li></ul>
  <?php endif; ?>
  
  <?php if (isset($confirmation) && !empty($confirmation)): ?>
  <div><?=$confirmation;?></div>
  <?php endif; ?>
  <form action="" method="POST" >
	<label for="email">email</label>
	<input type="email" id="email" name="email" required>
	<label for="password">Mot de passe </label>
	<input type="password" id="password" name="password" required>
	<label for="password2">Confirmation du mot de passe</label>
	<input type="password" id="password2" name="password2" required>
	<button type="submit" name="inscription">Inscription</button>
  </form>
</body>

<footer>
  made in France.
</footer>
</html>