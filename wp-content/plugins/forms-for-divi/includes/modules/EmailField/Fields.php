<?php
namespace WPT_DiviForms_Divi_Modules\EmailField;

/**
 * .
 */
class Fields
{
    protected $container;
    protected $module;
    protected $defaults;

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
            'field'     => [
                'selector' => "%%order_class%% input",
                'label'    => 'Text Field',
            ],
            'container' => [
                'selector' => "%%order_class%% .mdc-text-field",
                'label'    => 'Container',
            ],
        ];
    }

    /**
     *
     */
    public function set_default(
        $key,
        $value
    ) {
        $this->defaults[$key] = $value;
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
        $this->defaults = [
            'label'                  => 'Email',
            'name'                   => 'untitled_email',
            'default'                => '',
            'placeholder'            => '',
            'helper_text'            => '',

            'field_required'         => 'off',
            'field_required_message' => 'This field is required.',

            'field_email'            => 'on',
            'field_email_message'    => 'Enter a valid email.',
        ];

        return $this->defaults;
    }

    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];

        $fields += $this->general_fields();
        $fields += $this->container['divi_validation_fields']->set_required($this);
        $fields += $this->container['divi_validation_fields']->set_email($this);

        return $fields;
    }

    /**
     * General fields.
     */
    public function general_fields()
    {
        $fields = [];

        $fields['label'] = [
            'label'       => esc_html__('Label Text', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enter the label of the field', 'et_builder'),
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
            'label'       => esc_html__('Default Value', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Set a default text.', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('default'),
        ];

        $fields['placeholder'] = [
            'label'       => esc_html__('Placeholder Text', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Set placeholder text', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('placeholder'),
        ];

        $fields['helper_text'] = [
            'label'       => esc_html__('Helper Text', 'et_builder'),
            'type'        => 'textarea',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enter helper text. It can be instructions on how to fill the field', 'et_builder'),
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
