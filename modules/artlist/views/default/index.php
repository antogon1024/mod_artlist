<?php
use app\components\Url;

$this->registerJsFile('/web/artlist/js/maps.js');
$a=2;
?>

<!--mainblock-->
<?php if (Yii::$app->user->isGuest){ ?>
    <div class="mainblock">
        <div class="mainblock-forpc">
            <div class="mainblock-content">
                <div class="wrapper">
                    <script type="application/ld+json">
				{
				  "@context": "https://schema.org",
				  "@type": "BreadcrumbList",
				  "itemListElement": [{
					"@type": "ListItem",
					"position": 1,
					"name": "ARTLIST.PRO",
					"item": "<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/'.$gorod->url;?>"
				  },{
					"@type": "ListItem",
					"position": 2,
					"name": "⭐ Фотографы <?=$gorod->case;?> ⭐"
				  }]
				}
			</script>
                    <h1 class="mainblock-content__title"><a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url, 'ng' => 'photographers']); ?>" style="color:white !important;">Фотографы <?php
                            if (isset($this->params['city_ip']['name_ru'])):
                                echo $this->params['city_ip']['name_ru'];
                            else:
                                echo $gorod->case;
                            endif;
                            ?></a></h1>
                    <div class="mainblock-content__subtitle"><a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url, 'ng' => 'videographers']); ?>" class="mainblock-content__subtitle">видеооператоры</a>, <a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url, 'ng' => 'studios']); ?>" class="mainblock-content__subtitle">фотостудии</a>, <a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url, 'ng' => 'models']); ?>" class="mainblock-content__subtitle">модели</a> и <a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url, 'ng' => 'stylists']); ?>" class="mainblock-content__subtitle">стилисты</a></div>
                    <div class="mainblock-content-body">
                        <div class="mainblock-content-row row">
                            <div class="mainblock-content-column">
                                <div class="mainblock-content-textblock">
                                    <div class="mainblock-content-textblock__title">Ищете профессионала?</div>
                                    <div class="mainblock-content-textblock__text">Вам представлен полный
                                        каталог
                                        лучших фотографов <?php
                                        if (isset($this->params['city_ip']['name_ru'])):
                                            echo $this->params['city_ip']['name_ru'];
                                        else:
                                            echo $gorod->case;
                                        endif;
                                        ?> и других специалистов. Вы сможете ознакомиться с портфолио, просмотреть отзывы, узнать цену за час работы и принять
                                        окончательное решение, поэтому предлагаем перейти к выбору мастера.
                                    </div>
                                </div>
                                <a href="<?= Url::to(["/site/category", 'city_name' => $gorod->url,  'ng' => 'photographers']); ?>" class="mainblock-content__btn">Перейти к выбору</a>
                            </div>
                            <div class="mainblock-content-column">
                                <div class="mainblock-content-textblock">
                                    <div class="mainblock-content-textblock__title">Вы являетесь специалистом?
                                    </div>
                                    <div class="mainblock-content-textblock__text">Значит это предложение для
                                        Вас!
                                        Регистрируйтесь на сайте и присоединяйтесь к нам. Загрузите свои лучшие
                                        фотографии и видео, чтобы продемонстрировать свои навыки и
                                        профессионализм.
                                        Воспользуйтесь всеми преимуществами нашего ресурса.
                                    </div>
                                </div>
                                <a href="" class="mainblock-content__btn pl registration">Зарегистрироваться</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mainblock__bg ibg">
                <?php
                $ran = rand(1, 10);
                echo "<img src='/web/artlist/img/mainblock/".$ran.".jpg' alt='' />";
                ?>
            </div>
        </div>
        <div class="mainblock-formobile">
            <div class="mainblock-formobile__text">Вы являетесь специалистом? Присоединяйтесь!</div>
            <a href="" class="mainblock-formobile__btn pl registration">Зарегистрироваться</a>
        </div>
    </div>
<?php } ?>
<!--end mainblock-->
<div <?php if ($gorod->allgoodreviews > 49):?>itemscope itemtype="http://schema.org/Product"<?php endif; ?>>
    <?php if($active_blocks['popularblock']):?>
        <?php require(Yii::getAlias('@app/modules/artlist/views/default/blocks/popularblock.php')); ?>
    <?php endif; ?>
    <?php if($active_blocks['genreblock']) : ?>
        <?php require(Yii::getAlias('@app/modules/artlist/views/default/blocks/genreblock.php')); ?>
    <?php endif; ?>

</div>




<script type="text/javascript">
    var lin = location.href;
    window.addEventListener('load', function () {
        var url = "<?= Url::to(["adverticement/add"]); ?>";
        // updatePhotoBlock();
        $(document).on('click', '#photo-update', function (e) {
            e.preventDefault();
            var urlBlockMore = "<?= Url::to(["/site/fotoblock-more"]); ?>";
            $.ajax({
                url: urlBlockMore,
                type: 'get',
                success: function (response) {
                    $('#pjaxContent1').empty();
                    $('#pjaxContent1').append(response);
                },
                error: function () {
                    console.log('internal server error');
                }
            });
        });
        $('#adv_block_1').click(function() {
            $.ajax({
                url: url,
                type: 'get',
                data: {pos:1},
                success: function (response) {}
            });
        });
        $('#adv_block_2').click(function() {
            $.ajax({
                url: url,
                type: 'get',
                data: {pos:2},
                success: function (response) {}
            });
        });
        $('#adv_block_3').click(function() {
            $.ajax({
                url: url,
                type: 'get',
                data: {pos:3},
                success: function (response) {}
            });
        });
        $('#adv_block_4').click(function() {
            $.ajax({
                url: url,
                type: 'get',
                data: {pos:4},
                success: function (response) {}
            });
        });
    }, false);
</script>
