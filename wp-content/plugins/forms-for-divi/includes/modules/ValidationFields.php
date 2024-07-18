<?php
namespace WPT_DiviForms_Divi_Modules;

/**
 * ValidationFields.
 */
class ValidationFields
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
     * Set required fields
     */
    public function set_required(
        &$module_fields,
         $options = []
    ) {
        $options = shortcode_atts(
            [
                'message_field' => true,
            ],
            $options
        );

        $fields = [];

        $fields['field_required'] = [
            'label'       => esc_html__('Required', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Toggle switch to enable disable validation', 'et_builder'),
            'show_if'     => [],
            'default'     => $module_fields->get_default('field_required'),
        ];

        if ($options['message_field']) {
            $fields['field_required_message'] = [
                'label'       => esc_html__('Required Error Message', 'et_builder'),
                'type'        => 'text',
                'tab_slug'    => 'general',
                'toggle_slug' => 'validation',
                'description' => esc_html__('Add the error message for required validation.', 'et_builder'),
                'show_if'     => ['field_required' => 'on'],
                'default'     => $module_fields->get_default('field_required_message'),
            ];
        }

        return $fields;
    }

    /**
     * Set email validation field
     */
    public function set_email(&$module_fields)
    {
        $fields = [];

        $fields['field_email'] = [
            'label'       => esc_html__('Email', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Toggle switch to enable disable email validation', 'et_builder'),
            'show_if'     => [],
            'default'     => $module_fields->get_default('field_email'),
        ];

        $fields['field_email_message'] = [
            'label'       => esc_html__('Email Validation Error Message', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Add the error message for email validation.', 'et_builder'),
            'show_if'     => ['field_email' => 'on'],
            'default'     => $module_fields->get_default('field_email_message'),
        ];

        return $fields;
    }

    /**
     * Set minimum length validation
     */
    public function set_minlength(&$module_fields)
    {
        $fields = [];

        $fields['field_minlength'] = [
            'label'       => esc_html__('Min Length', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('', 'et_builder'),
            'show_if'     => [],
            'default'     => $module_fields->get_default('field_minlength'),
        ];

        $fields['field_minlength_value'] = [
            'label'          => esc_html__('Minimum character length', 'et_builder'),
            'type'           => 'range',
            'range_settings' => [
                'min'  => 0,
                'max'  => 10000,
                'step' => 1,
            ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'validation',
            'description'    => esc_html__('Set the minimum character length of the input field ', 'et_builder'),
            'show_if'        => ['field_minlength' => 'on'],
            'allowed_units'  => [''],
            'default_unit'   => '',
            'validate_units' => false,
            'default'        => $module_fields->get_default('field_minlength_value'),
        ];

        $fields['field_minlength_message'] = [
            'label'       => esc_html__('Min Length Error Message', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Set the error message for min length', 'et_builder'),
            'show_if'     => ['field_minlength' => 'on'],
            'default'     => $module_fields->get_default('field_minlength_message'),
        ];

        return $fields;

    }

    /**
     * Set maximum length validation
     */
    public function set_maxlength(&$module_fields)
    {
        $fields = [];

        $fields['field_maxlength'] = [
            'label'       => esc_html__('Max Length', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('', 'et_builder'),
            'show_if'     => [],
            'default'     => $module_fields->get_default('field_maxlength'),
        ];

        $fields['field_maxlength_value'] = [
            'label'          => esc_html__('Maximum character length', 'et_builder'),
            'type'           => 'range',
            'range_settings' => [
                'min'  => 0,
                'max'  => 30000,
                'step' => 1,
            ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'validation',
            'description'    => esc_html__('Set the maximum character length of the input field ', 'et_builder'),
            'show_if'        => ['field_maxlength' => 'on'],
            'allowed_units'  => [''],
            'default_unit'   => '',
            'validate_units' => false,
            'default'        => $module_fields->get_default('field_maxlength_value'),
        ];

        $fields['field_maxlength_message'] = [
            'label'       => esc_html__('Max Length Error Message', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Set the error message for max length', 'et_builder'),
            'show_if'     => ['field_maxlength' => 'on'],
            'default'     => $module_fields->get_default('field_maxlength_message'),
        ];

        return $fields;

    }

    /**
     * Set upload limit
     */
    public function set_upload_limit(&$module_fields)
    {
        $fields = [];

        $fields['upload_limit'] = [
            'label'       => esc_html__('Upload Limit', 'et_builder'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'et_builder'),
                'on'  => esc_html__('On', 'et_builder'),
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Toggle switch to enable/disable setting of upload limit.', 'et_builder'),
            'show_if'     => [],
            'default'     => $module_fields->get_default('upload_limit'),
        ];

        $fields['upload_limit_value'] = [
            'label'          => esc_html__('Maximum File Size (MB)', 'et_builder'),
            'type'           => 'range',
            'range_settings' => [
                'min'  => 1,
                'max'  => 30000,
                'step' => 1,
            ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'validation',
            'description'    => esc_html__('Set the maximum file size to be uploaded in MegaBytes.', 'et_builder'),
            'show_if'        => ['upload_limit' => 'on'],
            'allowed_units'  => [''],
            'default_unit'   => '',
            'validate_units' => false,
            'default'        => $module_fields->get_default('upload_limit_value'),
        ];

        $fields['upload_limit_message'] = [
            'label'       => esc_html__('Upload Limit Error Message', 'et_builder'),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'validation',
            'description' => esc_html__('Set the error message for upload limit', 'et_builder'),
            'show_if'     => ['upload_limit' => 'on'],
            'default'     => $module_fields->get_default('upload_limit_message'),
        ];

        return $fields;
    }

}
