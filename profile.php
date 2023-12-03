<?php
include('includes/templates/header.php');
require_once("includes/classes/funciones.php");
require_once("includes/classes/querys_for_users.php");

require('includes/config/db.php');
$db = conectarDB();

//Cargar datos de la sesión
$nombre = $_SESSION["nombre"] ?? null;
$apellidos = $_SESSION["apellidos"] ?? null;
$alias = $_SESSION["alias"] ?? null;
$user_id = $_SESSION["id_user"] ?? null;
$rutaImagenActual = $_SESSION["ruta_img"] ?? null;
$correo = "";
$totalLetras = "";
$errores = [];
$es_visita = false;

//Verificamos que de parte de la query string provenga un valor
$id_query_string = $_GET['id_profile'] ?? null;


//Si alguno de los valores que provienen de sesion son nulos redirigimos a inicio
if ($nombre == null || $apellidos == null || $alias == null) {
    header("Location: index.php");
}

$queryLetras = "SELECT * FROM letras WHERE usuario_id = $user_id";

$resultadoLetras = mysqli_query($db, $queryLetras);
$totalLetras = $resultadoLetras->num_rows;

$queryLikes = "SELECT SUM(likes) AS TotalLikes FROM letras WHERE usuario_id = $user_id";
$resultadoLetras = mysqli_query($db, $queryLikes);
$assocLetras = mysqli_fetch_assoc($resultadoLetras);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["email"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $alias = $_POST["alias"];
    $imagen = $_FILES['foto-input'];

    if (!$correo) {
        $errores[] = "No has colocado un correo, el correo es necesario para validar tu actualización de información";
    }

    if (!$nombre) {
        $errores[] = "Es obligatorio colocar un nombre nuevo o identico al anterior";
    }

    if (!$apellidos) {
        $errores[] = "Es obligatorio colocar un apellido nuevo o identico al anterior";
    }

    if (!$alias) {
        $errores[] = "Coloca un alias nuevo o el anterior";
    }

    //Con el metodo recibimos un resultado, false o el número de filas
    $resultado = obtenerCorreo($db, $user_id, $correo);

    //Si al ejecutar el primer query obtenemos un resultado es porque si fue el correo correcto y si no tiene errores el arreglo entra al if
    if ($resultado != 0 & isset($errores)) {
        //Variables para los métodos
        $carpeta = "imagenes/user_imgs/";
        $tipoImagen = $imagen['type'];
        $nombreTemporal = $imagen['tmp_name'];

        //Invocación de las clases con funciones
        $funciones = new funciones_gral();
        $funciones_server = new funciones_File_Server();
        //Guardamos el nuevo nombre y realizamos la eliminación y asignación de la nueva imagen
        $nuevoNombre = $funciones_server->eliminarImagenAnterior_asignarNueva($carpeta, $rutaImagenActual, $imagen['type'], $imagen['tmp_name']);

        //Realizamos el update
        $resultadoupdate = modificarUsuario($db, $nombre, $apellidos, $alias, $nuevoNombre, $user_id, $imagen);

        //Si se obtenemos true en $resultadoupdate...
        if ($resultadoupdate) {

            $resultadoAll = obtenerDatosUsuarioActual($db, $user_id);
            $user_data  = mysqli_fetch_assoc($resultadoAll);

            //Reiniciamos el arreglo de la superglobal session y asignamos los nuevos valores
            $_SESSION = [];
            $_SESSION["alias"] = $user_data["alias"];
            $_SESSION["nombre"] = $user_data["nombre"];
            $_SESSION["apellidos"] = $user_data["apellidos"];
            $_SESSION["autenticado"] = true;
            $_SESSION["id_user"] = $user_data["id"];
            $_SESSION["ruta_img"] = $user_data["foto"];
        }
        header("Location: profile.php");
    } else {
        $errores[] = "El correo que has ingresado es incorrecto, intenta de nuevo";
    }
}
?>

<main class="contenedor perfil-view no-margin-top padding-bottom">
    <h1 style="padding-top: 2rem;" class="no-margin-top no-margin-bottom">Datos de tu cuenta</h1>
    <p style="text-align:center" class="no-margin-top">Aquí puedes visualizar tu información asi como actualizarla si lo deseas</p>
    <?php foreach ($errores as $error) { ?>
        <div class="alerta"> <?php echo $error ?> </div>
    <?php } ?>
    <div class="profile_info_contenedor">
        <form style="padding: 0 3rem 0 3rem;" action="" method="POST" enctype="multipart/form-data" class="flex-column gap-normal">
            <label for="">Foto de perfil nueva</label>
            <div class="contenedor-relative--uploadfile">
                <div class="contenedor-absolute--uploadfile">
                    <h4 class="hide-element flex-center-vertical-horizontal" style="height: 100%; margin:0;">
                        Clic para seleccionar imagen
                        <input class="hide-element contenedor-absolute--uploadfile" type="file" name="foto-input" id="foto-input" accept="image/png, image/jpeg">
                    </h4>
                </div>

                <div style="text-align: center;">
                    <img class="img-user" style="width: 50%;" src="/LyrictasticWithBD/imagenes/user_imgs/<?php echo $rutaImagenActual ?>" alt="" id="imageSelected">
                </div>
            </div>
            <label for="" class="no-margin">Nombre completo</label>
            <input type="text" name="nombre" value="<?php echo $nombre ?>">
            <input type="text" name="apellidos" value="<?php echo $apellidos ?>">
            <label for="alias" class="no-margin">Alias</label>
            <input type="text" name="alias" id="alias" value="<?php echo $alias ?>">
            <label for="email">Para continuar ingresa tu correo</label>
            <input type="text" name="email" id="email" placeholder="Tu correo con el que te registraste">
            <input id="updateData" style="margin-top: 1rem; " type="submit" value="Actualizar información" class="btn-principal">
        </form>

        <div class="dashboard gap-normal">
            <!-- <h1 style="padding-top: 2rem;" class="no-margin-top no-margin-bottom">Tu contenido</h1>
            <p style="text-align:center" class="no-margin-top">Información acerca de todo el contenido que has subido a la página</p> -->
            <div class="profile-box publicaciones">
                <h2> Letras publicadas </h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                    <path d="M13.5 6.5l4 4" />
                </svg>
                <h2> <?php echo $totalLetras ?> </h2>
            </div>

            <div class="profile-box likes">
                <h2>Likes recibidos</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-thumb-up" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 11v8a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1h3a4 4 0 0 0 4 -4v-1a2 2 0 0 1 4 0v5h3a2 2 0 0 1 2 2l-1 5a2 3 0 0 1 -2 2h-7a3 3 0 0 1 -3 -3" />
                </svg>

                <h2>
                    <?php echo $assocLetras['TotalLikes'] ?>
                </h2>
            </div>

            <div class="profile-box date">
                <h2>Unido desde</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-stats" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                    <path d="M18 14v4h4" />
                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M15 3v4" />
                    <path d="M7 3v4" />
                    <path d="M3 11h16" />
                </svg>
                <h2>28/11/2023</h2>
            </div>

            <div class="profile-box genero-fav">
                <h2>Género favorito</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ear" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 10a7 7 0 1 1 13 3.6a10 10 0 0 1 -2 2a8 8 0 0 0 -2 3a4.5 4.5 0 0 1 -6.8 1.4" />
                    <path d="M10 10a3 3 0 1 1 5 2.2" />
                </svg>
                <h2>BANDA</h2>
            </div>
        </div>
    </div>
</main>

<?php
include('includes/templates/footer.php');
?>