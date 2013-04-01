<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Provee a la aplicacion de diferentes herramientas.
 */
class Utils {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Busca un recurso a lo largo del arbol de directorios de la aplicacion y devuelve la ruta.
     * 
     * @param $path String Modificador del nivel en el arbol de directorios.
     * @param $recurso String Nombre del recurso a buscar.
     * @return $dir String Direccion del recurso o null si no encuentra el recurso.
     */
    public final static function searchResource($resource, $path = ".") {
        $dir = null;
        $parseDir = self::parseDir($path);
        if (file_exists($parseDir . $resource)) {
            return $parseDir . $resource;
        }
        $path = escapeshellcmd($path);
        foreach (glob("$path/{.[^.]*,*}", GLOB_BRACE | GLOB_ONLYDIR) as $sub_dir) {
            $dir = self::searchResource($resource, $sub_dir);
            if ($dir != "")
                break;
        }
        if (!is_null($dir)) {
            if ($dir != "") {
                return $dir;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Realiza la misma operacion que buscarRecurso() imprimiendo como codigo HTML la direccion.
     * 
     * @param $path String Modificador del nivel en el arbol de directorios.
     * @param $recurso String Nombre del recurso a buscar.
     * @return $dir String Direccion del recurso a ser imprimida como codigo HTML.
     */
    public final static function getURLResource($resource, $path = ".") {
        echo self::searchResource($resource, $path);
    }

    /**
     * Comprueba que la ruta del directorio acaba con '/' y sino se la añade.
     * 
     * @param $dato String Direccion del directorio sin parsear.
     * @return $dato String Direccion del directorio parseada.
     */
    public final static function parseDir($dir) {
        if ($dir [strlen($dir) - 1] != "/") {
            return $dir . "/";
        } else {
            return $dir;
        }
    }

    /**
     * Procesa un dato para prevenir scripts maliciosos.
     * 
     * @param $dato String Dato a ser procesado.
     * @return $dato String Dato procesado.
     */
    public final static function secureData($data) {
        $data = htmlspecialchars(stripslashes(addslashes($data)));
        $data = str_ireplace("script", "blocked", $data);

        return $data;
    }

    /**
     * A partir de un valor numerico lo formatea con dos decimales.
     * 
     * @param $valor Float Valor numerico no formateado.
     * @return Float Devuelve un valor formateado con dos decimales.
     */
    public final static function formatPrice2Dec($value) {
        return number_format($value, 2, '.', '');
    }

    /**
     * Genera un ID irrepetible de 12 digitos.
     * 
     * @return $idTransaccion String ID generado.
     */
    public final static function generateID() {
        $idTransaccion = substr(date('Y'), 3, 1);
        $idTransaccion .= date('zHis');
        $idTransaccion .= rand(0, 9) . rand(0, 9);

        return $idTransaccion;
    }

    /**
     * Calcula la fecha en microsegundos, FUNCION UTIL PARA DEPURAR LA EFICENCIA DE SCRIPTS
     * 
     * @return $valor_defecto Float Fecha representada en valor numerico.
     */
    public final static function microtimeFloat() {
        list ( $useg, $seg ) = explode(" ", microtime());

        return ((float) $useg + (float) $seg);
    }

    /**
     * Devuelve un recurso solicitado.
     * 
     * @param $path String Path al directorio donde se encuentra el recurso.
     * @param $filename String Nombre del recurso
     * @param $toSave Boolean Parametro que establece si el recurso sera mostrado o enviado para guardar.
     */
    public final static function getFile($path, $filename, $toSave = false) {

        // Must be fresh start 
        if (headers_sent())
            die('Headers Sent');

        // Required for some browsers 
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        // File Exists? 
        if (file_exists($path . $filename)) {

            // Parse Info / Get Extension 
            $fsize = filesize($path . $filename);
            $path_parts = pathinfo($path . $filename);
            $ext = strtolower($path_parts ["extension"]);

            // Determine Content Type 
            switch ($ext) {
                case "pdf" :
                    $ctype = "application/pdf";
                    break;
                case "exe" :
                    $ctype = "application/octet-stream";
                    break;
                case "zip" :
                    $ctype = "application/zip";
                    break;
                case "doc" :
                    $ctype = "application/msword";
                    break;
                case "xls" :
                    $ctype = "application/vnd.ms-excel";
                    break;
                case "ppt" :
                    $ctype = "application/vnd.ms-powerpoint";
                    break;
                case "gif" :
                    $ctype = "image/gif";
                    break;
                case "png" :
                    $ctype = "image/png";
                    break;
                case "jpeg" :
                case "jpg" :
                    $ctype = "image/jpg";
                    break;
                default :
                    $ctype = "application/force-download";
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: $ctype");
            if ($toSave)
                header("Content-Disposition: attachment; filename=\"" . $filename . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $fsize);
            ob_clean();
            flush();
            readfile($path . $filename);
        } else {
            throw new MagicException('No se puede obtener el recurso ' . $path . $filename);
        }
    }

    /**
     * Devuelve el tipo de dato.
     * 
     * @param Object $var Dato a evaluar.
     * @return String Tipo de dato.
     */
    public final static function getType($var) {
        if (is_object($var))
            return get_class($var);
        if (is_null($var))
            return 'null';
        if (is_string($var))
            return 'string';
        if (is_array($var))
            return 'array';
        if (is_int($var))
            return 'integer';
        if (is_bool($var))
            return 'boolean';
        if (is_float($var))
            return 'float';
        if (is_resource($var))
            return 'resource';

        return 'unknown';
    }

    /**
     * Devuelve un menu de paginacion.
     * 
     * @param $total integer Numero total de registros a paginar
     * @param $block integer Numero de registros por cada pagina.
     */
    public final static function pagination($total, $block) {
        $pages = (int) ($total / $block);
        for ($pos = 0; $pos <= $pages; $pos++) {
            echo "<a class='pagination' href=''>" . $pos . "</a> ";
        }
    }

    /**
     * Imprime un menu de paginacion.
     * 
     * @param String $string Cadena con caracteres a paginar.
     * @param String $separator Caracter de separacion.
     */
    public final static function aZList($string, $separator = ",") {
        $abc = explode($separator, htmlentities(($string)));
        foreach ($abc as $char) {
            echo "<a class='listAZ' href=''>[" . $char . "]</a> ";
        }
    }

    /**
     * Devuelve un codigo hash MD5 unico para cada llamada realizada al servidor.
     *
     * @return String Hash MD5.
     */
    private final static function getURLtoCache() {
        if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
            return md5($_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] . implode($_POST));
        } else {
            return md5($_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] . implode($_GET));
        }
    }

    /**
     * Deuelve la fecha de la ultima modificacion del archivo.
     * 
     * @param String $file Ruta al archivo.
     * @return Date Fecha.
     */
    public final static function fileTime($file) {
        if (file_exists($file)) {
            return filemtime($file);
        } else {
            return 0;
        }
    }

    public final static function cacheStart() {
        $cachefile = __CACHE_PATH__ . self::getURLtoCache() . '.cache';

        if ((time() - __CACHING_LIFETIME__) < self::fileTime($cachefile)) {
            readfile($cachefile);
            ob_end_flush();
            exit();
        }
    }

    public final static function cacheEnd() {
        $cachefile = fopen(__CACHE_PATH__ . self::getURLtoCache() . '.cache', 'w');

        fwrite($cachefile, ob_get_contents());
        fclose($cachefile);
    }

    public final static function getHttpMethod() {
        switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
            case 'GET': return 'GET'; // SELECCIÓN
            case 'POST': return 'POST'; // AGREGACIÓN
            case 'PUT': return 'PUT'; // ACTUALIZACIÓN
            case 'DELETE': return 'DELETE'; // ELIMINACIÓN
            default : throw new MagicException('El metodo HTTP ' . strtoupper($_SERVER['REQUEST_METHOD']) . ' no es válido.');
        }
    }

