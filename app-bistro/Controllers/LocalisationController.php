<?php

namespace App\Controllers;

use App\Models\Localisation;
use App\Models\Parameter;

class LocalisationController extends Controller {

  /**
   * Affiche la topologie générale
   */
  public function index() {
    $tables = Localisation::getInstance()->getAllLocalisation();
    $nbrTables = count($tables);

    $this->display(
      'backoffice/localisation/index.html.twig',
      [
        'activItem' => 'table',
        'parameters' => Localisation::getInstance()->getAllParameter(),
        'tables' => $tables,
        'nbrTables' => $nbrTables
      ]
    );
  }

  /**
   * Effacer une table.
   * @param integer $id identifiant
   */
  public function delete( $id ) {
    checkLogin();
    Localisation::getInstance()->delete( $id );
    redirect('/backoffice/plan-table');
  }

  /**
   * Enregistrer une nouvelle table
   * Le QR code associé est produit
   */
  public function save( ) {
    checkLogin();          
    Localisation::getInstance()->add( $_POST );
    redirect('/backoffice/plan-table');
  }

  /**
  * Afficher le formulaire d'édition d'une table.
  * @param integer $id identifiant de la table
  **/
  public function edit( $id ) {
    checkLogin();
    $table = Localisation::getInstance()->get( $id );

    $this->display(
      'backoffice/localisation/edit.html.twig',
      [
        'table' => $table
      ]
    );
  }

  /**
  * Mettre à jour une table
  **/
  public function update( $id ) {
    checkLogin();
    Localisation::getInstance()->update( $id, $_POST );
    redirect('/backoffice/plan-table');
  }
}

?>