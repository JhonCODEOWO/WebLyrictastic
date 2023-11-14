<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../includes/funciones.php';
incluirTemplate('header');
$id_user = $_SESSION["id_user"] ?? null;
if (!$id_user) {
    header('Location: /lyrictasticWithBD/error_page.php?error=2');
}

require '../../includes/config/db.php';
$db = conectarDB();


//Consultar usuario
$query = "SELECT * FROM usuario WHERE nombre = 'Jonathan'";
$resultado = mysqli_query($db, $query);

//obtener generos
$queryGeneros = "SELECT * FROM genero";
$resultadoGeneros = mysqli_query($db, $queryGeneros);

//Creamos arreglo para los errores
$errores = [];

//Crear variables de los datos a recibir
$titulo = '';
$genero = '';
$imagen = '';
$artista_o = '';
$letras = '';
$escritor_l = '';
$likes = 0;
$dislikes = 0;

//Validamos que el método sea POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = mysqli_escape_string(conectarDB(), $_POST['titulo']);
    $genero = $_POST['genero'];
    $imagen = $_FILES['foto-input'];
    $size = 5341987;
    $pesoAMB = round($imagen['size'] / 1000000, 2);
    $artista_o = mysqli_escape_string(conectarDB(), $_POST['artista_original']);
    $letras = mysqli_escape_string($db, $_POST['letra']);
    $escritor_l = $_POST["id_user"];

    if (!$titulo) {
        $errores[] = "Ingresa un título para tus letras";
    }

    if ($genero == "vacio") {
        $errores[] = "Selecciona el genero de tu canción";
    }

    if (!$imagen['name']) {
        $errores[] = "Debes agregar una imagen";
    }

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
        //Subida de archivos
        $carpeta = '../../imagenes/';
        //verificamos si existe
        if (!is_dir($carpeta)) {
            mkdir($carpeta);
        }

        //generamos nombre unico
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        //subir imagen
        move_uploaded_file($imagen["tmp_name"], $carpeta . $nombreImagen);

        //Insertar datos a la tabla
        $queryInsert = "INSERT INTO letras (titulo, imagen, artista_original, letras, likes, dislikes, usuario_id, genero_id) VALUES ('$titulo', '$nombreImagen', '$artista_o', '$letras', '$likes', $dislikes, '$escritor_l', $genero)";

        $resultadoInsert = mysqli_query($db, $queryInsert);
        if ($resultado) {
            header('Location: /lyrictasticWithBD/crud/index.php');
        }
    }
}
?>

<main class="contenedor">
    <a href="../index.php" class="enlace-btn return-button">
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
            <div class="flex-default">
                <div>
                    <label for="album">Imagen de la carátula original(Tamaño máximo 5mb)</label>
                    <!-- <input type="file" name="album" id="album"> -->
                    <div class="contenedor-relative--uploadfile">
                        <div class="contenedor-absolute--uploadfile">
                            <h4 class="hide-element flex-center-vertical-horizontal" style="height: 100%; margin:0;">
                                Clic para seleccionar imagen
                                <input class="hide-element contenedor-absolute--uploadfile" type="file" name="foto-input" id="foto-input" accept="image/png, image/jpeg">
                            </h4>
                        </div>

                        <div style="text-align: center;">
                            <img style="width: 50%;" src="/LyrictasticWithBD/imagenes/user_imgs/<?php echo $rutaImagenActual ?>" alt="" id="imageSelected">
                        </div>
                    </div>
                </div>

                <divn class="flex-column">
                    <label for="titulo">Título original</label>
                    <input type="text" placeholder="Ingresa el nombre" name="titulo" id="titulo">
                    <label for="artista_original">Artista original de la obra</label>
                    <input type="text" name="artista_original" id="artista_original" placeholder="Escribe una artista">
                    <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                    <label for="genero">Selecciona un género</label>
                    <select name="genero" id="genero">
                        <option value="vacio">--SELECCIONA UNO--</option>
                        <?php foreach( $resultadoGeneros as $genero) {?>
                                <option value="<?php echo $genero['id'] ?>"> <?php echo $genero['nombre_genero'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Letra de la canción o traducción</legend>
            <label for="letra">Escribe la letra de la canción</label>
            <textarea name="letra" id="letra" cols="30" rows="10"></textarea>
        </fieldset>

        <input type="submit" value="Agregar canción" class="btn-principal">
    </form>
</main>

<?php
incluirTemplate('footer');
?>