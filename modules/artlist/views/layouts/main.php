<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppArtlistAsset;
use app\components\competition\CompetitionFlashWidget;
use app\components\message\MessageWidget;
use app\components\updaterating\UpdateRatingWidget;
//use app\models\City;
//use app\models\Country;
//use app\models\Materials;
//use app\models\Favorites;
//use app\models\user\LoginForm;
//use app\models\user\RegisterUser;
use app\models\user\UserType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

use app\modules\artlist\models\Materials;
use app\modules\artlist\models\user\LoginForm;
use app\modules\artlist\models\user\RegisterUser;
use app\modules\artlist\models\Country;
use app\modules\artlist\models\City;


AppArtlistAsset::register($this);

$cityName = empty($this->params['cities_name']) ? '' : $this->params['cities_name'];
$cityNameRu = empty($this->params['city_names']) ? '' : $this->params['city_names'];
$cityId = empty($this->params['cities_iden']) ? '' : $this->params['cities_iden'];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta name="robots" content="noindex, nofollow"/>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (empty(Yii::$app->session->hasFlash('mob_ver')) || Yii::$app->session->getFlash('mob_ver') == 'des') { ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php } ?>
    <link rel="shortcut icon" href="/web/favicon.ico" type="image/x-icon"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" media="none" onload="if(media!='all')media='all'">-->
    <!--<link rel="stylesheet" href="http://artlist/css/all.css" media="none" onload="if(media!='all')media='all'">-->

    <noscript><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"></noscript>

    <!--<link rel="stylesheet" href="https://static.artlist.pro/css/ion.rangeSlider.min.css" media="none" onload="if(media!='all')media='all'">
    <noscript><link rel="stylesheet" href="https://static.artlist.pro/css/ion.rangeSlider.min.css"></noscript>-->

    <link rel="stylesheet" href="http://artlist/css/ant/ion.rangeSlider.min.css" media="none" onload="if(media!='all')media='all'">
    <noscript><link rel="stylesheet" href="http://artlist/css/ant/ion.rangeSlider.min.css"></noscript>
	<!--<script src="https://static.artlist.pro/js/components/slick.js"></script>asd-->
	<!--<script src="https://static.artlist.pro/js/ion.rangeSlider.min.js"></script>-->
    <script src="http://artlist/js/static/ion.rangeSlider.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Podkova:400,700&display=swap" rel="stylesheet">


</head>

