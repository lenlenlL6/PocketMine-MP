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
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\World;

final class Coral extends BaseCoral{

	public function readStateFromWorld() : void{
		//TODO: this hack ensures correct state of coral plants, because they don't retain their dead flag in metadata
		$world = $this->pos->getWorld();
		$this->dead = true;
		foreach($this->pos->sides() as $vector3){
			if($world->getBlock($vector3) instanceof Water){
				$this->dead = false;
				break;
			}
		}
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$tx->fetchBlock($blockReplace->getPos()->down())->isSolid()){
			return false;
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onNearbyBlockChange(World $world, Vector3 $pos) : void{
		if(!$world->getBlock($pos->down())->isSolid()){
			$world->useBreakOn($pos);
		}else{
			parent::onNearbyBlockChange($world, $pos);
		}
	}
}
