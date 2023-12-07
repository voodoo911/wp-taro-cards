<?php
namespace WTOP\Menu;


class CreateMenu
{
    public array $menu_settings = [];

    public string $locale;

    public function __construct( $init_settings, $locale )
    {
        $this->menu_settings = $init_settings;
        $this->locale = $locale;
        add_action('admin_menu', array( $this, 'add_menu_item') );
        add_action('init', array( $this, 'save_settings') );


    }


    public function save_settings()
    {
        if( isset($_POST[$this->locale.'save_settings_field']) ){
            if(  wp_verify_nonce($_POST[$this->locale.'save_settings_field'], $this->locale.'save_settings_action') ){
                $options = array();
                foreach( $_POST as $key=>$value ){
                    if( is_array( $value ) ){
                        $options[$key] = array_map( 'sanitize_text_field',  $value  );
                    }else{
                        $options[$key] = sanitize_text_field( $value );
                    }

                }

                update_option( $this->locale.'_options', $options );



            }
        }
    }
}