<?php
function isMobile() { 
return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}?>
<?php if (!empty($this->params['main'])) { ?>
<body class="main-page-only<?php if(isMobile()) echo " ios"; else echo " web";?>">
<?php } else { ?>
<body class="other-page<?php if(isMobile()) echo " ios"; else echo " web";?>">
<?php } ?>
<div class="main-wrapper">
    <?php if (Yii::$app->user->isGuest): ?>
    <header>
        <div class="header-top">
            <div class="wrapper">
                <div class="header-table table">
                    <div class="cell">
                        <a href="<?= Url::to(['default/index', 'city_name' => $this->params['city_url']]) ?>"
                           class="header__logo">art<i>list</i> <span>pro</span></a>
                    </div>
                    <div class="cell cityplace">

                        <div class="header-city pl city">
                            <div class="header-city-body">
                                <div class="header-city__title">
                                <?= $this->params['city_name']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="header-city-answer">
                            <p class="header-city-answer__text">Ваш город
                                <span>
                                    <?php //= (isset($this->params['city_ip']['name_ru'])) ? $this->params['city_ip']['name_ru'] : $cityNameRu; ?>?
                                </span>
                            </p>

                            <button class="header-city-answer__button btn btn_full close-city-js">Да</button>
                            <button class="header-city-answer__button btn pl city">Нет</button>
                        </div>
                    </div>
                    <div class="cell scrollmenu full">
                        <div class="header__scrollmenu"></div>
                    </div>
                    <div class="cell">
                        <div class="header-user">
                            <div class="header-user-buttons">
                                <a href="#" class="header-user__btn registration pl">Регистрация</a>
                                <a href="#" class="header-user__btn login pl">Войти</a>
                            </div>
                            <div class="header-user__icon pl login"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="wrapper">
                <div class="header-bottom-table table">
                    <div class="cell full">
                        <div class="header-menu">
                            <?= Menu::widget([
                                'items' => [
                                    ['label' => 'Фотографы', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'photographers']],
                                    ['label' => 'Видеооператоры', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'videographers']],
                                    ['label' => 'Фотостудии', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'studios']],
                                    ['label' => 'Модели', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'models']],
                                    ['label' => 'Стилисты', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'stylists']],
                                ],
                                'options' => [
                                    'class' => 'header-menu-list 1',
                                ],
                                'linkTemplate' => '<a class="header-menu__link" href="{url}">{label}</a>',
                            ]); ?>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="header-search oc">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'search-form',
                                //'enableAjaxValidation'=>true,
                                'method' => 'get',
                                'action' => Url::to(['/search']),
                                'options' => ['class' => 'header-search-form']
                            ]); ?>
                            <input type="text" name="str" data-value="Поиск специалиста" class="header-search__input"
                                   autocomplete="off"/>
                            <button type="submit" class="header-search__btn"></button>
                            <div class="header-search-results">
                                <div class="header-search-results-list">
                                    <!--<span class="header-search-results-item-city ">
                                                <span class="cell">
                                                    <span class="header-search-results-item__cityname">
                                                        <?php
                                    if (isset($this->params['city_ip']['name_ru'])):
                                        echo $this->params['city_ip']['name_ru'];
                                    else:
                                        echo $cityNameRu;
                                    endif;
                                    ?>
                                                    </span>
                                                </span>
                                            </span>-->

                                </div>
                                <!--<a href="" class="header-search-results__all">Все результаты</a>-->
                            </div>
                            <?php ActiveForm::end(); ?>
                            <div class="header-search__cancel">отмена</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="content content_mainpage">

        <?php else: ?>

        <header class="header-auth">
            <div class="header-top">
                <div class="wrapper">
                    <div class="header-table table">
                        <div class="cell">
                            <a href="<?= Url::to(['site/user-city', 'city_name' => $this->params['cities_name']]) ?>"
                               class="header__logo">art<i>list</i> <span>pro</span></a>
                        </div>
                        <div class="cell cityplace">
                            <div class="header-city pl city">
                                <div class="header-city-body">
                                    <div class="header-city__title">
                                        <?php
                                        /*if (isset($this->params['city_ip']['name_ru'])):
                                            echo $this->params['city_ip']['name_ru'];
                                        else:
                                            echo $cityNameRu;
                                        endif;*/
                                        echo $this->params['city_name'];
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="header-city-answer">
                                <p class="header-city-answer__text">Ваш город <span><?php
                                        if (isset($this->params['city_ip']['name_ru'])):
                                            echo $this->params['city_ip']['name_ru'];
                                        else:
                                            echo $cityNameRu;
                                        endif;
                                        ?>?</span></p>

                                <button class="header-city-answer__button btn btn_full close-city-js">Да</button>
                                <button class="header-city-answer__button btn pl city">Нет</button>
                            </div>
                        </div>
                        <div class="cell scrollmenu full">
                            <div class="header__scrollmenu"></div>
                        </div>
                        <div class="cell">
                            <div class="header-authuser">
                                <div class="header-authuser-body table">
                                    <div class="cell">
                                        <div class="header-authuser__name">
                                            <?php $userType = UserType::getUserType(true); ?>
                                            <span>
                                                <?php
                                                $username = ($userType->name) ? $userType->name . ' ' . $userType->second_name : Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->second_name;
                                                if (iconv_strlen($username, 'UTF-8') > 37) {
                                                    $username = iconv_substr($username, 0, 37, 'UTF-8');
                                                    $username .= "...";
                                                }
                                                echo $username; ?>
                                            </span>
                                            <div class="header-authuser__avatar">

                                                <?php if (UserType::getUserType()):
                                                    if (!empty(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)['media'])): ?>
                                                        <img    <?php if(UserType::getUserType()->isGuest()):?>class="circle-avatar" <?php endif?>
                                                                src="<?= \app\models\user\UserType::getUserType()->createAvatar() ?>"
                                                                alt=""/>
                                                        <div class="clear"></div>
                                                    <?php else: ?>
                                                        <img <?php if(UserType::getUserType()->isGuest()):?>class="circle-avatar" <?php endif?>
                                                                src="<?= \app\models\user\UserType::getUserType()->createAvatar() ?>"
                                                                alt=""/>
                                                    <?php endif;endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="cell"></div>-->
                                </div>
                                <div class="header-authuser-menu">
                                    <ul class="header-authuser-menu-list">
                                        <?php if (UserType::getUserType()->isGuest()): ?>
                                            <li>
                                                <a href="<?= Url::to(['/my/message']) ?>"
                                                   class="header-authuser-menu__link click-main-menu">Сообщения
                                                    <p id="view-count-user-mess">
                                                        <?= ($this->params['messages'] > 0) ? '('.$this->params['messages'].')' : ''?>
                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (UserType::getUserType()->isUser()): ?>
                                            <li>
                                                <a href="<?= Url::to(["site/personal-page", 'id' => Usertype::getUserType()->id]); ?>"
                                                   class="header-authuser-menu__link">Моя страница</a>
                                            </li>
                                            <?php if ($userType->realType->isActive()): ?>
                                                <li>
                                                    <a href="<?= Url::to(['/my/pro']) ?>"
                                                       class="header-authuser-menu__link click-main-menu">PRO-аккаунт</a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <a href="<?= Url::to(['/my/message']) ?>"
                                                   class="header-authuser-menu__link click-main-menu">Сообщения
                                                    <p id="view-count-user-mess">
                                                        <?= ($this->params['messages'] > 0) ? '('.$this->params['messages'].')' : ''?>
                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (UserType::getUserType()->isAdmin()): ?>
                                            <li>
                                                <a href="<?= Url::to(['/admin']) ?>"
                                                   class="header-authuser-menu__link click-main-menu">Админка</a>
                                            </li>
                                            <li>
                                                <a href="<?= Url::to(['/my/message']) ?>"
                                                   class="header-authuser-menu__link click-main-menu">Сообщения
                                                    <p id="view-count-user-mess">
                                                        <?= ($this->params['messages'] > 0) ? '('.$this->params['messages'].')' : ''?>
                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (UserType::getUserType()->isUser()): ?>
                                                <li>
                                                    <a href="<?= Url::to(['/my/portfolio']) ?>"
                                                       class="header-authuser-menu__link click-main-menu">Портфолио</a>
                                                </li>
                                                <li>
                                                    <a href="<?= Url::to(['/my/news']) ?>"
                                                       class="header-authuser-menu__link click-main-menu">Новости</a>
                                                </li>
                                                <li>
                                                    <a href="<?= Url::to(['/my']) ?>"
                                                       class="header-authuser-menu__link click-main-menu">Анкета</a>
                                                </li>
                                            <li>
                                                <p class="header-authuser-menu__linkchange">Сменить аккаунт</p>
                                                <div class="header-authuser-submenu">
                                                    <div class="header-authuser-submenu-body">
                                                        <ul class="header-authuser-submenu-list">
                                                            <?php foreach (\app\models\user\UserType::getOtherUserType() as $t): ?>
                                                                <li>
                                                                    <a href="<?= Url::to(['user/set-type', 'id' => $t['id']]) ?>"
                                                                       class="header-authuser-submenu__item">
                                                                        <?= ($t['name']) ? $t['name'] . ' ' . $t['second_name'] : Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->second_name ?>
                                                                        <small><?= $t['type']['name'] ?>, <?= $t['city']['name'] ?></small>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <?php if (count(\app\models\user\UserType::getOtherUserType()) < 5): ?>
                                                            <a href="<?= Url::to(['user/add']) ?>"
                                                               class="header-authuser__add"><span>Создать новый аккаунт</span></a>
                                                        <?php endif; ?>
                                                        <a href="/info/faq/#add_account" class="header-authuser__info">Что это такое?</a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>

                                        <li>
                                            <?= Html::beginForm(['/site/logout'], 'post') ?>
                                            <?= Html::submitButton(
                                                'Выйти',
                                                ['class' => 'header-authuser-menu__link']
                                            ) ?>
                                            <?= Html::endForm() ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="wrapper">
                    <div class="header-bottom-table table">
                        <div class="cell">
                            <div class="header-menu">
                                <?= Menu::widget([
                                    'items' => [
                                        ['label' => 'Фотографы', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'photographers']],
                                        ['label' => 'Видеооператоры', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'videographers']],
                                        ['label' => 'Фотостудии', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'studios']],
                                        ['label' => 'Модели', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'models']],
                                        ['label' => 'Стилисты', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'stylists']],
                                    ],
                                    'options' => [
                                        'class' => 'header-menu-list 2',
                                    ],
                                    'linkTemplate' => '<a class="header-menu__link" href="{url}">{label}</a>',
                                ]); ?>
                            </div>
                        </div>
                        <div class="cell">
                            <div class="header-search oc">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'search-form',
                                    //'enableAjaxValidation'=>true,
                                    'method' => 'get',
                                    'action' => Url::to(['/search']),
                                    'options' => ['class' => 'header-search-form']
                                ]); ?>
                                <input type="text" name="str" data-value="Поиск специалиста"
                                       class="header-search__input" autocomplete="off"/>
                                <button type="submit" class="header-search__btn"></button>
                                <div class="header-search-results">
                                    <div class="header-search-results-list">
                                            <span class="header-search-results-item-city ">
                                                <span class="cell">
                                                    <span class="header-search-results-item__cityname">
                                                        <?php
                                                        if (isset($this->params['city_ip']['name_ru'])):
                                                            echo $this->params['city_ip']['name_ru'];
                                                        else:
                                                            echo $cityNameRu;
                                                        endif;
                                                        ?>
                                                    </span>
                                                </span>
                                            </span>
                                    </div>
                                    <!--<a href="" class="header-search-results__all">Все результаты</a>-->
                                </div>
                                <?php ActiveForm::end(); ?>
                                <div class="header-search__cancel">отмена</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="content content_mainpage">
            <?php endif; ?>
            <?= $content ?>
        </main>
        <footer class="footer">
                <?php /*
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() ?></p>
                */ ?>
                <div class="wrapper">
                    <div class="textmodule-footer" itemscope itemtype="http://schema.org/Organization">
                        <div class="footer-table table">
                            <div class="cell footer-table_main">
                                <a href="http://artlist" class="footer__logo"><img src="/web/artlist/img/main_php/footer-logo.png"
                                                                                        alt=""/></a>
                                <div class="footer__copy">
                                    <p>Все права защищены <span class="hidden" itemprop="name">ARTLIST.PRO</span></p>
									<link itemprop="url" href="http://artlist">
									<meta itemprop="logo" content="http://artlist/img/footer-logo.png">
									<p>E-mail : <a class="simple-link" href="mailto:admin@artlist.pro" itemprop="email">admin@artlist.pro</a></p>
                                    <p>Copyright © <?php echo date("Y"); ?></p>
                                </div>
                            </div>
                            <div class="cell footer-table_menu">
                                <?= Menu::widget([
                                    'items' => [
                                        ['label' => 'Фотографы', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'photographers']],
                                        ['label' => 'Видеооператоры', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'videographers']],
                                        ['label' => 'Фотостудии', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'studios']],
                                        ['label' => 'Модели', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'models']],
                                        ['label' => 'Стилисты', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'stylists']],
                                        ['label' => 'Новые участники', 'url' => ["site/category", 'city_name' => $cityName, 'ng' => 'new']],
                                    ],
                                    'options' => [
                                        'class' => 'footer-menu',
                                    ],
                                    'activeCssClass' => 'active',
                                    'linkTemplate' => '<a class="footer-menu__link" href="{url}">{label}</a>',
                                ]); ?>
                            </div>
                            <div class="cell footer-table_menu">

                                <?= Menu::widget([
                                    'items' => Materials::getLinksMenu(),
                                    'options' => [
                                        'class' => 'footer-menu',
                                    ],
                                    'activeCssClass' => 'active',
                                    'linkTemplate' => '<a class="footer-menu__link" href="{url}">{label}</a>',
                                ]); ?>

                            </div>
                            <div class="cell footer-table_text">
                                <div style="text-align:center;">
                                    <p class="joinus" >Присоединяйтесь к нам!</p>
                                </div>
                                <div class="textmodule-join-items"
                                     style="margin:  0 auto;width: 128px;padding: 10px 0 15px 0;">
									<a href="https://vk.com/artlistpro" target="_blank" class="textmodule-join__item textmodule-join__item_1" itemprop="sameAs"></a>
                                    <a href="https://www.instagram.com/artlist.pro/" target="_blank" class="textmodule-join__item textmodule-join__item_4" itemprop="sameAs"></a>
                                </div>
                                <div class="footer__text">В случае необходимости использования фото и
                                    видеоматериалов
                                    сайта, права на которые принадлежат третьим лицам, Вам необходимо обращаться к
                                    правообладателям этих материалов для получения разрешения. В противном случае
                                    использование любых материалов сайта запрещено
                                </div>
                            </div>
                        </div>
                        <div class="footer-mobile">

                            <ul class="footer-mobile-menu">
                                <li><a href="/news/all" class="footer-menu__link">Блог</a></li>
                                <?php foreach (Materials::getLinks() as $fl): ?>

                                    <li>
                                        <a href="<?= Url::to(["site/information", 'link' => $fl['link']]); ?>"
                                           class="footer-menu__link"><?= $fl['name'] ?></a></li>

                                <?php endforeach; ?>
                                <li><a href="<?= Url::to(['site/all-photos', 'id' => 0]) ?>" class="footer-menu__link">Все
                                        фотографии</a></li>
                            </ul>

                            <div style="text-align:center;">
                                <p class="joinus">Присоединяйтесь к нам!</p>
                            </div>
                            <div class="textmodule-join-items"
                                 style="margin:  0 auto;width: auto;padding: 10px 0 5px 0;">
                                <a href="https://vk.com/artlistpro" target="_blank" class="textmodule-join__item textmodule-join__item_1" itemprop="sameAs"></a>
                                <a href="https://www.instagram.com/artlist.pro/" target="_blank" class="textmodule-join__item textmodule-join__item_4" itemprop="sameAs"></a>
                            </div>
                            <!--
						   <?php if (empty(Yii::$app->session->hasFlash('mob_ver')) || Yii::$app->session->getFlash('mob_ver') == 'des') { ?>
                                <a href="<?= Url::to(['site/vers', 'v' => 'res']) ?>" class="footer-mobile__btn btn">ПОЛНАЯ версия</a>
                            <?php } else { ?>
                                <a href="<?= Url::to(['site/vers', 'v' => 'des']) ?>" class="footer-mobile__btn btn">МОБИЛЬНАЯ версия</a>
                            <?php } ?>
							-->
                        </div>
                    </div>
                </div>
        </footer>
