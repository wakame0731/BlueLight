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

use pocketmine\block\Sapling;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\Tree;
use pocketmine\block\Block;
use pocketmine\block\Wood2;

class SmallMountainsBiome extends GrassyBiome{

	public function __construct(){
		parent::__construct();

		$this->setGroundCover([
			Block::get(237, 8),
			Block::get(237, 8),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
			Block::get(13, 0),
		]);

		$trees = new Tree([Block::STONE, Block::COBBLESTONE, Sapling::OAK]);
		$trees->setBaseAmount(2);
		$this->addPopulator($trees);

		$trees2 = new Tree([Block::WOOD2, Block::COAL_ORE, Wood2::DARK_OAK]);
		$trees2->setBaseAmount(1);
		$this->addPopulator($trees2);

		$setter = new Setter([
			[-1, 8, 0, Block::STAINED_CLAY, 9],
			[-1, 8, 1, Block::STAINED_CLAY, 9],
			[-1, 8,-1, Block::STAINED_CLAY, 9],
			[ 0, 8, 0, Block::STAINED_CLAY, 9],
			[ 0, 8, 1, Block::STAINED_CLAY, 9],
			[ 0, 8,-1, Block::STAINED_CLAY, 9],
			[ 1, 8, 0, Block::STAINED_CLAY, 9],
			[ 1, 8, 1, Block::STAINED_CLAY, 9],
			[ 1, 8,-1, Block::STAINED_CLAY, 9],

			[-2, 7, 0, Block::STAINED_CLAY, 9],
			[-2, 7, 1, Block::STAINED_CLAY, 9],
			[-2, 7,-1, Block::STAINED_CLAY, 9],
			[ 2, 7, 0, Block::STAINED_CLAY, 9],
			[ 2, 7, 1, Block::STAINED_CLAY, 9],
			[ 2, 7,-1, Block::STAINED_CLAY, 9],
			[ 0, 7,-2, Block::STAINED_CLAY, 9],
			[ 1, 7,-2, Block::STAINED_CLAY, 9],
			[-1, 7,-2, Block::STAINED_CLAY, 9],
			[ 0, 7, 2, Block::STAINED_CLAY, 9],
			[ 1, 7, 2, Block::STAINED_CLAY, 9],
			[-1, 7, 2, Block::STAINED_CLAY, 9],

			[0, 7, 0, Block::CLAY_BLOCK, 0],
			[0, 6, 0, Block::CLAY_BLOCK, 0],
			[0, 5, 0, Block::CLAY_BLOCK, 0],
			[0, 4, 0, Block::CLAY_BLOCK, 0],
			[0, 3, 0, Block::CLAY_BLOCK, 0],
			[0, 2, 0, Block::CLAY_BLOCK, 0],
			[0, 1, 0, Block::CLAY_BLOCK, 0],
			[0, 0, 0, Block::CLAY_BLOCK, 0],
		], [237 => true]);
		$setter->setBaseAmount(1);

		$this->addPopulator($setter);

		$setter1 = new Setter([
			[ 0, -1, 0, Block::WOOL, 8],
			[ 0, -2, 0, Block::WOOL, 8],
			[ 0, -3, 0, Block::BLACK_GLAZED_TERRACOTTA, 0],
			[-1, -3, 0, Block::WOOL, 8],
			[ 1, -3, 0, Block::WOOL, 8],
			[ 0, -3,-1, Block::WOOL, 8],
			[ 0, -3, 1, Block::WOOL, 8],
			[ 0, -4, 0, Block::WOOL, 8],
		], [237 => true]);
		$setter1->setBaseAmount(1);
		$this->addPopulator($setter1);

		$this->setElevation(35, 90);
	}

	public function getName() : string{
		return "Small Mountains";
	}
}