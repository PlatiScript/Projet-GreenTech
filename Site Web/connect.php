<?php
session_start();
include 'Database.php'; 
if(isset($_POST['submit'])){
	if(htmlspecialchars(!empty($_POST['username'])) && htmlspecialchars(!empty($_POST['password']))){
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$query = $db->prepare("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
	$query->execute();
	$data = $query->fetchObject();
	if($data){
		$_SESSION['user'] = htmlspecialchars($_POST['username']);
		header("Location: index.php");
	}else{
		$error = "Identifiants incorrects...";
	}
}else{
	$error = "Veuillez remplir tous les champs...";
}
}

?>
<html>
<head>
	<title>Connexion - GreenTech</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="icon" type="image/png" href="img/greenhouse_logo.png" />
</head>
<body style="background-color : #71df7e">
	<div class="container div_connect">
		<img src="img/greenhouse_logo_all.png" alt="greenhouse_logo_all" class="logo">
		<img src="img/separator.jpg" alt="separator">
		<h2>Vous devez vous connecter pour accéder<br/> à votre serre...</h2>
		<img src="img/separator.jpg" alt="separator">
		<?php
			if(isset($error)){
				echo "<div class='error'>$error</div>";
			}
		?>
		<form action="connect.php" method="POST" class="form_connect">
			<input type="text" name="username" placeholder="Nom d'utilisateur">
			<input type="password" name="password" placeholder="Mot de passe">
			<input type="submit" name="submit" placeholder="Valider">
		</form>
	</div>
</body>
</html>