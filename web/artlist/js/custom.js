$(window).on('load resize', function () {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $('body').addClass('ios');
    } else {
        $('body').addClass('web');
    }

    $('body').removeClass('loaded');

    $(document).ready(function (){
        setTimeout(function(){
            $('.slick-track a:first').addClass('first_a');
        }, 1000);
    });
});
function get(name){
    if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
        return decodeURIComponent(name[1]);
}
/* viewport width */
function viewport() {
    var e = window,
        a = 'inner';
    if (!('innerWidth' in window)) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return {width: e[a + 'Width'], height: e[a + 'Height']}
}
function getcountmess(){
    $.ajax({
        url: '/message/get-count-mess',
        type: 'get',
        success: function (response) {
            response = JSON.parse(response);
            $('#view-count-user-mess').text((response['c'] > 0 ? '(' + response['c'] + ')' : ''))
        },
        error: function () {
            console.log('internal server error');
        }
    })
}



function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
/*
function checkRegisterCallBack(params) {
    $('#register-form-btn').removeClass('disabled')
}

function feedCallback(params) {
    $('.admin-feedback__submit').removeClass('disabled')
}

function checkLoginCallBack(params) {
	console.log("response",grecaptcha.getResponse());
    $('#login-form-btn').removeClass('disabled')
	return false;
}

function checkRecoveryCallBack(params) {
    $('#sendbutton').removeClass('disabled')
}

*/

function LoadRecapchaScript() {
    var script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
    script.async = true;
    script.defer = true;
    script.id = 'recapchaloaded';
    var element = $('#recapchaloaded').length;
    if(!element)
        document.body.appendChild(script);
}

/**
 * Returns length of the text inserted to the specified document.
 *
 * @param {module:engine/model/document~Document} document
 * @returns {Number}
 */
function countCharacters( document ) {
    const rootElement = document.getRoot();

    return countCharactersInElement( rootElement );

    // Returns length of the text in specified `node`
    //
    // @param {module:engine/model/node~Node} node
    // @returns {Number}
    function countCharactersInElement( node ) {
        let chars = 0;

        for ( const child of node.getChildren() ) {
            if ( child.is( 'text' ) ) {
                chars += child.data.length;
            } else if ( child.is( 'element' ) ) {
                chars += countCharactersInElement( child );
            }
        }

        return chars;
    }
}
/* viewport width */

