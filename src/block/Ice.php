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

use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class Ice extends Transparent{

	public function __construct(BlockIdentifier $idInfo, string $name, ?BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, $name, $breakInfo ?? new BlockBreakInfo(0.5, BlockToolType::PICKAXE));
	}

	public function getLightFilter() : int{
		return 2;
	}

	public function getFrictionFactor() : float{
		return 0.98;
	}

	public function onBreak(World $world, Vector3 $blockPos, Item $item, ?Player $player = null) : bool{
		if(($player === null or $player->isSurvival()) and !$item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())){
			$this->pos->getWorld()->setBlock($this->pos, VanillaBlocks::WATER());
			return true;
		}
		return parent::onBreak($world, $blockPos, $item, $player);
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick(World $world, Vector3 $pos) : void{
		if($world->getHighestAdjacentBlockLight($pos->x, $pos->y, $pos->z) >= 12){
			$world->useBreakOn($pos);
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}
}
