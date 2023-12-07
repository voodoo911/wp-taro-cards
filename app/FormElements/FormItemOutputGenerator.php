<?php

namespace WTOP\FormElements;

class FormItemOutputGenerator
{
    private array $local_input_settings;
    private mixed $value;
    public function __construct( $input_settings, $value )
    {
        $this->local_input_settings = $input_settings;
        $this->value = $value;


    }

    function GetCode()
    {
        switch( $this->local_input_settings['type'] )
        {
            case 'text':
                $element = new \WTOP\FormElements\ElementText( $this->local_input_settings, $this->value );
                return $element->getCode();
                break;
            case 'select':
                $element = new \WTOP\FormElements\ElementSelect( $this->local_input_settings, $this->value );
                return $element->getCode();
                break;
            case 'save':
                $element = new \WTOP\FormElements\ElementSave( $this->local_input_settings, $this->value );
                return $element->getCode();
                break;
        }
    }
}