</div>
<?= MessageWidget::widget(); ?>
<?= UpdateRatingWidget::widget(); ?>
<?= CompetitionFlashWidget::widget(); ?>
<div class="popup popup-autorisation">
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup__title">Оставить отзыв</div>
                <div class="popup__subtitle text-left">Чтобы оставить отзыв или проголосовать за любую работу, Вам
                    достаточно авторизоваться на сайте, используя одну из социальных сетей.
                </div>

                    <div class="popup-guest-items table">
                        <?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
                    </div>

                <div class="popup-guest">
                    <div class="popup-guest__text">
                        Для того, чтобы получить доступ ко всем функциям сайта, Вам необходимо
                        пройти регистрацию, либо авторизоваться, если Вы уже зарегистрированы на сайте.
                    </div>
                    <div class="popup-form-line text-center">
                        <p class="popup-form__info" style="color:#acacac;">
                            <a href="#registration" class="pl registration">Регистрация</a>
                            |
                            <a href="#login" class="pl login">Войти</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup popup-nu-autorisation">
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup__title">Авторизация на сайте</div>
                <div class="popup__subtitle text-left">Чтобы получить доступ к фотографиям из альбома "Фотосъемка НЮ",
                    а также возможность оставить отзыв пользователю или проголосовать за любую работу, Вам
                    достаточно авторизоваться на сайте, используя одну из социальных сетей.
                </div>
                <div class="popup-guest-items table">
                    <?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
                </div>
                <div class="popup-guest">
                    <div class="popup-guest__text">
                        Для того, чтобы получить доступ ко всем функциям сайта, Вам необходимо
                        пройти регистрацию, либо авторизоваться, если Вы уже зарегистрированы на сайте.
                    </div>
                    <div class="popup-form-line text-center">
                        <p class="popup-form__info" style="color:#acacac;">
                            <a href="#registration" class="pl registration">Регистрация</a>
                            |
                            <a href="#login" class="pl login">Войти</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup popup-login" >
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup__title">
                        авторизация специалиста
                </div>
                <div class="popup__subtitle">
                    Доступ к Вашему личному кабинету
                </div>
				<?php if (Yii::$app->user->isGuest): ?>
                <?php
                $login = new LoginForm();
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    //'enableAjaxValidation' => true,
                    //'enableClientValidation' => false,
                    //'validateOnChange' => false,
                    //'validateOnSubmit' => true,
                    //'validateOnBlur' => false,
                    'action' => Url::to(['site/login']),
                    'options' => ['class' => 'popup-form']
                ]);  ?>

                <div id="email-auth" class="popup-form-line">

                    <?= $form->field($login, 'username')->textInput(['class' => "req email", 'placeholder' => "E-MAIL"])->label(false) ?>

                </div>
                <div id="pass-auth" class="popup-form-line">
                    <?= $form->field($login, 'password')->passwordInput(['class' => "req", 'placeholder' => "ПАРОЛЬ"])->label(false) ?>
                </div>
				<!--
                <div class="popup-form-line popup-form-line_capcha">
                    <?/*= $form->field($login, 'reCaptchaLogin')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(), [
                        'widgetOptions' => ['class' => ''],

                    ]) */?>
                </div>
				-->
				<!--<input type="hidden" name="recaptcha">
				<div class="g-recaptcha"  style="display:none;"
					  data-sitekey="<?/*=Yii::$app->params['recaptcha2key']*/?>"
					  data-size="invisible"
					  >
				</div>	--><!--asd-->


                <div class="popup-form-line">
                    <div class="model-progress-wrapper">
                        <button type="submit"
						class="popup-form__btn btn btn_full" id="login-form-btn">войти</button>
                        <div class="element">
						<div class="spinner">
							  <div class="bounce1"></div>
							  <div class="bounce2"></div>
							  <div class="bounce3"></div>
							</div>
                        </div>
                    </div>
                </div>
                <div class="popup-form-line">
                    <div class="popup-form__info">
                        <p>Еще не зарегистрированы?<a href="" class="pl registration">Регистрация</a></p>
                        <p><a href="<?= Url::to(['site/recovery']) ?>" class="popup-form__lost">Забыли пароль?</a></p>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
				<?php endif; ?>

                    <div class="popup-guest">
                        <div class="popup__title">гостевой вход</div>
                        <div class="popup-guest__text">Чтобы оставить отзыв или проголосовать за любую работу, Вам
                            достаточно авторизоваться на сайте, используя одну из социальных сетей.
                        </div>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <div class="popup-guest-items table">
                                <?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

            </div>
        </div>
    </div>
