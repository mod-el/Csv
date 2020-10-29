<?php namespace Model\Csv;

use Model\Core\Core;

class AdminBridge
{
	protected $model;

	public function __construct(Core $model)
	{
		$this->model = $model;
	}

	public function export(iterable $list, array $columns, array $options = [])
	{
		$generator = $this->makeGenerator($list, $columns);
		$this->model->_Csv->export($generator, $columns, $options);
	}

	private function makeGenerator(iterable $list, array $columns): \Generator
	{
		foreach ($list as $item) {
			$row = [];

			foreach ($columns as $columnId => $column) {
				$itemColumn = $this->model->_Admin->getElementColumn($item['element'], $column);
				$row[$columnId] = $itemColumn ? $itemColumn['text'] : '';
			}

			yield $row;
		}
	}
}
