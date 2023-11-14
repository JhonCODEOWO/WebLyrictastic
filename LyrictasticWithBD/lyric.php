<?php
include 'includes/funciones.php';
include 'includes/classes/funciones.php';
$funciones = new funciones_gral();
incluirTemplate('header');

require 'includes/config/db.php';
$db = conectarDB();

//Id del usuario

//Id de la letra
$idRecibido = $_GET['id'] ?? null;

//Consulta en base al id recibido
$query = "SELECT * FROM letras WHERE id = '$idRecibido'";
$resultado = mysqli_query($db, $query);
$resultadoAsso = mysqli_fetch_assoc($resultado);
// echo "<pre>";
// var_dump($resultadoAsso);
// echo "</pre>";

if ($resultado->num_rows == 0) {
    header("Location: error_page.php?error=1");
}

//Manejo de los comentarios
$user_id = $_SESSION["id_user"]  ?? null;
$comentario = "";

//Cargar comentarios a un arreglo
$queryComentarios = "SELECT * FROM comentarios INNER JOIN usuario ON comentarios.usuario_id = usuario.id WHERE letras_id = '$idRecibido'";
$resultadoComentarios = mysqli_query($db, $queryComentarios);
$rows = $resultadoComentarios->num_rows;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($user_id != null) {
        //Almacenamos datos post
        // $like = $_POST["like"] ?? null;
        // $dislike = $_POST["dislike"] ?? null;
        // $omentario = $_POST["comentario"] ?? null;
        $operacion = $_POST["submit"];

        //Usamos la funcion para obtener que se está realizando
        //$operacion = $funciones->determinarOperacionLyric($comentario);

        //En base a la operacion realizamos algo...
        switch ($operacion) {
            case 'Me gusta':
                $date = date("Y-m-d");
                //Obtenemos datos de no gustados en base al id de la letra y el usuario
                $queryNoGustados = "SELECT * FROM no_gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                $resultadoNoGustados = mysqli_query($db, $queryNoGustados);
                //Evaluamos que la letra no haya sido gustada antes.
                $queryGustados = "SELECT * FROM gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                $resultadoGustados = mysqli_query($db, $queryGustados);

                //Si es menor a 1 quiere decir que el like no existia en los me gusta en caso contrario si existia el registro en la tabla
                if ($resultadoGustados -> num_rows < 1 && $resultadoNoGustados -> num_rows < 1) {
                    echo "No existia la letra en la tabla de gustados, se sumará un like y se añadirá al registro";
                    //Primero sumamos a los likes, luego añadimos a gustados
                    $query = "UPDATE letras SET likes = likes + 1 WHERE id = $idRecibido";
                    $añadirLike = mysqli_query($db, $query);

                    //Agregamos la letra gustada a la tabla con el usuario id y letra id y fecha
                    $query = "INSERT INTO gustados(usuario_id, letra_id, date_liked) VALUES ($user_id, $idRecibido, '$date')";
                    $añadirGustado = mysqli_query($db, $query);

                }else if($resultadoNoGustados -> num_rows == 1){
                    echo "Existia la letra en no gustados se eliminará el registro y añadirá uno nuevo a gustados";

                    //Eliminamos el dislike
                    $query = "UPDATE letras SET dislikes = dislikes - 1 WHERE id = $idRecibido";
                    $quitarLike = mysqli_query($db, $query);

                    //Eliminamos el registro de no gustados
                    $query = "DELETE FROM no_gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                    $quitarGustado = mysqli_query($db, $query);

                    //Añadimos un like
                    $query = "UPDATE letras SET likes = likes + 1 WHERE id = $idRecibido";
                    $añadirDislike = mysqli_query($db, $query);

                    //Agregamos la letra gustada a la tabla con el usuario id y letra id y fecha
                    $query = "INSERT INTO gustados(usuario_id, letra_id, date_liked) VALUES ($user_id, $idRecibido, '$date')";
                    $añadirGustado = mysqli_query($db, $query);
                }
                header("Location: lyric.php?id=" . $idRecibido);
                break;

            case 'No me gusta';
                //Evaluamos que la letra no haya sido dislikeada antes.
                $date = date("Y-m-d");
                //Obtenemos datos de no gustados en base al id de la letra y el usuario
                $queryNoGustados = "SELECT * FROM no_gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                $resultadoNoGustados = mysqli_query($db, $queryNoGustados);
                //Obtenemos datos de gustados para verificar si existen posteriormente
                $queryGustados = "SELECT * FROM gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                $resultadoGustados = mysqli_query($db, $queryGustados);

                if ($resultadoNoGustados -> num_rows < 1 && $resultadoGustados -> num_rows < 1) {
                    echo "No existia la letra en la tabla de gustados, se sumará un like y se añadirá al registro";
                    //Añadimos un dislike
                    $query = "UPDATE letras SET dislikes = dislikes + 1 WHERE id = $idRecibido";
                    $añadirDislike = mysqli_query($db, $query);
                    //Agregamos la letra gustada a la tabla con el usuario id y letra id y fecha
                    $query = "INSERT INTO no_gustados(usuario_id, letra_id, date_unliked) VALUES ($user_id, $idRecibido, '$date')";
                    $añadir_no_gustado = mysqli_query($db, $query);

                }else if($resultadoGustados -> num_rows == 1){
                    echo "Se ha dado click a un elemento que ya existia en la tabla de gustados, se eliminará de ahí y asignará a no gustados";
                    //Eliminamos el like
                    $query = "UPDATE letras SET likes = likes - 1 WHERE id = $idRecibido";
                    $quitarLike = mysqli_query($db, $query);
                    //Eliminamos el registro de gustados
                    $query = "DELETE FROM gustados WHERE letra_id = $idRecibido AND usuario_id = $user_id";
                    $quitarGustado = mysqli_query($db, $query);
                    //Añadimos un dislike
                    $query = "UPDATE letras SET dislikes = dislikes + 1 WHERE id = $idRecibido";
                    $añadirDislike = mysqli_query($db, $query);
                    //Añadimos a los no gustados
                    //Agregamos la letra gustada a la tabla con el usuario id y letra id y fecha
                    $query = "INSERT INTO no_gustados(usuario_id, letra_id, date_unliked) VALUES ($user_id, $idRecibido, '$date')";
                    $añadir_no_gustado = mysqli_query($db, $query);
                }
                header("Location: lyric.php?id=" . $idRecibido);
                break;

            case 'comentario':
                $comentario = $_POST["comentario"];
                $query = "INSERT INTO comentarios(texto, usuario_id, letras_id) VALUES ('$comentario', $user_id, $idRecibido)";

                $resultadoInsertarC = mysqli_query($db, $query);

                if ($resultadoInsertarC) {
                    header("Location: lyric.php?id=" . $idRecibido);
                }
                break;

            default:
                # code...
                break;
        }

        //Definimos que operación se está realizando
    } else {
        header("Location: lyric.php?id=" . $idRecibido);
        echo "alert('No puedes agregar comentario sin una cuenta')";
    }
}
?>

