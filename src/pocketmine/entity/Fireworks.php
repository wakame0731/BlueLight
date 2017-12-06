<?php

/*
 *   ____  _            _      _       _     _
 *  |  _ \| |          | |    (_)     | |   | |
 *  | |_) | |_   _  ___| |     _  __ _| |__ | |_
 *  |  _ <| | | | |/ _ \ |    | |/ _` | '_ \| __|
 *  | |_) | | |_| |  __/ |____| | (_| | | | | |_
 *  |____/|_|\__,_|\___|______|_|\__, |_| |_|\__|
 *                                __/ |
 *                               |___/
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author BlueLightJapan Team
 * 
*/

namespace pocketmine\entity;

use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\level\particle\FlameParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class Fireworks extends Creature{

    const NETWORK_ID = 72;

    protected $gravity = 0;
    protected $drag = 0.01;
    public $hadCollision = true;
    public $width = 0.25;
	public $length = 0.25;
    public $height = 0.25;

    public $lifetime = 0;

    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
        $this->lifetime = 20*1+rand(0,5)+rand(0,7);
    }

    public function spawnTo(Player $player){
        $pk = new AddEntityPacket;
        $pk->entityRuntimeId = $this->getId();
        $pk->type = self::NETWORK_ID;
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);
        parent::spawnTo($player);
        $this->getLevel()->broadcastLevelSoundEvent($this->asVector3(), 55);
        $this->setMotion(new Vector3(0, 2.5, 0));
    }

    public function onUpdate(int $tick): bool{
        if($this->lifetime-- === 5){
            $pk = new EntityEventPacket;
            $pk->entityRuntimeId = $this->getId();
            $pk->event = 25;//EntityEventPacket::FIREWORK_BURN;
            $this->getLevel()->getServer()->broadcastPacket($this->getLevel()->getPlayers(), $pk);
            $this->getLevel()->broadcastLevelSoundEvent($this->asVector3(), 56);
            $this->kill();
            echo "Burn!";
            return false;
        }else{
            $particle = new FlameParticle($this->getPosition());
            $this->getLevel()->addParticle($particle);
            return parent::onUpdate($tick);
        }
    }
    
    public function getName() : string{
        return "Firework";
    }
    
}
