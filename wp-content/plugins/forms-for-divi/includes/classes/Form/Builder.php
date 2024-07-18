<?php
namespace WPT\DiviForms\Form;

/**
 * Builder.
 */
class Builder
{
    protected $container;

    protected $type;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function input(
        $type,
        $name,
        $value = null,
        $options = []
    ) {
        $this->type = $type;

        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        // We will get the appropriate value for the given field. We will look for the
        // value in the session for the value in the old input data then we'll look
        // in the model instance if one is set. Otherwise we will just use empty.
        $id = $this->getIdAttribute($name, $options);

        if (!in_array($type, ['file', 'password', 'checkbox', 'radio'])) {
            $value = $this->getValueAttribute($name, $value);
        }

        // Once we have the type, value, and ID we can merge them into the rest of the
        // attributes array so we can convert them into their HTML attribute format
        // when creating the HTML element. Then, we will return the entire input.
        $merge = compact('type', 'value', 'id');

        $options = array_merge($options, $merge);

        return '<input' . $this->attributes($options) . '>';
    }

    /**
     * Get the ID attribute for the field
     */
    public function getIdAttribute(
        $name,
        $attributes
    ) {
        if (array_key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        return $name;
    }

    /**
     * Get the value that should be assigned to the field.
     *
     * @param  string  $name
     * @param  string  $value
     * @return mixed
     */
    public function getValueAttribute(
        $name,
        $value = null
    ) {

        if ($this->container['session']->get_form_status() == 200) {
            return '';
        }

        if (is_null($name)) {
            return $value;
        }

        $old = $this->old($name);

        if (!is_null($old)) {
            return $old;
        }

        if (!is_null($value)) {
            return $value;
        }

    }

    /**
     * Create attributes
     */
    public function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    protected function attributeElement(
        $key,
        $value
    ) {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class="' . implode(' ', $value) . '"';
        }

        if (!is_null($value)) {
            return $key . '="' . esc_attr($value) . '"';
        }
    }

    public function old($name)
    {
        if (isset($_SESSION['wpt_form_data'], $_SESSION['wpt_form_data'][$name])) {
            return $_SESSION['wpt_form_data'][$name];
        }

        return null;
    }

}
