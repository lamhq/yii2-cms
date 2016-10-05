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
		$this->createTable();
		$this->loadItems();
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
			$rows = (new \yii\db\Query())
				->select(['name', 'value'])
				->from($this->tableName)
				->all();

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
	 * 
	 * @return
	 * 
	 * If the database table doesn't exists, it will create it
	 */
	private function createTable()
	{
		$sql='CREATE TABLE IF NOT EXISTS '.$this->tableName.' (
		  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `value` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
		Yii::$app->db->createCommand($sql)->execute();
	}

	public function applySetting() {
		// merge database config to application params
		$params = array_merge(Yii::$app->params, $this->getItems());
		Yii::$app->params = $params;
	}
}
