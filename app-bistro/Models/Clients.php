<?php

namespace App\Models;

class Clients extends Base
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
	* Retourne toutes les informations sur les clients en fonction d'un
	* critère de date.
	* Le paramètre $period_criteria peut prendre 3 valeurs : 'jour', 'semaine' ou 'tous'
	* et, selon, la méthode retourne les clients intégrés dans la période ciblée.
	*
	* @return array
	*/
	public function getAllFiltered(string $period_criteria)
	{
		 try {
			 $today = date_format(date_create(), 'Y-m-d');
			 $today_minus_7j = date_create();
			 date_sub($today_minus_7j, date_interval_create_from_date_string('7 days'));
			 $today_minus_7j = date_format($today_minus_7j, 'Y-m-d');
			 switch ($period_criteria) {
			 	case 'jour':
			 		$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $today ."%'";
			 		$sth = self::$dbh->prepare( $sql );
		     		$sth->execute();
		      		return $sth->fetchAll();
		      		break;
		      	case 'semaine':
			 		$sql = "SELECT * FROM customer WHERE arrival_date >= '". $today_minus_7j ."%'";
			 		$sth = self::$dbh->prepare( $sql );
		     		$sth->execute();
		      		return $sth->fetchAll();
		      		break;
		      	case 'tous':
			 		$sql = "SELECT * FROM customer";
			 		$sth = self::$dbh->prepare( $sql );
		     		$sth->execute();
		      		return $sth->fetchAll();
		      		break;
		      	default:
		      		$sql = "SELECT * FROM customer WHERE arrival_date LIKE '". $today ."%'";
			 		$sth = self::$dbh->prepare( $sql );
		     		$sth->execute();
		      		return $sth->fetchAll();
		      		break;
		     }
		} catch(Exception $e){
		    
		}
	}

	/**
	* Recherche des clients par rapport à l'adresse mail et la date de venue
	**/
	public function search( $email, $arrival_date ) {
		if (empty($email) AND !empty($arrival_date)) {
			$sql = "SELECT * FROM customer WHERE arrival_date LIKE ':".$arrival_date."%'";
		} elseif (empty($arrival_date) AND !empty($email)) {
			$sql = "SELECT * FROM customer WHERE email LIKE '".$email."%'";
		} else {
			$sql = "SELECT * FROM customer WHERE email LIKE '".$email."%' OR arrival_date LIKE ':".$arrival_date."%'";
		}

		// Recherche des clients
		
		$sth = self::$dbh->prepare($sql);
		$sth->execute();
		$clients = $sth->fetchAll();

		header( 'Content-Type: application/json' );
	    echo json_encode([
	     'clients' => $clients
	    ]);
	}	


    
}
