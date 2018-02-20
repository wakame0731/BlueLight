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

namespace pocketmine\level\generator\populator;

use pocketmine\block\BlockFactory;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\normal\eardbiome\Biome;
use pocketmine\utils\Random;

class EardGroundCover extends Populator{

	public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random){
		$chunk = $level->getChunk($chunkX, $chunkZ);
		for($x = 0; $x < 16; ++$x){
			for($z = 0; $z < 16; ++$z){
				$biome = Biome::getBiome($chunk->getBiomeId($x, $z));
				$cover = $biome->getGroundCover();
				if(count($cover) > 0){
					$diffY = 0; 
					if(!$cover[0]->isSolid()){
						$diffY = 1;
					}

					$column = $chunk->getBlockIdColumn($x, $z);
					for($y = 255; $y > 0; --$y){
						if($column{$y} !== "\x00" and !BlockFactory::get(ord($column{$y}))->isTransparent()){
							break;
						}
					}
					$startY = min(255, $y + $diffY);
					$endY = $startY - count($cover);
					for($y = $startY; $y > $endY and $y >= 0; --$y){
						$b = $cover[$startY - $y];
						if($column{$y} === "\x00" and isset(BlockFactory::$solid[$chunk->getBlockId($x, $y, $z)])){
							$startY = $y-1;
							continue;
						}
						if($b->getId() == Block::SNOW_LAYER && $y <= 50){
							if($y == 50){
								$chunk->setBlockId($x, $y, $z, Block::ICE);
							}else{
								$chunk->setBlockId($x, $y, $z, Block::WATER);
							}
						}else if($b->getId() == Block::GRASS && $y < 96){
							$chunk->setBlockId($x, $y, $z, Block::DIRT);
						}else if($b->getId() == Block::CONCRETE_POWDER && $y <= 50){
							$chunk->setBlock($x, $y, $z, Block::CONCRETE, $b->getDamage());
						}else{
							if($b->getDamage() === 0){
								$chunk->setBlockId($x, $y, $z, $b->getId());
							}else{
								$chunk->setBlock($x, $y, $z, $b->getId(), $b->getDamage());
							}
						}
					}
				}
			}
		}
	}
}