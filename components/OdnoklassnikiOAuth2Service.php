<?php

namespace app\components;

class OdnoklassnikiOAuth2Service extends \nodge\eauth\services\OdnoklassnikiOAuth2Service
{
    protected   $baseApiUrl = 'http://api.odnoklassniki.ru/fb.do';

    protected function fetchAttributes()
    {
        $info = $this->makeSignedRequest('', [
            'query' => [
                'method' => 'users.getCurrentUser',
                'format' => 'JSON',
                'application_key' => $this->clientPublic,
                'client_id' => $this->clientId,
            ],
        ]);

        $this->attributes['id'] = $info['uid'];
        $this->attributes['first_name'] = $info['first_name'];
        $this->attributes['last_name'] = $info['last_name'];
        $this->attributes['photo'] = ($info['pic_1'] != 'https://i.mycdn.me/res/stub_50x50.gif') ? $info['pic_1'] : null;
        return true;
    }
}