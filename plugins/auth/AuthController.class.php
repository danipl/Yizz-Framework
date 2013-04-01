<?php

class AuthController extends BaseController {

    public static function home() {
        self::runView('home', 'auth');
    }

    public static function auth() {
        if (!Auth::authUser(Filter::getPost('username'), Filter::getPost('password'))) {
            Error::add('auth', 'Autentificación fallida.');
        }
        
        self::runView('home');
    }

    public static function registerRules() {
        
    }

}
