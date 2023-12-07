<?php
/*
Plugin Name: wp-taro-cards
Plugin URI: http://voodoopress.net
Description: Some description.
Version: 1.1.1
Author: Evgen "EvgenDob" Dobrzhanskiy
Author URI: http://voodoopress.net
Stable tag: 1.1
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

require_once('app/Localization/PluginLocalizer.php');

require_once('app/CPT/RegisterPostType.php');
require_once('app/CPT/RegisterTaxonomy.php');

require_once('app/FormElements/FormElement.php');

require_once('app/FormElements/ElementSave.php');
require_once('app/FormElements/ElementSelect.php');
require_once('app/FormElements/ElementText.php');

require_once('app/FormElements/FormItemOutputGenerator.php');

require_once('app/Scripts/RegisterEnqueItem.php');

require_once('app/Menu/CreateMenu.php');
require_once('app/Menu/MenuElement.php');

require_once('app/MetaBox/CreateMetaBox.php');


use \WTOP\Localization;
use \WTOP\CPT;
use \WTOP\MetaBox;
use \WTOP\Scripts;



/**
 *  main class
 */

class MainWTOPPluginClass extends \WTOP\Localization\PluginLocalizer
{
   public function __construct( $locale )
   {
       $this->locale = $locale;

       /**
        * add shortcode ot output data
        */
       add_shortcode('wtop_cart', [ $this, 'returnCartShortcode' ]);
   }

   public function createCPT( $cpt_attributes, $post_type )
   {
       new \WTOP\CPT\RegisterPostType( $cpt_attributes, $post_type );
   }

   public function createTaxonomy( $tax_slug, $post_type, $in_parameters  )
   {
       new \WTOP\CPT\registerTaxonomy( $tax_slug, $post_type, $in_parameters );
   }
   public function addMetaBox( $meta_box_attrs  )
   {
       new \WTOP\MetaBox\CreateMetaBox( $meta_box_attrs );
   }

    public function createSettingsMenu( $menu_settings  )
    {
        new \WTOP\Menu\MenuElement( $menu_settings, $this->locale );
    }

    function returnCartShortcode()
    {
        $settings = get_option( $this->locale.'_options' );

        $allCards = get_posts([
            'post_type' => 'cards',
            'showposts' => -1
        ]);

        $out = '<ul>';
        foreach( $allCards as $sCard ){
            $out .= '<li>'.esc_html( $settings['cart_prefix'] ).' '.$sCard->post_title.'</li>';
        }
        $out .= '</ul>';

        return $out;
    }
}

$locale = 'wtop';

$main_object = new MainWTOPPluginClass( $locale );

/*
 * register post type
 * */
$labels = array(
    'name' => __('Cards', $locale),
    'singular_name' => __('Card', $locale),
    'add_new' => __('Add New', $locale),
    'add_new_item' => __('Add New Card', $locale),
    'edit_item' => __('Edit Card', $locale),
    'new_item' => __('New Card', $locale),
    'all_items' => __('All Cards', $locale),
    'view_item' => __('View Card', $locale),
    'search_items' => __('Search Card', $locale),
    'not_found' =>  __('No Cards found', $locale),
    'not_found_in_trash' => __('No Cards found in Trash', $locale),
    'parent_item_colon' => '',
    'menu_name' => __('Taro Cards', $locale)

);
$args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title')
);
$main_object->createCPT( $args, 'cards' );


/**
 * create categories
 */
