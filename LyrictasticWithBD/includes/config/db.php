<?php

function conectarDB() : mysqli{
    $db = mysqli_connect('localhost', 'root', 'paola06', 'nostalgiclrcs_db');
    
    if (!$db) {
        echo 'Error al conectar';
        exit;
    }

    return $db;
}