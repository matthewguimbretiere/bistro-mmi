<?php

namespace App\Controllers;

use App\Models\Clients;
use App\Models\Formula;
use App\Models\Graphic;

class GraphicController extends Controller {
  /**
  * Affichage du graphique
  **/
  public function index( $filtre ) {
    checkLogin();

    // Récupération des dates de la semaine
    $dates = Graphic::getInstance()->getDates7();
    // Récupération des données ( en fonction des jours de la semaine )
    $datas = Graphic::getInstance()->getAllFiltered($filtre, $dates);

    $countFormulas = Graphic::getInstance()->countFormulas();

    try {
       switch ($filtre) {
          case 'clients':
              $this->display(
                'backoffice/graphic/clients.html.twig',
                [
                  'activItem' => 'graphic',
                  'filter' => $filtre,
                  'dates_week' => $dates,
                  'datas' => $datas
                ]
              );
              break;
          case 'formules':
              $this->display(
                'backoffice/graphic/formules.html.twig',
                [
                  'activItem' => 'graphic',
                  'filter' => $filtre,
                  'dates_week' => $dates,
                  'datas' => $datas,
                  'countDatas' => $countFormulas
                ]
              );
              break;
          case 'ca':
              $this->display(
                'backoffice/graphic/ca.html.twig',
                [
                  'activItem' => 'graphic',
                  'filter' => $filtre,
                  'dates_week' => $dates,
                  'datas' => $datas
                ]
              );
              break;
          case 'formules':
              $this->display(
                'backoffice/graphic/tous.html.twig',
                [
                  'activItem' => 'graphic',
                  'filter' => $filtre,
                  'dates_week' => $dates,
                  'datas' => $datas
                ]
              );
              break;
          default:
              $this->display(
                'backoffice/graphic/tous.html.twig',
                [
                  'activItem' => 'graphic',
                  'filter' => $filtre,
                  'dates_week' => $dates,
                  'datasClients' => $datas['clients'],
                  'datasFormules' => $datas['formules'],
                  'datasCa' => $datas['ca'],
                  'countFormulas' => $countFormulas
                ]
              );
              break;
         }
    } catch(Exception $e){
        
    }
  }
}

?>