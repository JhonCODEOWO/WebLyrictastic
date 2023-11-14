<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'includes/funciones.php';
    incluirTemplate('header');

    require 'includes/config/db.php';
    $db = conectarDB();

    $busqueda = "";
    $search_string = "";
    $errores = [];
    $resultado = "";

    //Realizar consulta de los géneros
    $queryGeneros = "SELECT * FROM genero";
    $resultadoGenero = mysqli_query($db, $queryGeneros);
    $arreglo = mysqli_fetch_all($resultadoGenero);


    //SI usamos el request method post es una busqueda, entonces..
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $division_nameSubmit = explode("_", $_POST["submit"]);
        $tipo = $division_nameSubmit[0];
        //Si el arreglo subdividio tiene más de un elemento...
        if (count($division_nameSubmit) > 1) {
            $dato = $division_nameSubmit[1];
        }
        // echo "<pre>";
        // var_dump($division_nameSubmit);
        // echo "<pre>";
        // echo count($division_nameSubmit);
        // exit;
        switch ($tipo) {
            case 'Buscar':
                $search_string = $_POST["busqueda"];
                if ($search_string == "") {
                $errores[] = "No has ingresado nada en la busqueda";
                }
                if (empty($errores)) {
                $query = "SELECT letras.id AS id_letra, usuario.id AS id_usuario, letras.*, usuario.* FROM letras inner join usuario on usuario.id = letras.usuario_id WHERE titulo LIKE '%$search_string%'";
        
                $resultado = mysqli_query($db, $query);
                $busqueda = "Canciones que coinciden con: $search_string";
                }
                break;
            
            case 'Genero':
                $search_string = $dato;
                $query = "SELECT letras.id AS id_letra, usuario.id AS id_usuario, letras.*, usuario.* FROM letras inner join usuario on usuario.id = letras.usuario_id WHERE genero_id = $search_string";
                $resultado = mysqli_query($db, $query);
                break;
            default:
                header("Location: LyrictasticWithBD/discover.php");
                break;
        }
    }else{
        //Unimos ambas tablas para mostrar letras creadas y usuarios que las subieron dos de ellas llevan alias para poder usarlas
        $query = "SELECT letras.id AS id_letra, usuario.id AS id_usuario, letras.*, usuario.* FROM letras inner join usuario on usuario.id = letras.usuario_id;";
        $resultado = mysqli_query($db, $query);
    }
?>

    <main class="main-index main-discover">
        <!-- <h1 class="no-margin margin-bottom-elemento">Descubre letras nuevas diariamente agregadas por los usuarios</h1> -->
        <form class="buscador-letras" action="" method="POST">
            <input class="bg-difuminado" type="text" name="busqueda" id="busqueda" placeholder="Busca una canción, artista o género">
            <input type="submit" value="Buscar" class="btn-principal" name="submit">
        </form>
    </main>

    <form class="discover--generos flex-default cancelresponsive" method="POST">

        <?php foreach($resultadoGenero as $genero){ ?>
            <div class="genero--nav">
                <button class="btn-genero" type="submit" name="submit" value="Genero_<?php echo $genero['id'] ?>"> <?php echo $genero['nombre_genero'] ?> </button>
            </div>
        <?php } ?>
    </form>

    <section class="contenedor">
        <?php if ($busqueda != "" || $search_string != "") { ?>
            <h3><?php echo $busqueda?></h3>
            <h4> 
                <a class="without-transition-a" href="discover.php">Volver a mostrar todas las letras</a>
            </h4>
        <?php } else { ?>
            <?php foreach($errores as $error) { ?>
                <h2>NO HAS INGRESADO ALGO EN LA BUSQUEDA</h2>
            <?php }?>
        <?php } ?>
        <!-- Recorremos con un foreach los resultados y usamos como background image e image la ruta basandonos en el nombre de la imagen en la bd asi como mostramos los demás datos -->
        <!-- NOTA: por cada elemento creado en el elemento a con ayuda de php añadimos un query string que usa la columna con alias letras_id y que usaremos en la página lyric para poder acceder -->
        <div class="letras letras--discover margin-top-contenedor">
            <?php if ($resultado != "") { ?>
                <?php foreach($resultado as $letra) { ?>
                    <div class="letra-tarjeta" style="background-image: url(imagenes/<?php echo $letra['imagen']; ?>);">
                        <div class="letra-tarjeta-content-blur">
                            <a class="without-transition-a" href="lyric.php?id=<?php echo $letra['id_letra']; ?>">
                                <img src="imagenes/<?php echo $letra['imagen']; ?> " alt="imagen_cancion">
                                <h3> <?php echo $letra['titulo']; ?> </h3>
                                <h3 class="light"> <?php echo $letra['artista_original'] ?></h3>
                                <h4>Letra escrita aquí por:</h4>
                                <h3 class="light"> <?php echo $letra['nombre']; ?> </h>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </section>

    
<?php
 incluirTemplate('footer');
?>