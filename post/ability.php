<?php

/*
__PocketMine Plugin__
name=Ability
version=0.0.1
description=Ability
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
					$output .= "[ability]luminous\n";
					$output .= "[ability]killer\n";
					case "help3":
					$output .= "[Lists of abilities]\n";
					$output .= "[ability]teleporter\n";
					$output .= "[ability]slayer\n";
					$output .= "[ability]cygnus\n";
					break;
					case "smasher";
					$output .= "[ability]You have selected a smasher\n";
					break;
					case "magician";
					$output .= "[ability]You have selected a magician\n";
					break;
					case "paladin";
					$output .= "[ability]You have selected a paladin\n";
					break;
					case "turbo";
					$output .= "[ability]You have selected a turbo\n";
					break;
					case "luminous";
					$output .= "[ability]You have selected a luminous\n";
					break;
					case "killer";
					$output .= "[ability]You have selected a killer\n";
					break;
					case "teleporter";
					$output .= "[ability]You have selected a teleporter\n";
					break;
					case "slayer";
					$output .= "[ability]You have selected a slayer\n";
					break;
					case "cygnus";
					$output .= "[ability]You have selected a cygnus\n";
					break;
				}
		}
	}

}