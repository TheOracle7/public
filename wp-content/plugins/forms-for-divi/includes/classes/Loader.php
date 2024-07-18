<?php

namespace WPT\DiviForms;

use  WPTools\Pimple\Container ;
/**
 * Container
 */
class Loader extends Container
{
    /**
     *
     * @var mixed
     */
    public static  $instance ;
    public function __construct()
    {
        parent::__construct();
        $this['bootstrap'] = function ( $container ) {
            return new WP\Bootstrap( $container );
        };
        $this['util'] = function ( $container ) {
            return new WP\Util( $container );
        };
        $this['rest'] = function ( $container ) {
            return new WP\Rest( $container );
        };
        $this['divi_framework'] = function ( $container ) {
            return new Divi\Framework( $container );
        };
        $this['divi_section_fields'] = function ( $container ) {
            return new Divi\Section\Fields( $container );
        };
        $this['divi_section_toggles'] = function ( $container ) {
            return new Divi\Section\Toggles( $container );
        };
        $this['divi_section_content'] = function ( $container ) {
            return new Divi\Section\Content( $container );
        };
        $this['form'] = function ( $container ) {
            return new Form\Form( $container );
        };
        $this['form_notification'] = $this->factory( function ( $container ) {
            return new Form\Notification( $container );
        } );
        $this['str'] = function ( $container ) {
            return new WP\Str( $container );
        };
        $this['form_builder'] = function ( $container ) {
            return new Form\Builder( $container );
        };
        $this['form_csrf'] = function ( $container ) {
            return new Form\Csrf( $container );
        };
        $this['divi'] = function ( $container ) {
            return new Divi\Divi( $container );
        };
        $this['request'] = function ( $container ) {
            return new Form\Request( $container );
        };
        $this['session'] = function ( $container ) {
            return new Form\Session( $container );
        };
        $this['form_validation'] = function ( $container ) {
            return new Form\Validator\Validation( $container );
        };
        $this['validation_constraints'] = $this->factory( function ( $container ) {
            return new Form\Validator\Constraints\Constraints( $container );
        } );
        $this['text_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\Text( $container );
        } );
        $this['email_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\Email( $container );
        } );
        $this['textarea_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\TextArea( $container );
        } );
        $this['checkboxes_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\Checkboxes( $container );
        } );
        $this['radio_buttons_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\RadioButtons( $container );
        } );
        $this['select_menu_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\SelectMenu( $container );
        } );
        $this['file_upload_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\FileUpload( $container );
        } );
        $this['consent_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\Consent( $container );
        } );
        $this['submit_button_field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\SubmitButton( $container );
        } );
        $this['field'] = $this->factory( function ( $container ) {
            return new Form\InputFields\Field( $container );
        } );
        $this['crypt'] = function ( $container ) {
            // phpcs:ignore
            return new WP\Crypt( $container );
        };
        $this['secret'] = function ( $container ) {
            return new WP\Secret( $container );
        };
        $this['entry'] = function ( $container ) {
            return new Form\Entry( $container );
        };
        $this['divi_validation_fields'] = function ( $container ) {
            return new \WPT_DiviForms_Divi_Modules\ValidationFields( $container );
        };
        $this['spam_protection'] = function ( $container ) {
            return new Form\SpamProtection( $container );
        };
        $this['admin_settings_page'] = function ( $container ) {
            return new WP\Admin\Settings\Page( $container );
        };
    }
    
    /**
     * Get container instance.
     */
    public static function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Loader();
        }
        return self::$instance;
    }
    
    /**
     * Plugin run
     */
    public function run()
    {
        register_activation_hook( $this['plugin_file'], [ $this['bootstrap'], 'register_activation_hook' ] );
        add_action( 'et_builder_framework_loaded', [ $this['divi_framework'], 'et_builder_framework_loaded' ] );
        add_action( 'et_builder_ready', [ $this['divi_framework'], 'et_builder_ready' ], 1 );
        add_action( 'divi_extensions_init', [ $this['divi_framework'], 'divi_extensions_init' ] );
        add_filter(
            'do_parse_request',
            [ $this['bootstrap'], 'do_parse_request' ],
            10,
            3
        );
        add_action( 'admin_menu', [ $this['admin_settings_page'], 'admin_menu' ] );
        if ( $this['divi']->is_visual_builder_request() ) {
            add_action( 'wp_enqueue_scripts', [ $this['bootstrap'], 'enqueue_scripts' ] );
        }
        $container = $this;
        add_action( 'rest_api_init', function () use( $container ) {
            register_rest_route( 'wpt-forms', 'v1/styles', [
                'methods'             => 'POST',
                'callback'            => [ $container['rest'], 'styles' ],
                'permission_callback' => function () {
                return true;
            },
            ] );
        } );
        add_action( 'admin_init', [ $this['admin_settings_page'], 'setup_sections' ] );
        add_action( 'admin_init', [ $this['admin_settings_page'], 'setup_fields' ] );
        if ( !wpt_ffd_fs()->is_premium() ) {
            add_action( 'wp_enqueue_scripts', function () {
                $inline_css = '.wpt-divi-forms .et_pb_row{padding-bottom: 0;padding-top: 0;}';
                wp_register_style( 'wpt-divi-forms-free-inline-css', false );
                wp_enqueue_style( 'wpt-divi-forms-free-inline-css' );
                wp_add_inline_style( 'wpt-divi-forms-free-inline-css', $inline_css );
            } );
        }
    }

}