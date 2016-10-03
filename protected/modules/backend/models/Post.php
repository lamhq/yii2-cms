<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

/**
 * @inheritdoc
 */
class Post extends \app\models\Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['published_at'], 'filter', 'skipOnEmpty' => true, 'filter' => function ($value) {
            	return \app\components\helpers\DateHelper::toDbDatetime($value);
            }],
        ]);
    }

}
