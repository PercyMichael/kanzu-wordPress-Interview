<?php
/*
Plugin Name:Books
Description:Testing
Author:Percy Michael
*/

function create_post_type() {
    register_post_type( 'acme_product',
        array(
           'labels' => array(

                'menu_name' => __( 'Books' ),
                'singular_name' => __( 'Book' ),
                'all_items' => __( 'All Books' ),
                'name' => __( 'Books' ),
                'add_new_item' => __( 'Add New Book'),
                'edit' => __( 'Edit' ),
                'edit_item' => __( 'Edit This Book' ),
                'search_items' => __( 'Search Books' )
            ),

            'public' => true,
            'menu_position' => 2,
            'register_meta_box_cb'=>'meta_box',
            'supports' => array( 'title','custom-fields',)
        )
    );
}
add_action( 'init', 'create_post_type' );

function meta_box(){
    add_meta_box('meta_box_customfield','Custom meta Field','meta_box_display','acme_product','normal','high');
}
add_action('add_meta_boxes','meta_box');

function meta_box_display(){
    global $post;

    $book_tag=get_post_meta($post->ID,'book_tag',true);
    ?>
    <label>book_tag</label>
    <input type="text" name="book_tag"  class="widefat" value="<?php print($book_tag); ?>">
    <?php
}

function save_meta_box($book_id)
{
    $is_autosave=wp_is_post_autosave($book_id);
    $is_revision=wp_is_post_revision($book_id);

    if ($is_autosave || $is_revision) {
        return;
    }
    $book=get_post($book_id);

    if($book->post_type=='acme_product'){

        /*save data*/
        if (array_key_exists('book_tag',$_POST)) {
            update_post_meta($book_id,'book_tag',$_POST['book_tag']);
        }

    }
}
add_action('save_post','save_meta_box');


//display posts
function get_books()
{
	$args=array('posts_per_page'=>100,'post_type'=>'acme_product');
	$books=get_posts($args);
	foreach ($books as $key => $value) {
	   print($value->post_title.'<br>');	
	}
}



add_shortcode('book_shortcode','get_books');


?>