<?php

/*
__PocketMine Plugin__
name=bank
version=0.0.1
author=miner&omattyao
class=bank
apiversion=9
*/

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("bank", "bank commands", array($this, "handleCommand"));
		$this->path = $this->api->plugin->createConfig($this, array());
		/*
		$this->api->console->alias("deposite", "bank");
		$this->api->console->alias("withdraw", "bank");
		$this->api->console->alias("loan", "bank");
		*/
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		$yml = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($cmd){
			case "bank":
			$target = $data->username;
			if(!$issuer instanceof $player)
			{
				$output .= "[Bank]Run this command by a player";
			}
			if(!array_key_exists($target, $yml))
			{
				$output .= "[Bank]You are not a valid player";
			}
				break;
		}
		return $output;
	}

}