</div>
<div class="popup popup-registration">
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup__title">регистрация специалиста</div>
                <div class="popup__text">Доступ ко всем функциям сайта: загрузка фотографий и видео, участие в конкурсах
                    возможность оставлять отзывы, голосовать за работы, архив личных сообщений и многое другое.
                </div>

                <!--				<form data-message="registration" action="#" class="popup-form hasmessage">-->


				<?php if (Yii::$app->user->isGuest): ?>
                <?php
                $register = new RegisterUser();
                $form = ActiveForm::begin([
                    'id' => 'register-form',
                    //'enableAjaxValidation' => true,
                    //'enableClientValidation' => false,
                    //'validateOnChange' => false,
                    //'validateOnSubmit' => true,
                    //'validateOnBlur' => false,
                    'action' => Url::to(['site/send-email']),
                    'method' => 'post',
                    'options' => ['class' => 'popup-form hasmessage', 'data-message' => "registration"]
                ]); ?>

                <div id="email-reg" class="popup-form-line">
                    <?= $form->field($register, 'email')->textInput(['class' => "input email req", 'placeholder' => "E-MAIL"]); ?>
                </div>
				<!--<input type="hidden" name="recaptcha">
				<div class="g-recaptcha" style="display:none;"
					  data-sitekey="<?/*=Yii::$app->params['recaptcha2key']*/?>"
					  data-size="invisible"
					  >
				</div>	-->

                    <!--
                <div class="popup-form-line popup-form-line_capcha">
                    <?/* $form->field($register, 'reCaptcha')->widget(\app\widgets\ReCaptchaUpdate::className(), [                       'widgetOptions' => ['class' => '']
                    ])*/?>
                </div>
				-->
                <div class="popup-form-line">
                    <div class="model-progress-wrapper">

                        <button type="button"  class="popup-form__btn btn btn_full" id="register-form-btn">
                            Зарегистрироваться
                        </button>
                        <div class="element">
                            <div class="spinner">
							  <div class="bounce1"></div>
							  <div class="bounce2"></div>
							  <div class="bounce3"></div>
							</div>
                        </div>
                    </div>

                </div>
                <div class="popup-form-line">
                    <div class="popup-form__info">Уже зарегистрированы? <a href="" class="pl login">Войти</a></div>
                </div>
                <?php ActiveForm::end(); ?>
				<?php endif; ?>
                <div class="popup-guest">
                    <div class="popup__title">гостевой вход</div>
                    <div class="popup-guest__text">Чтобы оставить отзыв или проголосовать за любую работу, Вам
                        достаточно авторизоваться на сайте, используя одну из социальных сетей.
                    </div>
                    <div class="popup-guest-items table">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup popup-city">
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup__title">выбор города</div>
                <form action="#" class="popup-cityform">
                    <div class="popup-citytabs tabs">
                        <div class="popup-city-navigator">
                            <?php
                            $country = Country::getAllCountries();

                            $country_popup = [];
                            $city_popup = [];
                            for ($i = 1; $i < count($country); $i++) {
                                $country_popup[] = $country[$i];
                            }

                            $a1 = 0;
                            foreach ($country_popup as $country):
                                ?>
                                <div class="popup-city-navigator__item tab__navitem <?php if ($a1 == 0) echo "active"; ?>"><?= $country; ?></div>
                                <?php
                                $a1++;
                            endforeach;
                            ?>
                        </div>

                        <?php
                        $a = 0;
                        $a2 = 0;
                        foreach ($country_popup as $key => $idc):
                            $city = City::getAllCitiesPopup($key + 1);

                            if (count($city) != 0):
                                $count = ceil(count($city) / 2);
                                $chunk_array = array_chunk($city, $count, TRUE);

                                ?>
                                <div class="popup-city-body <?=($key + 1)?>">
                                    <div class="popup-city-item tab__item <?php if ($a2 == 0) echo "active"; ?>">

                                        <div class="popup-city-search">
                                            <div class="popup-city-search-input">
                                                <input type="text" name="form[]" placeholder="Поиск города" value=""
                                                       class="popup-city-search__input"/>
                                            </div>
                                            <div class="popup-city-search-results">
                                                <ul class="popup-city-search-results-list">
                                                    <li>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="popup-city-item-body">
                                            <div class="popup-city-item-body-row row">
                                                <div class="popup-city-item-column">
                                                    <ul class="popup-city-item-list">
                                                        <?php
                                                        $translite = new \dastanaron\translit\Translit();
                                                        foreach ($chunk_array[0] as $keys => $city1):
                                                            $cty_name = $translite->translit($city1['name'], true, 'ru-en');
                                                            $cty_name = strtolower($cty_name);
                                                            ?>
                                                            <li>
                                                                <a href="<?=Url::to(['/artlist/default/index', 'city_name' =>  $city1['url']]);?>"
                                                                   class="popup-city-item__value <?php if($city1['bold']):?> bold <?php endif;?>">
                                                                    <?= @$city1['name']; ?>
                                                                </a>
                                                            </li>
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="popup-city-item-column">
                                                    <ul class="popup-city-item-list">
                                                        <?php
                                                        try {
                                                            foreach ($chunk_array[1] as $keys => $city1):
                                                                $cty_name = $translite->translit($city1['name'], true, 'ru-en');
                                                                $cty_name = strtolower($cty_name);
                                                                ?>
                                                                <li>
                                                                    <a href="<?=Url::to(['/artlist/default/index', 'city_name' =>  $city1['url']]);?>"
                                                                       class="popup-city-item__value <?php if($city1['bold']):?> bold <?php endif;?>">
                                                                        <?= @$city1['name']; ?>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            endforeach;
                                                        } catch (Exception $e) {

                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php

                            endif;
                            $a2++;
                        endforeach;
                        ?>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<?php if (Yii::$app->session->hasFlash('registrationFormSubmitted')): ?>
    <div class="popup popup_size-m active">
        <div class="popup-table table">
            <div class="cell">
                <div class="popup-content2">
                    <div class="popup-close"></div>
                    <div class="popup-message__text">На указанный почтовый адрес было отправлено письмо с инструкцией
                        для
                        завершения процедуры регистрации.
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            $(function () {
                setTimeout(function () {
                    $('.popup').removeClass('active').hide(0)
                }, 7000);
            })
        </script>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div class="popup popup_size-m active" id="new-mess-mess">
        <div class="popup-table table">
            <div class="cell">
                <div class="popup-content2">
                    <div class="popup-close"></div>
                    <div class="alert alert-success">
                        <p style="text-align:center">
                            Ваше сообщение отправлено. <br>Мы обязательно прочтем его и свяжемся с Вами.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            setTimeout(function () {
                $('#new-mess-mess').removeClass('active').hide(0)
            }, 7000);
        })
    </script>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('comfirmPass')): ?>
    <div class="popup popup_size-m active" id="new-mess-mess">
        <div class="popup-table table">
            <div class="cell">
                <div class="popup-content2">
                    <div class="popup-close"></div>
                    <div class="alert alert-success">
                        <p style="text-align:center">
                            На Ваш e-mail отправлено письмо со ссылкой для восстановления пароля.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            setTimeout(function () {
                $('#new-mess-mess').removeClass('active').hide(0)
            }, 7000);
        })
    </script>
