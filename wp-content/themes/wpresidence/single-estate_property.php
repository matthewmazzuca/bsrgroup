
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">

<title>
    <?php
    global $page, $paged;
    wp_title( '|', true, 'right' );
    bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    
    if ( $site_description && ( is_home() || is_front_page() ) ){
        echo " | $site_description";
    }
    
    if ( $paged >= 2 || $page >= 2 ){
        echo ' | ' . sprintf( __( 'Page %s', 'wpestate' ), max( $paged, $page ) );
    }
    ?>
</title>

<?php
global $property_adr_text;
global $property_details_text;
global $property_features_text;
global $feature_list_array;
global $use_floor_plans;
global $property_description_text;
global $post;
$walkscore_api= esc_html ( get_option('wp_estate_walkscore_api','') );
$show_graph_prop_page= esc_html( get_option('wp_estate_show_graph_prop_page', '') );
?>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 
<?php 
$favicon        =   esc_html( get_option('wp_estate_favicon_image','') );
if ( $favicon!='' ){
    echo '<link rel="shortcut icon" href="'.$favicon.'" type="image/x-icon" />';
} else {
    echo '<link rel="shortcut icon" href="'.get_template_directory_uri().'/img/favicon.gif" type="image/x-icon" />';
}


wp_head();


if( is_tax() ) {
    echo '<meta name="description" content="'.strip_tags( term_description('', get_query_var( 'taxonomy' ) )).'" >';
}

if (get_post_type()== 'estate_property'){
    $image_id       =   get_post_thumbnail_id();
    $share_img= wp_get_attachment_image_src( $image_id, 'full'); 
    ?>
    <meta property="og:image" content="<?php echo esc_url($share_img[0]); ?>"/>
    <meta property="og:image:secure_url" content="<?php echo esc_url($share_img[0]); ?>" />
<?php 
} 
?>
</head>



<?php 

$wide_class      =   '';
$wide_status     =   esc_html(get_option('wp_estate_wide_status',''));
if($wide_status==1){
    $wide_class=" wide ";
}

if( isset($post->ID) && wpestate_half_map_conditions ($post->ID) ){
    $wide_class="wide fixed_header ";
}


$halfmap_body_class='';
if( isset($post->ID) && wpestate_half_map_conditions ($post->ID) ){
    $halfmap_body_class=" half_map_body ";
}

if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="yes"){
    $halfmap_body_class.=" has_top_bar ";
}

$logo_header_type    =   get_option('wp_estate_logo_header_type','');
$header_transparent_class   =   '';

$header_transparent         =   get_option('wp_estate_header_transparent','');


//  $header_transparent_class=' header_transparent '; 

if(isset($post->ID) && !is_tax() && !is_category() ){
        $header_transparent_page    =   get_post_meta ( $post->ID, 'header_transparent', true);
        if($header_transparent_page=="global" || $header_transparent_page==""){
            if ($header_transparent=='yes'){
                $header_transparent_class=' header_transparent ';
            }
        }else if($header_transparent_page=="yes"){
            $header_transparent_class=' header_transparent ';
        }
}else{
    if ($header_transparent=='yes'){
            $header_transparent_class=' header_transparent ';
    }
}

$logo           =   get_option('wp_estate_logo_image','');   
$logo_margin    =   intval( get_option('wp_estate_logo_margin','') );
?>




<body <?php body_class($halfmap_body_class); ?>>  
   

<?php   get_template_part('templates/mobile_menu' ); ?> 
    
