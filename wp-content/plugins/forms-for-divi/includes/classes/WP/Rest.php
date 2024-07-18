<?php

namespace WPT\DiviForms\WP;

/**
 * Rest.
 */
class Rest
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
     * Rest API - For Divi builder styles.
     */
    public function styles( $request )
    {
        $styles = '';
        $props = $request->get_param( 'props' );
        $selector = $request->get_param( 'selector' );
        $address = ( $selector ? str_replace( '.et_pb_section_', '', $selector ) : 0 );
        return [
            'styles' => $styles,
        ];
    }

}