<?php endif; ?>
<div class="icon-load"></div>

<!-- Окошко для сообщений -->
<div class="popup popup_size-m alert_message" id="alert-message-popup">
    <div class="popup-table table">
        <div class="cell">
            <div class="popup-content2">
                <div class="popup-close"></div>
                <div class="popup-message__text"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function popupAlert(message, showTime) {
        var $popup = $('#alert-message-popup');
        $('.popup-message__text', $popup).html(message);
        $popup.addClass('active');
        if (showTime > 0) {
            setTimeout(function () {
                $popup.removeClass('active');
            }, showTime);
        }
    }
</script>
<!-- // Окошко для сообщений -->


<?php //$this->registerCss('/css/fontawesome.css?v=1.05',['position' => \yii\web\View::POS_END]);?>

<script type="application/javascript">
    var action_active = "<?= $this->context->action->id;?>";

    window.addEventListener('load', function () {
        var url = window.location.href;
        if (url.indexOf('#user-login') != (-1)) {
            $('.login').trigger('click');
        }

    }, false);
	
	
	$(document).ready(function() {
      $('#login-form').keydown(function(e){
        if(e.which == 13) {
			$("#login-form-btn").click();
			e.preventDefault();
			return false;
      }
	});
      $('#register-form').keydown(function(e){
        if(e.which == 13) {
			$("#register-form-btn").click();
			e.preventDefault();
			return false;
      }
	});
	});
