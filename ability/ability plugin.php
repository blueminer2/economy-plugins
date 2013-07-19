<?php

/*
__PocketMine Plugin__
name=ability
version=0.0.1
author=Miner
class=ab
apiversion=9
*/

class ab implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("ability", "Ability plugin", array($this, "handleCommand"));
		$this->api->console->alias("list", "ability");
		$this->api->console->alias("paladin", "ability");
		/*
		$this->api->console->alias("miner", "ability");
		$this->api->console->alias("cooker", "ability");
		$this->api->console->alias("deathnoter", "ability");
		$this->api->console->alias("axer", "ability");
		*/
		$this->path = $this->api->plugin->createConfig($this, array());
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		if($alias !== false){
			$cmd = $alias;
		}
		if($cmd{0} === "ability"){
			$cmd = substr($cmd, 1);
		}
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		
		switch($cmd){
			case "list":
			if($issuer instanceof Player)
			{
				$output .= "[ability :: plugin]\n";
				$output .= "[paladin]";
				/*
				$output .= "[miner]";
				$output .= "[cooker]";
				$output .= "[deathnoter]";
				$output .= "[axer]";
				*/
			}
			break;
			case "paladin":
			$target = $data->username;
			if($issuer instanceof Player)
			{
				$this->api->plugin->createConfig($this,array(
							$target => array(
								"ironsword" => 267,
								"leatherhelmet" => 298,
								"ironchestplate" => 307,
								"ironleggings" => 308,
								"goldboots" => 317,
								"apple" => 260
							)
				));
				$paladin = $cfg[$target][];
				$cmd = "give";
				$params = array($username, $paladin);
				$player = $issuer->username;
				$alias = false;
				$this->api->block->commandHandler($cmd, $params, $player, $alias);
			}
			break;
		}
		return $output;
	}

}
