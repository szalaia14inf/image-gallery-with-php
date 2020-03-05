<?php

/**
 * controllers.php: Az egyes útvonalakat (route) lekezelő függvények.
 * Minden függvénynek egy tömböt kell visszaadnia, aminek az első eleme a nézet (view)  neve.
 * Második eleme egy assoc tömb, amiben minden kulcs-érték párnak szerepelnie kell, amit a nézet használni fog.
 * return ["viewname", ['key1' => 'value1', 'key2' => 'value2', ...]];
 */

/**
 * notFoundController()
 *
 * @return void
 */
function notFoundController() {
    return [
        '404', 
        [
            'title' => '404'
        ]
    ];
}

/**
 * homeController()
 *
 * @return void
 */
function homeController() {
    /**
     * Query string változók: $_GET[]
     * PHP 7 új operátora: null coalescing operator
     * A ternary operátor (felt ? true : false) és az isset() fv. együttes használatát helyettesíti.
     * A null coalescing operator az első (bal oldali) operandusát adja vissza, ha az létezik és nem null, különben a másodikat (jobb oldalit)
     * Az isset() fv. igazat ad vissza, ha a paraméterül adott változó létezik és nem null (gyakran használatos a $_GET-ben levő változók ellenőrzésére).
     * Tehát az
     *   isset($_GET["size"]) ? $pageSize = $_GET["size"] : $pageSize = 10;
     * helyettesíthető ezzel:
     *   $pageSize = $_GET["size"] ??  10;
     * ami lényegesen tömörebb...
     */
    $size = $_GET["size"] ?? 10;    // $size: lapozási oldalméret
    $page = $_GET["page"] ?? 1;     // $page: oldalszám

    // $connection: Adatbázis kapcsolat
    $connection = getConnection();

    // $total: a képek számának meghatározása
    $total = getTotal($connection);

    // $offset: eltolás kiszámítása
    $offset = ($page - 1) * $size;

    // $content: egy oldalnyi kép
    $content = getPhotosPaginated($connection, $size, $offset);

    /**
    * $lastPage - AZ UTOLSÓ OLDAL SORSZÁMA
    */
    $lastPage = $total % $size == 0 ? intdiv($total, $size) : intdiv($total, $size) + 1;

    return [
        'home',
        [
            'title' => 'Nyitólap',
            'content' => $content,
            'total' => $total,
            'size' => $size,
            'page' => $page,
            'lastpage' => $lastPage
        ]
    ];
}

/**
 * SINGLEIMAGECONTROLLER - EGY DB KÉP MEGJELENÍTÉSE
 *
 * @param [type] $params
 * @return void
 */
function singleImageController($params) {
    $connection = getConnection();
    $picture = getImageById($connection, $params['id']);

    return [
        'singleImage', 
        [
            'title' => 'Kép' .$picture['id'],
            'picture' => $picture
        ]
    ];
}

/**
 * ABOUTCONTROLLER - RÓLUNK GOMB
 */
function aboutController() 
{
   return [
        'about', 
        [
          'title' => 'Rólunk',
        ]
    ];
    
}

/**
 * singleImageEditController - EGY KÉP SZERKESZTÉSE
 *
 * @return void
 */
function singleImageEditController($params) {
    $connection = getConnection();
    $id = $params['id'];
    $title = $_POST['title'];

    updateImage($connection, $id, $title);

    return [
        "redirect:/image/$id",
        []

    ];
    
}

/**
 * singleImageDeleteController - EGY KÉP TÖRLÉSE
 *
 * @return void
 */
function singleImageDeleteController($params) {
    $connection = getConnection();
    $id = $params['id'];
    
    deleteImage($connection, $id);

    return [
        "redirect:/",
        []

    ];
    
}

?>