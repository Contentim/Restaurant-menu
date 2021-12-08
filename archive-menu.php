<?php 
/*
Template Name: Меню ресторана
*/
get_header(); ?>

<?
    $taxonomy = 'menu';

    $term_id = get_queried_object()->term_id;

    $terms = get_terms($taxonomy);

    $i = 1;
?>

<!-- /content/themes/moon-prism/archive-menu.php -->
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
    
    <? //echo $i-1; ?>

    <div class="backPage">
        <a href="/menu"><img src="/content/uploads/2020/01/backPage.png" class=""></a>
    </div>
    <div class="clearfix"></div>

  </div>

</section>

<?php get_footer(); ?>