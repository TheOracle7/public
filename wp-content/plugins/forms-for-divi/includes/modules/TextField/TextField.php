<?php

namespace WPT_DiviForms_Divi_Modules\TextField;

use  ET_Builder_Module ;
class TextField extends ET_Builder_Module
{
    public  $slug = 'wpt_form_text_field' ;
    public  $vb_support = 'on' ;
    protected  $container ;
    protected  $helper ;
    public  $icon_path ;
    public function __construct( $container )
    {
        $this->container = $container;
        parent::__construct();
    }
    
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=text-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
        'author'     => 'WP Tools (7-day FREE Trial)',
        'author_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=text-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Single Line Text - MD', 'forms-for-divi' );
        $this->icon_path = $this->container['plugin_dir'] . '/resources/images/single-line-text.svg';
        $this->main_css_element = '%%order_class%%';
    }
    
    /**
     * get the fields helper class *
     */
    public function helper()
    {
        
        if ( !$this->helper ) {
            $this->helper = new Fields( $this->container );
            $this->helper->set_module( $this );
        }
        
        return $this->helper;
    }
    
    /**
     * get the module toggles *
     */
    public function get_settings_modal_toggles()
    {
        return [
            'general' => [
            'toggles' => [
            'main'       => esc_html__( 'General', 'et_builder' ),
            'validation' => esc_html__( 'Validation', 'et_builder' ),
        ],
        ],
        ];
    }
    
    /**
     * get the css fields for advanced divi module settings *
     */
    public function get_custom_css_fields_config()
    {
        return $this->helper()->get_css_fields();
    }
    
    /**
     * get the advanced field for divi module settings *
     */
    public function get_advanced_fields_config()
    {
        $config['border'] = false;
        $config['borders'] = false;
        $config['text'] = false;
        $config['box_shadow'] = false;
        $config['filters'] = false;
        $config['animation'] = false;
        $config['text_shadow'] = false;
        $config['max_width'] = false;
        $config['margin_padding'] = false;
        $config['custom_margin_padding'] = false;
        $config['background'] = false;
        $config['fonts'] = false;
        $config['link_options'] = false;
        $config['transform'] = false;
        return $config;
    }
    
    /**
     * get the divi module fields *
     */
    public function get_fields()
    {
        return $this->helper()->get_fields();
    }
    
    /**
     * Render the divi module *
     */
    public function render( $attrs, $content = null, $render_slug = null )
    {
        $attrs = wp_parse_args( $attrs, $this->helper()->get_defaults() );
        $this->add_classname( [ 'wpt_form_module' ] );
        $label = $this->container['divi']->get_prop_value( $this, 'label' );
        $name = $this->container['divi']->get_prop_value( $this, 'name' );
        $default = $this->container['divi']->get_prop_value( $this, 'default' );
        $placeholder = $this->container['divi']->get_prop_value( $this, 'placeholder' );
        $helper_text = $this->container['divi']->get_prop_value( $this, 'helper_text' );
        $enable_autocomplete = $this->container['divi']->get_prop_value( $this, 'enable_autocomplete' ) == 'on';
        $enable_autocomplete = $this->container['divi']->get_prop_value( $this, 'enable_autocomplete' ) == 'on';
        $autocomplete_attribute = $this->container['divi']->get_prop_value( $this, 'autocomplete_attribute' );
        $enable_password_field = $this->container['divi']->get_prop_value( $this, 'enable_password_field' ) == 'on';
        $text_field = $this->container['text_field']->set_name( $name )->set_enable_autocomplete( $enable_autocomplete )->set_autocomplete_attribute( $autocomplete_attribute )->set_enable_password_field( $enable_password_field )->set_label( $label )->set_default( $default )->set_helper_text( $helper_text )->set_placeholder( $placeholder );
        $field_required = $this->container['divi']->get_prop_value( $this, 'field_required' ) == 'on';
        
        if ( $field_required ) {
            $field_required_message = $this->container['divi']->get_prop_value( $this, 'field_required_message' );
            $text_field->add_constraint( 'required', [
                'error_message' => $field_required_message,
            ] );
            $text_field->set_is_required( true );
        }
        
        $field_minlength = $this->container['divi']->get_prop_value( $this, 'field_minlength' ) == 'on';
        
        if ( $field_minlength ) {
            $field_minlength_message = $this->container['divi']->get_prop_value( $this, 'field_minlength_message' );
            $field_minlength_value = $this->container['divi']->get_prop_value( $this, 'field_minlength_value' );
            $text_field->add_constraint( 'minlength', [
                'error_message' => $field_minlength_message,
                'value'         => $field_minlength_value,
            ] );
        }
        
        $field_maxlength = $this->container['divi']->get_prop_value( $this, 'field_maxlength' ) == 'on';
        
        if ( $field_maxlength ) {
            $field_maxlength_message = $this->container['divi']->get_prop_value( $this, 'field_maxlength_message' );
            $field_maxlength_value = $this->container['divi']->get_prop_value( $this, 'field_maxlength_value' );
            $text_field->add_constraint( 'maxlength', [
                'error_message' => $field_maxlength_message,
                'value'         => $field_maxlength_value,
            ] );
        }
        
        \ET_Builder_Element::set_style( $render_slug, [
            'selector'    => $this->helper()->get_selector( 'field' ),
            'declaration' => 'background:none; border: none;',
        ] );
        return $text_field->html();
    }
    
    /**
     * Get the default value for the field *
     */
    public function get_default( $key )
    {
        return $this->helper()->get_default( $key );
    }
    
    /**
     * Get the css selector *
     */
    public function get_selector( $key )
    {
        return $this->helper()->get_selector( $key );
    }

}