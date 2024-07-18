<?php
namespace WPT\DiviForms\Form;

/**
 * SpamProtection.
 */
class SpamProtection
{
    protected $container;

    protected $is_enabled;
    protected $type;
    protected $key;
    protected $secret;
    protected $threshold;
    protected $error_message;
    protected $first_digit;
    protected $second_digit;
    protected $basic_captcha_answer;
    protected $basic_captcha_validation_message;
    protected $basic_captcha_label_prefix;

    /**
     * Constructor
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->type      = '';
        $this->threshold = 0.5;
    }

    /**
     * Getter function for captcha label prefix
     */
    public function get_basic_captcha_label_prefix()
    {
        return $this->basic_captcha_label_prefix;
    }

    /**
     * Getter function for captcha label prefix
     */
    public function set_basic_captcha_label_prefix($basic_captcha_label_prefix)
    {
        $this->basic_captcha_label_prefix = $basic_captcha_label_prefix;
        return $this;
    }

    /**
     * Getter function for basic captcha validation message.
     */
    public function get_basic_captcha_validation_message()
    {
        return $this->basic_captcha_validation_message;
    }

    /**
     * Getter function for basic captcha validation message.
     */
    public function set_basic_captcha_validation_message($basic_captcha_validation_message)
    {
        $this->basic_captcha_validation_message = $basic_captcha_validation_message;
        return $this;
    }

    /**
     * Getter function for basic captcha answer
     */
    public function set_basic_captcha_answer($basic_captcha_answer)
    {
        $this->basic_captcha_answer = intval($basic_captcha_answer);
        return $this;
    }

    /**
     * Getter function for basic captcha's first digit
     */
    public function get_first_digit()
    {
        $this->first_digit = wp_rand(1, 19);
        return $this->first_digit;
    }

    /**
     * Getter function for basic captcha's first digit
     */
    public function set_first_digit($first_digit)
    {
        $this->first_digit = $first_digit;
        return $this;
    }

    /**
     * Getter function for
     */
    public function get_second_digit()
    {
        $this->second_digit = wp_rand(1, 19);
        return $this->second_digit;
    }

    /**
     * Getter function for
     */
    public function set_second_digit($second_digit)
    {
        $this->second_digit = $second_digit;
        return $this;
    }

    /**
     * Captcha enable check
     */
    public function is_enabled()
    {
        return $this->is_enabled;
    }

    /**
     * Set if enabled
     */
    public function set_is_enabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;
        return $this;
    }

    /**
     * get type
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * get key
     */
    public function get_key()
    {
        return $this->key;
    }

    /**
     * Set captcha type
     */
    public function set_type($type)
    {
        $this->type = $type;

        switch ($type) {
            case 'v2':
                $this->key    = get_option('wpt_gr_v2_key');
                $this->secret = get_option('wpt_gr_v2_secret');
                break;

            case 'v3':
                $this->key    = get_option('wpt_gr_v3_key');
                $this->secret = get_option('wpt_gr_v3_secret');
                break;

            default:
                // code...
                break;
        }

        return $this;
    }

    /**
     * Error message
     */
    public function set_error_message($error_message)
    {
        $this->error_message = $error_message;
        return $this;
    }

    /**
     * Get error message
     */
    public function get_error_message()
    {
        return $this->error_message;
    }

    /**
     * Threshold
     */
    public function set_threshold($threshold)
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Get threshold
     */
    public function get_threshold()
    {
        return $this->threshold;
    }

    /**
     * Get captcha html
     */
    public function html()
    {
        if ($this->is_enabled) {
            switch ($this->type) {
                case 'v2':
                    return sprintf('<div class="g-recaptcha" data-sitekey="%s"></div>', $this->key);
                    break;

                case 'basic_captcha':
                    $basic_captcha = sprintf(
                        '
                <div class="wpt-input-field-container wpt-basic-captcha">
                    <label class="mdc-text-field mdc-text-field--outlined">
                        <span class="mdc-notched-outline">
                            <span class="mdc-notched-outline__leading"></span>
                            <span class="mdc-notched-outline__notch">
                                <span class="mdc-floating-label">%s%s</span>
                            </span>
                            <span class="mdc-notched-outline__trailing"></span>
                        </span>
                        <input type="text" value="" name="__wpt_spam_protection_basic_captcha" class="mdc-text-field__input" aria-labelledby="__wpt_spam_protection_basic_captcha" required autocomplete="off">
                    </label>
                    <div class="mdc-text-field-helper-line">
                        <div class="mdc-text-field-helper-text mdc-text-field-helper-text--validation-msg">%s</div>
                    </div>
                </div>',
                        wp_kses($this->get_basic_captcha_label_prefix(), []),
                        sprintf('%1$s + %2$s', esc_html($this->first_digit), esc_html($this->second_digit)),
                        wp_kses($this->get_basic_captcha_validation_message(), [])
                    );

                    return $basic_captcha;
                    break;

                default:
                    // code...
                    break;
            }

        }

        return '';
    }

    /**
     * Verify spam protection
     */
    public function verify($data)
    {
        if ($this->is_enabled) {
            switch ($this->type) {
                case 'v2':
                case 'v3':
                    if (!isset($data['g-recaptcha-response'])) {
                        throw new \Exception("grecaptcha", 400);
                    }

                    return $this->verify_recaptha_token($data['g-recaptcha-response']);
                    break;

                case 'basic_captcha':
                    return $this->verify_basic_captcha();
                    break;

                default:
                    // code...
                    break;
            }

        }

        return false;
    }

    /**
     * Verify basic captcha response
     */
    public function verify_basic_captcha()
    {
        $computed = intval($this->first_digit) + intval($this->second_digit);

        return $computed == $this->basic_captcha_answer;
    }

    /**
     * Verify v2 google captcha
     */
    public function verify_recaptha_token($captcha_response)
    {
        $args = [
            'secret'   => $this->secret,
            'response' => $captcha_response,
        ];

        if (isset($_SERVER['REMOTE_ADDR'])) {
            // phpcs:ignore
            $args['remoteip'] = $_SERVER['REMOTE_ADDR'];
        }

        $gcaptcha = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => $args,
        ]);

        if (is_wp_error($gcaptcha)) {
            return false;
        }

        $body = wp_remote_retrieve_body($gcaptcha);

        if (empty($body)) {
            return false;
        }

        $result = json_decode($body);

        if (empty($result)) {
            return false;
        }

        if (!isset($result->success)) {
            return false;
        }

        if ($result->success && isset($result->score) && $this->type == 'v3') {
            return $result->score > $this->threshold;
        }

        return $result->success;
    }

    /**
     * Enqueue recaptcha script.
     */
    public function enqueue_script()
    {
        if ($this->is_enabled) {
            switch ($this->type) {
                case 'v2':
                    wp_enqueue_script('wpt-divi-form-recaptcha-v2', 'https://www.google.com/recaptcha/api.js', [], $this->container['plugin_version'], true);
                    break;

                case 'v3':
                    wp_enqueue_script('wpt-divi-form-recaptcha-v3', 'https://www.google.com/recaptcha/api.js?render=' . $this->key, [], $this->container['plugin_version'], true);
                    break;

                default:
                    // code...
                    break;
            }

        }
    }

}
