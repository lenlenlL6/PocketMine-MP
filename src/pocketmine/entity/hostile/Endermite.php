<?php

declare(strict_types=1);

namespace pocketmine\entity\hostile;

use pocketmine\entity\Ageable;
use pocketmine\entity\behavior\FloatBehavior;
use pocketmine\entity\behavior\LookAtPlayerBehavior;
use pocketmine\entity\behavior\MeleeAttackBehavior;
use pocketmine\entity\behavior\NearestAttackableTargetBehavior;
use pocketmine\entity\behavior\RandomLookAroundBehavior;
use pocketmine\entity\behavior\RandomStrollBehavior;
use pocketmine\entity\Monster;
use pocketmine\entity\Smite;
use pocketmine\Player;
use function mt_rand;

class Endermite extends Monster implements Ageable, Smite{
	public const NETWORK_ID = self::ENDERMITE;

	public $width = 0.4;
	public $height = 0.3;

	protected function initEntity() : void{
		$this->setMovementSpeed(0.35);
		$this->setFollowRange(35);
		$this->setAttackDamage(1);

		$this->setMaxHealth(8);
		$this->setHealth(8);

		parent::initEntity();
	}

	public function getName() : string{
		return "Endermite";
	}

	public function getXpDropAmount() : int{
		//TODO: check for equipment
		return 3;
	}

	protected function addBehaviors() : void{
		$this->behaviorPool->setBehavior(0, new FloatBehavior($this));
		$this->behaviorPool->setBehavior(1, new MeleeAttackBehavior($this, 1.0));
		$this->behaviorPool->setBehavior(2, new RandomStrollBehavior($this, 1.0));
		$this->behaviorPool->setBehavior(3, new LookAtPlayerBehavior($this, 8.0));
		$this->behaviorPool->setBehavior(4, new RandomLookAroundBehavior($this));

		$this->targetBehaviorPool->setBehavior(1, new NearestAttackableTargetBehavior($this, Player::class));
		$this->targetBehaviorPool->setBehavior(2, new NearestAttackableTargetBehavior($this, Villager::class));
	}
}