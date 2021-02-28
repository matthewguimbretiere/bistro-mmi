<?php

namespace App\Controllers;

use App\Models\Clients;
use App\Models\Localisation;
use App\Models\Formula;

class ClientsController extends Controller {
  /**
  * Affichage de la page des clients
  **/
  public function index( $filtre ) {
    checkLogin();

    $lesClients = Clients::getInstance()->getAll();
    $countClients = count($lesClients);
    $customers = Clients::getInstance()->getAllFiltered($filtre);
    $formulas = array();
    foreach ($customers as $customer) {
      $formula = Formula::getInstance()->get($customer['formula_id']);
      array_push($formulas, [
        "id_user" => $customer['id'],
        "formulaName" => $formula['name']
      ]);
    }
    $this->display(
      'backoffice/clients/index.html.twig',
      [
        'activItem' => 'clients',
        'countClients' => $countClients,
        'customers' => $customers,
        'formulas' => $formulas,
        'filter' => $filtre
      ]
    );
  }

  /**
  * Injection de clients fictifs
  **/
  public function injection() {
    checkLogin();

    // récupérer tous les clients
    $customers = Clients::getInstance()->getAll();
    // récupérer les identifiants de localisation des tables
    $localisations = Localisation::getInstance()->getAll();
    $localisation_ids = array_map(
     function ($localisation) {return $localisation['id'];},
     $localisations
    );
    // récupérer les identifiants de toutes les formules
    $formulas = Formula::getInstance()->getAll();
    $formula_ids = array_map(
     function ($formula) {return $formula['id'];},
     $formulas
    );
    // traiter chaque client pour faire une mise à jour
    foreach ($customers as $customer) {
     $datas = [];
     $id = $customer['id'];
     // fixer une localisation aléatoire
     $datas['localisation_id'] = $localisation_ids[array_rand($localisation_ids)];
     // fixer une formule aléatoire
     $datas['formula_id'] = $formula_ids[array_rand($formula_ids)];
     $entry = new \DateTime();
     $entry->setTime(12, 5); // arrivée à 12h05 par défaut du client
     if (random_int(0, 10) > 4) { // soustraire un certain nombre de jours (entre 0 et 15) dans 30% des cas ?
     $entry->sub(new \DateInterval('P' . random_int(0, 15) . 'D'));
     }
     // fixer une date/heure d'arrivée au format Y-m-d H:i:s
     $datas['arrival_date'] = $entry->format('Y-m-d H:i:s');
     $quit = $entry; // heure de départ identique à heure d'arrivée à priori
     // mais ajouter un certain nombre de minutes (entre 20 et 40)
     $quit->add(new \DateInterval('PT' . random_int(20, 40) . 'M'));
     // fixer une date/heure de départ
     $datas['departure_date'] = $quit->format('Y-m-d H:i:s');
     //
     Clients::getInstance()->update($id, $datas);
    }
    // redirection
    redirect( '/backoffice/clients/jour' );

  }

  /**
  * Edit d'un client
  **/
  public function edit( $id ) {
    checkLogin();

    $customer = Clients::getInstance()->get($id);
    $formula = Formula::getInstance()->get($customer['formula_id']);
    $this->display(
      'backoffice/clients/edit.html.twig',
      [
        'activItem' => 'clients',
        'customer' => $customer,
        'formula' => $formula
      ]
    );
  }

  /**
  * Sauvegarder mise à jour client
  **/
  public function update( $id ) {
    checkLogin();

    Clients::getInstance()->update($id, $_POST);

    redirect('/backoffice/clients/jour');
  }

  /**
  * Supprimer un client
  **/
  public function delete( $id ) {
    checkLogin();

    Clients::getInstance()->delete($id);

    redirect('/backoffice/clients/jour');
  }

  /**
  * Page d'ajout d'un client
  **/
  public function add() {
    checkLogin();
    $formulas = Formula::getInstance()->getAll();

    $this->display(
      'backoffice/clients/add.html.twig',
      [
        'activItem' => 'clients',
        'formulas' => $formulas
      ]
    );
  }

  /**
  * Sauvegarder nouveau client
  **/
  public function save() {
    checkLogin();
    
    Clients::getInstance()->add($_POST);

    redirect('/backoffice/clients/jour');
  }

  /**
  * Recherche des clients sur l'accueil du backoffice
  **/
  public function search( $email, $arrival_date ) {
    checkLogin();
 
    $clients = Clients::getInstance()->search( $email, $arrival_date );

  }

  /**
  * Sauvegarde de nouveau client par qrcode
  **/
  public function saveCustomer( ) {
    Clients::getInstance()->add($_POST);

    redirect('/');
  }
}

?>