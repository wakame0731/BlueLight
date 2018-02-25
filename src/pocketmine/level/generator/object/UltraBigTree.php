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

namespace pocketmine\level\generator\object;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\utils\VectorIterator;
use pocketmine\math\VectorMath;

class UltraBigTree extends Tree{

	/** @var Random */
	private $random;
	private $trunkHeightMultiplier = 0.928;
	private $trunkHeight;
	private $leafAmount = 1.2;
	private $leafDistanceLimit = 2;
	private $widthScale = 0.985;
	private $branchSlope = 0.11;

	public $type = 0;
	public $trunkBlock = Block::LOG;
	public $leafBlock = Block::LEAVES;
	public $leafType = 0;

	private $totalHeight;
	private $baseHeight = 25;

	public function __construct($log, $leaves, $leafType, $type, $baseHeight = 35){
		$this->trunkBlock = $log;
		$this->leafBlock = $leaves;
		$this->leafType = $leafType;
		$this->type = $type;
		$this->baseHeight = $baseHeight;
	}

	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random) : bool{
		if(!parent::canPlaceObject($level, $x, $y, $z, $random)){
			return false;
		}
		$base = new Vector3($x, $y, $z);
		$this->totalHeight = $this->baseHeight + $random->nextBoundedInt(12);
		$availableSpace = $this->getAvailableBlockSpace($level, $base, $base->add(0, $this->totalHeight - 1, 0));
		if($availableSpace > $this->baseHeight or $availableSpace == -1){
			if($availableSpace != -1){
				$this->totalHeight = $availableSpace;
			}
			return true;
		}
		return false;
	}

	public function placeObject(ChunkManager $level, int $x, int $y, int $z, Random $random){
		$this->random = $random;
		$this->trunkHeight = (int) ($this->totalHeight * $this->trunkHeightMultiplier);
		$leaves = $this->getLeafGroupPoints($level, $x, $y, $z);
		foreach($leaves as $leaf){
			/** @var Vector3 $leafGroup */
			$leafGroup = $leaf[0];
			$groupX = $leafGroup->getX();
			$groupY = $leafGroup->getY();
			$groupZ = $leafGroup->getZ();
			for($yy = $groupY; $yy < $groupY + $this->leafDistanceLimit; ++$yy){
				$this->generateGroupLayer($level, $groupX, $yy, $groupZ, $this->getLeafGroupLayerSize($yy - $groupY));
			}
		}
		$trunk = new VectorIterator($level, new Vector3($x, $y -1, $z), $lv = new Vector3($x+mt_rand(-8, 8), $y + $this->trunkHeight, $z+mt_rand(-8, 8)));
		while($trunk->valid()){
			$trunk->next();
			$pos = $trunk->current();
			$level->setBlockIdAt((int) $pos->x, (int) $pos->y, (int) $pos->z, $this->trunkBlock);
			$level->setBlockdataAt((int) $pos->x, (int) $pos->y, (int) $pos->z, $this->type);
			for ($i = 0; $i <= 1; $i++) { 
				$level->setBlockIdAt((int) $pos->x+$i, (int) $pos->y, (int) $pos->z+!$i, $this->trunkBlock);
				$level->setBlockdataAt((int) $pos->x+$i, (int) $pos->y, (int) $pos->z+!$i, $this->type);

				$level->setBlockIdAt((int) $pos->x-$i, (int) $pos->y, (int) $pos->z-!$i, $this->trunkBlock);
				$level->setBlockdataAt((int) $pos->x-$i, (int) $pos->y, (int) $pos->z-!$i, $this->type);
			}
		}
		$this->generateBranches($level, $lv->x, $y, $lv->z, $leaves);
	}

	private function getLeafGroupPoints(ChunkManager $level, $x, $y, $z){
		$amount = $this->leafAmount * $this->totalHeight / 43;
		$groupsPerLayer = (int) (1.382 + $amount * $amount);

		if($groupsPerLayer == 0){
			$groupsPerLayer = 1;
		}

		$trunkTopY = $y + $this->trunkHeight;
		$groups = [];
		$groupY = $y + $this->totalHeight - $this->leafDistanceLimit;
		$groups[] = [new Vector3($x, $groupY, $z), $trunkTopY];

		for($currentLayer = (int) ($this->totalHeight - $this->leafDistanceLimit); $currentLayer >= 0; $currentLayer--){
			$layerSize = $this->getRoughLayerSize($currentLayer);

			if($layerSize < 0){
				$groupY--;
				continue;
			}

			for($count = 0; $count < $groupsPerLayer; $count++){
				$scale = $this->widthScale * $layerSize * ($this->random->nextFloat() + 0.328);
				$randomOffset = VectorMath::getDirection2D($this->random->nextFloat() * 2 * pi())->multiply($scale);
				$groupX = (int) ($randomOffset->getX() + $x + 0.5);
				$groupZ = (int) ($randomOffset->getY() + $z + 0.5);
				$group = new Vector3($groupX, $groupY, $groupZ);
				if($this->getAvailableBlockSpace($level, $group, $group->add(0, $this->leafDistanceLimit, 0)) != -1){
					continue;
				}
				$xOff = (int) ($x - $groupX);
				$zOff = (int) ($z - $groupZ);
				$horizontalDistanceToTrunk = sqrt($xOff * $xOff + $zOff * $zOff);
				$verticalDistanceToTrunk = $horizontalDistanceToTrunk * $this->branchSlope;
				$yDiff = (int) ($groupY - $verticalDistanceToTrunk);
				if($yDiff > $trunkTopY){
					$base = $trunkTopY;
				}else{
					$base = $yDiff;
				}
				if($this->getAvailableBlockSpace($level, new Vector3($x, $base, $z), $group) == -1){
					$groups[] = [$group, $base];
				}
			}
			$groupY--;
		}
		return $groups;
	}

	private function getLeafGroupLayerSize(int $y){
		if($y >= 0 and $y < $this->leafDistanceLimit){
			return (int) (($y != ($this->leafDistanceLimit - 1)) ? 3 : 2);
		}
		return -1;
	}

	private function generateGroupLayer(ChunkManager $level, int $x, int $y, int $z, int $size){
		for($xx = $x - $size; $xx <= $x + $size; $xx++){
			for($zz = $z - $size; $zz <= $z + $size; $zz++){
				$sizeX = abs($x - $xx) + 0.5;
				$sizeZ = abs($z - $zz) + 0.5;
				if(($sizeX * $sizeX + $sizeZ * $sizeZ) <= ($size * $size)){
					if(isset($this->overridable[$level->getBlockIdAt((int) $xx, (int) $y, (int) $zz)])){
						$level->setBlockIdAt((int) $xx, (int) $y, (int) $zz, $this->leafBlock);
						$level->setBlockDataAt((int) $xx, (int) $y, (int) $zz, $this->leafType);						
					}
				}
			}
		}
	}

	private function getRoughLayerSize(int $layer) : float {
		$halfHeight = $this->totalHeight / 2;
		if($layer < ($this->totalHeight / 3)){
			return -1;
		}elseif($layer == $halfHeight){
			return $halfHeight / 4;
		}elseif($layer >= $this->totalHeight or $layer <= 0){
			return 0;
		}else{
			return sqrt($halfHeight * $halfHeight - ($layer - $halfHeight) * ($layer - $halfHeight)) / 2;
		}
	}

	private function generateBranches(ChunkManager $level, int $x, int $y, int $z, array $groups){
		foreach($groups as $group){
			$baseY = $group[1];
			if(($baseY - $y) >= ($this->totalHeight - $this->trunkHeight)){
				$base = new Vector3($x, $baseY, $z);
				$branch = new VectorIterator($level, $base, $group[0]);
				while($branch->valid()){
					$branch->next();
					$pos = $branch->current();
					$level->setBlockIdAt((int) $pos->x, (int) $pos->y, (int) $pos->z, $this->trunkBlock);
					$level->setBlockDataAt((int) $pos->x, (int) $pos->y, (int) $pos->z, $this->type);
					if($this->type === Sapling::JUNGLE && mt_rand(0, 3) === 1){
						$this->setCocoa($level, (int) $pos->x, (int) $pos->y, (int) $pos->z);
					}
				}
			}
		}
	}

	private function getAvailableBlockSpace(ChunkManager $level, Vector3 $from, Vector3 $to){
		$count = 0;
		$iter = new VectorIterator($level, $from, $to);
		while($iter->valid()){
			$iter->next();
			$pos = $iter->current();
			if(!isset($this->overridable[$level->getBlockIdAt((int) $pos->x, (int) $pos->y, (int) $pos->z)])){
				return $count;
			}
			$count++;
		}
		return -1;
	}

	protected function setCocoa(ChunkManager $level, $x, $y, $z){
		$id = $level->getBlockIdAt((int) $x, (int) $y, (int) $z);
		$data = $level->getBlockDataAt((int) $x, (int) $y, (int) $z);
		$s = (mt_rand(0, 1)*2)-1;
		if(mt_rand(0, 1)){
			$sx = (int) ($x + $s);
			$sz = (int) $z;
		}else{
			$sx = (int) $x;
			$sz = (int) ($z + $s);
		}
		if($level->getBlockIdAt($sx, $y, $sz) !== 0){
			return false;
		}
		$face = $this->getFace($sx, $y, $sz, $x, $y, $z);
		if($face !== 0 and $face !== 1){
			$faces = [
				2 => 8,
				3 => 10,
				4 => 11,
				5 => 9,
			];
			$meta = $faces[$face];
			$level->setBlockIdAt($sx, $y, $sz, Block::COCOA_BLOCK);
			$level->setBlockDataAt($sx, $y, $sz, $meta);
			return true;
		}
		return false;	
	}

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