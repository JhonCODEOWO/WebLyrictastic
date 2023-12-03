//Variables
let mostrar = false;

const btnProfile = document.querySelector('#btnProfile');
const ulOpcionesProfile = document.querySelector('#ulOpcionesProfile');
const btnProfileMovil = document.querySelector
('#btnMenuMovil');

const btnReturn = document.querySelector('#returnButton');
console.log(btnReturn);

headerMovilOpcion = document.querySelector('#header_movil--opciones');

const fileInputImage = document.querySelector('#foto-input');
const imageSelected = document.querySelector('#imageSelected');
console.log(imageSelected);

//Javascript para la carga de la página
window.addEventListener('DOMContentLoaded', function (evento) {

    //En el evento carga se muestra ahora si el body,
    window.onload = function(){
        document.body.style.display = "block";
    }

    //Verifica que exista una imagen seleccionada, si no...
    if (imageSelected.getAttribute("src") == "") {
        // console.log("Sin imagen");
        imageSelected.src = '/LyrictasticWithBD/imgs/ui/sinimagen.png';
    }

    //Agregamos el event listener al botón del usuario
    btnProfile.addEventListener('click', function (event) {
        //Prevenimos que actué su accion por defecto
        event.preventDefault();

        if (mostrar != true) {
            btnProfile.classList.remove('no-margin-top');
            btnProfile.classList.add('margin-top');
            ulOpcionesProfile.classList.remove('ocultar');
            ulOpcionesProfile.classList.add('mostrar');
            mostrar = true;
            console.log('En if');
        } else {
            ulOpcionesProfile.classList.remove('mostrar');
            ulOpcionesProfile.classList.add('ocultar');
            btnProfile.classList.add('no-margin-top');
            btnProfile.classList.remove('margin-top');
            mostrar = false;
            console.log('En else');
        }
    });
});

//Funciones
//Mostrar/ocultar menú del perfil

//Determinar en que dispositivo estamos..
window.addEventListener('resize', function (evento) {
    let windowWidth = window.innerWidth;
    console.log(windowWidth);
    if (windowWidth > 768) {
        btnProfile.removeEventListener('click');
        btnProfile.addEventListener('click', function (event) {
            //Prevenimos que actué su accion por defecto
            event.preventDefault();
            if (mostrar != true) {
                btnProfile.classList.remove('no-margin-top');
                btnProfile.classList.add('margin-top');
                ulOpcionesProfile.classList.remove('ocultar');
                ulOpcionesProfile.classList.add('mostrar');
                mostrar = true;
                console.log('En if');
            } else {
                ulOpcionesProfile.classList.remove('mostrar');
                ulOpcionesProfile.classList.add('ocultar');
                btnProfile.classList.add('no-margin-top');
                btnProfile.classList.remove('margin-top');
                mostrar = false;
                console.log('En else');
            }
        });
    } else {
        btnProfileMovil.removeEventListener('click');
        btnProfileMovil.addEventListener('click', function (elemento) {
            if (mostrar != true) {
                headerMovilOpcion.classList.remove('ocultar');
                headerMovilOpcion.classList.add('mostrar');
                mostrar = true;
            } else {
                headerMovilOpcion.classList.remove('mostrar');
                headerMovilOpcion.classList.add('ocultar');
                mostrar = false;
            }
        });
    }
});



//Cierre de header y footer


//profile.php
//event listener que cambia la imagen actual por la que se ha seleccionado, pendiente de crear un método para esto
fileInputImage.addEventListener('input', function (evento) {
    //Seleccionamos la imagen cargada por el usuario y la almacenamos en una variable
    let file = fileInputImage.files[0];
    //Creamos una URL temporal para acceder a la imagen
    const imageUrl = URL.createObjectURL(file);
    //Obtenemos dimensiones de la imagen
    const image = new Image();
    image.src = imageUrl;
    image.onload = function(){
        let width = image.width;
        let height = image.height
        console.log(width);

        if (width == height) {
            //Cargamos el url al elemento
            imageSelected.src = imageUrl;
            console.log(imageSelected);
        }else{
            //Crear elementos
            const advertencia = document.createElement('DIV');
            const tituloAdvertencia = document.createElement('H2');
            const textoAdvertencia = document.createElement('P');
            const boton = document.createElement('BUTTON');

            //Clases para los elementos
            advertencia.classList.add('advertencia');
            boton.classList.add('btn-principal');
            boton.classList.add('btn-warning');

            //Añadido de información a los elementos
            tituloAdvertencia.textContent = "Advertencia";
            textoAdvertencia.textContent = "La imagen que seleccionaste no se puede usar. \n Recomendación: Usa una imagen con altura y ancho iguales";
            boton.textContent = "Aceptar";

            //Si hay botones aqui se añaden sus listeners
            boton.addEventListener('click', function(evento){
                fileInputImage.scrollIntoView({ behavior: 'smooth', block: 'center'});
                advertencia.classList.add('ocultar');
                fileInputImage.value = '';
            });

            //Añadir elementos
            advertencia.appendChild(tituloAdvertencia);
            advertencia.appendChild(textoAdvertencia);
            advertencia.appendChild(boton);
            document.body.appendChild(advertencia);

            //Mover vista hasta el elemento
            advertencia.scrollIntoView({ behavior: 'smooth', block: 'center' });

            console.log('Imagen invalida');
        }
    }
});

//-------------------------------------------------------

// Retornar al anterior registro del navegador
btnReturn.addEventListener('click', function(){
    console.log('boton');
    window.history.back();
});


//Recargar página
const updateData = document.querySelector('#updateData');