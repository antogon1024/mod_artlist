<?php

namespace app\modules\artlist\models;

use Yii;

class DbModel extends \yii\db\ActiveRecord
{
    public static function getDb() {
        return Yii::$app->dbart;
    }

    public function deleteRecursive($relations = []) {

        foreach($relations as $relation) {

            if(is_array($this->$relation)) {

                foreach($this->$relation as $relationItem)
                    $relationItem->deleteRecursive();

            } else {

                if(isset($this->$relation))
                    $this->$relation->deleteRecursive();

            }

        }

        $this->delete();

        return true;

    }
}
