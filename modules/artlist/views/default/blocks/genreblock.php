<section class="genreblock">
    <div class="wrapper">
        <div class="genreblock__title  title">
            <h2 class="title_blocks">выбор фотографа по жанру съемки</h2>
        </div>
        <div class="genreblock-body <?= $gorod->url?>">
            <div class="genreblock-slider">
                <?php foreach ($foto_genres as $fg):?>
                    <?php //print_r( $fg) ;?>
                    <div class="genreblock-slide">
                        <a href="<?= \app\components\Url::to(["site/category", 'city_name' => $gorod->url,  'ng' => 'photographers', 'genre_id' => $fg['id']]); ?>" class="genreblock-item">
                            <div class="genreblock-item-content">
                                <div class="cell">
                                    <h3 class="genreblock-item__name genre_titles"><?php echo $fg['name'];?></h3>
                                </div>
                                <span class="cell">
                                <span class="genreblock-item__quantity"><?=$fg['userUnique']?></span>
                            </span>
                            </div>
                            <span class="genreblock-item__inage ibg">
                                <?php
                                if ($fg['userUnique'] > 0) {
                                    if ($fg['id'] == 1)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['портретный фотограф', 'портретных фотографа', 'портретных фотографов'])." ".$gorod->case;
                                    elseif ($fg['id'] == 2)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['фотограф', 'фотографа', 'фотографов'])." для фотосессий в студиях ".$gorod->case;
                                    elseif ($fg['id'] == 3)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['свадебный фотограф', 'свадебных фотографа', 'свадебных фотографов'])." ".$gorod->case;
                                    elseif ($fg['id'] == 4)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['детский фотограф', 'детских фотографа', 'детских фотографов'])." из ".$gorod->case;
                                    elseif ($fg['id'] == 7)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['фотограф', 'фотографа', 'фотографов'])." ".$gorod->case." для фотосессий влюбленных пар";
                                    elseif ($fg['id'] == 10)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['предметный фотохудожник', 'предметных фотохудожника', 'предметных фотохудожников'])." ".$gorod->case;
                                    elseif ($fg['id'] == 11)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['семейный фотограф', 'семейных фотографа', 'семейных фотографов'])." ".$gorod->case;
                                    elseif ($fg['id'] == 17)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['интерьерный фотограф', 'интерьерных фотографа', 'интерьерных фотографов'])." ".$gorod->case;
                                    elseif ($fg['id'] == 18)	$title = $fg['userUnique']." ".\app\components\TextHelper::plural($fg['userUnique'], ['репортажный фотограф', 'репортажных фотографа', 'репортажных фотографов'])." ".$gorod->case;
                                    else $title = $fg['name'];
                                } else $title = $fg['name'];
                                ?>
                                <img alt="<?=$title?>" title="<?=$title?>" src="<?=Yii::$app->params['genre_root'].$fg['img'];?>">
                            </span>
                        </a>
                    </div>

                <?php endforeach;?>
            </div>
        </div>
    </div>
</section>

<script>
    if ($('.genreblock-slider').length > 0) {
        $('.genreblock-slider').slick({
            //autoplay: true,
            infinite: false,
            dots: true,
            arrows: true,
            accessibility: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplaySpeed: 3000,
            //asNavFor:'',
            //appendDots:
            //appendArrows:$('.mainslider-arrows .container'),
            nextArrow: '<button type="button" class="slick-next"></button>',
            prevArrow: '<button type="button" class="slick-prev"></button>',
            responsive: [{
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }]
        });
    }
</script>