$labels = array(
    'name'                       => __( 'Category', $locale),
    'singular_name'              => __( 'Category', $locale ),
    'search_items'               => __( 'Search Categories', $locale ),
    'popular_items'              => __( 'Popular Categories', $locale ),
    'all_items'                  => __( 'All Categories', $locale ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Edit Category', $locale ),
    'update_item'                => __( 'Update Category', $locale ),
    'add_new_item'               => __( 'Add New Category', $locale ),
    'new_item_name'              => __( 'New Category Name', $locale ),
    'separate_items_with_commas' => __( 'Separate Categories with commas', $locale ),
    'add_or_remove_items'        => __( 'Add or remove Categories', $locale ),
    'choose_from_most_used'      => __( 'Choose from the most used Categoryies', $locale ),
    'not_found'                  => __( 'No Categories found.', $locale ),
    'menu_name'                  => __( 'Category', $locale ),
);

$args = array(
    'hierarchical'          => true,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => true,
);
$main_object->createTaxonomy(  'cards_tac', 'cards', $args );


/**
 * add meta box
 */
$meta_box = array(
    'title' => __('Card Info', $locale ),
    'post_type' => 'cards',
    'position' => 'advanced',
    'place' => 'high',
    'parameters' => array(
        array(
            'type' => 'select',
            'title' => __( 'Card type', $locale),
            'name' => 'card_type',
            'value' => [ 'death' => __('Death', $locale), 'live' => __( 'Live', $locale ) ]
        ),
        array(
            'type' => 'text',
            'title' => __( 'Card proprs', $locale),
            'name' => 'card_props',
        ),
    )
);


$main_object->addMetaBox( $meta_box  );


/**
 * create CPT Submenu
 */
$config_big =

    array(
        'type' => 'submenu',
        'parent_slug' => 'edit.php?post_type=cards',
        'form_title' => __('Settings', $locale),
        'is_form' => true,
        'page_title' => __('Settings', $locale),
        'save_message' => __('Settings Saved', $locale),
        'menu_title' => __('Settings', $locale),
        'capability' => 'edit_published_posts',
        'menu_slug' => 'main_settings',
        'parameters' => array(
            array(
                'type' => 'text',
                'title' => __('Cart prefix',$locale),
                'name' => 'cart_prefix',
                'sub_text' => __('', $locale),
                'id' => '',
                'class' => ''
            ),
            array(
                'type' => 'save',
                'title' => __('Save', $locale),
            ),


        )
    )
;
$main_object->createSettingsMenu( $config_big  );

/**
 * add scripts and styles
 */
add_action('init', function(){
    global $locale;

    $common_placement = [
        [ 'post_type' => 'page' ],
        [ 'page' => 'wtopmain_settings' ]
    ];
    $admin_placement = [
        [ 'post_type' => 'page' ],
        [ 'page' => 'wtopmain_settings' ]
    ];
    $front_placement = [
        [ 'shortcode' => 'wtop_cart' ],
    ];

    $scripts_list = array(
        'common' => array(
            array(
                'type' => 'style',
                'url' => plugins_url('/assets/inc/twbs/css/tw-bs4.css', __FILE__ ),
                'place' => $common_placement
            ),

        ),
        'admin' => array(
            array(
                'type' => 'script',
                'url' => plugins_url('/assets/js/admin.js', __FILE__ ),
                'enq' => array( 'jquery' ),
                'localization' => array(
                    'add_url' => get_option('home').'/wp-admin/post-new.php?post_type=event',
                    'nonce' => wp_create_nonce( 'ajax_call_nonce' ),
                    'ajaxurl' => admin_url('admin-ajax.php')
                ),
                'place' => $admin_placement
            ),
            array(
                'type' => 'style',
                'url' => plugins_url('/assets/css/admin.css', __FILE__ ),
                'place' => $admin_placement
            ),
        ),
        'front' => array(
            array(
                'type' => 'script',
                'url' => plugins_url('/assets/js/front.js', __FILE__ ),
                'enq' => array( 'jquery' ),
                'localization' => array(
                    'add_url' => get_option('home').'/wp-admin/post-new.php?post_type=event',
                    'nonce' => wp_create_nonce( 'ajax_call_nonce' ),
                    'ajaxurl' => admin_url('admin-ajax.php')
                ),
                'place' => $front_placement
            ),
            array(
                'type' => 'style',
                'url' => plugins_url('/assets/css/front.css', __FILE__ ),
                'place' => $front_placement
            ),
        )
    );
    new \WTOP\Scripts\RegisterEnqueItem( $scripts_list, $locale );
});









?>