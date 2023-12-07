<?php

namespace WTOP\FormElements;

class ElementSelect extends \WTOP\FormElements\FormElement
{
    private string $local_code;

    public function getCode()
    {
        $this->local_code = '
		<div class="form-group">  
			<label class="control-label" for="'.esc_attr( $this->processed_attributes['id'] ).'">'.esc_html( $this->processed_attributes['title'] ).'</label>  
			<select '.($this->processed_attributes['readonly'] ? ' readonly ' : '' ).' '.($this->processed_attributes['disabled'] ? ' disabled ' : '' ).' style="'.esc_attr( $this->processed_attributes['style']).'" class="form-control '.esc_attr( $this->processed_attributes['class']).'" name="'.esc_attr( $this->processed_attributes['name']).'" id="'.esc_attr( $this->processed_attributes['id']).'">' ;
            if( count( (array)$this->processed_attributes['value'] ) > 0 )
            foreach( $this->processed_attributes['value'] as $k => $v ){
                if( $this->value && $this->value != '' ){
                    $this->local_code .= '<option value="'.esc_attr( $k ).'" '.( $this->value  == $k ? ' selected ' : ' ' ).' >'.esc_html( $v ).'</option> ';
                }else{
                    $this->local_code .= '<option value="'.esc_attr( $k ).'" '.( $this->processed_attributes['default']  == $k ? ' selected ' : ' ' ).' >'.esc_html( $v ).'</option> ';
                }
            }
            $this->local_code .= '		
			</select>
			'.$this->generate_substring().'							
		</div> 
		';

        return $this->wrap_element( $this->local_code );
    }
}

