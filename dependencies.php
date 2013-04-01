<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1.2
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Desde este archivo se crea el contexto y se resuelven las propiedades de la aplicacion.
 */
/*
 * SESSION START
 */
session_start();

/*
 * SET TIMEZONE
 */
date_default_timezone_set(__TIMEZONE__);


/*
 * LOAD UTILS CLASS
 */
include_once __APP_DIR__ . 'kernel/classes/Utils.class.php';


/*
 * LOAD DEPENDENCIES
 */

function __autoload($className) {
    try {
        if (!is_null($classPath = Utils::searchResource($className . ".class.php", "../kernel/classes"))) {
            include $classPath;
        } elseif (!is_null($classPath = Utils::searchResource($className . ".class.php", "../controllers"))) {
            include $classPath;
        } elseif (!is_null($classPath = Utils::searchResource($className . ".class.php", "../plugins"))) {
            include $classPath;
        } else {
            throw new MagicException("La clase " . $className . " no ha podido ser cargada.");
        }
    } catch (MagicException $e) {
        Logger::addLine("fatal", $e->getDefaultMessage());
    }
}

/*
 * REWRITE URL
 */
if (__REWRITE__) {
    try {
        RewriteURL::exec();
    } catch (MagicException $e) {
        Logger::addLine("fatal", $e->getDefaultMessage());
    }
}
/*
 * SECURE PARAMS
 */
if (__SECURE__)
    Param::process();

/*
 * INTERNATIONALIZATION
 */
if (__I18N__) {
    I18n::exec();

    // Hack
    I18n::setLanguage('es');
}

/*
 * SHOPPING CART
 */
if (__SHOP__)
    Shop::loadCart();


/*
 * PERSISTENCE
 */
if (__DATASOURCE__) {
    foreach (explode(",", trim(__DATASOURCE_PROVIDERS__)) as $provider) {
        try {
            DataSource::setConnection($provider);
        } catch (MagicException $e) {
            Logger::addLine("fatal", $e->getDefaultMessage());
        }
    }
}


/*
 * AUTHENTICATION
 */
if (__AUTH__)
    Auth::checkUser();


/*
 * START APPLICATION!!! 
 */
DispatcherController::init();