<?php

/*
__PocketMine Plugin__
name=Ability
version=0.0.1
author=miner / CHOCO.M / omattyao_yk / chaosruin / sekjun9878
class=ability
apiversion=9
*/

class ability implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}

	public function init(){
		$this->api->console->register("ability", "Example command", array($this, "handleCommand"));
	}		

	public function __destruct(){

	}
	public function eventHandler($data, $event) {
		if ($data->fallY or $data->player == null) return;
		$x = (int) round($data->x - 0.5);
		$y = (int) round($data->y - 1);
		$z = (int) round($data->z - 0.5);
		if (isset($data->level)) {
			$block = $data->level->getBlock(new Vector3($x, $y, $z));
		} else {
			$block = $this->api->block->getBlock($x, $y, $z);
		}
		$jumpblock1 = ($block->getID() == 35);
		
		}

     public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "ability":
			if (!($issuer instanceof Player))
			{
				$output .= "Please use this command in the game.\n";
				break;
			}
			$subCommand = strtolower(array_shift($args));
			$username = $issuer->username;
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "help":
					$output .= "[Lists of abilities]\n";
					$output .= "[ability]smasher\n";
					$output .= "[ability]magician\n";
					$output .= "[ability]paladin\n";
					break;
					case "help2":
					$output .= "[Lists of abilities]\n";
					$output .= "[ability]turbo\n";
					break;
					case "smasher";
					$data->speedY = -10;
			        $data->player->dataPacket(MC_SET_ENTITY_MOTION, array(
				    "eid" => 0,
				    "speedX" => 0,
				    "speedY" => (int) ($data->speedY * 2400),
				    "speedZ" => 0,
			        ));
					return true;
					break;
					case "magician";
					
					break;
					case "paladin";
					break;
				}
		}
	}

}