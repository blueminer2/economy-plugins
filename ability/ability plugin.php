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
					$output .= "[ability]luminous";
					break;
					case "smasher";
					break;
					case "magician";
					break;
					case "paladin";
					break;
				}
		}
	}

}
