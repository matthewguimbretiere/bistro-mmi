<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Formula;

class BackOfficeController extends Controller {

  /**
   * Login dans l'application BackOffice
   */
  public function login() {
    $this->display(
      'backoffice/login.html.twig'
    );
  }

  /**
  * Vérifier le login.
  **/
  public function loginCheck() {
  	$check = Admin::getInstance()->isAdmin( $_POST['pseudo'], $_POST['password']);
  	if ( $check ) {
  		// ouvrir une session en mode ADMIN
  		$_SESSION['login'] = true;
  		$_SESSION['role'] = $check['role'];
  		// redirection vers le backoffice
  		redirect( '/backoffice ');
  	} else {
  		redirect('/login');
  	}
  }

  /**
  * Page indec
  **/
  public function index() {
  	checkLogin();

    // Chiffre d'affaire de la veille
    $caVeille = Admin::getInstance()->caVeille();
    // Clients du jour
    $nbrClients = Admin::getInstance()->nbrClients();
    // Liste formules du jour
    $formulas = Formula::getInstance()->getAll();

    $this->display(
      'backoffice/index.html.twig',
      [
        'activItem' => 'home',
        'caVeille' => $caVeille,
        'nbrClients' => $nbrClients,
        'formulas' => $formulas
      ]
    );
  }

  /**
  * Fermer la session administrateur
  **/
  public function delog() {
  	unset($_SESSION);
  	session_destroy();
  	redirect('/');
  }

  /**
  * Affichage de la page des paramètres
  **/
  public function settings() {
    checkLogin();
    $this->display(
      'backoffice/settings.html.twig',
      [
        'activItem' => 'settings',
        'infos' => Admin::getInstance()->getAllParameters()
      ]
    );
  }
  /**
  * Sauvegarde des modifications des paramètres
  **/
  public function settingsSave( $id ) {
    checkLogin();
    Admin::getInstance()->updated( "parameter", $id, $_POST);
    redirect('/backoffice/settings');
  }
}

?>