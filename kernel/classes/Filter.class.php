<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.6
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para el filtrado de la informacion a usar por la aplicacion, especialmente los parametros 
 *       cuyo origen reside en un cliente externo y no han sido tratados o validados con anterioridad.
 */
class Filter {

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Recoge un valor por parametro y lo procesa enviado mediante GET.
     * 
     * @param String $getValue Nombre del parametro.
     * @param String $type Verifica si el parametro es del tipo.
     * @return Object Valor procesado o null si no existe o da error.
     * @throws MagicException Excepción necesaria.
     */
    public static function getGet($getValue, $type = null) {

        if (Param::isGet())
            throw new MagicException('Los datos son accesibles desde la clase Param.');

        if (isset($_GET [$getValue]) && !is_null($_GET [$getValue])) {
            if (!is_null($type)) {
                $type = 'is' . ucwords(strtolower($type));
                if (self::$type($_GET [$getValue])) {
                    return $_GET [$getValue];
                } else {
                    return null;
                }
            } else {
                return Utils::secureData($_GET [$getValue]);
            }
        } else {
            return null;
        }
    }

    /**
     * Recoge un valor por parametro y lo procesa enviado mediante POST.
     * 
     * @param String $getValue Nombre del parametro.
     * @param String $type Verifica si el parametro es del tipo.
     * @return Object Valor procesado o null si no existe o da error.
     * @throws MagicException Excepción necesaria.
     */
    public static function getPost($getValue, $type = null) {

        if (Param::isPost())
            throw new MagicException('Los datos son accesibles desde la clase Param.');

        if (isset($_POST [$getValue]) && !is_null($_POST [$getValue])) {
            if (!is_null($type)) {
                $type = 'is' . ucwords(strtolower($type));
                if (self::$type($_POST [$getValue])) {
                    return $_POST [$getValue];
                } else {
                    return null;
                }
            } else {
                return Utils::secureData($_POST [$getValue]);
            }
        } else {
            return null;
        }
    }

    /**
     * Recoge un valor por parametro y lo procesa enviado mediante GET o POST.
     * 
     * @param String $getValue Nombre del parametro.
     * @param String $type Verifica si el parametro es del tipo.
     * @return Object Valor procesado o null si no existe o da error.
     * @throws MagicException Excepción necesaria.
     */
    public static function getRequest($getValue, $type = null) {

        if (Param::isGet() || Param::isPost())
            throw new MagicException('Los datos son accesibles desde la clase Param.');

        if (isset($_REQUEST [$getValue]) && !is_null($_REQUEST [$getValue])) {
            if (!is_null($type)) {
                $type = 'is' . ucwords(strtolower($type));
                if (self::$type($_REQUEST [$getValue])) {
                    return $_REQUEST [$getValue];
                } else {
                    return null;
                }
            } else {
                return Utils::secureData($_REQUEST [$getValue]);
            }
        } else {
            return null;
        }
    }

    /**
     * Comprueba si el valor suministrado es alfanumerico.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function isAlphanumeric($value) {
        if (!is_null($value) && $value != "") {
            if (ctype_alnum($value)) {
                return true;
            } else {
                Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es buleano.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function isBoolean($value) {
        switch (strtolower($value)) {
            case true:
            case TRUE:
            case ("true") :
            case (1) :
            case false:
            case FALSE:
            case ("false") :
            case (0) :
                return true;
            default :
                Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es un numero entero con coma flotante.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function isFloat($value) {
        if (!is_null($value) && $value != "") {
            if (is_numeric($value)) {
                if ((float) $value == $value) {
                    return true;
                } else {
                    Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                    return false;
                }
            } else {
                Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es buleano.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function isInteger($value) {
        if (!is_null($value) && $value != "") {
            if (is_numeric($value)) {
                if ((int) $value == $value) {
                    return true;
                } else {
                    return false;
                }
            } else {
                Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es una cadena.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function isString($value) {
        if (!is_null($value) && $value != "") {
            if (is_String($value)) {
                return true;
            } else {
                Logger::addLine("info", "Parametro " . Utils::secureData($value) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es un email.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es un email o "false" si no lo es.
     */
    public static function isEmail($email) {
        if (!is_null($email) && $email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                Logger::addLine("info", "Parametro " . Utils::secureData($email) . "@" . __CLASS__ . "->" . __METHOD__ . " no esperado desde el cliente " . Utils::secureData($_SERVER ['REMOTE_ADDR']));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Comprueba si el valor suministrado es nulo o vacio.
     * 
     * @param Object $value Valor suministrado.
     * @return Object Devuelve "true" si es nulo/vacio o "false" si no lo es.
     */
    public static function isEmpty($value) {
        if (is_null($value) || trim($value) == "") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Comprueba si la longitud de la cadena especificada esta entre los valores establecidos.
     * 
     * @param String $string Cadena a comprobar
     * @param Object $min Longitud minuma de la cadena o null si es indiferente.
     * @param Object $max Longitud maxima de la cadena o null si es indiferente
     * @return Boolean True/false segun el resultado de la comprobación.
     */
    public static function isStringBetween($string, $min = null, $max = null) {
        if (!is_null($min)) {
            if (strlen($string) < $min) {
                return false;
            }
        } elseif (!is_null($min)) {
            if (strlen($string) > $max) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida los campos de los formularios
     * 
     * @param Object $data Valor suministrado.
     * @param Integer $min_lenght Longitud minima del dato.
     * @param Integer $max_lenght Longitud maxima del dato.
     * @param Boolean $can_null Establece si el dato puede ser nulo.
     * @return Boolean Devuelve "true" si es correcto o "false" si no lo es.
     */
    public static function validateField($data, $min_lenght, $max_lenght, $can_null = false) {
        if ($can_null) {

            if (is_null($data)) {
                return true;
            } elseif (strlen($data) >= $min_lenght && strlen($data) <= $max_lenght) {
                return true;
            } else {
                return false;
            }
        } else {

            if (!is_null($data) && strlen($data) >= $min_lenght && strlen($data) <= $max_lenght) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Especifica un valor por defecto para datos nulos.
     * 
     * @param Object $data Dato suministrado.
     * @param Object $default Valor por defecto.
     * @return Object Valor suministrado o valor por defecto.
     */
    public static function checkNullValue($data, $default) {
        if (is_null($data)) {
            return $default;
        } else {
            return $data;
        }
    }

    /**
     * Especifica un valor por defecto para valores diferentes a uno dado.
     * 
     * @param Object $data Dato suministrado.
     * @param Object $value Valor de igualdad.
     * @param Object $default Valor por defecto.
     * @return Object Valor por defecto.
     */
    public static function checkSetValue($data, $value, $default) {
        if ($data == $value) {
            return $default;
        } else {
            return $data;
        }
    }

    /**
     * Comprueba si el telefono es valido.
     * 
     * @param Integer $phone Telefono para valirdar.
     * @return Boolean True/false según el resultado de la comprobación. 
     */
    public static function isPhone($phone) {

        return preg_match('/^[0-9]{9,15}$/', $phone);
    }

}