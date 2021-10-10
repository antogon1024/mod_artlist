<?php

/* @var $this yii\web\View */
/* @var $fotos_rand_genre \app\models\user\UserMedia[] */

use app\components\Url;
use app\components\ImageHelper;

$i = 0;
?>

<?php foreach ($fotos_rand_genre as $frg): ?>
    <?php 
        if(($i-1) < 0) $p_index = 0;
        else $p_index = $fotos_rand_genre[$i - 1]['id'];
        if(($i+1) >= count($fotos_rand_genre)) $n_index = 0;
        else $n_index = $fotos_rand_genre[$i + 1]['id'];

        $arr[] = $frg['id'];
        $gid = ($frg->genre_id == '') ? 0 : $frg->genre_id;
    ?>
    <a href="<?= Url::to(['site/shortlink', 'type' => 'photo', 'user_type_id' =>  $frg->user_type_id, 'media_id' =>  $frg->id, 'from' => 'all']); ?>"
       data-href="<?= Url::to(['photo-detail', 'id' => $frg->id, 'p_id' => $p_index, 'n_id' => $n_index, 'from' => 'all', 'gid' => $gid]); ?>"
       data-prev="<?= $p_index?>"
       data-next="<?= $n_index?>"
       data-id="<?=$frg->id?>"
       class="weddingphotos-item pl photo-slider">
        <span class="weddingphotos-item-content">
            <span class="weddingphotos-item-table table">
                <span class="cell">
                    <span class="weddingphotos-item__title"><?= $frg['un']." ".$frg['sn'];?><small><?= $frg['country_name'];?>, г. <?= $frg['city_name'];?></small></span>
                </span>
            </span>
            <span class="weddingphotos-item-info">
                <span class="weddingphotos-item-info__item likes"><?= $frg['like_media']?></span>
                <span class="weddingphotos-item-info__item comments">0</span>
            </span>
        </span>
        <span class="weddingphotos-item__image ibg <?php if($frg->genre_id == 6 && Yii::$app->user->isGuest):?>blured<?php endif;?>">
            <img src="<?=ImageHelper::thumb($frg, 370)?>"
                 data-img-path="/images/city_<?=$city_name->id?>/<?= $frg['user_type_id']?>/<?=$frg['name']?>"
                 data-id="<?= $frg['id'];?>"
                 data-prev="<?= $p_index?>"
                 data-next="<?= $n_index?>"
                 alt="<?= $frg['name'];?>"/>
              <img src="<?=$frg->user->createAvatar()?>" alt="" style="display:none;">
        </span>
    </a>
<?php $i++; endforeach; ?>


<script>
    $(function () {

        arrf = '<?= @json_encode($arr)?>';
        total = '<?= @$total; ?>'
        gid = '<?=$gid?>'

        var items = $('.weddingphotos-item.pl.photo-slider').length;

        if (items < 50) {
            $('.all-photos__show-more').hide();
        }

        if (total) {
            items = items + Number(50);
            if (total <= items) {
                $('.all-photos__show-more').hide();
            }
        }

        var url_string = window.location.href
        var url = new URL(url_string);
        if (url.search && url.search.length > 5) {
            var id = window.location.pathname.split('_')[1]

            var n_id, p_id = 0

            $('.photo-slider[data-id='+id+']').addClass('opened')

            if ($('.photo-slider.opened').next().length > 0) {
                n_id = $('.photo-slider.opened').next().data('id')
            }

            if ($('.photo-slider.opened').prev().length > 0) {
                p_id = $('.photo-slider.opened').prev().data('id')
            }

            $.ajax('/site/photo-detail?id='+id+'&p_id='+p_id+'&n_id='+n_id+'&from=all&gid=<?=$gid?>').done(function (data) {
                $('#popupSliderItem').html(data).show(0).addClass('active');
                window.$link = $('<link/>', {
                    rel: 'canonical',
                    href: $('#colorphoto').data('canonical')
                }).appendTo('head');

                document.title = $('#colorphoto').data('title')

                if($('#colorphoto').data('description').length > 0)
                    document.querySelector('meta[name="description"]').setAttribute("content", $('#colorphoto').data('description'))

                if($('#colorphoto').data('keywords').length > 0)
                    document.querySelector('meta[name="keywords"]').setAttribute("content", $('#colorphoto').data('keywords'))
            });
        }
    })
</script>