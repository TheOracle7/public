<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * SelectMenu.
 */
class SelectMenu extends InputField
{
    protected $options;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type    = 'select_menu';
        $this->options = [];
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

        $value = $this->default;

        if (!is_null($this->value)) {
            $value = $this->value;
        }

        $value = $this->container['form_builder']->getValueAttribute($this->name, $value);

        $options_html = '<div>';

        foreach ($this->options as $option) {
            $options_html .= $this->select_option_html($option);
        }

        return $this->field_wrapper(sprintf(
            '
            <div class="wpt-dropdown-container">
                <div class="mdc-select mdc-select--outlined %s">
                  <input type="hidden" name="%s">
                  <div class="mdc-select__anchor" aria-labelledby="outlined-select-label" %s>
                    <span class="mdc-notched-outline">
                      <span class="mdc-notched-outline__leading"></span>
                      <span class="mdc-notched-outline__notch">
                        <span id="outlined-select-label" class="mdc-floating-label">%s</span>
                      </span>
                      <span class="mdc-notched-outline__trailing"></span>
                    </span>
                    <span class="mdc-select__selected-text-container">
                      <span id="demo-selected-text" class="mdc-select__selected-text"></span>
                    </span>
                    <span class="mdc-select__dropdown-icon">
                      <svg
                          class="mdc-select__dropdown-icon-graphic"
                          viewBox="7 10 10 5" focusable="false">
                        <polygon
                            class="mdc-select__dropdown-icon-inactive"
                            stroke="none"
                            fill-rule="evenodd"
                            points="7 10 12 15 17 10">
                        </polygon>
                        <polygon
                            class="mdc-select__dropdown-icon-active"
                            stroke="none"
                            fill-rule="evenodd"
                            points="7 15 12 10 17 15">
                        </polygon>
                      </svg>
                    </span>
                  </div>

                  <div class="mdc-select__menu demo-width-class mdc-menu mdc-menu-surface">
                    <ul class="mdc-list">
                      %s
                    </ul>
                  </div>
                </div>

                <label class="mdc-text-field mdc-text-field--no-label">
                    <span class="mdc-text-field__ripple"></span>
                    <input class="mdc-text-field__input" type="text" style="border:none; padding:0;background: transparent;" >
                    <span class="mdc-line-ripple"></span>
                </label>

                <div class="mdc-text-field-helper-line">
                %s
                %s
                </div>
            </div>
            ',
            $this->is_required ? 'mdc-select--required' : '',
            $this->name,
            $this->is_required ? 'aria-required="true"' : '',
            $this->label,
            $options_html,
            $this->error_html(),
            $this->has_helper_text() ? $this->get_helper_text_mdc() : ''
        ));

    }

    /**
     * Checkbox HTML
     */
    public function select_option_html($option)
    {

        return sprintf('<li class="mdc-list-item" data-value="%s">
        <span class="mdc-list-item__ripple"></span>
        <span class="mdc-list-item__text">%s</span>
      </li>', $option, $option);

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
