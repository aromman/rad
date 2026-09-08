<?php
require_once 'crud.php';
require_once "logger.php";

class Salario extends Crud
{
  const TABLE='salario';
  public $pdo;
  public $logger;
  public $id;
  public $fecha;
  public $monto;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
    $this->logger = new Logger();
  }

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (fecha, monto) VALUES (?,?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->fecha,
        $this->monto
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET fecha = :fecha, monto = :monto WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':fecha' => $this->fecha,
        ':monto' => $this->monto,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        $this->logger->log(__FILE__,$e->getMessage(),$this->logger::CRITICAL);
    }
  }
}
?>
