<?php

require_once __DIR__ . '/freemius/start.php';

if ( !function_exists( 'wpt_ffd_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wpt_ffd_fs()
    {
        global  $wpt_ffd_fs ;
        
        if ( !isset( $wpt_ffd_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wpt_ffd_fs = fs_dynamic_init( [
                'id'             => '10636',
                'slug'           => 'forms-for-divi',
                'type'           => 'plugin',
                'public_key'     => 'pk_a7299148571f51fcbffeb0ed84e84',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'slug'    => 'divi-forms-getting-started',
                'support' => false,
            ],
                'is_live'        => true,
            ] );
        }
        
        return $wpt_ffd_fs;
    }
    
    // Init Freemius.
    wpt_ffd_fs();
    // Signal that SDK was initiated.
    do_action( 'wpt_ffd_fs_loaded' );
}
