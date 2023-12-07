<?php

namespace WTOP\FormElements;

class ElementSave extends \WTOP\FormElements\FormElement
{
    private string $local_code;

    public function getCode()
    {
        $this->local_code = '
		<div class="form-group">  
			<div class="form-actions">  
			    <button type="submit" class="btn btn-primary">'.esc_html( $this->processed_attributes['title'] ).'</button>  
			</div>
			'.$this->generate_substring().'							
		</div> 
		';

        return $this->wrap_element( $this->local_code );
    }
}