<div class="website-wrapper" id="all_wrapper" >
<div class="container  <?php print esc_html($wide_class); print esc_html('has_header_'.$logo_header_type.' '.$header_transparent_class); ?> ">

    <div class="master_header <?php print esc_html($wide_class.' '.$header_transparent_class); ?>">
        
        <?php   
            if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="yes"){
                get_template_part( 'templates/top_bar' ); 
            } 
            get_template_part('templates/mobile_menu_header' );
        ?>
       
        
        <div class="header_wrapper <?php echo 'header_'.$logo_header_type;?> ">
            <div class="header_wrapper_inside">
                
                <div class="logo" >
                    <a href="<?php echo home_url('','login');?>">
                        <?php  
                        if ( $logo!='' ){
                           print '<img style="margin-top:'.$logo_margin.'px;" src="'.$logo.'" class="img-responsive retina_ready" alt="logo"/>';    
                        } else {
                           print '<img class="img-responsive retina_ready" src="'. get_template_directory_uri().'/img/logo.png" alt="logo"/>';
                        }
                        ?>
                    </a>
                </div>   

              
                <?php 
                if(esc_html ( get_option('wp_estate_show_top_bar_user_login','') )=="yes"){
                   get_template_part('templates/top_user_menu');  
                }
                ?>    
                <nav id="access">
                    <?php 
                      /*wp_nav_menu( array( 'theme_location' => 'primary' ) );
                      
                       */  wp_nav_menu( 
                            array(  'theme_location'    => 'primary' ,
                                    'walker'            => new wpestate_custom_walker
                                ) 
                            ); 
                        
                        
                    ?>
                </nav><!-- #access -->
            </div>
        </div>

     </div> 
    
    
  <div class="container content_wrapper" style="margin-top: 100px;">
<?php
// Index Page
// Wp Estate Pack
global $current_user;
global $feature_list_array;
global $propid ;
$current_user = wp_get_current_user();
wp_estate_count_page_stats($post->ID);

$propid                     =   $post->ID;
$options                    =   wpestate_page_details($post->ID);
$gmap_lat                   =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
$gmap_long                  =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
$unit                       =   esc_html( get_option('wp_estate_measure_sys', '') );
$currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$use_floor_plans            =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );      


if (function_exists('icl_translate') ){
    $where_currency             =   icl_translate('wpestate','wp_estate_where_currency_symbol', esc_html( get_option('wp_estate_where_currency_symbol', '') ) );
    $property_description_text  =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
    $property_details_text      =   icl_translate('wpestate','wp_estate_property_details_text', esc_html( get_option('wp_estate_property_details_text') ) );
    $property_features_text     =   icl_translate('wpestate','wp_estate_property_features_text', esc_html( get_option('wp_estate_property_features_text') ) );
    $property_adr_text          =   icl_translate('wpestate','wp_estate_property_adr_text', esc_html( get_option('wp_estate_property_adr_text') ) );    
}else{
    $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $property_description_text  =   esc_html( get_option('wp_estate_property_description_text') );
    $property_details_text      =   esc_html( get_option('wp_estate_property_details_text') );
    $property_features_text     =   esc_html( get_option('wp_estate_property_features_text') );
    $property_adr_text          =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
}


$agent_id                   =   '';
$content                    =   '';
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);
$favorite_class             =   'isnotfavorite'; 
$favorite_text              =   __('add to favorites','wpestate');
$feature_list               =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array         =   explode( ',',$feature_list);
$pinteres                   =   array();
$property_city              =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$property_category          =   get_the_term_list($post->ID, 'property_category', '', ', ', '') ;
$property_action            =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');   
$slider_size                =   'small';
$thumb_prop_face            =   wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'property_full');

if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
        $favorite_class =   'isfavorite';     
        $favorite_text  =   __('favorite','wpestate');
    } 
}

if (has_post_thumbnail()){
    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'property_full_map');
}


if($options['content_class']=='col-md-12'){
    $slider_size='full';
}

?>



