<?php

namespace WPT_DiviForms_Divi_Modules\SubmitButtonField;

use  ET_Builder_Module ;
class SubmitButtonField extends ET_Builder_Module
{
    public  $slug = 'wpt_form_submit_button_field' ;
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
        'module_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=submit-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
        'author'     => 'WP Tools (7-day FREE Trial)',
        'author_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=submit-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Submit Button - MD', '' );
        $this->icon_path = $this->container['plugin_dir'] . '/resources/images/button.svg';
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
            'main_content' => esc_html__( 'Main Content', 'et_builder' ),
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
        $button_label = $this->container['divi']->get_prop_value( $this, 'button_label' );
        $full_width = $this->container['divi']->get_prop_value( $this, 'full_width' ) == 'on';
        $spam_protection = $this->container['spam_protection'];
        $spam_protection->enqueue_script();
        $submit_button_field = $this->container['submit_button_field']->show_label( true )->set_name( 'submit' )->set_label( $button_label )->set_default( '' )->set_placeholder( '' );
        if ( $full_width ) {
            $submit_button_field->add_class( 'wpt-full-width' );
        }
        return $spam_protection->html() . $submit_button_field->html();
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