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

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Tool;
use pocketmine\item\enchantment\Enchantment;

class Cobweb extends Flowable{

	protected $id = self::COBWEB;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function hasEntityCollision() : bool{
		return true;
	}

	public function getName() : string{
		return "Cobweb";
	}

	public function getHardness() : float{
		return 4;
	}

	/**
	 * @return int
	 */
	public function getToolType() : int{
		return Tool::TYPE_SHEARS;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	public function getDrops(Item $item) : array{
		if($item->isShears()){
			return [
				ItemFactory::get(Item::STRING, 0, 1)
			];
		}elseif($item->isSword()){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					ItemFactory::get(Item::COBWEB, 0, 1)
				];
			}else{
				return [
					ItemFactory::get(Item::STRING, 0, 1)
				];
			}
		}
		return [ItemFactory::get(Item::AIR, 0, 1)];
	}

	public function diffusesSkyLight() : bool{
		return true;
	}
}