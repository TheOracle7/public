<?php

namespace WPT\DiviForms\Divi\Section;

/**
 * Toggles.
 */
class Toggles
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    public function add( $parent_modules, $post_type )
    {
        
        if ( isset( $parent_modules['et_pb_section'] ) ) {
            $section = $parent_modules['et_pb_section'];
            $fields = $this->container['divi_section_fields']->get_fields();
            foreach ( array_keys( $fields ) as $key ) {
                if ( isset( $section->fields_unprocessed[$key], $section->fields_unprocessed[$key]['vb_support'] ) ) {
                    unset( $section->fields_unprocessed[$key]['vb_support'] );
                }
            }
            $priority = 999;
            // general
            $section->settings_modal_toggles['general']['toggles']['wpt_form'] = [
                'title'    => __( 'Form', 'forms-for-divi' ),
                'priority' => $priority++,
            ];
            $section->settings_modal_toggles['general']['toggles']['wpt_form_spam_protection'] = [
                'title'    => __( 'Spam Protection', 'forms-for-divi' ),
                'priority' => $priority++,
            ];
            $section->settings_modal_toggles['general']['toggles']['wpt_form_notifications'] = [
                'title'    => __( 'Form Email Notification', 'forms-for-divi' ),
                'priority' => $priority++,
            ];
        }
        
        return $parent_modules;
    }

}