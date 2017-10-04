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
use pocketmine\block\Flower as FlowerBlock;
use pocketmine\block\Sapling;
use pocketmine\level\generator\populator\Flower;
use pocketmine\level\generator\populator\LilyPad;
use pocketmine\level\generator\populator\Sugarcane;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\WaterPit;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\Rock;
use pocketmine\level\generator\populator\BigTree;
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\generator\populator\Cave2;

class PlainBiome2 extends GrassyBiome{

	public function __construct(){
		parent::__construct();
		$this->setGroundCover([
			Block::get(Block::GRASS, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
		]);
		$sugarcane = new Sugarcane();
		$sugarcane->setBaseAmount(6);
		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(25);
		$waterPit = new WaterPit();
		$waterPit->setBaseAmount(9999);
		$lilyPad = new LilyPad();
		$lilyPad->setBaseAmount(8);

		$flower = new Flower();
		$flower->setBaseAmount(2);
		$flower->addType([Block::DANDELION, 0]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_POPPY]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_AZURE_BLUET]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_RED_TULIP]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_ORANGE_TULIP]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_WHITE_TULIP]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_PINK_TULIP]);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_OXEYE_DAISY]);
		$trees1 = new Tree(Sapling::OAK);
		$trees1->setBaseAmount(2);
		$trees2 = new BigTree(Sapling::OAK, Sapling::OAK);
		$trees2->setBaseAmount(0);
		$rock = new Rock();
		$rock->setBaseAmount(0);

		/*$setter1 = new Setter([
			[ 0, -1, 0, Block::WOOL, 13],
			[ 0, -2, 0, Block::WOOL, 13],
			[ 0, -3, 0, Block::GREEN_GLAZED_TERRACOTTA, 0],
			[-1, -1, 0, Block::GRASS, 0],
			[ 1, -1, 0, Block::GRASS, 0],
			[ 0, -1,-1, Block::GRASS, 0],
			[ 0, -1, 1, Block::GRASS, 0],
			[-1, -2, 0, Block::DIRT, 0],
			[ 1, -2, 0, Block::DIRT, 0],
			[ 0, -2,-1, Block::DIRT, 0],
			[ 0, -2, 1, Block::DIRT, 0],
			[-1, -3, 0, Block::DIRT, 0],
			[ 1, -3, 0, Block::DIRT, 0],
			[ 0, -3,-1, Block::DIRT, 0],
			[ 0, -3, 1, Block::DIRT, 0],
			[ 0, -4, 0, Block::DIRT, 0],
		], [2 => true]);
		$setter1->setBaseAmount(1);

		$cave = new Cave2(45);
		$cave->setBaseAmount(0);

		$this->addPopulator($cave);

		$this->addPopulator($setter1);*/

		$this->addPopulator($rock);
		$this->addPopulator($trees1);
		$this->addPopulator($trees2);
		$this->addPopulator($sugarcane);
		$this->addPopulator($tallGrass);
		$this->addPopulator($flower);
		//$this->addPopulator($waterPit);
		//$this->addPopulator($lilyPad);

		$this->setElevation(45, 70);

		$this->temperature = 0.8;
		$this->rainfall = 0.4;
	}

	public function getName() : string{
		return "Plains2";
	}
}
