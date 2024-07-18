<?php
namespace WPT_DiviForms_Divi_Modules\ConsentField;

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
            'label'                  => 'I agree to the privacy policy.',
            'name'                   => 'untitled_consent',
            'default'                => 'on',
            'helper_text'            => '',

            'field_required'         => 'on',
            'field_required_message' => 'This field is required.',
        ];

        return $defaults;
    }

    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];

        $fields += $this->general_fields();
        $fields += $this->container['divi_validation_fields']->set_required($this);

        return $fields;
    }

    /**
     * General fields.
     */
    public function general_fields()
    {
        $fields = [];

        $fields['label'] = [
            'label'       => esc_html__('Checkbox Label', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enter the label for the consent checkbox', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('label'),
        ];

        $fields['name'] = [
            'label'       => esc_html__('Field Name (alphanumeric)', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enter the name of the field without spaces. Ideally, it`s lowercase', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('name'),
        ];

        $fields['default'] = [
            'label'       => esc_html__('Checkbox Default', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Unchecked', 'et_builder'),
                'on'  => esc_html__('Checked', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Set the checked/unchecked state for the checkbox.', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('default'),
        ];

        $fields['helper_text'] = [
            'label'       => esc_html__('Consent Text', 'et_builder'),
            'type'        => 'tiny_mce',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enter detailed text information related to the consent.', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('helper_text'),
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
