<?php

function conectarDB() : mysqli{
    $db = mysqli_connect('localhost', 'root', '07092002fake', 'nostalgiclrcs_db');
    
    if (!$db) {
        echo 'Error al conectar';
        exit;
    }

    return $db;
}