<?php
require_once 'crud.php';
class Cliente extends Crud
{
  private $id;
  const TABLE='clientes';
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

  public function eliminarConReasignacion($id){
    try
    {
        $this->pdo->beginTransaction();

        $stm = $this->pdo->prepare("update ventas_header set id_cliente = 1 where id_cliente = :id");
        $stm->execute(array(':id' => $id));

        $stm = $this->pdo->prepare("update ventas set id_cliente = 1 where id_cliente = :id");
        $stm->execute(array(':id' => $id));

        $stm = $this->pdo->prepare("delete FROM clientes WHERE id = :id");
        $stm->execute(array(':id' => $id));

        $this->pdo->commit();
    }
    catch (PDOException $e){
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllConDescuento(){
    try
    {
        $sql = "SELECT c.id, c.apellido, c.nombre, c.dni, c.email, d.nombre descuento, c.solo_contacto
        FROM clientes AS c
        LEFT JOIN descuentos AS d
        ON c.id_descuento = d.id
        ORDER BY c.apellido, c.nombre";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

  public function getAllActiveForSales($orderBy){
    try
    {
        $allowedOrderBy = array(
          'id' => 'id',
          'apellido' => 'apellido',
          'nombre' => 'nombre',
          'apellido, nombre ASC' => 'apellido, nombre ASC',
          'apellido ASC, nombre ASC' => 'apellido ASC, nombre ASC'
        );
        if (!isset($allowedOrderBy[$orderBy])) {
          throw new InvalidArgumentException("Ordenamiento invalido: ".$orderBy);
        }

        $stm = $this->pdo->prepare("SELECT * FROM clientes where solo_contacto = False ORDER BY ".$allowedOrderBy[$orderBy]);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
}


  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (apellido, nombre, dni, email, id_descuento) VALUES (?, ?, ?, ?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->apellido,
        $this->nombre,
        !empty($this->dni) ? $this->dni : null,
        !empty($this->email) ? $this->email : null,
        !empty($this->idDescuento) ? $this->idDescuento : null,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $params = array();
      $sql = "apellido = :apellido, nombre = :nombre";
      $params[':apellido'] = $this->apellido;
      $params[':nombre'] = $this->nombre;

      if (!empty($this->dni)){
        $sql .= ", dni = :dni";
        $params[':dni'] = $this->dni;
      }
      if (!empty($this->email)){
        $sql .= ", email = :email";
        $params[':email'] = $this->email;
      }
      if (!empty($this->idDescuento)){
        $sql .= ", id_descuento = :idDescuento";
        $params[':idDescuento'] = $this->idDescuento;
      }
      if (isset($this->soloContacto)){
        $sql .= ", solo_contacto = :soloContacto";
        $params[':soloContacto'] = $this->soloContacto;
      }

      $params[':id'] = $this->id;

      $sqlUpdate = "UPDATE ".self::TABLE." SET ".$sql." WHERE id = :id";
      $stm=$this->pdo->prepare($sqlUpdate);
      $stm->execute($params);
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>
