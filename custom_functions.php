<?php

add_action( 'init', 'customer_post_type', 0 );
function customer_post_type() {
     // Set UI labels for Customer Post Type
    $labels = array(
        'name'                => _x( 'Customers', 'Post Type General Name', 'crm' ),
        'singular_name'       => _x( 'Customer', 'Post Type Singular Name', 'crm' ),
        'menu_name'           => __( 'Customers', 'crm' ),
        'parent_item_colon'   => __( 'Parent Customer', 'crm' ),
        'all_items'           => __( 'All Customers', 'crm' ),
        'view_item'           => __( 'View Customer', 'crm' ),
        'add_new_item'        => __( 'Add New Customer', 'crm' ),
        'add_new'             => __( 'Add New', 'crm' ),
        'edit_item'           => __( 'Edit Customer', 'crm' ),
        'update_item'         => __( 'Update Customer', 'crm' ),
        'search_items'        => __( 'Search Customer', 'crm' ),
        'not_found'           => __( 'Not Found', 'crm' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'crm' ),
    );
    // Set other options for Customer Post Type     
    $args = array(
        'label'               => __( 'customers', 'crm' ),
        'description'         => __( 'Customer Relationship Management', 'crm' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'Customer-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_icon'  		  => 'dashicons-groups',
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Customer Post Type
    register_post_type( 'customers', $args );
 
}


/*
    Cutomer Post Meta
*/

//making the meta box (Note: meta box != custom meta field)
function crm_cutomres_metabox() {
   add_meta_box(
       'crm_meta_box',       // $id
       'Cutomers Data',                  // $title
       'crm_meta_box_fields',  // $callback
       'customers',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
}
add_action('add_meta_boxes', 'crm_cutomres_metabox');


//showing custom form fields
function crm_meta_box_fields( $post) {
    global $post;
    $customerPhonenumber = get_post_meta( $post->ID, '_customerPhonenumber', true );
    $customerEmail = get_post_meta( $post->ID, '_customerEmail', true );
    $customerDesiredBudget = get_post_meta( $post->ID, '_customerDesiredBudget', true );
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>

    <!-- CRM custom value input -->
    <p >
        <label for="customerPhonenumber"><?php _e('Phone Number');?></label> <br/>
        <input type="tel" class="form-control" id="customerPhonenumber" name="_customerPhonenumber" value="<?php _e( $customerPhonenumber ); ?>"  >
    </p>
    <p >
        <label for="customerEmail"><?php echo _e('Email');?></label><br/>
        <input type="email" class="form-control" id="customerEmail" name="_customerEmail"  value="<?php _e( $customerEmail );?>"  >
    </p>

    <p >
        <label for="customerDesiredBudget"><?php echo _e('Desired Budget');?></label><br/>
        <input type="number" class="form-control" id="customerDesiredBudget" name="_customerDesiredBudget"value="<?php _e( $customerDesiredBudget );?>" >
    </p>
    <?php
}

//Save meta data 
add_action( 'save_post', 'crm_meta_data', 10, 2 );
function crm_meta_data( $post_id, $post ) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "customers";

    if($slug != $post->post_type)
        return $post_id;

    $customerPhonenumber = "";
    $customerEmail = "";
    $customerDesiredBudget = "";

    if(isset($_POST["_customerPhonenumber"]))
    {
        $customerPhonenumber = $_POST["_customerPhonenumber"];
    }   
    update_post_meta($post_id, "_customerPhonenumber", $customerPhonenumber);

    if(isset($_POST["_customerEmail"]))
    {
        $customerEmail = $_POST["_customerEmail"];
    }   
    update_post_meta($post_id, "_customerEmail", $customerEmail);

    if(isset($_POST["_customerDesiredBudget"]))
    {
        $customerDesiredBudget = $_POST["_customerDesiredBudget"];
    }   
    update_post_meta($post_id, "_customerDesiredBudget", $customerDesiredBudget);
}

/*
    Customers form shortcode.
 */

function crm_form_shortcode( $atts = array() ){
    ob_start();
?>
<div class="" id="message"></div>
<form id="customer_info">
    <?php 
        // set up default parameters
        extract(shortcode_atts(array(
           'name' => 'Name',
            'phonenumber' => 'Phone Number',
            'email' => 'Email',
            'desiredbudget' => 'Desired Budget',            
            'message' => 'Message',
            'message_cols' => '50',
            'message_rows' => '4',
            'name_max_lenght' => '50',
            'phone_max_lenght' => '12',
            'budget_max_lenght' => '4',
            'email_max_lenght' => '50',
        ), $atts));
     ?>
    <div class="form-group">
        <label for="customerName"><?php echo esc_attr($name);?><span class="required"> *</span></label>
        <input type="tel" class="form-control" id="customerName" name="customerName" placeholder="Name"  minlength="1" maxlength="<?php echo esc_attr($name_max_lenght);?>" required="required" />
    </div>
    <div class="form-group">
        <label for="customerPhonenumber"><?php echo esc_attr($phonenumber);?><span class="required"> *</span></label>
        <input type="tel" class="form-control" id="customerPhonenumber" maxlength="<?php echo esc_attr($phone_max_lenght);?>" name="customerPhonenumber" placeholder="123-445-6789" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required="required" />
        
    </div>
    <div class="form-group">
        <label for="customerEmail"><?php echo esc_attr($email);?><span class="required"> *</span></label>
        <input type="email" class="form-control" id="customerEmail" name="customerEmail" placeholder="Enter email"  minlength="4" maxlength="<?php echo esc_attr($email_max_lenghtemailmaxsize);?>" required="required" />
    </div>

    <div class="form-group">
        <label for="customerDesiredBudget"><?php echo esc_attr($desiredbudget);?><span class="required"> *</span></label>
        <input type="number" class="form-control" id="customerDesiredBudget"  name="customerDesiredBudget" placeholder="Desired Budget" required="required" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "<?php echo esc_attr($budget_max_lenght);?>" />
    </div>
    <div class="form-group">
        <label for="customerMessage"><?php echo esc_attr($message);?> <span class="required"> *</span></label> 
        <textarea id="customerMessage" name="customerMessage" rows="<?php echo esc_attr($message_rows);?>" cols="<?php echo esc_attr($message_cols);?>" required="required" /></textarea>
    </div>
    <div class="form-group">
        <input type="hidden" name="crm-date" id="crm-date" value='<?php echo current_time( 'mysql' );?>' />
        <button id='submitPost' name='submitPost'>submit Post</button>
    </div>

    </form>
<?php
$output = ob_get_contents();
ob_end_clean();
return $output;
}
 wp_reset_query();
add_shortcode('customer_form', 'crm_form_shortcode');

/*
    CRM Form Submission Ajax
*/
add_action( 'wp_ajax_crm_form_insert', 'crm_form_insert' );    // If called from admin panel
add_action( 'wp_ajax_nopriv_crm_form_insert', 'crm_form_insert' );
function crm_form_insert() {
    $customerName           =   sanitize_text_field( $_POST['customerName'] );
    $customerPhonenumber    =   sanitize_text_field( $_POST['customerPhonenumber'] );
    $customerEmail          =   sanitize_text_field( $_POST['customerEmail'] );
    $customerDesiredBudget  =   sanitize_text_field( $_POST['customerDesiredBudget'] );
    $customerMessage        =   sanitize_text_field( $_POST['customerMessage'] );
    $crm_date        =   sanitize_text_field( $_POST['crm-date'] );
     $date_string = $crm_date; 
    $date_stamp = strtotime($date_string);
    $postdate = date("Y-m-d H:i:s", $date_stamp);
    $post_id = wp_insert_post( array(
                    'post_title'        => $customerName,
                    'post_content'      => $customerMessage,
                    'post_status'       => 'private',
                    'post_type'     => 'customers',
                    'post_date'     =>   $postdate
                ) );
   

     if( $post_id ) {                       
        add_post_meta($post_id, '_customerPhonenumber', $customerPhonenumber);
        add_post_meta($post_id, '_customerEmail', $customerEmail);
        add_post_meta($post_id, '_customerDesiredBudget', $customerDesiredBudget);
      
      }
    echo $post_id;
    wp_die();
}

