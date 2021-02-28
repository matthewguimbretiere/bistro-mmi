<?php

namespace App\Models;

class Graphic extends Base
{
    protected $tableName = 'customer';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
    * Récupération des dates sur 7 jours
    * @return array
    **/
    public function getDates7() {
    	$dates = array();

    	for($i = 0; $i <= 6; $i++ ){
    		$day = date_create();
			date_sub($day, date_interval_create_from_date_string($i .' days'));
			$day = date_format($day, 'Y-m-d');

			array_push($dates, $day);
    	}

    	return $dates;
    }

    /**
	* Retourne toutes les informations sur les clients, formules et chiffre d'affaires en fonction de la semaine.
	* Le paramètre $filtre peut prendre 4 valeurs : 'clients', 'formules', 'ca' ou 'tous'
	* et, selon, la méthode retourne les objets ciblés intégrés dans la semaine.
	*
	* @return array
	*/
	public function getAllFiltered(string $filtre, $dates)
	{
		 try {
			 switch ($filtre) {
			 	case 'clients':
			 		$tab = array();

			 		for($i = 0; $i < 7; $i++) {
			 			// Récupération des membres selon un jour
			 			$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		// Compteur de clients selon un jour
			     		$count = $sth->rowCount();

			     		array_push($tab, $count);
			 		}

			      	return $tab;
		      		break;
		      	case 'formules':
		      		$tab = array();
		      		// Séléction de toutes les formules
		      		$sql = "SELECT * FROM formula";
			     	$sth = self::$dbh->prepare( $sql );
			     	$sth->execute();
			     	$listeFormulas = $sth->fetchAll();
			     	$countFormulas = $sth->rowCount();

			     	foreach ($listeFormulas as $formula) {
			     		$datas = array();

			     		for($i = 0; $i < 7; $i++) {
			     			$sql = "SELECT * FROM customer WHERE formula_id = :formula_id AND arrival_date LIKE '". $dates[$i] ."%'";
				 			$sth = self::$dbh->prepare( $sql );
			     			$sth->execute([":formula_id" => $formula['id']]);
			     			$count = $sth->rowCount();

			     			array_push($datas, ["count" => $count]);
			     		}

			     		array_push($tab, ["name" => $formula['name'], "datas"=> $datas]);

			     	}

			 		return $tab;
		      		break;
		      	case 'ca':
		      		$tab = array();
		      		
			     	for($i = 0; $i < 7; $i++) {
			     		$somme = 0;
		      			// Séléction des clients et de leurs formules en fonction de la date
			     		$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		$clients = $sth->fetchAll();

		      			// Somme des formules pour un jour
		      			$sql = "SELECT * FROM formula WHERE id = :id";
			     		foreach ($clients as $client) {
			     			$sth = self::$dbh->prepare( $sql );
			     			$sth->execute([":id" => $client['formula_id']]);
			     			$formula = $sth->fetch();

			     			$somme = $somme + ($formula['price'] * (1 - $formula['discount'] / 100));
			     		}
			     		array_push($tab, ["ca" => $somme]);
			     	}

			 		return $tab;
		      		break;
		      	case 'tous':
			 		$tabClients = array();
			 		$tabFormulas = array();
			 		$tabCa = array();

			 		/*---------------------------------------Clients-------------------------------------*/
			 		for($i = 0; $i < 7; $i++) {			 			
			 			// Récupération des membres selon un jour
			 			$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		// Compteur de clients selon un jour
			     		$count = $sth->rowCount();

			     		array_push($tabClients, $count);
			 		}
			 		/*----------------------------------------Formules------------------------------------*/
			     	// Séléction de toutes les formules
			      	$sql = "SELECT * FROM formula";
				    $sth = self::$dbh->prepare( $sql );
				    $sth->execute();
				    $listeFormulas = $sth->fetchAll();
				    $countFormulas = $sth->rowCount();

				    foreach ($listeFormulas as $formula) {
				     	$datas = array();

				     	for($i = 0; $i < 7; $i++) {
				     		$sql = "SELECT * FROM customer WHERE formula_id = :formula_id AND arrival_date LIKE '". $dates[$i] ."%'";
					 		$sth = self::$dbh->prepare( $sql );
				     		$sth->execute([":formula_id" => $formula['id']]);
				     		$count = $sth->rowCount();

				     		array_push($datas, ["count" => $count]);
				     	}
				   		array_push($tabFormulas, ["name" => $formula['name'], "datas"=> $datas]);
				    }
				    /*----------------------------------------Chiffres d'affaires------------------------------------*/
				    for($i = 0; $i < 7; $i++) {
			     		$somme = 0;
		      			// Séléction des clients et de leurs formules en fonction de la date
			     		$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		$clients = $sth->fetchAll();

		      			// Somme des formules pour un jour
		      			$sql = "SELECT * FROM formula WHERE id = :id";
			     		foreach ($clients as $client) {
			     			$sth = self::$dbh->prepare( $sql );
			     			$sth->execute([":id" => $client['formula_id']]);
			     			$formula = $sth->fetch();

			     			$somme = $somme + ($formula['price'] * (1 - $formula['discount'] / 100));
			     		}
			     		array_push($tabCa, ["ca" => $somme]);
			     	}

			     	$tab = ["clients" => $tabClients, "formules" => $tabFormulas, "ca" => $tabCa];

			     	return $tab;
		      		break;
		      	default:
		      		$tabClients = array();
			 		$tabFormulas = array();
			 		$tabCa = array();

			 		/*---------------------------------------Clients-------------------------------------*/
			 		for($i = 0; $i < 7; $i++) {			 			
			 			// Récupération des membres selon un jour
			 			$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		// Compteur de clients selon un jour
			     		$count = $sth->rowCount();

			     		array_push($tabClients, $count);
			 		}
			 		/*----------------------------------------Formules------------------------------------*/
			     	// Séléction de toutes les formules
			      	$sql = "SELECT * FROM formula";
				    $sth = self::$dbh->prepare( $sql );
				    $sth->execute();
				    $listeFormulas = $sth->fetchAll();
				    $countFormulas = $sth->rowCount();

				    foreach ($listeFormulas as $formula) {
				     	$datas = array();

				     	for($i = 0; $i < 7; $i++) {
				     		$sql = "SELECT * FROM customer WHERE formula_id = :formula_id AND arrival_date LIKE '". $dates[$i] ."%'";
					 		$sth = self::$dbh->prepare( $sql );
				     		$sth->execute([":formula_id" => $formula['id']]);
				     		$count = $sth->rowCount();

				     		array_push($datas, ["count" => $count]);
				     	}
				   		array_push($tabFormulas, ["name" => $formula['name'], "datas"=> $datas]);
				    }
				    /*----------------------------------------Chiffres d'affaires------------------------------------*/
				    for($i = 0; $i < 7; $i++) {
			     		$somme = 0;
		      			// Séléction des clients et de leurs formules en fonction de la date
			     		$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $dates[$i] ."%'";
				 		$sth = self::$dbh->prepare( $sql );
			     		$sth->execute();
			     		$clients = $sth->fetchAll();

		      			// Somme des formules pour un jour
		      			$sql = "SELECT * FROM formula WHERE id = :id";
			     		foreach ($clients as $client) {
			     			$sth = self::$dbh->prepare( $sql );
			     			$sth->execute([":id" => $client['formula_id']]);
			     			$formula = $sth->fetch();

			     			$somme = $somme + ($formula['price'] * (1 - $formula['discount'] / 100));
			     		}
			     		array_push($tabCa, ["ca" => $somme]);
			     	}

			     	$tab = ["clients" => $tabClients, "formules" => $tabFormulas, "ca" => $tabCa];

			     	return $tab;
		      		break;
		     }
		} catch(Exception $e){
		    
		}
	}

	/**
	* Nombre de formules
	* @return integer
	**/
	public function countFormulas() {
		$sql = "SELECT * FROM formula";
		$sth = self::$dbh->prepare( $sql );
		$sth->execute();
		return $sth->rowCount();
	}

    


    
}
