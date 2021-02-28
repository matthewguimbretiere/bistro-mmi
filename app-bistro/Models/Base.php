<?php
//
// Fichier: app\Models\Base.php
//

namespace App\Models;

/**
 * Classe de base pour le CRUD sur les données.
 * Contient les 6 méthodes :
 *   - exists( $id )
 *   - add( $datas )
 *   - get( $id )
 *   - getAll()
 *   - update( $id, $datas )
 *   - delete( $id )
 */
class Base {
  protected $tableName;
  // instance de la classe
  protected static $dbh;

  public function __construct() {
    if (!self::$dbh) {
      try {
        self::$dbh = new \PDO(
          'mysql:host=' . APP_DB_HOST . ';dbname=' . APP_DB_NAME . ';charset=UTF8',
          APP_DB_USER,
          APP_DB_PASSWORD,
          [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
          ]
        );
      } catch (Exception $e) {
        trigger_error('Impossible de se connecter à la base', E_USER_ERROR);
      }
    }
  }

  /**
   * Indique si l'identifiant existe déjà dans la base.
   *
   * @param  integer  $id identifiant à tester.
   * @return boolean
   */
  public function exists($id) {
    try {
      $sql = "SELECT COUNT(*) AS c FROM `{$this->tableName}` WHERE id = :id";
      $sth = self::$dbh->prepare($sql);
      if ($sth) {
        $sth->bindValue(':id', $id);
        $sth->execute();
        return ($sth->fetch()['c'] > 0);
      } else {
        trigger_error('ERREUR dans la requête SQL de la méthode exists: ' . $sql);
      }
    } catch (Exception $e) {
      trigger_error('ERREUR dans la méthode Base@exists', E_USER_ERROR);
    }
  }

  /**
   * Ajoute les nouvelles informations.
   *
   * @param  array  $datas  données à ajouter organisées sous forme de tableau associatif.
   * @return integer
   */
  public function add($datas) {
    try {
      $sql = 'INSERT INTO `' . $this->tableName . '` ( ';
      foreach (array_keys($datas) as $k) {
        $sql .= " {$k} ,";
      }
      $sql = substr($sql, 0, strlen($sql) - 1) . ' ) VALUE (';
      foreach (array_keys($datas) as $k) {
        $sql .= " :{$k} ,";
      }
      $sql = substr($sql, 0, strlen($sql) - 1) . ' )';
      $sth = self::$dbh->prepare($sql);
      if ($sth) {
        foreach (array_keys($datas) as $k) {
          $sth->bindValue(':' . $k, $datas[$k]);
        }
        $sth->execute();
        return self::$dbh->lastInsertId();
      } else {
        trigger_error('ERREUR dans la requête SQL de la méthode add: ' . $sql);
      }
    } catch (Exception $e) {
      trigger_error('ERREUR dans la méthode Base@add', E_USER_ERROR);
    }
  }

  /**
   * Édite les  informations d'un identifiant.
   *
   * @param  integer  $id     identifiant à modifier.
   * @param  integer  $datas  tableau associatif des données à modifier.
   * @return integer
   */
  public function update($id, $datas) {
    try {
      $sql = 'UPDATE `' . $this->tableName . '` SET ';
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
   * Retourne les informations d'un identifiant.
   *
   * @param  integer  $id identifiant
   * @return array
   */
  public function get($id) {
    try {
      $sql = "SELECT * FROM `{$this->tableName}` WHERE id = :id";
      $sth = self::$dbh->prepare($sql);
      if ($sth) {
        $sth->bindValue(':id', $id);
        $sth->execute();
        return $sth->fetch();
      } else {
        trigger_error('ERREUR dans la requête SQL de la méthode get : ' . $sql);
      }
    } catch (Exception $e) {
      trigger_error('ERREUR dans la méthode Base@get', E_USER_ERROR);
    }
  }

  /**
   * Retourne toutes les informations.
   *
   * @return array
   */
  public function getAll() {
    try {
      $sql = "SELECT * FROM `{$this->tableName}`";
      $sth = self::$dbh->query($sql);
      if ($sth) {
        return $sth->fetchAll();
      } else {
        trigger_error('ERREUR dans la requête SQL de la méthode getAll: ' . $sql, E_USER_ERROR);
      }
    } catch (Exception $e) {
      trigger_error('ERREUR dans la méthode Base@getAll', E_USER_ERROR);
    }
  }

  /**
   * Efface l'identifiant.
   *
   * @param  integer  $id identifiant
   * @return int|boolean
   */
  public function delete($id) {
    try {
      $sql = "DELETE FROM `{$this->tableName}` WHERE id = :id";
      $sth = self::$dbh->prepare($sql);
      if ($sth) {
        $sth->bindValue(':id', $id);
        return $sth->execute();
      } else {
        trigger_error('ERREUR dans la requête SQL de la méthode delete: ' . $sql, E_USER_ERROR);
      }
    } catch (Exception $e) {
      trigger_error('ERREUR dans la méthode Base@delete', E_USER_ERROR);
    }
  }

  /**
  * Upload d'image
  **/
  public function upImg($id, $datas){
        // récupérer le nom et emplacement du fichier dans sa zone temporaire
        $source = $datas['illustration']['tmp_name'];
        // récupérer le nom originel du fichier
        $original = $datas['illustration']['name'];
        // récupérer séparément le nom du fichier et son extension
        $original_filename = pathinfo( $original, PATHINFO_FILENAME );
        $original_ext = pathinfo($original, PATHINFO_EXTENSION);
        // reconstruire le nouveau nom unique du fichier téléchargé
        $filename = $original_filename . '_' . uniqid() . '.' . $original_ext;
        // construire le nom et l'emplacement du fichier de destination
        $dest = 'assets/img/illustrations/' . $filename ;
        // placer le fichier dans son dossier cible (le fichier de la zone temporaire est effacé)
        move_uploaded_file( $source, $dest );

        if ($original_ext == "jpg" || $original_ext == "jpeg" || $original_ext == "png" || $original_ext == "gif") {          
          if ($id == 0) {
              $id = self::$dbh->lastInsertId();
          }
          $sql = "UPDATE formula SET illustration = :illustration WHERE id = :id";
          $sth = self::$dbh->prepare($sql);
          $sth->execute(
              [
                  ':illustration' => $filename,
                  ':id' => $id
              ]
          );
        }
    }

    /**
    * Affichage du bouton "en savoir plus"
    * @param datas
    * @return tab
    **/
    public function btnMore( $datas ) {
      $tab = array();
      foreach ($datas as $formule) {
        if (str_word_count ($formule['description']) > 19 ) {
          array_push($tab, ["id" => $formule['id'],"long" =>  1]);
        } else {
          array_push($tab, ["id" => $formule['id'],"long" => 0]);
        }
      }
      return $tab;
    }
}
