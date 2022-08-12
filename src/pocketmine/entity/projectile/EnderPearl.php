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

namespace pocketmine\entity\projectile;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;

class EnderPearl extends Throwable{
	public const NETWORK_ID = self::ENDER_PEARL;

	protected function onHit(ProjectileHitEvent $event) : void{
		$owner = $this->getOwningEntity();
		if($owner !== null){
			//TODO: check end gateways (when they are added) (ender eyes)

			switch($rand = mt_rand(0, 99)) {
				case $rand < 5:
					$nbt = Entity::createBaseNBT($event->getRayTraceResult()->getHitVector()->add(0.5, 0, 0.5), null, lcg_value() * 360, 0);
					$entity = Entity::createEntity(EntityIds::ENDERMITE, $this->level, $nbt);
					$entity->setImmobile(!Server::getInstance()->mobAiEnabled);
					$entity->spawnToAll();
				break;
			}
			$this->level->broadcastLevelEvent($owner, LevelEventPacket::EVENT_PARTICLE_ENDERMAN_TELEPORT);
			$this->level->addSound(new EndermanTeleportSound($owner));
			$owner->teleport($event->getRayTraceResult()->getHitVector());
			$this->level->addSound(new EndermanTeleportSound($owner));

			$owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
		}
	}
}
