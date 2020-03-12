<?php

/**
 *  $title: IDE ÍRD AZ ALKALMAZÁS NEVÉT
 */
$siteName = "Májsztró's Gallery";

/**
 *  $possiblePageSize: lehetséges PAGESIZE
 */
$possiblePageSize = [5, 10, 20, 50, 100];

$config['db_host'] = 'localhost';
$config['db_user'] = 'phpalapok';
$config['db_pass'] = 'phpalapok';
$config['db_name'] = 'phpalapok';

/**
 * $routes - ÚTVONALAKAT TÁROLÓ TÖMB
 */
$routes = [];

//ÚTVONALAK FELVÉTELE A $routes TÖMBBE
$routes['GET']['/'] = 'homeController';
$routes['GET']['/about'] = 'aboutController';
$routes['GET']['/image/(?<id>[\d]+)'] = 'singleImageController';
$routes['POST']['/image/(?<id>[\d]+)/edit'] = 'singleImageEditController';
$routes['POST']['/image/(?<id>[\d]+)/delete'] = 'singleImageDeleteController';

$routes['GET']['/login'] = 'loginFormController';
$routes['POST']['/login'] = 'loginSubmitController';
$routes['GET']['/logout'] = 'logoutSubmitController';

?>