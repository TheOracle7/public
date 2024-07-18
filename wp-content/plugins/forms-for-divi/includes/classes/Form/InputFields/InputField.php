<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * InputField.
 */
abstract class InputField
{
    protected $container;
    protected $type;
    protected $name;
    protected $label;
    protected $default;
    protected $value;
    protected $placeholder;
    protected $helper_text;
    protected $show_label;
    protected $constraints;
    public $payload_prefix;
    public $payload;
    public $is_required;
    public $classes;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container      = $container;
        $this->type           = 'text';
        $this->name           = '';
        $this->default        = '';
        $this->label          = '';
        $this->placeholder    = '';
        $this->show_label     = true;
        $this->payload_prefix = '_____';
        $this->payload        = '';
        $this->helper_text    = '';
        $this->value          = null;
        $this->is_required    = false;
        $this->classes        = [];

        $this->constraints = $this->container['validation_constraints'];

    }

    /**
     * Add class name
     */
    public function add_class($class)
    {
        $this->classes[] = $class;
        return $this;
    }

    /**
     * Set if field is requried
     */
    public function set_is_required($is_required)
    {
        $this->is_required = $is_required;
    }

    /**
     * Set helper text value
     */
    public function set_helper_text($text)
    {
        $this->helper_text = trim($text);
        return $this;
    }

    /**
     * Get the helper text value
     */
    public function get_helper_text()
    {
        return $this->helper_text;
    }

    /**
     *  check if helper text exists
     */
    public function has_helper_text()
    {
        return strlen($this->get_helper_text()) > 0;
    }

    /**
     * Get material design helper text html
     */
    public function get_helper_text_mdc()
    {
        return sprintf('<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent" aria-hidden="true">%s</div>', $this->get_helper_text());
    }

    public function field_wrapper($field_html)
    {
        return sprintf('<div class="wpt-input-field-container">%s</div>', $field_html);
    }

    public function set_type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    /**
     * Set name
     */
    public function set_name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function get_formatted_value()
    {
        return $this->value;
    }

    /**
     * Set value
     */
    public function set_value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set label
     */
    public function set_label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function set_default($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Set placeholder text.
     */
    public function set_placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Label html
     */
    public function label_html()
    {
        if ($this->show_label) {
            return sprintf('<label for="%s">%s</label>', $this->name, $this->label);
        }

        return false;
    }

    /**
     * show label.
     */
    public function show_label($show_label)
    {
        $this->show_label = $show_label;
        return $this;
    }

    /**
     * Get input field html
     */
    public function input_html($config = [])
    {
        $html = '';

        $label = $this->label_html();

        $classes    = 'wpt-input-field';
        $error_html = '';

        $has_error = $this->container['request']->has_error($this->name);

        if ($has_error) {
            $classes .= ' has-error';
            $error_messages = $this->container['request']->get_field_errors($this->name);
            $error_message  = implode(' ', $error_messages);
            $error_html     = sprintf('<div class="error-message">%s</div>', wp_kses_post($error_message));
        }

        $value = $this->default;

        if (!is_null($this->value)) {
            $value = $this->value;
        }

        $html .= $this->container['form_builder']->input(
            $this->type,
            $this->name,
            $this->container['form_builder']->getValueAttribute($this->name, $value),
            ['class' => $classes, 'placeholder' => $this->placeholder]
        );

        return sprintf(
            '<div class="wpt-input-container%s">%s<div class="input-field-container %s">%s</div>%s</div>',
            $has_error ? ' has-error' : '',
            $label ? $label : '',
            $this->type,
            $html,
            $error_html
        );
    }

    /**
     * Input field
     */
    public function html()
    {
        return $this->payload_html() . $this->input_html();

    }

    /**
     * get list of all the constraints
     */
    public function constraints()
    {
        return $this->constraints;
    }

    /**
     * Add constraint for the field
     */
    public function add_constraint(
        $rule,
        $options = []
    ) {
        $this->constraints->add($rule, $options);

        return $this;
    }

    /**
     * Get the html attributes for validation
     */
    public function validation_attributes()
    {
        $attributes = [];

        foreach ($this->constraints->rules as $constraint) {
            $attributes[] = $constraint->get_html_attribute();
        }

        return $attributes;
    }

    /**
     * Validates a given value
     */
    public function validate()
    {
        $violations = [];

        foreach ($this->constraints->rules as $constraint) {
            if ($constraint->failed($this->value)) {
                $violations[$constraint->get_type()] = $constraint->get_error_message();
            }
        }

        return $violations;
    }

    /**
     * Get all the constraint error messages irrespective of validation
     */
    public function get_error_constraint_messages()
    {
        $errors = [];

        foreach ($this->constraints->rules as $constraint) {
            $errors[] = $constraint->get_error_message();
        }

        return $errors;
    }

    /**
     * Error html mdc
     */
    public function error_html()
    {
        return sprintf(
            '<div class="mdc-text-field-helper-text mdc-text-field-helper-text--validation-msg">%s</div>',
            implode(' ', $this->get_error_constraint_messages())
        );
    }

    /**
     * get playload validation data
     */
    public function payload_validation()
    {
        $rules = [];

        foreach ($this->constraints->rules as $constraint) {
            $rules[] = [
                'type'    => $constraint->get_type(),
                'options' => $constraint->get_options(),
            ];
        }

        return $rules;
    }

    /**
     * Get the payload data
     */
    public function get_payload_data()
    {
        $this->payload = [
            'type'       => $this->type,
            'validation' => $this->payload_validation(),
        ];

        $this->payload = $this->container['crypt']->encrypt(wp_json_encode($this->payload));

        return $this->payload;
    }

    public function set_payload_data($payload)
    {
        $this->payload = $payload;
    }

    public function decrypt_payload($encrytped_payload)
    {
        $this->payload = $this->container['crypt']->decrypt($encrytped_payload);

        $this->payload = json_decode($this->payload, true);

        return $this->payload;
    }

    /**
     * Get the payload field that contains additional data
     */
    public function payload_html()
    {

        return sprintf(
            '<input type="hidden" name="%1$s%2$s" value="%3$s"/>',
            $this->payload_prefix,
            $this->name,
            $this->get_payload_data()
        );
    }

    public function rehydrate_validation()
    {
        $this->constraints = $this->container['validation_constraints'];

        if (isset($this->payload['validation']) && is_array($this->payload['validation'])) {
            foreach ($this->payload['validation'] as $rule => $options) {
                $this->add_constraint($rule, $options);
            }
        }

    }

}
