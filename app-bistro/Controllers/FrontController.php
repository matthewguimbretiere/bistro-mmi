<?php

namespace App\Controllers;

use App\Models\Formula;
use App\Models\Localisation;

class FrontController extends Controller {

  /**
   * Afficher toutes les formules
   */
  public function index() {
    $formula = Formula::getInstance()->getAll();
    $isLong = Formula::getInstance()->btnMore($formula);
    if (isset($_SESSION['login'])) {
        $userExist = true;
     }  else {
        $userExist = false;
     }
    $this->display(
      'front/index.html.twig',
      [
        'formulas' => $formula,
        'isLong' => $isLong,
        'userExist' => $userExist
      ]
    );
  }

  /**
  * Ajout de nouveau client par qrcode
  **/
  public function addCustomer( $table ) {
    $formulas = Formula::getInstance()->getAll();

    $id_table = Localisation::getInstance()->getByName( $table );

    $this->display(
      'front/addClient.html.twig',
      [
        'table' => $id_table,
        'formulas' => $formulas
      ]
    );
  }
}
