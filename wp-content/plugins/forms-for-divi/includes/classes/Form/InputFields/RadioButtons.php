<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * RadioButtons.
 */
class RadioButtons extends InputField
{
    protected $options;
    protected $layout;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type    = 'radio_buttons';
        $this->options = [];
        $this->layout  = 'horizontal';
    }

    /**
     * Set the layout.
     */
    public function set_layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Set options as string
     */
    public function set_options($options)
    {
        $options       = explode(',', $options);
        $this->options = array_map('trim', $options);
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

        $html = sprintf(
            '<label class="wpt-radio-label" >%s</label><div class="wpt-radio-container" data-layout="%s">',
            $this->label,
            $this->layout
        );

        foreach ($this->options as $option) {
            $html .= $this->radio_html($option, $value);
        }

        $html = $this->field_wrapper(sprintf(
            '%s</div><label class="mdc-text-field mdc-text-field--no-label wpt-hide-label">
                    <span class="mdc-text-field__ripple"></span>
                    <input class="mdc-text-field__input" type="text" style="border:none; padding:0;background: transparent; display:none;" %s>
                    <span class="mdc-line-ripple"></span>
                </label>

                <div class="mdc-text-field-helper-line">
                %s
                %s
                </div>',
            $html,
            implode(' ', $this->validation_attributes()),
            $this->error_html(),
            $this->has_helper_text() ? $this->get_helper_text_mdc() : ''
        ));

        return $html;

    }

    /**
     * Radio HTML
     */
    public function radio_html(
        $option,
        $selected
    ) {

        return sprintf(
            '<div class="mdc-form-field">
              <div class="mdc-radio">
                <input class="mdc-radio__native-control" type="radio" %s value="%s" name="%s">
                <div class="mdc-radio__background">
                  <div class="mdc-radio__outer-circle"></div>
                  <div class="mdc-radio__inner-circle"></div>
                </div>
                <div class="mdc-radio__ripple"></div>
              </div>
              <label for="%s">%s</label>
            </div>',
            $option == $selected ? 'checked' : '',
            $option,
            $this->name,
            $this->name,
            $option
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

    /**
     * Set value
     */
    public function set_value($value)
    {
        $this->value = wp_kses($value, []);
        return $this;
    }

}
