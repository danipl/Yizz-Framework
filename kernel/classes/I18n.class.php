<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 1.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase que provee de internacionalización a la aplicación.
 */
class I18n {

    /**
     * @staticvar Array Array del lenguaje.
     */
    private static $lang = array();

    /**
     * @staticvar String Lenguaje establecido.
     */
    private static $currentLang;

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Carga el lenguaje de la aplicación.
     */
    public final static function exec() {
        if (isset($_SESSION ['lang']) || isset($_REQUEST ['lang'])) {
            if (isset($_REQUEST ['lang']))
                self::setLocale($_REQUEST ['lang'], true);
            else
                self::setLocale($_SESSION ['lang'], false);
        } else {
            self::loadLocale();
        }
    }

    /**
     * Recupera el languaje establecido manualmente por el cliente.
     * 
     * @param $lang String languaje establecido.
     * @param $no_sesion Boolean Determina si la llamada al metodo ha sido a través 
     *                   de la sesion guardada o mediante GET/POST por el cliente.
     */
    private final static function setLocale($lang, $no_sesion) {

        // Prevención de errores.
        if (!is_string($lang))
            throw new MagicException("lang debe ser una cadena.");
        if ($no_sesion != true && $no_sesion != false)
            throw new MagicException("no_sesion debe ser buleano.");

        if ((!isset($_SESSION ['lang']) || $no_sesion) && file_exists(__I18N_PATH__ . $lang . ".lang"))
            $_SESSION ['lang'] = $lang;

        if (file_exists(__I18N_PATH__ . $_SESSION ['lang'] . ".lang")) {
            self::setCurrentLanguage($_SESSION ['lang']);
            self::loadLang($_SESSION ['lang']);
        } else {
            self::setCurrentLanguage(__DEFAULT_LANGUAGE__);
            self::loadLang(__DEFAULT_LANGUAGE__);
        }
    }

    /**
     * Recupera el languaje del explorador del cliente y determina el languaje a usar.
     */
    private final static function loadLocale() {
        $lang_array = explode('-', $_SERVER ['HTTP_ACCEPT_LANGUAGE']);
        $lang = substr($lang_array [0], 0, 2);
        if (file_exists(__I18N_PATH__ . $lang . ".lang")) {
            self::setCurrentLanguage($lang);
            self::loadLang($lang);
        } else {
            self::setCurrentLanguage(__DEFAULT_LANGUAGE__);
            self::loadLang(__DEFAULT_LANGUAGE__);
        }
    }

    /**
     * Carga el array que contiene la traducción de la página.
     * 
     * @param String $lang Lenguaje que se intentará cargar.
     */
    private final static function loadLang($lang) {

        // Prevención de errores.
        if (!is_string($lang))
            throw new MagicException("lang debe ser una cadena.");

        $contenido = file_get_contents(__I18N_PATH__ . $lang . ".lang");

        $lang = explode("\n", $contenido);

        foreach ($lang as $word) {
            $wordParse = explode("=", $word);
            self::$lang [trim($wordParse [0])] = trim($wordParse [1]);
        }
    }

    /**
     * Establece una constante con el lenguaje actual.
     * 
     * @param String $lang Lenguaje actual.
     */
    private final static function setCurrentLanguage($lang) {

        // Prevención de errores.
        if (!is_string($lang))
            throw new MagicException("lang debe ser una cadena.");

        self::$currentLang = $lang;
    }

    /**
     * Devuelve el lenguaje establecido.
     * 
     * @return String Lenguaje establecido.
     */
    public final static function getCurrentLanguage() {

        return self::$currentLang;
    }

    /**
     * Carga de forma manual el lenguaje especificado.
     * 
     * @param String $lang Lenguaje que se intentara cargar.
     */
    public final static function setLanguage($lang) {

        // Prevención de errores.
        if (!is_string($lang))
            throw new MagicException("lang debe ser una cadena.");

        if (file_exists(__I18N_PATH__ . $lang . ".lang")) {
            self::setCurrentLanguage($lang);
            self::loadLang($lang);
        } else {
            throw new MagicException("El lenguaje especificado no existe.");
        }
    }

    /**
     * Deuvelve la traduccion asociada a la palabra llave.
     * 
     * @param String $key Llave contenedora de la palabra traducida.
     */
    public final static function translate($key) {

        // Prevención de errores.
        if (!is_string($key))
            throw new MagicException("key debe ser una cadena.");

        if (isset(self::$lang [$key])) {
            return self::$lang [$key];
        } else {
            return null;
        }
    }

    /**
     * Añade un prefijo con el lenguaje actual a la tabla especificada.
     * 
     * @param String $table Tabla a ser prefijada con el lenguaje actual.
     * @param Boolean $lang Lenguaje manual.
     * @param Boolean $upper Establece si la cadena del lenguaje ira en mayúsculas.
     * @return String Tabla traducida.
     */
    public final static function translateString($string, $lang = null, $upper = false) {

        // Prevención de errores.
        if (!is_string($string))
            throw new MagicException("string debe ser una cadena.");

        if (is_null($lang)) {
            return $string . ($upper) ? strtoupper(self::$currentLang) : self::$currentLang;
        } else {
            return $string . ($upper) ? strtoupper($lang) : $lang;
        }
    }

}