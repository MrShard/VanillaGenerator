<?php

declare(strict_types=1);

namespace muqsit\vanillagenerator\generator\overworld\decorator;

use muqsit\vanillagenerator\generator\Decorator;
use muqsit\vanillagenerator\generator\object\BlockPatch;
use muqsit\vanillagenerator\generator\object\IceSpike;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;

class IceDecorator extends Decorator{

	private const OVERRIDABLES = [BlockLegacyIds::DIRT, BlockLegacyIds::GRASS, BlockLegacyIds::SNOW_BLOCK, BlockLegacyIds::ICE];

	public function populate(ChunkManager $world, Random $random, Chunk $chunk) : void{
		$sourceX = $chunk->getX() << 4;
		$sourceZ = $chunk->getZ() << 4;

		for($i = 0; $i < 3; ++$i){
			$x = $sourceX + $random->nextBoundedInt(16);
			$z = $sourceZ + $random->nextBoundedInt(16);
			$y = $world->getHighestBlockAt($x & 0x0f, $z & 0x0f) - 1;
			while($y > 2 && $world->getBlockAt($x, $y, $z)->getId() === BlockLegacyIds::AIR){
				--$y;
			}
			if($world->getBlockAt($x, $y, $z)->getId() === BlockLegacyIds::SNOW_BLOCK){
				(new BlockPatch(VanillaBlocks::PACKED_ICE(), 4, 1, ...self::OVERRIDABLES))->generate($world, $random, $x, $y, $z);
			}
		}

		for($i = 0; $i < 2; ++$i){
			$x = $sourceX + $random->nextBoundedInt(16);
			$z = $sourceZ + $random->nextBoundedInt(16);
			$y = $world->getHighestBlockAt($x & 0x0f, $z & 0x0f);
			while($y > 2 && $world->getBlockAt($x, $y, $z)->getId() === BlockLegacyIds::AIR){
				--$y;
			}
			if($world->getBlockAt($x, $y, $z)->getId() === BlockLegacyIds::SNOW_BLOCK){
				(new IceSpike())->generate($world, $random, $x, $y, $z);
			}
		}
	}

	public function decorate(ChunkManager $world, Random $random, Chunk $chunk) : void{
	}
}