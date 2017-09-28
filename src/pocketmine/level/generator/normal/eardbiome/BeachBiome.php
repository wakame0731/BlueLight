<?php

namespace pocketmine\level\generator\normal\eardbiome;

use pocketmine\level\generator\populator\Cactus;
use pocketmine\level\generator\populator\DeadBush;

class BeachBiome extends SandyBiome{

	public function __construct(){
		parent::__construct();

		$this->removePopulator(Cactus::class);
		$this->removePopulator(DeadBush::class);
		
		$this->setElevation(25, 52);
	}

	public function getName() : string{
		return "Beach";
	}
} 