<?php
namespace App\Utils;

use App\Db\Articulo;
use App\Db\Categoria;

class Validaciones{
    public static function sanearCadenas(string $cad): string{
        return htmlspecialchars(trim($cad));
    }

    public static function longitudCadenaValida(string $nomCampo, string $valorCampo, int $lMin, int $lMax): bool{
        if(strlen($valorCampo)<$lMin || strlen($valorCampo)>$lMax){
            $_SESSION["err_$nomCampo"]="*** Error se esperaban entre $lMin y $lMax caracteres";
            return false;
        }
        return true;        
    }
    public static function disponibleValido(string $valor): bool{
        if(!in_array($valor, ["SI", "NO"])){
            $_SESSION['err_disponible']="*** Elija disponibilidad de artículo";
            return false;
        }
        return true;
    }
    public static function categoriaValida(int $cat): bool{
        $categorias=Categoria::devolverArrayId();
        if(!in_array($cat, $categorias)){
            $_SESSION['err_categoria_id']="*** Error categoría inválida o no selleciono ninguna";
            return false;
        }
        return true;
    }

    public static function existeNombre(string $nombre, ?int $id=null): bool{
        if(Articulo::existeNombre($nombre, $id)){
            $_SESSION['err_nombre']="***, Error ya hay un articulo con el nombre: $nombre";
            return true;
        }
        return false;
    }

    public static function pintarError(string $nomError){
        if(isset($_SESSION[$nomError])){
            echo "<p class='text-red-500 text-sm mt-2 italic'>{$_SESSION[$nomError]}</p>";
            unset($_SESSION[$nomError]);
        }
    }
}