<?php

namespace App\Controllers;

use App\Models\Formula;

class FormulaController extends Controller {

  /**
   * Afficher toutes les formules
   */
  public function index() {
    checkLogin();
    $this->display(
      'backoffice/formula/list.html.twig',
      [
        'activItem' => 'formula',
        'formulas' => Formula::getInstance()->getAll()
      ]
    );
  }

  /**
   * Supprimer une formule
   * @param integer id
   */
  public function delete( $id ) {
    checkLogin();
    Formula::getInstance()->delete( $id );
    redirect('/backoffice/formules');
  }

  /**
  * Page d'ajout de formule
  **/
  public function add() {
    checkLogin();
    $this->display(
      'backoffice/formula/add.html.twig'
    );
  }

  /**
  * Sauvegarde ajout formule
  **/
  public function save( ) {
    checkLogin();
    Formula::getInstance()->add( $_POST );
    $id = 0;
    Formula::getInstance()->upImg( $id, $_FILES );
    redirect('/backoffice/formules');
  }

  /**
  * Page d'édition de formule
  * @param integer id
  **/
  public function edit ( $id ){
    checkLogin();
    $this->display(
      'backoffice/formula/edit.html.twig',
      [
        'infoFormula' => Formula::getInstance()->getFormulaById( $id )
      ]
    );
  }

  /**
  * Sauvegarde update
  * @param integer $id ( $id )
  **/
  public function update ( $id ) {
    checkLogin();
    Formula::getInstance()->update( $id, $_POST);
    Formula::getInstance()->upImg( $id, $_FILES );
    redirect('/backoffice/formules');
  }

  /**
  * Obtenir la description d'une formule 
  **/
  public function getDescriptionById( $id ) {
    $description = Formula::getInstance()->getDescriptionById( $id );

    
  }
}
