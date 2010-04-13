<?php


//
//  Gallery Child Theme Functions
//

function childtheme_menu_args($args) {
    $args = array(
        'show_home' => 'Home',
        'sort_column' => 'menu_order',
        'menu_class' => 'menu',
        'echo' => true
    );
	return $args;
}
add_filter('wp_page_menu_args', 'childtheme_menu_args');

// Adds a link to the author and other contributors
function childtheme_theme_link($themelink) {
      return $themelink . ' &amp; <a href="http://chris-wallace.com/2009/05/04/gallery-wordpress-theme/" title="Gallery Wordpress Theme" rel="designer">Gallery WordPress Theme</a> by <a href="http://chris-wallace.com">Chris Wallace</a>.<br /> Released by <a href="http://www.smashingmagazine.com">Smashing Magazine</a>. <a href="http://www.komodomedia.com/blog/2008/12/social-media-mini-iconpack/" title="Social Media Icons">Social Media Icons</a> by Rogie King';
}
add_filter('thematic_theme_link', 'childtheme_theme_link');

// Add a drop down category menu
function childtheme_menus() { ?>
        <div id="page-menu" class="menu">
            <ul id="page-nav" class="sf-menu">
                <li class="rss"><a href="<?php bloginfo('rss2_url'); ?>">RSS Feed</a></li>
                <?php wp_list_pages('title_li='); ?>
            </ul>
        </div>
        <div id="category-menu" class="menu">
            <ul id="category-nav" class="sf-menu">
                <li class="home"><a href="<? bloginfo('url'); ?>">Home</a></li>
                <?php wp_list_categories('title_li=&number=7'); ?>
                <li class="blog-description"><span><?php bloginfo('description'); ?></span></li>
            </ul>
        </div>
<?php }
add_action('wp_page_menu','childtheme_menus');

// Remove sidebar on gallery-style pages
function remove_sidebar() {
  if(is_page()){
    return TRUE;
  } else {
    return FALSE;
  }
}

  add_filter('thematic_sidebar', 'remove_sidebar');

// Change the Navigation

// Remove Navigation Above & Below
function remove_navigation() {
  remove_action('thematic_navigation_above', 'thematic_nav_above', 2);
  remove_action('thematic_navigation_below', 'thematic_nav_below', 2);
}
add_action('init', 'remove_navigation');

// re-create thematic_nav_below

function gallery_nav_below() {
  if (!(is_single())) { ?>

			<div id="nav-below" class="navigation">
                <?php if(function_exists('wp_pagenavi')) { ?>
                <?php wp_pagenavi(); ?>
                <?php } else { ?>  
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'thematic')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'thematic')) ?></div>
				<?php } ?>
			</div>	
	
<?php
		}
}

add_action('thematic_navigation_below', 'gallery_nav_below');

// End of NAVIGATION

// Creating the content for the INDEX
function remove_index_loop() {
  remove_action('thematic_indexloop', 'thematic_index_loop');
}
add_action('init', 'remove_index_loop');

function gallery_index_loop() {
  global $post;
  /* Count the number of posts so we can insert a widgetized area */ $count = 1;
  while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php
			thematic_post_class(); 
			if(function_exists('p75GetVideo')){
		    if(p75GetVideo($post->ID)){ 
				  echo " video"; 
				}
			}
			?>">
				<div class="entry-content">
					<?php childtheme_post_header() ?>
                    <a href="<?php echo the_permalink() ?>"><span class="slide-title"><?php echo the_title(); ?></span>
                    <img class="thumbnail" src="<?php if(get_post_meta($post->ID, 'thumbnail', $single = true)){echo get_post_meta($post->ID, 'thumbnail', $single = true);} else{bloginfo('url'); echo "/wp-content/themes/gallery/images/thumbnail-default.jpg";} ?>" width="125" height="125" alt="<?php echo the_title() ?>" /></a>
				</div>
			</div><!-- .post -->

    <?php comments_template();

    if ($count==$thm_insert_position) {
      get_sidebar('index-insert');
    }
    $count = $count + 1;
  endwhile;
}
add_action('thematic_indexloop', 'gallery_index_loop');
// End of INDEX

