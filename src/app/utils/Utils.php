<?php
namespace ProyectoWeb\app\utils;
class Utils {
    public static function esOpcionMenuActiva(string $option): bool{
        if (strpos($_SERVER["REQUEST_URI"], "/". $option) === 0 ){
            return true;
        }elseif ("/" === $_SERVER["REQUEST_URI"] && ("index" == $option)){
            //tal vez hayamos entrado de forma directa, sin index.php
            return true;
        }else   
            return false;
    }
    public static function  existeOpcionMenuActivaEnArray(array $options): bool{
        foreach ($options as $option){
            if (self::esOpcionMenuActiva($option)) {
                return true;
            }
        }
        return false;
    }
    public static function sanitizeInput(string $data): string {
        $data = trim($data);
        //Quitar las comillas escapadas \' y \ ""
        $data = stripslashes($data);
        //Prevenir la introducción de scripts en los campos
        $data = htmlspecialchars($data);
        return $data;
    }
    /**
     * Devuelve un máximo de tres elementos aleatorios del array $asociados
     *
     * @param array $asociados
     * @return array
     */
    public static function getAsociados(array $asociados): array{
        shuffle($asociados);
        return array_slice($asociados,0, 3);
    }

    /**
     * Crea un src válido para HTML5
     *
     * @param string $imgSrc
     * @return string
     */
    public static function generateValidHTML5src(string $imgSrc): string{
        return str_replace(' ', '%20', $imgSrc);
    }
}