<?php
namespace App\Db;
use \PDO;
use \PDOException;
use stdClass;

class Articulo extends Conexion{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private string $disponible;
    private int $categoria_id;

    public function create(): void{
        $q="insert into articulos(nombre, descripcion, disponible, categoria_id) values(:n, :d, :di, :ci)";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':ci'=>$this->categoria_id,
                ':di'=>$this->disponible,
            ]);
        }catch(PDOException $ex){
            throw new PDOException("Error en crear: {$ex->getMessage()}", -1);
            
        }finally{
            parent::cerrarConexion();
        }
    }
    public function update(int $id): void{
        $q="update articulos set nombre=:n, descripcion=:d, categoria_id=:ci, disponible=:di where id=:i";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':ci'=>$this->categoria_id,
                ':di'=>$this->disponible,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            throw new PDOException("Error en update: {$ex->getMessage()}", -1);
            
        }finally{
            parent::cerrarConexion();
        }
    }

    public static function read(): array{
        $q="select articulos.*, categorias.nombre as nomcat from articulos, categorias 
        where categoria_id=categorias.id order by nomcat";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            throw new PDOException("Error en read: {$ex->getMessage()}", -1);
            
        }finally{
            parent::cerrarConexion();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function getArticuloById(int $id): false|stdClass{
        $q="select * from articulos where id=:i";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            throw new PDOException("Error en getArticulo: {$ex->getMessage()}", -1);
        }finally{
            parent::cerrarConexion();
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
        
    }

    public static function existeNombre(string $nombre, ?int $id=null):bool{
        $q=($id===null) ? "select count(*) as total from articulos where nombre=:n":
        "select count(*) as total from articulos where nombre=:n AND id <> :i";
        $stmt=parent::getConexion()->prepare($q);
        try{
            ($id===null) ? $stmt->execute([':n'=>$nombre]): $stmt->execute([':n'=>$nombre, ':i'=>$id]);
        }catch(PDOException $ex){
            throw new PDOException("Error en existeNombre: {$ex->getMessage()}", -1);            
        }finally{
            parent::cerrarConexion();
        }
        $total=$stmt->fetchAll(PDO::FETCH_OBJ)[0]->total;
        return $total; //cero php lo interpreta como false, cualquier otra cosa como true
    }
    public static function delete(int $id){
        $q="delete from articulos where id=:i";
        $stmt=parent::getConexion()->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            throw new PDOException("Error en borrar: {$ex->getMessage()}", -1);            
        }finally{
            parent::cerrarConexion();
        }
    }

    public static function crearArticulosRandom(int $cant): void{
        $faker=\Faker\Factory::create('es_ES');
        $categoriasId=Categoria::devolverArrayId();
        for($i=0; $i<$cant; $i++){
            $nombre=ucwords($faker->unique()->words(random_int(2,3), true));
            $descripcion=$faker->text();
            $disponible=$faker->randomElement(["SI", "NO"]);
            $categoria_id=$faker->randomElement($categoriasId);
            (new Articulo)
            ->setNombre($nombre)
            ->setDescripcion($descripcion)
            ->setDisponible($disponible)
            ->setCategoriaId($categoria_id)
            ->create();
        }
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of categoria_id
     */
    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     */
    public function setCategoriaId(int $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }

    /**
     * Get the value of disponible
     */
    public function getDisponible(): string
    {
        return $this->disponible;
    }

    /**
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }
}