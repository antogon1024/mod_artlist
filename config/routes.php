<?php
 return [

     'unsubscribe/<hash:\w+>' => 'site/unsubscribe',

     'payment/webmoney' => 'payment/webmoney',
     'payment/robokassa' => 'payment/robokassa',

     'login/<service:google|facebook|etc>' => 'site/slogin',

     'adverticement/add' => 'adverticement/add',

     'search' => 'search/index',
     'search-ajax' => 'search/search-ajax',
     'search-city-ajax' => 'search/search-city-ajax',

     'like/competition-photo-like/<user_competition_id:\d+>' => 'like/competition-photo-like',
     'like/competition-photo-dislike/<id:\d+>' => 'like/competition-photo-dislike',
     'like/media-photo-like/<user_media_id:\d+>' => 'like/media-photo-like',
     'like/media-photo-dislike/<id:\d+>' => 'like/media-photo-dislike',

     'user/sort' =>  'user/sort',
     'user/subscribe' =>  'user/subscribe',
     'user/tfp' =>  'user/tfp',
     'user/showabout' =>  'user/showabout',
     '<type:photo|video><user_type_id:\d+>_<media_id:\d+>' => 'site/shortlink',
     'album<user_type_id:\d+>-<albom_id:\d+><type:\w+>' =>  'site/personal-albom',
     'assign' => 'site/assign',
     '404' => 'site/error',
     '/' => 'site/index',
     'reg' => 'site/reg',
     'site/login' => 'site/login',
     'site/logout' => 'site/logout',
     'site/send-email' => 'site/send-email',
     'site/soc-login' => 'site/soc-login',
     'payment/index' => 'payment/index',
     'add' => 'user/add',
     'my/message/chat/<id:\d+>' => 'user/message',
     'my/message' => 'user/message',
     'my/news' => 'user/news',
     'my/<page:\w+>' => 'user/index',
     'my/portfolio/<genre:\w+>/<type:\w+>' => 'user/album',
     '/my' => 'user/index',
     'admin' => 'admin/user/index',
     'id<id:\d+>' => 'site/personal-page',

     '/recovery' => 'site/recovery',
	 '/fav' => 'site/fav',
     '/zzz' => 'site/zzz',
     '/aaa' => 'site/aaa',


     '/contests' => 'competition/index',
     'competition/more' => 'competition/more',
     'competition/sort' => 'competition/sort',
     'competition/upload/<id:\d+>' => 'competition/upload',
     'contests/<id:\d+>' => 'competition/show',
     'competition/remove-photo/<id:\d+>' => 'competition/remove-photo',

     'competition/review' => 'competition/review',
     'competition/delete-review/<id:\d+>' => 'competition/delete-review',
	 
	 
     '/<city_name:\w+>/new' => 'site/new-users',
    // '<city_name:\w+>/<ng:\w+>/id<genre_id:\d+>' => 'site/category',
     'info/<link:\w+>' => 'site/information',
     '/info/site/self-mess' => 'site/self-mess',
     '<city_name:>' => 'site/user-city',


     '<city_name:\w+>/site/user-city/<id:\d+>' => 'site/index',

                'cphoto/<id:\d+>' => 'competition/photo',

     '/news/all' => 'news/index',
                '/news/<id:\d+>' => 'news/show',
     '/photo/all/<id:\d+>' => 'site/all-photos',
     '/photo/id<id:\d+>' => 'site/all-photos',
     'site/vers' => 'site/vers',
     '<city_name:\w+>/<ng:\w+>/id<genre_id:\d+>' => 'site/category',
	 
     '<city_name:\w+>/<ng:\w+>/ne<price_do:\w+>go' => 'site/category',
     '<city_name:\w+>/<ng:\w+>/t<tfp:\w+>p' => 'site/category',

     '<city_name:\w+>/<ng:\w+>' => 'site/category',

	 
     '<city:\w+>/site/all-photos' => 'site/all-photos',
     '<city:\w+>/site/all-photos/<id:\d+>' => 'site/all-photos',
     '<city:\w+>/site/all-photos/<id:\d+>/<idg:\d+>' => 'site/all-photos',
     '<city:\w+>/site/all-photos-more/<id:\d+>/<idg:\d+>' => 'site/all-photos-more',


     '<city:\w+>/site/category-more/' => 'site/category-more',
     '<city:\w+>/site/category-more/<id:\d+>' => 'site/category-more',
     '<city:\w+>/site/category-more/<id:\d+>/<idg:\d+>' => 'site/category-more',
     '<city:\w+>/site/category-more/<id:\d+>/<idg:\d+>/<genre_id:\d+>' => 'site/category-more',

     '<city:\w+>/site/information' => 'site/information',
     '<city:\w+>/site/information/<id:\d+>/<link:\w+>' => 'site/information',

               // '/site/personal-page/<cid:\d+>/<id:\d+>' => 'site/personal-page',

     'site/contest-work' => 'site/contest-work',
	 
	 'hello/'=>'hello/index'
 ];