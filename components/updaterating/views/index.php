<?php

/** @var $show bool */
/** @var $message string */
/** @var $slide bool */

?>

    <div class="flash-container" style="display: none" id="rating-flash">
		<div class="blue-message">
        <div class="wrapper">
            <span><span class="msg"></span><span class="msg_razdel">. </span><span class="msg_href"><a href="http://artlist/info/faq#6">Подробнее о рейтинге</a></span>
			</span>
        </div>
        <div class="control-block">
            <a href="" id="flash-close"><b>Закрыть</b></a>
            <label>
                <input type="checkbox" id="remember" data-hide_url=''>
                Больше не показывать
            </label>
        </div>
        <div class="control-block-x">
            <a href="#" id="flash-close2">закрыть</a>
        </div>
		</div>
    </div>
    <script>
		function ratingFlashSet(info) {
			var old_url = $('#remember').data('hide_url');
			console.log("RATING FLASH!","'"+old_url+"'",info);
			// ЧАВ Хак оставляем на экране при ajax
			if (old_url == '<?=\app\components\Url::to(['/site/hide-flash'])?>') return;
			
			$('#rating-flash .msg').html(info.msg);
			if (old_url != info.hide_url) {
				$('#remember').prop('checked',false); 
			}
			$('#rating-flash #remember').data('hide_url',info.hide_url);
			if (info.show) {
				if (!$('#popupSliderItem').hasClass('photo-up')) {
					$('#popupSliderItem').addClass('photo-up');
				}
				
				if (!$('#rating-flash').hasClass('flash-container-over'))
					$('#rating-flash').addClass('flash-container-over');
				
				if (!$('#rating-flash').is(":visible")) {	
					if (info.first_show) {
						$('#rating-flash').slideDown(500);
					} else {
						$('#rating-flash').show(0);
					}
				}
			} else {
				$('#rating-flash').hide();
				$('#popupSliderItem').removeClass('photo-up');
			}
		}
        $(function(){
			ratingFlashSet(<?=json_encode($ratingInfo)?>);
			//$('.flash-container').addClass('flash_opened');
            $('#flash-close').on('click', function(e){				
                e.preventDefault();
                
                $('#rating-flash').slideUp();				
				var url = $('#remember').data('hide_url');
				var post_data = {};
				if ($('#remember').prop('checked')) {
					post_data.hide = true;
				};
				
				if (url) $.post(url,post_data);
				
				if ($('#popupSliderItem').hasClass('photo-up')) 
				{
					setTimeout(function () {
						$('#popupSliderItem').removeClass('photo-up');
					}, 200);
				}
				$('.flash-container').addClass('flash_closed');
				$('.flash-container').removeClass('flash_opened');
				
            })
            $('#flash-close2').on('click', function(e){
				
                e.preventDefault()
				var url = $('#remember').data('hide_url');
                if (url) $.post(url);
				
                $('.flash-container').slideUp()              
            })
        })
    </script>
