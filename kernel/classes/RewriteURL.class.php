<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.3
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase manejadora de reglas para las URL.
 */
class RewriteURL {

    /**
     * @var string URL base. 
     */
    private static $baseURL;

    /**
     * @var string URL de servidor. 
     */
    private static $serverName;

    /**
     * @var string URI. 
     */
    private static $requestURI;

    /**
     * @var array Array de reglas URL. 
     */
    private static $rewriteRules = array();

    /**
     * @var string nombre del controlador. 
     */
    private static $controller = null;

    /**
     * @var string nombre del método. 
     */
    private static $method = null;

    /**
     * @var array Array con los valores de los parámetros de cada regla. 
     */
    private static $matches = null;

    /**
     * @var array Array con los parámetros de cada regla. 
     */
    private static $params = array();

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public final function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Método de la clase que prepara el sistema de reglas de URL. 
     */
    public final static function exec() {

        self::$serverName = "http://" . $_SERVER["SERVER_NAME"];
        self::$requestURI = $_SERVER["REQUEST_URI"];
        self::$baseURL = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

        self::loadRegisterRules();

        if (!Error::isSystemError())
            self::processRewriteURL();
    }

    /**
     * Carga las reglas de URL.
     * 
     * @throws MagicException Excepción necesaria.
     */
    private final static function loadRegisterRules() {

        $gestor = opendir(__APP_DIR__ . 'controllers/');

        if ($gestor !== false) {

            while (($controller = readdir($gestor)) !== false) {
                $controller = str_replace('.class.php', '', $controller);

                if (strpos($controller, '.') === false) {

                    $rules = $controller::registerRules();

                    if (!is_array($rules))
                        throw new MagicException('La regla debe ser un array.');

                    foreach ($rules as $rule => $options) {

                        if (!array_key_exists('controller', $options))
                            throw new MagicException('Falta de especificar el controlador en la regla. ->' . $rule);

                        if (!array_key_exists('method', $options))
                            throw new MagicException('Falta de especificar el método en la regla. ->' . $rule);

                        if (!array_key_exists('params', $options))
                            throw new MagicException('Faltan de especificar los nombres de los parámetros en la regla. ->' . $rule);

                        if (array_key_exists($rule, self::$rewriteRules))
                            throw new MagicException('La regla ya esta declarada. -> ' . $rule);
                    }

                    self::$rewriteRules = array_merge(self::$rewriteRules, $rules);
                }
            }

            closedir($gestor);
        }
    }

    /**
     * Maneja las reglas de URL.
     * 
     * @throws MagicException Excepción necesaria.
     */
    private final static function processRewriteURL() {

        foreach (self::$rewriteRules as $rewriteRule => $options) {

            if (preg_match_all($rewriteRule, self::$requestURI, self::$matches)) {

                unset(self::$matches[0]);

                if (!is_null($options['params']))
                    $params = explode(',', $options['params']);

                if ((count($params) != count(self::$matches)) || (strpos(self::$matches[1][0], '/')))
                    throw new MagicException('No coincide el número de parametros requerido en la URL.');

                $pos = 1;
                $var = array();

                if (!is_null($params)) {
                    foreach ($params as $param) {
                        $vars[trim($param)] = Utils::secureData(self::$matches[$pos][0]);
                        $pos++;
                    }
                }

                self::$params = $vars;
                self::$controller = $options['controller'];
                self::$method = $options['method'];

                break;
            }
        }

        if (DOMAIN != self::$baseURL && is_null(self::$controller))
            throw new MagicException('La URL no coincide con ninguna regla. -> ' . self::$baseURL);
    }

    /**
     * Devuelve el valor del parámetro obtenido previamente regla de URL asegurado.
     * 
     * @param string $key Nombre del parámetro.
     * @return object Valor del parámetro o null si no existe.
     */
    public final static function getParam($key) {

        if (array_key_exists($key, self::$params))
            return self::$params[$key];

        return null;
    }

    /**
     * Devuelve el controlador de la regla.
     * 
     * @return string Nombre del controlador.
     */
    public final static function getController() {

        return self::$controller;
    }

    /**
     * Devuelve el meétodo de la regla.
     * 
     * @return string Nombre del método.
     */
    public final static function getMethod() {

        return self::$method;
    }

}