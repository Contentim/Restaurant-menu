<?php get_header(); ?>

<?
    $taxonomy = 'menu';
    $post_type = 'food';

    $term_id = get_queried_object()->term_id;

    $terms = get_terms($taxonomy);

    $i = 1;
?>

<?php
    $posts = get_posts(array(
        'showposts' => -1,
        'post_type' => $post_type,
        'tax_query' => array(
            array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'terms' => $term_id,
            'include_children' => false
        )),
        'orderby' => 'title',
        'order' => 'ASC'
    ));
?>

<!-- /content/themes/moon-prism/taxonomy-menu.php -->

<section class="content">

  <div class="container">

    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" >
            <h1 class="text-align__center" style="margin-bottom: 20px;"><?php single_term_title( ); ?></h1>
        </div>

        <?
            $category_arr = array();
            $x=1;

            foreach ( $terms as $term ) {
                $term_id = get_queried_object()->term_id;
                $id = $term->term_id;

                $querySort = $wpdb->get_results("
                    SELECT  `meta_value`
                    FROM    `st_termmeta`
                    WHERE   `term_id` ='".$id."' AND `meta_key` = 'sort_tax_menu'");

                if($term->parent == $term_id) {

                    $src = ttw_thumbnail_url($term->term_id);
                    $pattern = '/taxonomy-thumbnail-widget/';

                    if(preg_match($pattern, $src)){
                        $src = 'https://place-hold.it/555x411.jpg';
                    }

                    // echo '<pre>';
                    // print_r($id);
                    // echo '</pre>';

                if(empty($querySort)){
                    $sortResult = 9999;
                } else if(isset($querySort[0]->meta_value) && empty($querySort[0]->meta_value)) {
                    $sortResult = 9999;
                } else {
                    $sortResult = $querySort[0]->meta_value;
                }

                array_push($category_arr, array(
                    'id' => $id,
                    'title' => $term->name,
                    'src' => $src,
                    'slug' => $term->slug,
                    'sort' => (int)$sortResult,
                ));

                }

            }

            // sort ASC - по возрастанию
            usort($category_arr, function($a, $b){
                return $a['sort'] <=> $b['sort'];
            });

        ?>

        <? foreach ($category_arr as $item): ?>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-25" id="term_<?=$item['id']?>">
                <div class="flex-child">

                    <a href="/menu/<?=$item['slug']?>" title="<?=$item['title']?>" class="content_container">
                        <div class="thumbnail_container"><img src="<?=$item['src']?>" alt="<?=$item['title']?>" title="<?=$item['title']?>"></div>
                        <div class="title_container">
                            <div class="title__item"><?=$item['title']?></div>
                        </div>
                    </a>
                </div>
            </div>
            <?php
                if($x%2 == 0){
                    echo '<div class="clearfix"></div>';
                }
                $x++;
            ?>
        <? endforeach; ?>
    </div>

    <div class="clearfix"></div>

    <div class="page-content posts-news">
      <div class="news-content">

        <div class="row" style="display: block;">

            <?
                $posts_arr = array();

                $posts_arr_is_img = array();
                $posts_arr_not_img = array();
            ?>
            <? foreach ($posts as $post): ?>
            <?
                $id = $post->ID;
                $title = get_the_title();
                $src = get_the_post_thumbnail_url();

                $data_post = get_post($post->ID);
                $content = $data_post->post_content;

                $post_modified = get_post_modified_time('U', false, $post->ID);

                $single_size = get_field("single_size", get_the_id());
                $single_price = get_field("single_price", get_the_id());

                $s_size = get_field("s_size", get_the_id());
                $s_price = get_field("s_price", get_the_id());

                $xl_size = get_field("xl_size", get_the_id());
                $xl_price = get_field("xl_price", get_the_id());


                $size_is_garnish = trim(get_field("size_is_garnish", get_the_id()));
                $price_is_garnish = trim(get_field("price_is_garnish", get_the_id()));

                $size_not_garnish = trim(get_field("size_not_garnish", get_the_id()));
                $price_not_garnish = trim(get_field("price_not_garnish", get_the_id()));

                

                if (!empty($src)){
                    array_push($posts_arr_is_img, array(
                        'id' => $id,
                        'title' => $title,
                        'src' => $src,
                        'content' => $content,
                        'post_modified' => $post_modified,
                        'single_size' => $single_size,
                        'single_price' => $single_price,
                        's_size' => $s_size,
                        's_price' => $s_price,
                        'xl_size' => $xl_size,
                        'xl_price' => $xl_price,
                        'size_is_garnish' => $size_is_garnish,
                        'price_is_garnish' => $price_is_garnish,
                        'size_not_garnish' => $size_not_garnish,
                        'price_not_garnish' => $price_not_garnish,
                    ));
                } else {
                    array_push($posts_arr_not_img, array(
                        'id' => $id,
                        'title' => $title,
                        'src' => $src,
                        'content' => $content,
                        'post_modified' => $post_modified,
                        'single_size' => $single_size,
                        'single_price' => $single_price,
                        's_size' => $s_size,
                        's_price' => $s_price,
                        'xl_size' => $xl_size,
                        'xl_price' => $xl_price,
                        'size_is_garnish' => $size_is_garnish,
                        'price_is_garnish' => $price_is_garnish,
                        'size_not_garnish' => $size_not_garnish,
                        'price_not_garnish' => $price_not_garnish,
                    ));
                }

            ?>
            <? endforeach; ?>

            <?
                usort($posts_arr_is_img, function($a, $b){
                    return $b['post_modified'] <=> $a['post_modified'];
                });

                usort($posts_arr_not_img, function($a, $b){
                    return $b['post_modified'] <=> $a['post_modified'];
                });

                $posts_arr = array_merge($posts_arr_is_img, $posts_arr_not_img);
            ?>

            <? foreach ($posts_arr as $post): ?>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-25">
                  <div class="post-list-item menu__item" style="background: transparent">

                  <?php if (!empty($post['src'])): ?>
                        <div class="img"><img src="<?=$post['src']?>" alt="<?=$post['title']?>" title="<?=$post['title']?>"/></div>
                    <? else: ?>
                        <div class="img" hidden><img src="https://place-hold.it/1147x853.jpg"></div>
                  <? endif; ?>

                  <div class="title">
                    <?=$post['title']?>
                    <?
                        if(!empty($post['single_size'])) {
                            if(empty(trim($size_is_garnish)) && empty(trim($price_is_garnish)) && empty(trim($size_not_garnish)) && empty(trim($price_not_garnish))){
                                echo '<span class="single_size">| ' . $post['single_size'] . '</span>';
                            }                            
                        }
                    ?>
                  </div>

                  <div class="excerpt"><?=$post['content']?></div>

                  <div class="price">
                    <?
                        if(!empty($post['single_price'])){
                            if(empty(trim($post['size_is_garnish'])) && empty(trim($post['price_is_garnish'])) && empty(trim($post['size_not_garnish'])) && empty(trim($post['price_not_garnish']))){
                                echo '<div class="bold" title="'.$post['single_price'].' руб.">';
                                echo $post['single_price'] . '-';
                                echo '</div>';
                            }                            
                        }
                    ?>

                    <?
                        if(!empty($post['s_size']) || !empty($post['xl_size'])){
                            if(empty(trim($post['size_is_garnish'])) && empty(trim($post['price_is_garnish'])) && empty(trim($post['size_not_garnish'])) && empty(trim($post['price_not_garnish']))){
                                echo '<div class="size_multi">';

                                if(!empty($post['s_size'])){
                                    echo '<img src="/content/uploads/2020/01/icon_s_size.png">' . '<span class="size">' . $post['s_size'] . '</span>';
                                    echo '<span class="bold price_item" title="'. $post['s_price'] .' руб.">' . $post['s_price'] . '-</span>';
                                }
                                if(!empty($post['xl_size'])){
                                    echo '<img src="/content/uploads/2020/01/icon_xl_size.png">' . '<span class="size">' . $post['xl_size'] . '</span>';
                                    echo '<span class="bold price_item" title="'. $post['xl_price'] .' руб.">' . $post['xl_price'] . '-</span>';
                                }

                            echo '</div>';
                        }                            
                        }

                    ?>

                    <?
                        if(!empty(trim($post['size_is_garnish'])) || !empty(trim($post['price_is_garnish'])) || !empty(trim($post['size_not_garnish'])) || !empty(trim($post['price_not_garnish']))){
                            echo '<div class="garnish-price_container">';
                                echo '<span class="size_item" title="'. $post['size_is_garnish'] .'">' . $post['size_is_garnish'] . '</span>';
                                echo '<span class="bold separator">/</span>';
                                echo '<span class="bold price_item" title="'. $post['price_is_garnish'] .' руб.">' . $post['price_is_garnish'] . '-</span>';
                                echo '<span class="separator_common">|</span>';
                                echo '<span class="size_item" title="'. $post['size_not_garnish'] .'">' . $post['size_not_garnish'] . ' (без гарнира)</span>';
                                echo '<span class="bold separator">/</span>';
                                echo '<span class="bold price_item" title="'. $post['price_not_garnish'] .' руб.">' . $post['price_not_garnish'] . '-</span>';
                            echo '</div>';
                        }                            
                    ?>
                  </div>

                  </div>
                </div>

                <?php
                    if($i%2 == 0){
                        echo '<div class="clearfix"></div>';
                    }
                    $i++;
                ?>
            <? endforeach; ?>

        </div>

      </div>
      
    </div>
    
    <? //echo $i-1; ?>

    <div class="backPage">
        <a href="/menu"><img src="/content/uploads/2020/01/backPage.png" class=""></a>
    </div>
    <div class="clearfix"></div>

  </div>

</section>

<?php get_footer(); ?>