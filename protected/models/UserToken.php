<?php

namespace app\models;

use app\models\UserTokenQuery;
use Yii;
use yii\base\InvalidCallException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%user_token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $token
 * @property integer $expire_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserToken extends ActiveRecord
{
	const TOKEN_LENGTH = 40;
	const TYPE_ACTIVATION = 1;
	const TYPE_PASSWORD_RESET = 2;

	/**
	 * @return string
	 */
	function __toString()
	{
		return $this->token;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user_token}}';
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()'),
			]
		];
	}

	/**
	 * @return UserTokenQuery
	 */
	public static function find()
	{
		return new UserTokenQuery(get_called_class());
	}


	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'type', 'token'], 'required'],
			[['user_id'], 'integer'],
			[['type'], 'integer'],
			[['expire_at'], 'safe'],
			[['token'], 'string', 'max' => self::TOKEN_LENGTH]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User ID'),
			'type' => Yii::t('app', 'Type'),
			'token' => Yii::t('app', 'Token'),
			'expire_at' => Yii::t('app', 'Expire At'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @param mixed $user_id
	 * @param string $type
	 * @param int|null $duration
	 * @return bool|UserToken
	 */
	public static function create($user_id, $type, $duration = null)
	{
		$model = new self;
		$model->setAttributes([
			'user_id' => $user_id,
			'type' => $type,
			'token' => Yii::$app->security->generateRandomString(self::TOKEN_LENGTH),
			'expire_at' => $duration ? 
				new Expression(sprintf('DATE_ADD(NOW(), INTERVAL %d SECOND)', $duration))
				: null,
		]);

		if (!$model->save()) {
			var_dump($model->getErrors());die;
			throw new InvalidCallException;
		};

		return $model;
	}

	/**
	 * @param int|null $duration
	 */
	public function renew($duration)
	{
		$this->updateAttributes([
			'expire_at' => $duration ? time() + $duration : null
		]);
	}
	
}
