<?php

/* @var $this yii\web\View */
/* @var $fotos_rand_genre \app\models\user\UserMedia[] */
/* @var $rand_genre \app\models\Genre */

use app\components\Url;
use app\components\ImageHelper;

?>

<div class="weddingphotos-header">
    <div class='container'>
        <div class="wrapper">
            <div class="weddingphotos__title title">
                <h2 class="title_blocks">
                    <?= $rand_genre->name; ?>
                    <a href="" id="photo-update" class="weddingphotos__reload"></a>
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="weddingphotos-body">
    <div class="weddingphotos-body-overhide">
        <div class="weddingphotos-items">
            <div class="weddingphotos-items-block">
                <?php $i = 0;
                $i2 = 0;
                foreach ($fotos_rand_genre

                as $frg) {
                $i++; ?>
                <?php
                if (($i2 - 1) < 0) $p_index = 0;
                else $p_index = $fotos_rand_genre[$i2 - 1]['id'];
                if (($i2 + 1) >= count($fotos_rand_genre)) $n_index = 0;
                else $n_index = $fotos_rand_genre[$i2 + 1]['id'];
                ?>
                <?php if ($i == 10){ ?>
            </div>
        </div>
        <div class="weddingphotos-items2">
            <div class="weddingphotos-items-block">
                <?php } ?>
                <?php if ($i == 19){ ?>
            </div>
        </div>
        <div class="weddingphotos-items3">
            <div class="weddingphotos-items-block">
                <?php } ?>
                <a href="<?= Url::to(['site/shortlink', 'type' => 'photo', 'user_type_id' =>  $frg->user_type_id, 'media_id' =>  $frg->id, 'from' => 'main']); ?>"
                   data-href="<?= Url::to(['photo-detail', 'id' => $frg->id, 'p_id' => $p_index, 'n_id' => $n_index, 'from' => 'main']); ?>"
                   class="weddingphotos-item pl photo-slider"
                   data-prev="<?= $p_index?>"
                   data-next="<?= $n_index?>"
                   data-id="<?= $frg->id ?>">
                    <span class="weddingphotos-item-content">
                        <span class="weddingphotos-item-table table">
                            <span class="cell">
                                <span class="weddingphotos-item__title">
                                    <?= $frg->user->name . " " . $frg->user->second_name; ?>
                                    <small><?= $frg->user->city->name; ?>, <?= $frg->user->city->country->name; ?></small></span>
                            </span>
                        </span>
                        <span class="weddingphotos-item-info">
                            <span class="weddingphotos-item-info__item likes"><?= count($frg->likes) ?></span>
                            <span class="weddingphotos-item-info__item comments">0</span>
                        </span>
                    </span>
                    <span class="weddingphotos-item__image ibg  <?php if ($frg->genre_id == 6 && Yii::$app->user->isGuest): ?>blured<?php endif; ?>">
                        <img 
						<?php if(Yii::$app->user->isGuest):?>
						src="<?= ImageHelper::thumbMin($frg, 50) ?>" data-src="<?= ImageHelper::thumb($frg, 370) ?>" class="lazyload blur-up"
						<?php else: ?>
						src="<?= ImageHelper::thumb($frg, 370) ?>"
						<?php endif;?>
						
                             alt="Фотография #<?= $frg->id; ?>, автор: <?= $frg->user->name . " " . $frg->user->second_name; ?>"
							 title="Фотография #<?= $frg->id; ?>, автор: <?= $frg->user->name . " " . $frg->user->second_name; ?>"
                             data-img-path="/images/city_<?= $frg->user->city_id ?>/<?= $frg->user_type_id ?>/<?= $frg->name ?>"
                             data-id="<?= $frg['id']; ?>"
                             data-prev="<?= $p_index ?>"
                             data-next="<?= $n_index ?>"/>
                    </span>
                </a>
                <?php $i2++;
                } ?>
            </div>
        </div>

    </div>
    <div class="weddingphotos-footer">
        <a href="<?= Url::to(["default/all-photos", 'id' => $rand_genre['id']]); ?>" class="weddingphotos__btn btn">Показать все фотографии</a>
    </div>
</div>

<div class="popup popup-slider" id="popupSliderItem"></div>
<script>
    var url_string = window.location.href
    var url = new URL(url_string);
    if (url.search && url.search.length > 5) {
        var id = window.location.pathname.split('_')[1]

        var n_id, p_id = 0

        $('.photo-slider[data-id=' + id + ']').addClass('opened')

        if ($('.photo-slider.opened').next().length > 0) {
            n_id = $('.photo-slider.opened').next().data('id')
        }

        if ($('.photo-slider.opened').prev().length > 0) {
            p_id = $('.photo-slider.opened').prev().data('id')
        }

        $.ajax('/site/photo-detail?id=' + id + '&p_id=' + p_id + '&n_id=' + n_id+'&from=main').done(function (data) {
            $('#popupSliderItem').html(data).show(0).addClass('active');

            window.$link = $('<link/>', {
                rel: 'canonical',
                href: $('#colorphoto').data('canonical')
            }).appendTo('head');

            document.title = $('#colorphoto').data('title')

            if ($('#colorphoto').data('description').length > 0)
                document.querySelector('meta[name="description"]').setAttribute("content", $('#colorphoto').data('description'))

            if ($('#colorphoto').data('keywords').length > 0)
                document.querySelector('meta[name="keywords"]').setAttribute("content", $('#colorphoto').data('keywords'))
        })
    }
</script>
<script>
window.lazySizesConfig = window.lazySizesConfig || {};
window.lazySizesConfig.expand  = 200;
</script>