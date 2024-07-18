<?php

namespace WPT\DiviForms\Divi;

/**
 * Framework.
 */
class Framework
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Trigger on et framework load.
     */
    public function et_builder_framework_loaded()
    {
        add_filter( 'et_pb_all_fields_unprocessed_et_pb_section', [ $this->container['divi_section_fields'], 'add' ] );
        add_filter(
            'et_pb_module_shortcode_attributes',
            [ $this->container['divi_section_content'], 'modify_props' ],
            10,
            5
        );
        add_filter(
            'et_builder_get_parent_modules',
            [ $this->container['divi_section_toggles'], 'add' ],
            10,
            2
        );
        add_filter(
            'et_pb_module_content',
            [ $this->container['divi_section_content'], 'et_pb_module_content' ],
            -99,
            6
        );
    }
    
    /**
     * Is divi visual builder request
     */
    public function is_visual_builder_request()
    {
        // phpcs:ignore
        return wp_doing_ajax() || isset( $_GET['et_fb'] );
    }
    
    /**
     * Initialize the divi modules.
     */
    public function et_builder_ready()
    {
        new \WPT_DiviForms_Divi_Modules\TextField\TextField( $this->container );
        new \WPT_DiviForms_Divi_Modules\EmailField\EmailField( $this->container );
        new \WPT_DiviForms_Divi_Modules\TextAreaField\TextAreaField( $this->container );
        new \WPT_DiviForms_Divi_Modules\CheckboxesField\CheckboxesField( $this->container );
        new \WPT_DiviForms_Divi_Modules\RadioButtonsField\RadioButtonsField( $this->container );
        new \WPT_DiviForms_Divi_Modules\SelectMenuField\SelectMenuField( $this->container );
        new \WPT_DiviForms_Divi_Modules\FileUploadField\FileUploadField( $this->container );
        new \WPT_DiviForms_Divi_Modules\ConsentField\ConsentField( $this->container );
        new \WPT_DiviForms_Divi_Modules\SubmitButtonField\SubmitButtonField( $this->container );
    }
    
    /**
     * Intialize divi extension
     */
    public function divi_extensions_init()
    {
        new \WPT_DiviForms_Divi_Modules\DiviFormExtension( $this->container );
    }

}