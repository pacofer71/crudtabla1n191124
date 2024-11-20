<?php

use App\Db\Articulo;
use App\Db\Categoria;

$cant=0;
do{
    $cant=(int) readline("Dame el número de registros (5-50), '0' para salir: ");
    if($cant==0){
        echo "\nSaliendo a petición del usuario.".PHP_EOL;
        $salir=true;
        break;
    }
    if($cant<5 || $cant>50){
        echo "\nError introduzca un número entre 5 y 50, '0' para salir";
    }
}while($cant<5 || $cant>50);
if($cant!=0){
    require __DIR__."/../vendor/autoload.php";
    Categoria::crearCategoriasRandom();
    Articulo::crearArticulosRandom($cant);
    echo "\n Se han creado $cant artículos.".PHP_EOL;
}