<?php

/**
 * @programador Daniel Pardo Ligorred
 * @disenador
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.8
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @descripcion
 */
class MagicException extends Exception {

    /**
     * Constructor de la clase.
     * 
     * @param String $msg Mensaje de la Excepci�n a lanzar.
     * @param Integer $code El código de la Excepci�n.
     * @param Exception $prev La excepci�n previa usada por el encadenado de la excepci�n.
     */
    public final function __construct($msg, $code = 0, Exception $prev = null) {
        Error::setSystemError();
        
        parent::__construct($msg, $code, $prev);
    }

    /**
     * Funcion por defecto para el manejo de excepciones.
     * 
     * @return String Informacion detallada con mensaje del error.
     */
    public final function getDefaultMessage() {
        echo "<div id=\"error\" style=\"display:none\">" . I18n::translate('ERROR_SERVER') . "</div>";

        return "Error en la linea " . $this->getLine() . "@" . $this->getFile() . "\n" .
                $this->getMessage() . "\n";
    }

}