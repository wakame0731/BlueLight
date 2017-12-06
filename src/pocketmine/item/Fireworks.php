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
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\math\Vector3;
use pocketmine\entity\Fireworks as FireworksEntity;
use pocketmine\Player;

class Fireworks extends Item{

	public function __construct(int $meta = 0){
		parent::__construct(self::FIREWORKS, $meta, "Fireworks");
    }
    
    public function onActivate(Level $level, Player $player, Block $block, Block $target, int $face, Vector3 $facePos) : bool{

        $namedTag = $this->getNamedTag();
        $nbt = new CompoundTag("", [
            "Pos" => new ListTag("Pos", [
                    new DoubleTag("", $target->getX()),
                    new DoubleTag("", $target->getY()),
                    new DoubleTag("", $target->getZ()),
            ]),
            "Motion" => new ListTag("Motion", [
                    new DoubleTag("", 0),
                    new DoubleTag("", 0),
                    new DoubleTag("", 0),
            ]),
            "Rotation" => new ListTag("Rotation", [
                    new FloatTag("", 0),
                    new FloatTag("", 0),
            ]),

            new CompoundTag("Fireworks", [
                new ListTag("Explosions", [
                    new CompoundTag("", [
                        new ByteArrayTag("FireworkColor", "4"),
                        new ByteArrayTag("FireworkFade", "5"),
                        new ByteTag("FireworkFlicker",1),
                        new ByteTag("FireworkTrail", 0),
                        new ByteTag("FireworkType", 4),
                        
                    ]),
                ]),
                new ByteTag("Flight", 2),
            ]),
        ]);
        $firework = new FireworksEntity($level, $nbt);
        $firework->spawnToAll();
        //$firework->setImmobile(true);
        return false;
    }

}

