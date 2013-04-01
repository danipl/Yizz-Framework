<?php

/**
 * @author Daniel Pardo Ligorred
 * @license YIZZ FRAMEWORK @ www.yizztech.com
 * @version 0.4
 * @copyright 2011 @ YIZZ FRAMEWORK by Daniel Pardo Ligorred 
 * is licensed under a Creative Commons Reconocimiento-NoComercial-CompartirIgual 3.0 Unported License.
 *
 * @desc Clase para la implementación de carro de compras.
 */
class Shop {

    /**
     * @var Array Contiene los articulos de compra.
     */
    private static $cart = array();

    /**
     * Evita que la clase pueda ser instanciada.
     * 
     * @throws MagicException Excepción necesaria.
     */
    public function __construct() {

        throw new MagicException('No se puede crear una instancia de ' . __CLASS__);
    }

    /**
     * Carga el carrito de la compra. 
     */
    public final static function loadCart() {
        if (!isset($_SESSION ['cart'])) {
            $_SESSION ['cart'] = array();
        } else {
            self::$cart = $_SESSION ['cart'];
        }
    }

    /**
     * A�ade un producto al array del carrito o aumenta su cantidad si ya existe.
     * 
     * @param String $idProduct ID del producto que va a ser a�adido al carrito.
     * @param Array $product Array con los datos del producto.
     */
    public final static function addProduct($idProduct, $product) {

        // Prevención de errores.
        if (Filter::isEmpty($idProduct))
            throw new MagicException("idProduct no puede ser nulo");
        if (!is_array($product))
            throw new MagicException("Product debe ser un vector().");

        if (!isset(self::$cart [$idProduct])) {
            self::$cart [$idProduct] = array_merge($product, array('amount' => 1));
        } else {
            self::$cart [$idProduct] ['amount'] = self::$cart [$idProduct] ['amount'] + 1;
        }
        $_SESSION ['cart'] = self::$cart;
    }

    /**
     * Elimina una unidad o el producto del carrito segun su ID.
     * 
     * @param Integer $idProduct ID del producto que va a ser eliminado.
     * @param Boolean $total Si 'true' elimina completamente el articulo independientemente de la cantidad.
     */
    public final static function removeProduct($idProduct, $total = false) {

        // Prevención de errores.
        if (Filter::isEmpty($idProduct))
            throw new MagicException("idProduct no puede ser nulo");
        if ($total != true && $total != false)
            throw new MagicException("total debe ser un buleano.");

        if ($total) {

            if (isset(self::$cart [$idProduct])) {
                unset(self::$cart [$idProduct]);
            }
        } else {

            if (isset(self::$cart [$idProduct])) {

                if (self::$cart [$idProduct] ['amount'] == 1) {
                    unset(self::$cart [$idProduct]);
                } elseif (self::$cart [$idProduct] ['amount'] > 1) {
                    self::$cart [$idProduct] ['amount'] = self::$cart [$idProduct] ['amount'] - 1;
                }
            }
        }
        $_SESSION ['cart'] = self::$cart;
    }

    /**
     * Carga la cantidad de unidades del producto.
     */
    public final static function setAmount($idProduct, $amount) {

        // Prevención de errores.
        if (Filter::isEmpty($idProduct))
            throw new MagicException("idProduct no puede estar vacio.");
        if (!is_integer($amount))
            throw new MagicException("amount debe ser un entero.");

        if (isset(self::$cart [$idProduct])) {
            self::$cart [$idProduct] ['amount'] = $amount;
        }
        $_SESSION ['cart'] = self::$cart;
    }

    /**
     * Devuelve la cantidad de unidades seleccionadas de un producto a partir de su id.
     * 
     * @param Integer $idProduct ID del producto.
     * @return Integer Cantidad de unidades del producto.
     */
    public final static function getAmount($idProduct) {

        // Prevención de errores.
        if (Filter::isEmpty($idProduct))
            throw new MagicException("idProduct no puede estar vacio.");

        if (isset(self::$cart [$idProduct])) {

            return self::$cart [$idProduct] ['amount'];
        } else {

            return null;
        }
    }

    /**
     * Devuelve el Array completo con los productos añadidos.
     * 
     * @return Array Array de productos.
     */
    public final static function getCart() {

        return self::$cart;
    }

    /**
     * Vacia el carrito de la compra.
     */
    public final static function resetCart() {
        self::$cart = array();
    }

    /**
     * Devuelve el numero de articulos añadidos en el carrito.
     * 
     * @return integer Numero de articulos del carrito.
     */
    public final static function sizeCart() {

        return count(self::$cart);
    }

    /**
     * Devuelve el valor monetario total de los productos del carrito.
     * 
     * @param String $priceValue Nombre del parametro del Array que guarda el valor monetario del producto.
     * @return integer Valor monetario total del carrito.
     */
    public final static function getTotalPrice($priceValue) {

        // Prevención de errores.
        if (Filter::isEmpty($priceValue))
            throw new MagicException("priceValue no puede estar vacio.");

        $price = 0;
        foreach (self::$cart as $product) {
            $price += $product [$priceValue] * $product ['amount'];
        }

        return $price;
    }

}