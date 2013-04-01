<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Provee a la aplicación de herramientas para el manejo de URLs.
 */
class Url {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Convierte una URL ordinaria en una URL amigable.
     * 
     * @param String $url URL ordinaria.
     * @return String URL amigable
     */
    public final static function encodeFriendlyURL($url) {

        $domain = substr($url, 0, strpos($url, '?'));
        preg_match('/(?:v\=){1}(\w+)+/', $url, $num);
        $params = substr($url, strpos($url, '?') + 1);
        $params = preg_replace('/(?:v\=){1}(\w+)+/', '', $params);
        $params = str_replace('&', '/', $params);
        $params = str_replace('=', '_', $params);

        if ($params [strlen($params) - 1] != '/')
            $params .= '/';

        return $domain . $num [1] . "/" . $params;
    }

    /**
     * Convierte una cadena en amigable.
     * 
     * @param String $string Cadena a ser procesada.
     * @return String Cadena amigable.
     */
    public final static function convertFriendly($string, $lang = null) {
        
        $friendly = '';
        $aux = '';

        $string = trim(strtolower($string));

        if (is_null($lang)) {
            if (__I18N__) {
                $lang = I18n::getCurrentLanguage();
            } else {
                $lang = __DEFAULT_LANGUAGE__;
            }
        }

        for ($pos = 0; $pos < strlen($string); $pos++) {

            $string[$pos] = self::changeSpanishSpecial($string[$pos]);
            if (self::validateChar($string[$pos])) {

                $friendly = $friendly . $string[$pos];
            } elseif (self::utfCharToNumber($string[$pos]) == 32) {
                $friendly = $friendly . '-';
            }
        }

        $friendly = str_replace(' ', '', self::stopWords($friendly, $lang));

        return $friendly;
    }

    /**
     * Valida los caracteres de direcciones amigables.
     * 
     * @param String $char Caracter a ser procesado.
     * @return boolean Verdadero/falso segun el resultado de la operación.
     */
    public final static function validateChar($char) {

        // 0123456789
        // abcdefghijklmnopqrstuvwxyz
        // ABCDEFGHIJKLMNOPQRSTUVWXYZ
        // ¿? - 194, 63
        // ñÑáÁéÉíÍóÓúÚüÜ - 195
        // € - 226
        // '
        // [space]

        $validCodes =
                array(48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77,
                    78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 97, 98, 99, 100, 101, 102, 103, 104, 105,
                    106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122,
                    226,
                    39,
                    32);

        $ASCIICode = ord($char);

        for ($pos = 0; $pos < count($validCodes); $pos++) {

            if ($validCodes[$pos] == $ASCIICode) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el codigo ASCII de un caracter.
     * 
     * @param String $char Caracter a ser procesado.
     * @return String Codigo ASCII.
     */
    public final static function utfCharToNumber($char) {
        
        $i = 0;
        $number = '';
        while (isset($char{$i})) {
            $number.= ord($char{$i});
            ++$i;
        }
        return $number;
    }

    /**
     * Convierte los caracteres especiales.
     * 
     * @param String $char Caracter a ser procesado.
     * @return String Caracter procesado.
     */
    private final static function changeSpecial($char) {

        switch (self::utfCharToNumber($char)) {
            case 195161: //á
                return 'a';
            case 195129: //Á
                return 'A';
            case 195169: //é
                return 'e';
            case 195137: //É
                return 'E';
            case 195173: //í
                return 'i';
            case 195141: //Í
                return 'I';
            case 195179: //ó
                return 'o';
            case 195147: //Ó
                return 'O';
            case 195186: //ú
                return 'u';
            case 195154: //Ú
                return 'U';
            case 195177: //ñ
                return 'n';
            case 195145: //Ñ
                return 'N';
            case 195188: //ü
                return 'u';
            case 195156: //Ü
                return 'U';
            case 226130172: // €
                return 'euros';
        }

        return $char;
    }

    /**
     * Elimina las "stopwords" en castellano.
     * 
     * @param String $string Cadena a ser procesada
     * @return String Cadena procesada
     */
    public final static function stopWords($string, $lang) {

        $string = ' ' . $string . ' ';

        try {
            $stopWords = file_get_contents(__LANGUAGES_DIR__ . 'stopwords_' . $lang . '.txt');
        } catch (Exception $e) {
            throw new MagicException('El fichero ' . $lang . ' para convertir cadenas amigales no existe.');
        }

        $stopWords = explode(',', $stopWords);

        foreach ($stopWords as $word) {
            $string = str_replace(' ' . trim($word) . ' ', ' ', $string);
        }

        return trim($string);
    }

    /**
     * Comprueba si la llamada es mediante ajax.
     *
     * @return Boolean True/false si la llamada es ajax.
     */
    public final static function isAjax() {
        
        if (isset($_REQUEST ['type'])) {
            if ($_REQUEST['type'] == 'ajax') {
                return true;
            }
        }

        return false;
    }

}