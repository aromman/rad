<?php
require_once 'crud.php';
class ProductoFormato extends Crud
{

  const TABLE='productos_formato';
  public  $pdo;
  public $id;
  public $nombre;
  public $montoObjetivo;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
  }

  public function getByName($name){
    try
    {
        $stm = $this->pdo->prepare("SELECT * FROM productos_formato WHERE nombre=?");
        $stm->execute(array($name));
        if ($stm->rowCount() > 0) {
          return $stm->fetch(PDO::FETCH_ASSOC);
        } else {
          return null;
        }
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
}


/*Aquí Insertamos un animal, tenemos que crear forzosamente este método porque en el CRUD lo agregamos como **abstract** sino lo agregamos obtendremos un error.*/
  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (nombre, monto_objetivo) VALUES (?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->nombre,
        $this->montoObjetivo,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET nombre = :nombre, monto_objetivo = :montoObjetivo WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':nombre' => $this->nombre,
        ':montoObjetivo' => $this->montoObjetivo,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

?>
