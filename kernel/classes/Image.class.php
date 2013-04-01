<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase funcional para la manipulacion de imagenes.
 */
class Image {

    /**
     * Buffer de la imagen.
     * 
     * @staticvar Resource $image Buffer de la imagen.
     */
    private static $image = null;

    /**
     * Formato de la imagen.
     * 
     * @staticvar String $image_type Formato de la imagen.
     */
    private static $imageType;

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Carga una imagen para ser procesada posteriormente.
     * 
     * @param String $filename Ruta de la imagen.
     */
    public final static function load($filename) {

        // Prevención de errores.
        if (!is_string($filename))
            throw new MagicException("filename debe ser una cadena.");

        $image_info = getimagesize($filename);
        self::$imageType = $image_info ['mime'];
        if (self::$imageType == 'image/jpeg' || self::$imageType == 'image/jpg') {
            self::$image = imagecreatefromjpeg($filename);
        } elseif (self::$imageType == 'image/gif') {
            self::$image = imagecreatefromgif($filename);
            self::saveAlpha();
        } elseif (self::$imageType == 'image/png') {
            self::$image = imagecreatefrompng($filename);
            self::saveAlpha();
        }
    }

    /**
     * Guardar una imagen
     * 
     * @param String $filename Ruta donde se va a guardar el archivo.
     * @param String $image_type Formato en el que queremos guardar la imagen.
     * @param Integer $quality Calidad en la que queremos guardar la imagen (solo JPEG).
     * @param Integer $permissions Permisos que va a tener el archivo. 
     * 
     * @return Boolean True/False segun el resultado de la operacion.
     */
    public final static function save($filename, $imageType = "JPEG", $quality = 75, $permissions = null) {

        // Prevención de errores.
        if (!is_string($filename))
            throw new MagicException("filename debe ser una cadena.");
        if (!is_string($imageType))
            throw new MagicException("imageType debe ser una cadena.");
        if (!Filter::isInteger($quality))
            throw new MagicException("quality debe ser un entero.");

        if (!is_string($permissions) && !is_null($permissions))
            throw new MagicException("permisions debe ser una cadena o un valor nulo.");

        if (strtoupper($imageType) == "JPEG") {
            $result = imagejpeg(self::$image, $filename, $quality);
        } elseif (strtoupper($imageType) == "GIF") {
            $result = imagegif(self::$image, $filename);
        } elseif (strtoupper($imageType) == "PNG") {
            $result = imagepng(self::$image, $filename);
        }

        if ($permissions != null && $result) {
            chmod($filename, $permissions);
        }

        return $result;
    }

    /**
     * Devuelve el buffer actual de la imagen.
     * 
     * @return Resource Buffer de la imagen.
     */
    public final static function getBuffer() {
        return self::$image;
    }

    /**
     * Muestra la imagen en el explorador sin necesidad de guardar el archivo.
     * 
     * @param String $image_type Formato de la imagen.
     */
    public final static function output($imageType = "JPEG") {

        // Prevención de errores.
        if (!is_string($imageType))
            throw new MagicException("imageType debe ser una cadena.");

        ob_clean();
        header("Pragma: public");
        header("Cache-Control: private", false);

        if (strtoupper($imageType) == "JPEG") {
            header('Content-Type: image/jpeg');
            imagejpeg(self::$image);
        } elseif (strtoupper($imageType) == "GIF") {
            header('Content-Type: image/gif');
            imagegif(self::$image);
        } elseif (strtoupper($imageType) == "PNG") {
            header('Content-Type: image/png');
            imagepng(self::$image);
        }

        imagedestroy(self::$image);
    }

