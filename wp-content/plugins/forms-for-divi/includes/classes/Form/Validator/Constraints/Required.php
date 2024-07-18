<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * Required.
 */
class Required extends Constraint
{

    public function __construct(
        $type,
        $options
    ) {
        $options['default_error_message'] = 'This field is required.';
        parent::__construct($type, $options);
    }

    /**
     * Required validation
     */
    public function failed($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            return strlen($value) === 0;
        }

        return false;
    }

    /**
     * Get html attribute
     */
    public function get_html_attribute()
    {
        return 'required';
    }

}
