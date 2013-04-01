<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Archivo de configuracion para la aplicacion.
 */
/*
 * GENERAL
 */


/*
 * TIMEZONE
 */
define('__TIMEZONE__', 'Europe/Madrid');


/*
 * PAGE
 */
define('DOMAIN', 'http://localhost/yizzframework/wwwroot/');


/*
 * DIRECTORIES
 */
define('__PUBLIC_DIR__', 'wwwroot/');
define('__APP_DIR__', dirname(__FILE__) . '/');
define('__APP_KERNEL__', __APP_DIR__ . 'kernel/');
define('__TEMPLATES_DIR__', __APP_DIR__ . 'templates/');
define('__PLUGINS_DIR__', __APP_DIR__ . 'plugins/');
define('__MODELS_DIR__', __APP_DIR__ . 'controllers/');
define('__LANGUAGES_DIR__', __APP_DIR__ . __PUBLIC_DIR__ . 'languages/');
define('__CSS_DIR__', __APP_DIR__ . __PUBLIC_DIR__ . 'css/');
define('__JS_DIR__', __APP_DIR__ . __PUBLIC_DIR__ . 'js/');
define('__IMAGES_DIR__', __APP_DIR__ . __PUBLIC_DIR__ . 'images/');


/*
 * MORE SETTINGS
 */
define('__DEFAULT_CONTROLLER__', 'NormalController');
define('__DEFAULT_RESOURCE__', 'home');
define('__REWRITE__', true);


/*
 * TEMPLATES
 */
define('__TEMPLATE_DEFAULT__', 'default');


/*
 * LOGGER
 */
define('__LOGGER_PATH__', __APP_KERNEL__ . 'logs/');
define('__LOGGER_BY_MOUNTH__', true);


/*
 * SECURE PARAMS
 */
define('__SECURE__', true);


/*
 * CACHE
 */
define('__CACHING__', false);
define('__CACHE_PATH__', __APP_KERNEL__ . 'cache/');
define('__CACHING_LIFETIME__', 95000);


/*
 * SHOPPING CART
 */
define('__SHOP__', false);


/*
 * BBDD
 */

define('__HOST__', '');
define('__PORT__', '');
define('__USER__', '');
define('__PASSWORD__', '');
define('__BBDD_NAME__', '');

define('__DATASOURCE__', false);
define('__DATASOURCE_PROVIDERS__', 'MySqlProvider');
define('__DEFAULT_PROVIDER__', 'MySqlProvider');
define('__LOG_BBDD_QUERY__', true);


/*
 * I18N
 */
define('__I18N__', true);
define('__I18N_PATH__', __APP_DIR__ . __PUBLIC_DIR__ . 'languages/');
define('__DEFAULT_LANGUAGE__', 'es');


/*
 * AUTH
 */
define('__AUTH__', true);
define('__AUTH_TABLE__', 'users');
define('__AUTH_USER_COLUMN__', 'alias');
define('__AUTH_PASSWORD_COLUMN__', 'password');
define('__AUTH_ROLES_COLUMN__', 'roles');


/*
 * Mail
 */
define('MAIL_HOST', '');
define('MAIL_PORT', 587);
define('MAIL_ATTEMPS', 5);
define('MAIL_IS_SEND', true);

define('MAIL_Timeout', 30);
define('MAIL_Username', '');
define('MAIL_Password', '');

define('MAIL_FROM', '');
define('MAIL_FROMNAME', '');