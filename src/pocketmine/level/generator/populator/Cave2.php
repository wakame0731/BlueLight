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
use pocketmine\utils\Random;

class Cave2 extends Populator{
	/** @var ChunkManager */
	private $level;
	private $randomAmount;
	private $baseAmount;

	private $id;
	private $data;

	public function __construct($size = 20){
		$this->size = $size;
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
			if($y === -1 || mt_rand(0, 8) !== 1){
				continue;
			}
			//ObjectTree::growTree($this->level, $x, $y, $z, $random, $this->type);
			//$log, $leaves, $leafType, $type, $treeHeight = 8
			new CreateCave($x, $y, $z, $this->level, $this->size+mt_rand(-3, 3));
		}
	}

	private function getHighestWorkableBlock($x, $z){
		for($y = 127; $y > 0; --$y){
			$b = $this->level->getBlockIdAt($x, $y, $z);
			$bl = Block::get($b);
			if(($bl->isSolid() || $bl instanceof Liquid) && ($b !== Block::LEAVES && $b !== Block::LEAVES2 && $b !== Block::COBBLE && $b !== Block::OBSIDIAN && $b !== 159 && $b !== 99 && $b !== 100)){
				break;
			}elseif($b !== 0 and $b !== Block::SNOW_LAYER){
				return -1;
			}
		}

		return ++$y;
	}
}

class CreateCave{
	
	public function __construct($x, $y, $z, $level, $loop, $block = 0){

		$this->lavas = [];
		$this->level = $level;
		$this->setCave($x, $y, $z, $level, $loop, $block, mt_rand(6, 12)/3);
	}

	public function setLava(){

		foreach($this->lavas as $k => $value){
			list($x, $y, $z) = $this->lavas[$k];
			$v = (mt_rand()%2)*2-1;
			$ran = mt_rand(0, 1);
			$id = 0;
			$i = 0;

			while($i != 100){
				 
				if($ran){
					$bi = $this->level->getBlockIdAt($x+$v*$i, $y, $z);

					if($bi != 0){
						$this->level->setBlockIdAt($x+$v*$i, $y, $z, 10);
						break;
					}
				}
				else{

					$bi = $this->level->getBlockIdAt($x, $y, $z+$v*$i);

					if($bi != 0){
						$this->level->setBlockIdAt($x, $y, $z+$v*$i, 10);
						break;
					}
				}

				$i++;
			}
		}

		$this->lavas = [];
	}

	public function setCave($x, $y, $z, $level, $loop, $block, $r){

		if($loop >= 0){
			$r += mt_rand(-3, 3)/3;
			if($r < 1.5){
				$r = 1.5;
			}else if($r > 4){
				$r = 4;
			}
			$this->delBall($x, $y, $z, $level, $r, $block, $x, $y, $z);
			$vx = ((mt_rand()%2)*2-1)*mt_rand(1, 4);
			$vy = (-0.5-mt_rand(1, 2)/3)*mt_rand(1, 2);
			$vz = ((mt_rand()%2)*2-1)*mt_rand(1, 4);

			if($level->getBlockIdAt($x+$vx*2, $y+$vy*2, $z+$vz*2) != 0){
				$this->setCave($x+$vx, $y+$vy, $z+$vz, $level, $loop-1, $block, $r);
			}
			else{
				$this->setCave($x, $y, $z, $level, $loop-2, $block, $r);
			}

			if(mt_rand(1, 5) == 1){
				$vx = ((mt_rand()%2)*2-1)*3;
				$vy = mt_rand(-1, 0)*mt_rand(3, 4);
				$vz = ((mt_rand()%2)*2-1)*3;

				if($level->getBlockIdAt($x+$vx*2, $y+$vy*2, $z+$vz*2) != 0){
					$this->setCave($x+$vx, $y+$vy, $z+$vz, $level, $loop-2, $block, $r);
				}
			}

			if(mt_rand(1, 50) == 1){
				$this->lavas[] = [$x, $y, $z];
			}
		}
	}

	public function delBall($x, $y, $z, $level, $r, $block, $sx, $sy, $sz, $cont = 0){
		$id = $level->getBlockIdAt($x, $y, $z);
		if($id != 0 && $id != 7 && $id != 4 && $id !== Block::OBSIDIAN && $y > 2){
			if($id === 8 || $id === 9){
				$level->setBlockIdAt($x, $y, $z, 4);
				$cont++;
			}else{
				$level->setBlockIdAt($x, $y, $z, $block);
			}
			$d = sqrt(pow($x-$sx, 2)+pow($y-$sy, 2)+pow($z-$sz, 2));
			if(($d < $r || (($id === 8 || $id === 9) && $d < $r*4)) && $cont <= 2){
				$this->delBall($x+1, $y, $z, $level, $r, $block, $sx, $sy, $sz, $cont);
				$this->delBall($x-1, $y, $z, $level, $r, $block, $sx, $sy, $sz, $cont);
				$this->delBall($x, $y+1, $z, $level, $r, $block, $sx, $sy, $sz, $cont);
				$this->delBall($x, $y-1, $z, $level, $r, $block, $sx, $sy, $sz, $cont);
				$this->delBall($x, $y, $z+1, $level, $r, $block, $sx, $sy, $sz, $cont);
				$this->delBall($x, $y, $z-1, $level, $r, $block, $sx, $sy, $sz, $cont);
			}
		}
	}
}