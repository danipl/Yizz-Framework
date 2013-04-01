<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 1.0
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Interface que deben implementar los controladores para el registro de reglas de URLs.
 */
interface RewriteInterface{
    
    /**
     * Registar un array con las reglas para las llamadas que atendera cada controlador.
     * 
     * @return array Array con reglas de URL.
     */
    static function registerRules();
    
}
