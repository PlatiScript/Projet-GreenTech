<?php
session_start();
include 'Database.php'; 
if($_SESSION['user']){
if(htmlspecialchars(isset($_POST['submit']))){
	if(!empty($_POST['name']) && !empty($_FILES['img']) && !empty($_POST['temps_arrosage']) && !empty($_POST['temps_recolte'])){
		$name = htmlspecialchars($_POST['name']);
		$temps_arrosage = htmlspecialchars($_POST['temps_arrosage']);
		$temps_recolte = htmlspecialchars($_POST['temps_recolte']);
		var_dump($_FILES['img']['type']);
		if($_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png"){

			$temp = explode(".", $_FILES["img"]["name"]);
			$newfilename = $name . '.jpg';
			
			$query = $db->prepare("SELECT * FROM legumes WHERE name= '$name'");
			$query->execute();
			$data = $query->fetchObject();
			if($data){
				$error = "Un légume existe déja sous ce nom...";
			}else{
				if(move_uploaded_file($_FILES["img"]["tmp_name"], "img/legume/" . $newfilename)){
				$req = $db->prepare("INSERT INTO legumes (name, temps_recolte, temps_arrosage) VALUES (:name, :temps_recolte, :temps_arrosage)");
			    $req->execute(array(
			            "name" => $name, 
			            "temps_recolte" => $temps_recolte,
			            "temps_arrosage" => $temps_arrosage,
			            ));
			    header("Location:index.php");
			    }else{
					$error = "Erreur lors de l'importation du fichier...";
			    }
			}
		}else{
			$error = "Veuillez mettre une image de type JPG ou PNG...";
		}
	}else{
		$error = "Veuillez remplir tout les champs...";
	}
}
?>
<html>
<head>
	<title>Créer un légume - GreenTech</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="icon" type="image/png" href="img/greenhouse_logo.png" />
</head>
<body style="background-color : #71df7e">
	<div class="container div_new_legume">
		<a href="index.php"><img src="img/greenhouse_logo_all.png" alt="greenhouse_logo_all" class="logo"></a>
		<img src="img/separator.jpg" alt="separator">
		<h2>Un légume manque dans les suggestions ? Vous pouvez grâce à ce formulaire, en rajouter un avec toutes ses caractéristiques !</h2>
		<img src="img/separator.jpg" alt="separator">
		<?php
			if(isset($_POST['submit']) && isset($error)){
				echo "<div class='error'>". $error ."</div>";
			}
		?>
		<form action="new_legume.php" method="POST" class="form_connect" enctype="multipart/form-data">
			<input type="text" name="name" placeholder="Nom du légume">
			<p class="img_label">Image du légume (200*200) (PNG, JPG)</p>
			<input type="file" name="img" value="100000">
			<input type="number" name="temps_arrosage" placeholder="Délai d'arrosage (en heures)">
			<input type="number" name="temps_recolte" placeholder="Temps de pousse (en jours)">
			<input type="submit" name="submit" placeholder="Valider">
		</form>
	</div>
</body>
</html>
<?php
}else{
	header("Location:connect.php");
}
?>