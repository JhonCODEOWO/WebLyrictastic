<?php

//Requires siempre necesarios
require 'funciones.php';
require 'config/db.php';
require __DIR__.'/../vendor/autoload.php';

//Funciones a usar siempre que se consulta una parte que incluya app
$db = conectarDB();

use App\Usuario;
//Asignamos la conexión en todo momento
Usuario::setConnection($db);