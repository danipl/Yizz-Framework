<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Provee métodos para controlar el flujo de vistas.
 */
class View extends BaseController {

    /**
     * Procesa una nueva vista.
     * 
     * @param String $resource Nombre del recurso.
     * @throws MagicException Excepción necesaria.
     */
    public final static function page($resource) {
        $resource = explode('/', $resource);
        $resource[0] = ucwords(strtolower($resource[0])) . 'Controller';
        $resource[1] = strtolower($resource[1]);

        if (!class_exists($resource[0], true))
            throw new MagicException('El controlador ' . $resource[0] . ' no existe.');

        if (!method_exists($resource[0], $resource[1]))
            throw new MagicException('El metodo ' . $resource[1] . ' no existe para el controlador ' . $resource[0] . '.');

        try {
            //Procesa la llamada.
            $resource[0]::$resource[1]();
        } catch (MagicException $ex) {
            Logger::addLine('fatal', $ex->getDefaultMessage());
            self::runView('errors/server_error');
        }
    }

    /**
     * Cambia la vista del recurso a ser procesada.
     * 
     * @param String $resource Nombre del recurso.
     */
    public final static function redirect($resource) {
        self::page($resource);

        if (DispatcherController::isCacheable())
            Utils::cacheEnd();

        // Vuelca y deshabilita el buffer de salida.
        ob_end_flush();
        exit();
    }

    /**
     * Declaración obligada.
     */
    public static function registerRules() {
     
        // EMPTY
    }

}