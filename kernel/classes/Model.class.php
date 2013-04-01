<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Gestiona el modelo de la aplicación.
 */
class Model {

    /**
     * @var array Array con las variables cargadas. 
     */
    private static $model = array();

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Añade un nuevo valor.
     * 
     * @param String $key Clave del error.
     * @param String $value Valor del error.
     * @return Boolean True/false segun el resultado de la operacion.
     * @throws MagicException Excepción necesaria.
     */
    public final static function add($key, $value) {
        if (empty(self::$model)) {
            self::$model[$key] = $value;
            return true;
        }

        if (!self::exists($key)) {
            self::$model[$key] = $value;
            return true;
        } else {
            throw new MagicException('El valor ' . $key . ' ya existe.');
        }
    }

    /**
     * Comprueba si ya existe el valor.
     * 
     * @param String $key Clave del valor.
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function exists($key = null) {
        if (is_null($key)) {
            if (!empty(self::$model))
                return true;
        } else {
            if (empty(self::$model))
                return false;
            if (array_key_exists($key, self::$model))
                return true;
        }

        return false;
    }

    /**
     * Devuelve un valor.
     * 
     * @param String $key Clave del error.
     * @return Object Valor devuelto o null si no existe.
     */
    public final static function get($key) {
        if (self::exists($key)) {
            $return = self::$model[$key];
        } else {
            $return = null;
        }
        return $return;
    }

    /**
     * Imprime un valor.
     * 
     * @param String $key Clave del error.
     * @return Object Valor devuelto o null si no existe.
     */
    public final static function printVal($key) {
        if (self::exists($key)) {
            echo self::$model[$key];
        } else {
            echo '';
        }
    }

    /**
     *  Elimina un valor.
     * 
     * @param type $key Clave del valor.
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function remove($key) {
        if (self::exists($key)) {
            unset(self::$model[$key]);
            return true;
        }

        return false;
    }

    /**
     * Limpia todos los errores. 
     */
    public final static function clear() {
        self::$model = array();
    }

    /**
     * Imprime los atributos actuales del modelo. 
     */
    public final static function dumpValues() {
        if (self::exists()) {
            echo '<pre>';
            print_r(self::$model);
            echo '</pre>';
        }
    }

}