<?php

namespace WPT_DiviForms_Divi_Modules\RadioButtonsField;

use  ET_Builder_Module ;
class RadioButtonsField extends ET_Builder_Module
{
    public  $slug = 'wpt_form_radio_buttons_field' ;
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
        'module_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=radio-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
        'author'     => 'WP Tools (7-day FREE Trial)',
        'author_uri' => 'https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=radio-field&utm_medium=divi-module&utm_campaign=divi-forms&utm_content=module-credits',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Radio Buttons - MD', 'forms-for-divi' );
        $this->icon_path = $this->container['plugin_dir'] . '/resources/images/radio-button.svg';
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
        $options = $this->container['divi']->get_prop_value( $this, 'options' );
        $helper_text = $this->container['divi']->get_prop_value( $this, 'helper_text' );
        $layout = $this->container['divi']->get_prop_value( $this, 'layout' );
        $columns = $this->container['divi']->get_prop_value( $this, 'columns' );
        // design
        $responsive = et_pb_responsive_options();
        $responsive_columns = $this->container['divi']->get_responsive_values( 'columns', $this->props, $this->helper()->get_default( 'columns' ) );
        $responsive->generate_responsive_css(
            $responsive_columns,
            $this->helper()->get_selector( 'horizontal_grid' ),
            '--wpt-form-radio-columns',
            $this->slug,
            '',
            'color'
        );
        $radio_buttons_field = $this->container['radio_buttons_field']->set_name( $name )->set_label( $label )->set_default( $default )->set_helper_text( $helper_text )->set_layout( $layout )->set_options( $options );
        $field_required = $this->container['divi']->get_prop_value( $this, 'field_required' ) == 'on';
        
        if ( $field_required ) {
            $field_required_message = $this->container['divi']->get_prop_value( $this, 'field_required_message' );
            $radio_buttons_field->add_constraint( 'required', [
                'error_message' => $field_required_message,
            ] );
            $radio_buttons_field->set_is_required( true );
        }
        
        return $radio_buttons_field->html();
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