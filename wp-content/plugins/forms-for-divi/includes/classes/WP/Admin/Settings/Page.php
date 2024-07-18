<?php
namespace WPT\DiviForms\WP\Admin\Settings;

/**
 * Page.
 */
class Page
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Setup admin menu
     */
    public function admin_menu()
    {
        // Add the menu item and page
        add_menu_page('Divi Forms - Getting Started', 'Divi Forms', 'manage_options', 'divi-forms-getting-started', [$this, 'getting_started'], 'dashicons-welcome-widgets-menus');

        $parent_slug = 'divi-forms-getting-started';
        $page_title  = 'Divi Forms Settings';
        $menu_title  = 'Settings';
        $capability  = 'manage_options';
        $menu_slug   = 'wpt-divi-forms-settings';
        $callback    = [$this, 'page_content'];
        $icon        = 'dashicons-admin-plugins';
        $position    = 2;
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback, $position);

    }

    /**
     * Getting started admin menu contents
     */
    public function getting_started()
    {
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/admin/getting_started.php';
        // phpcs:ignore
        echo ob_get_clean();
    }

    /**
     * Menu page contents
     */
    public function page_content()
    {
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/admin/settings.php';
        // phpcs:ignore
        echo ob_get_clean();
    }

    public function setup_sections()
    {
        add_settings_section('google-recaptcha-v2', 'Google Recaptcha v2', [$this, 'v2_section_callback'], 'wpt_google_recaptcha_fields');
        add_settings_section('google-recaptcha-v3', 'Google Recaptcha v3', [$this, 'v3_section_callback'], 'wpt_google_recaptcha_fields');

        register_setting('wpt_google_recaptcha_fields', 'wpt_gr_v2_key');
        register_setting('wpt_google_recaptcha_fields', 'wpt_gr_v2_secret');
        register_setting('wpt_google_recaptcha_fields', 'wpt_gr_v3_key');
        register_setting('wpt_google_recaptcha_fields', 'wpt_gr_v3_secret');
    }

    /**
     * Section callback
     */
    public function v2_section_callback($arguments)
    {
        echo '
        <p>For details, read the <a target="_blank" href="https://wptools.app/how-to/add-google-recaptcha-spam-protection-to-divi-contact-form/?utm_source=source-code&amp;utm_medium=settings-page&amp;utm_campaign=divi-forms&amp;utm_content=settings">Create Google reCAPTCHA v2 Profile</a> how to.</p>
    ';
    }

    public function v3_section_callback($arguments)
    {
        echo '<p>For details, read the <a target="_blank" href="https://wptools.app/how-to/add-google-recaptcha-spam-protection-to-divi-contact-form/?utm_source=source-code&amp;utm_medium=settings-page&amp;utm_campaign=divi-forms&amp;utm_content=settings">Create Google reCAPTCHA v3 Profile</a> how to.</p>';
    }

    public function setup_fields()
    {
        add_settings_field('wpt_gr_v2_key', 'Site Key', [$this, 'wpt_gr_v2_key_field_callback'], 'wpt_google_recaptcha_fields', 'google-recaptcha-v2');
        add_settings_field('wpt_gr_v2_secret', 'Secret Key', [$this, 'wpt_gr_v2_secret_field_callback'], 'wpt_google_recaptcha_fields', 'google-recaptcha-v2');

        add_settings_field('wpt_gr_v3_key', 'Site Key', [$this, 'wpt_gr_v3_key_field_callback'], 'wpt_google_recaptcha_fields', 'google-recaptcha-v3');
        add_settings_field('wpt_gr_v3_secret', 'Secret Key', [$this, 'wpt_gr_v3_secret_field_callback'], 'wpt_google_recaptcha_fields', 'google-recaptcha-v3');

    }

    public function wpt_gr_v2_key_field_callback($arguments)
    {
        echo '<input name="wpt_gr_v2_key" id="wpt_gr_v2_key" type="text" class="large-text" value="' . esc_attr(get_option('wpt_gr_v2_key')) . '" />';
    }

    public function wpt_gr_v2_secret_field_callback($arguments)
    {
        echo '<input name="wpt_gr_v2_secret" id="wpt_gr_v2_secret" type="text" class="large-text" value="' . esc_attr(get_option('wpt_gr_v2_secret')) . '" />';
    }

    public function wpt_gr_v3_key_field_callback($arguments)
    {
        echo '<input name="wpt_gr_v3_key" id="wpt_gr_v3_key" type="text" class="large-text" value="' . esc_attr(get_option('wpt_gr_v3_key')) . '" />';
    }

    public function wpt_gr_v3_secret_field_callback($arguments)
    {
        echo '<input name="wpt_gr_v3_secret" id="wpt_gr_v3_secret" type="text" class="large-text" value="' . esc_attr(get_option('wpt_gr_v3_secret')) . '" />';
    }

}
