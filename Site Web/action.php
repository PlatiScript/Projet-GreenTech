<?php
	namespace php\manager\crontab;
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	session_start();
	if($_SESSION['user']){
	include 'Database.php';

	function newCrontab($commande){
		$f = fopen("crontab", "w");
		fwrite($f, $commande);
		fclose($f);

		exec('crontab /var/www/html/crontab');

		$output = shell_exec('crontab -l');
    		echo "<pre>$output</pre>";
	}
	if(isset($_GET['action'])){
		if($_GET['action'] == "recolte"){
			$query = $db->prepare("UPDATE cases SET planted=0 WHERE id=".$_GET['case']);
			$query->execute();
			$query = $db->prepare("UPDATE cases SET name='none' WHERE id=".$_GET['case']);
			$query->execute();
			exec('python /var/www/html/serre.py '. $_GET['action']. ' ' . $_GET['case']);
			header("Location: index.php");
		}
		if($_GET['action'] == "planter"){
			$legume = $_GET['name'];
			$query = $db->prepare("SELECT * FROM legumes WHERE name= '$legume'");
			$query->execute();
			$data = $query->fetchObject();

			$query = $db->prepare("UPDATE cases SET planted=1, name='". $_GET['name'] ."' WHERE id=".$_GET['case']);
			$query->execute();
			header("Location: index.php");
			newCrontab("* * * * * /var/www/html/action.php?arrosage&case=".$_GET['case']);
			exec('python /var/www/html/serre.py '. $_GET['action']. ' ' . $_GET['case']);
		}
		if($_GET['action'] == "arrosage"){
			header("Location: index.php");
			exec('python /var/www/html/serre.py '. $_GET['action']. ' ' . $_GET['case']);
		}
		if($_GET['action'] == "deco"){
			session_destroy($_SESSION['user']);
			header("Location: connect.php");
		}
		if($_GET['action'] == "calibration"){
			exec('python /var/www/html/serre.py calibration 0');
			header("Location: index.php");
		}
		if($_GET['action'] == "getInfo"){
		$legume = $_GET['legume'];
		$query = $db->prepare("SELECT * FROM legumes WHERE name= '$legume'");
		$query->execute();
		$data = $query->fetchObject();
		$date_recolte=Date('d/m/y', strtotime("+". $data->temps_recolte ." days"));
		?>
		<h2>INFORMATIONS DU LÉGUME</h2>
		<h3>Temps de pousse : <?php echo $data->temps_recolte." jours" ?></h3>
		<h3 class="temps_recolte">Récolte prévue le : <?php echo $date_recolte?></h3>
		<h3>Arrosage prévu toutes les : <?php echo $data->temps_arrosage." heures" ?></h3>
		<h3>Niveau d'eau du réservoir : 25%</h3>
		<?php
	}
	}else{
		header("Location:index.php");
	}
	
}else{
	header("Location: connect.php");
}   

?>