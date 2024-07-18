<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * Email.
 */
class Email extends Constraint
{
    public $pattern = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/';

    public function __construct(
        $type,
        $options
    ) {
        $options['default_error_message'] = 'Enter a valid email address.';
        parent::__construct($type, $options);
    }

    /**
     * Email validation
     */
    public function failed($value)
    {
        $value = trim($value);

        return !preg_match($this->pattern, $value);
    }

    /**
     * Get html attribute
     */
    public function get_html_attribute()
    {
        return 'email';
    }

}
