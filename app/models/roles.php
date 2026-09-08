<?php

require_once 'crud.php';
require_once "logger.php";

class Rol extends Crud
{
  
  const TABLE='roles';
  
  public  $pdo;
  public $logger;
  public $rol;

  public function __construct(){
    parent::__construct(self::TABLE);
    $this->pdo=parent::conexion();
    $this->logger =  new Logger();
  }

  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (rol) VALUES (?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array($this->rol));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
  
  public function update(){
    // a implementar
  }
}