<?php
require_once 'crud.php';
require_once "logger.php";

class Sueldo extends Crud
{
  const TABLE='sueldos';
  public $pdo;
  public $logger;
  public $id;
  public $empleadoId;
  public $fecha;
  public $monto;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
    $this->logger = new Logger();
  }

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (empleado_id, fecha, monto) VALUES (?,?,?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->empleadoId,
        $this->fecha,
        $this->monto
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

  public function update(){}
}
?>