<div class="row">
    <?php get_template_part('templates/breadcrumbs'); ?>
    <div class=" <?php print esc_html($options['rightmargin']);?> ">
        <?php get_template_part('templates/ajax_container'); ?>
        <?php
        while (have_posts()) : the_post();
            $price          =   floatval   ( get_post_meta($post->ID, 'property_price', true) );
            $price_label    =   esc_html ( get_post_meta($post->ID, 'property_label', true) ); 
            $price_label_before    =   esc_html ( get_post_meta($post->ID, 'property_label_before', true) );  
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
            if ($price != 0) {
               $price = wpestate_show_price(get_the_ID(),$currency,$where_currency,1);  
           }else{
               $price='<span class="price_label price_label_before">'.$price_label_before.'</span><span class="price_label ">'.$price_label.'</span>';
               
           }
        ?>
        
        <h1 class="entry-title entry-prop"><?php the_title(); ?></h1>  
        <span class="price_area"><?php print ($price); ?></span>
        <div class="single-content listing-content">
            
          
             
        <?php            
      

        $status = esc_html( get_post_meta($post->ID, 'property_status', true) );    
        if (function_exists('icl_translate') ){
            $status     =   icl_translate('wpestate','wp_estate_property_status_'.$status, $status ) ;                                      
        }

        ?>
            
            
        <div class="notice_area">           
            
            <div class="property_categs">
                <?php print ($property_category) .' '.__('in','wpestate').' '.($property_action);?>
            </div>  
            
            <span class="adres_area">
                <?php 
                  
                    $property_address =esc_html( get_post_meta($post->ID, 'property_address', true) );
                    if($property_address!=''){
                        print esc_html($property_address);
                    }
                    
                    if($property_city!=''){
                        if($property_address!=''){
                            print ', ';
                        }
                        print ($property_city);
                    }
                      
                    if($property_area!=''){
                        if($property_address!='' || $property_city!=''){
                            print ', ';
                        }
                        print ($property_area);
                    }
                    
                ?>
            
            </span>   
            <div id="add_favorites" class="<?php print esc_html($favorite_class);?>" data-postid="<?php the_ID();?>"><?php echo esc_html($favorite_text);?></div>                 
            <div class="download_pdf"></div>
           
            <div class="prop_social">
                <div class="no_views dashboad-tooltip" data-original-title="<?php _e('Number of Page Views','wpestate');?>"><i class="fa fa-eye-slash "></i><?php echo intval( get_post_meta($post->ID, 'wpestate_total_views', true) );?></div>
                <i class="fa fa-print" id="print_page" data-propid="<?php print $post->ID;?>"></i>
                <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><i class="fa fa-facebook fa-2"></i></a>
                <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. get_permalink()); ?>" class="share_tweet" target="_blank"><i class="fa fa-twitter fa-2"></i></a>
                <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><i class="fa fa-google-plus fa-2"></i></a> 
                <?php if (isset($pinterest[0])){ ?>
                   <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url($pinterest[0]);?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"> <i class="fa fa-pinterest fa-2"></i> </a>      
                <?php } ?>
              
            </div>
        </div>    
    <div role="tabpanel" id="tab_prpg">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#description" aria-controls="description" role="tab" data-toggle="tab">
        <?php 
            if($property_description_text!=''){
                echo esc_html($property_description_text);
            }else{
                _e('Description','wpestate');
            }
        ?>
        </a>
        
    </li>
    
    <!-- <li role="presentation">
        <a href="#address" aria-controls="address" role="tab" data-toggle="tab">
            <?php 
                if($property_adr_text!=''){
                    echo esc_html($property_adr_text);
                } else{
                    _e('Property Address','wpestate');
                }
            ?>
        </a>
    </li> -->
    
    <!-- <li role="presentation">
        <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
            <?php                      
                if($property_details_text=='') {
                    print __('Property Details', 'wpestate');
                }else{
                    print  $property_details_text;
                }
            ?>
        </a>
    </li> -->
    <?php
    if ( count( $feature_list_array )!= 0 && count($feature_list_array)!=1 ){ ?>
        <!-- <li role="presentation">
            <a href="#features" aria-controls="features" role="tab" data-toggle="tab">
               <?php
                    if($property_features_text ==''){
                        print __('Amenities and Features', 'wpestate');
                    }else{
                        print $property_features_text;
                    }
                ?>
            </a>
        </li> -->
    <?php } ?>
    
    <?php
    $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') ); 
    $local_pgpr_slider_type_status  =   get_post_meta($post->ID, 'local_pgpr_slider_type', true);
    
    if( ($local_pgpr_slider_type_status=='global' && $prpg_slider_type_status == 'full width header') ||
            $local_pgpr_slider_type_status=='full width header' ){
    ?>
       
    <?php } ?>
        
    
    <?php if($walkscore_api!=''){?>
        <li role="presentation">
            <a href="#walkscore" aria-controls="walkscore" role="tab" data-toggle="tab">
                <?php _e('Walkscore','wpestate');?>
            </a>
        </li>
    <?php } ?>
        
    
    <?php if ( $use_floor_plans==1 ){  ?>
    <li role="presentation">
        <a href="#floor" aria-controls="floor" role="tab" data-toggle="tab">
            <?php _e('Floor Plans','wpestate');?>
        </a>
    </li>
    <?php } ?>
    
    <?php if($show_graph_prop_page=='yes'){?>
    <!-- <li role="presentation" class="tabs_stats" data-listingid="<?php echo intval($post->ID);?>">
        <a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">
            <?php _e('Page Views','wpestate');?>
        </a>
    </li> -->
    <?php }?>
    <li role="presentation">
            <a href="#propmap" aria-controls="propmap" role="tab" data-toggle="tab">
                <?php _e('Map','wpestate');?>
            </a>
        </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="description">

        <?php //print 'Status:'.$status.'</br>'; ?>

        <?php //get_template_part('templates/listingslider');
        // slider type -> vertical or horizinalt
        $local_pgpr_slider_type_status  =   get_post_meta($post->ID, 'local_pgpr_slider_type', true);
        $prpg_slider_type_status        =   esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
       
    
    $show_slider=1;
    if ( $local_pgpr_slider_type_status=='full width header'){
        $show_slider=0; 
    }
    
    if( $local_pgpr_slider_type_status=='global' && $prpg_slider_type_status == 'full width header')  {
        $show_slider=0;
    }
        
    // if ( $show_slider==1 ){ 
        
            
    //         if ($local_pgpr_slider_type_status=='global'){
    //             $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
    //             if($prpg_slider_type_status=='vertical'){
    //                 get_template_part('templates/listingslider-vertical');
    //             }else{
    //                 get_template_part('templates/listingslider');
    //             }
    //         }elseif($local_pgpr_slider_type_status=='vertical') {    
    //             get_template_part('templates/listingslider-vertical');
    //         }else{
    //             get_template_part('templates/listingslider');
    //         }
        
        
    // }
    ?>
    <?php
