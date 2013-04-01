<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Controlador básico del framework.
 */
class NormalController extends BaseController {

    /**
     * Registra las reglas que manejan los métodos del controlador.
     * 
     * @return array Array con reglas URL que manejan los métodos del controlador.
     */
    public static function registerRules() {

        return array(
            '/js\/scripts.js$/' => array(
                'controller' => 'normal',
                'method' => 'js',
                'params' => null
            ),
            '/rewrite\/(.*)$/' => array(
                'controller' => 'normal',
                'method' => 'rewrite',
                'params' => 'name1'
            )
        );
    }

    /**
     * Index, home... del Framework.
     */
    public static function home() {

        Model::add('title', 'Yizz Framework');
        self::runView('home');
    }

    /**
     * Método de ejemplo para el manejo de reglas.
     */
    public static function rewrite() {

        echo "Valor de name1: " . RewriteURL::getParam('name1');
    }

    /**
     * Script con rutas absolutas.
     */
    public static function js() {

        self::runView('scripts');
    }

    /**
     * Cabecera de la aplicación.
     */
    public static function header() {

        self::runView('header');
    }

    /**
     * Pie de página de la aplicación.
     */
    public static function footer() {

        self::runView('footer');
    }

}