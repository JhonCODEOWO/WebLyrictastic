<?php

function agregarUsuario($db, $nombre, $apellidos, $contraseña_hash, $email, $alias, $nombreImagen){
    //Creamos un query como sentencia preparada
    $query = "INSERT INTO usuario(nombre, apellidos, contraseña, email, alias, foto)VALUES (?, ?, ?, ?, ?, ?)";
    //Preparamos la sentencia
    $stmt = $db -> prepare($query);

    if ($stmt) {
        //Vinculamos los parámetros
        $stmt -> bind_param("ssssss", $nombre, $apellidos, $contraseña_hash, $email, $alias, $nombreImagen);

        //Ejecutamos el query dentro de un try catch para recibir falso si hay un error en la ejecución o true si funcionó
        try{
            $stmt -> execute();
            return true;
            //Cerramos el statement y la conexión
        }catch(mysqli_sql_exception $e){
            echo $e -> getMessage();
            return false;
            //Cerramos el statement y la conexión
        }
    }else{
        return false;
    }
}

// Funciones usadas dentro de profile.php
//Hace una busqueda en la base de datos, si el correo se encuentra devuelve un numero de filas, el cual siempre será 1 en caso contrario devuelve un false
/**
     * Esta función realiza una consulta a la base de datos en base al usuario recibido y su correo, al realizar la transacción se retorna el resultado en numero de filas, en caso de alguna falla retorna un false
     *
     * @param mysql $db es necesaria una instancia de base de datos.
     * @param int $user_id es necesario un id de un registro de la base de datos en la tabla usuario.
     * @param string $correo representa el correo a buscar en la base de datos.
     * @return string Retorna un dato de tipo string con la extensión del archivo en versión corta
    */
function obtenerCorreo($db, $user_id, $correo){
    //Creamos el query como sentencia preparada
    $query = "SELECT * FROM usuario WHERE id = ? AND email = ?";

    $stmt = $db -> prepare($query);
    if ($stmt) {
        $stmt -> bind_param("is", $user_id, $correo);

        try{
            $stmt -> execute();
            // Obtener el resultado en un conjunto de resultados
            $result = $stmt -> get_result();
            return $result -> num_rows;
            //Cerramos el statement y la conexión
        }catch(mysqli_sql_exception $e){
            return false;
            //Cerramos el statement y la conexión
        }
    }else{
        //Si falla la ejecución devuelve un falso
        return false;
    }
}

function modificarUsuario($db, $nombre, $apellidos, $alias, $nombreImagen, $user_id, $imagen){
    //Verificamos si la imagen recibida tiene un nombre
    if (strlen($imagen['name']) > 0) {
        //Creamos el query como sentencia preparada
        $query = "UPDATE usuario SET nombre = ?, apellidos = ?, alias = ?, foto = ? WHERE id = ?";

        //Preparamos la sentencia
        $stmt = $db -> prepare($query);

        if ($stmt) {
            $stmt -> bind_param("ssssi", $nombre, $apellidos, $alias, $nombreImagen, $user_id);

            try {
                $stmt -> execute();
                return true;
            } catch (mysqli_sql_exception $e) {
                return false;
            }
        }else{
            return false;
        }
    }else{
        $query = "UPDATE usuario SET nombre = ?, apellidos = ?, alias = ? WHERE id = ?";

        $stmt = $db -> prepare($query);

        if ($stmt) {
            $stmt -> bind_param("sssi", $nombre, $apellidos, $alias, $user_id);
            try {
                $stmt -> execute();
                return true;
            } catch (mysqli_sql_exception $e) {
                return false;
            }
        }else{
            return false;
        }
    }
    
}

function obtenerDatosUsuarioActual($db, $user_id){
    $queryAll = "SELECT * FROM usuario where id = ?";

    $stmt = $db -> prepare($queryAll);

    if ($stmt) {
        $stmt -> bind_param("i", $user_id);
        $stmt -> execute();
        $result = $stmt -> get_result();
        return $result;
    }else{
        //Si falla el stmt entonces retornamos un false
        return false;
    }
}