<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase que asegura los parámetros enviados por el cliente.
 */
class Param {
    
    /*
     * @var boolean true si existen valores obtenidos por método HTTP GET o no.
     */

    private static $get = false;

    /*
     * @var boolean true si existen valores obtenidos por método HTTP POST o no.
     */
    private static $post = false;

    /*
     * @var boolean true si existen valores obtenidos por reglas URL o no.
     */
    private static $rule = false;

    /*
     * @var Array Parámetros obtenidos por método HTTP GET asegurados.
     */
    private static $getParams = array();

    /*
     * @var Array Parámetros obtenidos por método HTTP POST asegurados.
     */
    private static $postParams = array();

    /*
     * @var Array Parámetros obtenidos por reglas URL asegurados.
     */
    private static $ruleParams = array();

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Procesa los parámetros.
     */
    public static final function process() {

        if ($_GET)
            self::processGet($_GET);

        if ($_POST)
            self::processPost($_POST);
    }

    /**
     * Procesa los parámetros enviados mediante método HTTP GET.
     * 
     * @param type $params Array de parámetros GET.
     * @param type $arrayKeyName Nombre del parámetro
     */
    private static final function processGet($params, $arrayKeyName = null) {

        self::$get = true;

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                self::processGet($value, $key);
            } else {
                if (!is_null($arrayKeyName)) {
                    self::$getParams[$arrayKeyName][$key] = Utils::secureData($value);
                } else {
                    self::$getParams[$key] = Utils::secureData($value);
                }
            }
        }

        unset($_GET);
    }

    /**
     * Procesa los parámetros enviados mediante método HTTP POST.
     * 
     * @param type $params Array de parámetros POST.
     * @param type $arrayKeyName Nombre del parámetro
     */
    private static final function processPost($params, $arrayKeyName = null) {

        self::$post = true;

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                self::processGet($value, $key);
            } else {
                if (!is_null($arrayKeyName)) {
                    self::$postParams[$arrayKeyName][$key] = Utils::secureData($value);
                } else {
                    self::$postParams[$key] = Utils::secureData($value);
                }
            }
        }

        unset($_POST);
    }
    
    /**
     * Devuelve el valor del parámetro obtenido previamente por método GET asegurados.
     * 
     * @param string $key Nombre del parámetro.
     * @return object Valor del parámetro o null si no existe.
     */
    public static final function getParam($key) {

        if (array_key_exists($key, self::$getParams))
            return self::$getParams[$key];

        return null;
    }

    /**
     * Devuelve el valor del parámetro obtenido previamente por método POST asegurados.
     * 
     * @param string $key Nombre del parámetro.
     * @return object Valor del parámetro o null si no existe.
     */
    public static final function postParam($key) {

        if (array_key_exists($key, self::$postParams))
            return self::$postParams[$key];

        return null;
    }

    /**
     * Comprueba si existen parámetros enviados mediante el método HTTP GET.
     * 
     * @return Boolean True/false según el resultado de la comprobación. 
     */
    public static final function isGet() {

        return self::$get;
    }

    /**
     * Comprueba si existen parámetros enviados mediante el método HTTP POST.
     * 
     * @return Boolean True/false según el resultado de la comprobación. 
     */
    public static final function isPost() {

        return self::$post;
    }

    /**
     * Imprime los atributos actuales del modelo. 
     */
    public final static function dumpValues() {
        if (true) {
            echo '<pre>';
            echo 'GET';
            print_r(self::$getParams);
            echo 'POST';
            print_r(self::$postParams);
            echo '</pre>';
        }
    }

}