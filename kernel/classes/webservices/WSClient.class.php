<?php

/* IN DEVELOPMENT */

class WSClient {

    private static $SOAPClient = null;

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    public static function loadClient($wsdl = null, $options = array('exceptions' => false)) {

        self::$SOAPClient = new SoapClient($wsdl, $options);
    }

    public static function getWSFunctions() {

        return self::$SOAPClient->__getFunctions();
    }

    public static function doCall($function, $params = null) {
        if (is_null($params))
            return self::$SOAPClient->__soapCall($function);

        //return self::$SOAPClient->__soapCall($function, $params);
        return self::$SOAPClient->$function($params);
    }

}