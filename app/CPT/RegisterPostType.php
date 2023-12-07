<?php
namespace WTOP\CPT;

class RegisterPostType
{
    private array $parameters;
    private string $post_type;

    function __construct( $in_parameters, $post_type ){
        $this->parameters = $in_parameters;
        $this->post_type = $post_type;

        add_action( 'init', array( $this, 'add_post_type' ), 1 );
        register_activation_hook( __FILE__, array( $this, 'add_post_type' ) );
        register_activation_hook( __FILE__, 'flush_rewrite_rules' );
    }
    function add_post_type(){
        register_post_type( $this->post_type, $this->parameters );
    }
}