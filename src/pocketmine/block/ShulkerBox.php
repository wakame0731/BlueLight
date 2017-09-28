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
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/
namespace pocketmine\block;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\ShulkerBox as TileShulkerBox;
use pocketmine\tile\Tile;
use pocketmine\math\Vector3;
class ShulkerBox extends Transparent {
	const WHITE = 0;
	const ORANGE = 1;
	const MAGENTA = 2;
	const LIGHT_BLUE = 3;
	const YELLOW = 4;
	const LIME = 5;
	const PINK = 6;
	const GRAY = 7;
	const LIGHT_GRAY = 8;
	const CYAN = 9;
	const PURPLE = 10;
	const BLUE = 11;
	const BROWN = 12;
	const GREEN = 13;
	const RED = 14;
	const BLACK = 15;
	protected $id = self::SHULKER_BOX;
	/**
	 * ShulkerBox constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	/**
	 * @return bool
	 */
	public function canBeActivated() : bool{
		return true;
	}
	/**
	 * @return int
	 */
	public function getResistance() : float{
		return 30;
	}
	/**
	 * @return int
	 */
	public function getHardness() : float{
		return 6.0;
	}
	/**
	 * @return int
	 */
	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}
	/**
	 * @return AxisAlignedBB
	 */
	/*
	protected function recalculateBoundingBox(){
		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.9475,
			$this->z + 0.9375
		);
	}
	*/
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
	public function place(Item $item, Block $block, Block $target,int $face,Vector3 $facePos, Player $player = null): bool{
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::SHULKER_BOX),
			new ByteTag("color", $item->getDamage() & 0x0f),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}
		Tile::createTile("ShulkerBox", $this->getLevel(), $nbt);
		return true;
	}
	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	/*
	public function getDrops(Item $item) : array{
 			return [
 				[Item::SHULKER_BOX, $this->meta & 0x0f, 1],
 			];
	}
	*/

	public function getName() : string{
		static $names = [
			0 => "White Shulker Box",
			1 => "Orange Shulker Box",
			2 => "Magenta Shulker Box",
			3 => "Light Blue Shulker Box",
			4 => "Yellow Shulker Box",
			5 => "Lime Shulker Box",
			6 => "Pink Shulker Box",
			7 => "Gray Shulker Box",
			8 => "Light Gray Shulker Box",
			9 => "Cyan Shulker Box",
			10 => "Purple Shulker Box",
			11 => "Blue Shulker Box",
			12 => "Brown Shulker Box",
			13 => "Green Shulker Box",
			14 => "Red Shulker Box",
			15 => "Black Shulker Box",
		];
		return $names[$this->meta & 0x0f];
	}
	
	public function getMaxStackSize() : int{
		return 1;
	}
}