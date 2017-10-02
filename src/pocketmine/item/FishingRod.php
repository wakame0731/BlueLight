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

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\entity\Projectile;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\level\sound\LaunchSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;

class FishingRod extends Tool{
	public function __construct(int $meta = 0, $count = 1){
		parent::__construct(self::FISHING_ROD, $meta, "Fishing Rod");
	}

	public function onClickAir(Player $player, Vector3 $directionVector) : bool{
		$entity = null;
		if(!$player->isFishing()){
			$entity = $this->spawnHook($player, $directionVector);
		}else{
			$player->fishingHook->reelLine();
		}
		$player->linkHookToPlayer($entity);
		return true;
	}

	public function spawnHook(Player $player, Vector3 $directionVector){
		$nbt = new CompoundTag("", [
			new ListTag("Pos", [
				new DoubleTag("", $player->x),
				new DoubleTag("", $player->y + $player->getEyeHeight()),
				new DoubleTag("", $player->z)
			]),
			new ListTag("Motion", [
				new DoubleTag("", $directionVector->x),
				new DoubleTag("", $directionVector->y),
				new DoubleTag("", $directionVector->z)
			]),
			new ListTag("Rotation", [
				new FloatTag("", $player->yaw),
				new FloatTag("", $player->pitch)
			]),
		]);

		$snowball = Entity::createEntity($this->getProjectileEntityType(), $player->getLevel(), $nbt, $player);
		$snowball->setMotion($snowball->getMotion()->multiply($this->getThrowForce()));

		//$this->count--;

		if($snowball instanceof Projectile){
			$player->getServer()->getPluginManager()->callEvent($projectileEv = new ProjectileLaunchEvent($snowball));
			if($projectileEv->isCancelled()){
				$snowball->kill();
			}else{
				$snowball->spawnToAll();
				$player->getLevel()->addSound(new LaunchSound($player), $player->getViewers());
			}
		}else{
			$snowball->spawnToAll();
		}

		return $snowball;
	}

	public function getProjectileEntityType() : string{
		return "FishingHook";
	}

	public function getThrowForce() : float{
		return 1;
	}
	//TODO
}