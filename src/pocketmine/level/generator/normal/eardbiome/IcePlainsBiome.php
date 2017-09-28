<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\level\generator\normal\eardbiome;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Rock;

class IcePlainsBiome extends SnowyBiome{

	public function __construct(){
		parent::__construct();

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(5);
		$this->addPopulator($tallGrass);

		$ice = new Rock(Block::PACKED_ICE, 0, 6, 14, false);
		$ice->setBaseAmount(0);
		$this->addPopulator($ice);

		$this->setElevation(40, 62);

		$trees2 = new Tree(Sapling::SPRUCE);
		$trees2->setBaseAmount(2);
		$this->addPopulator($trees2);

		$setter = new Setter([
			[ 0, 0, 0, Block::SNOW_LAYER, 0],
			[ 0, -1, 0, Block::PACKED_ICE, 0],
			[ 0, -2, 0, Block::CYAN_GLAZED_TERRACOTTA, 0],
			[-1, -2, 0, Block::PACKED_ICE, 0],
			[ 1, -2, 0, Block::PACKED_ICE, 0],
			[ 0, -2,-1, Block::PACKED_ICE, 0],
			[ 0, -2, 1, Block::PACKED_ICE, 0],
			[ 0, -3, 0, Block::PACKED_ICE, 0],
		], [2 => true, 3 => true]);
		$setter->setBaseAmount(1);
		$this->addPopulator($setter);

		$setter1 = new Setter([
			[ 0, 0, 0, Block::SNOW_LAYER, 0],
			[ 0, -1, 0, Block::PACKED_ICE, 0],
			[ 0, -2, 0, Block::LIGHT_BLUE_GLAZED_TERRACOTTA, 0],
			[-1, -2, 0, Block::PACKED_ICE, 0],
			[ 1, -2, 0, Block::PACKED_ICE, 0],
			[ 0, -2,-1, Block::PACKED_ICE, 0],
			[ 0, -2, 1, Block::PACKED_ICE, 0],
			[ 0, -3, 0, Block::PACKED_ICE, 0],
		], [2 => true, 3 => true]);
		$setter1->setBaseAmount(1);

		$this->addPopulator($setter1);

		$this->temperature = 0.05;
		$this->rainfall = 0.8;
	}

	public function getName() : string{
		return "Ice Plains";
	}
}