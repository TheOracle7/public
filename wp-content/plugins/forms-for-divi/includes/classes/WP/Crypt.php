<?php
namespace WPT\DiviForms\WP;

/**
 * Crypt.
 */
class Crypt
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Encrypt text
     */
    public function encrypt($text)
    {
        list($key, $iv) = $this->key_iv();

        // phpcs:ignore
        return base64_encode(openssl_encrypt($text, "AES-256-CBC", $key, 0, $iv));
    }

    /**
     * decrypt text
     */
    public function decrypt($cryptic_text)
    {
        list($key, $iv) = $this->key_iv();

        // phpcs:ignore
        return openssl_decrypt(base64_decode($cryptic_text), "AES-256-CBC", $key, 0, $iv);
    }

    public function key_iv()
    {

        $key = hash('sha256', $this->container['secret']->get_key());
        $iv  = substr(hash('sha256', $this->container['secret']->get_iv()), 0, 16);

        return [$key, $iv];

    }

}
