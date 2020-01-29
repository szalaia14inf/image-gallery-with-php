<?php

//AZ ÚTVONAL (AKTUÁLIS) KISZEDÉSE AZ URL-BŐL
$uri = $_SERVER["REQUEST_URI"] ?? '/';

/** @var type $cleand */
$cleand = explode("?", $uri)[0];

//DISPATCH() FV. MEGHÍVÁSA, AMI KIVÁLASZTJA AZ ADOTT ÚTVONALHOZ TARTOZÓ CONTROLLERT
list($view, $data) = dispatch($cleand, 'notFoundController');
extract($data);