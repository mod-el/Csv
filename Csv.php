<?php namespace Model\Csv;

use Model\Core\Module;

class Csv extends Module
{
	public function export($list, array $columns, array $options = [])
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
			foreach ($columns as $label => $c)
				$row[] = $label;

			fputcsv($f, $row, $options['delimiter'], $options['enclosure']);
		}

		foreach ($list as $element) {
			$form = $element->getForm();
			$row = [];
			foreach ($columns as $label => $c) {
				if (!is_string($c) and is_callable($c)) {
					$value = call_user_func($c, $element);
				} else {
					$value = isset($form[$c]) ? $form[$c]->getText() : $element[$c];
				}
				if ($options['charset'] !== 'UTF-8')
					$value = iconv('UTF-8', $options['charset'] . '//TRANSLIT', $value);
				$row[] = $value;
			}
			fputcsv($f, $row, $options['delimiter'], $options['enclosure']);
		}

		fclose($f);
	}
}
