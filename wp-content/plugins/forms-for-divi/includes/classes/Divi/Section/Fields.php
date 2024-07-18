<?php

namespace WPT\DiviForms\Divi\Section;

/**
 * Fields.
 */
class Fields
{
    protected  $container ;
    protected  $defaults ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
        $this->defaults = [];
    }
    
    /**
     * Add fields to divi section
     */
    public function add( $fields_unprocessed )
    {
        $fields = $this->get_fields();
        return array_merge( $fields_unprocessed, $fields );
    }
    
    /**
     * Get the extra section fields
     */
    public function get_fields()
    {
        $fields = $this->get_form_content_fields();
        $fields += $this->spam_protection_fields();
        $fields += $this->form_notification();
        return $fields;
    }
    
    /**
     * Get fields for form content tab
     */
    public function get_form_content_fields()
    {
        $fields['wpt_enable_form'] = [
            'label'           => esc_html__( 'Enable Form', 'forms-for-divi' ),
            'type'            => 'yes_no_button',
            'option_category' => 'configuration',
            'options'         => [
            'off' => esc_html__( 'No', 'forms-for-divi' ),
            'on'  => esc_html__( 'Yes', 'forms-for-divi' ),
        ],
            'default'         => 'off',
            'tab_slug'        => 'general',
            'toggle_slug'     => 'wpt_form',
            'description'     => 'Toggle "Yes" to enable a form. Add the form fields divi modules within this section',
        ];
        $fields['wpt_form_name'] = [
            'label'       => esc_html__( 'Form Name', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form',
            'description' => esc_html__( 'Enter the name of the form. It should be unique for your website. Form submissions are saved against this name.', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form' => 'on',
        ],
            'default'     => 'Untitled Form',
        ];
        $fields['wpt_form_success_message'] = [
            'label'       => esc_html__( 'Success Message', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form',
            'description' => esc_html__( 'Enter message to be shown on successful form submission.', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form' => 'on',
        ],
            'default'     => $this->get_default( 'wpt_form_success_message' ),
        ];
        return $fields;
    }
    
    /**
     * Spam protection
     */
    public function spam_protection_fields()
    {
        $fields = [];
        $providers = [
            ''              => 'None',
            'basic_captcha' => 'Basic Captcha',
        ];
        $fields['wpt_form_spam_protection_provider'] = [
            'label'       => esc_html__( 'Service Provider', 'et_builder' ),
            'type'        => 'select',
            'options'     => $providers,
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_spam_protection',
            'description' => esc_html__( 'Choose an appropriate spam protection service provider.', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form' => 'on',
        ],
            'default'     => $this->get_default( 'wpt_form_spam_protection_provider' ),
        ];
        $fields['wpt_form_basic_captcha_label_prefix'] = [
            'label'       => esc_html__( 'Basic Captcha Label Prefix', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_spam_protection',
            'description' => esc_html__( 'Label prefix for basic captcha field', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form'                   => 'on',
            'wpt_form_spam_protection_provider' => [ 'basic_captcha' ],
        ],
            'default'     => $this->get_default( 'wpt_form_basic_captcha_label_prefix' ),
        ];
        $fields['wpt_form_basic_captcha_validation_error_message'] = [
            'label'       => esc_html__( 'Basic Captcha Validation Message', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_spam_protection',
            'description' => esc_html__( 'The error message to show for required validation ', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form'                   => 'on',
            'wpt_form_spam_protection_provider' => [ 'basic_captcha' ],
        ],
            'default'     => $this->get_default( 'wpt_form_basic_captcha_validation_error_message' ),
        ];
        $fields['wpt_form_spam_protection_error_message'] = [
            'label'       => esc_html__( 'Error Message', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_spam_protection',
            'description' => esc_html__( 'Spam protection error message.', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form'                   => 'on',
            'wpt_form_spam_protection_provider' => [ 'v2', 'v3', 'basic_captcha' ],
        ],
            'default'     => $this->get_default( 'wpt_form_spam_protection_error_message' ),
        ];
        return $fields;
    }
    
    /**
     * Form notification
     */
    public function form_notification()
    {
        $fields = [];
        $fields['wpt_form_notification_send'] = [
            'label'       => esc_html__( 'Send Notification', 'et_builder' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'et_builder' ),
            'on'  => esc_html__( 'On', 'et_builder' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'wpt_enable_form' => 'on',
        ],
            'default'     => $this->get_default( 'wpt_form_notification_send' ),
        ];
        $show_if = [
            'wpt_enable_form'            => 'on',
            'wpt_form_notification_send' => 'on',
        ];
        $fields['wpt_form_notification_subject'] = [
            'label'       => esc_html__( 'Subject', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => $show_if,
            'default'     => $this->get_default( 'wpt_form_notification_subject' ),
        ];
        $fields['wpt_form_notification_to'] = [
            'label'       => esc_html__( 'To', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => $show_if,
            'default'     => $this->get_default( 'wpt_form_notification_to' ),
        ];
        $fields['wpt_form_notification_reply_to'] = [
            'label'       => esc_html__( 'Reply To', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => $show_if,
            'default'     => $this->get_default( 'wpt_form_notification_reply_to' ),
        ];
        $fields['wpt_form_notification_bcc'] = [
            'label'       => esc_html__( 'Bcc', 'et_builder' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => $show_if,
            'default'     => $this->get_default( 'wpt_form_notification_bcc' ),
        ];
        $fields['wpt_form_notification_message'] = [
            'label'       => esc_html__( 'Message', 'et_builder' ),
            'type'        => 'textarea',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wpt_form_notifications',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => $show_if,
            'default'     => $this->get_default( 'wpt_form_notification_message' ),
        ];
        return $fields;
    }
    
    /**
     * Get default for given keys
     */
    public function get_default( $key )
    {
        $defaults = $this->get_defaults();
        return ( isset( $defaults[$key] ) ? $defaults[$key] : '' );
    }
    
    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $this->defaults = [
            'wpt_form_dropdown_hover_bg_color'                => '#000000',
            'wpt_form_dropdown_selected_item_bg_color'        => '#6200ee',
            'wpt_form_dropdown_hover_opacity'                 => '0.04',
            'wpt_form_dropdown_selected_item_opacity'         => '0.2',
            'wpt_form_section_row_padding_bottom_set_zero'    => 'on',
            'wpt_form_success_message'                        => "We've received your query. We'll get back to you shortly.",
            'wpt_form_field_container_custom_margin'          => '||27px||false|false',
            'wpt_form_field_container_custom_padding'         => '||||false|false',
            'wpt_forms_label_color'                           => '#000000',
            'wpt_forms_theme_error'                           => '#b00020',
            'wpt_forms_theme_error_message_background'        => '#b00020',
            'wpt_forms_theme_success_message_background'      => '#4caf50',
            'wpt_forms_input_text_color'                      => '#000000',
            'wpt_forms_help_text_color'                       => '#000000',
            'wpt_forms_help_text_color_opacity'               => 0.6,
            'wpt_forms_input_border_color'                    => '#000000',
            'wpt_forms_selected_checkbox_background_color'    => '#000000',
            'wpt_forms_checkbox_tick_color'                   => '#ffffff',
            'wpt_forms_input_background_color'                => '#ffffff',
            'wpt_forms_input_background_color_focus'          => '#ffffff',
            'wpt_forms_selected_radio_background_color'       => '#000000',
            'wpt_forms_file_upload_background'                => '#efefef',
            'wpt_forms_file_upload_text_color'                => '#000000',
            'wpt_forms_file_button_custom_padding'            => '7px|14px|7px|14px|true|true',
            'wpt_forms_file_button_font_size'                 => '14px',
            'wpt_forms_file_button_line_height'               => '1em',
            'wpt_forms_submit_button_background'              => '#2EA3F2',
            'wpt_forms_submit_button_text_color'              => '#ffffff',
            'wpt_forms_selected_switch_background'            => '#000000',
            'wpt_forms_selected_switch_tick_color'            => '#ffffff',
            'wpt_forms_selected_switch_track_color'           => '#efefef',
            'wpt_forms_unselected_switch_background'          => '#222222',
            'wpt_forms_unselected_switch_dash_icon_color'     => '#ffffff',
            'wpt_forms_unselected_switch_track_color'         => '#efefef',
            'wpt_forms_consent_field_label_text_color'        => '#000000',
            'wpt_forms_submit_button_align'                   => 'left',
            'wpt_forms_submit_button_custom_padding'          => '8px|22px|8px|22px|true|true',
            'wpt_form_submit_button_font_size'                => '22px',
            'wpt_form_submit_button_line_height'              => '1.7em',
            'wpt_forms_success_message_custom_padding'        => '5px|16px|5px|16px|true|true',
            'wpt_form_success_message_timeout'                => '5000',
            'wpt_forms_error_message_custom_padding'          => '5px|16px|5px|16px|true|true',
            'wpt_form_error_message_timeout'                  => '5000',
            'wpt_form_spam_protection_provider'               => '',
            'wpt_form_spam_protection_error_message'          => 'There was an error trying to submit the form.',
            'wpt_form_recaptcha_v3_threshold'                 => '0.5',
            'wpt_form_basic_captcha_validation_error_message' => 'Captcha field value is required.',
            'wpt_form_basic_captcha_label_prefix'             => 'Solve Captcha : ',
            'wpt_form_notification_send'                      => 'on',
            'wpt_form_notification_message'                   => '',
            'wpt_form_notification_reply_to'                  => '',
            'wpt_form_notification_to'                        => '',
            'wpt_form_notification_subject'                   => '%%_site_title%%',
        ];
        return $this->defaults;
    }
    
    /**
     * Get the selectors
     */
    public function get_selectors()
    {
        return [
            'rows'                           => [
            'selector' => "%%order_class%%.wpt-divi-forms .et_pb_row",
            'label'    => 'Rows',
        ],
            'field_container'                => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt-input-field-container",
            'label'    => 'Field Container',
        ],
            'label_inline'                   => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-text-field:not(.mdc-text-field--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms .mdc-select:not(.mdc-select--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_checkboxes_field .wpt-checkbox-label,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_radio_buttons_field .wpt-radio-label",
            'label'    => 'Inline Label',
        ],
            'label_floating'                 => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-text-field .mdc-floating-label.mdc-floating-label--float-above,\n                               %%order_class%%.wpt-divi-forms .mdc-select .mdc-floating-label.mdc-floating-label--float-above",
            'label'    => 'Label Floating',
        ],
            'label'                          => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-text-field:not(.mdc-text-field--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms .mdc-select:not(.mdc-select--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms .mdc-text-field .mdc-floating-label.mdc-floating-label--float-above,\n                               %%order_class%%.wpt-divi-forms .mdc-select .mdc-floating-label.mdc-floating-label--float-above,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_checkboxes_field .wpt-checkbox-label,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_radio_buttons_field .wpt-radio-label",
            'label'    => 'Label',
        ],
            'label_line_height'              => [
            'selector' => "%%order_class%%.wpt-divi-forms .et_pb_module:not(.wpt_form_textarea_field) .mdc-text-field:not(.mdc-text-field--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms .mdc-select:not(.mdc-select--disabled) .mdc-floating-label,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_checkboxes_field .wpt-checkbox-label,\n                               %%order_class%%.wpt-divi-forms div.wpt_form_radio_buttons_field .wpt-radio-label",
            'label'    => 'Label',
        ],
            'text_and_dropdown_input'        => [
            'selector' => "%%order_class%%.wpt-divi-forms input[type='text'], %%order_class%%.wpt-divi-forms textarea, %%order_class%%.wpt-divi-forms .mdc-list-item .mdc-list-item__text, %%order_class%%.wpt-divi-forms  .mdc-select:not(.mdc-select--disabled) .mdc-select__selected-text",
            'label'    => 'Text & Dropdown Input',
        ],
            'text'                           => [
            'selector' => "%%order_class%%.wpt-divi-forms input[type='text']",
            'label'    => 'Single Line Text',
        ],
            'textarea'                       => [
            'selector' => "%%order_class%%.wpt-divi-forms textarea",
            'label'    => 'Paragraph Text',
        ],
            'checkbox_option_text'           => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt-checkbox-container .mdc-form-field label",
            'label'    => 'Checkbox Option Text',
        ],
            'radio_option_text'              => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt-radio-container .mdc-form-field label",
            'label'    => 'Radio Option Text',
        ],
            'file_upload_button'             => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-file-upload-field-container div.mdc-form-field.mdc-file .mdc-button,\n%%order_class%%.wpt-divi-forms\n    .mdc-file-upload-field-container\n    div.mdc-form-field.mdc-file\n    .mdc-button:hover,\n%%order_class%%.wpt-divi-forms\n    .mdc-file-upload-field-container\n    div.mdc-form-field.mdc-file\n    .mdc-button--raised:not(:disabled)",
            'label'    => 'File Upload Button',
        ],
            'file_upload_button_text'        => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-file-upload-field-container button .mdc-button__label",
            'label'    => 'File Upload Button Text',
        ],
            'consent_field_text'             => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt-form-consent-field-container .wpt-consent-label",
            'label'    => 'Consent Field Text',
        ],
            'submit_button_text'             => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt_form_submit_button_field .mdc-button",
            'label'    => 'Submit Button Text',
        ],
            'submit_button_align'            => [
            'selector' => "%%order_class%%.wpt-divi-forms .wpt_form_submit_button_field .et_pb_module_inner",
            'label'    => 'Submit Button Text',
        ],
            'help_text'                      => [
            'selector' => "%%order_class%%.wpt-divi-forms .mdc-text-field:not(.mdc-text-field--disabled) + .mdc-text-field-helper-line .mdc-text-field-helper-text",
            'label'    => 'Help Text',
        ],
            'success_message_surface'        => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-success .mdc-snackbar__surface",
            'label'    => 'Success Message Container',
        ],
            'success_message'                => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-success .mdc-snackbar__surface  .mdc-snackbar__label, %%order_class%%.wpt-divi-forms .form-submit-success .mdc-snackbar__action:not(:disabled)",
            'label'    => 'Success Message Text',
        ],
            'success_message_close_text'     => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-success .mdc-snackbar__surface .mdc-button__label",
            'label'    => 'Success Message Close Icon',
        ],
            'error_message_surface'          => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-error  .mdc-snackbar__surface",
            'label'    => 'Error Message Container',
        ],
            'error_message'                  => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-error  .mdc-snackbar__surface .mdc-snackbar__label, %%order_class%%.wpt-divi-forms .form-submit-error .mdc-snackbar__action:not(:disabled)",
            'label'    => 'Error Message Text',
        ],
            'error_message_close_text'       => [
            'selector' => "%%order_class%%.wpt-divi-forms .form-submit-error .mdc-snackbar__surface  .mdc-button__label",
            'label'    => 'Error Message Close Icon',
        ],
            'select_menu_open_item_hover'    => [
            'selector' => "%%order_class%% .wpt_form_select_menu_field .mdc-menu-surface--open .mdc-list-item:hover .mdc-list-item__ripple::before",
            'label'    => esc_html__( 'Select Menu Item:Before (Hover)', '%text-domain%' ),
        ],
            'select_menu_open_item_selected' => [
            'selector' => "%%order_class%% .wpt_form_select_menu_field .mdc-menu-surface--open .mdc-list-item.mdc-list-item--selected .mdc-list-item__ripple::after",
            'label'    => esc_html__( 'Select Open Menu Selected Item', '%text-domain%' ),
        ],
        ];
    }
    
    /**
     *
     */
    public function get_selector( $key )
    {
        $selectors = $this->get_selectors();
        if ( !isset( $selectors[$key] ) ) {
            return '';
        }
        return $selectors[$key]['selector'];
    }
    
    /**
     * Get pure selector with %%order_class% replaced.
     */
    public function get_selector_pure( $key, $order_class )
    {
        $selector = $this->get_selector( $key );
        return str_replace( '%%order_class%%', $order_class, $selector );
    }
    
    /**
     * Get the prop value
     */
    public function get_prop_value( $key, $props )
    {
        return ( isset( $props[$key] ) && $props[$key] ? $props[$key] : $this->get_default( $key ) );
    }

}