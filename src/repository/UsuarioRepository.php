<?php
namespace ProyectoWeb\repository;
use ProyectoWeb\database\QueryBuilder;
use ProyectoWeb\entity\Entity;
use ProyectoWeb\security\IPasswordGenerator;
use ProyectoWeb\entity\Usuario;
use ProyectoWeb\exceptions\QueryException;
use ProyectoWeb\exceptions\NotFoundException;
class UsuarioRepository extends QueryBuilder
{
    /**
     * Generador de password
     *
     * @var IPasswordGenerator
     */
    private $passwordGenerator;

    public function __construct(IPasswordGenerator $passwordGenerator){
        $this->passwordGenerator = $passwordGenerator;
        parent::__construct('users', 'Usuario');
    }
    /**
     * Devuelve el usuario identificado por $username y $password
     *
     * @param string $username
     * @param string $password
     * @return Usuario
     */
    public function findByUserNameAndPassword(string $username, string $password): Usuario{
        $sql = "SELECT * FROM $this->table WHERE username = :username";
        $parameters = ['username' => $username];
       
        $statement = $this->connection->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "ProyectoWeb\\entity\\" . $this->classEntity);
        $statement->execute($parameters);
        $result =  $statement->fetch();
        if (empty($result)){
            throw new NotFoundException("No se ha encontrado ningún elemento con esas credenciales");
        } else {
            if (!$this->passwordGenerator::passwordVerify($password, $result->getPassword())) {
                throw new NotFoundException("No se ha encontrado ningún elemento con esas credenciales");
            }
        }
        return $result;
    }

    /** 
    * @param Entity $entity
    * @throws QueryException
    */
   public function save(Entity $entity)
   {
    $parameters = $entity->toArray();
    $entity->setPassword($this->passwordGenerator::encrypt($parameters['password']));
    parent::save($entity);    
   }
}
