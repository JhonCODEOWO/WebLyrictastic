<?php
//Funciones de la aplicación como utilidades
class funciones_gral{
    //Recibe el tipo de una imagen y determina que tipo es, devolviendo un dato string con su abreviatura para archivos
    /**
     * Esta función determina el tipo de imagen que envías por submit y devuelve un string con su formato en versión corta .jpg .png...
     *
     * @param string $tipo se refiere al string de type file por ejemplo: image/jpeg.
     * @return string Retorna un dato de tipo string con la extensión del archivo en versión corta
    */
    function determinaTipo($tipo){
        switch ($tipo) {
            case 'image/jpeg':
                return ".jpg";
                break;
            
            case 'image/png':
                return ".png";
                break;
            default:
                return null;
                break;
        }
    }

    function determinarOperacionLyric($comentario){
        if (!$comentario) {
            return "valoracion";
        }else{
            return "comentario";
        } 
    }
}

//Clase con funciones para el servidor y subida de archivos
class funciones_File_Server{
    /**
     * Sube al servidor la imagen cargada por el usuario
     *
     * @param string $carpeta Es la ruta de la ruta que se utilizará en el método, debe ser relativa a la ubicación de este archivo que contiene las funciones.
     * @param string $nombreTmp es el nombre de la nueva imagen seleccionada en tiempo de ejecución mediante el input file, manda el tmp_name aquí.
     * @param string $TipoImg se refiere al tipo de imagen que se está utilizando, debes mandarlo directamente de $_FILE['nombre']['type'].
     * @return string Retorna el nombre con el que se subió la imagen al servidor
     * @note No olvides considerar la ruta en donde se encuentra este archivo para usar el argumento de carpeta.
    */
    function subirImagen($carpeta, $nombreTmp, $TipoImg){
        if (!file_exists($carpeta)) {
            mkdir($carpeta);
        }

        $Funciones = new funciones_gral();
        $nombreImagen = md5(uniqid(rand(), true)).$Funciones -> determinaTipo($TipoImg);

        move_uploaded_file($nombreTmp, $carpeta.$nombreImagen);
        return $nombreImagen;
    }

    /**
     * Reasigna las imagenes al actualizar un registro, al usarse debe tenerse en cuenta la ubicación del archivo que contenga estas funciones
     *
     * @param string $carpeta Es la ruta de la ruta que se utilizará en el método, debe ser relativa a la ubicación de este archivo que contiene las funciones.
     * @param string $NombreImagenAnterior Debe ser un nombre de la imagen anterior regsitrada.
     * @param string $TipoImg se refiere al tipo de imagen que se está utilizando, debes mandarlo directamente de $_FILE['nombre']['type'].
     * @param string $NombreImagenNuevaTmp es el nombre de la nueva imagen seleccionada en tiempo de ejecución mediante el input file, manda el tmp_name aquí.
     * @return string Retorna el nombre con el que se subió la imagen al servidor
     * @note No olvides considerar la ruta en donde se encuentra este archivo para usar el argumento de carpeta.
    */
    function eliminarImagenAnterior_asignarNueva($carpeta, $NombreImagenAnterior, $TipoImg, $NombreImagenNuevaTmp){
        if (!file_exists($carpeta)) {
            mkdir($carpeta);
        }
        
        $rutaAnterior = $carpeta.$NombreImagenAnterior;
        
        //Borra imagen anterior
        unlink($rutaAnterior);

        //Creamos un nombre aleatorio
        $Funciones = new funciones_gral();
        $NuevoNombre = md5(uniqid( rand(), true )).$Funciones -> determinaTipo($TipoImg);

        //Sube la nueva imagen
        move_uploaded_file($NombreImagenNuevaTmp, $carpeta.$NuevoNombre);
        return $NuevoNombre;
    }

    function verificarRuta($ruta){
        if (file_exists($ruta)) {
            if (is_file($ruta)) {
                return "Es un archivo: $ruta";
            } elseif (is_dir($ruta)) {
                return "Es un directorio: $ruta";
            } else {
                return "Es otra cosa: $ruta";
            }
        } else {
            return "La ruta NO existe: $ruta";
        };
    }
}