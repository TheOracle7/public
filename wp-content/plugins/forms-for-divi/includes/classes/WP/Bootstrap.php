<?php
namespace WPT\DiviForms\WP;

use Exception;

/**
 * Bootstrap.
 */
class Bootstrap
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function do_parse_request(
        $bool,
        $wp,
        $extra_query_vars
    ) {
        $this->init();
        return $bool;
    }

    public function init()
    {
        // start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $form = $this->container['form'];

        try {
            $response = $form->process();
        } catch (Exception $e) {
            if ($e->getCode() == 200) {
                $this->container['session']->set_valid_request($form->get_success_message());
            }

            if ($e->getCode() == 400) {
                $this->container['session']->set_invalid_request($form->get_error_message());
            }

        }
    }

    /**
     * Register activation hook
     */
    public function register_activation_hook()
    {

        // create form table.
        global $wpdb;
        if (is_multisite() && $network_wide) {
            // phpcs:ignore
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                $this->create_table();
                restore_current_blog();
            }
        } else {
            $this->create_table();
        }

        flush_rewrite_rules(true);
    }

    public function table_name()
    {
        global $wpdb;
        return $wpdb->prefix . 'wpt_form_entries';
    }

    /**
     * Create table
     */
    public function create_table()
    {
        global $wpdb;
        $table_name = $this->table_name();

        // phpcs:ignore
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
            `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `form_name` varchar(255) NOT NULL,
            `form_post_url` varchar(2048) NOT NULL,
            `form_data` longtext NOT NULL,
            `form_date` datetime DEFAULT '1970-01-01 00:00:00' NOT NULL,
            `form_read` tinyint(1) default 0,
            PRIMARY KEY (`ID`),
            KEY `form_name`(`form_name`),
            KEY `form_date`(`form_date`)
        ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }
    }

    /**
     * Enqueue base scripts and styles.
     */
    public function enqueue_scripts()
    {
        $plugin_url     = $this->container['plugin_url'];
        $plugin_version = $this->container['plugin_version'];

        wp_enqueue_style(
            'wpt-form-mdc-base',
            $plugin_url . '/css/material-components-web.min.css',
            $plugin_version
        );

        if (WPT_FFD_DEBUG) {
            wp_enqueue_style(
                'wpt-form-mdc-custom',
                $plugin_url . '/css/styles-v2.css',
                ['wpt-form-mdc-base'],
                $plugin_version
            );
        }

        wp_enqueue_script(
            'wpt-form-mdc-base',
            $plugin_url . '/js/material-components-web.min.js',
            ['jquery'],
            $plugin_version,
            false
        );

        if (!is_admin() && WPT_FFD_DEBUG) {
            wp_enqueue_script(
                'wpt-form-custom',
                $plugin_url . '/js/form.js',
                ['wpt-form-mdc-base'],
                $plugin_version,
                true
            );
        }

        if (!wpt_ffd_fs()->is_premium()) {
            wp_add_inline_style('wpt-form-mdc-custom', '.wpt-divi-forms .et_pb_row { padding-top: 0; padding-bottom: 0;}');
        }

    }

    /**
     * Get uploads directory.
     */
    public function get_upload_dir()
    {
        $wp_upload_dir = wp_upload_dir();
        $dir           = $wp_upload_dir['basedir'] . '/wpt_divi_forms_uploads';

        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
            // phpcs:ignore
            $fp = fopen($dir . '/index.php', 'w');
            // phpcs:ignore
            fwrite($fp, "<?php \n\t // Silence is golden.");
            // phpcs:ignore
            fclose($fp);
        }

        return $dir;
    }

    public function admin_menu()
    {

        wp_enqueue_style('wpt-divi-forms-admin-style', $this->container['plugin_url'] . '/css/admin.css');

        $hook_name = add_menu_page(__('Divi Forms', 'forms-for-divi'), __('Divi Forms', 'forms-for-divi'), 'manage_options', 'divi-forms-settings', [$this, 'listing_view'], 'dashicons-list-view');

        add_action("load-$hook_name", [$this, 'init_list_table']);
    }

}
