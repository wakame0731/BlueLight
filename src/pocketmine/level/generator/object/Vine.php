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
use pocketmine\block\BlockFactory;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\utils\Random;

class Vine{

	public function placeObject($level, $pos, $nx, $ny, $nz){
		$this->level = $level;
		$x = $pos->x;
		$y = $pos->y;
		$z = $pos->z;
		$face = $this->getFace($x, $y, $z, $nx, $ny-1, $nz);
		for($my = 0; $my < 9; $my++){
			$result = $this->set($x, $y-$my-1, $z, $face);
			if($my > 3 && (!$result || mt_rand(0, 3) === 1)){
				break;
			}
		}
	}

	public function canPlaceObject($level, $x, $y, $z){
		if(!isset(BlockFactory::$solid[$level->getBlockIdAt($x, $y-1, $z)])){
			return false;
		}
		switch (true) {
			case $level->getBlockIdAt($x+1, $y-1, $z) === 0:
				return new Vector3($x+1, $y-1, $z);
			break;
			case $level->getBlockIdAt($x, $y-1, $z+1) === 0:
				return new Vector3($x, $y-1, $z+1);
			break;
			case $level->getBlockIdAt($x-1, $y-1, $z) === 0:
				return new Vector3($x-1, $y-1, $z);
			break;
			case $level->getBlockIdAt($x, $y-1, $z-1) === 0:
				return new Vector3($x, $y-1, $z-1);
			break;
			default:
				return false;
			break;
		}
		return false;
	}

	public function set($x, $y, $z, $face){
		if($this->level->getBlockIdAt($x, $y, $z) !== 0){
			return false;
		}
		$faces = [
			0 => 0,
			1 => 0,
			2 => 1,
			3 => 4,
			4 => 8,
			5 => 2,
		];
		$meta = $faces[$face];
		$this->level->setBlockIdAt($x, $y, $z, Block::VINE);
		$this->level->setBlockDataAt($x, $y, $z, $meta);
		return true;
	}

	/**
	 * (tx, ty, tz) => 設置する座標(空気)
	 * (nx, ny, nz) => 設置する座標(ブロック)
	 */
	protected function getFace($tx, $ty, $tz, $nx, $ny, $nz){
		switch (true) {
			case $ty < $ny:
				return 0;
			break;
			case $ty > $ny:
				return 1;
			break;
			case $tz < $nz:
				return 2;
			break;
			case $tz > $nz:
				return 3;
			break;
			case $tx < $nx:
				return 4;
			break;
			case $tx > $nx:
				return 5;
			break;
		}
		return 0;
	}
}
