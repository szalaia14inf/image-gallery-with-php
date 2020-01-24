<?php

//ÚTVONALAK FELVÉTELE A $routes TÖMMBE
route('/', 'homeController');
route('/about', 'aboutController');
route('/image/(?<id>[\d]+)', 'singleImageController');
route('/image/(?<id>[\d]+)/edit', 'singleImageEditController', 'POST');
route('/image/(?<id>[\d]+)/delete', 'singleImageDeleteController', 'POST');

//AZ ÚTVONAL LEKÉRDEZÉSE
$uri = $_SERVER["REQUEST_URI"];
$cleand = explode("?", $uri)[0];

//DISPATCH() FV. MEGHÍVÁSA, AMI KIVÁLASZTJA AZ ADOTT ÚTVONALHOZ TARTOZÓ CONTROLLERT
list($view, $data) = dispatch($cleand, 'notFoundController');
extract($data);






 