</script>
<script type="application/javascript">
    window.addEventListener('load', function () {
        $('.header-search__input').keyup(function () {
            var str = $('.header-search__input').val();
            var city = '<?= $cityId; ?>';
            var urls = '<?= Url::to(["search/search-ajax"]);?>';

            str = str.trim()
            /*$.ajax({
                url: urls,
                type: 'get',
                // dataType: 'json',
                data: {id: city, str: str},
                success: function (response) {
                    $('.header-search-results-list').empty();
                    $('.header-search-results__all').remove();
                    var cr = 0;

                    if (response != '[]') {
                        response = JSON.parse(response);

                        for (var i in response) {
                            if (cr == 7) {
                                break;
                            }
                            $('.header-search-results-list').append('<span class="header-search-results-item-city "> <span class="cell"><span class="header-search-results-item__cityname">' + i + '</span></span></span>');
                            for (var j in response[i]) {
                                var avatar = response[i][j]['ava']

                                cr++;
                                $('.header-search-results-list').append(
                                    '<a href="' + '/id' + response[i][j]['idu'] + '" class="header-search-results-item">' +
                                    '<span class="cell">' +
                                    '<span class="header-search-results-item__avatar">' +
                                    '<img src="' + avatar + '" alt=""/>' +
                                    '</span>' +
                                    '</span>' +
                                    '<span class="cell">' +
                                    '<span class="header-search-results-item__name">' +
                                    response[i][j]['un'] + ' ' + response[i][j]['sn'] +
                                    '<small>' +
                                    response[i][j]['nam'] +
                                    '</small>' +
                                    '</span>' +
                                    '</span>' +
                                    '</a>'
                                );
                            }
                        }

                        if (cr == 7)
                            $('.header-search-results').append('<a href="/search?str=' + str + '" class="header-search-results__all">Все результаты</a>');
                    } else {
                        $('.header-search-results').append('<div class="header-search-results__all">ничего не найдено</div>');
                    }
                },
                error: function () {
                    console.log('internal server error');
                }
            });*/

            $.ajax({
                url: urls,
                type: 'get',
                data: {id: city, str: str},
                success: function (response) {
                    $('.header-search-results-list').empty();
                    $('.header-search-results__all').remove();
                    var cr = 0;

                    if (response != '[]') {
                        $('.header-search-results-list').append(response);

                        cr = $('.header-search-results-list').find('a').length;

                        if (cr == 7)
                            $('.header-search-results').append('<a href="/search?str=' + str + '" class="header-search-results__all">Все результаты</a>');
                    } else {
                        $('.header-search-results').append('<div class="header-search-results__all">ничего не найдено</div>');
                    }
                },
                error: function () {
                    console.log('internal server error');
                }
            });

        })

        $('.popup-city-search__input').keyup(function () {
            var str = $(this).val();
            var urls = '<?= Url::to(["search/search-city-ajax"]);?>';
            var urls2 = '<?= Url::home(true); ?>'
            $.ajax({
                url: urls,
                type: 'get',
                dataType: 'json',
                data: {str: str},
                success: function (response) {
                    $('.popup-city-search-results-list').empty();
                    if (response.length > 0) {
                        for (var i = 0; i < (response.length); i++) {
                            $('.popup-city-search-results-list').append('<a href="#" data-city="' + response[i]['city_name_english'] + '" onclick="lexaChange(this)"><div class="popup-city-search-results__item">' + response[i].name + ', ' + response[i].countryName + '</div></a>');
                        }
                    }
                },
                error: function () {
                    console.log('internal server error 2');
                }
            });

        })
    })
