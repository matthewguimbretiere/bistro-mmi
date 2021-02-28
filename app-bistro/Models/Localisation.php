<?php

namespace App\Models;


use Endroid\QrCode\QrCode;

class Localisation extends Base
{
    protected $tableName = 'localisation';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
    * Obtenir tous les paramètres
    **/
    public function getAllParameter() {
      $sql = "SELECT * FROM parameter";
      $sth = self::$dbh->prepare( $sql );
      $sth->execute();
      return $sth->fetchAll();
    }

    /**
    * Obtenir toutes les localisations
    **/
    public function getAllLocalisation() {
      $sql = "SELECT * FROM localisation";
      $sth = self::$dbh->prepare( $sql );
      $sth->execute();
      return $sth->fetchAll();
    }

    /**
    * Ajout d'une table
    **/
    public function add( $datas ) {
      $name = htmlspecialchars($datas['name']);

      $dataQRcode = APP_ROOT_URL_COMPLETE . "/client/table/" . $name;
      // instancier l'objet de manipulation du QRcode
      $qrCode = new QrCode($dataQRcode);
      // specifier la dimension de l'image produite + les marges
      $qrCode->setSize(300);
      $qrCode->setMargin(10); 
      // spécifier le nom du fichier image en .png (sans le dossier d'installation)
      // le nommage doit être qrcode-nom-de-la-table-.png
      $qrcodeFileName = "qrcode-" . $name . ".png";
      // produire l'image
      $qrCode->writeFile(APP_ASSETS_DIRECTORY . "img/qrcode/" . $qrcodeFileName);

      $sql = "INSERT INTO localisation (name,row,col,cplace,qrcode) VALUES(:name,:row,:col,:cplace,:qrcode)";
      $sth = self::$dbh->prepare( $sql );
      $sth->execute([
        ":name" => $name,
        ":row" => $datas['row'],
        ":col" => $datas['col'],
        ":cplace" => $datas['cplace'],
        ":qrcode" => $qrcodeFileName,
      ]);
    }

    /**
    * Obtenir l'id d'une table par son nom
    **/
    public function getByName( $name ) {
      $sql = "SELECT * FROM localisation WHERE name = :name";
      $sth = self::$dbh->prepare( $sql );
      $sth->execute([":name" => $name]);
      $infos = $sth->fetch();

      return $infos['id'];
    }
   
}
?>