// this is the slider for the blog post
// embed_video_id embed_video_type
global $slider_size;
$video_id       =   '';
$video_thumb    =   '';
$video_alone    =   0;
$full_img       =   '';
$arguments      = array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'post_parent' => $post->ID,
                    'post_status' => null,
                    'exclude' => get_post_thumbnail_id(),
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                );

$post_attachments   = get_posts($arguments);

$video_id           = esc_html( get_post_meta($post->ID, 'embed_video_id', true) );
$video_type         = esc_html( get_post_meta($post->ID, 'embed_video_type', true) );
      
$prop_stat = esc_html( get_post_meta($post->ID, 'property_status', true) );    
if (function_exists('icl_translate') ){
    $prop_stat     =   icl_translate('wpestate','wp_estate_property_status_'.$prop_stat, $prop_stat ) ;                                      
}
$ribbon_class       = str_replace(' ', '-', $prop_stat);    
        
        
if ($post_attachments || has_post_thumbnail() || get_post_meta($post->ID, 'embed_video_id', true)) {  ?>   
    <div class="row">
    <div class="col-md-8">
    <div id="carousel-listing" style="height: 400px;" class=" carousel slide post-carusel" data-ride="carousel" data-interval="false">
        <?php 
        if($prop_stat!='normal'){
            print '<div class="slider-property-status ribbon-wrapper-'.$ribbon_class.' '.$ribbon_class.'">' . $prop_stat . '</div>';
        }
        ?>
        
        <?php  
        $indicators='';
        $round_indicators='';
        $slides ='';
        $captions='';
        $counter=0;
        $has_video=0;
        if($video_id!=''){
            $has_video  =   1; 
            $counter    =   1;
            $videoitem  =   'videoitem';
            if ($slider_size    ==  'full'){
                $videoitem  =  'videoitem_full';
            }
          
            
            $indicators.='<li data-target="#carousel-listing"  data-video_data="'.$video_type.'" data-video_id="'.$video_id.'"  data-slide-to="0" class="active video_thumb_force">
                         <img src= "'.get_video_thumb($post->ID).'" alt="video_thumb" class="img-responsive"/>
                         <span class="estate_video_control"><i class="fa fa-play"></i> </span>
                         </li>'; 

            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="0" class="active"></li>';

            $slides .= '<div class="item active '.$videoitem.'">';

             if($video_type=='vimeo'){
                 $slides .= custom_vimdeo_video($video_id);
             }else{
                  $slides.= custom_youtube_video($video_id);
             }

             $slides   .= '</div>';
             $captions .= '<span data-slide-to="0" class="active" >'.__('Video','wpestate').'</span>';
        }

        if( has_post_thumbnail() ){
              $counter++;
            $active='';
            if($counter==1 && $has_video!=1){
                $active=" active ";
            }else{
                $active=" ";
            }

            $post_thumbnail_id  = get_post_thumbnail_id( $post->ID );
            $preview            = wp_get_attachment_image_src($post_thumbnail_id, 'slider_thumb');
            
            if ($slider_size=='full'){
                $full_img           = wp_get_attachment_image_src($post_thumbnail_id, 'listing_full_slider_1');
            }else{
                $full_img           = wp_get_attachment_image_src($post_thumbnail_id, 'listing_full_slider');
            }
          
            $full_prty          = wp_get_attachment_image_src($post_thumbnail_id, 'full');
            $attachment_meta    = wp_get_attachment($post_thumbnail_id);

            $indicators.= '<li data-target="#carousel-listing" data-slide-to="'.($counter-1).'" class="'. $active.'">
                                <img  src="'.$preview[0].'"  alt="slider" />
                           </li>';

            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="'.($counter-1).'" class="'. $active.'" ></li>';
            $slides .= '<div class="item '.$active.' ">
                           <a href="'.$full_prty[0].'" rel="prettyPhoto[pp_gal]" class="prettygalery"> 
                                <img  src="'.$full_img[0].'"  alt="'.$attachment_meta['alt'].'" class="img-responsive" />
                           </a>
                        </div>';

            $captions .= '<span data-slide-to="'.($counter-1).'" class="'.$active.'" >'. $attachment_meta['caption'].'</span>';

        }



        foreach ($post_attachments as $attachment) {
            $counter++;
            $active='';
            if($counter==1 && $has_video!=1){
                $active=" active ";
            }else{
                $active=" ";
            }

            $preview            = wp_get_attachment_image_src($attachment->ID, 'slider_thumb');
            if ($slider_size=='full'){
                $full_img           = wp_get_attachment_image_src($attachment->ID, 'listing_full_slider_1');
            }else{
                $full_img           = wp_get_attachment_image_src($attachment->ID, 'listing_full_slider');
            }
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'full');
            $attachment_meta    = wp_get_attachment($attachment->ID);
         
            $indicators.= ' <li data-target="#carousel-listing" data-slide-to="'.($counter-1).'" class="'. $active.'">
                                <img  src="'.$preview[0].'"  alt="slider" />
                            </li>';
            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="'.($counter-1).'" class="'. $active.'"></li>';

            $slides .= '<div class="item '.$active.'">
                        <a href="'.$full_prty[0].'" rel="prettyPhoto[pp_gal]" class="prettygalery" > 
                            <img  src="'.$full_img[0].'" alt="'.$attachment_meta['alt'].'" class="img-responsive" />
                         </a>
                        </div>';

            $captions .= '<span data-slide-to="'.($counter-1).'" class="'.$active.'"> '. $attachment_meta['caption'].'</span>';                    
        }// end foreach
        ?>

    <?php 
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
    $global_header_type         =   get_option('wp_estate_header_type','');

  
  
    if ( $header_type == 0 ){ // global
        if ($global_header_type != 4){
                $gmap_lat                   =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
                $gmap_long                  =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
                $property_add_on            =   ' data-post_id="'.$post->ID.'" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
                ?>
                <div id="slider_enable_map">    <i class="fa fa-map-marker"></i>        </div>
                <?php 
                $no_street=' no_stret ';
                if ( get_post_meta($post->ID, 'property_google_view', true) ==1){
                    print '  <div id="slider_enable_street"> <i class="fa fa-location-arrow"></i>    </div>';
                      $no_street='';
                }
                ?>
              
                <div id="slider_enable_slider" class="slideron <?php echo   $no_street; ?>"> <i class="fa fa-picture-o"></i>         </div>
                
                <div id="gmapzoomplus"  class="smallslidecontrol"><i class="fa fa-plus"></i> </div>
                <div id="gmapzoomminus" class="smallslidecontrol"><i class="fa fa-minus"></i></div>
        
                <div id="googleMapSlider" <?php print $property_add_on; ?> >              
                </div> 
        <?php       
        }
    }else{
        if($header_type!=5){
                $gmap_lat                   =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
                $gmap_long                  =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
                $property_add_on            =   ' data-post_id="'.$post->ID.'" data-cur_lat="'.$gmap_lat.'" data-cur_long="'.$gmap_long.'" ';
                ?>
                <div id="slider_enable_map">    <i class="fa fa-map-marker"></i>        </div>
                <?php 
                $no_street=' no_stret ';
                if ( get_post_meta($post->ID, 'property_google_view', true) ==1){
                    print '  <div id="slider_enable_street"> <i class="fa fa-location-arrow"></i>    </div>';
                      $no_street='';
                }
                ?>
                <div id="slider_enable_slider" class="slideron <?php echo   $no_street; ?>"> <i class="fa fa-picture-o"></i>         </div>
                
                <div id="gmapzoomplus"  class="smallslidecontrol" ><i class="fa fa-plus"></i> </div>
                <div id="gmapzoomminus" class="smallslidecontrol" ><i class="fa fa-minus"></i></div>
                
                <div id="googleMapSlider" <?php print $property_add_on; ?> >   
                </div>
        <?php        
        }
    }
       
   
    ?>    

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <?php print $slides;?>
    </div>

    <!-- Indicators -->    
    <div class="carusel-back"></div>  
    <ol class="carousel-indicators">
      <?php print $indicators; ?>
    </ol>

    <ol class="carousel-round-indicators">
        <?php print $round_indicators;?>
    </ol> 

    <div class="caption-wrapper">   
      <?php print $captions;?>
        <div class="caption_control"></div>
    </div>  

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-listing" data-slide="prev">
      <i class="fa fa-angle-left"></i>
    </a>
    <a class="right carousel-control" href="#carousel-listing" data-slide="next">
      <i class="fa fa-angle-right"></i>
    </a>
    </div>
    </div>

    <div class="col-md-4">
        <?php print estate_listing_details($post->ID);?> 

    </div>
    </div>

