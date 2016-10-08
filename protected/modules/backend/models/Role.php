<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\rbac\Item;

/**
 * Account form
 */
class Role extends Model
{
	public $name;
	public $description;
	public $permissions='[]';
	public $access;

	public $isNewRecord = true;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['name', 'required', 'on'=>'insert'],
			['name', function ($attribute, $params) {
				$name = $this->$attribute;
				$auth = Yii::$app->authManager;
				$query = (new Query)
					->from($auth->itemTable)
					->where(['name'=>$this->name]);
				if ($query->exists($auth->db)) {
					$this->addError($attribute, 'This name was already taken.');
				}
			}, 'on'=>['insert']],
			[['name'], 'string', 'max' => 64],
			[['description','permissions','access'], 'safe', 'on'=>['insert', 'update']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'access' => Yii::t('app', 'Can access')
		];
	}

	static public function getAccessOptions () {
		return [
			'all' => Yii::t('app', 'All'),
			'custom' => Yii::t('app', 'Custom'),
		];
	}

	static public function getListData () {
		return ArrayHelper::map(self::getRoles(), 'name', 'name');
	}

	static public function getRoles () {
		return Yii::$app->authManager->getRoles();
	}

	static public function getMasterRoles() {
		$list = (new Query)
			->from('{{%master_role}}')
			->column(Yii::$app->authManager->db);
		return array_filter(self::getRoles(), function ($role) use ($list) {
			return in_array($role->name, $list);
		});
	}

	// get assigned top permissions of a role
	static public function getAssignedPermissions($role) {
		// $auth = Yii::$app->authManager;
		// $query = (new Query)
		// 	->select(['item.name'])
		// 	->from($auth->itemChildTable.' AS itemChild')
		// 	->join('INNER JOIN', $auth->itemTable.' AS item', 'itemChild.child=item.name')
		// 	->where([
		// 		'parent' => $role,
		// 		'item.type'=> Item::TYPE_PERMISSION
		// 	]);

		// return $query->column($auth->db);
		return Permission::getChildPermissions($role);
	}

	static public function find($id) {
		$auth = Yii::$app->authManager;
		$role = $auth->getRole($id);
		if (!$role) return false;

		$model = new Role([
			'scenario'=>'update',
			'name'=>$role->name,
			'description'=>$role->description,
			'isNewRecord'=>false
		]);

		$ap = Permission::getChildPermissions($role->name);
		$isMaster = $auth->db->createCommand('SELECT COUNT(*) FROM {{%master_role}} WHERE name=:name')
			->bindValue(':name', $id)
			 ->queryScalar();
		if ($isMaster) {
			$model->access = 'all';
		} else {
			$model->access = !$ap ? null : 'custom';
		}

		$model->permissions = json_encode($ap);
		return $model;
	}

	public function save() {
		if (!$this->validate()) return;

		$auth = Yii::$app->authManager;
		$transaction = $auth->db->beginTransaction();
		try {
			// update role's information
			if ($this->isNewRecord) {
				$role = $auth->createRole($this->name);
				$auth->add($role);
			} else {
				$role = $auth->getRole($this->name);
			}
			$role->description = $this->description;
			$role->name = $this->name;
			$auth->update($this->name, $role);

			// remove old assigned permissions
			foreach(Permission::getChildPermissions($role->name) as $permission) {
				$auth->removeChild($role, $auth->getPermission($permission));
			}

			// assign new permissions
			$auth->db->createCommand()->delete('{{%master_role}}', 'name=:name')
				->bindValue(':name', $this->name)
				->execute();
			switch ($this->access) {
				case 'all':
					$auth->db->createCommand()
						->insert('{{%master_role}}', ['name'=>$this->name])
						->execute();				
					$newPermissions = array_column(Permission::getChildPermissions(null), 'name');
					break;
				case 'custom':
					$newPermissions = json_decode($this->permissions, true);
					break;
				
				default:
					$newPermissions = [];
					break;
			}
			foreach ($newPermissions as $permission) {
				$auth->addChild($role, $auth->getPermission($permission));
			}

			$transaction->commit();
		} catch(\Exception $e) {
			$transaction->rollBack();
			throw $e;
			return false;
		}
		return true;
	}

	public function delete() {
		$auth = Yii::$app->authManager;
		$auth->remove($auth->getRole($this->name));
	}
}
