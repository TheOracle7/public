<?php
namespace WPT\DiviForms\WP;

/**
 * Str.
 */
class Str
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
     * Random string
     */
    public function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            // phpcs:ignore
            $bytes = random_bytes($size);

            // phpcs:ignore
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

}
