<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * MinLength.
 */
class MinLength extends Constraint
{

    public function __construct(
        $type,
        $options
    ) {
        $options['default_error_message'] = 'Enter a minimum of x characters.';
        parent::__construct($type, $options);
    }

    /**
     * MinLength validation
     */
    public function failed($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            return strlen($value) < $this->options['value'];
        }

        return false;
    }

    /**
     * Get html attribute
     */
    public function get_html_attribute()
    {
        return sprintf('minlength="%s"', $this->options['value']);
    }

}
