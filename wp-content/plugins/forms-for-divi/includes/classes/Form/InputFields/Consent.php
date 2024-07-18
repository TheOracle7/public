<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * Consent.
 */
class Consent extends InputField
{

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type = 'consent';
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
            '<div class="wpt-form-consent-field-container">
            <div class="wpt_form_consent_field_checkbox_label_container">
<button class="mdc-switch %s" type="button" role="switch" %s>
  <div class="mdc-switch__track"></div>
  <div class="mdc-switch__handle-track">
    <div class="mdc-switch__handle">
      <div class="mdc-switch__shadow">
        <div class="mdc-elevation-overlay"></div>
      </div>
      <div class="mdc-switch__ripple"></div>
      <div class="mdc-switch__icons">
        <svg class="mdc-switch__icon mdc-switch__icon--on" viewBox="0 0 24 24">
          <path d="M19.69,5.23L8.96,15.96l-4.23-4.23L2.96,13.5l6,6L21.46,7L19.69,5.23z" />
        </svg>
        <svg class="mdc-switch__icon mdc-switch__icon--off" viewBox="0 0 24 24">
          <path d="M20 13H4v-2h16v2z" />
        </svg>
      </div>
    </div>
  </div>
</button>
<label class="wpt-consent-label" for="%s">%s</label>
</div>

<label class="mdc-text-field mdc-text-field--filled mdc-text-field--no-label">
  <span class="mdc-text-field__ripple"></span>
  <input class="mdc-text-field__input" name="%s" type="text" %s>
  <span class="mdc-line-ripple"></span>
</label>
<div class="mdc-text-field-helper-line">
    %s
    %s
</div>
</div>
',
            $this->default ? 'mdc-switch--selected' : 'mdc-switch--unselected',
            $this->default ? 'aria-checked="true"' : '',
            $this->name,
            $this->label,
            $this->name,
            $this->is_required ? 'required' : '',
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
     * Error html mdc
     */
    public function error_html()
    {
        return sprintf('<div class="mdc-text-field-helper-text mdc-text-field-helper-text--validation-msg">
        %s
        </div>', implode('. ', $this->get_error_constraint_messages()));
    }

    /**
     * Set value for the consent field.
     */
    public function set_value($value)
    {
        $this->value = sanitize_title($value);
        return $this;
    }

}