<main class="contenedor margin-top-contenedor">
    <a href="discover.php" class="enlace-btn return-button">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-arrow-left" width="32" height="32" viewBox="0 0 24 24" stroke-width="3" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 21a9 9 0 1 0 0 -18a9 9 0 0 0 0 18" />
            <path d="M8 12l4 4" />
            <path d="M8 12h8" />
            <path d="M12 8l-4 4" />
        </svg>
        Volver
    </a>
    <?php foreach ($resultado as $letra) { ?>
        <div class="lyric-info">
            <div class="lyric-info__texto">
                <h1> <?php echo $letra['titulo'] ?> </h1>
                <h4> <?php echo $letra['artista_original'] ?> </h4>
                <div class="flex-default justify-center">
                    <form method="POST" class="flex-default justify-center cancelresponsive">
                        <div class="flex-default cancelresponsive">
                            <button class="valoracion like" type="submit" value="Me gusta" id="like" name="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M313.4 32.9c26 5.2 42.9 30.5 37.7 56.5l-2.3 11.4c-5.3 26.7-15.1 52.1-28.8 75.2H464c26.5 0 48 21.5 48 48c0 18.5-10.5 34.6-25.9 42.6C497 275.4 504 288.9 504 304c0 23.4-16.8 42.9-38.9 47.1c4.4 7.3 6.9 15.8 6.9 24.9c0 21.3-13.9 39.4-33.1 45.6c.7 3.3 1.1 6.8 1.1 10.4c0 26.5-21.5 48-48 48H294.5c-19 0-37.5-5.6-53.3-16.1l-38.5-25.7C176 420.4 160 390.4 160 358.3V320 272 247.1c0-29.2 13.3-56.7 36-75l7.4-5.9c26.5-21.2 44.6-51 51.2-84.2l2.3-11.4c5.2-26 30.5-42.9 56.5-37.7zM32 192H96c17.7 0 32 14.3 32 32V448c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V224c0-17.7 14.3-32 32-32z" />
                                </svg>
                            </button>
                            <h4> <?php echo $resultadoAsso['likes'] ?> </h4>
                        </div>

                        <div class="flex-default cancelresponsive">
                            <button class="valoracion dislike" type="submit" value="No me gusta" id="dislike" name="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M313.4 479.1c26-5.2 42.9-30.5 37.7-56.5l-2.3-11.4c-5.3-26.7-15.1-52.1-28.8-75.2H464c26.5 0 48-21.5 48-48c0-18.5-10.5-34.6-25.9-42.6C497 236.6 504 223.1 504 208c0-23.4-16.8-42.9-38.9-47.1c4.4-7.3 6.9-15.8 6.9-24.9c0-21.3-13.9-39.4-33.1-45.6c.7-3.3 1.1-6.8 1.1-10.4c0-26.5-21.5-48-48-48H294.5c-19 0-37.5 5.6-53.3 16.1L202.7 73.8C176 91.6 160 121.6 160 153.7V192v48 24.9c0 29.2 13.3 56.7 36 75l7.4 5.9c26.5 21.2 44.6 51 51.2 84.2l2.3 11.4c5.2 26 30.5 42.9 56.5 37.7zM32 384H96c17.7 0 32-14.3 32-32V128c0-17.7-14.3-32-32-32H32C14.3 96 0 110.3 0 128V352c0 17.7 14.3 32 32 32z" />
                                </svg>
                            </button>
                            <h4> <?php echo $resultadoAsso['dislikes'] ?> </h4>
                        </div>
                        <!-- <input class="valoracion" type="submit" value="Me gusta" id="like" name="like">
                        <input class="valoracion" type="submit" value="No me gusta" id="dislike" name="dislike"> -->
                    </form>
                </div>
            </div>

            <div class="lyric-info__albumart">
                <img src="imagenes/<?php echo $letra['imagen'] ?>" alt="">
            </div>
        </div>

        <div class="lyrics__content">
            <p> <?php echo nl2br($letra['letras']); ?> </p>
        </div>
    <?php } ?>
