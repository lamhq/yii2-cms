<?php
namespace backend\models;

use ReflectionMethod;
use Yii;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\Query;
use yii\rbac\Item;
use app\components\helpers\AppHelper;

/**
 * Account form
 */
class Permission extends Model
{
	public $name;
	public $description;
	public $childs=[];
	public $parents=[];

	public $isNewRecord = true;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['name', 'required', 'on'=>'insert'],
			[['description'], 'required', 'on'=>['insert', 'update']],
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
			[['description','childs','parents'], 'safe', 'on'=>['insert', 'update']],
		];
	}

	/**
	 * Returns all permissions in the system.
	 * @return yii\rbac\Permission[] all permissions in the system. The array is indexed by the permission names.
	 */
	static public function getPermissions() {
		return AppHelper::singleton('permissions', function () {
			return Yii::$app->authManager->getPermissions();
		});
	}

	/**
	 * Returns an array of permission relation [ ['parent', 'child'],.. ]
	 * @return array
	 */
	static protected function getPermissionRelation() {
		return AppHelper::singleton('permissionRelation', function () {
			$auth = Yii::$app->authManager;
			return (new Query)
				->select('itemChild.*')
				->from($auth->itemChildTable.' AS itemChild')
				->join('INNER JOIN', $auth->itemTable.' AS item', 'itemChild.parent=item.name')
				->where(['item.type'=> Item::TYPE_PERMISSION])
				->all($auth->db);
		});
	}

	/**
	 * Get child permissions of a parent permission. if parent is null, return top permissions
	 * @param string $name the parent name
	 * @return yii\rbac\Permission[]
	 */
	static public function getChildPermissions($parent=null) {
		// find top permissions
		if (!$parent) {
			$childs = array_column(self::getPermissionRelation(), 'child');
			return array_filter(self::getPermissions(), function($item) use ($childs) {
				return !in_array($item->name, $childs);
			});
		}

		// find child permissions
		$childs = array_column(
			array_filter(self::getPermissionRelation(), function($item) use ($parent) {
				return $item['parent'] == $parent;
			}),
			'child');
		return array_filter(self::getPermissions(), function($item) use ($childs) {
			return in_array($item->name, $childs);
		});
	}

	/**
	 * Get all descendant permissions of a permission
	 * @param string $name the parent name
	 * @return yii\rbac\Permission[]
	 */
	static public function getDescendantPermissions($parent) {
		$result = $descendants = self::getChildPermissions($parent);
		foreach ($descendants as $item) {
			$result = array_merge($result, self::getDescendantPermissions($item->name));
		}
		return $result;
	}

	/**
	 * Get all ancestor permissions of a permission
	 * @param string $child the children name
	 * @return yii\rbac\Permission[]
	 */
	static public function getAncestorPermissions($child) {
		$result = $ancestors = self::getParentPermissions($child);
		foreach ($ancestors as $item) {
			$result = array_merge(self::getAncestorPermissions($item->name), $result);
		}
		return $result;
	}

	/**
	 * Get all parent permissions of a permission (a permission may has more than one parent)
	 * @param string $child the children name
	 * @return yii\rbac\Permission[]
	 */
	static public function getParentPermissions($child) {
		$parents = array_column(
			array_filter(self::getPermissionRelation(), function($item) use ($child) {
				return $item['child'] == $child;
			}),
			'parent');
		return array_filter(self::getPermissions(), function($item) use ($parents) {
			return in_array($item->name, $parents);
		});
	}

	/**
	 * Returns an array of permission data [ ['name', 'description', 'childs'],.. ]
	 * @return array
	 */
	static public function getPermissionTree() {
		$buildTree = function ($parent=null) use (&$buildTree) {
			return array_map(function ($item) use ($buildTree) {
				$data = [
					'name' => $item->name,
					'description' => $item->description,
				];
				$data['childs'] = $buildTree($item->name);
				return $data;
			}, self::getChildPermissions($parent) );
		};

		return $buildTree();
	}

	/**
	 * Returns an array of permission data for display in menu widget [ ['label', 'url', 'items'],.. ]
	 * @return array
	 */
	static public function getPermissionMenuItems() {
		$buildTree = function ($parent=null) use (&$buildTree) {
			return array_map(function ($item) use ($buildTree) {
				$data = [
					'label' => $item->description,
					'url' => Url::to(['/backend/permission/index', 'id'=>$item->name])
				];
				$data['items'] = $buildTree($item->name);
				return $data;
			}, self::getChildPermissions($parent) );
		};

		return $buildTree();
	}

	/**
	 * called when delete/update a permission
	 */
	static protected function onPermissionChange() {
		// auto assign top level permissions to master roles
		$auth = Yii::$app->authManager;
		foreach (Role::getMasterRoles() as $role) {
			foreach(Role::getChildPermissions($role->name) as $permission) {
				$auth->removeChild($role, $permission);
			}

			foreach(self::getChildPermissions() as $permission) {
				$auth->addChild($role, $permission);
			}
		}
	}

	/**
	 * Returns the permission model base on the name
	 * @param string $name the permission name
	 * @return backend\model\Permisson the model
	 */
	static public function find($name) {
		$auth = Yii::$app->authManager;
		$permission = $auth->getPermission($name);
		if (!$permission) return false;

		$model = new Permission([
			'scenario'=>'update',
			'name'=>$permission->name,
			'description'=>$permission->description,
			'isNewRecord'=>false
		]);

		$model->childs = array_column(
			array_filter(self::getPermissionRelation(), function($item) use ($model) {
				return $item['parent'] == $model->name;
			}), 'child');
		$model->parents = array_column(
			array_filter(self::getPermissionRelation(), function($item) use ($model) {
				return $item['child'] == $model->name;
			}), 'parent');
		return $model;
	}

	public function save() {
		if (!$this->validate()) return;

		$auth = Yii::$app->authManager;
		$transaction = $auth->db->beginTransaction();
		try {
			// update permission's information
			if ($this->isNewRecord) {
				$permission = $auth->createPermission($this->name);
				$auth->add($permission);
			} else {
				$permission = $auth->getPermission($this->name);
			}
			$permission->description = $this->description;
			$permission->name = $this->name;
			$auth->update($this->name, $permission);

			// remove all child permissions
			foreach(self::getChildPermissions($this->name) as $item) {
				$auth->removeChild($permission, $item);
			}
			// remove all parent permissions
			foreach(self::getParentPermissions($this->name) as $item) {
				$auth->removeChild($item, $permission);
			}

			// assign new childrens
			$this->childs = is_array($this->childs) ? $this->childs : [];
			foreach($this->childs as $item) {
				$auth->addChild($permission, $auth->getPermission($item));
			}
			// assign new parents
			$this->parents = is_array($this->parents) ? $this->parents : [];
			foreach($this->parents as $item) {
				$auth->addChild($auth->getPermission($item), $permission);
			}

			self::onPermissionChange();
			$transaction->commit();
		} catch(\Exception $e) {
			$transaction->rollBack();
			throw $e;
			// return false;
		}
		return true;
	}

	public function delete() {
		$auth = Yii::$app->authManager;
		$transaction = $auth->db->beginTransaction();
		try {
			$auth->remove($auth->getPermission($this->name));
			self::onPermissionChange();
			$transaction->commit();
		} catch(\Exception $e) {
			$transaction->rollBack();
			throw $e;
			// return false;
		}
		return true;
	}

	public function getChildsListData() {
		if ($this->isNewRecord) {
			$permissions = self::getPermissions();
		} else {
			$ancestors = ArrayHelper::getColumn(self::getAncestorPermissions($this->name), 'name');
			$permissions = array_filter(self::getPermissions(), function($item) use ($ancestors) {
				return !in_array($item->name, $ancestors);
			});
		}
		return ArrayHelper::map($permissions, 'name', 'description');
	}

	public function getParentsListData() {
		if ($this->isNewRecord) {
			$permissions = self::getPermissions();
		} else {
			$descendants = ArrayHelper::getColumn(self::getDescendantPermissions($this->name), 'name');
			$permissions = array_filter(self::getPermissions(), function($item) use ($descendants) {
				return !in_array($item->name, $descendants);
			});
		}
		return ArrayHelper::map($permissions, 'name', 'description');
	}
}
