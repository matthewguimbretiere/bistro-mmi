<?php

namespace App\Models;

class Admin extends Base
{
    protected $tableName = 'admin';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
   * Indique si les données d'authentification sont correctes.
   * @param string $pseudo
   * @param string $password
   * @return boolean false ou bien le tableau des données de l'admin.
   */
    function isAdmin( string $pseudo, string $password )
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE login = :pseudo AND password = :password";
        $sth = self::$dbh->prepare($sql);
 		   $sth->execute([':pseudo' => $pseudo, ':password' => sha1($password)]);
 		   return $sth->fetch();
    }

    /**
   * Retourne les paramètres
   * @return void
   */
    public function getAllParameters()
    {
        $sql = "SELECT * FROM parameter ORDER BY id DESC LIMIT 1";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        return $sth->fetch();
    }

    /**
    * Sauvegarde des modifications
    **/
    public function updated($table, $id, $datas) {
      try {
        $sql = 'UPDATE `' . $table . '` SET ';
        foreach (array_keys($datas) as $k) {
          $sql .= " {$k} = :{$k} ,";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= ' WHERE id =:id';
        $sth = self::$dbh->prepare($sql);
        if ($sth) {
          foreach (array_keys($datas) as $k) {
            $sth->bindValue(':' . $k, $datas[$k]);
          }
          $sth->bindValue(':id', $id);
          return $sth->execute();
        } else {
          trigger_error('ERREUR dans la requête SQL de la méthode get: ' . $sql);
        }
      } catch (Exception $e) {
        trigger_error('ERREUR dans la méthode Base@update', E_USER_ERROR);
      }
    }

    /**
    * Chiffres d'affaire de la veille
    **/
    public function caVeille() {
      // Date de la veille
      $date = date('Y-m-d');
      $date =  date('Y-m-d',strtotime($date)-86400);
      // Sélection des clients de la veille
      $sql = "SELECT * FROM customer WHERE arrival_date LIKE '".$date."%'";
      $sth = self::$dbh->prepare($sql);
      $sth->execute();
      $clients = $sth->fetchAll();
      // Connaître le prix de chaque formules des clients
      // Et les additionner
      $tot = 0;
      foreach ($clients as $client) {
        $sql = "SELECT price FROM formula WHERE id = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->execute([":id" => $client['formula_id']]);
        $formule = $sth->fetch();
        $tot = $tot + $formule['price'];
      }

      return $tot;
    }

    /**
    * Nombre de clients du jour
    **/
    public function nbrClients() {
      // date du jour 
      $date = date('Y-m-d');

      // Sélection des clients + les compters
      $sql = "SELECT * FROM customer WHERE arrival_date LIKE '".$date."%'";
      $sth = self::$dbh->prepare($sql);
      $sth->execute();
      $tot = $sth->rowCount();

      return $tot;
    }
}
?>