//Creating the content for the Single Post
function remove_single_post() {
  remove_action('thematic_singlepost', 'thematic_single_post');
}
add_action('init', 'remove_single_post');

function gallery_single_post() { 
  global $post;
	if(function_exists('file_get_contents')){
    $shortenedurl = file_get_contents('http://tinyurl.com/api-create.php?url=' . urlencode(get_permalink())); 
	} else {
		$shortenedurl = urlencode(get_permalink());
	}
		?>
			<div id="post-<?php the_ID(); ?>" class="<?php
			thematic_post_class(); 
			if(function_exists('p75GetVideo')){
		    if(p75GetVideo($post->ID)){ 
				  echo " video";
					$video = 1;
				}
			}
			?>">
			  <div class="entry-content">
			    <?php if(function_exists('the_ratings')) { echo the_ratings(); } ?>
			    <h1><?php the_title(); ?></h1>
				<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>', 'thematic').''); ?>
				<ul class="meta">
				  <?php if(get_post_meta($post->ID, 'designed-by')){ ?><li class="designer">Designed by: <?php echo get_post_meta($post->ID, 'designed-by', $single = true); ?></li><?php } ?>
				  <?php if(get_post_meta($post->ID, 'web-url')){ ?>
					  <li class="site-link"><a rel="source" href="<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>"><?php echo get_post_meta($post->ID, 'web-url', $single = true); ?></a></li>
					  <li class="delicious"><a href="http://del.icio.us/post?url=<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>&amp;<?php the_title(); ?>">Bookmark This (<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>)</a></li>
					  <li class="twitter"><a href="http://www.twitter.com/home?status=<?php echo str_replace(' ', '+', the_title_attribute('echo=0')); echo '+' . $shortenedurl; echo "+(via+@mixcss)"; ?>" title="Share <?php the_title_attribute(); ?> on Twitter">Tweet This</a></li>
				  <?php } ?>
				</ul>
			    <div id="nav-below" class="navigation">
			      <div class="nav-previous"><?php previous_post_link('%link', '<span class="meta-nav">&laquo;</span> %title') ?></div>
				  <div class="nav-next"><?php next_post_link('%link', '%title <span class="meta-nav">&raquo;</span>') ?></div>
			    </div>
			  </div>
			</div><!-- .post -->

			<div class="artwork-container">
			  <div class="entry-artwork">
          <?php if($video==1) {echo p75GetVideo($post->ID); }
					      else{ ?>
							<?php if(get_post_meta($post->ID, 'web-url')){ ?>
                    <a href="<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>"><img src="<?php if(get_post_meta($post->ID, 'full-image')){echo get_post_meta($post->ID, 'full-image', $single = true);}else{bloginfo('url'); echo '/wp-content/themes/gallery/images/full-image-default.jpg';} ?>" alt="<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>"/></a>
              <?php }else{ ?>
                    <img src="<?php if(get_post_meta($post->ID, 'full-image')){echo get_post_meta($post->ID, 'full-image', $single = true);}else{echo '/wp-content/themes/gallery/images/full-image-default.jpg';} ?>" alt="<?php echo get_post_meta($post->ID, 'web-url', $single = true); ?>"/>
							<?php } ?>
            <?php }?>
			  </div>
			</div>
<?php
}
add_action('thematic_singlepost', 'gallery_single_post');

// End of SINGLE

//Creating the content for the Archive
function remove_archive_loop() {
  remove_action('thematic_archiveloop', 'thematic_archive_loop');
}
add_action('init', 'remove_archive_loop');

