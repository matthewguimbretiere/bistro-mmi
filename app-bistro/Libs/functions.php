<?php

/**
 * Retourne l'URL complète.
 *
 * @param string $url
 */
function url($url = '') {
  echo APP_ROOT_URL_COMPLETE . $url;
}

/**
 * Redirige vers une commande.
 *
 * @param string $url
 * @return void
 */
function redirect(string $url = '/') {
  header('Location: ' . APP_ROOT_URL_COMPLETE . $url);
}

/**
* Indique si une session administrateur est ouverte.
* @return boolean
**/
function isLogin()
{
	return isset($_SESSION['login']);
}

/**
* Indique si une session administrateur est ouverte
* @return boolean
**/
function checkLogin()
{
	if ( !isLogin() ) {
		redirect( '/login' );
	}
}
