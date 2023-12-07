<?php
namespace WTOP\CPT;

class registerTaxonomy
{
    var $parameters;
    var $post_type;
    var $tax_slug;

    function __construct( $tax_slug, $post_type, $in_parameters  ){
        $this->parameters = $in_parameters;
        $this->post_type = $post_type;
        $this->tax_slug = $tax_slug;

        add_action( 'init', array( $this, 'register_taxonomy' ), 2  );
        register_activation_hook( __FILE__, array( $this, 'register_taxonomy' ) );
        //register_activation_hook( __FILE__, 'flush_rewrite_rules' );
    }
    function register_taxonomy(){

        register_taxonomy( $this->tax_slug, $this->post_type, $this->parameters );
    }
}