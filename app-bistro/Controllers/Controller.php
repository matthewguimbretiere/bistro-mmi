<?php

namespace App\Controllers;

class Controller {

  protected $twig;

  public function __construct() {
    global $twig;
    $this->twig = $twig;
  }

  /**
   * Pour afficher une vue.
   * @param  string $vue    nom de la vue.
   * @param  array  $params paramètres de la vue.
   */
  public function display(string $vue, array $params = []) {
    $this->twig->display($vue, $params);
  }

/**
 * Pour produire une réponse au format JSON.
 * @param  array  $datas les données à transmettre.
 */
  public function json(array $datas = []) {
    header('Content-Type: application/json');
    return json_encode($datas);
  }

}
