<?php

/*
__PocketMine Plugin__
name=Life
version=0.0.1
author=miner&omattyao
class=Life
apiversion=9
*/

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("life", "command that handles most of your life", array($this, "handleCommand"));
		$this->api->console->alias("age", "life");
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "age":
			if(!$issuer instanceof $player)
			{
				$output .= "[Life]Please run this command by a player";
			}
				break;
		}
		return $output;
	}

}
