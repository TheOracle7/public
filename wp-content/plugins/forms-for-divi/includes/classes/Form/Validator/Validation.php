<?php
namespace WPT\DiviForms\Form\Validator;

/**
 * Validation.
 */
class Validation
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function create_validator()
    {
        return new Validator($this->container);
    }

}