    /**
     * Aplica una marca de agua a la imagen.
     * 
     * @param Resource $watermark Buffer con la imagen marca de agua.
     * @param Integer $dst_x Coordenada x del punto de la imagen.
     * @param Integer $dst_y Coordenada y del punto de la imagen.
     * @param Integer $src_x Coordenada x del punto de la imagen marca de agua.
     * @param Integer $src_y Coordenada y del punto de la imagen marca de agua.
     * @param Integer $src_width Anchura original de la imagen marca de agua.
     * @param Integer $src_height Altura original de la imagen marca de agua.
     * @param Integer $trans Transparencia aplicada a la imagen marca de agua.
     * 
     * @return Boolean True/False segun el resultado de la operacion.
     */
    public final static function applyWatermark($watermark, $dst_x, $dst_y, $src_x, $src_y, $src_width, $src_height, $trans) {
        if (imagecopymerge(self::$image, $watermark, $dst_x, $dst_y, $src_x, $src_y, $src_width, $src_height, $trans)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Aplica una marca de agua a la imagen compatible con transparencia de archivos PNG.
     * 
     * @param Resource $watermark Buffer con la imagen marca de agua.
     * @param Integer $dst_x Coordenada x del punto de la imagen.
     * @param Integer $dst_y Coordenada y del punto de la imagen.
     * @param Integer $src_x Coordenada x del punto de la imagen marca de agua.
     * @param Integer $src_y Coordenada y del punto de la imagen marca de agua.
     * @param Integer $src_width Anchura original de la imagen marca de agua.
     * @param Integer $src_height Altura original de la imagen marca de agua.
     * 
     * @return Boolean True/False segun el resultado de la operacion.
     */
    public final static function applyWatermarkWithTrans($watermark, $dst_x, $dst_y, $src_x, $src_y, $src_width, $src_height) {
        if (imagecopy(self::$image, $watermark, $dst_x, $dst_y, $src_x, $src_y, $src_width, $src_height)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve el ancho de la imagen.
     * 
     * @return Integer Ancho de la imagen.
     */
    public final static function getWidth() {
        return imagesx(self::$image);
    }

    /**
     * Devuelve la altura de la imagen.
     * 
     * @return Integer Altura de la imagen.
     */
    public final static function getHeight() {
        return imagesy(self::$image);
    }

    /**
     * Cambia el tamaño de una imagen segun el nuevo tamaño establecido.
     * 
     * @param Integer $width Nueva anchura de la imagen.
     * @param integer $height Nueva altura de la imagen.
     * 
     */
    public final static function resize($width, $height) {

        // Prevención de errores.
        if (!Filter::isInteger($width))
            throw new MagicException("width debe ser un entero.");
        if (!Filter::isInteger($height))
            throw new MagicException("height debe ser un entero.");

        $new_image = imagecreatetruecolor($width, $height);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        imagecopyresampled($new_image, self::$image, 0, 0, 0, 0, $width, $height, self::getWidth(), self::getHeight());
        self::$image = $new_image;
    }

    /**
     * Cambia el tamaño de la imagen segun el ratio de la nueva altura dada respecto la altura original.
     * 
     * @param Integer $height Nueva altura.
     */
    public final static function resizeToHeight($height) {

        // Prevención de errores.
        if (!Filter::isInteger($height))
            throw new MagicException("height debe ser un entero.");

        $ratio = $height / self::getHeight();
        $width = self::getWidth() * $ratio;
        self::resize($width, $height);
    }

    /**
     * Cambia el tamaño de la imagen segun el ratio de la nueva anchura dada respecto la anchura original.
     * 
     * @param Integer $height Nueva anchura.
     */
    public final static function resizeToWidth($width) {

        // Prevención de errores.
        if (!Filter::isInteger($width))
            throw new MagicException("width debe ser un entero.");

        $ratio = $width / self::getWidth();
        $height = self::getheight() * $ratio;
        self::resize($width, $height);
    }

    /**
     * Cambia el tamaño de la imagen segun el valor porcentual establecido.
     * 
     * @param Integer $scale Valor porcentual.
     */
    public final static function scale($scale) {

        // Prevención de errores.
        if (!Filter::isInteger($scale))
            throw new MagicException("scale debe ser un entero.");

        $width = self::getWidth() * $scale / 100;
        $height = self::getheight() * $scale / 100;
        self::resize($width, $height);
    }

    /**
     * Vacia el buffer de la imagen.
     */
    public final static function end() {
        imagedestroy(self::$image);
        self::$imageType = "";
    }

    /**
     * Garantiza la transparencia de la imagen.
     */
    private final static function saveAlpha() {
        imagealphablending(self::$image, false);
        imagesavealpha(self::$image, true);
    }

}