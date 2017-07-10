<?php
session_start();
include 'Database.php'; 
if($_SESSION['user']){
	function GetPlanted($id, $db){
		$query = $db->prepare("SELECT * FROM cases WHERE id = '$id'");
		$query->execute();
		$data = $query->fetchObject();
		return $data;
	}
	function GetLegume($name, $db){
		$query = $db->prepare("SELECT * FROM legumes WHERE name = '$name'");
		$query->execute();
		$data = $query->fetchObject();
		return $data;
	}
	function LoadImage($id, $db){
		if(GetPlanted($id, $db)->planted == 1){
			$name = GetPlanted($id, $db)->name
			?>
			<img src="img/legume/<?php echo $name ?>.jpg" class="case_img">
			<?php
		}else{
			echo('<img src="img/legume/none.jpg" class="case_img">');
		}
	}
	function convertMonths($day){
		$month = intval($day/30);
		$day = fmod ($day , 30);

		return [$month, $day];
	}
	function afficher_case($id, $db){
		$planted = GetPlanted($id, $db)->planted;
		$name = GetPlanted($id, $db)->name;
		$date_recolt = GetPlanted($id, $db)->date_recolt;
		$date_planted = GetPlanted($id, $db)->date_planted;
		echo "<div class='case' id ='$id' planted='$planted' name='$name' planted_date='$date_planted' recolte_date='$date_recolt'>";
		LoadImage($id, $db);

		if($planted == 1){
			$date_arrosage = GetLegume(GetPlanted($id, $db)->name, $db)->temps_arrosage;
		}else{
			$date_arrosage = "";
		}

		echo "

		<div class='popup'>
				<img src='img/legume/tomate.jpg' alt='none'/>
				<h1 class='name'>$name</h1>
				<h4><span>En pousse</span></h4>
				<h4 class='planted'>Planté le : $date_planted</h4>
				<h4 class='recolted'>Récolte prévu le : $date_recolt</h4>
				<h4 class='arrosage'>Prochain arrosage dans : $date_arrosage H</h4>
				<a href='#' class='link_recolte'><button class='force_button'>FORCER LA RÉCOLTE</button></a>
				<a href='#' class='link_arrosage'><button class='force_button'>FORCER L'ARROSAGE</button></a>
				<div class='separator'></div>
				<h3>INFORMATIONS DE LA SERRE</h3>
				<h4>Température : 15°C</h4>
				<h4>Humidité : 56%</h4>
				<h4>Humidité de la terre : 84%</h4>
				<h4>Niveau d'eau du réservoir : 25%</h4>
				<button class='validate'>FERMER</button>
			</div>
		";

		//Div Popup_none

		echo"</div>";
	}
?>
<html>
<head>
	<title>Votre Serre - GreenTech</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="icon" type="image/png" href="img/greenhouse_logo.png" />
</head>
<body>
	<div class="header">
		<img src="img/greenhouse_logo.png" alt="greehouse_logo" class="logo">
	</div>
	<div class="aside_left">
		<h2>Actions :</h2>
		<a href="new_legume.php"><button>Créer un légume</button></a>
		<a href="action.php?action=calibration"><button>Calibrer la serre</button></a>
		<button>Éteindre les lumières</button>
		<button>Éteindre la serre</button>
		<div style="height: 100px"></div>
		<a href="action.php?action=deco"><button>Se déconnecter</button></a>
	</div>
	<div class="container">
		<div class="container_greenhouse">
			<?php afficher_case(1, $db); ?>
			<?php afficher_case(2, $db); ?>
			<?php afficher_case(3, $db); ?>
			<?php afficher_case(4, $db); ?>
			<?php afficher_case(5, $db); ?>
			<?php afficher_case(6, $db); ?>
			<?php afficher_case(7, $db); ?>
			<?php afficher_case(8, $db); ?>
			<?php afficher_case(9, $db); ?>
			<div class="popup_none">
				<div class="close">X</div>
				<img src="img/legume/ail.jpg" alt="none"/>
				<h1 class="name">Aucun légume<br />planté</h1>
				<h3>Choisir un légume :</h3>
				<select name="legume">
				<?php
					$query = $db->prepare("SELECT * FROM legumes");
					$query->execute();
					$datas = $query->fetchAll();
					foreach ($datas as $legume) {
						echo "<option name=".$legume["name"].">".$legume["name"]."</option>";
					}
					?>
				</select>
				<div class="separator"></div>
				<div id="info_legume">
				</div>
				<a href="action.php" class="link_planter"><button class="validate">PLANTER !</button></a>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="script.js"></script>
</html>
<?php
}else{
	header("Location: connect.php");
}
?>