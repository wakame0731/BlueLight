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

class Rock{

	private $id;
	private $data;
	private $size;
	private $height;

	public function __construct($id, $data, $size){
		$this->id = $id;
		$this->data = $data;
		$this->size = $size;
		$this->height = ceil($this->size/3);
	}

	public function placeObject($level, $x, $y, $z){

		$this->set($level, $x, $y+$this->height, $z, 0);
	}

	public function set($level, $x, $y, $z, $count){
		$id = $level->getBlockIdAt($x, $y, $z);
		$data  = $level->getBlockDataAt($x, $y, $z);
		if($y <= 1 || ($count > $this->size && $id !== 0)){
			return false;
		}
		if($id !== $this->id || $data !== $this->data){
			$level->setBlockIdAt($x, $y, $z, $this->id);
			$level->setBlockDataAt($x, $y, $z, $this->data);
		}else{
			++$count;
		}
		$this->set($level, $x, $y-1, $z, $count+1);
		if(mt_rand(0, $count) === 0) $this->set($level, $x+1, $y-1, $z, $count+1);
		if(mt_rand(0, $count) === 0) $this->set($level, $x-1, $y-1, $z, $count+1);
		if(mt_rand(0, $count) === 0) $this->set($level, $x, $y-1, $z+1, $count+1);
		if(mt_rand(0, $count) === 0) $this->set($level, $x, $y-1, $z-1, $count+1);
		return true;
	}
}