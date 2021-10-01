<?php

/* @var $userdialogs \app\models\UserDialog[] */
/* @var $mass_in_home boolean */

use app\components\TimeHelper;
use app\components\Url;
use app\models\user\UserType;
?>

<div class="slider-overlay no-close <?php if ($mass_in_home) echo 'active'; ?>" data-container="slider">
    <div class="slider-wrapper" style="max-height:80vh;">

        <?php if(count($userdialogs) > 1):?>
            <div class="new-paggg">
                <!-- Add Arrows -->
                <div class="slider-btn prev swiper-button-prev"></div>
                <!-- Add Pagination -->
                <div class="slider-pagination swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="slider-btn next swiper-button-next"></div>
            </div>
        <?php endif;?>
        <span class="slider-close close-container checkreaded" data-containerid="slider"></span>

        <div class="slider__container swiper-container" style="overflow-y: auto;">
            <ul class="slider-list swiper-wrapper">
                <?php if (!empty($userdialogs)) foreach ($userdialogs as $userdialog): ?>
                 <?php if(!$userdialog->dialog) continue;?>
                    <li class="slider-item swiper-slide" style="overflow-y: auto;max-height: 73vh;">
                        <div class="mess-wrapper">
                        <?php if(!$userdialog->dialog->lastmessage->notice):?>
                            <h3 class="count-titlee">Личное сообщение</h3>
                        <?php endif;?>
                        <?php $vi = 1; foreach ($userdialog->dialog->getLastMessages(3) as $message): if ($vi < 4): ?>
                        <?php if($message->sender == UserType::getUserType()->id) continue;?>
                            <div class="one-post-rew">
                                <div class="slider-item-img-wrapper" style="height:auto;">
                                    <?php if($message->notice):?>
                                        <!--div>
                                            <b>
                                                <p class="name-userr user-unknow"><?= $message->notice->name?></p>
                                            </b>
                                        </div-->
                                    <?php elseif(isset($message->from)):?>
                                        <a target="_blank" href="<?= $message->from->createLink() ?>">
                                            <img src="<?= $message->from->createAvatar() ?>"
                                                 alt="avatar"
                                                <?php if($message->from->isGuest()):?> class="circle-avatar"<?php endif;?>>
                                        </a>
                                        <div>
                                            <a target="_blank" href="<?= $message->from->createLink() ?>">
                                                <p class="name-userr"><?= $message->from->name . ' ' .  $message->from->second_name; ?></p>
                                            </a>
                                            <span class="data-rew"><?= TimeHelper::time_elapsed_string($message->created); ?></span>
                                        </div>
                                    <?php else:?>
                                        <span>
                                            <img src="/images/ava_null.png" alt="avatar">
                                        </span>
                                        <div>
                                            <b>
                                                <p class="name-userr user-unknow"><?= htmlspecialchars($message->guest_name,ENT_NOQUOTES)?> (<?=htmlspecialchars($message->guest_email,ENT_NOQUOTES); ?>)</p>
                                            </b>
                                            <span class="data-rew"><?= TimeHelper::time_elapsed_string($message->created); ?></span>
                                        </div>
                                    <?php endif;?>

                                </div>
                                <p class="slider-item-text">
                                    <?php if($message->notice):?>
                                        <?= $message->text;?>
                                    <?php else:?>
                                        <?= nl2br(\yii\helpers\StringHelper::truncate(htmlspecialchars($message->text,ENT_NOQUOTES),300,'...')); ?>
                                    <?php endif;?>
                                </p>
                            </div>
                        <?php endif; $vi++; endforeach; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <footer class="slider__footer">
            <section class="slider-footer__container">
                <div class="slider-gallery swiper-container gallery-thumbs">
                    <ul class="slider-gallery-list swiper-wrapper">
                        <?php if (!empty($userdialogs)) foreach ($userdialogs as $value) { ?>
                            <li class="slider-gallery-item swiper-slide"></li>
                        <?php } ?>
                    </ul>
                </div>
            </section>
        </footer>
    </div>
</div>
<!-- скрипт внизу, чтобы были стрелочки у ЛС, если их больше 1 -->
<?php if(count($userdialogs) > 1):?>
<script src="https://static.artlist.pro/js/components/swiper.js"></script>
<?php endif;?>
<script>
    $(function(){

        var height = $('.swiper-slide-active').find('.mess-wrapper').height()

        $('.slider-wrapper').animate({height:  (height + 100)+'px'}, 50, 'linear')

        $('.checkreaded').on('click', function(){
            $.ajax({
                url: '/site/check-popup',
                type: 'post',
                success: function(data){
                    getcountmess()
                }
            })
        })
        $('.slider-btn').on('click', function(){

            var height = $('.swiper-slide-active').find('.mess-wrapper').height()

            $('.slider-wrapper').css('height', (height + 100)+'px')
            return true
        })
    })
</script>
