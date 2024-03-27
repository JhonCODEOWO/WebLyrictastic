<?php
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');

function incluirTemplate($nombreTemplate){
    include TEMPLATES_URL . "/{$nombreTemplate}.php";
    // echo TEMPLATES_URL . "/{$nombreTemplate}.php";
}

function debuguear($dato){
    echo "<pre>";
    var_dump($dato);
    echo "</pre>";
    exit;
}