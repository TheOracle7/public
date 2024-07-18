<?php
namespace WPT\DiviForms\Form;

/**
 * Request.
 */
class Request
{
    protected $container;
    protected $csrf;
    protected $fields = [];
    protected $errors = [];

    protected $csrf_key;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->csrf_key  = $this->container['form_csrf']->get_key();
    }

    public function valid()
    {
        $csrf = $this->container['form_csrf'];

        // phpcs:ignore
        return isset($_POST) && !empty($_POST) && $csrf->hasToken() && isset($_POST[$this->csrf_key]) && $csrf->verify($_POST[$this->csrf_key]);
    }

    public function init()
    {
        $this->fields = [];

        // phpcs:ignore
        if (isset($_POST[$this->csrf_key])) {
            // phpcs:ignore
            $data = $_POST;
            // phpcs:ignore
            $this->csrf = $_POST[$this->csrf_key];

            unset($data[$this->csrf_key]);

            foreach ($data as $name => $value) {
                // form meta
                if ($name == '___fm') {
                    $this->container['form']->hydrate_meta($value);
                    continue;
                }

                if ($name == '__wpt_spam_protection_basic_captcha') {
                    $spam_protection = $this->container['spam_protection'];
                    $spam_protection->set_basic_captcha_answer($value);
                }

                if (strpos($name, '_____') === 0) {
                    // input related data like validation etc
                    if (strlen($name) > 5) {
                        $name = substr($name, 5);

                        $field_value = null;

                        // check for post.
                        if (isset($data[$name])) {
                            $field_value = $data[$name];
                        }

                        // check for file
                        if (isset($_FILES, $_FILES[$name])) {
                            // phpcs:ignore
                            $field_value = $_FILES[$name];
                        }

                        if (!is_null($field_value)) {
                            $general_field = $this->container['field'];
                            $payload       = $general_field->decrypt_payload($value);

                            $field = $this->container[$payload['type'] . '_field'];

                            $field->set_type($payload['type'])
                                ->set_name($name)
                                ->set_value($field_value);

                            $field->rehydrate_validation();

                            $this->fields[] = $field;
                        }
                    }
                }

            }
        }
    }

    /**
     * Check if spam protection check is OK
     */
    public function spam_protection_check($data)
    {
        $spam_protection = $this->container['spam_protection'];

        // spam_protection check
        if ($spam_protection->is_enabled()) {
            $spam_protection_verified = $spam_protection->verify($data);

            if (!$spam_protection_verified) {
                return false;
            }

        }

        return true;
    }

    /**
     * Validate form POST.
     */
    public function validate($data)
    {

        $valid        = true;
        $this->errors = [];

        foreach ($this->fields as $field) {
            $validation_errors = $field->validate();

            if ($valid && !empty($validation_errors)) {
                $valid = false;
            }

            if (!empty($validation_errors)) {
                $this->errors[$field->get_name()] = $validation_errors;
            }

        }

        return $valid;
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function get_session_errors()
    {
        if (isset($_SESSION, $_SESSION['wpt_form_errors'])) {
            return $_SESSION['wpt_form_errors'];
        }

        return [];
    }

    public function has_error($field_name)
    {
        if (isset($_SESSION, $_SESSION['wpt_form_errors'], $_SESSION['wpt_form_errors'][$field_name]) && !empty($_SESSION['wpt_form_errors'][$field_name])) {
            return true;
        } else {
            return false;
        }
    }

    public function get_field_errors($field_name)
    {
        if (isset($_SESSION, $_SESSION['wpt_form_errors'], $_SESSION['wpt_form_errors'][$field_name])) {
            return $_SESSION['wpt_form_errors'][$field_name];
        }

        return [];
    }

    /**
     * get the request uri
     */
    public function request_uri()
    {
        // phpcs:ignore
        return isset($_SERVER['REQUEST_URI']) ? sanitize_url($_SERVER['REQUEST_URI']) : '';
    }

    /**
     * Redirect back to the same page.
     */
    public function redirect_back()
    {
        wp_safe_redirect($this->request_uri(), 302);
        exit;
    }

    /**
     * Form data
     */
    public function get_form_fields()
    {
        return $this->fields;
    }

}
