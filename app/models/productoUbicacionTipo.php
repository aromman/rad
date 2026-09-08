<?php
require_once 'crud.php';
class ProductoUbicacionTipo extends Crud
{
  private $id;
  const TABLE='productos_ubicacion_tipo';
  public  $pdo;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  public function __set($name,$value){
    $this->$name=$value;
  }
  public function __get($name){
    return $this->$name;
  }

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (codigo, nombre) VALUES (?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->codigo,
        $this->nombre,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET codigo = :codigo, nombre = :nombre WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':codigo' => $this->codigo,
        ':nombre' => $this->nombre,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>
