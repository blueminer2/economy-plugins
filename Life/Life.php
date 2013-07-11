<?php

/*
__PocketMine Plugin__
name=Life
version=0.0.1
author=miner&omattyao
class=Life
apiversion=9
*/

define("DEFAULT_AGE", 1);

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->addHandler("player.growth", array($this, "eventHandler"));
		$this->api->console->register("life", "command that handles most of your life", array($this, "handleCommand"));
		$this->path = $this->api->plugin->createConfig($this, array());
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
	$output = "";
	$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
	switch($event){
		case "player.join":
		$target = $data->username;
				if(!array_key_exists($target, $cfg))
				{
					$this->api->plugin->createConfig($this,array(
							$target => array(
									'age' => DEFAULT_AGE
							)
					));
					$this->api->chat->broadcast("[Life]$target is born in this town.");
				}
				break;
		case "player.growth":
		$players = $this->api->player->getall($level = null)->username;
			if($this->api->time->get(20000))
			{
				$growth = 2;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(30000))
			{
				$growth = 3;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(40000))
			{
				$growth = 4;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(50000))
			{
				$growth = 5;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(60000))
			{
				$growth = 6;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(70000))
			{
				$growth = 7;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(80000))
			{
				$growth = 8;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(90000))
			{
				$growth = 9;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(100000))
			{
				$growth = 10;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(140000))
			{
				$growth = 15;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(170000))
			{
				$growth = 20;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(220000))
			{
				$growth = 30;
				$age2 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
		break;
		}
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "life":
				$subCommand = $args[0];
				$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "":
					case "help":
					$output .= "  ==[ :::Lists of All Availible Commands::: ]==";
					$output .= "==[ ::Showing the Commands of {Life} Plugin:: ]==";
					$output .= "[Life]/life age ++Shows How Old You Are++";
					$output .= "[Life]/life job ++Shows the availible jobs you can get for your age++";
					$output .= "";
					break;
					case "age":
					if(!array_key_exists($issuer->username, $cfg))
						{
							$output .= "[Life]You are not a villager.";
							break;
						}
						$money = $cfg[$issuer->username]['age'];
						$output .= "[Life]$money years old";
						break;
					break;
				}
		}
		return $output;
	}

	private function overwriteConfig($dat)
	{
		$cfg = array();
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		$result = array_merge($cfg, $dat);
		$this->api->plugin->writeYAML($this->path."config.yml", $result);
	}
}