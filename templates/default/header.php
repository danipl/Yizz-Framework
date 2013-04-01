<!DOCTYPE html>
<html lang="<?php echo I18n::getCurrentLanguage(); ?>">
    <head>
        <title>.:: Yizz Framework | <?php echo Model::get('title'); ?> ::.</title>

        <!-- SEO -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="<?php echo I18n::translate('SEODESCRIPTION'); ?>" >
        <meta name="keywords" content="<?php echo I18n::translate('SEOKEYWORDS'); ?>" >
        <meta name="author" content="Yizz Technologies" >

        <!-- STYLES -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo DOMAIN; ?>images/favicon.ico">
        <link rel="stylesheet" href="<?php echo DOMAIN; ?>css/styles.css" >
        <link href="http://fonts.googleapis.com/css?family=Play|Audiowide" rel="stylesheet" type="text/css">

        <!-- SCRIPTS -->
        <script src="<?php echo DOMAIN; ?>js/jquery-1.7.2.min.js"></script>
        <script src="<?php echo DOMAIN; ?>js/scripts.js"></script>
    </head>
    <body>

        <div class="container-fluid">

            <header class="marginbottom20">
                <div class="row-fluid">
                    <div class="span12">
                        <div id="logo"><h1>Yizz Framework</h1></div>
                    </div>
                </div>
            </header>