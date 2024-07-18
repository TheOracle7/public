<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * Checkboxes.
 */
class Checkboxes extends InputField
{
    protected $options;
    protected $layout;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type    = 'checkboxes';
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

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $value = array_map('trim', $value);

        $html = sprintf(
            '<div class="wpt-checkbox-container" data-layout="%s"><label class="wpt-checkbox-label">%s</label><div class="label-with-checkboxes">',
            $this->layout,
            $this->label
        );

        foreach ($this->options as $option) {
            $html .= $this->checkbox_html($option, $value);
        }

        $html = $html . '</div>';

        $html = $this->field_wrapper(sprintf(
            '%s<label class="mdc-text-field mdc-text-field--no-label">
                    <span class="mdc-text-field__ripple"></span>
                    <input class="mdc-text-field__input" type="text" style="border:none; padding:0;background: transparent;" %s>
                    <span class="mdc-line-ripple"></span>
                </label>

                <div class="mdc-text-field-helper-line">
                %s
                %s
                </div>
            </div>',
            $html,
            implode(' ', $this->validation_attributes()),
            $this->error_html(),
            $this->has_helper_text() ? $this->get_helper_text_mdc() : ''
        ));

        return $html;

    }

    /**
     * Checkbox HTML
     */
    public function checkbox_html(
        $option,
        $values
    ) {
        return sprintf(
            '<div><div class="mdc-form-field" %s>
              <div class="mdc-checkbox">
                <input type="checkbox" name="%s[]" value="%s" class="mdc-checkbox__native-control" />
                <div class="mdc-checkbox__background">
                  <svg class="mdc-checkbox__checkmark"
                       viewBox="0 0 24 24">
                    <path class="mdc-checkbox__checkmark-path"
                          fill="none"
                          d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                  </svg>
                  <div class="mdc-checkbox__mixedmark"></div>
                </div>
                <div class="mdc-checkbox__ripple"></div>
              </div>
              <label for="checkbox-1">%s</label>
            </div></div>',
            array_search($option, $values) > -1 ? 'selected' : '',
            $this->name,
            $option,
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
     * Comma-separated and spaced values.
     */
    public function get_formatted_value()
    {
        return $this->value;
    }

    /**
     * Set value
     */
    public function set_value($value)
    {
        if (is_array($value)) {
            foreach ($value as $index => $data) {
                $value[$index] = wp_kses($data, []);
            }
        }
        $this->value = $value;
        return $this;
    }

}
