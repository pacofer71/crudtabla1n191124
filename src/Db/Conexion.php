<?php
namespace App\Db;

use \PDO;
use \PDOException;

class Conexion{
    private static ?PDO $conexion=null;

    protected static function getConexion(): PDO{
        if(self::$conexion==null) self::setConexion();
        return self::$conexion;
    }

    private static function setConexion(): void{
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();
        
        $user=$_ENV['USER'];
        $pass=$_ENV['PASS'];
        $host=$_ENV['HOST'];
        $db=$_ENV['DB'];
        $port=$_ENV['PORT'];

        $dsn="mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
        $options=[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT=>true
        ];
        try{
            self::$conexion=new PDO($dsn, $user, $pass, $options);
        }catch(PDOException $ex){
            die("Error en conexion: ".$ex->getMessage());
        }


    }

    protected static function cerrarConexion(): void{
        self::$conexion=null;
    }
}