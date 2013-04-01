<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.1
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase que provee servicio de mensajeria electronica a traves de PHPMailer.
 */
class Mail {

    /**
     * Mailer.
     * 
     * @staticvar PHPMailer 
     */
    private static $mailer = null;

    /**
     * Error del mensaje.
     * 
     * @staticvar String Mensaje de error.
     */
    private static $error = null;

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Inicializa $mailer con los datos por defecto.
     */
    private final static function prepare() {
        if (is_null(self::$mailer)) {

            require_once __PLUGINS_DIR__ . 'phpmailer/class.phpmailer.php';

            // Configuracion basica
            self::$mailer = new PHPMailer ();
            self::$mailer->PluginDir = __PLUGINS_DIR__ . 'phpmailer/';
            self::$mailer->Mailer = 'smtp';

            // Servidor
            self::$mailer->Host = MAIL_HOST;
            self::$mailer->Port = MAIL_PORT;

            // Autentificacion
            self::$mailer->Timeout = MAIL_Timeout;
            self::$mailer->Username = MAIL_Username;
            self::$mailer->Password = MAIL_Password;

            // Datos de email
            self::$mailer->From = MAIL_FROM;
            self::$mailer->FromName = MAIL_FROMNAME;
        }
    }

    /**
     * Envia el email
     * 
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function send() {
        $state = false;
        $attemps = -1;

        if (MAIL_IS_SEND)
            self::$mailer->IsSendmail();

        do {
            $attemps++;
            $state = self::$mailer->Send();
            if ($state)
                return true;
        } while (!$state && !$attemps < MAIL_ATTEMPS);

        return false;
    }

    /**
     * Envia el email activando el flag isSendMail.
     * 
     * @return Boolean True/false segun el resultado de la operacion.
     */
    public final static function isSendMail() {
        $state = false;
        $attemps = -1;


        do {
            $attemps++;
            $state = self::$mailer->Send();
            if ($state)
                return true;
        } while (!$state && !$attemps < MAIL_ATTEMPS);

        return false;
    }

    /**
     * Establece si se trata de una conexion con un servidor SMTP.
     * 
     * @param Boolean $smtp True/false segun el caso.
     */
    public final static function isSMTP($smtp = false) {
        self::prepare();

        self::$mailer->IsSMTP($smtp);
        self::$mailer->SMTPAuth = $smtp;
    }

    /**
     * Establece la direccion de correo a la que sera enviado el mensaje electronico.
     * 
     * @param String $addres Dirrecion de correo.
     */
    public final static function setClientAddres($addres) {
        self::prepare();

        self::$mailer->AddAddress($addres);
    }

    /**
     * Establece el asunto del correo electronico.
     * 
     * @param String $subject Asunto del correo electronico.
     */
    public final static function setSubject($subject) {
        self::prepare();

        self::$mailer->Subject = $subject;
    }

    /**
     * Establece el cuerpo del correco electronico.
     * 
     * @param String $body Cuerpo del correo electronico.
     */
    public final static function setBody($body) {
        self::prepare();

        self::$mailer->Body = $body;
    }

    /**
     * Establece el nombre del emisor.
     * 
     * @param String $fromName Nombre del emisor.
     */
    public final static function setFromName($fromName) {
        self::prepare();

        self::$mailer->FromName = $fromName;
    }

    /**
     * Establece si se trata de un correco electronico en lenguaje HTML.
     * 
     * @param Boolean $smtp True/false segun el caso.
     */
    public final static function isHTML($html = false) {
        self::prepare();

        self::$mailer->IsHTML($html);
    }

    /**
     * Carga el cuerpo del mensaje electronico desde un fichero.
     * 
     * @param String $filename Ruta al fichero.
     */
    public final static function loadBodyFromFile($filename) {
        if (!file_exists($filename))
            return null;

        return file_get_contents($filename);
    }

    /**
     * Carga la informacion en el buffer del email.
     * 
     * @param String $body Cuerpo del mensaje electronico.
     * @param Array $params Parametros a sustituir.
     */
    public final static function loadBodyData($body, $params) {

        foreach ($params as $key => $value) {
            $body = str_replace("<!--" . $key . "-->", $value, $body);
        }

        return $body;
    }

    /**
     * Devuelve el error recibido.
     * 
     * @return String Error recibido.
     */
    public final static function getError() {

        return self::$mailer->ErrorInfo;
    }

}