<?php
} // end if post_attachments
?>
            
         
            
        <?php
        
        // content type -> tabs or accordion
        
        $local_pgpr_content_type_status     =  get_post_meta($post->ID, 'local_pgpr_content_type', true);
        // if($local_pgpr_content_type_status =='global'){
        //     $global_prpg_content_type_status= esc_html ( get_option('wp_estate_global_prpg_content_type','') );
        //     if($global_prpg_content_type_status=='tabs'){
        //         get_template_part ('/templates/property_page_tab_content'); 
        //     }else{
        //         get_template_part ('/templates/property_page_acc_content'); 
        //     }
        // }
        // elseif ($local_pgpr_content_type_status =='tabs') {
        //     get_template_part ('/templates/property_page_tab_content');
        // }else{
        //     get_template_part ('/templates/property_page_acc_content'); 
        // }
         
        ?>    
        
        <?php 
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);

            if($content!=''){                            
                print $content;     
            }

            get_template_part ('/templates/download_pdf');
        ?>      
    </div>

    <!-- <div role="tabpanel" class="tab-pane" id="address">
        <?php print estate_listing_address($post->ID); ?>
    </div> -->
      
    <div role="tabpanel" class="tab-pane" id="details">
        <?php print estate_listing_details($post->ID);?>  
    </div>
      
    <div role="tabpanel" class="tab-pane" id="features">
        <?php print estate_listing_features($post->ID); ?>
    </div>  
    
    <?php
    $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );        
    if( ($local_pgpr_slider_type_status == 'global' && $prpg_slider_type_status == 'full width header') ||
        $local_pgpr_slider_type_status  == 'full width header' ){
    ?>

    <?php } ?>  
      


    <?php if($walkscore_api!=''){?>
        <div role="tabpanel" class="tab-pane" id="walkscore">
            <?php wpestate_walkscore_details($post->ID); ?>
        </div>
    <?php } ?> 
      

        <div role="tabpanel" class="tab-pane" id="floor">
            <table class="table table-striped">
            <tbody>
            <thead>
              <tr>
                  <th>Unit</th>
                  <th>Plan Size</th>
                  <th>Plan Rooms</th>
                  <th>Plan Price</th>
                  <th>Status</th>
                  <th>Plan Image</th>
                  
                  
              </tr>
            </thead>
            <?php print estate_floor_plan($post->ID); ?>
            </tbody>
            </table>
        </div>

