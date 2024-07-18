<?php
namespace WPT\DiviForms\Form;

/**
 * Session.
 */
class Session
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function clear()
    {
        $keys = [
            'wpt_form_request_status',
            'wpt_form_errors',
            'wpt_form_data',
            'wpt_form_error_message',
            'wpt_form_success_message',
        ];

        foreach ($keys as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }

    }

    /**
     * Set request to be invalid
     */
    public function set_invalid_request($message)
    {
        $_SESSION['wpt_form_request_status'] = 400;
        $_SESSION['wpt_form_error_message']  = $message;

        if (isset($_SESSION['wpt_form_success_message'])) {
            unset($_SESSION['wpt_form_success_message']);
        }
    }

    /**
     * Set request to be valid
     */
    public function set_valid_request($message)
    {
        $_SESSION['wpt_form_request_status'] = 200;

        $_SESSION['wpt_form_success_message'] = $message;

        if (isset($_SESSION['wpt_form_error_message'])) {
            unset($_SESSION['wpt_form_error_message']);
        }
    }

    /**
     *  get form status.
     */
    public function get_form_status()
    {
        if (isset($_SESSION['wpt_form_request_status'])) {
            return $_SESSION['wpt_form_request_status'];
        }

        return 301;
    }

    /**
     * Get success message
     */
    public function get_success_message()
    {
        $allowed_tags = wp_kses_allowed_html('post');
        return isset($_SESSION['wpt_form_success_message']) ? wp_kses($_SESSION['wpt_form_success_message'], $allowed_tags) : '';
    }

    /**
     * Get error message
     */
    public function get_form_error_message()
    {
        $allowed_tags = wp_kses_allowed_html('post');
        return isset($_SESSION['wpt_form_error_message']) ? wp_kses($_SESSION['wpt_form_error_message'], $allowed_tags) : '';
    }

}
