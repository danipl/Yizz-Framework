<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Gestiona los errores en tiempo de ejecución.
 */
class Error {

    /**
     * @var boolean True/false segun el resultado.
     */
    private static $systemError = false;

    /**
     * @var array Contiene mensajes de error controlados. 
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
     * Añade un nuevo error.
     * 
     * @param String $key Clave del error.
     * @param String $value Valor del error.
     * @return Boolean True/false segun el resultado de la operación.
     * @throws MagicException Excepción necesaria.
     */
    public final static function add($key, $value) {
        if (empty(self::$errors)) {
            self::$errors[$key] = $value;
            return true;
        }

        if (!self::exists($key)) {
            self::$errors[$key] = $value;
            return true;
        } else {
            throw new MagicException('El error ' . $key . ' ya existe.');
        }
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

    /**
     * Establece a true el flag de error del sistema.
     */
    public final static function setSystemError() {
        self::$systemError = true;
    }

    /**
     * Devuelve el valor del flag de error del sistema.
     * 
     * @return boolean True/false segun el resultado de la operación.
     */
    public final static function isSystemError() {

        return self::$systemError;
    }

}
