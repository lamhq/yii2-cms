<?php
namespace backend\models;

use yii\base\Model;
use Yii;
use yii\base\UnknownPropertyException;

/**
 * Account form
 */
class SettingForm extends Model
{
	protected $_data = [];

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['adminEmail', 'required'],
			['adminEmail', 'email'],
			[['tagLine','siteTitle', 'adminEmail'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'siteTitle' => Yii::t('app', 'Site Title'),
			'tagLine' => Yii::t('app', 'Tagline'),
			'adminEmail' => Yii::t('app', 'Admin Email'),
		];
	}

	public function init() {
		$this->attributes = Yii::$app->setting->getItems();
	}

	public function save() {
		Yii::$app->setting->setItem($this->attributes);
		return Yii::$app->setting->saveItems();
	}

	public function __set($name, $value) {
		try {
			parent::__set($name, $value);
		} catch (UnknownPropertyException $e) {
			$this->_data[$name] = $value;
		}
	}

	public function __get($name)
	{
		try {
			return parent::__get($name);
		} catch (UnknownPropertyException $e) {
			return isset($this->_data[$name]) ? $this->_data[$name] : null;
		}
	}

	public function attributes() {
		return $this->safeAttributes();
	}

}
