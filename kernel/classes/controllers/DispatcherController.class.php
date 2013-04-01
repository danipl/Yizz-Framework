<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.3
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase manejadora del Framework.
 */
class DispatcherController extends BaseController {

    /**
     * @var string nombre del controlador. 
     */
    private static $controller = null;

    /**
     * @var string nombre del método. 
     */
    private static $method = null;

    /**
     * @var boolean True/false según el resultado de la operación.. 
     */
    private static $cacheable = __CACHING__;

    /**
     * @var Object Instancia propia de clase. 
     */
    private static $instance = false;

    /**
     * Método de la clase que ejecuta la aplicación. 
     * 
     * @throws MagicException Excepción necesaria.
     */
    public final static function init() {

        if (self::$instance)
            throw new MagicException(__METHOD__ . " no es accesible.");

        self::$instance = true;

        // Inicia buffer de salida.
        ob_start();

        if (Error::isSystemError()) {

            self::runView('errors/server_error');
        } else {

            try {
                // Carga el controlador de la llamada.
                self::$controller = self::loadController();

                // Carga el recurso de la llamada.
                self::$method = self::loadMethod();

                if (self::isCacheable())
                    Utils::cacheStart();

                $ctr = self::$controller;
                $rsc = self::$method;

                // Procesa la llamada.
                $ctr::$rsc();

                if (self::isCacheable())
                    Utils::cacheEnd();

                DataSource::close();

                if (URL::isAjax()) {
                    $htmlBuffer = ob_get_contents();
                    ob_clean();
                    echo substr($htmlBuffer, strpos($htmlBuffer, '<!--AJAX-->'), (strpos($htmlBuffer, '<!--ENDAJAX-->') - strpos($htmlBuffer, '<!--AJAX-->')));
                }
            } catch (MagicException $ex) {
                Logger::addLine("fatal", $ex->getDefaultMessage());
                self::runView('errors/server_error');
            }
        }

        // Vuelca y deshabilita el buffer de salida.
        ob_end_flush();
    }

    /**
     * Carga el controlador.
     * 
     * @return string Nombre del controlador.
     * @throws MagicException Excepción necesaria.
     */
    private final static function loadController() {

        $class = null;

        if (__REWRITE__) {

            if (!is_null(RewriteURL::getController())) {
                $class = ucwords(strtolower(Utils::secureData(RewriteURL::getController()))) . 'Controller';
            }
        }

        if (isset($_REQUEST ['c']) && is_null($class))
            $class = ucwords(strtolower(Utils::secureData($_REQUEST ['c']))) . 'Controller';

        if (!is_null($class)) {

            if (class_exists($class, true)) {
                return $class;
            } else {
                throw new MagicException('El controlador ' . $class . ' no existe.');
            }
        } else {
            return __DEFAULT_CONTROLLER__;
        }
    }

    /**
     * Carga el método.
     * 
     * @return string Nombre del método.
     * @throws MagicException Excepción necesaria.
     */
    private final static function loadMethod() {

        $method = null;

        if (__REWRITE__) {

            if (!is_null(RewriteURL::getMethod())) {
                $method = strtolower(Utils::secureData(RewriteURL::getMethod()));
            }
        }

        if (isset($_REQUEST ['v']) && is_null($method))
            $method = strtolower(Utils::secureData($_REQUEST ['v']));

        if (!is_null($method)) {

            if (method_exists(self::$controller, $method)) {
                return $method;
            } else {
                throw new MagicException('El metodo ' . $method . ' no existe para el controlador ' . self::$controller . '.');
            }
        } else {
            return __DEFAULT_RESOURCE__;
        }
    }

    /**
     * Limpia el buffer de salida.
     */
    public final static function clearBuffer() {

        ob_clean();
    }

    /**
     * Comprueba si la vista es cacheable.
     * 
     * @param $data Array Datos de la vista.
     * @return Boolean True/false segun el resultado de la operación.
     */
    private static function isCacheable() {

        if (self::$cacheable && !isset($_REQUEST ['nocache'])) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * Establece el nuevo valor de cacheo.
     * 
     * @return Boolean Nuevo valor a ser establecido.
     * @throws MagicException Excepción necesaria.
     */
    public final static function setCacheable($boolean) {

        if (!Filter::isBoolean($boolean))
            throw new MagicException('Boolean debe ser un valor buleano.');

        self::$cacheable = $boolean;
    }

    /**
     * Declaración obligada.
     */
    public static function registerRules() {
     
        // EMPTY
    }

}