$(function () {

    window.addEventListener('keydown', function(e) {

        if(e.key === 'ArrowLeft'){
            if($('.contestmodule-work__img-nav.nav-prev').data('id') && $('.contestmodule-work__img-nav.nav-prev').is(":visible") ){
                $('.contestmodule-work__img-nav.nav-prev').click()

            }
        }
        if(e.key === 'ArrowRight'){
            if($('.contestmodule-work__img-nav.nav-next').data('id') && $('.contestmodule-work__img-nav.nav-next').is(":visible") ){
                $('.contestmodule-work__img-nav.nav-next').click()
            }
        }
    }, true);

    document.real_title = document.title
    document.real_description = (document.querySelector('meta[name="description"]')) ? document.querySelector('meta[name="description"]').getAttribute('content') : ''
    document.real_keywords = (document.querySelector('meta[name="keywords"]')) ? document.querySelector('meta[name="keywords"]').getAttribute('content') : ''

    var is_iPad = navigator.userAgent.match(/iPad/i) != null;

    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        }, BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        }, iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        }, Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        }, Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        }, any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if (isMobile.any()) {
        $('body').addClass('touch');
        $('.header-authuser-menu-list>li>a').click(function () {
            $(this).parent().toggleClass('hover');
            if ($(this).parent().find('.header-authuser-submenu').length > 0) {
                return false;
            }
        });
        $('.tip').click(function (event) {
            $('.tip').toggleClass('active');
        });
        $('.tip_tfp').click(function (event) {
            $('.tip_tfp').toggleClass('active');
        });
    } else {
        $('.header-authuser-menu-list>li').hover(function () {
            $(this).addClass('hover');
        }, function () {
            $(this).removeClass('hover');
        });

        $('.header__scrollmenu,.header-bottom').hover(function () {
            $('.header-bottom,.header__scrollmenu').addClass('hover');
            //    $('.header-bottom').show()
        }, function () {
            var sc2r = $(window).scrollTop();
            $('.header-bottom,.header__scrollmenu').removeClass('hover');
            //    $('.header-bottom').hide()
            if ((!$('.header-bottom').hasClass('open')) && (sc2r > 0)) {
                $('.header-search-results').removeClass('active');
            }
        });
    }

    $(window).scroll(function (event) {
        var sc3r = $(window).scrollTop();
        if (((!$('.header-bottom').hasClass('open')) && (sc3r > 192))) {
            $('.header-search-results').removeClass('active');
        }
    });

    var act = "click";
    if (isMobile.iOS()) {
        var act = "touchstart";
    }

    $('.header-authuser').click(function () {
        $(this).toggleClass('hover');
    });
    //Ð½Ð°Ð¶Ð¸Ð¼Ð°ÐµÐ¼ Ð½Ð° Ð±ÑƒÑ€Ð³ÐµÑ€ - Ð·Ð°ÐºÑ€ÐµÐ¿Ð»ÑÐµÐ¼ Ð¼ÐµÐ½ÑŽ
    $('.header__scrollmenu').click(function () {
        $('.header-bottom').toggleClass('open');
    });

    //Ð½Ð°Ð¶Ð¸Ð¼Ð°ÐµÐ¼ Ð½Ð° Ð±ÑƒÑ€Ð³ÐµÑ€ - Ð·Ð°ÐºÑ€ÐµÐ¿Ð»ÑÐµÐ¼ Ð¼ÐµÐ½ÑŽ Ð´Ð»Ñ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ð°
    $('.header__scrollmenu').click(function () {
        $('.header-bottom').toggleClass('onpress');
    });

    //Ð½Ð°Ð²Ð¾Ð´Ð¸Ð¼ Ð½Ð° Ð±ÑƒÑ€Ð³ÐµÑ€ - Ð¿Ð¾ÑÐ²Ð»ÑÐµÑ‚ÑÑ Ð¼ÐµÐ½ÑŽ, ÑƒÐ±Ð¸Ñ€Ð°ÐµÐ¼ ÐºÑƒÑ€ÑÐ¾Ñ€ Ñ Ð±ÑƒÑ€Ð³ÐµÑ€Ð° Ð¸Ð»Ð¸ Ð¼ÐµÐ½ÑŽ - Ð¼ÐµÐ½ÑŽ Ð¿Ñ€Ð¾Ð¿Ð°Ð´Ð°ÐµÑ‚,
    $('.header__scrollmenu, .header-bottom').hover(function () {
        $('.header-bottom').addClass('onhover');
    }, function () {
        $('.header-bottom').removeClass('onhover');
    });

    //ÐŸÑ€Ð¸ Ð½Ð°Ð¶Ð°Ñ‚Ð¸Ð¸ Ð² Ð¿Ð¾Ð»Ðµ Ð¿Ð¾Ð¸ÑÐºÐ° Ð¼ÐµÐ½ÑŽ Ñ„Ð¸ÐºÑÐ¸Ñ€ÑƒÐµÑ‚ÑÑ, ÐºÐ°Ðº Ð¿Ñ€Ð¸ Ð½Ð°Ð¶Ð°Ñ‚Ð¸Ð¸ Ð½Ð° Ð±ÑƒÑ€Ð³ÐµÑ€ Ð´Ð»Ñ ÐŸÐš
    var w = $(window).outerWidth();
    if (w > 992) {
        $('.header-search__input').click(function (event) {
            $('.header__scrollmenu').addClass('active');
            $('.header-bottom').addClass('onpress');
            $('.header-bottom').addClass('open');
            $('.header-bottom').addClass('onhover');
        });
    }


    $('.header-user__icon').click(function (event) {
        $('.header-user__icon').toggleClass('active');
    });


    var mainblock_exist = $('.mainblock').length;

    var lastScrollTop = 104;
    $(window).scroll(function (event) {
        var scr = $(window).scrollTop();
        var h = $(window).outerHeight();
        var w = $(window).outerWidth();
        //	alert (scr);
        //	alert (lastScrollTop);
        if (w > 992) {
            if (scr > lastScrollTop && !$('.popup.popup-slider.active').length) {
                setTimeout(function(){
                    $('header').addClass('fixed');
                }, 500);
            }
            if (scr > lastScrollTop && lastScrollTop != 104 && !$('.popup.popup-slider.active').length) {
                $('header').addClass('hidehead');
                $('.header-authuser').removeClass('hover');
                $('header').removeClass('fixlock');
            } else {
                $('header').removeClass('hidehead');
            }
            if (scr > 104) {
                if ($('body').hasClass('isSafari') && ($('.popup').hasClass('active'))) {
                    $('header').addClass('fixlock');
                }
                $('header').addClass('fix');
                if ($('body').hasClass('main-page-only') && (mainblock_exist))
                {
                    $('.header-bottom').removeClass('fixed-shadow');
                }
            }
            if (scr < 35) {
                if (!$('header').hasClass('fixlock2')){
                    $('header').removeClass('fix');
                }
                $('header').removeClass('fixed');
                if ($('body').hasClass('main-page-only') && (mainblock_exist))
                {
                    $('.header-bottom').addClass('fixed-shadow');
                }
                if ($('body').hasClass('isSafari') && (!$('header').hasClass('opened-photo'))) {
                    $('header').removeClass('fix');
                }
            }
            if ($('header').hasClass('fixlock2')) {
                if (!$('body').hasClass('isSafari')) {
                    $('header').removeClass('fixlock2');
                }
            }
            if (scr > 104) {
                lastScrollTop = scr;
            }
        }
    });

    $('.tip').webuiPopover({
        placement: 'top-right',
        container: '',
        trigger: 'hover',
        backdrop: false,
        dismissible: true,
        padding: false,
        hideEmpty: true
    });

    $('.tip_tfp').webuiPopover({
        placement: 'auto',
        container: '',
        trigger: 'hover',
        backdrop: false,
        dismissible: true,
        padding: false,
        hideEmpty: true,
        arrow: false
    });

    $('body').on('keyup', '.header-search__input', function (event) {
        var inp = $(this).val().length;
        if (inp > 2) {
            $('.header-search-results').addClass('active');
        } else {
            $('.header-search-results').removeClass('active');
        }
    });

    $(function () {
        $(".header-search__input").keyup(function (e) {
            if (e.keyCode == 27) {
                $(this).val("");
            }
        });
    });

    $('body').on('keyup', '.popup-city-search__input', function (event) {
        var inp = $(this).val().length;
        if (inp > 2) {
            $('.popup-city-search-results').addClass('active');
            $('.popup-city-search-results').getNiceScroll().resize();
        } else {
            $('.popup-city-search-results').removeClass('active');
        }
    });
    $('body').on('click', '.header-search__input', function (event) {
        var inp = $(this).val().length;
        if (inp > 2) {
            $('.header-search-results').addClass('active');
        } else {
            $('.header-search-results').removeClass('active');
        }
    });
    $('.popup-city-search-results__item').click(function (event) {
        $(this).parents('.popup-city-item').find('.popup-city-search__input').val($(this).html());
        $('.popup-city-search-results').removeClass('active');
    });
    $('.header-city__title').click(function(){
        $('.popup-cityform').find('.popup-city-navigator__item').first().click()
    });

    //$('.popup-city-search-results').niceScroll('.popup-city-search-results-list', {
    //    cursorcolor: "#007fb9",
    //    cursorwidth: "3px",
    //    background: "#f3f5f8",
    //    autohidemode: false,
    //    bouncescroll: false,
    //    cursorborderradius: "0px",
    //    cursorborder: "0px solid #fff",
    //});


    $(document).on('click', 'input', function (event) {
        if ($(this).hasClass('save_price_btn')) {
            $('.alert_message').css("display", "block");
            $('.alert_message').addClass('active');
            setTimeout(function () {
                $('.alert_message').css("display", "none");
                $('.alert_message').removeClass('active');
            }, 2000);
        }
        if ($(this).hasClass('save_main_genre_btn')) {
            $('.alert_message').css("display", "block");
            $('.alert_message').addClass('active');
            setTimeout(function () {
                $('.alert_message').css("display", "none");
                $('.alert_message').removeClass('active');
            }, 2000);
        }
    });


    $(document).on('click', '.delete-account', function (event) {
        if (!isMobile.any()) {
            $('header').removeClass('fixlock2');
            $('.header-bottom').addClass('fixed-shadow');
            $('header').removeClass('fix');
        }
        $('.flash-container').removeClass('flash-container-over');

    });
    $(document).on('click', '.delete-photo', function (event) {
        if (!isMobile.any()) {
            $('header').removeClass('fixlock2');
            $('.header-bottom').addClass('fixed-shadow');
            $('header').removeClass('fix');
        }
        $('.flash-container').removeClass('flash-container-over');
    });


    $(document).on('click', '.pl', function (event) {
        $('.popup').removeClass('active').hide();
        // $('#alert-message-popup').show();
        if (!$(this).hasClass('photo-slider')) {
            $('.flash-container').removeClass('flash-container-over');}

        if ($('body').hasClass('isSafari')) {
            bodyScrollLock.disableBodyScroll('.popup');
        }

        if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)) {
            $('.popup').css({"background-color":"black"});
        }

        if ((!$('header').hasClass('fixlock')) && (w > 992)) {
            if (!$(this).hasClass('photo-slider')) {
                $('header').addClass('fixlock');
            }
        }
        $('header').removeClass('fixlock2');

        if (w>992) {
            $(".ant-button").css('right',"87px");
            if (!$('body').hasClass('lock') && isMobile.any()) {
                $('body').addClass('verhidden');
            }
            if (!$('body').hasClass('lock') && !isMobile.any() && ($(document).height() > $(window).height()))
            {
                $('body').addClass('lock');
                if (!$('.main-wrapper').hasClass('fullpage')) {
                    $('.flash-container').addClass('flash-container-fix');
                }
            }
        } else {
            if (!$('body').hasClass('lock')) {
                $('body').addClass('lock');
                $('.flash-container').addClass('flash-container-fix');
            }
        }

        if ($(this).hasClass('registration')) {
            $('.popup-registration').show(0).addClass('active');
            if (REGISTER_RECAPATCHA_ID === null) {
                function regLoop ()
                {
                    LoadRecapchaScript();
                    setTimeout(function ()
                    {
                        if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render ==='undefined')
                        {
                            if ($('#registeruser-email').val().length != 0)
                                $('#register-form-btn').addClass('disabled');
                            regLoop();
                        } else
                        {
                            try {
                                $('#register-form-btn').removeClass('disabled');
                                REGISTER_RECAPATCHA_ID = grecaptcha.render(document.querySelector('.popup-registration .g-recaptcha'), {
                                    'callback' : onRegisterFormSubmit,
                                });
                            }
                            catch(error) {}
                        }
                    }, 200)
                }

                regLoop();
            }
        }
        if ($(this).hasClass('registration_user_guest')) {
            $('.popup-registration').show(0).addClass('active');
        }
        if ($(this).hasClass('autorization_user_guest')) {
            $('.popup-autorisation').show(0).addClass('active');
        }
        if ($(this).hasClass('nu_autorization_user_guest')) {
            $('.popup-nu-autorisation').show(0).addClass('active');
        }
        if ($(this).hasClass('login')) {

            $('.popup-login').find('.popup-content2 > .popup__title').text('Ð°Ð²Ñ‚Ð¾Ñ€Ð¸Ð·Ð°Ñ†Ð¸Ñ ÑÐ¿ÐµÑ†Ð¸Ð°Ð»Ð¸ÑÑ‚Ð°')
            $('.popup-login').find('.popup__subtitle').text('Ð”Ð¾ÑÑ‚ÑƒÐ¿ Ðº Ð’Ð°ÑˆÐµÐ¼Ñƒ Ð»Ð¸Ñ‡Ð½Ð¾Ð¼Ñƒ ÐºÐ°Ð±Ð¸Ð½ÐµÑ‚Ñƒ')
            $('.popup-login').find('.popup-guest').show()
            $('.popup-login').removeClass('popup_size-l').show(0).addClass('active');
            if (LOGIN_RECAPTCHA_ID === null) {
                function loginLoop ()
                {
                    LoadRecapchaScript();
                    setTimeout(function ()
                    {
                        if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render ==='undefined')
                        {
                            if (($('#loginform-username').val().length != 0) && ($('#loginform-password').val().length != 0))
                                $('#login-form-btn').addClass('disabled');
                            loginLoop();
                        } else
                        {
                            try {
                                $('#login-form-btn').removeClass('disabled');
                                LOGIN_RECAPTCHA_ID = grecaptcha.render(document.querySelector('.popup-login .g-recaptcha'), {
                                    'callback' : onLoginFormSubmit,
                                });
                            }
                            catch(error) {}
                        }
                    }, 100)
                }

                loginLoop();
            }



        }
        if ($(this).hasClass('city')) {
            $('.popup-city').show(0).addClass('active');
        }
        if ($(this).hasClass('message-write')) {

            $('.popup-message-write').show(0).addClass('active');

            function messLoop ()
            {
                LoadRecapchaScript();
                setTimeout(function ()
                {
                    if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render ==='undefined')
                    {
                        if (($('#loginform-username').val().length != 0) && ($('#loginform-password').val().length != 0))
                            $('#login-form-btn').addClass('disabled');
                        messLoop();
                    } else
                    {
                        try {
                            if ($('#guest-message-write').length) {
                                if (MESSAGE_WRITE_RECAPTCH_ID === null) {

                                    MESSAGE_WRITE_RECAPTCH_ID = grecaptcha.render(document.querySelector('#guest-message-write .g-recaptcha'), {
                                        'callback' : onMessageWriteFormSubmit,
                                    });
                                }
                            }
                        }
                        catch(error) {}
                    }
                }, 100)
            }

            messLoop();


        }
        if ($(this).hasClass('feedback')) {
            $('.popup-review-write').show(0).addClass('active');
        }
        if ($(this).hasClass('buy-pro')) {
            $.ajax($(this).data('href')).done(function (data) {
                $('.popup-buy-pro').html(data).show(0).addClass('active');
            });
        }
        if ($(this).hasClass('answer-write')) {
            $('.popup-answer-write').find('input[name="parent_id"]').val($(this).data('id'))
            $('.popup-answer-write').show(0).addClass('active');
        }
        if ($(this).hasClass('photo-upload-open')) {
            $('.popup-upload-photo').show(0).addClass('active');
        }
        if ($(this).hasClass('photo-upload-guest')) {
            $('.popup-login').find('.popup-content2 > .popup__title').text('Ð·Ð°Ð³Ñ€ÑƒÐ·ÐºÐ° Ñ„Ð¾Ñ‚Ð¾Ð³Ñ€Ð°Ñ„Ð¸Ð¸ Ð½Ð° ÐºÐ¾Ð½ÐºÑƒÑ€Ñ')
            $('.popup-login').find('.popup__subtitle').text('Ð£Ñ‡Ð°ÑÑ‚Ð¸Ðµ Ð² ÐºÐ¾Ð½ÐºÑƒÑ€ÑÐµ Ð¼Ð¾Ð³ÑƒÑ‚ Ð¿Ñ€Ð¸Ð½Ð¸Ð¼Ð°Ñ‚ÑŒ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ð°Ð²Ñ‚Ð¾Ñ€Ð¸Ð·Ð¾Ð²Ð°Ð½Ð½Ñ‹Ðµ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»Ð¸ Ð¸Ð· Ð³Ñ€ÑƒÐ¿Ð¿Ñ‹ Ð¤Ð¾Ñ‚Ð¾Ð³Ñ€Ð°Ñ„Ñ‹')
            $('.popup-login').find('.popup-guest').hide()
            $('.popup-login').show(0).addClass('active  popup_size-l');
        }
        if ($(this).hasClass('contest-upload-guest')) {
            $('.popup-upload-guest').show(0).addClass('active');
        }
        if ($(this).hasClass('upload-not-possible')) {
            $('.popup-upload-not-possible').show(0).addClass('active');
        }
        if ($(this).hasClass('photo-slider')) {

            if (w<993) {
                $('header').addClass('fixlock2');
                //  $('.header-bottom').hide();
            }
            $('header').addClass('opened-photo');

            $(this).addClass('opened').find('img').attr('id');
//alert( $(this).data('href') );
            $.ajax($(this).data('href')).done(function (data) {
                //alert(data);
                $('#popupSliderItem').html(data).show(0).addClass('active');

                if($('#popupSliderItem').hasClass('photo-up')) {
                    $('.flash-container').addClass('flash-container-over');
                }


                $('header').removeClass('hidehead')
                if (w < 993) {
                    $('header').addClass('fixlock');
                } else {
                    $('header').addClass('fixlock2');
                }
                window.$link = $('<link/>', {
                    rel: 'canonical',
                    href: $('#colorphoto').data('canonical')
                }).appendTo('head');

                document.title = $('#colorphoto').data('title')

                //if($('#colorphoto').data('description').length > 0)
                    //document.querySelector('meta[name="description"]').setAttribute("content", $('#colorphoto').data('description'));

                //if($('#colorphoto').data('keywords').length > 0)
                    //document.querySelector('meta[name="keywords"]').setAttribute("content", $('#colorphoto').data('keywords'));
            });

            if ($(this).find('img').length) {
                var imgPath = $(this).find('img').data('img-path');
                $('.popup-slider .contestmodule-work__img').attr('src', imgPath);
            } else {
                var vidoePath = $(this).find('iframe').attr('src');
                $('.popup-slider .contestmodule-work__video').attr('src', vidoePath);
            }
        }
        return false;
    });

    // popup-slider nav next
    $(document).on('click', '.popup .contestmodule-work__img-nav.nav-next', function (e) {

        var arrow = $(this)

        if ($('.contestmodule-work__img').length) {
            if ($('.photo-slider.opened').data('next') > 0 || arrow.data('id') > 0) {

                if($('.photo-slider.opened').next().length){
                    var n_id = $('.photo-slider.opened').data('next')
                    var href = $('.photo-slider.opened').next().data('href')
                }
                else {
                    var n_id = arrow.data('id')
                    var href = '/site/photo-detail?id='
                        +arrow.data('id')
                        +'&p_id='+arrow.data('prev')
                        +'&n_id='+arrow.data('next')
                        +'&from='+arrow.data('from')
                        +'&genre_id='+arrow.data('genre')
                        +'&city_id='+arrow.data('city')
                        +'&str='+arrow.data('str');
                }

                $('.photo-slider.opened').removeClass('opened')

                $('.photo-slider[data-id="'+n_id+'"]').addClass('opened')

                var p = $('.photo-slider.opened').find('img').data('img-path');

                var nextId = $('.photo-slider.opened').find('img').data('next')
                var prevId = $('.photo-slider.opened').find('img').data('prev')

                $('.contestmodule-work__img-nav.nav-next').attr('data-id', nextId)
                $('.contestmodule-work__img-nav.nav-prev').attr('data-id', prevId)

                //  $('.contestmodule-work__img').attr('src', p);


                $.ajax(href).done(function (data) {
                    $('#popupSliderItem').html(data);

                    localStorage.setItem('fullpage', $('.main-wrapper').hasClass('fullpage'))

                    if(window.$link)
                        window.$link.remove()

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
            } else {
                return false;
            }
        } else if ($('.contestmodule-work__video').length) {
            if ($('.photo-slider.opened').data('next') > 0 || arrow.data('id') > 0) {

                if($('.photo-slider.opened').next().length){
                    var n_id = $('.photo-slider.opened').data('next')
                    var href = $('.photo-slider.opened').next().data('href')
                }
                else {
                    var n_id = arrow.data('id')
                    var href = '/site/photo-detail?id='
                        +arrow.data('id')
                        +'&p_id='+arrow.data('prev')
                        +'&n_id='+arrow.data('next')
                        +'&from='+arrow.data('from')
                        +'&genre_id='+arrow.data('genre')
                        +'&city_id='+arrow.data('city')
                        +'&str='+arrow.data('str');
                }

                $('.photo-slider.opened').removeClass('opened')

                $('.photo-slider[data-id="'+n_id+'"]').addClass('opened')

                var p = $('.photo-slider.opened').find('img').data('img-path');

                var nextId = $('.photo-slider.opened').find('img').data('next')
                var prevId = $('.photo-slider.opened').find('img').data('prev')

                $('.contestmodule-work__img-nav.nav-next').attr('data-id', nextId)
                $('.contestmodule-work__img-nav.nav-prev').attr('data-id', prevId)

                //  $('.contestmodule-work__img').attr('src', p);


                $.ajax(href).done(function (data) {
                    $('#popupSliderItem').html(data);

                    localStorage.setItem('fullpage', $('.main-wrapper').hasClass('fullpage'))

                    if(window.$link)
                        window.$link.remove()

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
            } else {
                return false;
            }
        } else {
            return false;
        }

        // nav visibility
        var firstItem = $('.photo-slider.opened').parent().children().first(),
            lastItem = $('.photo-slider.opened').parent().children().last();

        if (firstItem.is('.opened')) {
            $('.nav-prev').hide();
        } else if (lastItem.is('.opened')) {
            $('.nav-next').hide();
        } else {
            $('.nav-prev').show();
            $('.nav-next').show();
        }
    });

    // popup-slider nav prev
    $(document).on('click', '.popup .contestmodule-work__img-nav.nav-prev', function (e) {

        var arrow = $(this)

        if ($('.contestmodule-work__img').length) {
            if ($('.photo-slider.opened').data('prev') > 0 || arrow.data('id') > 0) {

                if($('.photo-slider.opened').prev().length){
                    var p_id = $('.photo-slider.opened').data('prev')
                    var href = $('.photo-slider.opened').prev().data('href')
                }
                else {
                    var p_id = arrow.data('id')
                    var href = '/site/photo-detail?id='
                        +arrow.data('id')
                        +'&p_id='+arrow.data('prev')
                        +'&n_id='+arrow.data('next')
                        +'&from='+arrow.data('from')
                        +'&genre_id='+arrow.data('genre')
                        +'&city_id='+arrow.data('city')
                        +'&str='+arrow.data('str');
                }

                $('.photo-slider.opened').removeClass('opened')

                $('.photo-slider[data-id="'+p_id+'"]').addClass('opened')

                var p = $('.photo-slider.opened').find('img').data('img-path');

                var nextId = $('.photo-slider.opened').find('img').data('next')
                var prevId = $('.photo-slider.opened').find('img').data('prev')

                $('.contestmodule-work__img-nav.nav-next').attr('data-id', nextId)
                $('.contestmodule-work__img-nav.nav-prev').attr('data-id', prevId)

                //  $('.contestmodule-work__img').attr('src', p);

                $.ajax(href).done(function (data) {
                    $('#popupSliderItem').html(data);

                    localStorage.setItem('fullpage', $('.main-wrapper').hasClass('fullpage'))

                    if(window.$link)
                        window.$link.remove()

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

            } else {
                return false;
            }
        } else if ($('.contestmodule-work__video').length) {
            if ($('.photo-slider.opened').data('prev') > 0 || arrow.data('id') > 0) {

                if($('.photo-slider.opened').prev().length){
                    var p_id = $('.photo-slider.opened').data('prev')
                    var href = $('.photo-slider.opened').prev().data('href')
                }
                else {
                    var p_id = arrow.data('id')
                    var href = '/site/photo-detail?id='
                        +arrow.data('id')
                        +'&p_id='+arrow.data('prev')
                        +'&n_id='+arrow.data('next')
                        +'&from='+arrow.data('from')
                        +'&genre_id='+arrow.data('genre')
                        +'&city_id='+arrow.data('city')
                        +'&str='+arrow.data('str');
                }

                $('.photo-slider.opened').removeClass('opened')

                $('.photo-slider[data-id="'+p_id+'"]').addClass('opened')

                var p = $('.photo-slider.opened').find('img').data('img-path');

                var nextId = $('.photo-slider.opened').find('img').data('next')
                var prevId = $('.photo-slider.opened').find('img').data('prev')

                $('.contestmodule-work__img-nav.nav-next').attr('data-id', nextId)
                $('.contestmodule-work__img-nav.nav-prev').attr('data-id', prevId)

                //  $('.contestmodule-work__img').attr('src', p);

                $.ajax(href).done(function (data) {
                    $('#popupSliderItem').html(data);

                    localStorage.setItem('fullpage', $('.main-wrapper').hasClass('fullpage'))

                    if(window.$link)
                        window.$link.remove()

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

            } else {
                return false;
            }
        } else {
            return false;
        }

        // nav visibility
        var firstItem = $('.photo-slider.opened').parent().children().first(),
            lastItem = $('.photo-slider.opened').parent().children().last();

        if (firstItem.is('.opened')) {
            $('.nav-prev').hide();
        } else if (lastItem.is('.opened')) {
            $('.nav-next').hide();
        } else {
            $('.nav-prev').show();
            $('.nav-next').show();
        }
    });

    // popup-slider full screen
    $(document).on('click', '.contestmodule-work__img-fullscreen', function (e) {
        e.preventDefault();
        $('.popup-slider .popup-content .box-comment').toggle();
        $('.popup-slider .popup-content .box-photo').toggleClass('open-fullscreen');
        toggleFullScreen();
    });

    function toggleFullScreen() {
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    $(document).on('click', '.popup-close', function (event) {
        event.preventDefault()
        popupclose()
        return false
    });
    $(document).on('click', '.popup-close-too', function (event) {
        event.preventDefault();
        popupclose()
    });
    function popupclose() {
        $(document).off("touchstart");
        $(document).off("touchmove");
        $(document).off("touchend");
        if(window.$link)
            window.$link.remove()

        if(document.real_title)
            document.title = document.real_title

        if(document.real_description.length > 0)
            document.querySelector('meta[name="description"]').setAttribute("content", document.real_description)

        if(document.real_keywords.length > 0)
            document.querySelector('meta[name="keywords"]').setAttribute("content", document.real_keywords)

        var isNu = false;

        $(".ant-button").css('right',"70px");

        $('.header-bottom').show();

        var w = $(window).outerWidth();

        if((w < 993) && $('.photo-comments-delete'))
            $('.photo-comments-delete').remove();

        if (!isMobile.any()) {
            if ($('.popup.active').hasClass('popup-del-akk')) {
                $('.header-bottom').removeClass('fixed-shadow');
                $('header').addClass('fix');
            }}

        if( $('.popup.active').hasClass('popup-nu-autorisation') ||
            $('.popup.active').hasClass('popup-autorisation') ||
            $('.popup.active').hasClass('popup-registration') ||
            $('.popup.active').hasClass('popup-login')       ||
            $('.popup.active').hasClass('popup-city'))
        {
            isNu = true
            var from = get('from')
            var url_string = window.location.href
            var url = new URL(url_string);
            var id = window.location.pathname.split('_')[1];

        }


        if ($(this).hasClass('photo-slider')) {
            if($('.flash-container').css('display') != 'none') {
                $('.flash-container').addClass('flash-container-over');
                $('#popupSliderItem').addClass('photo-up');

                console.log("ADD photo-up in popup close!");
            }
        }

        $('.popup').removeClass('active').hide(0);
        // $('#alert-message-popup').show();

        if (!isMobile.any()) {
            //     $('header').removeClass('fixlock');
        }
        var w = $(window).outerWidth();

        if (($('header').hasClass('fix')) && (!isMobile.any()) || (w > 767)) {
            $('header').addClass('fixlock2');
        }

        if (w > 992) {
            if (!isMobile.any()) {
                $('header').removeClass('fixlock2');
            }
            $('header').addClass('fixed');
        }

        //if (w < 993){
        if ($('header').hasClass('fixlock') || $('header').hasClass('mobilefix') || $('header').hasClass('opened-photo')) {
            $('header').addClass('fixlock2');
        }
        //	}


        $('body').removeClass('lock');
        $('body').removeClass('verhidden');
        $('.flash-container').removeClass('flash-container-fix');

        $('.header-user__icon').removeClass('active');

        if (document.exitFullscreen) {
            //  document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }

        $('.popup-slider .popup-content .box-comment').show('fast');

        $('.popup-slider .popup-content .box-photo').removeClass('open-fullscreen');

        $('.contestmodule-work__img-nav').show('fast');

        $('.photo-slider').removeClass('opened');

        $('.popup-content .box-photo').html('')
        $('#colorphoto').html('')
        if (isNu && id) {

            var n_id = 0
            var p_id = 0
            $('.photo-slider[data-id='+id+']').addClass('opened')

            if ($('.photo-slider.opened').next().length > 0) {
                n_id = $('.photo-slider.opened').next().data('id')
            }

            if ($('.photo-slider.opened').prev().length > 0) {
                p_id = $('.photo-slider.opened').prev().data('id')
            }

            if (!from) {
                $.ajax('/site/photo-detail?id=' + id+'&p_id='+p_id+'&n_id='+n_id).done(function (data) {
                    //  window.scrollTo(0, 0);
                    //   $('.header-bottom').hide()
                    $('#popupSliderItem').html(data).show(0).addClass('active');
                    $('.flash-container').addClass('flash-container-over');
                    $('body').toggleClass('lock');
                    if (!$('.main-wrapper').hasClass('fullpage')) {
                        $('.flash-container').toggleClass('flash-container-fix');
                    }
                });
            } else {
                $.ajax('/site/photo-detail?id=' + id+'&p_id='+p_id+'&n_id='+n_id+'&from='+from).done(function (data) {
                    //  window.scrollTo(0, 0);
                    //   $('.header-bottom').hide()
                    $('#popupSliderItem').html(data).show(0).addClass('active');
                    $('.flash-container').addClass('flash-container-over');
                    $('body').toggleClass('lock');
                    if (!$('.main-wrapper').hasClass('fullpage')) {
                        $('.flash-container').toggleClass('flash-container-fix');
                    }
                });
            }

        } else {
            var countScroll = $(window).scrollTop();
            $('header').removeClass('opened-photo');
            if ($('body').hasClass('isSafari') && countScroll<15) {
                $('header').removeClass('fix');
            }
            if ($('body').hasClass('isSafari')) {
                $('header').removeClass('fixlock2');
            }
            var newurl = $('.popup-close').data('return')

            if( $('.popup-close').parents('.popup').hasClass('fullpage-detail')){
                window.location = newurl
            }
            else{
                window.history.pushState({path: newurl}, '', newurl);
            }
        }

        if ((w > 992) && ($('body').hasClass('isSafari')) && ($('header').hasClass('opened-photo'))) {
            $('header').addClass('fix');
        }

        if ((w < 993) && ($('header').hasClass('fixlock2')) && (!$('header').hasClass('opened-photo'))) {
            $('header').removeClass('fixlock');
            $('header').removeClass('fixlock2');
            $('header').removeClass('fix');
        }
        if ($('body').hasClass('isSafari') && (!$('.popup').hasClass('active')) && (!$('header').hasClass('opened-photo'))) {
            bodyScrollLock.enableBodyScroll('.popup');
        }
    }

    $('.popup-button-close').on('click', function (e) {
        popupclose();
    })

    $('.popupclose').on('click', function (e) {
        popupclose();
    })

    $('body').on('click', '.popup', function (e) {
        if (!$(e.target).is(".popup>.popup-table>.cell *") || $(e.target).is(".popup-close")) {
            popupclose();
            return false;
        }
    });

    $(this).keydown(function (eventObject) {
        if ((eventObject.which == 27) && ($('header').hasClass('fixlock'))) {
            popupclose();
        }
    });

    $('.header__scrollmenu').click(function (event) {
        $('.header__scrollmenu').toggleClass('active');
    });
    $('.header-search-form').click(function (event) {
        var w = $(window).outerWidth();
        if (w > 480 && w < 992) {
            $('.header-search').addClass('active');
            $('.header-menu').addClass('hidehead');
        }
    });
    $('.header-search__cancel').click(function (event) {
        $('.header-search').removeClass('active');
        $('.header-search-results').removeClass('active');
        $('.header-menu').removeClass('hidehead');
    });

    $.each($('.ibg'), function (index, val) {
        $(this).css('background-image', 'url("' + $(this).find('img').attr('src') + '")');
    });

    //ÐšÐ»Ð¸Ðº Ð²Ð½Ðµ Ð¾Ð±Ð»Ð°ÑÑ‚Ð¸
    $(document).on('click touchstart', function (e) {
        var w = $(window).outerWidth();
        if (w > 992) {
            if ((!$(e.target).is(".header-search *") && !$(e.target).is(".header-search")) || $(e.target).is(".header-search__cancel")) {
                $('.oc').removeClass('active');
            }
            if ((!$(e.target).is(".header-search *") && !$(e.target).is(".header-search"))) {
                $('.header-search-results').removeClass('active');
            }
        }

        if ((!$(e.target).is(".popup-city-search *") && !$(e.target).is(".popup-city-search"))) {
            $('.popup-city-search-results').removeClass('active');
        }

        if (w < 992) {
            if (($(e.target).is('.header-authuser') || $(e.target).is('.header__scrollmenu')) && $('.header-authuser').hasClass('hover')) {
                $('.header-authuser').removeClass('hover');
                $('.header-authuser-menu-list>li').removeClass('hover');
            }
        }
        if (w > 992) {
            if ($('.header-authuser').hasClass('hover') && (!$(e.target).is('.header-authuser *'))) {
                $('.header-authuser').removeClass('hover');
                $('.header-authuser-menu-list>li').removeClass('hover');
            }
        }

        if (w > 992) {
            if ((!$(e.target).is('.header-bottom *') && !$(e.target).is('.header__scrollmenu') && !$('.header-bottom').hasClass('onhover') && !$('.oc').hasClass('active'))) {
                $('.header__scrollmenu').removeClass('active');
                $('.header-bottom').removeClass('onpress');
                $('.header-bottom').removeClass('open');
            }
        }

        if ((!$(e.target).is(".tip"))) {
            $('.tip').removeClass('active');
        }
        if ((!$(e.target).is(".tip_tfp"))) {
            $('.tip_tfp').removeClass('active');
        }

        if (isMobile.any()) {
            if ($(e.target).is('.header__scrollmenu'))
                $('.header-bottom').toggleClass('onhover');

            if ($(e.target).is('.header-authuser') || $(e.target).is('.header-authuser *') && ($('.header-bottom').hasClass('onhover') || $('.header-bottom').hasClass('onpress'))) {
                $('.header__scrollmenu').removeClass('active');
                $('.header-bottom').removeClass('onpress');
                $('.header-bottom').removeClass('onhover');
                $('.header-bottom').removeClass('open');
            }
        }

        if (w > 766 && w < 992) {
            if (($(e.target).is('.header__scrollmenu') && $('.header-search-results').hasClass('active'))) {
                $('.oc').removeClass('active');
                $('.header-search-results').removeClass('active');
            }
            if ((!$(e.target).is('.header-bottom *') && !$(e.target).is('.header__scrollmenu') && $('.header-search-results').hasClass('active'))) {
                $('.header-bottom').toggleClass('onhover');
            }
            if (($(e.target).is('.header-authuser *') || $(e.target).is('.header-authuser') && $('.header-search-results').hasClass('active'))) {
                $('.header__scrollmenu').removeClass('active');
                $('.header-bottom').removeClass('onpress');
                $('.header-bottom').removeClass('onhover');
                $('.header-bottom').removeClass('open');
                $('.oc').removeClass('active');
                $('.header-search-results').removeClass('active');
            }
        }
    });

    $('.tab__navitem').click(function (event) {
        var eq = $(this).index();
        if ($(this).hasClass('parent')) {
            var eq = $(this).parent().index();
        }
        if (!$(this).hasClass('active')) {
            $(this).closest('.tabs').find('.tab__navitem').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.tabs').find('.tab__item').removeClass('active').eq(eq).addClass('active');
            if ($(this).closest('.tabs').find('.slick-slider').length > 0) {
                $(this).closest('.tabs').find('.slick-slider').slick('setPosition');
            }
        }
    });

    $.each($('.spoller.active'), function (index, val) {
        $(this).next().show();
    });
    $('.spoller').click(function (event) {
        if ($(this).hasClass('mob') && !isMobile.any()) {
            return false;
        }
        if ($(this).hasClass('closeall') && !$(this).hasClass('active')) {
            $.each($(this).closest('.spollers').find('.spoller'), function (index, val) {
                $(this).removeClass('active');
                $(this).next().slideUp(300);
            });
        }
        $(this).toggleClass('active').next().slideToggle(300, function (index, val) {
            if ($(this).parent().find('.slick-slider').length > 0) {
                $(this).parent().find('.slick-slider').slick('setPosition');
            }
        });
    });

    //Adaptive functions
    $(window).resize(function (event) {
        adaptive_function();
    });

    function adaptive_header() {
        var w = $(window).outerWidth();
        var headerCity = $('.header-city');
        if (w < 768) {
            if ($('.cityplace>.header-city').length > 0) {
                headerCity.prependTo('.header-bottom>.wrapper');
            }
        } else {
            if ($('.cityplace>.header-city').length == 0) {
                headerCity.appendTo('.cityplace');
            }
        }
    }

    function adaptive_function() {
        adaptive_header();
    }

    adaptive_function();

    //ZOOM
    //if ($('.zoom').length > 0) {
    //    $('.zoom').fancybox({
    //        helpers: {
    //            overlay: {locked: false},
    //            title: {type: 'inside'}
    //        }
    //    });
    //}

    function preloadImages() {
        for (var i = 0; i < arguments.length; i++) {
            new Image().src = arguments[i];
        }
    }

    preloadImages(
        "/img/icons/social_h.png",
        "/img/icons/bg_pro.png",
        "/img/icons/s-arrow_a.png",
        "/img/icons/s-arrow_h.png",
        "/img/icons/info_h.png",
        "/img/icons/addaccount_active.png",
        "/img/icons/arrow_active.png",
        "/img/icons/arrow_down_blue.png",
        "/img/icons/arrow.png",
        "/img/icons/arrow_left_blue.png",
        "/img/icons/arrow_left_white.png"
    );

    function paddingTop() {
        var hh = $('header').height();
        $('.content_mainpage').css('padding-top', hh + 'px');
    }

    paddingTop();

    $(window).resize(function () {
        paddingTop();
    });

    $('.toggle-content').on('click', function () {
        $('.profile-portfolio-content').toggleClass('hidden');
    });

    $('.profile-portfolio__breadcrumbs-item').on('click', function (e) {
        //  e.preventDefault();
    });

    /* FAQ list items */
    var textWrap = $('.faq-module__text-wrap'),
        currentItemTitle = $('.faq-module__elem-title.active'),
        currentTextWrap = currentItemTitle.next();

    //  textWrap.slideUp(0);
    // currentTextWrap.slideDown(0);

    $('.faq-module__elem-title').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').next().slideUp(350);
        } else {
            $(this).addClass('active').next().slideDown(350);
        }
    });

    /* info list items */
    var textWrap = $('.info-module__text-wrap'),
        currentItemTitle = $('.info-module__elem-title.active'),
        currentTextWrap = currentItemTitle.next();

    //  textWrap.slideUp(0);
    // currentTextWrap.slideDown(0);

    $('.info-module__elem-title').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').next().slideUp(350);
        } else {
            $(this).addClass('active').next().slideDown(350);
        }
    });


    (function($) {
    $(function() {

        if ($('.styled').length) {
            $('.styled').styler();
        }

    });

    })(jQuery);

    //Step Form
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    function fsContentHeight() {
        $('.sf-content-wrap').height($('.registration-module__fieldset-step.fs-active').height());
    }

    $(".submit").on('click', function () {
        setTimeout(function () {
            fsContentHeight();
        }, 250);
    });

    $('.registration-module__input.input').on('focus input blur', function () {
        setTimeout(function () {
            fsContentHeight();
        }, 250);
    })

    /* In Top Button */
    //Ð¿Ð¾Ð»Ð¾Ð¶ÐµÐ½Ð¸Ðµ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ñ‹
    var BottomPosition = 0;
    //Ñ„Ð»Ð°Ð³ Ð´Ð»Ñ Ð¾Ñ‚Ð¾Ð±Ñ€Ð°Ð¶ÐµÐ½Ð¸Ñ ÐºÐ½Ð¾Ð¿ÐºÐ¸ "ÐÐ°Ð·Ð°Ð´"
    var BottomFlag = false;
    //Ñ„Ð»Ð°Ð³ Ð´Ð»Ñ Ð²Ñ‹Ð¿Ð¾Ð»Ð½ÐµÐ½Ð¸Ñ Ð°Ð½Ð¸Ð¼Ð°Ñ†Ð¸Ð¸
    var AnimateFlag = false;
    //ÐºÐ»Ð¸Ðº Ð¿Ð¾ ÐºÐ½Ð¾Ð¿ÐºÐµ "ÐÐ°Ð²ÐµÑ€Ñ…/ÐÐ°Ð·Ð°Ð´"
    $('.inTop').click(function () {
        //Ð²Ñ‹Ð¿Ð¾Ð»Ð½ÑÐµÑ‚ÑÑ Ð°Ð½Ð¸Ð¼Ð°Ñ†Ð¸Ñ
        AnimateFlag = true;
        if (BottomFlag) { //ÐµÑÐ»Ð¸ Ð½Ð°Ð¶Ð°Ñ‚Ð° ÐºÐ½Ð¾Ð¿ÐºÐ° "ÐÐ°Ð·Ð°Ð´"
            //Ð²Ð¾Ð·Ð²Ñ€Ð°Ñ‚ Ð² Ð½ÑƒÐ¶Ð½Ð¾Ðµ Ð¼ÐµÑÑ‚Ð¾ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ñ‹ ÑÐ¾ ÑÐºÐ¾Ñ€Ð¾ÑÑ‚ÑŒÑŽ 200
            $("body,html").animate({"scrollTop": BottomPosition}, 250, function () {
                AnimateFlag = false; // Ð·Ð°ÐºÐ¾Ð½Ñ‡Ð¸Ð»Ð¾ÑÑŒ Ð²Ñ‹Ð¿Ð¾Ð»Ð½ÐµÐ½Ð¸Ðµ Ð°Ð½Ð¸Ð¼Ð°Ñ†Ð¸Ð¸
            });
            //Ð·Ð°Ð¼ÐµÐ½ÑÐµÐ¼ ÐºÐ½Ð¾Ð¿ÐºÑƒ
            BottomFlag = false;
            $('.inTop span').html('<img src="/img/icons/icon-top.png" border="0">');
        } else { //ÐµÑÐ»Ð¸ Ð½Ð°Ð¶Ð°Ñ‚Ð° ÐºÐ½Ð¾Ð¿ÐºÐ° "Ð½Ð°Ð²ÐµÑ€Ñ…"
            //Ð²Ð¾Ð·Ð²Ñ€Ð°Ñ‚ Ð² Ð½Ð°Ñ‡Ð°Ð»Ð¾ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ñ‹ ÑÐ¾ ÑÐºÐ¾Ñ€Ð¾ÑÑ‚ÑŒÑŽ 200
            $("body,html").animate({"scrollTop": 0}, 250, function () {
                AnimateFlag = false;
            });
            //Ð·Ð°Ð¿Ð¾Ð¼Ð¸Ð½Ð°ÐµÐ¼, Ð´Ð¾ ÐºÐ°ÐºÐ¾Ð¹ Ð¿Ð¾Ð·Ð¸Ñ†Ð¸Ð¸ Ð±Ñ‹Ð»Ð° Ð¿Ñ€Ð¾ÐºÑ€ÑƒÑ‡ÐµÐ½Ð° ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ð°
            BottomPosition = $(window).scrollTop();
            //Ð¿Ð¾ÐºÐ°Ð·Ñ‹Ð²Ð°ÐµÐ¼ ÐºÐ½Ð¾Ð¿ÐºÑƒ "ÐÐ°Ð·Ð°Ð´"
            BottomFlag = true;
            $('.inTop span').html('<img src="/img/icons/icon-top.png" class="imgintop">');
        }
    });
    //Ð¾ÑÑƒÑ‰ÐµÑÑ‚Ð²Ð»ÑÐµÐ¼ Ð¿Ñ€Ð¾Ð²ÐµÑ€ÐºÑƒ Ð¿Ñ€Ð¸ Ð¿Ñ€Ð¾ÐºÑ€ÑƒÑ‡Ð¸Ð²Ð°Ð½Ð¸Ð¸ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ñ‹:
    $(window).scroll(function (event) {
        var countScroll = $(window).scrollTop();
        //ÐµÑÐ»Ð¸ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑŒ Ð¿Ñ€Ð¾Ð¼Ð¾Ñ‚Ð°Ð» Ð±Ð¾Ð»ÐµÐµ 200 Ð¿Ð¸ÐºÑÐµÐ»ÐµÐ¹
        if (countScroll > 300 && !AnimateFlag) {
            //Ð¿Ð¾ÐºÐ°Ð·Ñ‹Ð²Ð°ÐµÐ¼ ÐºÐ½Ð¾Ð¿ÐºÑƒ "ÐÐ°Ð²ÐµÑ€Ñ…"
            $('.inTop').show();
            if (BottomFlag) {
                BottomFlag = false;
                $('.inTop span').html('<img src="/img/icons/icon-top.png" border="0">');
            }
        } else {
            if (!BottomFlag) {
                //Ð² Ð´Ñ€ÑƒÐ³Ð¸Ñ… ÑÐ»ÑƒÑ‡Ð°ÑÑ… Ð¿Ñ€ÑÑ‡ÐµÐ¼ ÐºÐ½Ð¾Ð¿ÐºÑƒ, ÐµÑÐ»Ð¸ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ ÑÑ‚Ð¾ Ð½Ðµ ÐºÐ½Ð¾Ð¿ÐºÐ° "ÐÐ°Ð·Ð°Ð´"
                $('.inTop').hide();
            }
        }
    });

    /* Forms */
    function forms() {
        $('input,textarea').focus(function () {
            if ($(this).val() == $(this).attr('data-value')) {
                $(this).addClass('focus');
                $(this).parent().addClass('focus');
                $(this).removeClass('err');
                $(this).parent().removeClass('err');
                if ($(this).attr('data-type') == 'pass') {
                    $(this).attr('type', 'password');
                }
                $(this).val('');
            }
        });
        $('input[data-value], textarea[data-value]').each(function () {
            if (this.value == '' || this.value == $(this).attr('data-value')) {
                this.value = $(this).attr('data-value');
                if ($(this).hasClass('l')) {
                    $(this).parent().append('<div class="form__label">' + $(this).attr('data-value') + '</div>');
                }
            }
            $(this).click(function () {
                if (this.value == $(this).attr('data-value')) {
                    if ($(this).attr('data-type') == 'pass') {
                        $(this).attr('type', 'password');
                    }
                    this.value = '';
                }
            });
            $(this).blur(function () {
                if (this.value == '') {
                    this.value = $(this).attr('data-value');
                    $(this).removeClass('focus');
                    $(this).parent().removeClass('focus');
                    if ($(this).attr('data-type') == 'pass') {
                        $(this).attr('type', 'text');
                    }
                }
            });
        });
    }

    forms();

    // //VALIDATE FORMS

    $('#login-form-btn').on('click',function (e) {
        // e.preventDefault();
        //console.log('er');
        if($(this).hasClass('disabled')){
            return false
        }

        var errors = false

        if($('#loginform-username').val().length == 0 || !isValidEmailAddress($('#loginform-username').val())){
            $('#loginform-username').addClass('err new-shake');
            errors = true
        }

        if($('#loginform-password').val().length == 0){
            $('#loginform-password').addClass('err new-shake');
            errors = true
        }

        if(errors) return false;
        //console.log('er');
        //grecaptcha.execute(LOGIN_RECAPTCHA_ID); //asd
    });

    $('#guest-message-write #sendbutton').on('click',function (e) {
        e.preventDefault();
        //grecaptcha.execute(MESSAGE_WRITE_RECAPTCH_ID);
    });

    $('#login-form').on('afterValidate', function(e){
        e.preventDefault();

        if($(this).find('.has-error').length > 0 ){

            $('.field-loginform-password').find('input').val('').addClass('err new-shake');
            $('.field-loginform-username').find('input').addClass('err new-shake');
            /*
            for(var c in recaptchaIdArr){
                grecaptcha.reset(recaptchaIdArr[c]);
            }
            */
            //  $('#login-form-btn').addClass('disabled')
        }

        $('.model-progress-wrapper .element').hide()
        $('#login-form-btn').attr('disabled', false).removeClass('progress')
    })

    $('#login-form').on('submit', function(e){ //login asd
        $('#login-form-btn').attr('disabled', true).addClass('progress')
        $('.model-progress-wrapper .element').show()
    })


    $('input.req').focus().removeClass('err new-shake');
    /*
        $('.popup-login .g-recaptcha').attr('data-callback', 'checkLoginCallBack');

        $('#recovery-form .g-recaptcha').attr('data-callback', 'checkRecoveryCallBack');

        $('.popup-registration .g-recaptcha').attr('data-callback', 'checkRegisterCallBack');
    */
    $('#registeruser-email, #loginform-username, #loginform-password').on('input', function(){
        $(this).removeClass('err new-shake')
    })
    $('#registeruser-email, #loginform-username, #loginform-password').on('focus', function(){
        $(this).removeClass('err new-shake')
    })

    $('#register-form-btn').on('click',function (e) {
        //  e.preventDefault();
//alert();
        if($(this).hasClass('disabled')){
            return false
        }
        if($('#registeruser-email').val().length == 0 || !isValidEmailAddress($('#registeruser-email').val())){
            $('#registeruser-email').addClass('err new-shake');
            return false;
        }
        //grecaptcha.execute(REGISTER_RECAPATCHA_ID); //asd
        //alert(2);
        $('#register-form').submit(); //asd
        return true
    });


    $('#register-form').on('afterValidate',function (e) {
        $('.form-group.has-error').find('input').addClass('err new-shake');
//alert('after'+$(this).find('.has-error').length);
        if($(this).find('.has-error').length > 0 ){

            $('.field-loginform-password').find('input').val('').addClass('err new-shake');
            $('.field-loginform-username').find('input').addClass('err new-shake');
            /*
            for(var c in recaptchaIdArr){
                grecaptcha.reset(recaptchaIdArr[c]);
            }
            */
            $('#register-form-btn').addClass('disabled')
            $('.model-progress-wrapper .element').hide()
            $('#register-form .popup-form__btn').text('Ð·Ð°Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð¸Ñ€Ð¾Ð²Ð°Ñ‚ÑŒÑÑ').attr('disabled', false).removeClass('progress')
        }
    })

    $('#register-form').on('submit',function (e) {
        alert(3);
        $('#register-form .popup-form__btn').text('Ð ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ñ..').attr('disabled', true).addClass('progress')
        $('.model-progress-wrapper .element').show()
    })

    function maskclear(n) {
        if (n.val() == "") {
            n.inputmask('remove');
            n.val(n.attr('data-value'));
            n.removeClass('focus');
            n.parent().removeClass('focus');
        }
    }

    $('#registertwouser-checkedrule').on('change', function () {
        if ($(this).prop('checked')) {
            $('#check-rule-button').prop('disabled', false);
        } else {
            $('#check-rule-button').prop('disabled', true);
        }
    });
    $('#addusertype-city_id').on('change', function () {
        if ($(this).val() > 0) {
            $('#choice-city-button').prop('disabled', false);
        } else {
            $('#choice-city-button').prop('disabled', true);
        }
    });
    $('#addusertype-type_id').on('change', function () {

        if ($(this).val() > 0) {
            $('#choice-type-button').prop('disabled', false);
        } else {
            $('#choice-type-button').prop('disabled', true);
        }
    });

    // Answer City
    if (!$.cookie('was')) {
        setTimeout(function () {
            $('.header-city-answer').show('fast');
        }, 1000);
    }
    $.cookie('was', true, {
        expires: 365,
        path: '/'
    });
    $('.header-city-answer__button').click(function () {
        $(this).parent().hide('fast');
    });

    // $('.blogmodule-item__title').ellipsis({
    //     lines: 2,
    //     responsive: true
    // });
});

var handler = function () {

    var height_footer = $('footer').height();
    var height_header = $('header').height();
    //$('.content').css({'padding-bottom':height_footer+40, 'padding-top':height_header+40});


    var viewport_wid = viewport().width;
    var viewport_height = viewport().height;

    if (viewport_wid <= 991) {

    }
}



$(window).bind('load', handler);
$(window).bind('resize', handler);

// This code for crosbrauser
$(document).ready(function () {

    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';
    // Safari 3.0+ "[object HTMLElementConstructor]"
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;
    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;
    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;
    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;

    if (isFirefox) $('body').addClass('isFirefox');
    if (isChrome) $('body').addClass('isChrome');
    if (isSafari) $('body').addClass('isSafari');
    if (isIE) $('body').addClass('isIE');
    if (isEdge) $('body').addClass('isEdge');
});
function detectmob() {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ) {
        return true;
    }
    else {
        return false;
    }
}

function windowSize(){
    if ($(window).width() <= '700'){
        $(".artistmodule-reviews-item-text__value").text(function(i, text) {
            if (text.length >= 700) {
                text = text.substring(0, 700);
                var lastIndex = text.lastIndexOf(" ");
                text = text.substring(0, lastIndex) + '...';
            }
            $(this).text(text);
        });
    }
}

$(window).on('load resize',windowSize);


$('#agreement-check').on('change', function () {
    if ($('#agreement-check').prop('checked')) {
        $('.contestmodule-participate__button').prop('disabled', false);
    } else {
        $('.contestmodule-participate__button').prop('disabled', true);
    }
});

$(document).ready(function () {
    var head1 = jQuery("iframe").contents().find("head");
    var css1 = '<style type="text/css">' +
        'p{font-family:"Roboto", sans-serif;display:inline-block;margin:0;}' +
        'iframe{overflow:auto; height:auto}' +
        '</style>';
    jQuery(head1).append(css1);

});


window.onresize= function(){
    var w = $(window).outerWidth();
    if (w < 768) {
        $('.content_block').addClass('hidden');
        $('.content_toggle').removeClass('hidden');
    };
    if (w > 767) {
        $('.content_toggle').addClass('hidden');
        $('.content_block').removeClass('hidden');
    };
    if (w > 1030) {
        $('.male_filter_1030').css("display", "inline-block");
    };
};

$(document).ready(function () {
    var w = $(window).outerWidth();
    if (w < 768) {
        $('.content_block').addClass('hidden');
        $('.content_toggle').removeClass('hidden');
    };
    if (w > 767) {
        $('.content_toggle').addClass('hidden');
    };
    if (w > 1030) {
        $('.male_filter_1030').css("display", "inline-block");
    };
});

$(document).ready(function () {
    $('.header-authuser-menu__linkchange').click(function () {
        $('this').toggleClass('active');
        $('.header-authuser').addClass('focus');
        $('.header-authuser-submenu').toggleClass('focus');
    });
});

$(document).on('click', function(e) {
    if (!$(e.target).closest(".header-authuser").length) {
        $('.header-authuser-submenu').removeClass('focus');
        $('.header-authuser').removeClass('focus');
        $('.header-authuser').removeClass('hover');
    }
    e.stopPropagation();
});


function getCookie(){}
function setCookie(){}

$(document).ready(function () {

    $(document).
    on('click', '.close-container', function () {
        $('[data-container = ' + $(this).data('containerid') + ']').removeClass('active');
        enableScroll();
    }).
    on('click', '.open-container', function () {
        $('[data-container = ' + $(this).data('containerid') + ']').addClass('active');
        disableScroll();
    }).
    mouseup(function (e) {
        var popup = $('.slider-wrapper');
        if (!popup.is(e.target) && popup.has(e.target).length === 0 && !$(e.target).is(".no-close")) {
            if($(popup).closest('.slider-overlay').hasClass('active')){
                $(popup).closest('.slider-overlay').removeClass('active');
                $.ajax({
                    url: '/site/check-popup',
                    type: 'post',
                    success: function(data){
                        getcountmess()
                    }
                })
                enableScroll();
            }
        }
    });

    var slide_lenght = document.querySelectorAll('.slider-item').length;
    if (slide_lenght > 1) {
        var galleryTop = new Swiper('.slider__container', {
            spaceBetween: 10,
            pagination: '.swiper-pagination',
            paginationType: 'fraction',
            loop: true,
            loopedSlides: $('.slider__container .swiper-wrapper .swiper-slide').length });


        var galleryThumbs = new Swiper('.slider-gallery', {
            spaceBetween: 10,
            centeredSlides: '3',
            slidesPerView: 'auto',
            touchRatio: 0.2,
            slideToClickedSlide: true,
            keyboardControl: true,
            loopAdditionalSlides: 1,
            nextButton: '.slider-btn.swiper-button-next',
            prevButton: '.slider-btn.swiper-button-prev',
            loop: true,
            loopedSlides: $('.slider__container .swiper-wrapper .swiper-slide').length });


        galleryTop.params.control = galleryThumbs;
        galleryThumbs.params.control = galleryTop;
    } else
    {
        $('.slider__footer').remove();
    }

    var keys = { 37: 1, 38: 1, 39: 1, 40: 1 };

    function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    function disableScroll() {
        if (window.addEventListener) // older FF
            window.addEventListener('DOMMouseScroll', preventDefault, false);
        window.onwheel = preventDefault; // modern standard
        window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
        window.ontouchmove = preventDefault; // mobile
        document.onkeydown = preventDefaultForScrollKeys;
    }

    function enableScroll() {
        if (window.removeEventListener)
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
        window.onmousewheel = document.onmousewheel = null;
        window.onwheel = null;
        window.ontouchmove = null;
        document.onkeydown = null;
    }

});

// find error anceta
$(document).ready(function () {
    $("#form-newEmail .personal-data__button-submit").on('click', function (e) {

        if ($('#newemailuser-new_email').val() === '') {
            e.preventDefault()
            $('#newemailuser-new_email').addClass('errr-new')
            $('#newemailuser-new_email').addClass('new-shake')
            setTimeout(function () {
                $('#newemailuser-new_email').removeClass('new-shake')
            }, 1000)
        }
        if ($('#newemailuser-new_email').parent().hasClass('has-success')) {
            $('#newemailuser-new_email').removeClass('errr-new')
        }
    })


    $('.admin-feedback__textarea').on('focus', function() {
        $('.admin-feedback__textarea').removeClass('new-shake').removeClass('errr-new');
    });

    $('#newemailuser-unsubscribed').on('change', function(e){
        e.preventDefault()

        $.ajax({
            url: '/user/subscribe',
            type: 'post',
            data: {data: ($(this).prop('checked')) ? 1 : 0},
            success: function(data){
                $('.field-newemailuser-unsubscribed .help-block').text('âœ” OK')
                setTimeout(function () {
                    $('.field-newemailuser-unsubscribed .help-block').fadeOut()
                    setTimeout(function () {
                        $('.field-newemailuser-unsubscribed .help-block').text('').fadeIn()
                    }, 1000)
                }, 2000)
            }
        })
    })

    $('#usertypeforprofile-show_in_cat').on('change', function(e){
        e.preventDefault()

        $.ajax({
            url: '/user/showabout',
            type: 'post',
            data: {data: ($(this).prop('checked')) ? 1 : 0},
            success: function(data){
                $('.form-group field-usertypeforprofile-show_in_cat .help-block').text('âœ” OK')
                setTimeout(function () {
                    $('.form-group field-usertypeforprofile-show_in_cat .help-block').fadeOut()
                    setTimeout(function () {
                        $('.form-group field-usertypeforprofile-show_in_cat .help-block').text('').fadeIn()
                    }, 1000)
                }, 2000)
            }
        })
    })

    $('#tfp-checkbox').on('change', function(e){
        e.preventDefault()

        $.ajax({
            url: '/user/tfp',
            type: 'post',
            data: {data: ($(this).prop('checked')) ? 1 : 0},
        })
    })

    $('#form-newEmail').on('beforeSubmit', function(e){
        e.preventDefault()
        $('.flash-container').removeClass('flash-container-over');
        $('header').removeClass('fixlock2');
        let form = $('#form-newEmail').serialize()

        $.ajax({
            url: '/user/new-email',
            data: form,
            type: 'post',
            success: function(data){
                $('#successSendEmail').fadeIn(300).addClass('active');

                setTimeout(function () {
                    $('#successSendEmail').fadeOut(1000);
                    $('#successChangeEmail').fadeOut(1000);
                }, 20000);
            }
        })

        return false;
    })

    $('#form-newEmail').on('submit', function(e){
        //   e.preventDefault()
        ///   e.stopImmediatePropagation();
        //   return false;
    })

    $('#form-personal-data .personal-data__button-submit').on('click', function () {

        var result = true;

        if ($('#current-name').val() === '' || $('#current-name').val().trim() === '' ) {
            $('#current-name').addClass('errr-new')
            $('#current-name').addClass('new-shake')
            setTimeout(function () {
                $('#current-name').removeClass('new-shake')
            }, 1000)
            result = false
        }
        if ($('#current-name').parent().hasClass('has-success')) {
            //$('#current-name').removeClass('errr-new')
        }

        if($('#current-second-name').length && ($('#current-second-name').val() === '' || $('#current-second-name').val().trim() === '')){
            $('#current-second-name').addClass('errr-new')
            $('#current-second-name').addClass('new-shake')
            setTimeout(function () {
                $('#current-second-name').removeClass('new-shake')
            }, 1000)
            result = false
        }
        if ($('#current-second-name').parent().hasClass('has-success')) {
            //   $('#current-second-name').removeClass('errr-new')
        }

        return result
    })
})



$(document).ready(function () {
    $("#predprosmotr").on('click', function () {
        var n_title = $('#news-title').val();
        var n_body = $('#news-body').val();
        $('#pp_title').empty();
        $('#pp_body').empty();
        $('#pp_title').append(n_title);
        $('#pp_body').append(n_body);
        $('.popup-pred').addClass('active');
    });
    $("#form-registration-data .personal-data__button-submit").on('click', function () {

        if($('#form-registration-data #old-password').val() === '' || $('#form-registration-data #old-password').hasClass('witherror')){
            $('#form-registration-data #old-password').addClass('errr-new');
            $('#form-registration-data #old-password').addClass('new-shake');
            setTimeout(function () {
                $('#form-registration-data #old-password').removeClass('new-shake');
            },1000);
        }

        if($('#form-registration-data #registration-password').val() === '' || $('#form-registration-data #registration-password').hasClass('witherror')){
            $('#form-registration-data #registration-password').addClass('errr-new');
            $('#form-registration-data #registration-password').addClass('new-shake');
            setTimeout(function () {
                $('#form-registration-data #registration-password').removeClass('new-shake');
            },1000);
        }

        if($('#form-registration-data #registration-password-confirmation').val() === '' || $('#form-registration-data #registration-password-confirmation').hasClass('witherror')){
            $('#form-registration-data #registration-password-confirmation').addClass('errr-new');
            $('#form-registration-data #registration-password-confirmation').addClass('new-shake');
            setTimeout(function () {
                $('#form-registration-data #registration-password-confirmation').removeClass('new-shake');
            },1000);
        }

        if($('#form-registration-data .field-old-password')&&('#form-registration-data .field-registration-password')&&('#form-registration-data .field-registration-password-confirmation').hasClass('has-success')){
            $('#form-registration-data .personal-data__field-input').removeClass('errr-new');
        }
    });
    $('.registration-module__input').focus(function() {
        $(this).removeClass('errr-new');
    });
    $('.personal-data__field-input').focus(function() {
        $(this).removeClass('errr-new');
    });
});

$(document).ready(function () {
    $('#send-news').on('beforeValidate', function(e){
        e.preventDefault()
        var errors = ''
        if($('.my-news__add-button').hasClass('disabled')){
            if($('#news-title').val().length == 0){
                errors = 'Ð—Ð°Ð¿Ð¾Ð»Ð½Ð¸Ñ‚Ðµ Ð¿Ð¾Ð»Ðµ "ÐÐ°Ð¸Ð¼ÐµÐ½Ð¾Ð²Ð°Ð½Ð¸Ðµ" \r\n'
            }
            if($('#news-description').val().length < 200){
                errors += 'ÐœÐ¸Ð½Ð¸Ð¼ÑƒÐ¼ 200 ÑÐ¸Ð¼Ð²Ð¾Ð»Ð¾Ð² Ð² Ð¿Ð¾Ð»Ðµ "ÐšÑ€Ð°Ñ‚ÐºÐ¾Ðµ Ð¾Ð¿Ð¸ÑÐ°Ð½Ð¸Ðµ" \r\n'
            }
            if(countCharacters( window.CKbody.model.document ) < 2000){
                errors += 'ÐœÐ¸Ð½Ð¸Ð¼ÑƒÐ¼ 2000 ÑÐ¸Ð¼Ð²Ð¾Ð»Ð¾Ð² Ð² Ð¿Ð¾Ð»Ðµ "ÐÐ¾Ð²Ð¾ÑÑ‚ÑŒ" '
            }
            alert(errors)
            return false
        }
        else{
            $.ajax({
                url: '/user/check-news',
                type: 'post',
                data: {text: window.CKbody.getData()},
                success: function(data){
                    if(data.success){
                        $.ajax({
                            url: '/user/save-news',
                            data: $('#send-news').serialize(),
                            type: 'post',
                            success: function(data){
                                console.log(data)
                            }
                        })
                        return true
                    }else{
                        $('.shin-error').find('a').attr('href', '/news/id'+data.id).text(data.title)
                        $('.shin-error').show(0).addClass('active');
                    }
                }
            })
            return false
        }
    })

    $('.sendnew').on('click', function(){
        $.ajax({
            url: '/user/save-news',
            data: $('#send-news').serialize(),
            type: 'post',
            success: function(data){
                console.log(data)
            }
        })
    })

    $('.admin-feedback__submit').on('click', function(e){
        //  e.preventDefault()
        if($(this).hasClass('disabled')){
            return false
        }


    })

    $(".registration-module__button_step-4").on('click', function () {

        let errors = false

        if($('#registration-name').val() == ''){
            $('#registration-name').addClass('new-shake').addClass('errr-new');
            setTimeout(function () {
                $('#registration-name').removeClass('new-shake');
            },1000);
            errors = true
        }

        if($('#addusertype-type_id-styler .jq-selectbox__select-text').text() != "Ð¤Ð¾Ñ‚Ð¾ÑÑ‚ÑƒÐ´Ð¸Ñ"){
            if($('#registration-second-name').val() == ''){
                $('#registration-second-name').addClass('new-shake').addClass('errr-new');
                setTimeout(function () {
                    $('#registration-second-name').removeClass('new-shake');
                },1000);
                errors = true
            }

            if($('#addusertype-age')){
                if($('#addusertype-age').val() == ''){
                    $('#addusertype-age').addClass('new-shake').addClass('errr-new');
                    setTimeout(function () {
                        $('#addusertype-age').removeClass('new-shake');
                    },1000);
                    errors = true
                }
            }


            if($('#registertwouser-age')){
                if($('#registertwouser-age').val() == ''){
                    $('#registertwouser-age').addClass('new-shake').addClass('errr-new');
                    setTimeout(function () {
                        $('#registertwouser-age').removeClass('new-shake');
                    },1000);
                    errors = true
                }
            }


            if($('#registration-password')){
                if($('#registration-password').val() == ''){
                    $('#registration-password').addClass('new-shake').addClass('errr-new');
                    setTimeout(function () {
                        $('#registration-password').removeClass('new-shake');
                    },1000);
                    errors = true
                }
            }

            if($('#registration-password-confirmation')){
                if($('#registration-password-confirmation').val() == '' || $('#registration-password-confirmation').val() != $('#registration-password').val()){
                    $('#registration-password-confirmation').addClass('new-shake').addClass('errr-new');
                    setTimeout(function () {
                        $('#registration-password-confirmation').removeClass('new-shake');
                    },1000);
                    errors = true
                }
            }
        }


        return !errors;
    })
});

$(document).ready(function () {
    if ($('document').hasClass('section-profile')) {
        $('body').addClass('new-lld');
    }
    $('.photo-slider').click(function (event) {
        var scr = $(window).scrollTop();
        if (scr > 109) {
            $('header').addClass('fix');
        }
    });
    if ($('header').hasClass('mobilefix')) {
        $('#popupSliderItem').addClass('popup_mobilefix');
    }

    if ($('.main-wrapper').hasClass('fullpage') && (($('.flash-container').css('display') != 'none'))) {
        $('.flash-container').addClass('flash-container-over');
        $('#popupSliderItem').addClass('photo-up');
    }
});

$('.click-album').on('click', function () {
    setTimeout(function () {
        if($('.portfolio__album-item').hasClass('personal-data__button-delete')){
            $('.portfolio__album-item').css("display", "none");
            console.log(1);
        }
    },100);
    console.log($('.portfolio__album-item').height());
});

document.addEventListener('click', function(e) {
    var map = document.querySelector('#map-wrap iframe')
    if(map){
        if(e.target.id === 'map-wrap') {
            map.style.pointerEvents = 'all'
        } else {
            map.style.pointerEvents = 'none'
        }
    }
});


function onLoginFormSubmit(token) {
    console.log('success!', token);
    $('#login-form input[name="recaptcha"]').val(token);
    //grecaptcha.reset(LOGIN_RECAPTCHA_ID);
    $('#login-form').submit();
}

function onRegisterFormSubmit(token) {
    console.log('success!', token);
    $('#register-form input[name="recaptcha"]').val(token);
    //grecaptcha.reset(REGISTER_RECAPATCHA_ID);
    $('#register-form').submit();
}

function onMessageWriteFormSubmit(token) {
    console.log('success!', token);
    $('#guest-message-write input[name="recaptcha"]').val(token);
    //grecaptcha.reset(MESSAGE_WRITE_RECAPTCH_ID);
    $('#guest-message-write').submit();
}

LOGIN_RECAPTCHA_ID = null;
REGISTER_RECAPATCHA_ID = null;
MESSAGE_WRITE_RECAPTCH_ID = null;
PASS_RECOVER_RECAPTCHA_ID = null;

/*FAV*/
jQuery(document).ready(function($){
    var cartWrapper = $('.cd-cart-container');
    var productId = 0;
    var i = 1;
    //var cnt = 0;
    var actual;
    var next;
    var ids = '';
    var token = $('meta[name="csrf-token"]').attr("content");
    var countUsers = $('#ajaxContent').attr('count');
    var j = 0;
    var limit = 20;
    var flag = 0;

    if($( ".ant-button" ).css('bottom') == '-30px' && $( "#ant-counter" ).text()*1 != 0) {
        if($('.flash-container').css('display') != 'none') {
            $( ".ant-button" ).animate({ "bottom": "70px" }, 0 );
        } else {
            $( ".ant-button" ).animate({ "bottom": "40px" }, 0 );
        }
    }
    $(window).resize(function() {
        w = $(window).width();
        if(w < 993){
            w2 = $('.ant-button').width();
            w3 = ((w - w2) / 2) -20;
            $('.ant-button').css('right', w3+'px');
        }else if (w < 1920){
            $('.ant-button').css('right', '70px');
        }else {
            w4 = (w - 1906) / 2;
            w5 = w4 + 70;
            $('.ant-button').css('right', w5+'px');
        }
    });
    $(window).resize();

    if(window.location.pathname == '/fav'){
        $('.cd-cart-trigger').hide();
    }

    if(countUsers > limit){
        $('#add-spec').show();
    }else{
        $('#add-spec').hide();
    }


    $('#fav-icon span').on('click', function(event){
        if($(this).parent().find('img').attr('src') == '/img/icons/fav-empty-blue.png'){
            $('.cd-addd-to-cart').attr('src', '/img/icons/fav-full.png');
            $( '.wrapper' ).find('#fav-icon span').text('Ð£Ð´Ð°Ð»Ð¸Ñ‚ÑŒ Ð¸Ð· Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ð³Ð¾');
        }else{
            $('.cd-addd-to-cart').attr('src', '/img/icons/fav-empty-blue.png');
            $( '.wrapper' ).find('#fav-icon span').text('Ð”Ð¾Ð±Ð°Ð²Ð¸Ñ‚ÑŒ Ð² Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ðµ');
        }

        id = $(this).parent().parent().parent().attr('data-user');
        updateFavorites(id);
    });

    $(".cd-addd-to-cart").mouseenter(function(){
        if($(this).parent().find('img').attr('src') == '/img/icons/fav-empty.png'){
            $(this).attr('src','/img/icons/fav-empty-blue.png');
        }
    });

    $(".cd-addd-to-cart").mouseleave(function(){
        if($(this).parent().find('img').attr('src') == '/img/icons/fav-empty-blue.png'){
            $(this).attr('src','/img/icons/fav-empty.png');
        }
    });

    // ÐºÐ½Ð¾Ð¿ÐºÐ° "Ð¿Ð¾ÐºÐ°Ð·Ð°Ñ‚ÑŒ ÐµÑ‰Ðµ"
    $('#add-spec').on('click', function(event){
        j++;

        if(j <= Math.floor(countUsers / limit) && window.location.pathname == '/fav'){
            offset = limit * j;
            //limit2 = (j+1)*limit;

            $.post('/fav', {offset:offset, _csrf: token}, function(data) {
                //$('#ajaxContent').empty();
                $('#ajaxContent').append(data);
                $('.cd-addd-to-cart').attr('src', '/img/icons/fav-full.png');
            });

            var k = ( Math.floor(countUsers % limit == 0) ) ? 1 : 0;

            if(j == Math.floor(countUsers / limit - k)){
                $(this).hide();
            }
        }else{
            setBlue(ids);
        }
    });


    $.post('/fav', {getIds:1, _csrf: token}, function(data) {

        if((typeof data === "string" || data instanceof String) && data != ''){
            cnt = data.split(',').length;
            $('#ant-counter').text(cnt);
            ids = data+',';
        }else{
            $('#ant-counter').text(0);
        }
    });

    $('body').on('click', '.cd-addd-to-cart' ,function(event){
        if(flag == 1) return false;
        flag = 1;

        /*if ($("#ant-counter").text()*1 == 0) {
            if($(this).parent().find('img').attr('src') == '/img/icons/fav-empty.png'){
                $( ".ant-button" ).show();
            }
        }*/

        if( $(this).attr('src') == '/img/icons/fav-empty-blue.png' ){
            $(this).attr('src', '/img/icons/fav-full.png');
            $( '.wrapper' ).find('#fav-icon span').text('Ð£Ð´Ð°Ð»Ð¸Ñ‚ÑŒ Ð¸Ð· Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ð³Ð¾');

        }else{
            if(window.location.pathname != '/fav'){
                $(this).attr('src', '/img/icons/fav-empty-blue.png');
            }
            $( '.wrapper' ).find('#fav-icon span').text('Ð”Ð¾Ð±Ð°Ð²Ð¸Ñ‚ÑŒ Ð² Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ðµ');

        }

        id = findSpecialist($(this));
        updateFavorites(id); // ÑƒÐ´Ð°Ð»ÐµÐ½Ð¸Ðµ id Ð½Ð° ÑÐµÑ€Ð²ÐµÑ€Ðµ

        deleteSpecialist(this);
    });



    function setBlue(str) {
        $('body').find('.wrapper').each(function( index ) {
            id = $( this ).parent('li').attr('userid');


            if (typeof id === "string" || id instanceof String) {
                if(str.indexOf(id) != -1){
                    $( this ).find('.cd-addd-to-cart').attr('src', '/img/icons/fav-full.png');
                }
            }
        });

        id2 = $('.wrapper').find('.profile-card').attr('data-user');

        if(ids.indexOf(id2) != -1){
            $( '.wrapper' ).find('.cd-addd-to-cart').attr('src', '/img/icons/fav-full.png');
            $( '.wrapper' ).find('#fav-icon span').text('Ð£Ð´Ð°Ð»Ð¸Ñ‚ÑŒ Ð¸Ð· Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ð³Ð¾');
        }
    }

    function deleteSpecialist(obj) {
        if(window.location.pathname == '/fav'){
            if(ids == ''){
                $('#ant-best').text('Ð’Ñ‹ Ð¿Ð¾ÐºÐ° Ð½Ð¸Ñ‡ÐµÐ³Ð¾ Ð½Ðµ Ð´Ð¾Ð±Ð°Ð²Ð¸Ð»Ð¸ Ð² Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ðµ');
            }

            updateList();
        }
    }

    function updateList() {
        limit2 = (j+1)*limit;
        offset = 0;
        countUsers--;

        $.post('/fav', {offset:offset, limit:limit2,  _csrf: token}, function(data) {
            $('#ajaxContent').empty();
            $('#ajaxContent').append(data);
            $('.cd-addd-to-cart').attr('src', '/img/icons/fav-full.png');

            var k = ( Math.floor(countUsers % limit == 0) ) ? 1 : 0;


            if(j == Math.floor(countUsers / limit - k)){
                $('#add-spec').hide();
            }
        });

    }

    function findSpecialist(obj) {
        //Ð¿Ð¾Ð¸ÑÐº Ð½Ð° Ñ€Ð°Ð·Ð½Ñ‹Ñ… Ð²Ð¸Ð´Ð°Ñ… ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†
        if(obj.hasClass('ant-header')){
            id = obj.parent().parent().parent().attr('data-user');
        }else{
            id = obj.parents('.ant-wrapper').attr('userid');
        }

        return id;
    }

    function updateFavorites(id){
        $.ajax({
            type: 'POST',
            url: '/fav',
            data: {id:id, _csrf: token},
            success: function(res) {
                console.log(res);
                $('#ant-counter').text(res);
                flag = 0;
                if(res == 0){
                    el = 'Ð’Ñ‹ Ð¿Ð¾ÐºÐ° Ð½Ð¸Ñ‡ÐµÐ³Ð¾ Ð½Ðµ Ð´Ð¾Ð±Ð°Ð²Ð¸Ð»Ð¸ Ð² Ð¸Ð·Ð±Ñ€Ð°Ð½Ð½Ð¾Ðµ';
                    $('#ant-best').text(el);
                    $( ".ant-button" ).animate({ "bottom": "-30px" }, 100 );
                }

                if(res == 1){
                    if($( ".ant-button" ).css('bottom') == '-30px') {
                        if($('.flash-container').css('display') != 'none') {
                            $( ".ant-button" ).animate({ "bottom": "70px" }, 100 );
                        } else {
                            $( ".ant-button" ).animate({ "bottom": "40px" }, 100 );
                        }
                    }
                }

            },
            async:false // Ð±ÑƒÐ´ÐµÐ¼ Ð²Ñ‹Ð¿Ð¾Ð»Ð½ÑÑ‚ÑŒ ÑÐ¸Ð½Ñ…Ñ€Ð¾Ð½Ð½Ð¾
        });
    }

});
/* /FAV */

