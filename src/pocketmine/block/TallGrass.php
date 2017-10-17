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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

class TallGrass extends Flowable {

	const NORMAL = 1;
	const FERN = 2;

	const BITFLAG_TOP = 0x08;

	protected $id = self::TALL_GRASS;

	/**
	 * TallGrass constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 1){
		$this->meta = $meta;
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced():bool{
		return true;
	}

	/**
	 * @return bool
	 */
	public function canBeActivated() : bool{
		return true;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			0 => "Dead Shrub",
			1 => "Tall Grass",
			2 => "Fern",
			3 => ""
		];
		return $names[$this->meta & 0x03];
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $facePos, Player $player = null):bool{
		$down = $this->getSide(0);
		if($down->getId() === Block::GRASS or $down->getId()=== Block::DIRT or $down->getId() === Block::PODZOL){
			$this->getLevel()->setBlock($blockReplace, $this, true);
			
			return true;
		}

		return false;
	}


	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), false, false);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}


	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null):bool{
		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F and $this->getLevel()->getBlock($this->getSide(Vector3::SIDE_UP))->getId() === 0){
			$damage=($this->meta)+1;
			$item->count--;
			$this->getLevel()->setBlock($this,Block::get(Item::DOUBLE_PLANT,$damage), true);
			$this->getLevel()->setBlock($this->getSide(Vector3::SIDE_UP), Block::get(Item::DOUBLE_PLANT, $damage | self::BITFLAG_TOP), true);
			return true;
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getToolType():int{
		return Tool::TYPE_SHEARS;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if(mt_rand(0, 15) === 0){
			return [
				ItemFactory::get(Item::WHEAT_SEEDS, 0, 1)
			];
		}

		return [];
	}

}
