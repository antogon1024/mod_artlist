<?php

/** @var $show bool */
/** @var $message string */

?>

<?php if($show):?>
<div class="popup popup_size-m active" id="new-mess-mess">
                <div class="popup-table table">
                    <div class="cell">
                        <div class="popup-content2">
                            <div class="popup-close"></div>
                            <div class="alert alert-success">
                                <p style="text-align:center" >
                                    Поздравляем!<br><br><?=$message?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script>
$('.popup').css("background-color", "rgba(0,0,0,0.95)");
if ($('body').hasClass('isSafari')) {
	bodyScrollLock.disableBodyScroll('.popup');
}

if (!$('body').hasClass('lock')) {
$('body').addClass('lock'); }
</script>
<?php endif;?>