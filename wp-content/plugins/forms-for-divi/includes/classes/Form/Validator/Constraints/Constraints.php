<?php
namespace WPT\DiviForms\Form\Validator\Constraints;

/**
 * Constraints.
 */
class Constraints
{
    protected $container;
    public $rules;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->rules     = [];
    }

    /**
     * Add constraints
     */
    public function add(
        $name,
        $options = []
    ) {

        switch ($name) {
            case 'required':
                $this->rules[] = new Required($name, $options);
                break;

            case 'email':
                $this->rules[] = new Email($name, $options);
                break;

            case 'minlength':
                $this->rules[] = new MinLength($name, $options);
                break;

            case 'maxlength':
                $this->rules[] = new MaxLength($name, $options);
                break;

            case 'upload_limit':
                $this->rules[] = new UploadLimit($name, $options);
                break;

            default:
                // code...
                break;
        }
    }

    public function get_all()
    {
        return $this->rules;
    }

}
