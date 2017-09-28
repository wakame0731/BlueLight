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

declare(strict_types=1);

namespace pocketmine\level\generator\normal\eardbiome;

use pocketmine\block\Sapling;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\generator\populator\Rock;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class TaigaBiome extends GrassyBiome{

	public function __construct(){
		parent::__construct();

		$this->setGroundCover([
			BlockFactory::get(237, 9),
			BlockFactory::get(237, 9),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
			BlockFactory::get(168, 0),
		]);
		
		//$tallGrass = new TallGrass();
		//$tallGrass->setBaseAmount(5);

		$ice = new Rock(Block::PACKED_ICE, 0, 3, 5, true);
		$ice->setBaseAmount(0);
		$this->addPopulator($ice);

		$setter = new Setter([[0, 0, 0, 38, 1]], [237 => true, 1 => true]);
		$setter->setBaseAmount(5);

		$this->addPopulator($setter);

		$trees = new Tree([Block::WOOD2, Block::PACKED_ICE, Sapling::SPRUCE]);
		$trees->setBaseAmount(1);
		$this->addPopulator($trees);

		$setter2 = new Setter([
			[ 0, 0, 0, Block::SEA_LANTERN, 0],
			[ 0,-1, 1, Block::PACKED_ICE, 0],
			[-1,-1, 0, Block::PACKED_ICE, 0], 
			[ 0,-1, 0, Block::PACKED_ICE, 0], 
			[ 1,-1, 0, Block::PACKED_ICE, 0],
			[ 0,-1,-1, Block::PACKED_ICE, 0],
		], [8 => true, 9 => true]);
		$setter2->setBaseAmount(1);
		$this->addPopulator($setter2);

		$this->setElevation(15, 95);

		$this->temperature = 0.5;
		$this->rainfall = 0.5;
	}

	public function getName() : string{
		return "TaigaBiome";
	}
}