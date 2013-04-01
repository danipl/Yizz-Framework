<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Controlador base del framework. Debe ser extendido por el resto de Controladores.
 */
abstract class BaseController implements RewriteInterface {

    /**
     * Evita la instancia de clases.
     * 
     * @throws MagicException Excepción necesaria.
     */
    protected final function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Procesa la vista de respuesta al cliente.
     * 
     * @param string $viewName Nombre de la vista.
     * @param string $module Nombre del modulo de la vista.
     * @throws MagicException Excepción necesaria.
     */
    protected final static function runView($viewName, $module = null) {
        if (is_null($module)) {
            if (file_exists(__TEMPLATES_DIR__ . self::loadTemplate() . '/' . $viewName . '.php')) {
                require_once __TEMPLATES_DIR__ . self::loadTemplate() . '/' . $viewName . '.php';
            } else {
                throw new MagicException('La vista ' . $viewName . ' no existe.');
            }
        } else {
            if (file_exists(__PLUGINS_DIR__ . strtolower($module) . '/templates/' . $viewName . '.php')) {
                require_once __PLUGINS_DIR__ . strtolower($module) . '/templates/' . $viewName . '.php';
            } else {
                throw new MagicException('La vista ' . $viewName . ' no existe.');
            }
        }
    }

    /**
     * Carga la plantilla a utilizar.
     * 
     * @return String Nombre de la plantilla.
     */
    protected final static function loadTemplate() {
        if (isset($_SESSION ['template'])) {
            return $_SESSION ['template'];
        } elseif (isset($_REQUEST ['template'])) {
            return $_REQUEST ['template'];
        } else {
            return __TEMPLATE_DEFAULT__;
        }
    }

    /**
     * Cambia en tiempo de ejecución la opción de cacheo de una pagina.
     * 
     * @param boolean $boolean Opción a cambiar.
     * @throws MagicException Excepción necesaria.
     */
    protected final static function editCacheable($boolean) {
        if (!Filter::isBoolean($boolean))
            throw new MagicException('Boolean debe ser un valor buleano.');

        Dispatcher::setCacheable($boolean);
    }

}