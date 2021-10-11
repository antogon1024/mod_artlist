<?php

/* @var $this yii\web\View */
/* @var $foto_genres \app\models\Genre[] */
/* @var $fotos_rand_genre \app\models\user\UserMedia[] */
/* @var $city_name \app\models\City */
/* @var $cities_name string */
/* @var $city_iden integer */

use app\components\Url;

$this->title = 'Все фотографии | ARTLIST.PRO';
?>

<!-- CONTENT -->
    <section class="section-all-photos">
        <div class="wrapper">
            <div class="all-photos">
                <div class="all-photos__title title">
                    <p>ВСЕ фотографии</p>
                </div>
                <form action="" method="" class="all-photos__form">
                    <label for="all-photos-genre" class="all-photos__label">
                        <select name="all-photos-genre" id="all-photos-genre" class="all-photos__select styled" data-placeholder="выбрать жанр">
                            <option value="0" <?php if(0 === $id) echo "selected='selected'"; ?>>Все фотографии</option>
                            <?php foreach ($foto_genres as $fg) { ?>
                                <option value="<?= $fg->id?>" <?php if($fg->id == $id) echo "selected='selected'"; ?>><?= $fg->name?></option>
                            <?php } ?>
                        </select>
                    </label>
                </form>

                <div class="all-photos__content">
                    <?php echo \Yii::$app->view->render('all-fotos-block.php',[
                        'fotos_rand_genre'          => $fotos_rand_genre,
                        'city_name'                 => $city_name,
                        'gid'                 => $id,
                    ]); ?>
                </div>
                <div></div>
                <?php if(count($fotos_rand_genre) >= 50):?>
                    <div id="loader-circle" class="cssload-container" style="display: none">
                        <div class="cssload-speeding-wheel"></div>
                    </div>
                    <button type="button" class="all-photos__show-more">показать еще</button>
                <?php endif;?>
            </div>
        </div>
    </section>
<?php //echo '<pre>';print_r($arr);exit; ?>
<div class="popup popup-slider" id="popupSliderItem"> </div>
<!-- END CONTENT -->
<script type="application/javascript">

    var url = "<?= Url::to(["/site/all-photos"]); ?>";

    var urlMore = "<?= Url::to(["/artlist/default/all-photos-more", "city"=>$cities_name, "id"=>$id, 'idg' => $id]); ?>";
//alert(urlMore);
    var arrf = "<?= !empty($arr)?json_encode($arr):''?>";

    window.addEventListener('load',function () {
        $('#all-photos-genre').change(function(){
            window.location.href = '/artlist/photo/all/'+$(this).val();
        })

        $('.all-photos__show-more').click(function(){
            $('#loader-circle').show();
            $.ajax({
                url: urlMore,
                data: {arr:arrf},
                type: 'get',
                success: function (response) {
                    //alert(response);
                    $('.all-photos__content').append(response);
                    $('#loader-circle').hide();
                },
                error: function () {
                    $('#loader-circle').hide();
                    console.log('internal server error');
                }
            });
        })
    }, false);
</script>