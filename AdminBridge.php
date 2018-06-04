<?php namespace Model\Csv;

use Model\AdminFront\DataVisualizer;
use Model\Core\Core;

class AdminBridge
{
	protected $model;

	public function __construct(Core $model)
	{
		$this->model = $model;
	}

	public function export($list, DataVisualizer $visualizer, array $options = [])
	{
		$columns = $visualizer->getStandardColumns();
		$this->model->_Csv->export($list, $columns, $options);
	}
}