function gallery_archive_loop() {
  global $post;
  while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php thematic_post_class() ?>">
				<div class="entry-content">
				<?php childtheme_post_header() ?>
	        	<a href="<?php echo the_permalink() ?>"><span class="slide-title"><?php echo the_title(); ?></span><img class="thumbnail" src="<?php if(get_post_meta($post->ID, 'thumbnail')){echo get_post_meta($post->ID, 'thumbnail', $single = true);} else{bloginfo('url'); echo "/wp-content/themes/gallery/images/thumbnail-default.jpg";} ?>" width="125" height="125" alt="<?php echo the_title() ?>" /></a>
				</div>
			</div><!-- .post -->

  <?php endwhile;
}
add_action('thematic_archiveloop', 'gallery_archive_loop');

// End of ARCHIVE

//Creating the content for the Category
function remove_category_loop() {
  remove_action('thematic_categoryloop', 'thematic_category_loop');
}
add_action('init', 'remove_category_loop');

function gallery_category_loop() {
  global $post;
  /* Count the number of posts so we can insert a widgetized area */ $count = 1;
  while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php thematic_post_class() ?>">
				<div class="entry-content">
				<?php childtheme_post_header() ?>
	        	<a href="<?php echo the_permalink() ?>"><span class="slide-title"><?php echo the_title(); ?></span><img class="thumbnail" src="<?php if(get_post_meta($post->ID, 'thumbnail')){echo get_post_meta($post->ID, 'thumbnail', $single = true);} else{bloginfo('url'); echo "/wp-content/themes/gallery/images/thumbnail-default.jpg";} ?>" width="125" height="125" alt="<?php echo the_title() ?>" /></a>
			  </div>
			</div><!-- .post -->

    <?php comments_template();

    if ($count==$thm_insert_position) {
      get_sidebar('index-insert');
    }
    $count = $count + 1;
  endwhile;
}
add_action('thematic_categoryloop', 'gallery_category_loop');

// End of CATEGORY

// Creating the content for the Tag
function remove_tag_loop() {
  remove_action('thematic_tagloop', 'thematic_tag_loop');
}
add_action('init', 'remove_tag_loop');

function gallery_tag_loop() {
  global $post;
  /* Count the number of posts so we can insert a widgetized area */ $count = 1;
  while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php thematic_post_class() ?>">
				<div class="entry-content">
			<?php childtheme_post_header() ?>
        <a href="<?php echo the_permalink() ?>"><span class="slide-title"><?php echo the_title(); ?></span><img class="thumbnail" src="<?php if(get_post_meta($post->ID, 'thumbnail')){echo get_post_meta($post->ID, 'thumbnail', $single = true);} else{bloginfo('url'); echo "/wp-content/themes/gallery/images/thumbnail-default.jpg";} ?>" width="125" height="125" alt="<?php echo the_title() ?>" /></a>
				</div>
			</div><!-- .post -->

    <?php comments_template();

    if ($count==$thm_insert_position) {
      get_sidebar('index-insert');
    }
    $count = $count + 1;
  endwhile;
}
add_action('thematic_tagloop', 'gallery_tag_loop');

// End of TAG

// Filter the Page Title
function gallery_page_title ($content) {
  if (is_category()) {
    $content = '<h1 class="page-title"><span>';
    $content .= single_cat_title("", false);
    $content .= '</span></h1>';
    if ( !(''== category_description()) ) {
	$content .= '<div class="archive-meta">';
	$content .= apply_filters('archive_meta', category_description());
	$content .= '</div>';
    }
  } elseif (is_tag()) {
    $content = '<h1 class="page-title"><span>';
    $content = thematic_tag_query();
    $content = '</span></h1>';
  }
  return $content;
}
add_filter('thematic_page_title', 'gallery_page_title');
// End of Filter the Page Title

// Add fix for ie6 styles
function fix_ie6(){
  echo '  <!--[if lt IE 7]>
    <script src="/wp-content/themes/gallery/js/DD_belatedPNG.js"></script>
    <script type="text/javascript">
      DD_belatedPNG.fix("body,#wrapper, ul.meta li,#blog-title a,#access,#access a,.new,#comments h3,ul.children li,.cover-up,.entry-content .post-ratings img,.post-ratings-image");    
    </script>
  <![endif]-->';

}

