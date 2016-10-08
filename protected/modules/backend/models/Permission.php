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

	static public function getPermissions() {
		$key = 'tmp-pers';
		if (!isset(Yii::$app->params[$key])) {
			Yii::$app->params[$key] = Yii::$app->authManager->getPermissions();
		}
		return Yii::$app->params[$key];
	}

	static protected function getPermissionRelation() {
		$key = 'tmp-per-relation';
		if (!isset(Yii::$app->params[$key])) {
			$auth = Yii::$app->authManager;
			Yii::$app->params[$key] = (new Query)
				->select('itemChild.*')
				->from($auth->itemChildTable.' AS itemChild')
				->join('INNER JOIN', $auth->itemTable.' AS item', 'itemChild.parent=item.name')
				->where(['item.type'=> Item::TYPE_PERMISSION])
				->all($auth->db);
		}
		return Yii::$app->params[$key];
	}

	// get child permissions. if parent is null, return top permissions
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

	// get parent permissions.
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

	// get all permissions in hierarchy
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

	static protected function onPermissionChange() {
		// re-assign top permissions to master roles
		$auth = Yii::$app->authManager;
		foreach (Role::getMasterRoles() as $role) {
			foreach(Role::getAssignedPermissions($role->name) as $permission) {
				$auth->removeChild($role, $permission);
			}

			foreach(self::getChildPermissions() as $permission) {
				$auth->addChild($role, $permission);
			}
		}
	}

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

}
