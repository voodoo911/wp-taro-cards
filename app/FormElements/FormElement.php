<?php

namespace WTOP\FormElements;

abstract class FormElement
{
    public mixed $value;

    protected array $default_attributes = [
        'class' => '',
        'disabled' => '',
        'readonly' => '',
        'id' => '',
        'value' => '',
        'default' => '',
        'width' => '',
        'title' => '',
        'sub_title' => '',
        'sub_text' => '',
        'rows' => '',
        'name' => '',
        'href' => '',
        'style' => '',
        'upload_text' => '',
        'container_class' => '',
        'placeholder' => '',
    ];
    protected array $processed_attributes = [];

    public function __construct( $element_parameters, $value )
    {
        $this->value = $value;
        $this->processed_attributes = array_merge( $this->default_attributes, $element_parameters );
    }

    function wrap_element( $content )
    {
        $out = '<div class="'.esc_attr( $this->processed_attributes['container_class'] ).' '.( $this->processed_attributes['width'] ? esc_attr( $this->processed_attributes['width'] ) : 'col-12' ).'">'.$content.'</div>';
        return $out;
    }

    function generate_substring(){
        return '<p class="help-block">'.esc_html( $this->processed_attributes['sub_text'] ).'</p>  	';
    }


}