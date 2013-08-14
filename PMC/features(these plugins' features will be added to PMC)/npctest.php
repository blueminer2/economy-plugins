<?php

/*
__PocketMine Plugin__
name=NPCTest
description=Create some NPCs!
version=1.1
author=zhuowei
class=NPCTest
apiversion=9
*/

/* 
Small Changelog
===============

1.0: Initial release

1.1: NPCs now chase you

1.2: NPCs now save, updated for API 9, added allstatic configuration parameter to emulate 1.0 behaviour

1.2.1: Killing an NPC no longer crashes the server

*/



class NPCTest implements Plugin{

	private $api, $npclist, $config, $path, $ticksuntilupdate;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
		$this->npclist = array();
		$this->ticksuntilupdate = 0;
	}
	
	public function init(){
		$this->path = $this->api->plugin->configPath($this);
		$this->api->console->register("spawnnpc", "Add an NPC. /spawnnpc [name] [player location] OR /spawnnpc [name] <x> <y> <z> <world>", array($this, "command"));
		$this->api->console->register("rmnpc", "Remove an NPC. /rmnpc [name]", array($this, "rmcommand"));
		$this->api->schedule(5, array($this, "tickHandler"), array(), true);
		$this->config = new Config($this->path."config.yml", CONFIG_YAML, array(
			"npcs" => array(),
			"allstatic" => false,
		));
		$this->spawnAllNpcs();

	}

	public function spawnAllNpcs() {
		$npcconflist = $this->config->get("npcs");
		if (!is_array($npcconflist)) {
			$this->config->set("npcs", array());
			return;
		}
		foreach(array_keys($npcconflist) as $pname) {
			$p = $npcconflist[$pname];
			$pos = new Position($p["Pos"][0], $p["Pos"][1], $p["Pos"][2], $this->api->level->getDefault());
			$this->createNpc($pname, $pos);
		}
	}

	public function command($cmd, $params, $issuer, $alias){
		$npcname = $params[0];
		$location = $this->api->level->getDefault()->getSpawn();
		if (count($params) <= 2) {
			$locationPlayer = $this->api->player->get($params[1]);
			if ($locationPlayer instanceof Player and ($locationPlayer->entity instanceof Entity)) {
				$location = $locationPlayer->entity;
			}
		} else {
			$locationX = $params[1];
			$locationY = $params[2];
			$locationZ = $params[3];
			if (count($param) > 4) {
				$locationWorld = $params[4];
			} else {
				$locationWorld = $this->api->level->getDefault();
			}
			$location = new Position($locationX, $locationY, $locationZ, $locationWorld);
		}
		$this->createNpc($npcname, $location);
		return "Created NPC at " . $location;
	}

	public function rmcommand($cmd, $params, $issuer, $alias){
		$npcname = $params[0];
		$this->removeNpc($npcname);
		return "Removed NPC " . $npcname;
	}

	public function createNpc($npcname, $location) {
		$npcplayer = new Player("0", "127.0.0.1", 0, 0); //all NPC related packets are fired at localhost
		$npcplayer->spawned = true;
		$playerClassReflection = new ReflectionClass(get_class($npcplayer));
		$usernameField = $playerClassReflection->getProperty("username");
		$usernameField->setAccessible(true);
		$usernameField->setValue($npcplayer, $npcname);
		$iusernameField = $playerClassReflection->getProperty("iusername");
		$iusernameField->setAccessible(true);
		$iusernameField->setValue($npcplayer, strtolower($npcname));
		$timeoutField = $playerClassReflection->getProperty("timeout");
		$timeoutField->setAccessible(true);
		$timeoutField->setValue($npcplayer, PHP_INT_MAX - 0xff);
		$entityit = $this->api->entity->add($this->api->level->getDefault(), ENTITY_PLAYER, 0, array(
			"x" => $location->x,
			"y" => $location->y,
			"z" => $location->z,
			"Health" => 20,
			"player" => $npcplayer,
		));
		$entityit->setName($npcname);
		$this->api->entity->spawnToAll($entityit);
		$npcplayer->entity = $entityit;
		array_push($this->npclist, $npcplayer);
		$npcconf = array(
			"Pos" => array(
				0 => $location->x,
				1 => $location->y,
				2 => $location->z,
			),
			"mobile" => true,
		);
		$entireArr = $this->config->get("npcs");
		$entireArr[$npcname] = $npcconf;
		$this->config->set("npcs", $entireArr);
		//console($this->config->get("npcs")[$npcname]);
		$this->config->save();
		$npcplayer->data["npcconf"] = $npcconf;
	}

	public function removeNpc($npcname) {
		foreach(array_keys($this->npclist) as $pk) {
			$p = $this->npclist[$pk];
			if ($p->entity->name === $npcname) {
				$this->api->entity->remove($p->entity->eid);
				unset($this->npclist[$pk]);
				break;
			}
		}
		unset($this->config->get("npcs")[$npcname]);
		$this->config->save();
	}

	public function tickHandler($data, $event) {
		$this->ticksuntilupdate = $this->ticksuntilupdate - 1;
		$checkupdate = true;
		foreach($this->npclist as $p) {
			if ($p->entity->dead) {
				$p->entity->fire = 0;
				$p->entity->air = 300;
				$p->entity->setHealth(20, "respawn");
				$p->entity->updateMetadata();
				$this->api->entity->spawnToAll($p->entity);
			}
			if ($checkupdate) {
				if (isset($p->data["target"]) and $p->data["target"] instanceof Entity) {
					$target = $p->data["target"];
					if ($target->closed) {
						unset($p->data["target"]);
					} else {
						$xdiff = $target->x - $p->entity->x;
						$ydiff = $target->y - $p->entity->y;
						$zdiff = $target->z - $p->entity->z;
						$distaway = pow($xdiff, 2) + pow($ydiff, 2) + pow($zdiff, 2);
						$angle = atan2($zdiff, $xdiff);
						if (!$this->config->get("allstatic") and $p->data["npcconf"]["mobile"]) {
							if ($distaway > 1) {

								$speedX = cos($angle) * 0.2;
								$speedZ = sin($angle) * 0.2;


								$p->entity->speedX = $speedX;
								$p->entity->speedZ = $speedZ;
							}
						} else {
							$p->entity->speedX = 0;
							$p->entity->speedZ = 0;
						}
						$p->entity->yaw = (($angle * 180) / M_PI) - 90;
						$this->fireMoveEvent($p->entity);

					}
				}
				$mindist = -1;
				$minplayer = null;
				foreach($this->api->player->getAll($p->entity->level) as $otherp) {
					if ($otherp === $p) continue;
					if (!$otherp->connected or !$otherp->spawned) continue;
					$distaway = pow($p->x - $otherp->x, 2) + pow($p->y - $otherp->y, 2) + pow($p->z - $otherp->z, 2);
					if ($minplayer == null || $distaway < $mindist) {
						$mindist = $distaway;
						$minplayer = $otherp;
					}
				}
				if (!($minplayer instanceof Player) or $minplayer == null) {
					$p->data["target"] = null;
				} else {
					$p->data["target"] = $minplayer->entity;
				}
			}
			//TODO: more physics on the players. Attacking
		}
	}

	public function fireMoveEvent($entity) {
		//console($entity);
		if($entity->speedX != 0){
			$entity->x += $entity->speedX * 5;
		}
		if($entity->speedY != 0){
			$entity->y += $entity->speedY * 5;
		}
		if($entity->speedZ != 0){
			$entity->z += $entity->speedZ * 5;
		}
		if(($entity->last[0] != $entity->x or $entity->last[1] != $entity->y or $entity->last[2] != $entity->z or $entity->last[3] != $entity->yaw or $entity->last[4] != $entity->pitch)){
			if($this->api->handle("entity.move", $entity) === false){
				$entity->setPosition($entity->last[0], $entity->last[1], $entity->last[2], $entity->last[3], $entity->last[4]);
			}
			$entity->updateLast();
			$players = $this->api->player->getAll($entity->level);
			if($entity->player instanceof Player){
				unset($players[$entity->player->CID]);
			}
			$this->api->player->broadcastPacket($players, MC_MOVE_ENTITY_POSROT, array(
				"eid" => $entity->eid,
				"x" => $entity->x,
				"y" => $entity->y,
				"z" => $entity->z,
				"yaw" => $entity->yaw,
				"pitch" => $entity->pitch,
			));
		}
	}

	
	public function __destruct(){

	}

	
}
