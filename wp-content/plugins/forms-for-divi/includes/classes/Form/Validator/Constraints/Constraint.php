<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * Constraint.
 */
abstract class Constraint
{
    protected $type;
    protected $options;

    public function __construct(
        $type,
        $options
    ) {
        $this->type    = $type;
        $this->options = $options;

    }

    abstract public function failed($value);
    abstract public function get_html_attribute();

    public function get_type()
    {
        return $this->type;
    }

    public function get_options()
    {
        return $this->options;
    }

    /**
     * Get error message
     */
    public function get_error_message()
    {
        return isset($this->options['error_message']) && $this->options['error_message'] ? $this->options['error_message'] : $this->options['default_error_message'];
    }
}