<div role="tabpanel" class="tab-pane" id="propmap">
<div id="map"></div>
<?php 
$show_adv_search_status     =   get_option('wp_estate_show_adv_search','');
$global_header_type         =   get_option('wp_estate_header_type','');
$adv_search_type            =   get_option('wp_estate_adv_search_type','');
?>
<div class="header_media with_search_<?php echo esc_html($adv_search_type);?>">

    <?php
if ( is_category() || is_tax() || is_archive() || is_search() ){
    $header_type=0;
}else{
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}

if( isset($post->ID) && !wpestate_half_map_conditions ($post->ID) ){
    $custom_image               =   esc_html( esc_html(get_post_meta($post->ID, 'page_custom_image', true)) );  
    $rev_slider                 =   esc_html( esc_html(get_post_meta($post->ID, 'rev_slider', true)) ); 
    
    
    ////////////////////////////////////////////////////////////////////////////
    // if taxonomy
    ////////////////////////////////////////////////////////////////////////////
    if( is_tax() ){
        $taxonmy    =   get_query_var('taxonomy');
        if ( $taxonmy !=='property_action_category' && $taxonmy!='property_category'  ){
            global $term_data;
            $term       =   get_query_var( 'term' );
            $term_data  =   get_term_by('slug', $term, $taxonmy);
            $place_id   =   $term_data->term_id;
            $term_meta  =   get_option( "taxonomy_$place_id");
            if( isset($term_meta['category_featured_image']) && $term_meta['category_featured_image']!='' ){
               $header_type=7;
            }
        }
      
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // if property page
    ////////////////////////////////////////////////////////////////////////////
    
    
    if(is_singular('estate_property')){
        $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
        $local_pgpr_slider_type_status=  get_post_meta($post->ID, 'local_pgpr_slider_type', true);
          
        if($local_pgpr_slider_type_status=='global' && $prpg_slider_type_status === 'full width header'){
            $header_type=8;
        }
        if($local_pgpr_slider_type_status=='full width header'){
            $header_type=8;
        }
    }
    
    
    
     
    if (!$header_type==0){  // is not global settings
          switch ($header_type) {
            case 1://none
                break;
            case 2://image
                print '<img src="'.$custom_image.'"  class="img-responsive" alt="header_image"/>';
                break;
            case 3://theme slider
                wpestate_present_theme_slider();
                break;
            case 4://revolutin slider
                putRevSlider($rev_slider);
                break;
            case 5://google maps
                get_template_part('templates/google_maps_base'); 
                break;
            case 7://google maps
                get_template_part('templates/header_taxonomy'); 
                break;
            case 8:
                wpestate_listing_full_width_slider($post->ID);
                break;
          }
        
         
            
    }else{    // we don't have particular settings - applt global header
          switch ($global_header_type) {
            case 0://image
                break;
            case 1://image
                $global_header  =   get_option('wp_estate_global_header','');
                print '<img src="'.$global_header.'"  class="img-responsive" class="headerimg" alt="header_image"/>';
                break;
            case 2://theme slider
                wpestate_present_theme_slider();
                break;
            case 3://revolutin slider
                 $global_revolution_slider   =  get_option('wp_estate_global_revolution_slider','');
                 putRevSlider($global_revolution_slider);
                break;
            case 4://google maps
                get_template_part('templates/google_maps_base'); 
                break;
            case 8:
                wpestate_listing_full_width_slider($post->ID);
                break;
          }
    
    } // end if header
}
    

    
    
    

    
                     
?>
    
<?php
$show_adv_search_general    =   get_option('wp_estate_show_adv_search_general','');

$global_header_type         =   get_option('wp_estate_header_type','');
$show_adv_search_slider     =   get_option('wp_estate_show_adv_search_slider','');
$show_mobile                =   0;  

if ( is_category() || is_tax() || is_archive() || is_search() ){
    $header_type=0;
}else{
    $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
}
    
if($show_adv_search_general ==  'yes' && !is_404() && !is_page_template('property_list_half.php')){
    
    if( isset($post->ID) && !wpestate_half_map_conditions ($post->ID) ){
        if($header_type == 1){
          //nothing  
        }else if($header_type == 0){ 
            if($global_header_type==4){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
            }else if( $global_header_type==0){
               //nonthing 
            }else{
                if($show_adv_search_slider=='yes'){
                    $show_mobile=1;
                    get_template_part('templates/advanced_search');  
                }
            }

        }else if($header_type == 5){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
        }else{
             if($show_adv_search_slider=='yes'){
                $show_mobile=1;
                get_template_part('templates/advanced_search');  
            }
        }  
    }
}
?>   
</div>

<?php 

if( $show_mobile == 1 ){
    get_template_part('templates/adv_search_mobile');
}
?>
</div> 

      
    <?php if($show_graph_prop_page=='yes'){ ?>
        <div role="tabpanel" class="tab-pane" id="stats">
             <div class="panel-body">
                <canvas id="myChartacc"></canvas>
             </div>
        </div>
    <?php } ?>
      
      
  </div>

</div>
            
            
       
    
        <?php 
        wp_reset_query();
        ?>  
         
        
       
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        
        $sidebar_agent_option_value=    get_post_meta($post->ID, 'sidebar_agent_option', true);
        $enable_global_property_page_agent_sidebar= esc_html ( get_option('wp_estate_global_property_page_agent_sidebar','') );
        if ( $sidebar_agent_option_value=='global' ){
            if($enable_global_property_page_agent_sidebar!='yes'){
                get_template_part ('/templates/agent_area');
            }
            
        }else if($sidebar_agent_option_value !='yes'){
             get_template_part ('/templates/agent_area');
        }
        
        get_template_part ('/templates/similar_listings');
     
        ?>

        </div><!-- end single content -->
    </div><!-- end 9col container-->
    
</div>   

<?php get_footer(); ?>
