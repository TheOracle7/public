<?php
namespace WPT_DiviForms_Divi_Modules\TextField;

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
            'label'                   => 'Untitled',
            'name'                    => 'untitled_text',
            'default'                 => '',
            'placeholder'             => '',
            'helper_text'             => '',
            'enable_autocomplete'     => 'off',
            'autocomplete_attribute'  => '',
            'enable_password_field'   => 'off',

            'field_required'          => 'off',
            'field_required_message'  => 'This field is required.',

            'field_minlength'         => 'off',
            'field_minlength_value'   => '0',
            'field_minlength_message' => 'Enter a minimum of x characters.',

            'field_maxlength'         => 'off',
            'field_maxlength_value'   => '0',
            'field_maxlength_message' => 'Enter a maximum of x characters.',
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
        $fields += $this->container['divi_validation_fields']->set_minlength($this);
        $fields += $this->container['divi_validation_fields']->set_maxlength($this);

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

        $fields['enable_autocomplete'] = [
            'label'       => esc_html__('Enable Autocomplete', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Enable/disable the autocomplete attribute', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('enable_autocomplete'),
        ];

        $fields['autocomplete_attribute'] = [
            'label'       => esc_html__('Autocomplete Attribute', 'et_builder'),
            'type'        => 'select',
            'options'     => [
                ""                     => "Select One",
                "name"                 => "name",
                "honorific-prefix"     => "honorific-prefix",
                "given-name"           => "given-name",
                "additional-name"      => "additional-name",
                "family-name"          => "family-name",
                "honorific-suffix"     => "honorific-suffix",
                "nickname"             => "nickname",
                "username"             => "username",
                "new-password"         => "new-password",
                "current-password"     => "current-password",
                "one-time-code"        => "one-time-code",
                "organization-title"   => "organization-title",
                "organization"         => "organization",
                "street-address"       => "street-address",
                "address-line1"        => "address-line1",
                "address-line2"        => "address-line2",
                "address-line3"        => "address-line3",
                "address-level4"       => "address-level4",
                "address-level3"       => "address-level3",
                "address-level2"       => "address-level2",
                "address-level1"       => "address-level1",
                "country"              => "country",
                "country-name"         => "country-name",
                "postal-code"          => "postal-code",
                "cc-name"              => "cc-name",
                "cc-given-name"        => "cc-given-name",
                "cc-additional-name"   => "cc-additional-name",
                "cc-family-name"       => "cc-family-name",
                "cc-number"            => "cc-number",
                "cc-exp"               => "cc-exp",
                "cc-exp-month"         => "cc-exp-month",
                "cc-exp-year"          => "cc-exp-year",
                "cc-csc"               => "cc-csc",
                "cc-type"              => "cc-type",
                "transaction-currency" => "transaction-currency",
                "transaction-amount"   => "transaction-amount",
                "language"             => "language",
                "bday"                 => "bday",
                "bday-day"             => "bday-day",
                "bday-month"           => "bday-month",
                "bday-year"            => "bday-year",
                "sex"                  => "sex",
                "url"                  => "url",
                "photo"                => "photo",
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('', 'et_builder'),
            'show_if'     => ['enable_autocomplete' => 'on'],
            'default'     => $this->get_default('autocomplete_attribute'),
        ];

        $fields['enable_password_field'] = [
            'label'       => esc_html__('Enable Password Field', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'main',
            'description' => esc_html__('Set the field as a password field. The input characters are not shown to the user.', 'et_builder'),
            'show_if'     => [],
            'default'     => $this->get_default('enable_password_field'),
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
