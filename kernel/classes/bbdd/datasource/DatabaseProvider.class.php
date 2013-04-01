<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 1.0
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Proveedor de métodos para la interaccion con base de datos MySQL.
 */
abstract class DatabaseProvider implements DatabaseProviderInterface {

    /**
     * @var Object Recurso de conexión a la base de datos.
     */
    protected static $resource = null;

    /**
     * @var Object Instancia propia de clase. 
     */
    protected static $instance;

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Realiza una conexión a la base de datos.
     * @see DatabaseProvider::connect()
     * 
     * @param String $host Hostname del servidor de la base de datos.
     * @param String $user Usuario de la base de datos.
     * @param String $pass Contraseña del usuario de la base de datos.
     * @param String $dbname Nombre de la bse de datos.
     */
    abstract protected static function startConnection($host, $user, $pass, $dbname);

    /**
     * Recibe los datos para realizar una nueva conexión a la base de datos
     * comprobando si el proveedor ya existe.
     * 
     * @param String $host Hostname del servidor de la base de datos.
     * @param String $user Usuario de la base de datos.
     * @param String $pass Contraseña del usuario de la base de datos.
     * @param String $dbname Nombre de la bse de datos.
     */
    public static function connect($host, $user, $pass, $dbname) {
        $calledClass = get_called_class();

        if (self::$instance instanceof $calledClass) {
            throw new MagicException($calledClass . " esta en uso.");
        } else {
            self::$instance = new $calledClass();
        }

        self::$instance->startConnection($host, $user, $pass, $dbname);
    }

    /**
     * Devuelve el número del error de la base de datos.
     * @see DatabaseProvider::getErrorNo()
     * 
     * @return Integer Numero del error.
     */
    abstract public static function getErrorNo();

    /**
     * Devuelve una descripción del error de la base de datos.
     * @see DatabaseProvider::getError()
     * 
     * @return String Descripción del error.
     */
    abstract public static function getError();

    /**
     * Envia una consulta SQL a la base de datos.
     * @see DatabaseProvider::query()
     * 
     * @param String $sql SQL
     * @return Resource Recurso devuelto por la base de datos.
     */
    abstract public static function query($sql);

    /**
     * Convierte el recurso/respuesta de la base de datos en un array asociativo. 
     * @see DatabaseProvider::fetchArray()
     * 
     * @param Resource $resource Recurso de la base de datos.
     * @return Array Array asociativo.
     */
    abstract public static function fetchArray($resource);

    /**
     * Devuelve el estado de la conexión.
     * @see DatabaseProvider::isConnected()
     * 
     * @return Boolean True/false segun el resultado de la operación.
     */
    abstract public static function isConnected();

    /**
     * Escapa un parámetro.
     * @see DatabaseProvider::escape()
     * 	 
     * @param String $value Valor no escapado.
     * @return String Valor escapado.
     */
    abstract public static function escape($value);

    /**
     * Devuelve el próximo valor autoincremental de una tabla de la base de datos.
     * @see DatabaseProvider::getAutoIncrement($table);
     * 
     * @param String $table Nombre de la tabla.
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    abstract public static function getAutoIncrement($table);

    /**
     * Devuelve el valor autoincremental de una tabla de la base de datos.
     * @see DatabaseProvider::getLastId();
     * 
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    abstract public static function getLastId();

    /**
     * Cierra o confirma que la conexión con la base de datos esta cerrada.
     * 
     * @return Boolean True/false segun el resultado de la operación.
     */
    abstract public static function close();
}