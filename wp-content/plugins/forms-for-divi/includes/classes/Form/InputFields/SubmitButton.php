<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * SubmitButton.
 */
class SubmitButton extends InputField
{

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type = 'submit_button';
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

        return $this->field_wrapper(
            sprintf(
                '<div class="mdc-form-field%s">
                    <button type="submit" class="mdc-button mdc-button--raised">
                      <span class="mdc-button__label">%s</span>
                    </button>
                </div>',
                $this->classes ? ' ' . implode(' ', $this->classes) : '',
                $this->label)
        );

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
     * Error html mdc
     */
    public function error_html()
    {
        // return '';

        return sprintf('<div class="mdc-text-field-helper-text mdc-text-field-helper-text--validation-msg">
        %s
        </div>', implode('. ', $this->get_error_constraint_messages()));
    }

}
