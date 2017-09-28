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

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\block\Liquid;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Rock as ObjectRock;
use pocketmine\utils\Random;

class Rock extends Populator{
	/** @var ChunkManager */
	private $level;
	private $randomAmount;
	private $baseAmount;

	private $id;
	private $data;

	public function __construct($id = Block::STONE, $data = 0, $min = 4, $max = 6, $onLiquid = false){
		$this->id = $id;
		$this->data = $data;
		$this->min = $min;
		$this->max = $max;
		$this->onLiquid = $onLiquid;
	}

	public function setRandomAmount($amount){
		$this->randomAmount = $amount;
	}

	public function setBaseAmount($amount){
		$this->baseAmount = $amount;
	}

	public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random){
		$this->level = $level;
		$amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if($y === -1){
				continue;
			}
			//ObjectTree::growTree($this->level, $x, $y, $z, $random, $this->type);
			//$log, $leaves, $leafType, $type, $treeHeight = 8
			$rock = new ObjectRock($this->id, $this->data, mt_rand($this->min, $this->max)*3);
			$rock->placeObject($this->level, $x, $y, $z);
		}
	}

	private function getHighestWorkableBlock($x, $z){
		for($y = 127; $y > 0; --$y){
			$b = $this->level->getBlockIdAt($x, $y, $z);
			$bl = Block::get($b);
			if(((!$this->onLiquid && $bl->isSolid()) || ($this->onLiquid && $bl instanceof Liquid)) && ($b !== Block::LEAVES && $b !== Block::LEAVES2 && $b !== Block::COBBLE && $b !== 159 && $b !== 99 && $b !== 100)){
				break;
			}elseif($b !== 0 and $b !== Block::SNOW_LAYER){
				return -1;
			}
		}

		return ++$y;
	}
}