// Add slider and lazyload
function gallery_slider(){
  echo '<script type="text/javascript" src="';  bloginfo('url'); echo '/wp-content/themes/gallery/js/gallery.js"></script>';
  echo '<script type="text/javascript" src="';  bloginfo('url'); echo '/wp-content/themes/gallery/js/jquery.lazyload.pack.js"></script>';
	
}
add_action('wp_head','fix_ie6');
add_action('wp_head','gallery_slider');

// Custom post header
function childtheme_post_header(){

  global $childoptions;
	foreach ($childoptions as $childvalue) {
	if (get_settings( $childvalue['id'] ) === FALSE) { $$childvalue['id'] = $childvalue['std']; }
	else { $$childvalue['id'] = get_settings( $childvalue['id'] ); }
	}
	
	if($gall_newlength){
		$time = $gall_newlength;
	} else {
		$time = 3;
	}
	
	if ( (time()-get_the_time('U')) <= ($time*86400) ) { // The number 3 is how many days to keep posts marked as new
		echo '<div class="new"></div>';
	}
}

/*
Plugin Name: Custom Write Panel
Plugin URI: http://wefunction.com/2008/10/tutorial-create-custom-write-panels-in-wordpress
Description: Allows custom fields to be added to the WordPress Post Page
Version: 1.0
Author: Spencer
Author URI: http://wefunction.com
/* ----------------------------------------------*/

$new_meta_boxes =
  array(
  "full-image" => array(
  "name" => "full-image",
  "std" => "",
  "title" => "Path to Full-Size Image (500x375)",
  "description" => "Using the \"<em>Add an Image</em>\" button, upload a 500x375 image and paste the URL here."),
  "thumbnail" => array(
  "name" => "thumbnail",
  "std" => "",
  "title" => "Path to Thumbnail Image (125x125)",
  "description" => "Using the \"<em>Add an Image</em>\" button, upload a 125x125 thumbnail image and paste the URL here."),
  "designed-by" => array(
  "name" => "designed-by",
  "std" => "",
  "title" => "Designed by",
  "description" => "Enter the name of the designer (if known or applicable)."),
  "web-url" => array(
  "name" => "web-url",
  "std" => "",
  "title" => "Website URL",
  "description" => "Enter the full website URL (if applicable).")
);

function new_meta_boxes() {
  global $post, $new_meta_boxes;
  
  foreach($new_meta_boxes as $meta_box) {
    $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
    
    if($meta_box_value == "")
      $meta_box_value = $meta_box['std'];
    
    echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
    
    echo'<label style="font-weight: bold; display: block; padding: 5px 0 2px 2px" for="'.$meta_box['name'].'">'.$meta_box['title'].'</label>';
    
    echo'<input type="text" name="'.$meta_box['name'].'" value="'.$meta_box_value.'" size="55" /><br />';
    
    echo'<p><label for="'.$meta_box['name'].'">'.$meta_box['description'].'</label></p>';
  }
}

function create_meta_box() {
  global $theme_name;
  if ( function_exists('add_meta_box') ) {
    add_meta_box( 'new-meta-boxes', 'Gallery Post Settings', 'new_meta_boxes', 'post', 'normal', 'high' );
  }
}

function save_postdata( $post_id ) {
  global $post, $new_meta_boxes;
  
  foreach($new_meta_boxes as $meta_box) {
  // Verify
  if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }
  
  if ( 'page' == $_POST['post_type'] ) {
  if ( !current_user_can( 'edit_page', $post_id ))
    return $post_id;
  } else {
  if ( !current_user_can( 'edit_post', $post_id ))
    return $post_id;
  }
  
  $data = $_POST[$meta_box['name']];
  
  if(get_post_meta($post_id, $meta_box['name']) == "")
    add_post_meta($post_id, $meta_box['name'], $data, true);
  elseif($data != get_post_meta($post_id, $meta_box['name'], true))
    update_post_meta($post_id, $meta_box['name'], $data);
  elseif($data == "")
    delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
  }
}

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');




