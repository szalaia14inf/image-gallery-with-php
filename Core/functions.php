<?php

/**
 * LAPOZÁST MEGVALÓSÍTÓ FÜGGVÉNY
 *
 * @param [int] $total
 * @param [int] $currentPage
 * @param [int] $size
 * @return [string]
 */
function paginate($total, $currentPage, $size) {
    $page = 0;
    $markup = "";

//ELÖZŐ OLDAL
if($currentPage > 1){
    $prevPage = $currentPage -1;
    $markup .=
    "<li class=\"page-item\">
         <a class =\"page-link\" href=\"?size=$size&page=$prevPage\">Prev</a>
    </li>";
} else {
    $markup .= 
    "<li class=\"page-item disabled\">
         <a class=\"page-link\" href=\"#\">Prev</a>
    </li>";
}
   
    //SZÁMOZOTT LAPOK
    for ($i=0; $i < $total; $i += $size) {
        $page++;
        $activeClass = $currentPage == $page ? "active" : "";
        $markup .=
        "<li class=\"page-item $activeClass\">
    <a class=\"page-link\" href=\"?size=$size&page=$page\">$page</a>
    </li>";
    }
    
      //KÖVETKEZŐ OLDAL
      if($currentPage < $page){
        $nextPage = $currentPage +1;
        $markup .=
        "<li class=\"page-item\">
        <a class =\"page-link\" href=\"?size=$size&page=$nextPage\">Buzi Áron</a>
        </li>";
    } else {
        $markup .= 
        "<li class=\"page-item disabled\">
        <a class=\"page-link\" href=\"#\">Next</a>
        </li>";
    }

    return $markup;

}

/**
 * Hiba esetén megjeleníti a templates/error.php oldalt
 *
 * @return void
 */
function errorPage() {
    include "templates/error.php";
    die();
}

/**
 * Kiír egy hibát a logfájlba (logs/application.log)
 *
 * @param [string] $level Hiba szint.
 * @param [string] $message Hibaüzenet szövege.
 * @return void
 */
function logMessage($level, $message) {
    $file = fopen("logs/application.log", "a");
    fwrite($file, "[$level] " . date("Y-m-d H:i:s") . " $message" . PHP_EOL);
    fclose($file);
}

/**
 * ADATBÁZIS KAPCS.
 *
 * @return $connection
 */
function getConnection() {
  global $config;
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if (mysqli_connect_errno($connection)) {
    die (mysqli_connect_error());
    logMessage("ERROR", 'Failed to connect to MySQL: ' . mysqli_connect_error());
 }

 return $connection;

}

/**
 * getTotal() a képek számának meghatározása
 *
 * @param [type] $connection MySQL kapcsolat
 * @return [int] A képek száma
 */
function getTotal($connection) {
    $query = "SELECT count(*) AS count FROM photos";
    if ($result = mysqli_query($connection, $query)) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * Egy oldalnyi képet ad vissza az adatbázisból, a lapméret és az eltolás alapján.
 *
 * @param [type] $connection MySQL kapcsolat
 * @param [int] $size Lapméret
 * @param [int] $offset Eltolás
 * @return void MYSQLI_ASSOC
 */
function getPhotosPaginated($connection, $size, $offset) {
    $query = "SELECT * FROM photos LIMIT ?, ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "ii", $offset, $size);
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }   
}

/**
 * EGY KÉPET AD VISSZA, AZONOSÍTÓ ALAPJÁN
 *
 * @param [type] $connection
 * @param integer $id
 * @return void
 */
function getImageById($connection, $id) {
    $query = "SELECT * FROM photos WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $id);
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        return mysqli_fetch_assoc($result);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * Egy route (útvonal) és a hozzá tartozó kezelő függvény hozzáadása a $routes assoc tömbhöz.
 *
 * @global
 * @param	mixed 	$route   	Útvonal
 * @param	mixed 	$callable	Handler function (Controller)
 * @param	string	$method  	Default: "GET"
 * @return	void
 */
function route($route, $callable, $method = "GET") {
    global $routes;
    //$route = "%^$route$%";
    $routes[strtoupper($method)][$route] = $callable;
}

/**
 * Az aktuális útvonalhoz tartozó kezelő függvény (CONTROLLER) KIKERESÉSE, meghívása
 *
 * @param [string] $actualRoute Az aktuális útvonal
 * @return [bool] true - találat esetén | false egyébként
 */
function dispatch($actualRoute, $notFound) {
    global $routes;
    $method = $_SERVER["REQUEST_METHOD"];   // POST GET PATH DELETE
    if (key_exists($method, $routes)) {
        foreach ($routes[$method] as $route => $callable) {
            $route = "%^$route$%";
            if (preg_match($route, $actualRoute, $matches)) {
                return $callable($matches);
            }
        }
    }
    
    return $notFound();
}

/**
 * updateImage() - EGY KÉP LEÍRÁSÁNAK MÓDOSÍTÁSA, AZONOSÍTÓ ALAPJÁN
 *
 * @param [type] $connection
 * @param [type] $id
 * @param [type] $title
 * @return void
 */
function updateImage($connection, $id, $title) {
    $query = "UPDATE photos SET title = ? WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "si", $title, $id);
        mysqli_stmt_execute($statment);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * deleteImage() - EGY KÉP TÖRLÉSE, AZONOSÍTÓ ALAPJÁN
 */
function deleteImage($connection, $id) {
    $query = "DELETE FROM photos WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $id);
        mysqli_stmt_execute($statment);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * FELHASZNÁLÓ LÉTREHOZÁSA
 *
 * @return void
 */
function createUser() {

    $loggedIn = array_key_exists('user', $_SESSION);

    return [
        'loggedIn' => $loggedIn,
        'name' => $loggedIn ? $_SESSION['user']['name'] : null
    ];   
}
/**
 * BEJELENTKEZÉS
 */
function loginUser($connection, $email, $password)
{
    $query = "SELECT name, password FROM photos_users WHERE email = ?";

    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "s", $email);
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);

        if ($record != null && password_verify($password, $record['password'])) {
            return $record;
        } else {
            return null;
        }
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}