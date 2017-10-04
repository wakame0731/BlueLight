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

namespace pocketmine\level\generator\normal\eardbiome;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
/*
use pocketmine\level\generator\normal\eardbiome\DesertBiome;
use pocketmine\level\generator\normal\eardbiome\ForestBiome;
use pocketmine\level\generator\normal\eardbiome\IcePlainsBiome;
use pocketmine\level\generator\normal\eardbiome\MountainsBiome;
use pocketmine\level\generator\normal\eardbiome\OceanBiome;
use pocketmine\level\generator\normal\eardbiome\PlainBiome;
use pocketmine\level\generator\normal\eardbiome\RiverBiome;
use pocketmine\level\generator\normal\eardbiome\SmallMountainsBiome;
use pocketmine\level\generator\normal\eardbiome\SwampBiome;
use pocketmine\level\generator\normal\eardbiome\TaigaBiome;*/
use pocketmine\level\generator\populator\Populator;
use pocketmine\utils\Random;

abstract class Biome{

	const OCEAN = 0;
	const PLAINS = 1;
	const DESERT = 2;
	const MOUNTAINS = 3;
	const FOREST = 4;
	const TAIGA = 5;
	const SWAMP = 6;
	const RIVER = 7;
	const HELL = 8;
	const END = 9;
	const FROZEN_OCEAN = 10;
	const FROZEN_RIVER = 11;
	const ICE_PLAINS = 12;
	const ICE_MOUNTAINS = 13;
	const MUSHROOM_ISLAND = 14;
	const MUSHROOM_ISLAND_SHORE = 15;
	const BEACH = 16;
	const DESERT_HILLS = 17;
	const FOREST_HILLS = 18;
	const TAIGA_HILLS = 19;
	const SMALL_MOUNTAINS = 20;
	const BIRCH_FOREST = 27;
	const BIRCH_FOREST_HILLS = 28;
	const ROOFED_FOREST = 29;	
	const COLD_TAIGA = 30;
	const COLD_TAIGA_HILLS = 31;
	const MEGA_TAIGA = 32;
	const MEGA_TAIGA_HILLS = 33;
	const EXTREME_HILLS_PLUS = 34;
	const SAVANNA = 35;
	const SAVANNA_PLATEAU = 36;
	const MESA = 37;
	const MESA_PLATEAU_F = 38;
	const MESA_PLATEAU = 39;

	const MAX_BIOMES = 256;

	/** @var Biome[] */
	private static $biomes = [];

	/** @var int */
	private $id;
	/** @var bool */
	private $registered = false;

	/** @var Populator[] */
	private $populators = [];

	/** @var int */
	private $minElevation;
	/** @var int */
	private $maxElevation;

	/** @var Block[] */
	private $groundCover = [];

	/** @var float */
	protected $rainfall = 0.5;
	/** @var float */
	protected $temperature = 0.5;

	protected static function register(int $id, Biome $biome){
		self::$biomes[$id] = $biome;
		$biome->setId($id);
	}

	public static function init(){
		self::register(self::END, new Aether());
		self::register(self::OCEAN, new OceanBiome());
		self::register(self::BEACH, new BeachBiome());
		self::register(self::PLAINS, new PlainBiome());
		self::register(self::DESERT, new DesertBiome());
		self::register(self::MOUNTAINS, new MountainsBiome());
		self::register(self::FOREST, new ForestBiome());
		self::register(self::TAIGA, new TaigaBiome());
		self::register(self::SWAMP, new SwampBiome());
		self::register(self::RIVER, new RiverBiome());

		self::register(self::ICE_PLAINS, new IcePlainsBiome());


		self::register(self::SMALL_MOUNTAINS, new SmallMountainsBiome());

		self::register(self::BIRCH_FOREST, new PlainBiome2());
	}

	/**
	 * @param int $id
	 *
	 * @return Biome
	 */
	public static function getBiome(int $id) : Biome{
		if(isset(self::$biomes[$id])) {
			return self::$biomes[$id];
		}
		echo("$id is not register Biome");
		return self::$biomes[self::OCEAN];
	}

	public function clearPopulators(){
		$this->populators = [];
	}

	public function addPopulator(Populator $populator){
		$this->populators[get_class($populator)][] = $populator;
	}

	public function removePopulator($class){
		if(isset($this->populators[$class])){
			unset($this->populators[$class]);
		}
	}

	public function populateChunk(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		foreach($this->populators as $populator){
			if(is_array($populator)){
				foreach($populator as $p){
					$p->populate($level, $chunkX, $chunkZ, $random);
				}
			}else{
				$populator->populate($level, $chunkX, $chunkZ, $random);
			}
		}
	}

	/**
	 * @return Populator[]
	 */
	public function getPopulators() : array{
		return $this->populators;
	}

	public function setId(int $id){
		if(!$this->registered){
			$this->registered = true;
			$this->id = $id;
		}
	}

	public function getId() : int{
		return $this->id;
	}

	abstract public function getName() : string;

	public function getMinElevation() : int{
		return $this->minElevation;
	}

	public function getMaxElevation() : int{
		return $this->maxElevation;
	}

	public function setElevation(int $min, int $max){
		$this->minElevation = $min;
		$this->maxElevation = $max;
	}

	/**
	 * @return Block[]
	 */
	public function getGroundCover() : array{
		return $this->groundCover;
	}

	/**
	 * @param Block[] $covers
	 */
	public function setGroundCover(array $covers){
		$this->groundCover = $covers;
	}

	public function getTemperature() : float{
		return $this->temperature;
	}

	public function getRainfall() : float{
		return $this->rainfall;
	}
}