</main>

<section class="contenedor">
    <!-- Si un usuario no está logeado entonces... -->
    <?php if ($user_id != null) { ?>
        <h2 class="text-align-left">Escribe tu opinion...</h2>
        <form class="lyric__comentarios" action="" method="POST">
            <input type="text" name="comentario" id="comentario" placeholder="Deja un comentario sobre esta letra">
            <button type="submit" value="comentario" class="btn-principal" name="submit"> Envíar comentario </button>
            <!-- <input class="btn-principal" type="submit" value="Envíar comentario" name="submit"> -->
        </form>
    <?php } else { ?>
        <h2>No puedes comentar sin una cuenta, para crear una haz clic <span><a class="without-transition-a sizeh2" href="login.php">Aquí</a></span> o en iniciar sesión</h2>
    <?php } ?>

    <h2 class="text-align-left">Comentarios</h2>
    <div class="comentarios">
        <?php if ($rows != 0) { ?>
            <?php foreach ($resultadoComentarios as $comentario) { ?>
                <div class="comentario">
                    <div class="comentario__info">
                        <div class="flex-default flex-center-vertical-horizontal">
                            <img src="/LyrictasticWithBD/imagenes/user_imgs/<?php echo $comentario["foto"] ?>" alt="">
                        </div>
                        <h4> <?php echo $comentario["alias"] ?> </h4>
                        <p> <?php echo $comentario["texto"] ?> </p>
                        <form class="flex-default cancelresponsive" method="POST">
                        <button class="valoracion entrada like" type="submit" value="Me gusta" id="like" name="like">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <style>
                                    svg {
                                        fill: #ffffff
                                    }
                                </style>
                                <path d="M313.4 32.9c26 5.2 42.9 30.5 37.7 56.5l-2.3 11.4c-5.3 26.7-15.1 52.1-28.8 75.2H464c26.5 0 48 21.5 48 48c0 18.5-10.5 34.6-25.9 42.6C497 275.4 504 288.9 504 304c0 23.4-16.8 42.9-38.9 47.1c4.4 7.3 6.9 15.8 6.9 24.9c0 21.3-13.9 39.4-33.1 45.6c.7 3.3 1.1 6.8 1.1 10.4c0 26.5-21.5 48-48 48H294.5c-19 0-37.5-5.6-53.3-16.1l-38.5-25.7C176 420.4 160 390.4 160 358.3V320 272 247.1c0-29.2 13.3-56.7 36-75l7.4-5.9c26.5-21.2 44.6-51 51.2-84.2l2.3-11.4c5.2-26 30.5-42.9 56.5-37.7zM32 192H96c17.7 0 32 14.3 32 32V448c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V224c0-17.7 14.3-32 32-32z" />
                            </svg>
                        </button>

                        <button class="valoracion entrada dislike" type="submit" value="No me gusta" id="dislike" name="dislike">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <style>
                                    svg {
                                        fill: #ffffff
                                    }
                                </style>
                                <path d="M313.4 479.1c26-5.2 42.9-30.5 37.7-56.5l-2.3-11.4c-5.3-26.7-15.1-52.1-28.8-75.2H464c26.5 0 48-21.5 48-48c0-18.5-10.5-34.6-25.9-42.6C497 236.6 504 223.1 504 208c0-23.4-16.8-42.9-38.9-47.1c4.4-7.3 6.9-15.8 6.9-24.9c0-21.3-13.9-39.4-33.1-45.6c.7-3.3 1.1-6.8 1.1-10.4c0-26.5-21.5-48-48-48H294.5c-19 0-37.5 5.6-53.3 16.1L202.7 73.8C176 91.6 160 121.6 160 153.7V192v48 24.9c0 29.2 13.3 56.7 36 75l7.4 5.9c26.5 21.2 44.6 51 51.2 84.2l2.3 11.4c5.2 26 30.5 42.9 56.5 37.7zM32 384H96c17.7 0 32-14.3 32-32V128c0-17.7-14.3-32-32-32H32C14.3 96 0 110.3 0 128V352c0 17.7 14.3 32 32 32z" />
                            </svg>
                        </button>
                        <!-- <input class="valoracion" type="submit" value="Me gusta" id="like" name="like">
                        <input class="valoracion" type="submit" value="No me gusta" id="dislike" name="dislike"> -->
                    </form>
                    </div>
                    <div class="comentario__botones">
                    
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <h2>No hay aún comentarios para esta canción</h2>
        <?php } ?>
    </div>
    </div>
</section>

<?php
incluirTemplate('footer');
?>