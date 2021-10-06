<?php

/* @var $lable_b_f integer */
/* @var $most_popular_fotographer \app\models\user\UserType[] */
/* @var $adv app\models\Advertisement */

/* @var $city_iden integer */

use app\components\Url;
?>


<?php
function isMob() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}?>
<section class="popularblock">
    <div class="wrapper">
        <?php if ($gorod->allgoodreviews > 49):?> <meta itemprop="image" content="<?php if (!strpos($most_popular_fotographer[0]->createAvatar(), 'artlist')) {echo "http://artlist";} echo $most_popular_fotographer[0]->createAvatar(); ?>"><?php endif;?>
        <div class="popularblock__title title">
            <h2 class="title_blocks"<?php if ($gorod->allgoodreviews > 49):?>  itemprop="name"<?php endif;?>><a style="color:#000 !important;" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/photographers' ?>">Лучшие фотографы
                    <?= (isset($this->params['city_ip']['name_ru'])) ? $this->params['city_ip']['name_ru'] : $gorod->case?></a>

                <?php if ($lable_b_f > 0):?>
                    <i>и др. городов</i>
                <?php endif;?>
            </h2>
        </div>
        <div class="popularblock-body">
            <div class="popularblock-slider"<?php if ($gorod->allgoodreviews > 49):?> itemscope itemtype="http://schema.org/ItemList"<?php endif;?>>
                <?php $to = 1;
                foreach ($most_popular_fotographer as $mpf): ?>
                    <div class="popularblock-slide"<?php if ($gorod->allgoodreviews > 49):?> itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"<?php endif;?>>
                        <?php if ($gorod->allgoodreviews > 49):?> <meta itemprop="position" content="<?=$to;?>"><?php endif;?>
                        <a href="<?= Url::to(["site/personal-page", 'id' => $mpf['id']]); ?>" class="popularblock-item"<?php if ($gorod->allgoodreviews > 49):?>  itemprop="url"<?php endif;?>>
                            <?php if ($mpf->pro_status == 1 && $mpf->realType->isActive()): ?>
                                <span class="popularblock-item__pro">PRO</span>
                            <?php endif;?>
                            <span class="popularblock-item__image">
                            <img <?php if(isMob() && Yii::$app->user->isGuest) { ?>src="<?= $mpf->createAvatarlazy(); ?>" data-src="<?= $mpf->createAvatar(); ?>" class="lazyload blur-up"<?php } else { ?>src="<?= $mpf->createAvatar(); ?>"<?php }?> alt="<?= ($mpf['name']) ? $mpf['name'] . ' ' . $mpf['second_name'] : $mpf['un'] . ' ' . $mpf['sn'] ?>" title="<?= ($mpf['name']) ? $mpf['name'] . ' ' . $mpf['second_name'] : $mpf['un'] . ' ' . $mpf['sn'] ?>"/>
                            </span>
                            <span class="popularblock-item__name <?php if (($to >= $lable_b_f) && ($lable_b_f > 0)):?> popularblock-item__name_grey <?php endif?>">
                                <?= ($mpf['name']) ? $mpf['name'] . ' ' . $mpf['second_name'] : $mpf['un'] . ' ' . $mpf['sn'] ?>
                            </span>
                        </a>
                    </div>
                    <?php $to++; endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php if($active_blocks['adv_1']):?>
    <section class="popular-foto-bl">
        <div class="popularblock-banners row">
            <?php if (strtotime($adv['adv1_end_date']) > strtotime(date('Y-m-d'))): ?>
                <div class="popularblock-banners-column">
                    <a href="<?= $adv['adv1_link'] ?>" target="_blank" class="popularblock-banners__item" id="adv_block_1">
                        <img src="<?= '/img/banners/' . $city_iden . '/' . $adv['adv1'] ?>" alt=""/>
                    </a>
                </div>
            <?php else: ?>
                <div class="popularblock-banners-column">
                    <div class="popularblock-banners__item">
                        <img src="/img/banners/white_block.jpg" alt=""/>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (strtotime($adv['adv2_end_date']) > strtotime(date('Y-m-d'))) : ?>
                <div class="popularblock-banners-column">
                    <a href="<?= $adv['adv2_link'] ?>" target="_blank" class="popularblock-banners__item" id="adv_block_2">
                        <img src="<?= '/img/banners/' . $city_iden . '/' . $adv['adv2'] ?>" alt=""/>
                    </a>
                </div>
            <?php else: ?>
                <div class="popularblock-banners-column">
                    <div class="popularblock-banners__item"><img src="/img/banners/white_block.jpg" alt=""/></div>
                </div>
            <?php endif ?>
        </div>
    </section>
<?php endif;?>
<script>
    if ($('.popularblock-slider').length > 0) {
        $('.popularblock-slider').slick({
            //autoplay: true,
            infinite: false,
            dots: true,
            arrows: true,
            accessibility: false,
            slidesToShow: 8,
            slidesToScroll: 8,
            autoplaySpeed: 3000,
            //asNavFor:'',
            //appendDots:
            //appendArrows:$('.mainslider-arrows .container'),
            nextArrow: '<button type="button" class="slick-next"></button>',
            prevArrow: '<button type="button" class="slick-prev"></button>',
            responsive: [{
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            }, {
                breakpoint: 630,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }]
        });
    }
</script>

