<?php
namespace ProyectoWeb\repository;

use ProyectoWeb\database\QueryBuilder;
class AsociadoRepository extends QueryBuilder
{
    public function __construct(){
        parent::__construct('asociados', 'Asociado');
    }
    public function getAsociados(): array{
        $sql = "SELECT * FROM $this->table ORDER BY RAND() LIMIT 3";
        return $this->executeQuery($sql);
    }

}