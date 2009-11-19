<?php
/**
 * CDataProvider is a base class that implements the {@link IDataProvider} interface.
 *
 * Derived classes mainly need to implement three methods: {@link fetchData},
 * {@link fetchKeys} and {@link calculateTotalCount}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.web
 * @since 1.1
 */
abstract class CDataProvider extends CComponent implements IDataProvider
{
	private $_id;
	private $_data;
	private $_keys;
	private $_totalCount;
	private $_sort;
	private $_pagination;

	/**
	 * Fetches the data from the persistent data storage.
	 * @return array list of data items
	 */
	abstract protected function fetchData();
	/**
	 * Fetches the data item keys from the persistent data storage.
	 * @return array list of data item keys.
	 */
	abstract protected function fetchKeys();
	/**
	 * Calculates the total number of data items.
	 * @return integer the total number of data items.
	 */
	abstract protected function calculateTotalCount();

	/**
	 * @return string the unique ID that uniquely identifies the data provider among all data providers.
	 */
	public function getId()
	{
		if($this->_id===null)
			$this->_id=$this->modelClass;
		return $this->_id;
	}

	/**
	 * @param string the unique ID that uniquely identifies the data provider among all data providers.
	 */
	public function setId($value)
	{
		$this->_id=$value;
	}

	/**
	 * @return CPagination the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination()
	{
		if($this->_pagination===null)
		{
			$this->_pagination=new CPagination;
			$this->_pagination->pageVar=$this->getId().'_page';
		}
		return $this->_pagination;
	}

	/**
	 * @param mixed the pagination to be used by this data provider. This could be a {@link CPagination} object
	 * or an array used to configure the pagination object. If this is false, it means the pagination should be disabled.
	 */
	public function setPagination($value)
	{
		if(is_array($value))
		{
			$pagination=$this->getPagination();
			foreach($value as $k=>$v)
				$pagination->$k=$v;
		}
		else
			$this->_pagination=$value;
	}

	/**
	 * @return CSort the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort()
	{
		if($this->_sort===null)
		{
			$this->_sort=new CSort;
			$this->_sort->sortVar=$this->getId().'_sort';
		}
		return $this->_sort;
	}

	/**
	 * @param mixed the sorting to be used by this data provider. This could be a {@link CSort} object
	 * or an array used to configure the sorting object. If this is false, it means the sorting should be disabled.
	 */
	public function setSort($value)
	{
		if(is_array($value))
		{
			$sort=$this->getSort();
			foreach($value as $k=>$v)
				$sort->$k=$v;
		}
		else
			$this->_sort=$value;
	}

	/**
	 * Returns the data items currently available.
	 * @param boolean whether the data should be re-fetched from persistent storage.
	 * @return array the list of data items currently available in this data provider.
	 */
	public function getData($refresh=false)
	{
		if($this->_data===null || $refresh)
			$this->_data=$this->fetchData();
		return $this->_data;
	}

	/**
	 * @param array put the data items into this provider.
	 */
	public function setData($value)
	{
		$this->_data=$value;
	}

	/**
	 * Returns the key values associated with the data items.
	 * @param boolean whether the keys should be re-calculated.
	 * @return array the list of key values corresponding to {@link data}. Each data item in {@link data}
	 * is uniquely identified by the corresponding key value in this array.
	 */
	public function getKeys($refresh=false)
	{
		if($this->_keys===null || $refresh)
		{
			if($refresh)
				$this->getData(true);
			$this->_keys=$this->fetchKeys();
		}
		return $this->_keys;
	}

	/**
	 * @param array put the data item keys into this provider.
	 */
	public function setKeys($value)
	{
		$this->_keys=$value;
	}

	/**
	 * Returns the total number of data items.
	 * Note that when pagination is used, this number refers to the total number of data items
	 * without pagination. So it could be greater than
	 * the number of data items returned by {@link data}.
	 * @param boolean whether the total number of data items should be re-calculated.
	 * @return integer total number of possible data items.
	 */
	public function getTotalCount($refresh=false)
	{
		if($this->_totalCount===null || $refresh)
			$this->_totalCount=$this->calculateTotalCount();
		return $this->_totalCount;
	}
}
