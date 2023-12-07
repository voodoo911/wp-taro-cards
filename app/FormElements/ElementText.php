<?php

namespace WTOP\FormElements;


class ElementText extends \WTOP\FormElements\FormElement
{
    private string $local_code;

    public function getCode()
    {

        $this->local_code = '
		<div class="form-group">  
			<label class="control-label" for="'.esc_attr( $this->processed_attributes['id'] ).'">'.esc_html( $this->processed_attributes['title'] ).'</label>  
			<input type="text" '.($this->processed_attributes['readonly'] ? ' readonly ' : '' ).' '.($this->processed_attributes['disabled'] ? ' disabled ' : '' ).' class="form-control '.esc_attr( $this->processed_attributes['class'] ).'"  name="'.esc_attr( $this->processed_attributes['name'] ).'" id="'.esc_attr( $this->processed_attributes['id'] ).'" placeholder="'.esc_attr( $this->processed_attributes['placeholder'] ).'" value="'.( $this->value && $this->value != '' ? esc_attr( stripslashes( $this->value ) ) : $this->processed_attributes['default'] ).'">  
			'.$this->generate_substring().'							
		</div> 
		';

        return $this->wrap_element( $this->local_code );
    }
}

