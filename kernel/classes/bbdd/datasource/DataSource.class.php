<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Provee a la aplicación de una capa de persistencia para la interacción con bases de datos.
 */
class DataSource {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Establece la conexión a la base de datos.
     * 
     * @param String $provider Nombre del proveedor de la base de datos.
     * @param Array $config Configuración de la base de datos.
     */
    public final static function setConnection($provider = __DEFAULT_PROVIDER__, $config = null) {

        // Prevención de errores.
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");
        if (!is_string($config) && !is_null($config))
            throw new MagicException("config debe ser una cadena o un valor nulo.");

        try {
            if (is_null($config)) {
                $provider::connect(__HOST__, __USER__, __PASSWORD__, __BBDD_NAME__);
            } else {
                $provider::connect($config [0], $config [1], $config [2], $config [3]);
            }
        } catch (MagicException $e) {
            Logger::addLine("fatal", $e->getDefaultMessage());
        }
    }

    /**
     * Prepara la consulta sql reemplazando los parémetros.
     * 
     * @param String $sql Sentencia sql preprocesada.
     * @param Array $params Parametros utilizados por la consulta.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return String Sentencia sql procesada.
     */
    private final static function prepare($sql, $params, $provider) {

        // Prevención de errores.
        if (!is_string($sql))
            throw new MagicException("config debe ser una cadena.");
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        // Si no existen parametros, devolvemos la cadena sin comprobar.
        if (is_null($params))
            return $sql;

        // Comprobamos si los parametros vienen o no por array.
        if (!is_array($params)) {

            // Si es un parametro simple.
            if (is_bool($params))
                $params = ( $params == true ) ? 1 : 0;
            elseif (is_double($params))
                $params = $provider::escape(str_replace(',', '.', $params));
            elseif (is_numeric($params))
                $params = $provider::escape($params);
            elseif (is_null($params))
                $params = "NULL";
            else
                $params = "'" . $provider::escape($params) . "'";

            return str_replace("+?", $params, $sql);
        } else {

            // Si los parametros vienen mediante array numerico.
            if (isset($params [0])) {

                for ($i = 0; $i < sizeof($params); $i++) {
                    if (is_bool($params [$i]))
                        $params [$i] = $params [$i] ? 1 : 0;
                    elseif (is_double($params [$i]))
                        $params [$i] = $provider::escape(str_replace(',', '.', $params [$i]));
                    elseif (is_numeric($params [$i]))
                        $params [$i] = $provider::escape($params [$i]);
                    elseif (is_null($params [$i]))
                        $params [$i] = "NULL";
                    else
                        $params [$i] = "'" . $provider::escape($params [$i]) . "'";
                }
            } else {

                // Si los parametros vienen mediante array asociativo.
                foreach ($params as $key => $value) {
                    if (is_bool($value))
                        $params [$key] = $value ? 1 : 0;
                    elseif (is_double($value))
                        $params [$key] = $provider::escape(str_replace(',', '.', $value));
                    elseif (is_numeric($value))
                        $params [$key] = $provider::escape($value);
                    elseif (is_null($value))
                        $params [$key] = "NULL";
                    else
                        $params [$key] = "'" . $provider::escape($value) . "'";
                }
            }

            foreach ($params as $param => $value)
                $sql = preg_replace('/(\+\?)/', $value, $sql, 1);

            return $sql;
        }
    }

    /**
     * Realiza la consulta a la base de datos.
     * 
     * @param String $q Consulta preprocesada.
     * @param Array $params Parametros utilizados por la consulta.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Object Resultado devuelto por la base de datos.
     */
    private final static function sendQuery($sql, $params, $provider) {

        // Prevención de errores.
        if (!is_string($sql))
            throw new MagicException("config debe ser una cadena.");
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        $query = self::prepare($sql, $params, $provider);
        if (__LOG_BBDD_QUERY__) {
            Logger::addLine('bbdd', $query);
        }
        $result = $provider::query($query);
        if ($provider::getErrorNo()) {
            Logger::addLine('bbdd', $provider::getErrorNo() . '-' . $provider::getError());
        }

        return $result;
    }

    /**
     * Realiza una consulta a la base de datos devolviendo un unico valor de un campo
     * 
     * @param String $q Consulta preprocesada.
     * @param Array $params Parametros utilizados por la consulta.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Object Valor de la consulta o null si no existe.
     */
    public final static function getUniqueValue($sql, $params = null, $provider = __DEFAULT_PROVIDER__) {

        // Prevención de errores.
        if (!is_string($sql))
            throw new MagicException("config debe ser una cadena.");
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        $result = self::sendQuery($sql, $params, $provider);
        if (!is_null($result)) {
            if (!is_object($result)) {
                return $result;
            } else {
                $row = $provider::fetchArray($result);
                return $row [0];
            }
        }

        return null;
    }

    /**
     * Realiza una consulta a la base de datos.
     * 
     * @param String $q Consulta preprocesada.
     * @param Array $params Parametros utilizados por la consulta.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Object Valor/Array de la consulta o null si no existe.
     */
    public final static function send($sql, $params = null, $provider = __DEFAULT_PROVIDER__) {

        // Prevención de errores.
        if (!is_string($sql))
            throw new MagicException("config debe ser una cadena.");
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        $result = self::sendQuery($sql, $params, $provider);
        if (is_object($result)) {
            $arr = array();
            while (@$row = $provider::fetchArray($result)) {
                $arr [] = $row;
            }
            if (count($arr) > 0) {
                return $arr;
            } else {
                return null;
            }
        }

        return null;
    }

    /**
     * Realiza una consulta a la base de datos para comprobar que un campo existe.
     * 
     * @param String $q Consulta preprocesada.
     * @param Array $params Parametros utilizados por la consulta.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Object True/false segun el resultado de la comprobacion.
     */
    public final static function exists($sql, $params = null, $provider = __DEFAULT_PROVIDER__) {

        // Prevención de errores.
        if (!is_string($sql))
            throw new MagicException("config debe ser una cadena.");
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        $result = self::sendQuery($sql, $params, $provider);
        if (is_object($result)) {
            $arr = array();
            while (@$row = $provider::fetchArray($result)) {
                $arr [] = $row;
            }
            if (count($arr) > 0) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Cierra la conexion con la base de datos.
     * 
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return True o false en caso de exito o error.
     */
    public final static function close($provider = __DEFAULT_PROVIDER__) {

        // Prevención de errores.
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        return $provider::close();
    }

    /**
     * Comprueba que el resultado de la ultima operacion con la base de datos es correcto.
     * 
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Object True/false segun el resultado de la comprobacion.
     */
    public final static function isOk($provider = __DEFAULT_PROVIDER__) {

        // Prevención de errores.
        if (!is_string($provider))
            throw new MagicException("provider debe ser una cadena.");

        if ($provider::getErrorNo() == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve el valor autoincremental de una tabla de la base de datos.
     * 
     * @param String $table Nombre de la tabla.
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    public final static function getAutoIncrement($table, $provider = __DEFAULT_PROVIDER__) {

        return $provider::getAutoIncrement($table);
    }

    /**
     * Devuelve el valor autoincremental de una tabla de la base de datos.
     * 
     * @param String $provider Nombre del proveedor de la base de datos.
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    public final static function getLastId($provider = __DEFAULT_PROVIDER__) {

        return $provider::getLastId();
    }

}