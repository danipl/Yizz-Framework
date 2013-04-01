<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para la manipulación de logs.
 */
class Logger {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Funcion que guarda los logs en un archivo.
     * 
     * @param String $level Nivel del mensaje.
     * @param String $line Mensaje.
     */
    private final static function save($level, $line) {

        // Prevención de errores.
        if (!is_string($level))
            throw new MagicException("level debe ser una cadena.");
        if (!is_string($line))
            throw new MagicException("line debe ser una cadena.");

        self::fixDir();
        $fhandle = fopen(__LOGGER_PATH__ . $level . ".txt", "a+");
        fwrite($fhandle, $line);
        fclose($fhandle);
    }

    /**
     * Función que guarda los logs por directorio según el mes y en año.
     * 
     * @param String $level Nivel del mensaje.
     * @param String $line Mensaje.
     */
    private final static function saveByMounth($level, $line) {

        // Prevención de errores.
        if (!is_string($level))
            throw new MagicException("level debe ser una cadena.");
        if (!is_string($line))
            throw new MagicException("line debe ser una cadena.");

        self::fixDir();
        $fhandle = fopen(__LOGGER_PATH__ . date("Y") . "/" . date("m") . "/" . $level . ".txt", "a+");
        fwrite($fhandle, $line);
        fclose($fhandle);
    }

    /**
     * Función que comprueba si los subdirectorios de mes y año existen, y los crea en casa de que no 
     * esten creados.
     */
    private final static function fixDir() {
        if (!file_exists(__LOGGER_PATH__ . date("Y") . "/")) {
            mkdir(__LOGGER_PATH__ . date("Y") . "/");
        }
        if (!file_exists(__LOGGER_PATH__ . date("Y") . "/" . date("m") . "/")) {
            mkdir(__LOGGER_PATH__ . date("Y") . "/" . date("m") . "/");
        }
    }

    /**
     * Función que se encarga de determinar el método de loguear el mensaje.
     * 
     * @param String $level Nivel del mensaje.
     * @param String $line Mensaje.
     */
    public final static function addLine($level, $line) {

        // Prevención de errores.
        if (!is_string($level))
            throw new MagicException("level debe ser una cadena.");
        if (!is_string($line))
            throw new MagicException("line debe ser una cadena.");

        $line = is_array($line) ? print_r($line, true) : $line;
        $line = date("d-m-Y h:i:s") . ": $line\n";
        IF (__LOGGER_BY_MOUNTH__) {
            self::saveByMounth($level, $line);
        } else {
            self::save($level, $line);
        }
    }

}