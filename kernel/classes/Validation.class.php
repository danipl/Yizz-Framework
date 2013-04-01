<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para la manipulación de parámetros.
 */
class Validation {

    /**
     * @var Array Mensaje de error por parámetros.
     */
    private static $errors = array();

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Valida los parámetros según el tipo de dato.
     * 
     * @param object $data Dato a validar.
     * @param string $type Tipo de dato.
     * @param string $dataName Nombre del parámetro.
     * @param string $errorMessage Mensaje de error.
     * @return object Dato suministrado para válidar o null si no es válido.
     * @throws MagicException Excepción necesaria.
     */
    public static final function validate($data, $type, $dataName, $errorMessage) {

        if (is_null($dataName))
            throw new MagicException('Se debe especificar un nombre de dato para validar.');
        if (is_null($type))
            throw new MagicException('Se debe especificar un tipo de dato para validar.');
        if (is_null($errorMessage))
            throw new MagicException('Se debe especificar un mensaje de error para validar.');

        $type = 'is' . ucwords(strtolower($type));

        if (!method_exists('Filter', $type))
            throw new MagicException('El tipo de validación no existe en Filter.');

        if (!Filter::$type($data)) {

            self::$errors[$dataName] = $errorMessage;
            return null;
        }

        return $data;
    }

    /**
     * Comprueba si ya existe el error.
     * 
     * @param String $key Clave del error.
     * @return Boolean True/false segun el resultado de la operación.
     */
    public final static function exists($key = null) {
        if (is_null($key)) {
            if (!empty(self::$errors))
                return true;
        } else {
            if (empty(self::$errors))
                return false;
            if (array_key_exists($key, self::$errors))
                return true;
        }

        return false;
    }

    /**
     * Devuelve un error.
     * 
     * @param String $key Clave del error.
     * @return String Error devuelto o null si no existe.
     */
    public final static function get($key) {
        if (self::exists($key)) {
            $return = self::$errors[$key];
        } else {
            $return = null;
        }
        return $return;
    }

    /**
     * Devuelve un error.
     * 
     * @param String $key Clave del error.
     * @return String Error devuelto o null si no existe.
     */
    public final static function printVal($key) {
        if (self::exists($key)) {
            echo self::$errors[$key];
        } else {
            echo '';
        }
    }

    /**
     * Imprime una lista HTML formateada con los errores. 
     */
    public final static function printList() {
        if (self::exists()) {

            echo '<ul class="errors">';
            foreach (self::$errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
        } else {
            echo '';
        }
    }

    /**
     *  Elimina un valor.
     * 
     * @param type $key Clave del valor.
     * @return Boolean True/false segun el resultado de la operación.
     */
    public final static function remove($key) {
        if (self::exists($key)) {
            unset(self::$errors[$key]);
            return true;
        }

        return false;
    }

    /**
     * Limpia todos los errores. 
     */
    public final static function clear() {
        self::$erros = array();
    }

    /**
     * Imprime los atributos actuales del modelo. 
     */
    public final static function dumpValues() {
        if (self::exists()) {
            echo '<pre>';
            print_r(self::$errors);
            echo '</pre>';
        }
    }

}