// Theme Options

$childthemename = "Gallery";
$childshortname = "gall";
$childoptions = array();

function gallery_options() {
    global $childthemename, $childshortname, $childoptions;

		$childoptions = array (
										
				array(	"name" => "Number of Days to Keep Posts as New",
								"desc" => "Select a number of days to keep posts as new.",
								"id" => $childshortname."_newlength",
								"std" => "3",
								"type" => "text")

		  );
}
add_action('init', 'gallery_options');

// Make a Theme Options Page

function childtheme_add_admin() {

    global $childthemename, $childshortname, $childoptions;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($childoptions as $childvalue) {
                    update_option( $childvalue['id'], $_REQUEST[ $childvalue['id'] ] ); }

                foreach ($childoptions as $childvalue) {
                    if( isset( $_REQUEST[ $childvalue['id'] ] ) ) { update_option( $childvalue['id'], $_REQUEST[ $childvalue['id'] ]  ); } else { delete_option( $childvalue['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($childoptions as $childvalue) {
                delete_option( $childvalue['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($childthemename." Options", "$childthemename Options", 'edit_themes', basename(__FILE__), 'childtheme_admin');

}

function childtheme_admin() {

    global $childthemename, $childshortname, $childoptions;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$childthemename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$childthemename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $childthemename; ?> Options</h2>

<form method="post">

<table class="form-table">

<?php foreach ($childoptions as $childvalue) { 
	
	switch ( $childvalue['type'] ) {
		case 'text':
		?>
		<tr valign="top"> 
		    <th scope="row"><?php echo $childvalue['name']; ?>:</th>
		    <td>
		        <input name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" type="<?php echo $childvalue['type']; ?>" value="<?php if ( get_settings( $childvalue['id'] ) != "") { echo get_settings( $childvalue['id'] ); } else { echo $childvalue['std']; } ?>" />
			    <?php echo $childvalue['desc']; ?>
		    </td>
		</tr>
		<?php
		break;
		
		case 'select':
		?>
		<tr valign="top"> 
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
	            <select name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>">
	                <?php foreach ($childvalue['options'] as $option) { ?>
	                <option<?php if ( get_settings( $childvalue['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $childvalue['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select>
			    <?php echo $childvalue['desc']; ?>
	        </td>
	    </tr>
		<?php
		break;
		
		case 'textarea':
		$ta_options = $childvalue['options'];
		?>
		<tr valign="top"> 
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
			    <?php echo $childvalue['desc']; ?>
				<textarea name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php 
				if( get_settings($childvalue['id']) != "") {
						echo stripslashes(get_settings($childvalue['id']));
					}else{
						echo $childvalue['std'];
				}?></textarea>
	        </td>
	    </tr>
		<?php
		break;

		case "radio":
		?>
		<tr valign="top"> 
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
	            <?php foreach ($childvalue['options'] as $key=>$option) { 
				$radio_setting = get_settings($childvalue['id']);
				if($radio_setting != ''){
		    		if ($key == get_settings($childvalue['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $childvalue['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="radio" name="<?php echo $childvalue['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
	            <?php } ?>
	        </td>
	    </tr>
		<?php
		break;
		
		case "checkbox":
		?>
			<tr valign="top"> 
		        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
		        <td>
		           <?php
						if(get_settings($childvalue['id'])){
							$checked = "checked=\"checked\"";
						}else{
							$checked = "";
						}
					?>
		            <input type="checkbox" name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" value="true" <?php echo $checked; ?> />
		            <?php  ?>
			    <?php echo $childvalue['desc']; ?>
		        </td>
		    </tr>
			<?php
		break;

		default:

		break;
	}
}
?>

</table>

<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<p><?php _e('For more information about this theme, <a href="http://themeshaper.com">visit ThemeShaper</a>. If you have any questions, visit the <a href="http://themeshaper.com/forums/">ThemeShaper Forums</a>.', 'thematic'); ?></p>

<?php
}

add_action('admin_menu' , 'childtheme_add_admin');

?>