</script>
<script>
    window.addEventListener('load', function () {

        $('.header-search-results__all').click(function () {
            $('.header-search__btn').click()
            return false;
        });
    })

</script>
<?php if (!Yii::$app->user->getIsGuest()): ?>
    <script>
        window.addEventListener('load', function () {
          //  getcountmess()
        })

    </script>
<?php endif; ?>


<?php $this->endBody();?>
<?php //$fav = new Favorites(); ?>

<?php //if(Yii::$app->request->pathInfo != 'fav' /*&& $fav->getCount() != 0*/): ?>
<!--<a class="ant-button" href="/fav">
	ИЗБРАННОЕ: <span id="ant-counter"><?php /*//=$fav->getCount() */?></span>
	<img id="user3" src="/web/artlist/img/main_php/user3.png" width="20" height="20">
</a>-->
<?php //endif; ?>
<!--<div class="cd-cart-container empty">
	<a href="/fav" class="cd-cart-trigger">
		ИЗБРАННОЕ:<img id="user3" src="/img/user3.png" width="20" height="20">
		<ul class="count"> 
			<li>0</li>
			<li>0</li>
		</ul> <
	</a>

	<div class="cd-cart">
		<div class="wrapper" style="display:none">
			<header>
				<h2>Cart</h2>
				<span class="undo">Item removed. <a href="#0">Undo</a></span>
			</header>
			
			<div class="body">
				<ul>
					
				</ul>
			</div>

			<footer>
				<a href="#0" class="checkout btn"><em>Checkout - $<span>0</span></em></a>
			</footer>
		</div>
	</div> 
</div> -->
</body>
</html>
<?php $this->endPage() ?>
