<?php

echo password_hash('password', PASSWORD_BCRYPT);

die();

require_once "Core/functions.php";
require_once "Core/controllers.php";
require_once "Core/config.php";
require_once "Core/app.php";
require_once "Templates/layout.php";

?>