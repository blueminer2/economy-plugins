<?php

/*
__PocketMine Plugin__
name=PocketPvP
version=0.0.3
author=miner, Cha0sRuin
class=pvp
apiversion=9
*/

class pvp implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->config = new Config($this->api->plugin->configPath($this) . "config.yml", CONFIG_YAML);	
		$this->api->console->register("pvp", "PvP Master Command", array($this, "CommandHandler"));
		$this->api->ban->cmdWhitelist("pvp");		
		$this->api->addHandler("player.join", array($this, "EventHandler"), 5);
		$this->path = $this->api->plugin->createConfig($this, array());		
	}
	
	public function eventHandler($data, $event)
	{
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($event)
		{
			case "player.join":
				$target = $data->username;
				if (!$this->config->exists($target)) {
				$this->config->set($target, array('level' => 1, 'kill' => 0, 'point' => 1000,));					
				$this->api->chat->broadcast("[PocketPvP] $target registerd to pvp.");
				}
				$this->config->save();
				break;
		}
	}
	
	
	public function CommandHandler($cmd, $args, $issuer, $alias)
	{
		$output = "";
		$cmd = strtolower($cmd);
		switch ($cmd) {
			case "pvp":
				$subCommand = $args[0];
				switch ($subCommand) {
					case "":
					$output .= "=====PocketPvP Commands=====\n";
					$output .= "/pvp ::Shows help page of pvp\n";
					$output .= "/pvp status ::Shows your pvp status\n";					
					case "status":
					$lvl = $this->config->get($issuer->username)['level'];						
					$kill = $this->config->get($issuer->username)['kill'];
					$point = $this->config->get($issuer->username)['point'];					
					$output .= "==PocketPvP Status==\nLevel: $lvl\nKill: $kill\nPoint: $point\n===================\n";
					break;
					
					default:
						$output .= "[PocketPvP] /pvp $subCommand dosen't exist.";
						break;
				}
				break;
		}
		return $output;
	}
	
	public function __destruct()
	{
		$this->config->save();
	}	
}