<?php

namespace WTOP\Menu;

use WTOP\FormElements;

class MenuElement extends \WTOP\Menu\CreateMenu
{


    private array $predefined_menu_settings = [
        'type' => '',
        'parent_slug' => '',
        'form_title' => '',
        'is_form' => '',
        'page_title' => '',
        'menu_title' => '',
        'capability' => '',
        'menu_slug' => '',
        'icon' => ''
    ];



    public function add_menu_item(): void
    {
        switch( $this->menu_settings['type'] )
        {
            case "option":
                add_options_page(
                    $this->menu_settings['page_title'],
                    $this->menu_settings['menu_title'],
                    $this->menu_settings['capability'],
                    $this->locale.$this->menu_settings['menu_slug'],
                    array( $this, 'generate_form' )
                );
                break;

            case "menu":
                add_menu_page(
                    $this->menu_settings['page_title'],
                    $this->menu_settings['menu_title'],
                    $this->menu_settings['capability'],
                    $this->locale.$this->menu_settings['menu_slug'],
                    array( $this, 'generate_form' ),
                    $this->menu_settings['icon']
                );
                break;

            case "submenu":
                add_submenu_page(
                    $this->menu_settings['parent_slug'],
                    $this->menu_settings['page_title'],
                    $this->menu_settings['menu_title'],
                    $this->menu_settings['capability'],
                    $this->locale.$this->menu_settings['menu_slug'],
                    array( $this, 'generate_form' )
                );
                break;

        }

    }



    function generate_save_message()
    {
        if( isset($_POST[$this->locale.'save_settings_field']) ){
            if(  wp_verify_nonce($_POST[$this->locale.'save_settings_field'], $this->locale.'save_settings_action') ){
                return  '<div class="alert alert-success">'.$this->menu_settings['save_message'].'</div>';
            }
        }
    }

    function generate_form_top()
    {
        if( $this->menu_settings['is_form'] ){
            return '<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >';
        }
    }
    function generate_form_bottom()
    {
        if( $this->menu_settings['is_form'] ){
            return '</form>';
        }
    }

    function generate_form()
    {
        $out = '<div class="wrap tw-bs4">
		<h2>' . esc_html($this->menu_settings['form_title']) . '</h2>
		<hr/>
		' . $this->generate_save_message() . '		
		' . $this->generate_form_top() . '
		' . wp_nonce_field($this->locale . 'save_settings_action', $this->locale . 'save_settings_field', false, false) . '  
		<fieldset>';
        $settings = get_option( $this->locale.'_options' );

        foreach ($this->menu_settings['parameters'] as $key => $element_setting ) {


            $interface_element_value = '';
            if( $element_setting['type'] == 'select' ){
                $interface_element_value = [];
            }
            if (isset($element_setting['name'])) {
                if (isset($settings[$element_setting['name']])) {
                    $interface_element_value = $settings[$element_setting['name']];
                }
            }


            $object = new \WTOP\FormElements\FormItemOutputGenerator(  $element_setting, $interface_element_value );
            $out .= $object->GetCode();
        }
        $out .= '
		</fieldset>  
		' . $this->generate_form_bottom() . '
		</div>';

        echo  $out;
    }

}
