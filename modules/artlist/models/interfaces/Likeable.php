<?php


namespace app\modules\artlist\models\interfaces;


interface Likeable
{
    public function like();

    public function dislike();

    public function getLikeId();
}