<?php
require_once 'crud.php';
class GastosClase extends Crud
{
  public $id;
  const TABLE='gastos_clase';
  public  $pdo;
  public $nombre;
  public $presupuesto;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }


/*Aquí Insertamos un animal, tenemos que crear forzosamente este método porque en el CRUD lo agregamos como **abstract** sino lo agregamos obtendremos un error.*/
  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (nombre, presupuesto) VALUES (?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->nombre,
        $this->presupuesto,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET nombre = :nombre, presupuesto = :presupuesto WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':nombre' => $this->nombre,
        ':presupuesto' => $this->presupuesto,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>