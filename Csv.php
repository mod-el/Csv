<?php namespace Model\Csv;

use Model\Core\Module;

class Csv extends Module
{
	public function export(iterable $list, array $columns, array $options = [])
	{
		$options = array_merge([
			'target' => 'php://output',
			'header' => true,
			'delimiter' => ',',
			'enclosure' => '"',
			'charset' => 'UTF-8',
		], $options);

		$f = fopen($options['target'], 'w+');

		if ($options['header']) {
			$row = [];
			foreach ($columns as $column)
				$row[] = $column['label'];

			fputcsv($f, $row, $options['delimiter'], $options['enclosure']);
		}

		foreach ($list as $item) {
			$row = [];
			foreach ($columns as $columnId => $column) {
				$value = $item[$columnId] ?? '';
				if ($options['charset'] !== 'UTF-8')
					$value = iconv('UTF-8', $options['charset'] . '//TRANSLIT', $value);
				$row[] = $value;
			}
			fputcsv($f, $row, $options['delimiter'], $options['enclosure']);
		}

		fclose($f);
	}
}
