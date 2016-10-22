<?php
namespace app\components;

use yii\base\Component;
use Yii;

/**
 * Class Setting
 * @author Lam Huynh <lamhq.com>
 */
class Setting extends Component
{
	public $cacheId='global_website_config';

	public $cachingDuration=3600;

	public $tableName='{{%setting}}';

	protected $_items=array();

	/**
	 * 
	 * @return
	 */
	public function init() {
		$this->loadItems();
		$this->applySetting();
	}

	/**
	 * 
	 * @param mixed $key
	 * @param string $value
	 * @return
	 * 
	 * It will set an item to be available during the request.
	 */
	public function setItem($key, $value='')
	{
		if(is_array($key))
		{
			foreach($key AS $k=>$v)
				$this->setItem($k, $v);
		}
		else
			$this->_items[$key]=$value;
	}

	/**
	 * 
	 * @param mixed $key
	 * @return mixed
	 * 
	 * If the items exists it will be returned
	 */
	public function getItem($key)
	{
		return isset($this->_items[$key]) ? $this->_items[$key] : null;
	}

	/**
	 * 
	 * @return array
	 * 
	 * If the items exists it will be returned
	 */
	public function getItems()
	{
		return $this->_items;
	}

	/**
	 * 
	 * @return
	 * 
	 * It will load all the items from the database
	 * and store them in the memory and cache if it's the case
	 */
	public function saveItems()
	{        
		$names = array_map(function ($item) {
			return "'$item'";
		}, array_keys($this->_items));
		if (!$names) return false;

		// delete existed items in current config
		Yii::$app->db->createCommand()->delete($this->tableName, 
			sprintf("name in (%s)", implode(',', $names))
		)->execute();

		// save new records
		Yii::$app->db->createCommand()->batchInsert(
			$this->tableName,
			['name', 'value'],
			array_map( function ($key, $value) {
				return [$key, serialize($value)];
			}, array_keys($this->_items), array_values($this->_items) )
		)->execute();
		return true;
	}

	/**
	 * 
	 * @return
	 * 
	 * It will load all the items from the database
	 * and store them in the memory and cache if it's the case
	 */
	public function loadItems()
	{        
		$items=false;
		
		$cache = Yii::$app->cache;
		if($this->cacheId)
			$items=$cache->get($this->cacheId);

		if(!$items) {
			// use try catch in case running migration
			try {
				$rows = (new \yii\db\Query())
					->select(['name', 'value'])
					->from($this->tableName)
					->all();
			} catch (\yii\db\Exception $e) {
				$rows = [];
			}

			if(empty($rows))
				return false;

			$items=[];
			foreach($rows as $row) {
				$items[$row['name']] = unserialize($row['value']);
			}

			if($this->cacheId)
				$cache->set($this->cacheId, $items, $this->cachingDuration); 
		}
		$this->setItem($items);
	}

	/**
	 * merge database config to application params
	 */
	public function applySetting() {
		$params = array_merge(Yii::$app->params, $this->getItems());
		Yii::$app->params = $params;
	}
}
