<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Controlador de errores.
 */
class ErrorController extends BaseController {

    /**
     * Roles de usuario.
     */
    public static function rolError() {
        self::runView('errors/rol_error');
    }

    /**
     * Recurso no encontrado.
     */
    public static function e404() {
        self::runView('errors/page_error');
    }

    /**
     * Declaración obligada.
     */
    public static function registerRules() {
     
        // EMPTY
    }

}