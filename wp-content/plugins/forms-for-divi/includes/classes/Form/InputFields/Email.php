<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * Email.
 */
class Email extends InputField
{

    protected $enable_autocomplete;
    protected $autocomplete_attribute;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type                   = 'email';
        $this->enable_autocomplete    = false;
        $this->autocomplete_attribute = 'off';
    }

    /**
     * Get input field html
     */
    public function input_html($config = [])
    {
        $html = '';

        $value = $this->default;

        if (!is_null($this->value)) {
            $value = $this->value;
        }

        $value = $this->container['form_builder']->getValueAttribute($this->name, $value);

        return $this->field_wrapper(sprintf(
            '<label class="mdc-text-field mdc-text-field--outlined">
            %s
        <input type="text" value="%s" name="%s" class="mdc-text-field__input" aria-labelledby="%s" pattern="\b[A-Za-z0-9._%%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b" placeholder="%s" %s autocomplete="%s">
        </label>
        <div class="mdc-text-field-helper-line">
            %s
            %s
        </div>',
            $this->label_html(),
            $value,
            $this->name,
            $this->name,
            $this->placeholder,
            implode(' ', $this->validation_attributes()),
            $this->enable_autocomplete ? $this->autocomplete_attribute : 'off',
            $this->error_html(),
            $this->has_helper_text() ? $this->get_helper_text_mdc() : ''
        ));
    }

    /**
     *
     */
    public function label_html()
    {
        return sprintf(
            '<span class="mdc-notched-outline">
            <span class="mdc-notched-outline__leading"></span>
            <span class="mdc-notched-outline__notch">
            <span class="mdc-floating-label">%s</span>
            </span>
            <span class="mdc-notched-outline__trailing"></span>
            </span>',
            $this->label
        );
    }

    /**
     * Set value
     */
    public function set_value($value)
    {
        $this->value = sanitize_email($value);
        return $this;
    }
}
