<?php
namespace WPT\DiviForms\Form\InputFields;

/**
 * TextArea.
 */
class TextArea extends InputField
{
    protected $rows;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->type = 'textarea';
        $this->rows = 9;
    }

    /**
     * Set number of rows
     */
    public function set_rows($rows)
    {
        $this->rows = $rows;
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
            '<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea">
            %s
        <span class="mdc-text-field__resizer">
            <textarea class="mdc-text-field__input" aria-labelledby="%s" name="%s" %s placeholder="%s" rows="%s">%s</textarea>
        </span>

          </label>
        <div class="mdc-text-field-helper-line">
            %s
            %s
        </div>',
            $this->label_html(),
            $this->name,
            $this->name,
            implode(' ', $this->validation_attributes()),
            $this->placeholder,
            $this->rows,
            $value,
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
