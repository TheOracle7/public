<?php
namespace WPT\DiviForms\WP;

/**
 * Util.
 */
class Util
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
     * Convert hex to rgb
     */
    public function hex2rgb($hex)
    {
        if (strpos($hex, 'rgb') === 0) {
            $hex = str_replace('rgba', '', $hex);
            $hex = str_replace('rgb', '', $hex);
            $hex = str_replace('(', '', $hex);
            $hex = str_replace(')', '', $hex);
            return explode(',', $hex);
        }

        $rgb = sscanf($hex, "#%02x%02x%02x");
        return $rgb;
    }

}
