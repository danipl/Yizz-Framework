<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para el manejo y la autentificación de usuarios.
 */
class Auth {

    /**
     * @var Array Información del usuario 
     */
    private static $user = null;

    /**
     * Evita que la clase pueda ser instanciada.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     *  Comprueba si existe un usuario logeado en la sesión.
     */
    public final static function checkUser() {
        if (isset($_SESSION ['user']))
            self::$user = $_SESSION ['user'];
    }

    /**
     * Comprueba si el usuario existe en la base de datos y carga sus datos en sesión.
     * 
     * @param String $user Alias del usuario.
     * @param String $password Contraseña del usuario
     * @return Boolean True/false segun el resultado de la operación.
     */
    public final static function authUser($user, $password) {

        // Prevención de errores.
        if (!is_string($user))
            throw new MagicException('user debe ser una cadena.');
        if (!is_string($password))
            throw new MagicException('password debe ser una cadena.');

        $result = DataSource::send('SELECT * FROM ' . __AUTH_TABLE__ . ' WHERE ' . __AUTH_USER_COLUMN__ . ' = +? AND ' . __AUTH_PASSWORD_COLUMN__ . ' = +?', array('user' => $user, 'password' => $password));

        if (!is_null($result)) {
            $_SESSION ['user'] = $result [0];
            self::$user = $_SESSION ['user'];

            return true;
        }

        return false;
    }

    /**
     * Deslogea al usuario.
     */
    public final static function deAuth() {
        if (isset($_SESSION ['user'])) {
            unset($_SESSION ['user']);
            self::$user = null;
        }
    }

    /**
     * Comprueba si existe un usuario autentificado.
     * 
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function isAuth() {
        if (!is_null(self::$user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el usuario tiene un rol especifico.
     * 
     * @param String $role Rol.
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function hasRole($role) {
        if (strpos(self::$user ['roles'], $role) !== false) {
            return true;
        } else {
            ErrorController::rolError();
            throw new MagicException('Rol incorrecto desde el cliente ' . Utils::secureData($_SERVER ['REMOTE_ADDR']) . '.');
        }
    }

    /**
     * Obtiene el alias del usuario.
     * 
     * @return Object Alias del usuario o null si no existe un usuario logeado.
     */
    public final static function getUser() {
        if (!is_null(self::$user)) {
            return self::$user ['user'];
        }

        return null;
    }

}