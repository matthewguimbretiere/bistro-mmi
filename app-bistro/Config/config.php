<?php
//
// Fichier : app/Config/config.php
//

// si machine=localhost alors on est en développement ...
if ($_SERVER['SERVER_NAME'] === 'localhost') {
  include 'config.local.php';
  // la constante indique le mode de travail (ici DEVELOPPEMENT)
  define('APP_MODE', 'dev');
} else {
  // ... sinon, on est en production
  include 'config.prod.php';
  // la constante indique le mode de travail (ici PRODUCTION)
  define('APP_MODE', 'prod');
}

/** séparateur entre dossiers */
define('DS', DIRECTORY_SEPARATOR);

/** Chemin absolu vers le dossier du projet. */
define('APP_ROOT_DIRECTORY', realpath(__DIR__ . DS . '..' . DS . '..') . DS);

/** chemin absolu vers le dossier de l'application */
define('APP_SRC_DIRECTORY', APP_ROOT_DIRECTORY . 'app-bistro' . DS);

/** chemin absolu vers le dossier des ressources CSS,JS,IMAGES */
define('APP_ASSETS_DIRECTORY', APP_ROOT_DIRECTORY . DS . 'www' . DS . 'bistro-mmi' . DS . 'assets' . DS);

/** chemin absolu vers le dossier des ressources */
define('APP_STORAGE_DIRECTORY', APP_SRC_DIRECTORY . 'Storage' . DS);

/** URL partielle de l'application */
define('APP_ROOT_URL', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

/** URL complète de l'application en http:// ou https:// */
define(
  'APP_ROOT_URL_COMPLETE',
  (strpos($_SERVER['SERVER_PROTOCOL'], 'HTTP/') !== false ? 'http' : 'https') .
  "://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}" . APP_ROOT_URL
);
