<?php

class MeasureTableSeeder extends Seeder {

    public function run()
	{
		
		$uoms = [
				['name' => 'kg', 'label' => 'Kilogram'],
				['name' => 'pack', 'label' => 'Pack'],
				['name' => 'pcs', 'label' => 'Pieces'],
				['name' => 'sacks', 'label' => 'Sacks'],
				['name' => 'gram', 'label' => 'Gram(s)'],
				['name' => 'bottle', 'label' => 'Bottle'],
				['name' => 'ml', 'label' => 'Milliliter'],
			];

		foreach ($uoms as $uom) {
			$measure = new \UnitOfMeasure;
			$measure->name = $uom['name'];
			$measure->label = $uom['label'];
			$measure->save();
		}

	}

}