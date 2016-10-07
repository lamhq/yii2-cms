<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * AccountSearch represents the model behind the search form about `app\models\User`.
 */
class Account extends User
{
	public $password;
	public $repeatPassword;
	public $roles=[];

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'email'], 'required'],
			[['username','email'], 'filter', 'filter' => 'trim'],
			['username', 'unique', 'targetClass' => User::className(), 'filter' => function ($query) {
				if (!$this->isNewRecord) {
					$query->andWhere(['not', ['id'=>$this->id]]);
				}
			}],
			['username', 'string', 'min' => 2, 'max' => 255],
			['email', 'email'],
			[['password','username'], 'required', 'on'=>'insert'],
			['password', 'compare', 'compareAttribute' => 'repeatPassword'],
			['password', 'string', 'min' => 6],
			[['roles', 'status', 'repeatPassword'], 'safe'],
		];
	}

	public function save($runValidation = true, $attributeNames = NULL) {

		if ($this->password) {
			$this->setPassword($this->password);
		}

		$result = false;
		$transaction = self::getDb()->beginTransaction();
		try {
			$result = parent::save($runValidation, $attributeNames);
			if (!$result) {
				throw new Exception('Can not update data.');
			}

			$auth = Yii::$app->authManager;
			$auth->revokeAll($this->id);
			if ($this->roles && is_array($this->roles)) {
				foreach ($this->roles as $role) {
					$auth->assign($auth->getRole($role), $this->id);
				}
			}

			$transaction->commit();
		} catch(\Exception $e) {
			$transaction->rollBack();
			// throw $e;
		}
		return $result;
	}

}
