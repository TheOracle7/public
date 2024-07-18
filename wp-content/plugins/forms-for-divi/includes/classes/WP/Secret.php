<?php
namespace WPT\DiviForms\WP;

/**
 * Secret.
 */
class Secret
{
    protected $container;
    protected $key;
    protected $iv;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;

        $secret = get_option('wpt_form_secret', false);
        if (!$secret) {
            $secret = [
                'key' => $this->container['str']->random(9),
                'iv'  => $this->container['str']->random(9),
            ];
            add_option('wpt_form_secret', $secret);
        }

        $this->set_key($secret['key']);
        $this->set_iv($secret['iv']);
    }

    /**
     * key
     */
    public function get_key()
    {
        return $this->key;
    }

    /**
     * iv
     */
    public function get_iv()
    {
        return $this->iv;
    }

    /**
     * set key
     */
    public function set_key($key)
    {

        $this->key = $key;
    }

    /**
     * set iv
     */
    public function set_iv($iv)
    {

        $this->iv = $iv;

    }

}
