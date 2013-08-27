<?php

/*
__PocketMine Plugin__
name=PMconomy
version=0.0.1
author=miner/AndKmh/KsyMC/Omattyao
class=PMC
apiversion=9
*/

define("DEFAULT_ASSETS", 100);
define("DEFAULT_LOANS", 0);
define("DEFAULT_POINT", 100);
define("DEFAULT_MONEY", 30);

class PMC implements Plugin{
	private $api;
	private $path;
	
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
		$this->server = ServerAPI::request();
	}
	
	public function init(){
	$ex = false;
	foreach($this->api->plugin->getList() as $p)
	{
		if($p["name"] == "PocketMoney")
		{
			$ex = true;
			break;
		}
	}
    if($ex == false)
	{
       console("[ERROR] PocketMoney does not exist");
       $this->api->console->run("stop");
    }
		$this->config = new Config($this->api->plugin->configPath("./plugins/PockwetMoneyConomy") . "PMC-config.yml", CONFIG_YAML);
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->console->register("money", "Economy feature for PocketMine MP", array($this, "commandHandler"));
		$this->servname = $this->server->name;
		$this->server->name = "[PMC] ".$this->servname;
	}
	
	public function __destruct(){
		$this->config->save();
	}
	
	public function eventHandler($data, $event)
	{
		switch ($event) {
			case "player.join":
			$user = $data->username;
			if(!$this->config->exists($user))
			{
				$this->config->set($target, array('bank' => self::DEFAULT_ASSETS, 'loan' => self::DEFAULT_LOANS, 'point' => self::DEFAULT_POINT, 'money' => self::DEFAULT_MONEY));
				$this->api->chat->broadcast("[PMC] $user has been added to the " . $this->servname . " banks");
				$this->config->save();
			}
			break;
		}
	}
	public function commandHandler($cmd, $args, $issuer, $alias)
	{
		$output = "[PMC] ";
		switch ($cmd)
		{
			case "money":
			if($issuer instanceof Player)
			{
				$output .= "Please run this command in-game";
			}
			$subCommand = strtolower(array_shift($args));
			switch ($subCommand)
			{
				case "check":
				$money = $this->config->get($issuer->username)['money'];
				$output .= "$money is in your account in " .$this->servname. "'s currency";
				break;
				
				case "donate":
				$ammount = array_shift($args);
				break;
			}
			break;
		}
	}
}