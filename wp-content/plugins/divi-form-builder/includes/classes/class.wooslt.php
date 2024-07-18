<?php

if ( ! defined( 'ABSPATH' ) ) { exit;}
if ( !class_exists( 'DE_FB' ) ) {
    class DE_FB  {
        var $licence;

        var $interface;

        public static $divi_layouts = array();

        /**
        *
        * Run on class construct
        *
        */
        function __construct( ) {
            $this->licence              =   new DE_FB_LICENSE();

            $this->interface            =   new DE_FB_options_interface();

        }

        static function log_data( $data ) {

            $data   =   (array)$data;

            $fp = fopen( DE_FB_PATH . '/log.txt', 'a');


            foreach($data   as  $key    =>  $line) {
                $key    =   trim($key);

                if(!empty($key))
                    fwrite($fp, $key . " " . $line ."\n");
                else
                    fwrite($fp, $line ."\n");
            }

            fclose($fp);
        }

        static function get_divi_layouts(  ){

            if ( empty( self::$divi_layouts ) ) {
                $layout_query = array(
                    'post_type'=>'et_pb_layout'
                    , 'posts_per_page'=>-1
                    , 'meta_query' => array(
                            array(
                                    'key' => '_et_pb_predefined_layout',
                                    'compare' => 'NOT EXISTS',
                            ),
                    )
                );

                self::$divi_layouts['none'] = 'No Layout (please choose one)';
                if ($layouts = get_posts($layout_query)) {
                    foreach ($layouts as $layout) {
                        self::$divi_layouts[$layout->ID] = $layout->post_title;
                    }
                }
            }
            return self::$divi_layouts;     
        }
    }
}
