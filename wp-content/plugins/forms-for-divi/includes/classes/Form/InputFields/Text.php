<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * Text.
 */
class Text extends InputField
{
    protected $enable_password_field;
    protected $enable_autocomplete;
    protected $autocomplete_attribute;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type                  = 'text';
        $this->enable_password_field = false;
    }

    public function set_enable_password_field($enable_password_field)
    {
        $this->enable_password_field = $enable_password_field;
        return $this;
    }

    public function set_enable_autocomplete($enable_autocomplete)
    {
        $this->enable_autocomplete = $enable_autocomplete;
        return $this;
    }

    public function set_autocomplete_attribute($autocomplete_attribute)
    {
        $this->autocomplete_attribute = $autocomplete_attribute;
        return $this;
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
        <input type="%s" value="%s" name="%s" class="mdc-text-field__input" aria-labelledby="%s" placeholder="%s" %s autocomplete="%s">
        </label>
        <div class="mdc-text-field-helper-line">
            %s
            %s
        </div>',
            $this->label_html(),
            $this->enable_password_field ? 'password' : 'text',
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
        $this->value = wp_kses($value, []);
        return $this;
    }
}
