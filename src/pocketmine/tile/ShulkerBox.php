<?php
/*
 *
 *  _____            _               _____
 * / ____|          (_)             |  __ \
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/
 *                         __/ |
 *                        |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/
namespace pocketmine\tile;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
class ShulkerBox extends Spawnable implements Nameable {
	/**
	 * ShulkerBox constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->color) or !($nbt->color instanceof ByteTag)){
			$nbt->color = new ByteTag("color", 10); // default to purple
		}
		parent::__construct($level, $nbt);
	}
	/**
	 * @return int
	 */
	public function getColor() : int{
		return $this->namedtag->color->getValue();
	}
	/**
	 * @param int $color
	 */
	public function setColor(int $color){
		$this->namedtag["color"] = $color & 0x0f;
		$this->onChanged();
	}
	/**
	 * @return string
	 */
	public function getName() : string{
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Shulker Box";
	}
	/**
	 * @return bool
	 */
	public function hasName() : bool{
		return isset($this->namedtag->CustomName);
	}
	/**
	 * @param void $str
	 */
	public function setName(string $str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}
	/**
	 * @return CompoundTag
	 */
	public function getSpawnCompound(): CompoundTag{
		$c = new CompoundTag("", [
			new StringTag("id", Tile::SHULKER_BOX),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			$this->namedtag->color
		]);
		if($this->hasName()){
			$c->CustomName = $this->namedtag->CustomName;
		}
		return $c;
    }

    public function addAdditionalSpawnData(CompoundTag $nbt){
		if($this->hasName()){
			$nbt->color = $this->namedtag->color;
		}
	}
}