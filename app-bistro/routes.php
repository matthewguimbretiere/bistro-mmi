<?php
//== la page d'accueil ========================================
$route->addRoute('GET', '/', 'FrontController@index');
$route->addRoute(['GET','POST'],'/backoffice/formules/update/{id:[0-9]+}', 'FormulaController@update');
// == Authentification=========================================
$route->addRoute('GET', '/login', 'BackOfficeController@login');
$route->addRoute('POST', '/login', 'BackOfficeController@loginCheck');
$route->addRoute('GET', '/delog', 'BackOfficeController@delog');
$route->addRoute('GET', '/backoffice', 'BackOfficeController@index');
// == Formules ==============================================
$route->addRoute('GET','/backoffice/formules', 'FormulaController@index');
$route->addRoute('GET','/backoffice/formules/effacer/{id:[0-9]+}', 'FormulaController@delete');
$route->addRoute('GET','/backoffice/formules/ajouter', 'FormulaController@add');
$route->addRoute('POST','/backoffice/formules/ajouter', 'FormulaController@save');
$route->addRoute('GET','/backoffice/formules/editer/{id:[0-9]+}', 'FormulaController@edit');
// == Localisation ============================================
$route->addRoute('GET','/backoffice/plan-table', 'LocalisationController@index');
$route->addRoute('GET','/backoffice/plan-table/effacer/{id:[0-9]+}', 'LocalisationController@delete');
$route->addRoute('POST','/backoffice/plan-table/ajouter', 'LocalisationController@save');
$route->addRoute('GET','/backoffice/plan-table/editer/{id:[0-9]+}', 'LocalisationController@edit');
$route->addRoute('POST','/backoffice/plan-table/editer/{id:[0-9]+}', 'LocalisationController@update');
// == Clients ===============================================
$route->addRoute('GET','/backoffice/clients/injection', 'ClientsController@injection');
$route->addRoute('GET','/backoffice/clients/add', 'ClientsController@add');
$route->addRoute('POST','/backoffice/clients/add', 'ClientsController@save');
$route->addRoute('GET','/backoffice/clients/edit/{id:[0-9]+}', 'ClientsController@edit');
$route->addRoute('POST','/backoffice/clients/edit/{id:[0-9]+}', 'ClientsController@update');
$route->addRoute('GET','/backoffice/clients/delete/{id:[0-9]+}', 'ClientsController@delete');
$route->addRoute('GET','/backoffice/clients/search/{email}/{arrival_date}', 'ClientsController@search');
$route->addRoute('GET','/backoffice/clients/{filtre}', 'ClientsController@index');
// == Graphique ===============================================
$route->addRoute('GET','/backoffice/graphique/{filtre}', 'GraphicController@index');
// == Settings ==============================================
$route->addRoute('GET','/backoffice/settings',"BackOfficeController@settings");
$route->addRoute(['GET','POST'],'/backoffice/settings/{id:[0-9]+}',"BackOfficeController@settingsSave");
// == Ajout de client ========================================
$route->addRoute('GET','/client/table/{table}',"FrontController@addCustomer");
$route->addRoute('POST','/client/table',"ClientsController@saveCustomer");
// == Front ========================================
$route->addRoute('GET','/front/formula-description/{id:[0-9]+}',"FormulaController@getDescriptionById");
?>
