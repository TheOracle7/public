<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'DE_FB_options_interface' ) ) {
    class DE_FB_options_interface {

        var $licence;

        const P_CODE = 'DE_FB';

        function __construct() {

            $this->licence          =   new DE_FB_LICENSE();

            if (isset($_GET['page']) && ($_GET['page'] == 'de-fb-options'  ||  $_GET['page'] == 'divi-fb-license')) // phpcs:ignore
            {
                add_action( 'init', array($this, 'options_update'), 1 );
            }

            add_action( 'init', array( $this, 'download_fb_entries' ) );
            add_action( 'init', array( $this, 'process_actions' ) );

            add_action( 'admin_menu', array($this, 'admin_menu') );
            add_action( 'network_admin_menu', array($this, 'network_admin_menu') );

            $de_get = get_option( 'de_plugins', array() );

            $product_key = array_search( self::P_CODE, $de_get);

            if(!$this->licence->licence_key_verify())
            {
                add_action('admin_notices', array($this, 'admin_no_key_notices'));
                add_action('network_admin_notices', array($this, 'admin_no_key_notices'));
                // add_action('admin_menu', array($this, 'admin_remove_menu'));
                
                if ( $product_key === false ) {
                    $de_get[] = self::P_CODE;
                }
            } else {
                if ( $product_key !== false  ) {
                    unset( $de_get[ $product_key ] );
                }
            }

            update_option( 'de_plugins', $de_get );            
        }

        function __destruct() {

        }

        function network_admin_menu()
        {
            if(!$this->licence->licence_key_verify()) {
                $hookID   = add_submenu_page('divi-engine', 'Divi Form Builder License', 'Divi Form Builder License', 'manage_options', 'de-fb-options', array($this, 'license_form_divi_fb'));
                $formID   = add_submenu_page('divi-engine', 'Divi Form Entries', 'Divi Form Entries', 'manage_options', 'de-fb-entries', array($this, 'divi_fb_form_entries'));
            } else {
                $hookID   = add_submenu_page('divi-engine', 'Divi Form Builder License', 'Divi Form Builder License', 'manage_options', 'de-fb-options', array($this, 'licence_deactivate_form'));
                $formID   = add_submenu_page('divi-engine', 'Divi Form Entries', 'Divi Form Entries', 'manage_options', 'de-fb-entries', array($this, 'divi_fb_form_entries'));

                add_action('load-' . $hookID , array($this, 'load_dependencies'));
                add_action('load-' . $hookID , array($this, 'admin_notices'));

                add_action('load-' . $formID , array($this, 'load_dependencies'));
                add_action('load-' . $formID , array($this, 'admin_notices'));

                //add_action('admin_print_styles-' . $hookID , array($this, 'admin_print_styles'));
                //add_action('admin_print_scripts-' . $hookID , array($this, 'admin_print_scripts'));

                //add_action('admin_print_styles-' . $formID , array($this, 'admin_print_styles'));
                //add_action('admin_print_scripts-' . $formID , array($this, 'admin_print_scripts'));
            }
        }

        function admin_menu() {

            if(!$this->licence->licence_key_verify())
            {
                $hookID   = add_submenu_page('divi-engine', 'Divi Form Builder License', 'Divi Form Builder License', 'manage_options', 'divi-fb-license', array($this, 'license_form_divi_fb'));
                //$formID   = add_submenu_page('divi-engine', 'Divi Form Entries', 'Divi Form Entries', 'manage_options', 'de-fb-entries', array($this, 'divi_fb_form_entries'));
            } else {
                $hookID   = add_submenu_page('divi-engine', 'Divi Form Builder License', 'Divi Form Builder License', 'manage_options', 'divi-fb-license', array($this, 'licence_deactivate_form'));
                
                add_action('load-' . $hookID , array($this, 'load_dependencies'));
                //add_action('load-' . $formID , array($this, 'load_dependencies'));
            }
            $formID   = add_submenu_page('divi-engine', 'Divi Form Entries', 'Divi Form Entries', 'manage_options', 'de-fb-entries', array($this, 'divi_fb_form_entries'));

            add_action('load-' . $hookID , array($this, 'admin_notices'));
            //add_action('load-' . $formID , array($this, 'admin_notices'));
            //add_action('admin_print_styles-' . $hookID , array($this, 'admin_print_styles'));
            //add_action('admin_print_scripts-' . $hookID , array($this, 'admin_print_scripts'));
            //add_action('admin_print_styles-' . $formID , array($this, 'admin_print_styles'));
            //add_action('admin_print_scripts-' . $formID , array($this, 'admin_print_scripts'));
        }

        function options_interface() {
            if(!$this->licence->licence_key_verify() && !is_multisite()) {
                $this->license_form_divi_fb();
                return;
            }

            if(!$this->licence->licence_key_verify() && is_multisite())
            {
                $this->licence_multisite_require_nottice();
                return;
            }
        }

        function options_update() {

            if (isset($_POST['de_fb_licence_form_submit'])) // phpcs:ignore
            {
                $this->licence_form_submit();
                return;
            }

        }

        function load_dependencies() {
        }

        function admin_notices() {
            global $slt_form_submit_messages;

            if($slt_form_submit_messages == '')
                return;

            $messages = $slt_form_submit_messages;

            if(count($messages) > 0) {
                $messages_implode = implode("</p><p>", $messages);
?> 
                <div id='notice' class='updated error'><p><?php echo esc_html( $messages_implode ) ?></p></div> <?php
            }
        }

        function admin_no_key_notices() {
            if ( !current_user_can('manage_options'))
                return;

            $screen = get_current_screen();

            if( !is_network_admin() ) {
                if(isset($screen->id) && $screen->id == 'divi-fb-license')
                    return;
                if ( !is_plugin_active( 'divi-form-builder/divi-form-builder.php' ) )
                    return;
?>
                    <div class="updated error"><p><?php echo esc_html( "Divi Form Builder is inactive, please enter your", 'divi-form-builder' ) ?> <a href="admin.php?page=divi-fb-license"><?php echo esc_html( "License Key", 'divi-form-builder' ) ?></a> to get updates</p>
                    </div>
<?php
            }
        }

        function licence_form_submit() {
            global $slt_form_submit_messages;

            //check for de-activation
            if (isset($_POST['de_fb_licence_form_submit']) && isset($_POST['de_fb_licence_deactivate']) && wp_verify_nonce($_POST['divi_fb_license_nonce'],'divi_fb_license')) {
                global $slt_form_submit_messages;

                $license_data = DE_FB_LICENSE::get_licence_data();
                $license_key = $license_data['key'];

                //build the request query
                $args = array(
                    'woo_sl_action'         => 'deactivate',
                    'licence_key'           => $license_key,
                    'product_unique_id'     => DE_FB_PRODUCT_ID,
                    'domain'                => DE_FB_INSTANCE
                );
                
                $request_uri    = DE_FB_APP_API_URL . '?' . http_build_query( $args , '', '&');
                $data           = wp_remote_get( $request_uri );

                //log if debug
                if (defined('WP_DEBUG') &&  WP_DEBUG    === TRUE) {
                    DE_FB::log_data("------\nArguments:");
                    DE_FB::log_data($args);
                    DE_FB::log_data("\nResponse Body:");
                    DE_FB::log_data($data['body']);
                    DE_FB::log_data("\nResponse Server Response:");
                    DE_FB::log_data($data['response']);
                }

                if(is_wp_error( $data ) || $data['response']['code'] != 200) {
                    if ( $data['response']['code'] == 403 ) {
                        $header_data = $data['headers']->getAll();
                        $cf_ray = $header_data['cf-ray'];
                        $slt_form_submit_messages[] .= __('There was a problem connecting to diviengine.com. It seems our firewall blocked you from accessing our server. Please contact support with this Ray ID: ', 'divi-form-builder') . $cf_ray;
                    } else {
                        $slt_form_submit_messages[] .= __('There was a problem connecting to ', 'divi-form-builder') . DE_FB_APP_API_URL;    
                    }
                    return;
                }

                $response_block = json_decode($data['body']);
                //retrieve the last message within the $response_block
                $response_block = $response_block[count($response_block) - 1];
                $response = $response_block->message;

                if(isset($response_block->status)) {
                    if($response_block->status == 'success' && $response_block->status_code == 's201') {
                        //the license is active and the software is active
                        $slt_form_submit_messages[] = $response_block->message;

                        $license_data = DE_FB_LICENSE::get_licence_data();

                        //save the license
                        $license_data['key']          = '';
                        $license_data['last_check']   = time();

                        DE_FB_LICENSE::update_licence_data( $license_data );
                    }  else if ($response_block->status_code == 'e002' || $response_block->status_code == 'e104' || $response_block->status_code == 'e110') {
                        $license_data = DE_FB_LICENSE::get_licence_data();

                        //save the license
                        $license_data['key']          = '';
                        $license_data['last_check']   = time();

                        DE_FB_LICENSE::update_licence_data( $license_data );
                    } else {

                        $slt_form_submit_messages[] = __('There was a problem deactivating the licence: ', 'divi-form-builder') . $response_block->message;
                        return;
                    }
                } else {
                    $slt_form_submit_messages[] = __('There was a problem with the data block received from ' . DE_FB_APP_API_URL, 'divi-form-builder');
                    return;
                }

                //redirect
                $current_url    =   'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // phpcs:ignore

                wp_redirect($current_url);
                die();
            }

            if (isset($_POST['de_fb_licence_form_submit']) && wp_verify_nonce($_POST['divi_fb_license_nonce'],'divi_fb_license')) {

                $license_key = isset($_POST['license_key'])? sanitize_key(trim($_POST['license_key'])) : ''; // phpcs:ignore

                if($license_key == '') {
                    $slt_form_submit_messages[] = __("License Key can't be empty", 'divi-form-builder');
                    return;
                }

                //build the request query
                $args = array(
                    'woo_sl_action'         => 'activate',
                    'licence_key'       => $license_key,
                    'product_unique_id'        => DE_FB_PRODUCT_ID,
                    'domain'          => DE_FB_INSTANCE
                );

                $request_uri    = DE_FB_APP_API_URL . '?' . http_build_query( $args , '', '&');
                $data           = wp_remote_get( $request_uri );

                //log if debug
                If (defined('WP_DEBUG') &&  WP_DEBUG    === TRUE) {
                    DE_FB::log_data("------\nArguments:");
                    DE_FB::log_data($args);

                    DE_FB::log_data("\nResponse Body:");
                    DE_FB::log_data($data['body']);
                    DE_FB::log_data("\nResponse Server Response:");
                    DE_FB::log_data($data['response']);
                }

                if(is_wp_error( $data ) || $data['response']['code'] != 200) {
                    if ( $data['response']['code'] == 403 ) {
                        $header_data = $data['headers']->getAll();
                        $cf_ray = $header_data['cf-ray'];
                        $slt_form_submit_messages[] .= __('There was a problem connecting to diviengine.com. It seems our firewall blocked you from accessing our server. Please contact support with this Ray ID: ', 'divi-form-builder') . $cf_ray;
                    } else {
                        $slt_form_submit_messages[] .= __('There was a problem connecting to ', 'divi-form-builder') . DE_FB_APP_API_URL;    
                    }
                    
                    return;
                }

                $response_block = json_decode($data['body']);
                //retrieve the last message within the $response_block
                $response_block = $response_block[count($response_block) - 1];
                $response = $response_block->message;

                if(isset($response_block->status)) {
                    if($response_block->status == 'success' && ( $response_block->status_code == 's100' || $response_block->status_code == 's101' ) ) {
                        //the license is active and the software is active
                        $slt_form_submit_messages[] = $response_block->message;

                        $license_data = DE_FB_LICENSE::get_licence_data();

                        //save the license
                        $license_data['key']          = $license_key;
                        $license_data['last_check']   = time();

                        DE_FB_LICENSE::update_licence_data ( $license_data );
                    } else {
                        $slt_form_submit_messages[] = __('There was a problem activating the licence: ', 'divi-form-builder') . $response_block->message;
                        return;
                    }
                } else {
                    $slt_form_submit_messages[] = __('There was a problem with the data block received from ' . DE_FB_APP_API_URL, 'divi-form-builder');
                    return;
                }
    
                //redirect
                $current_url    =   'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // phpcs:ignore

                wp_redirect($current_url);
                die();
            }
        }

        function license_form_divi_fb() {
?>
        <div class="wrap">
            <div id="icon-settings" class="icon32"></div>
            <h2><?php esc_html_e( "Divi Form Builder Software License", 'divi-form-builder' ) ?><br />&nbsp;</h2>

            <form id="form_data" name="form" method="post">
                <div class="postbox">

                <?php wp_nonce_field('divi_fb_license','divi_fb_license_nonce'); ?>
                    <input type="hidden" name="de_fb_licence_form_submit" value="true" />

                    <div class="section section-text ">
                        <h4 class="heading"><?php esc_html_e( "License Key", 'divi-form-builder' ) ?></h4>
                        <div class="option">
                            <div class="controls">
                                <input type="text" value="" name="license_key" class="text-input">
                            </div>
                            <div class="explain"><?php esc_html_e( "Enter the License Key you got when bought this product. If you lost the key, you can always retrieve it from", 'divi-form-builder' ) ?> <a href="http://diviengine.com/my-account/" target="_blank"><?php esc_html_e( "My Account", 'divi-form-builder' ) ?></a><br />
                            <?php esc_html_e( "More keys can be generate from", 'divi-form-builder' ) ?> <a href="http://diviengine.com/my-account/" target="_blank"><?php esc_html_e( "My Account", 'divi-form-builder' ) ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="submit">
                    <input type="submit" name="Submit" class="button button-primary" value="<?php esc_html_e('Save', 'divi-form-builder') ?>">
                </p>
            </form>
        </div>
<?php
        }

        function licence_deactivate_form() {
            $license_data = DE_FB_LICENSE::get_licence_data();

            if(is_multisite()) {
?>
        <div class="wrap">
            <div id="icon-settings" class="icon32"></div>
            <h2><?php esc_html_e( "General Settings", 'divi-form-builder' ) ?></h2>
<?php
            }
?>
        <div id="form_data">
            <h2 class="subtitle"><?php esc_html_e( "Divi Form Builder Software License", 'divi-form-builder' ) ?></h2>
            <div class="postbox">
                <form id="form_data" name="form" method="post">
                    <?php wp_nonce_field('divi_fb_license','divi_fb_license_nonce'); ?>
                    <input type="hidden" name="de_fb_licence_form_submit" value="true" />
                    <input type="hidden" name="de_fb_licence_deactivate" value="true" />

                    <div class="section section-text ">
                        <h4 class="heading"><?php esc_html_e( "License Key", 'divi-form-builder' ) ?></h4>
                        <div class="option">
                            <div class="controls">
<?php
                        if($this->licence->is_local_instance()) {
?>
                                <p>Local instance, no key applied.</p>
<?php
                        } else {
?>
                                <p><b><?php echo esc_html( substr($license_data['key'], 0, 20) ) ?>-xxxxxxxx-xxxxxxxx</b> &nbsp;&nbsp;&nbsp;<a class="button-primary" title="Deactivate" href="javascript: void(0)" onclick="jQuery(this).closest('form').submit();">Deactivate</a></p>
<?php 
                        } 
?>
                            </div>
                            <div class="explain"><?php esc_html_e( "You can generate more keys from", 'divi-form-builder' ) ?> <a href="http://diviengine.com/my-account/" target="_blank">My Account</a></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php

            if(is_multisite()) {
?>
        </div>
<?php
            }
        }

        function download_fb_entries() {

            global $wpdb;

            if ( isset( $_GET['page'] ) && $_GET['page'] == 'de-fb-entries' && isset( $_GET['action'] ) && $_GET['action'] == 'download' ) {

                $tbl_forms = $wpdb->prefix . 'de_contact_forms';
                $tbl_form_entry_name = $wpdb->prefix.'de_contact_form_entries';

                $action = isset( $_REQUEST['action'] )?$_REQUEST['action']:'';

                $form_id = isset($_REQUEST['form_id'])?$_REQUEST['form_id']: -1;
                $form_obj = $wpdb->get_row( "SELECT * FROM {$tbl_forms} WHERE id={$form_id}");

                if ( $action == 'download' && $form_obj ) {

                    $form_entries = $wpdb->get_results("SELECT * FROM {$tbl_form_entry_name} WHERE form_id={$form_id} ORDER BY id DESC");

                    if ( !empty( $form_entries ) ) {

                        $field_title_array = array();

                        if ( !empty( $form_entries ) ) {
                            foreach ($form_entries as $key => $entry) {
                                $content = maybe_unserialize( $entry->form_entry );
                                $field_titles = array_column( $content, 'field_title' );
                                foreach ($field_titles as $field_key => $title) {
                                    if ( !in_array( $title, $field_title_array ) ) {
                                        $field_title_array[] = $title;
                                    }
                                }
                            }
                        }

                        $field_title_array[] = "Submitted Date";

                        $delimiter = ","; 
                        $filename = $form_obj->form_name . "(" . date('Ymd') . ").csv";
                         
                        // Create a file pointer 
                        $f = fopen('php://memory', 'w'); 
                        

                        $reserved_str = chr (0xEF). chr (0xBB). chr (0xBF);
                        fputs($f, $reserved_str, strlen($reserved_str) );
                        // Set column headers 
                        fputcsv($f, $field_title_array, $delimiter); 
                         
                        // Output each row of the data, format line as csv and write to file pointer 
                        foreach ( $form_entries as $ind => $entry ) {
                            $lineData = array();
                            $entry_array = maybe_unserialize( $entry->form_entry );

                            foreach ( $field_title_array as $title_key => $title ) {

                                if ( $title == 'Submitted Date' ) {
                                    $lineData[] = $entry->insert_date;
                                } else {
                                    $field_val = '';
                                    foreach ( $entry_array as $a_key => $entry_field ) {
                                        if ( $entry_field['field_title'] == $title ) {
                                            $field_val = $entry_field['field_val'];
                                            break;
                                        }
                                    }
                                    $lineData[] = $field_val;
                                }
                            }

                            fputcsv($f, $lineData, $delimiter); 
                        }
                        
                        // Move back to beginning of file 
                        fseek($f, 0); 
                         
                        // Set headers to download file rather than displayed 
                        header('Content-Type: application/csv; charset=UTF-8'); 
                        //header("Content-Transfer-Encoding: UTF-8");
                        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
                         
                        //output all remaining data on a file pointer 
                        fpassthru($f); 
                    }
                    exit;
                }
            }
        }

        function process_actions() {
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'de-fb-entries' && isset($_GET['action']) && $_GET['action'] != '' ) {
                global $wpdb;
                $tbl_forms = $wpdb->prefix . 'de_contact_forms';
                $tbl_form_entry_name = $wpdb->prefix.'de_contact_form_entries';

                $action = $_GET['action'];

                $form_id = isset($_REQUEST['form_id'])?$_REQUEST['form_id']: -1;

                if ( $action == 'clear' && $form_obj ) {
                    $wpdb->delete( $tbl_form_entry_name, array( 'form_id' => $form_id ) );
                }

                if ( $action == 'delete' ) {
                    $entry_ids = $_REQUEST['ids'];
                    $result = $wpdb->query( "DELETE FROM {$tbl_form_entry_name} WHERE id IN ($entry_ids)" );
                    wp_safe_redirect( admin_url('admin.php?page=de-fb-entries&action=view&form_id='.$form_id.'&deleted_entries='.$result ) );
                    exit;
                }

                if ( $action == 'delete_form' ) {
                    $form_ids = $_REQUEST['ids'];
                    $result = $wpdb->query( "DELETE FROM {$tbl_forms} WHERE id IN ($form_ids)" );
                    $wpdb->query( "DELETE FROM {$tbl_form_entry_name} WHERE form_id IN ($form_ids)" );
                    wp_safe_redirect( admin_url('admin.php?page=de-fb-entries&deleted_forms='.$result ) );
                    exit;
                }
            }
        }

        function divi_fb_form_entries() {

            global $wpdb;
            $tbl_forms = $wpdb->prefix . 'de_contact_forms';
            $tbl_form_entry_name = $wpdb->prefix.'de_contact_form_entries';

            $action = isset( $_REQUEST['action'] )?$_REQUEST['action']:'';

            $form_id = isset($_REQUEST['form_id'])?$_REQUEST['form_id']: -1;
            $form_obj = $wpdb->get_row( "SELECT * FROM {$tbl_forms} WHERE id={$form_id}");

            if ( $action != 'view' || $form_id == -1 || !$form_obj  ) {

                $forms = $wpdb->get_results("SELECT forms.*, count(entries.form_id) as entry_cnt FROM {$tbl_forms} forms LEFT JOIN {$tbl_form_entry_name} entries ON forms.id=entries.form_id GROUP BY forms.id" );
?>
        <div class="wrap">
            <div id="icon-settings" class="icon32"></div>
            <h2><?php esc_html_e( "Divi Form Entries", 'divi-form-builder' ) ?><br />&nbsp;</h2>
<?php
        if ( isset( $_REQUEST['deleted_forms'] ) && $_REQUEST['deleted_forms'] != '' ) {
?>
            <div class="notice notice-info is-dismissible">
                <p><?php echo $_REQUEST['deleted_forms'];?> forms have deleted successfully.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>
<?php
        }
?>
            <button type="button" name="delete_forms"  class="button button-primary delete_forms" style="margin:20px 0;">Delete Selected Forms</button>
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <th scope="col" id="sel" class="manage-column column-sel column-primary"><input type="checkbox" class="select_all"></th>
                    <th scope="col" id="title" class="manage-column column-title column-primary"><?php echo esc_html__( 'Title', 'divi-form-builder' );?></th>
                    <th scope="col" id="location" class="manage-column column-location column-primary"><?php echo esc_html__( 'Location', 'divi-form-builder' );?></th>
                    <th scope="col" id="entries" class="manage-column column-entries column-primary"><?php echo esc_html__( 'Entries', 'divi-form-builder' );?></th>
                    <th scope="col" id="actions" class="manage-column column-actions column-primary"><?php echo esc_html__( 'Actions', 'divi-form-builder' );?></th>
                </thead>
                <tbody>
<?php
                if ( !empty( $forms ) ) {
                    foreach ( $forms as $ind => $form ) {
?>
                    <tr>
                        <td><input type="checkbox" class="select_entry" value="<?php echo $form->id;?>"></td>
                        <td><a href="<?php echo admin_url('admin.php?page=de-fb-entries&action=view&form_id='.$form->id );?>"><?php echo $form->form_name;?></a></td>
                        <td><a href="<?php echo get_the_permalink($form->post_id);?>" target="_blank"><?php echo get_the_title( $form->post_id );?></a></td>
                        <td><?php echo $form->entry_cnt;?></td>
                        <td><a href="<?php echo admin_url('admin.php?page=de-fb-entries&action=download&form_id='.$form->id );?>" target="_blank"><?php echo esc_html__( 'Download', 'divi-form-builder' );?></a>&nbsp;|&nbsp;<a href="<?php echo admin_url('admin.php?page=de-fb-entries&action=clear&form_id='.$form->id );?>"><?php echo esc_html__( 'Delete Entries', 'divi-form-builder' );?></a></td>
                    </tr>
<?php
                    }
                }
?>
                </tbody>
            </table>
            <button type="button" name="delete_forms"  class="button button-primary delete_forms" style="margin:20px 0;">Delete Selected Forms</button>
        </div>
        <script>
            jQuery(document).ready(function($){
                $('.select_all').change(function(){
                    if ( $(this).is(':checked') ) {
                        $('.select_entry').prop('checked', true);
                    } else {
                        $('.select_entry').prop('checked', false);
                    }
                });
                $('.select_entry').change(function(){
                    var total_entries = $('.select_entry').length;
                    var checked_entries = $('.select_entry:checked').length;
                    if ( total_entries == checked_entries ) {
                        $('.select_all').prop('checked', true);
                    } else {
                        $('.select_all').prop('checked', false);
                    }
                });

                $('.delete_forms').click(function(e){
                    e.preventDefault();
                    var checked_entries = $('.select_entry:checked');
                    var checked_ids = [];
                    if ( checked_entries.length > 0 ) {
                        checked_entries.each(function(){
                            checked_ids.push( $(this).val() );
                        });

                        var url = "<?php echo admin_url('admin.php?page=de-fb-entries&action=delete_form' );?>&ids=" + checked_ids.join(',');
                        document.location.href=url;
                    }
                });
            });
        </script>
        <style type="text/css">
            .column-sel{
                width: 30px;
            }
            .column-sel input{
                margin-left: 0!important;
            }
        </style>
<?php
            } else if ( $action == 'view' ) {
                $form_entries = $wpdb->get_results("SELECT * FROM {$tbl_form_entry_name} WHERE form_id={$form_id} ORDER BY id DESC");
?>
        <div class="wrap">
            <div id="icon-settings" class="icon32"></div>
            <h2><?php esc_html_e( "View Form Entries", 'divi-form-builder' ) ?>(<?php echo $form_obj->form_name;?>)<br />&nbsp;</h2>

<?php
        if ( isset( $_REQUEST['deleted_entries'] ) && $_REQUEST['deleted_entries'] != '' ) {
?>
            <div class="notice notice-info is-dismissible">
                <p><?php echo $_REQUEST['deleted_entries'];?> entries have deleted successfully.</p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>
<?php
        }

                $field_title_array = array();

                if ( !empty( $form_entries ) ) {
                    foreach ($form_entries as $key => $entry) {
                        $content = maybe_unserialize( $entry->form_entry );
                        $field_titles = array_column( $content, 'field_title' );
                        foreach ($field_titles as $field_key => $title) {
                            if ( !in_array( $title, $field_title_array ) ) {
                                $field_title_array[] = $title;
                            }
                        }
                    }
                }
?>
            <p><a href="<?php echo admin_url('admin.php?page=de-fb-entries');?>" style="font-size:16px;line-height: 20px;">← <?php echo esc_html__( 'Back to Form List', 'divi-form-builder' );?></a></p>
            <button type="button" name="delete_entries"  class="button button-primary delete_entries" style="margin:20px 0;">Delete Selected Entries</button>
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <th><input type="checkbox" class="select_all"></th>
<?php
                if ( !empty( $field_title_array ) ) {
                    foreach( $field_title_array as $ind=>$title ) {
?>                    
                    <th scope="col" id="title" class="manage-column column-title column-primary"><?php echo esc_html__( $title, 'divi-form-builder' );?></th>
<?php 
                    }
                    echo '<th scope="col" id="date" class="manage-column column-date column-primary">' . esc_html__('Submitted Date', 'divi-form-builder') . '</th>';
                }
?>
                </thead>
                <tbody>
<?php
                if ( !empty( $form_entries ) ) {
                    foreach ( $form_entries as $ind => $entry ) {
                        $entry_array = maybe_unserialize( $entry->form_entry );
?>
                    <tr>
                        <td><input type="checkbox" class="select_entry" value="<?php echo $entry->id;?>"></td>
<?php
                        foreach( $field_title_array as $title_key => $title ) {
                            $field_val = '';
                            foreach ($entry_array as $key => $field) {
                                if ( $field['field_title'] == $title ) {
                                    $field_val = $field['field_val'];
                                    break;
                                }
                            }
?>
                        <td><?php echo $field_val;?></td>
<?php                            
                        }
                        echo '<td>' . $entry->insert_date . '</td>';
?>                        
                    </tr>
<?php                        
                    }
                }
?>
                </tbody>
            </table>
            <button type="button" name="delete_entries" class="button button-primary delete_entries" style="margin-top:20px;">Delete Selected Entries</button>
        </div>
        <script>
            jQuery(document).ready(function($){
                $('.select_all').change(function(){
                    if ( $(this).is(':checked') ) {
                        $('.select_entry').prop('checked', true);
                    } else {
                        $('.select_entry').prop('checked', false);
                    }
                });
                $('.select_entry').change(function(){
                    var total_entries = $('.select_entry').length;
                    var checked_entries = $('.select_entry:checked').length;
                    if ( total_entries == checked_entries ) {
                        $('.select_all').prop('checked', true);
                    } else {
                        $('.select_all').prop('checked', false);
                    }
                });

                $('.delete_entries').click(function(e){
                    e.preventDefault();
                    var checked_entries = $('.select_entry:checked');
                    var checked_ids = [];
                    if ( checked_entries.length > 0 ) {
                        checked_entries.each(function(){
                            checked_ids.push( $(this).val() );
                        });

                        var url = "<?php echo admin_url('admin.php?page=de-fb-entries&action=delete&form_id='.$form_id );?>&ids=" + checked_ids.join(',');
                        document.location.href=url;
                    }
                });
            });
        </script>
<?php                
            }
        }

        function licence_multisite_require_nottice() {
?>
        <div class="wrap">
            <div id="icon-settings" class="icon32"></div>
            <h2><?php esc_html_e( "General Settings", 'divi-form-builder' ) ?></h2>
            <h2 class="subtitle"><?php esc_html_e( "Divi Form Builder Software License", 'divi-form-builder' ) ?></h2>
            <div id="form_data">
                <div class="postbox">
                    <div class="section section-text ">
                        <h4 class="heading"><?php esc_html_e( "License Key Required", 'divi-form-builder' ) ?>!</h4>
                        <div class="option">
                            <div class="explain">
                                <?php esc_html_e( "Enter the License Key you got when bought this product. If you lost the key, you can always retrieve it from", 'divi-form-builder' ) ?> 
                                <a href="http://diviengine.com/my-account/" target="_blank"><?php esc_html_e( "My Account", 'divi-form-builder' ) ?></a><br />
                                <?php esc_html_e( "More keys can be generate from", 'divi-form-builder' ) ?> 
                                <a href="http://diviengine.com/my-account/" target="_blank"><?php esc_html_e( "My Account", 'divi-form-builder' ) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
        }
        
        function admin_remove_menu() {
            remove_submenu_page('divi-engine', 'de-fb-entries');   
        }
    }
}