<?php

/**
 *
 * YIZZ FRAMEWORK
 *
 * IS DEVELOPMENT BY DANIEL PARDO LIGORRED (daniel@yizztech.com)
 * PAGE FOR INFO AND CHANGELOG: http://www.yizztech.com/framework/
 * IS UNDER CREATIVE COMMONS LICENSE, MORE INFO IN 'LICENSE.txt'
 * - FREE TO NO COMERCIAL APPLICATIONS.
 * - NOT FREE TO COMERCIAL APPLICATIONS (CONTACT ME TO MORE INFO).
 *
 */

/* SEO URL */
if ($_SERVER["SERVER_NAME"] != 'localhost') {
    if (strrpos(strtolower($_SERVER["HTTP_HOST"]), 'www.') === false) {
        header('Location: http://www.' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        exit();
    }
}

/*  APP BASIC PROPERTIES */
if ($_SERVER["SERVER_NAME"] == 'localhost') {
    include_once '../properties_local.php';
} else {
    include_once '../properties_web.php';
}

/* DEPENDENCIES */
include_once __APP_DIR__ . '/dependencies.php';