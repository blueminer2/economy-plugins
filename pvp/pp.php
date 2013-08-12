<?php

/*
__PocketMine Plugin__
name=Pocket PVP
version=0.0.1
author=miner
class=pvp
apiversion=7
*/

class pvp implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("pvp", "Example command", array($this, "handleCommand"));
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "pvp":
			if($issuer instanceof Player)
			{
				$output .= "[pvp]Run this command in game";
			}
			$subCommand = strtolower(array_shift($args));
			switch($subcommand){
				case "":
				break;
			}
				break;
		}
	}

}