<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * UploadLimit.
 */
class UploadLimit extends Constraint
{

    public function __construct(
        $type,
        $options
    ) {
        $options['default_error_message'] = 'Upload a maximum file size of x.';
        parent::__construct($type, $options);
    }

    /**
     * UploadLimit validation
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
        return sprintf('upload-limit="%s"', $this->options['value']);
    }

}
