<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para el manejo de plugins.
 */
class Plugin {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Carga el modulo especificado.
     * 
     * @param String $module Nombre del modulo.
     * @throws MagicException Excepción necesaria.
     */
    public static function load($module) {

        // Prevención de errores.
        if (!is_string($module))
            throw new MagicException("module debe ser una cadena.");

        $module = ucwords(strtolower($module)) . 'Controller';

        self::checkController($module);
        self::checkHome($module);

        $module::home();
    }

    /**
     * Comprueba que el controlador existe.
     * 
     * @param type $module Nombre del modulo.
     * @throws MagicException Excepción necesaria.
     */
    private final static function checkController($module) {
        $class = $module;
        if (!class_exists($class, true)) {
            throw new MagicException('El controlador ' . $class . ' no existe.');
        }
    }

    /**
     * Comprueba que el metodo 'home' existe para el controlador especificado.
     * 
     * @param type $module Nombre del modulo.
     * @throws MagicException Excepción necesaria.
     */
    private final static function checkHome($module) {
        $method = 'home';
        if (!method_exists($module, $method)) {
            throw new MagicException('El metodo ' . $method . ' debe existir para el controlador ' . self::$controller . '.');
        }
    }

}