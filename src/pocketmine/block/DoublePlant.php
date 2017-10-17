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
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\item\Tool;

class DoublePlant extends Flowable{
	const BITFLAG_TOP = 0x08;

	protected $id = self::DOUBLE_PLANT;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeReplaced():bool{
		if ($this->meta === 2 or $this->meta === 3 or $this->meta === (2 | self::BITFLAG_TOP) or $this->meta === (3 | self::BITFLAG_TOP)){
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	 public function canBeActivated() : bool{
		return true;
	}

	public function getName():string{
		static $names = [
			0 => "Sunflower",
			1 => "Lilac",
			2 => "Double Tallgrass",
			3 => "Large Fern",
			4 => "Rose Bush",
			5 => "Peony"
		];
		return $names[$this->meta & 0x07] ?? "";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $facePos, Player $player = null) : bool{
		$id = $blockReplace->getSide(Vector3::SIDE_DOWN)->getId();
		if(($id === Block::GRASS or $id === Block::DIRT or $id === Block::PODZOL) and $blockReplace->getSide(Vector3::SIDE_UP)->canBeReplaced()){
			$this->getLevel()->setBlock($blockReplace, $this, false, false);
			$this->getLevel()->setBlock($blockReplace->getSide(Vector3::SIDE_UP), Block::get($this->id, $this->meta | self::BITFLAG_TOP), false, false);

			return true;
		}

		return false;
	}

	/**
	 * Returns whether this double-plant has a corresponding other half.
	 * @return bool
	 */
	public function isValidHalfPlant() : bool{
		if($this->meta & self::BITFLAG_TOP){
			$other = $this->getSide(Vector3::SIDE_DOWN);
		}else{
			$other = $this->getSide(Vector3::SIDE_UP);
		}

		return (
			$other->getId() === $this->getId() and
			($other->getDamage() & 0x07) === ($this->getDamage() & 0x07) and
			($other->getDamage() & self::BITFLAG_TOP) !== ($this->getDamage() & self::BITFLAG_TOP)
		);
	}

	public function onUpdate($type){
		
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true && !$this->getSide(0) instanceof DoublePlant){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), false, false);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
		
	}

	public function onBreak(Item $item, Player $player = null):bool{
		if(parent::onBreak($item) and $this->isValidHalfPlant()){
			return $this->getLevel()->setBlock($this->getSide(($this->meta & self::BITFLAG_TOP) !== 0 ? Vector3::SIDE_DOWN : Vector3::SIDE_UP), Block::get(Block::AIR));
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null):bool{
		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){
			if($this->meta === 2 or $this->meta === 3 or $this->meta === (2 | self::BITFLAG_TOP) or $this->meta === (3 | self::BITFLAG_TOP)){
				return false;
			}
			$item->count--;
			$this->level->dropItem($this,Item::get($this->id, $this->meta & 0x07));
			return true;
		}
		return false;
	}

	public function getDrops(Item $item): array{
		if(!$item->isShears() and ($this->meta === 2 or $this->meta === 3 or $this->meta === (2 | self::BITFLAG_TOP) or $this->meta === (3 | self::BITFLAG_TOP))){ //grass or fern
			if(mt_rand(0, 24) === 0){
				return [
					ItemFactory::get(Item::SEEDS, 0, 1)
				];
			}else{
				return [];
			}
		}else{
			return [
				ItemFactory::get($this->id, $this->meta & 0x07, 1)
			];
		}
	}

	public function getAffectedBlocks() : array{
		return [$this, $this->getSide(($this->meta & 0x08) === 0x08 ? Vector3::SIDE_DOWN : Vector3::SIDE_UP)];
	}
}