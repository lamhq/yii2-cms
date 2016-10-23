<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%slideshow_image}}".
 *
 * @property integer $id
 * @property string $filename
 * @property integer $slideshow_id
 *
 * @property Slideshow $slideshow
 */
class SlideshowImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slideshow_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slideshow_id'], 'integer'],
            [['filename'], 'string', 'max' => 255],
            [['slideshow_id'], 'exist', 'skipOnError' => true, 'targetClass' => Slideshow::className(), 'targetAttribute' => ['slideshow_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'filename' => Yii::t('app', 'Filename'),
            'slideshow_id' => Yii::t('app', 'Slideshow ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlideshow()
    {
        return $this->hasOne(Slideshow::className(), ['id' => 'slideshow_id']);
    }
}
