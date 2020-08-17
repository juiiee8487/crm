<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
<div class="container wrap">
		<?php
		// Show the selected front page content.
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/page/content', 'front-page' );
			endwhile;
		else :
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>
		<table>
			<tr>
			<th>No.</th>
			<th>Title</th>
			<th>Content</th>
			<th>Image</th>
			<th>Action</th>
		</tr>
			<?php     $args = array(  
			        'post_type' => 'post',
			        'post_status' => 'publish'
			    );

		 $loop = new WP_Query( $args );   $count=0;  while ( $loop->have_posts() ) : $loop->the_post();  $count++;
		 $post_id =get_the_ID();
		 $url =  site_url('/edit-post/').'?post_id='.$post_id;
		 
		 ?>
		<tr><td><?php echo $count;?></td>
			<td><?php the_title();?></td>
			<td><?php the_content();?></td>
			<td><img src="<?php the_post_thumbnail_url();?>" width="150" height="150"></td>
			<td><a href="<?php echo $url;?>">Edit</a></td>
		</tr>
			<?php  endwhile;

    wp_reset_postdata(); ?>
		</table>

<div id="submit-post">
    <?php
        $content            = ''; 
        $editorSettings     = array(
            'wpautop'           => true,
            'media_buttons'     => false,
            'textarea_name'     => 'articleEditor',
            'editor_class'      => 'articleEditor',
            'theme'             => 'advanced',
            'textarea_rows'     => get_option('default_post_edit_rows', 12),
            'tinymce'           => array(
                'theme_advanced_buttons1' => 'bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,wp_more,|,spellchecker,fullscreen,wp_adv',
                'theme_advanced_buttons2' => 'formatselect,underline,justifyfull,forecolor,backcolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,|,undo,redo,wp_help',
                'theme_advanced_buttons3' => '',
                'theme_advanced_buttons4' => ''
            ),
            'quicktags'         => array(
            'buttons'           => 'b,i,ul,ol,li,link,close'
            )
        );

        // BUILD CATEGORY SELECT
        $client_select  = '';
        $categories     = get_categories('hide_empty=0');
        $optionname     = "articlecat";
        $emptyvalue     = "";

        // SELECT DROP DOWN TERMS
        $client_select  .= '<select name="'.$optionname.'" class="form-control input-normal" id="'.$optionname.'"><option selected="'.$selected.'" value="'.$emptyvalue.'">'.__('Choose a category').'</option>';
        foreach($categories as $category){
            if($currentCatId == $category->term_id) {$selected = 'selected="selected"';} else {$selected = '';}
            $client_select  .= '<option name="'.$category->term_id.'" value="'.$category->term_id.'" '.$selected.'>'.$category->name.'</option>';
        }
        $client_select  .= '</select>';

    ?>

<form method="post" id="post_insert" name="front_end" enctype="multipart/form-data" action="" >		
    <div class="post-title"><input type="text" id="title" class="" placeholder="<?php _e('Article Title'); ?>" /></div>
    <div class="post-category"><?php echo $client_select; ?></div>
    <div class="col-md-12">
			<label class="control-label">Upload Post Image</label>
			<input type="file" name="file_data" class="form-control file_data " />
		</div>
    <div class="post-body">
        <label for="description"><?php _e('Article Body: '); ?></label>
        <?php wp_editor($content, 'articlebody', $editorSettings); ?>
    </div>
    <input type="submit" name="Submit" value="Submit PosT">
    <div class="submit-post"><!-- <button type="button" id="submitPost"><?php _e('Submit Post'); ?> --></button></div>
</form>
</div>

<script type="text/javascript">
jQuery('form#post_insert').submit(function (e) {
	    e.preventDefault();
	   
	    var formData = new FormData();
	    //alert(jQuery('input[type=file]')[0].files[0]);

    formData.append('title', jQuery('#title').val());
    formData.append('body', jQuery('#articlebody').val());
    formData.append('body_ifr', jQuery('#articlebody_ifr').contents().find('body').html());
    formData.append('category', jQuery('#articlecat option:selected').val());
    formData.append('file_data', jQuery('input[type=file]')[0].files[0]);
    formData.append('action', 'single_post');
     jQuery.ajax({          
	        url:'<?php echo admin_url( "admin-ajax.php" );  ?>',
	        type:'POST',
	        dataType:"json",
	        data: formData,
		    processData: false,
		    contentType: false,
	         success: function(data){
	             alert('Client Successfull added');
	        }
               
        });
});

   	/*jQuery('#submitPost').click(function(e) {
     var formData = {
        'title'       :   jQuery('#title').val(),
        'body'        :   jQuery('#articlebody').val(),
        'body_ifr'    :   jQuery('#articlebody_ifr').contents().find('body').html(),
        'category'    :   jQuery('#articlecat option:selected').val(),
        //''  		  : jQuery('.class').find('tag').attr('src'),
            action:'single_post'
        };
        jQuery.ajax({          
	        url:'<?php //echo admin_url( "admin-ajax.php" );  ?>',
	        type:'POST',
	        dataType:"json",
	        data: formData,
	         success: function(response){
	             alert('Client Successfull added');
	        }
               
        });
  
       return false;
});*/
</script>

</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
