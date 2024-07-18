<?php
namespace WPT\DiviForms\Divi;

/**
 * Divi.
 */
class Divi
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function get_prop_value(
        $module,
        $prop_name
    ) {
        return isset($module->props[$prop_name]) && $module->props[$prop_name] ? $module->props[$prop_name] : $module->get_default($prop_name);
    }

    /**
     * Is divi visual builder request
     */
    public function is_visual_builder_request()
    {
        // phpcs:ignore
        return (wp_doing_ajax() || isset($_GET['et_fb']));
    }

    /**
     * Check if hover is enabled. If so return the value. Else null.
     */
    public function hover_value(
        $prop_name,
        $props
    ) {
        $hover_enabled_key = $prop_name . '__hover_enabled';

        if (isset($props[$hover_enabled_key]) && strpos($props[$hover_enabled_key], 'on') === 0) {
            $hover_key = $prop_name . '__hover';
            if (isset($props[$hover_key])) {
                return $props[$hover_key];
            }
        }

        return isset($props[$prop_name]) ? $props[$prop_name] : null;
    }

    /**
     * Get responsive values
     */
    public function get_responsive_values(
        $prop_name,
        $props,
        $default
    ) {

        $desktop = et_pb_responsive_options()->get_desktop_value($prop_name, $props, $default);
        $tablet  = et_pb_responsive_options()->get_tablet_value($prop_name, $props, $desktop);
        $phone   = et_pb_responsive_options()->get_phone_value($prop_name, $props, $tablet);

        return [
            'desktop' => $desktop,
            'tablet'  => $tablet,
            'phone'   => $phone,
        ];
    }

}
