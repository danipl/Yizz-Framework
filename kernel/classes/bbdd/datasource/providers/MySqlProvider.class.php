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
class MySqlProvider extends DatabaseProvider {

    /**
     * Realiza una conexión a la base de datos.
     * @see DatabaseProvider::connect()
     * 
     * @param String $host Hostname del servidor de la base de datos.
     * @param String $user Usuario de la base de datos.
     * @param String $pass Contraseña del usuario de la base de datos.
     * @param String $dbname Nombre de la bse de datos.
     */
    protected final static function startConnection($host, $user, $pass, $dbname) {
        // Prevención de errores.
        if (!is_string($host))
            throw new MagicException("host debe ser una cadena.");
        if (!is_string($user))
            throw new MagicException("user debe ser una cadena.");
        if (!is_string($pass))
            throw new MagicException("pass debe ser una cadena.");
        if (!is_string($dbname))
            throw new MagicException("dbname debe ser una cadena.");

        self::$resource = new mysqli($host, $user, $pass, $dbname);

        if (mysqli_connect_errno()) {
            self::$resource = null;
            throw new MagicException("Connect failed: " . mysqli_connect_error());
        }
    }

    /**
     * Devuelve el número del error de la base de datos.
     * @see DatabaseProvider::getErrorNo()
     * 
     * @return Integer Numero del error.
     */
    public final static function getErrorNo() {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return mysqli_errno(self::$resource);
    }

    /**
     * Devuelve una descripción del error de la base de datos.
     * @see DatabaseProvider::getError()
     * 
     * @return String Descripción del error.
     */
    public final static function getError() {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return mysqli_error(self::$resource);
    }

    /**
     * Envia una consulta SQL a la base de datos.
     * @see DatabaseProvider::query()
     * 
     * @param String $sql SQL
     * @return Resource Recurso devuelto por la base de datos.
     */
    public final static function query($sql) {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return mysqli_query(self::$resource, $sql);
    }

    /**
     * Convierte el recurso/respuesta de la base de datos en un array asociativo. 
     * @see DatabaseProvider::fetchArray()
     * 
     * @param Resource $resource Recurso de la base de datos.
     * @return Array Array asociativo.
     */
    public final static function fetchArray($resource) {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return mysqli_fetch_array($resource);
    }

    /**
     * Devuelve el estado de la conexión.
     * @see DatabaseProvider::isConnected()
     * 
     * @return Boolean True/false segun el resultado de la operación.
     */
    public final static function isConnected() {

        return !is_null(self::$resource);
    }

    /**
     * Escapa un parámetro.
     * @see DatabaseProvider::escape()
     * 	 
     * @param String $value Valor no escapado.
     * @return String Valor escapado.
     */
    public final static function escape($value) {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return mysqli_real_escape_string(self::$resource, $value);
    }

    /**
     * Devuelve el próximo valor autoincremental de una tabla de la base de datos.
     * @see DatabaseProvider::getAutoIncrement($table);
     * 
     * @param String $table Nombre de la tabla.
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    public final static function getAutoIncrement($table) {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        // Prevención de errores.
        if (!is_string($table)) {
            throw new MagicException('table debe ser una cadena.');
        }

        $value = self::query("SELECT Auto_increment FROM information_schema.tables WHERE table_name='" . $table . "'");

        if ($value == false)
            return null;

        return $value[0]['Auto_increment'];
    }

    /**
     * Devuelve el valor autoincremental de una tabla de la base de datos.
     * @see DatabaseProvider::getLastId();
     * 
     * @return Integer Valor del campo autoincremental o null en caso de error.
     */
    public final static function getLastId() {

        if (!self::isConnected())
            throw new MagicException("No existe ninguna conexión con la bbdd.");

        return (@$id = mysqli_insert_id(self::$resource) != 0) ? $id : null;
    }

    /**
     * Cierra o confirma que la conexión con la base de datos esta cerrada.
     * 
     * @return Boolean True/false segun el resultado de la operación.
     */
    public final static function close() {

        if (self::isConnected())
            mysqli_close(self::$resource);

        return true;
    }

}