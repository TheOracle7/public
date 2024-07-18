<?php
namespace WPT\DiviForms\Form;

/**
 * Csrf.
 */
class Csrf
{
    protected $container;
    public $key;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->key       = 'wpt_csrf_token';
    }

    public function get_key()
    {
        return $this->key;
    }

    /**
     * Get the csrf token
     */
    public function token()
    {

        if (empty($_SESSION[$this->key])) {
            $_SESSION[$this->key] = $this->container['str']->random(32);
        }

        return $_SESSION[$this->key];
    }

    public function reset()
    {
        if (!empty($_SESSION[$this->key])) {
            unset($_SESSION[$this->key]);
        }

    }

    /**
     * Check if csrf is valid.
     */
    public function valid()
    {
        // phpcs:ignore
        if (isset($_POST[$this->key])) {
            // phpcs:ignore
            return $this->verify($_POST[$this->key]);
        }

        return false;
    }

    /**
     * Verify csrf token
     */
    public function verify($token)
    {

        if ($this->hasToken()) {
            return $token == $this->token();
        }

        return false;
    }

    /**
     * Check if token is present in the POST
     */
    public function hasToken()
    {
        // phpcs:ignore
        if (isset($_POST, $_POST[$this->key])) {
            return true;
        }

        return false;
    }

}
