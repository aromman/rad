<?php
require_once 'crud.php';
class ProductoSerie extends Crud
{
  private $id;
  const TABLE='productos_serie';
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

  public function getOcupacionCupos(){
    try
    {
        $sql = "select ps.nombre,
        (sum(stock) * 100) / ps.cupo_maximo as porcentaje,
        (ps.cupo_minimo * 100) / ps.cupo_maximo as minimo
        from productos p, productos_serie ps
        where p.stock > 0
        and p.id_serie = ps.id
        group by ps.nombre
        order by ps.nombre";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
        die($e->getMessage());
    }
  }

/*Aquí Insertamos un animal, tenemos que crear forzosamente este método porque en el CRUD lo agregamos como **abstract** sino lo agregamos obtendremos un error.*/
  public function create(){
    try{
      $sql = "INSERT INTO ".self::TABLE." (nombre, cupo_maximo, cupo_minimo) VALUES (?, ?, ?)";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        $this->nombre,
        $this->cupoMaximo,
        $this->cupoMinimo,
      ));
      return $this->pdo->lastInsertId();
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }

  public function update(){
    try{
      $sql = "UPDATE ".self::TABLE." SET nombre = :nombre, cupo_maximo = :cupoMaximo, cupo_minimo = :cupoMinimo WHERE id = :id";
      $stm=$this->pdo->prepare($sql);
      $stm->execute(array(
        ':nombre' => $this->nombre,
        ':cupoMaximo' => $this->cupoMaximo,
        ':cupoMinimo' => $this->cupoMinimo,
        ':id' => $this->id,
      ));
    }catch(PDOException $e){
        error_log(PHP_EOL."[".date('d.m.Y h:i:s'). "] ".$e->getMessage(), 3, "my-errors.log");
    }
  }
}

 ?>