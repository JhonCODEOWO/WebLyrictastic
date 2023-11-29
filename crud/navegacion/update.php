<?php
require '../../includes/classes/funciones.php';
$Funciones = new funciones_gral();
$Funciones_Servidor = new funciones_File_Server();
include '../../includes/funciones.php';
incluirTemplate('header');
$id_user = $_SESSION["id_user"] ?? null;
//Si no hay un usuario se redirecciona al error
if (!$id_user) {
    header('Location: /lyrictasticWithBD/error_page.php?error=2');
}

require '../../includes/config/db.php';
$db = conectarDB();

//Consultar usuario
$query = "SELECT * FROM usuario WHERE nombre = 'Jonathan'";
$resultado = mysqli_query($db, $query);

//Consultar datos actuales
$idLetra = $_GET['id'];
$queryLetra = "SELECT * FROM letras WHERE id = $idLetra";
$resultado = mysqli_query($db, $queryLetra);
$datos = mysqli_fetch_assoc($resultado);

//obtener generos
$queryGeneros = "SELECT * FROM genero";
$resultadoGeneros = mysqli_query($db, $queryGeneros);
// echo "<pre>";
// var_dump($datos);
// echo "</pre>";

//Creamos arreglo para los errores
$errores = [];

//Crear variables de los datos a recibir
$titulo = $datos['titulo'];
$imagen = $datos['imagen'];
$artista_o = $datos['artista_original'];
$letras = $datos['letras'];
$genero_a = $datos['genero_id'];
$escritor_l = '';
$likes = 0;

//Validamos que el método sea POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = mysqli_escape_string(conectarDB(), $_POST['titulo']);
    //Obtenemos la imagen colocada
    $imagen = $_FILES['foto-input'];
    //Generamos un peso en mb para evaluar después.
    $size = 5341987;
    $pesoAMB = round($imagen['size'] / 1000000, 2);
    $artista_o = mysqli_escape_string(conectarDB(), $_POST['artista_original']);
    $letras = mysqli_escape_string($db, $_POST['letra']);
    $escritor_l = $_POST["id_user"];

    if (!$titulo) {
        $errores[] = "Ingresa un título para tus letras";
    }

    // if (!$imagen['name']) {
    //     $errores[] = "Debes agregar una imagen";
    // }

    if ($imagen['size'] > $size) {
        $errores[] = "Tu imagen pesa " . $pesoAMB . "mb el límite es de 15mb";
    }

    if (!$artista_o) {
        $errores[] = "El artista original es obligatorio si no quieres problemas XD";
    }

    if (!$letras) {
        $errores[] = "¡Te olvidaste de colocar la letra!";
    }

    if (empty($errores)) {
        //Si no hay imagen seleccionada entonces...
        if (!$imagen['name']) {
            $queryUpdate = "UPDATE letras SET titulo = '$titulo', artista_original = '$artista_o', letras = '$letras' WHERE id = $idLetra";

            $resultadoUpdate = mysqli_query($db, $queryUpdate);

            if ($resultado) {
                header('Location: /lyrictasticWithBD/crud/index.php');
            }
        } else {
            $carpeta = '../../imagenes/';
            $nombreAnterior = $datos['imagen'];
            $tipoImg = $imagen['type'];
            $tmpName = $imagen["tmp_name"];

            $nombreNuevo = $Funciones_Servidor->eliminarImagenAnterior_asignarNueva($carpeta, $nombreAnterior, $tipoImg, $tmpName);

            $queryUpdate = "UPDATE letras SET titulo = '$titulo', imagen = '$nombreNuevo', artista_original = '$artista_o', letras = '$letras' WHERE id = $idLetra";

            $resultadoUpdate = mysqli_query($db, $queryUpdate);

            if ($resultado) {
                header('Location: /lyrictasticWithBD/crud/index.php');
            }
        }
    }
}
?>

<main class="contenedor">
    <a href="../index.php" class="enlace-btn return-button" id="returnButton">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-arrow-left" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 21a9 9 0 1 0 0 -18a9 9 0 0 0 0 18" />
            <path d="M8 12l4 4" />
            <path d="M8 12h8" />
            <path d="M12 8l-4 4" />
        </svg>
        Volver</a>
    <h1>Escribe los datos correspondientes</h1>
    <?php foreach ($errores as $error) { ?>
        <div class="error">
            <?php echo $error ?>
        </div>
    <?php } ?>
    <form action="" method="POST" class="form-lyric" enctype="multipart/form-data">
        <fieldset>
            <legend>Datos para evitar reclamos de copyright</legend>
            <label for="foto-input">Imagen actual, selecciona otra si lo deseas (Tamaño máximo 5mb)</label>
            <div class="flex-default">
                <div class="contenedor-relative--uploadfile">
                    <div class="contenedor-absolute--uploadfile">
                        <h4 class="hide-element flex-center-vertical-horizontal" style="height: 100%; margin:0;">
                            Clic para seleccionar imagen
                            <input class="hide-element contenedor-absolute--uploadfile" type="file" name="foto-input" id="foto-input" accept="image/png, image/jpeg">
                        </h4>
                    </div>

                    <div style="text-align: center;">
                        <img style="width: 50%;" src="/LyrictasticWithBD/imagenes/<?php echo $imagen ?>" alt="" id="imageSelected">
                    </div>
                </div>

                <div class="flex-column">
                    <label for="titulo">Título original</label>
                    <input type="text" placeholder="Ingresa el nombre" name="titulo" id="titulo" value="<?php echo $titulo ?>">
                    <label for="artista_original">Artista original de la obra</label>
                    <input type="text" name="artista_original" id="artista_original" placeholder="Escribe una artista" value="<?php echo $artista_o ?>">
                    <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                    <label for="genero">Selecciona un género</label>
                    <select name="genero" id="genero">
                        <option value="vacio">--SELECCIONA UNO--</option>
                        <?php foreach ($resultadoGeneros as $genero) { ?>
                            <?php if($genero_a == $genero['id']){ ?>
                                <option selected value="<?php echo $genero['id'] ?>"> <?php echo $genero['nombre_genero'] ?> </option>
                            <?php } ?>
                            <option value="<?php echo $genero['id'] ?>"> <?php echo $genero['nombre_genero'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <!-- <label for="">Imagen actual</label>
            <img style="width:20rem;" src="../../imagenes/<?php echo $imagen ?>" alt="">
            <label for="album">Selecciona una imagen nueva(Tamaño máximo 5mb)</label>
            <input type="file" name="album" id="album">
            <label for="titulo">Título original</label>
            <input type="text" placeholder="Ingresa el nombre" name="titulo" id="titulo" value="<?php echo $titulo ?>">
            <label for="artista_original">Artista original de la obra</label>
            <input type="text" name="artista_original" id="artista_original" placeholder="Escribe una artista" value="<?php echo $artista_o ?>">
            <input type="hidden" name="id_user" value="<?php echo $id_user ?>"> -->
        </fieldset>

        <fieldset>
            <legend>Letra de la canción o traducción</legend>
            <label for="letra">Escribe la letra de la canción</label>
            <textarea name="letra" id="letra" cols="30" rows="10"><?php echo $letras ?></textarea>
        </fieldset>

        <input type="submit" value="Agregar canción" class="btn-principal">
    </form>
</main>

<?php
incluirTemplate('footer');
?>