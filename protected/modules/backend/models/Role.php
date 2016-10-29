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

	/**
	 * Returns all roles in the system.
	 * @return yii\rbac\Role[] all roles in the system. The array is indexed by the role names.
	 */
	static public function getRoles () {
		return Yii::$app->authManager->getRoles();
	}

	/**
	 * Returns all master roles in the system.
	 * Roles in master role table have access to all permissions without manually assign
	 * 
	 * @return yii\rbac\Role[] all roles in the system. The array is indexed by the role names.
	 */
	static public function getMasterRoles() {
		$list = self::getMasterRoleNames();
		return array_filter(self::getRoles(), function ($role) use ($list) {
			return in_array($role->name, $list);
		});
	}

	/**
	 * Returns the names of role in master role table
	 * Roles in master role table have access to all permissions without manually assign
	 * 
	 * @return string the child permissions
	 */
	static protected function getMasterRoleNames() {
		return (new Query)
			->from('{{%master_role}}')
			->column(Yii::$app->authManager->db);
	}

	/**
	 * Returns the child permissions of an auth item.
	 * @param string $name the parent name
	 * @return yii\rbac\Item[] the child permissions
	 */
	static public function getChildPermissions($name) {
		return array_filter(Yii::$app->authManager->getChildren($name), function ($item) {
			return $item->type==Item::TYPE_PERMISSION;
		});
	}

	/**
	 * Returns the role model base on the name
	 * @param string $name the role name
	 * @return backend\model\Role the role model
	 */
	static public function find($name) {
		$auth = Yii::$app->authManager;
		$role = $auth->getRole($name);
		if (!$role) return false;

		$model = new Role([
			'scenario'=>'update',
			'name'=>$role->name,
			'description'=>$role->description,
			'isNewRecord'=>false
		]);

		$assigneds = ArrayHelper::getColumn(
			self::getChildPermissions($role->name)
			, 'name', false);
		$model->permissions = json_encode($assigneds);

		$isMaster = in_array($model->name, self::getMasterRoleNames());
		if ($isMaster) {
			$model->access = 'all';
		} else {
			$model->access = !$assigneds ? null : 'custom';
		}

		return $model;
	}

	/**
	 * Validate and save role to database with selected permissions
	 */
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
			foreach(self::getChildPermissions($role->name) as $permission) {
				$auth->removeChild($role, $permission);
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
					$newPermissions = ArrayHelper::getColumn(Permission::getChildPermissions(), 'name');
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
