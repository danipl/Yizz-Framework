<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 1.0
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Interface que deben implementar los diferentes proveedores de base de datos.
 */
interface DatabaseProviderInterface {

    /**
     * Recibe los datos para realizar una nueva conexión a la base de datos
     * comprobando si el proveedor ya existe.
     * 
     * @param String $host Hostname del servidor de la base de datos.
     * @param String $user Usuario de la base de datos.
     * @param String $pass Contraseña del usuario de la base de datos.
     * @param String $dbname Nombre de la bse de datos.
     */
    static function connect($host, $user, $pass, $dbname);

    /**
     * Devuelve el número del error de la base de datos.
     * @see DatabaseProvider::getErrorNo()
     * 
     * @return Integer Numero del error.
     */
    static function getErrorNo();

    /**
     * Devuelve una descripción del error de la base de datos.
     * @see DatabaseProvider::getError()
     * 
     * @return String Descripción del error.
     */
    static function getError();

    /**
     * Envia una consulta SQL a la base de datos.
     * @see DatabaseProvider::query()
     * 
     * @param String $sql SQL
     * @return Resource Recurso devuelto por la base de datos.
     */
    static function query($q);

    /**
     * Convierte el recurso/respuesta de la base de datos en un array asociativo. 
     * @see DatabaseProvider::fetchArray()
     * 
     * @param Resource $resource Recurso de la base de datos.
     * @return Array Array asociativo.
     */
    static function fetchArray($resource);

    /**
     * Devuelve el estado de la conexión.
     * @see DatabaseProvider::isConnected()
     * 
     * @return Boolean True/false según el resultado de la operación.
     */
    static function isConnected();

    /**
     * Escapa un parámetro.
     * @see DatabaseProvider::escape()
     * 	 
     * @param String $value Valor no escapado.
     * @return String Valor escapado.
     */
    static function escape($var);

    /**
     * Devuelve el próximo valor autoincremental de una tabla de la base de datos.
     * 
     * @param String $table Nombre de la tabla.
     * @return Integer Valor del campo autoincremental.
     */
    static function getAutoIncrement($table);

    /**
     * Devuelve el valor autoincremental que acaba de ser almacenado en la bd.
     * 
     * @return Integer Valor del campo autoincremental.
     */
    static function getLastId();

    /**
     * Cierra la conexión con la base de datos.
     * 
     * @return Boolean True/false segun el resultado de la operación.
     */
    static function close();
}