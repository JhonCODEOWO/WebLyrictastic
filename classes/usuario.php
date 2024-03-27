<?php

namespace App;

class Usuario{

    protected static $db;
    protected static $columnas_db = ['id', 'nombre', 'apellidos', 'contraseña', 'email', 'foto'];

    public $id;
    public $nombre;
    public $apellidos;
    public $contraseña;
    public $email;
    public $foto;

    public static function setConnection($database){
        self::$db = $database;
    }

    public function __construct($args = [])
    {
        $this -> id = $args['id'] ?? '';
        $this -> nombre = $args['nombre'] ?? '';
        $this -> apellidos = $args['apellidos'] ?? '';
        $this -> contraseña = $args['contraseña'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> foto = $args['foto'] ?? 'SinImagen.jpg';
    }

    public function guardar(){
        $sanitizados = $this -> sanitizarDatos();
        debuguear($sanitizados);
    }

    public function atributos(){
        //Arrelo a mapear por llave y valor
        $atributos = [];

        foreach (self::$columnas_db as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this -> $columna;
        }

        return $atributos;
    }

    public function sanitizarDatos(){
        $atributos = $this -> atributos();
        $sanitizados = [];

        foreach ($atributos as $key => $value) {
            $sanitizados[$key] = self::$db -> escape_string($value);
        }

        return $sanitizados;
    }
}