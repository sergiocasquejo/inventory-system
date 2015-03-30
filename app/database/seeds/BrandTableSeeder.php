<?php

class BrandTableSeeder extends Seeder {

    public function run()
	{
		
		$uoms = [
				['name' => 'CJ FEEDS', 'description' => 'CJ FEEDS'],
				['name' => 'THUNDER BIRD GAME FOWL FEEDS ', 'description' => 'THUNDER BIRD GAME FOWL FEEDS '],
				['name' => 'POULTRY', 'description' => 'POULTRY'],
				['name' => 'KUSOG FEEDS', 'description' => 'KUSOG FEEDS'],
				['name' => 'MEDICINES', 'description' => ''],
			];

		foreach ($uoms as $uom) {
			$measure = new \Brand;
			$measure->name = $uom['name'];
			$measure->description = $uom['description'];
			$measure->save();
		}

	}

}