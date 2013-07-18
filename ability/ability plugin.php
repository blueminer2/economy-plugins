<?php

/*
__PocketMine Plugin__
name=ability plugin
version=0.0.2
author=miner
class=ability
apiversion=9
*/

class ability implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}

	public function init(){
		$this->api->console->register("ability", "Ability plugin for pvp servers", array($this, "handlecommand"));
	}
	
	public function __destruct(){
	
	}

	public function handleCommand($cmd, $args, $issuer, $alias){
		$output = "";
		switch($cmd)
		{
			case "ability":
			$subCommand = $args[0];
				switch($subCommand){
					case "help":			
						$output .= "==[availible abilities]==\n";
						$output .= "[paladin]\n";
						$output .= "[cooker]\n";
						$output .= "[watergod]\n";
						$output .= "[axer]\n";
						break;
					case "paladin":
					if($issuer instanceof Player)
					{
						$output .= "Please run this command in-game.\n";
						break;
					}
						$bomber = array(
											364 => "steak",
                    	                    267 => "ironsword",
                        	                306 => "ironhelmet",
											299 => "leatherchestplate",
                                	        316 => "goldleggings",
                                    	    301 => "leatherboots"
						);
						$cmd = "give";
						$params = array($username, $bomber);
						$issuer = $this->api->player->get($username);
						$alias = false;
						$this->api->block->commandHandler($cmd, $params, $issuer, $alias);
						$output .= "Granted you the paladin ability.\n";
						break;
					case "cooker":
					if(!($issuer instanceof Player))
					{
						$output .= "Please run this command in-game.\n";
						break;
					}
						$bomber = array(
											364 => "steak",
											360 => "melon",
											260 => "apple",
											366 => "cookedchicken",
											257 => "ironpickaxe",
											306 => "ironhelmet",
											307 => "ironchestplate",
											308 => "ironleggings",
											309 => "ironboots",
						);
						$cmd = "give";
						$issuer = $this->api->player->get($username);
						$params = array($username, $bomber);
						$alias = false;
						$this->api->block->commandHandler($cmd, $params, $issuer, $alias);
						$output .= "Granted you the cooker ability.\n";
						break;
					case "watergod":
					if(!($issuer instanceof Player))
					{
						$output .= "Please run this command in-game.\n";
						break;
					}
						$bomber = array(
											8 => "water",
											8 => "water",
											8 => "water",
											283 => "goldsword",
											310 => "diamondhelmet",
											307 => "ironchestplate",
											316 => "goldleggings",
											305 => "chainmailboots",
						);
						$cmd = "give";
						$params = array($username, $bomber);
						$issuer = $this->api->player->get($username);
						$alias = false;
						$this->api->block->commandHandler($cmd, $params, $issuer, $alias);
						$output .= "Granted you the watergod ability.\n";
						break;
					case "axer":
					if(!($issuer instanceof Player))
					{
						$output .= "Please run this command in-game.\n";
						break;
					}
						$bomber = array(
											279 => "diamondaxe",
											282 => "mushroomstew",
											302 => "chainmailhelmet",
											315 => "goldchestplate",
											316 => "goldleggings",
											305 => "chainmailboots",
						);
						$cmd = "give";
						$params = array($username, $bomber);
						$issuer = $this->api->player->get($username);
						$alias = false;
						$this->api->block->commandHandler($cmd, $params, $issuer, $alias);
						$output .= "Granted you the axer ability.\n";
						break;
					}
		break;
		}
		return $output;
	}
}