<?php
namespace app\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;

class TreeCheckbox extends InputWidget
{
	public $tree;

	public function run() {
		$this->registerClientScript();
		return $this->render('tree-checkbox');
	}

	public function getInputTag() {
		return $this->hasModel() ? 
			Html::activeHiddenInput($this->model, $this->attribute, $this->options) :
			Html::hiddenInput($this->name, $this->value, $this->options);
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript() {
		$view = $this->getView();
		\app\assets\TreeCheckbox::register($view);

		$model = $this->model;
		$attribute = $this->attribute;
		$value = json_decode($model->$attribute, true);
		$treeJson = json_encode( self::convertTreeToJstreeData($this->tree, $value) );
		$this->id = 'tree'.time().rand(0, 9);
		$view->registerJs("$('#{$this->id}').treeCheckbox({$treeJson});");
	}

	static protected function convertTreeToJstreeData($permissions, $selecteds) {
		$items = array();
		foreach ($permissions as $permission) {
			$name = $permission['name'];
			$checked = in_array($name, $selecteds);

			$item = array(
				'id' => $name,
				'icon' => false,
				'text'=>$permission['description'],
				'state'=>array(
					'selected' => $checked,
					'opened' => $checked
				),
			);
			if (isset($permission['childs'])) {
				$childItems = self::convertTreeToJstreeData($permission['childs'], $selecteds);
				if ($childItems) {
					foreach ($childItems as $c) {
						if ($c['state']['opened'])
							$item['state']['opened'] = true;
					}
					$item['children'] = $childItems;
				}
			}
			$items[] = $item;
		}
		return $items;
	}

}
