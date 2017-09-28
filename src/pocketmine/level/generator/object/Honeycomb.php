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

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\utils\Random;

class Honeycomb{

	public $overridable = [
		Block::AIR => true,
		6 => true,
		17 => true,
		18 => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true,
		Block::LEAVES2 => true
	];

	public function placeObject($level, $x, $y, $z){
		$this->level = $level;
		for($h = 0; $h < 6; $h++){
			switch ($h) {
				case 0:
					$this->set($x, $y-$h, $z);
				break;
				case 1:
					$this->set($x, $y-$h, $z);					
					$this->set($x+1, $y-$h, $z);
					$this->set($x-1, $y-$h, $z);
					$this->set($x, $y-$h, $z+1);
					$this->set($x, $y-$h, $z-1);
				break;
				case 2:
				case 5:
					switch ($h) {
						case 2:
							$level->setBlockIdAt($x, $y-$h, $z, Block::EMERALD_ORE);
							$level->setBlockDataAt($x, $y-$h, $z, 1);
							for ($xx = -1;$xx < 2; $xx++) { 
								for ($zz = -1;$zz < 2; $zz++) { 
									$this->set($x+$xx, $y-$h, $z+$zz);
								}
							}
						break;
						case 5:
							$this->set($x+1, $y-$h, $z+1);
							$this->set($x+1, $y-$h, $z-1);
							$this->set($x-1, $y-$h, $z+1);
							$this->set($x-1, $y-$h, $z-1);
						break;
					}
				case 3:
				case 4:
					for($i = -1; $i <= 1; $i++){
						$this->set($x+2, $y-$h, $z+$i);
						$this->set($x-2, $y-$h, $z+$i);
						$this->set($x+$i, $y-$h, $z+2);
						$this->set($x+$i, $y-$h, $z-2);
					}
				break;
			}
		}
	}

	public function canPlaceObject(ChunkManager $level, $x, $y, $z){
		for($i = 1; $i <= 8; $i++){
			switch (true) {
				case $level->getBlockIdAt($x, $y-$i, $z) !== 0:
				case $level->getBlockIdAt($x+1, $y-$i, $z) !== 0:
				case $level->getBlockIdAt($x-1, $y-$i, $z) !== 0:
				case $level->getBlockIdAt($x, $y-$i, $z+1) !== 0:
				case $level->getBlockIdAt($x, $y-$i, $z-1) !== 0:
					return false;
				break;
			}
		}
		return true;
	}

	public function set($x, $y, $z){
		if(isset($this->overridable[$this->level->getBlockIdAt($x, $y, $z)])){
			$this->level->setBlockIdAt($x, $y, $z, Block::YELLOW_GLAZED_TERRACOTTA);
			$this->level->setBlockDataAt($x, $y, $z, mt_rand(0, 3));
		}
		return true;
	}
}