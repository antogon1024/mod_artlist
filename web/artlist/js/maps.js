var yamap = {

    init: function() {
        yamap.hover();
        yamap.click();
        yamap.scrol();
    },

    hover: function() {

        jQuery('body').on('mouseenter', '.ymap-container', function(e) {
            if (!$('#loader-check').hasClass('done')) {
                yamap.setSrc(this);
            }
        });

    },

    click: function() {

        jQuery('body').on('click', '.ymap-container', function(e) {
            if (!$('#loader-check').hasClass('done')) {
                yamap.setSrc(this);
            }
        });

    },

    scrol: function() {
        $(window).scroll(function (event) {
            if (!$('#loader-check').hasClass('done')) {
                yamap.setSrc(this);
            }
        });
    },

    setSrc: function(el) {
        if (el) {
            if (window.location.href.indexOf("petrozavodsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrAiWpm';
            }
            if (window.location.href.indexOf("moscow") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrAq22-';
            }
            if (window.location.href.indexOf("saratov") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEiI9R';
            }
            if (window.location.href.indexOf("perm") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEUBJ4';
            }
            if (window.location.href.indexOf("ekaterinburg") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCSxvCo3';
            }
            if (window.location.href.indexOf("krasnodar") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEM-Lv';
            }
            if (window.location.href.indexOf("kazan") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEMPmG';
            }
            if (window.location.href.indexOf("samara") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEUSLP';
            }
            if (window.location.href.indexOf("omsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEQL06';
            }
            if (window.location.href.indexOf("volgograd") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEaX-S';
            }
            if (window.location.href.indexOf("voronezh") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEILMX';
            }
            if (window.location.href.indexOf("kirov") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEyIYw';
            }
            if (window.location.href.indexOf("kemerovo") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEm4Mc';
            }
            if (window.location.href.indexOf("kaliningrad") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrE4Zki';
            }
            if (window.location.href.indexOf("astrahan") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEi-Y1';
            }
            if (window.location.href.indexOf("irkutsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEqY7V';
            }
            if (window.location.href.indexOf("izhevsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEqW0k';
            }
            if (window.location.href.indexOf("barnaul") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEqCnm';
            }
            if (window.location.href.indexOf("vladivostok") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEmD-I';
            }
            if (window.location.href.indexOf("spb") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrA5VnV';
            }
            if (window.location.href.indexOf("sevastopol") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEYTnP';
            }
            if (window.location.href.indexOf("simferopol") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEa0ls';
            }
            if (window.location.href.indexOf("novosibirsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEQOZC';
            }
            if (window.location.href.indexOf("chelyabinsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEYY8p';
            }
            if (window.location.href.indexOf("rostov") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEUZJ5';
            }
            if (window.location.href.indexOf("ufa") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEUXIY';
            }
            if (window.location.href.indexOf("tyumen") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEiN11';
            }
            if (window.location.href.indexOf("yaroslavl") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEm-IT';
            }
            if (window.location.href.indexOf("artlist.pro/nn") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEQ4N1';
            }
            if (window.location.href.indexOf("ulyanovsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEqNk6';
            }
            if (window.location.href.indexOf("orenburg") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEmV9z';
            }
            if (window.location.href.indexOf("tomsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEmO2q';
            }
            if (window.location.href.indexOf("tolyatti") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEiPKi';
            }
            if (window.location.href.indexOf("tula") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrE4FYo';
            }
            if (window.location.href.indexOf("lipetsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrE4PiE';
            }
            if (window.location.href.indexOf("kursk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrE4MPd';
            }
            if (window.location.href.indexOf("tver") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEYCP3';
            }
            if (window.location.href.indexOf("sochi") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEY0np';
            }
            if (window.location.href.indexOf("krasnoyarsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CCrEeJNe';
            }
            if (window.location.href.indexOf("habarovsk") > -1) {
                var url = 'https://yandex.ru/map-widget/v1/-/CKQkuHIX';
            }
            jQuery('iframe#map-load').removeAttr('style');
            jQuery('iframe#map-load').attr('src', url).fadeIn();
            jQuery('iframe#map-load').attr('style',  'height:0; display:block;');
            jQuery('.loader').addClass('is-active');
            setTimeout(function() {
                jQuery('iframe#map-load').removeAttr('style');
                jQuery('.loader').removeClass('is-active');
                jQuery('.loader').addClass('done');
                jQuery('div#map-preview').hide();
                jQuery('.mapmodule').attr('style',  'margin:0 0 40px 0;');
                jQuery('iframe#map-load').attr('height', '400').fadeIn();
            }, 1500)
        }
    }
}

jQuery(document).ready(function() {
    yamap.init();
});