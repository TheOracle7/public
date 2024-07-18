<?php
namespace WPT_DiviForms_Divi_Modules\SubmitButtonField;

/**
 * .
 */
class Fields
{
    protected $container;
    protected $module;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Set the module instance.
     */
    public function set_module($module)
    {
        $this->module = $module;
    }

    /**
     * Get selector
     */
    public function get_selector($key)
    {
        $selectors = $this->get_selectors();

        return $selectors[$key]['selector'];
    }

    /**
     * List of selectors
     */
    public function get_selectors()
    {
        return [
            'instructions_container' => [
                'selector' => "{$this->module->main_css_element} ol.instructions_container",
                'label'    => '',
            ],
        ];
    }

    /**
     * Get default for given keys
     */
    public function get_default($key)
    {
        $defaults = $this->get_defaults();

        return isset($defaults[$key]) ? $defaults[$key] : '';
    }

    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $defaults = [
            'full_width'   => 'off',
            'button_label' => 'Submit',
        ];

        return $defaults;
    }

    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];

        $fields['button_label'] = [
            'label'       => esc_html__('Button Label', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main_content',
            'description' => esc_html__('Enter label for the button', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('button_label'),
        ];

        $fields['full_width'] = [
            'label'       => esc_html__('Full Width', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'main_content',
            'description' => esc_html__('Toggle "On" to set the button to fullwidth. Set "Off" otherwise', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('full_width'),
        ];

        $fields['admin_label'] = [
            'label'       => __('Admin Label', 'et_builder'),
            'type'        => 'text',
            'description' => 'This will change the label of the module in the builder for easy identification.',
        ];

        return $fields;
    }

    public function get_css_fields()
    {
        $selectors = $this->get_selectors();

        foreach ($selectors as $key => $selector) {
            $selectors[$key]['selector'] = "html body div#page-container " . $selector['selector'];
        }

        return $selectors;
    }

    public function set_advanced_toggles(&$toggles)
    {
        $selectors = $this->get_selectors();

        foreach ($selectors as $slug => $selector) {
            $toggles['advanced']['toggles'][$slug] = $selector['label'];
        }
    }

    /**
     * Advanced font definition
     */
    public function get_advanced_font_definition($key)
    {
        return [
            'css' => [
                'main'      => $this->get_selector($key),
                'important' => 'all',
            ],
        ];
    }

    public function set_advanced_font_definition(
        &$config,
         $key
    ) {
        $config['fonts'][$key] = $this->get_advanced_font_definition($key);
    }

}
