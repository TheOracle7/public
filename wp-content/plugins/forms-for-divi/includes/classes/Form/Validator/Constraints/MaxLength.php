<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * MaxLength.
 */
class MaxLength extends Constraint
{

    public function __construct(
        $type,
        $options
    ) {
        $options['default_error_message'] = 'Enter a maximum of x characters.';
        parent::__construct($type, $options);
    }

    /**
     * MaxLength validation
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
        return sprintf('maxlength="%s"', $this->options['value']);
    }

}
