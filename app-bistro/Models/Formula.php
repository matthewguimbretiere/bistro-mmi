<?php

namespace App\Models;

class Formula extends Base
{
    protected $tableName = 'formula';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
   * Retourne les formules
   * @return void
   */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY created_at DESC";
        return self::$dbh->query($sql)->fetchAll();
    }

    /**
    * Obtenir une formule par son id
    * @param integer id
    * @return void
    **/
    public function getFormulaById( $id ) {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([":id" => $id]);
        return $sth->fetch();
    }

    /**
    * Supprime une formule
    * @param integer id
    **/
    public function delete( $id ) {
        $sql = "DELETE FROM {$this->tableName} WHERE id = :id";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([":id" => $id]);
    }

    /**
    * Ajout d'une formule
    **/
    public function add ( $datas ){
        $name = htmlspecialchars($datas['name']);
        $description = htmlspecialchars($datas['description']);
        $price = htmlspecialchars($datas['price']);
        if (isset($datas['enabled'])) {
            $enabled = 1;
        } else {
            $enabled = 0;
        }
        $discount = htmlspecialchars($datas['discount']);
        $sql = "INSERT INTO formula (name,description,price,enabled,discount) VALUES (:name,:description,:price,:enabled,:discount)";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([
            ":name" => $name,
            ":description" => $description,
            ":price" => $price,
            ":enabled" => $enabled,
            ":discount" => $discount
        ]);
    }

    /**
    * Update d'une formule
    **/
    public function update ( $id, $datas ){
        $name = htmlspecialchars($datas['name']);
        $description = htmlspecialchars($datas['description']);
        $price = htmlspecialchars($datas['price']);
        if (isset($datas['enabled'])) {
            $enabled = 1;
        } else {
            $enabled = 0;
        }
        $discount = htmlspecialchars($datas['discount']);
        $sql = "UPDATE formula SET name = :name, description = :description, price = :price, enabled = :enabled, discount = :discount WHERE id = :id";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([
            ":name" => $name,
            ":description" => $description,
            ":price" => $price,
            ":enabled" => $enabled,
            ":discount" => $discount,
            ":id" => $id
        ]);
    }

    /**
    * Obtenir une description de formule par son id
    * @param integer id
    * @return void
    **/
    public function getDescriptionById( $id ) {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $sth = self::$dbh->prepare( $sql );
        $sth->execute([":id" => $id]);
        $description = $sth->fetch();

        header( 'Content-Type: application/json' );
        echo json_encode([
          'description' => $description['description']
        ]);
    }
}