    public final static function arrayHasNullValue($array) {
        if (!is_array($array))
            throw new MagicException('Array debe ser un vector valido.');

        foreach ($array as $key => $value) {
            if (is_null($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el valor de la columna segun la seccion.
     * 
     * @param type $array Array con los datos.
     * @param String $type Nombre de la columna que obtener el valor. 
     * @param String $tag Tag.
     * @param String $subtag Subtag.
     * @return String Valor de la columna.
     * @throws MagicException Excepcion necesaria.
     */
    public final static function getText($array, $type, $tag, $subtag = null) {
        if (!is_array($array))
            throw new MagicException('Array debe ser un vector valido.');

        if (!is_string($type))
            throw new MagicException('Type debe ser una cadena valida.');

        if (!is_string($tag))
            throw new MagicException('Tag debe ser una cadena valida.');

        if (!is_string($subtag) && !is_null($subtag))
            throw new MagicException('Subtag debe ser una cadena o un valor nulo.');

        foreach ($array as $value) {
            if (!is_null($tag) && !is_null($subtag)) {
                if (strtolower($value['tag']) == strtolower($tag) && strtolower($value['subtag']) == strtolower($subtag)) {
                    return $value[$type];
                }
            } else {
                if (strtolower($value['tag']) == strtolower($tag)) {
                    return $value[$type];
                }
            }
        }
    }

}