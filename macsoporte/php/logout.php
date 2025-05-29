<?php
/* Se inicia sesion para manejar las variables de sesion */
session_start(); 

/* Se eliminan todas las variables de sesion */
session_unset(); 

/* Se destruye la sesion completamente */
session_destroy(); 
header("Location: ../index.html"); 
exit();

