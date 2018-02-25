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
use pocketmine\level\generator\populator\Tree;
use pocketmine\level\generator\populator\UltraBigTree;
use pocketmine\level\generator\populator\Setter;
use pocketmine\level\generator\populator\Honeycomb;
use pocketmine\level\generator\populator\Vine;
use pocketmine\level\generator\populator\LilyPad;
use pocketmine\block\Block;

class ForestBiome extends GrassyBiome{

	const TYPE_NORMAL = 0;
	const TYPE_BIRCH = 1;

	public $type;

	public function __construct($type = self::TYPE_NORMAL){
		parent::__construct();

		$this->type = $type;

		$trees1_1 = new UltraBigTree(Sapling::OAK, Sapling::OAK);
		$trees1_1->setBaseAmount(2);
		$this->addPopulator($trees1_1);
/*		$trees1_2 = new Tree(Sapling::OAK);
		$trees1_2->setBaseAmount(3);
		$this->addPopulator($trees1_2);
		$trees2 = new Tree(Sapling::SPRUCE);
		$trees2->setBaseAmount(2);
		$this->addPopulator($trees2);
		$trees3_1 = new BigTree(Sapling::BIRCH, Sapling::BIRCH);
		$trees3_1->setBaseAmount(0);
		$this->addPopulator($trees3_1);
		$trees3_2 = new Tree(Sapling::BIRCH);
		$trees3_2->setBaseAmount(1);
		$this->addPopulator($trees3_2);
		$trees4_1 = new BigTree(Sapling::JUNGLE, Sapling::JUNGLE);
		$trees4_1->setBaseAmount(0);
		$this->addPopulator($trees4_1);
		$trees4_2 = new Tree(Sapling::JUNGLE);
		$trees4_2->setBaseAmount(1);
		$this->addPopulator($trees4_2);*/

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(3);

		$this->addPopulator($tallGrass);

		$lilyPad = new LilyPad();
		$lilyPad->setBaseAmount(5);

		$this->addPopulator($lilyPad);

		$setter1 = new Setter([[0, 0, 0, 39, 0]], [2 => true, 3 => true]);
		$setter1->setBaseAmount(3);
		$this->addPopulator($setter1);

		$setter2 = new Setter([[0, 0, 0, 40, 0]], [2 => true, 3 => true]);
		$setter2->setBaseAmount(3);
		$this->addPopulator($setter2);

		$setter3 = new Setter([
			[ 0, 1, 0, Block::GLOWSTONE, 0],
			[ 0, 0, 1, Block::LEAVES, Sapling::JUNGLE],
			[-1, 0, 0, Block::LEAVES, Sapling::JUNGLE], 
			[ 0, 0, 0, Block::LOG, Sapling::JUNGLE], 
			[ 1, 0, 0, Block::LEAVES, Sapling::JUNGLE],
			[ 0, 0,-1, Block::LEAVES, Sapling::JUNGLE],
		], [2 => true, 3 => true]);
		$setter3->setBaseAmount(1);
		$this->addPopulator($setter3);
		
		$setter4 = new Setter([
			[ 0, 2, 0, Block::GLOWSTONE, 0],
			[ 0, 1, 1, Block::LEAVES, Sapling::JUNGLE],
			[-1, 1, 0, Block::LEAVES, Sapling::JUNGLE], 
			[ 0, 1, 0, Block::LOG, Sapling::JUNGLE], 
			[ 1, 1, 0, Block::LEAVES, Sapling::JUNGLE],
			[ 0, 1,-1, Block::LEAVES, Sapling::JUNGLE],
			[ 0, 0, 0, Block::FENCE, Sapling::DARK_OAK],
		], [2 => true, 3 => true]);
		$setter4->setBaseAmount(1);
		$this->addPopulator($setter4);

/*		$setter5 = new Setter([
			[-1, 9, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 9, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 9,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 9, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 9, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 9,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 9, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 9, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 9,-1, Block::RED_MUSHROOM_BLOCK, 14],

			[-2, 8, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[-2, 8, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[-2, 8,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 2, 8, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[ 2, 8, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 2, 8,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 8,-2, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 8,-2, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 8,-2, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 8, 2, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 8, 2, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 8, 2, Block::RED_MUSHROOM_BLOCK, 14],

			[ 2, 7, 2, Block::RED_MUSHROOM_BLOCK, 14],
			[-2, 7, 2, Block::RED_MUSHROOM_BLOCK, 14],
			[ 2, 7,-2, Block::RED_MUSHROOM_BLOCK, 14],
			[-2, 7,-2, Block::RED_MUSHROOM_BLOCK, 14],
			[-3, 7, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[-3, 7, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[-3, 7,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 3, 7, 0, Block::RED_MUSHROOM_BLOCK, 14],
			[ 3, 7, 1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 3, 7,-1, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 7,-3, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 7,-3, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 7,-3, Block::RED_MUSHROOM_BLOCK, 14],
			[ 0, 7, 3, Block::RED_MUSHROOM_BLOCK, 14],
			[ 1, 7, 3, Block::RED_MUSHROOM_BLOCK, 14],
			[-1, 7, 3, Block::RED_MUSHROOM_BLOCK, 14],

			[0, 8, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 7, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 6, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 5, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 4, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 3, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 2, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 1, 0, Block::RED_MUSHROOM_BLOCK, 10],
			[0, 0, 0, Block::RED_MUSHROOM_BLOCK, 10],
		], [2 => true, 3 => true]);
		$setter5->setBaseAmount(1);

		$this->addPopulator($setter5);

		$setter6 = new Setter([
			[-1, 11, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 11, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 11,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 11, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 11, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 11,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 11, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 11, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 11,-1, Block::BROWN_MUSHROOM_BLOCK, 14],

			[-2, 11, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-2, 11, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-2, 11,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 2, 11, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 2, 11, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 2, 11,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 11,-2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 11,-2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 11,-2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 11, 2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 11, 2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 11, 2, Block::BROWN_MUSHROOM_BLOCK, 14],

			[ 2, 10, 2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-2, 10, 2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 2, 10,-2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-2, 10,-2, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-3, 10, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-3, 10, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-3, 10,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 3, 10, 0, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 3, 10, 1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 3, 10,-1, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 10,-3, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 10,-3, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 10,-3, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 0, 10, 3, Block::BROWN_MUSHROOM_BLOCK, 14],
			[ 1, 10, 3, Block::BROWN_MUSHROOM_BLOCK, 14],
			[-1, 10, 3, Block::BROWN_MUSHROOM_BLOCK, 14],

			[0, 10, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 9, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 8, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 7, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 6, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 5, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 4, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 3, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 2, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 1, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
			[0, 0, 0, Block::BROWN_MUSHROOM_BLOCK, 10],
		], [2 => true, 3 => true]);
		$setter6->setBaseAmount(1);

		$this->addPopulator($setter6);

		$comb = new Honeycomb();
		$comb->setBaseAmount(0);

		$this->addPopulator($comb);

		$vine = new Vine();
		$vine->setBaseAmount(7);

		$this->addPopulator($vine);*/

		$this->setElevation(70, 130);

		if($type === self::TYPE_BIRCH){
			$this->temperature = 0.6;
			$this->rainfall = 0.5;
		}else{
			$this->temperature = 0.7;
			$this->rainfall = 0.8;
		}
	}

	public function getName() : string{
		return $this->type === self::TYPE_BIRCH ? "Birch Forest" : "Forest";
	}
}