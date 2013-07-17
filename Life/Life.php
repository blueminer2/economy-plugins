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
				$age3 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(40000))
			{
				$growth = 4;
				$age4 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(50000))
			{
				$growth = 5;
				$age5 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(60000))
			{
				$growth = 6;
				$age6 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(70000))
			{
				$growth = 7;
				$age7 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(80000))
			{
				$growth = 8;
				$age8 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(90000))
			{
				$growth = 9;
				$age9 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(100000))
			{
				$growth = 10;
				$age10 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(140000))
			{
				$growth = 15;
				$age15 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(170000))
			{
				$growth = 20;
				$age20 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age2);
			}
			if($this->api->time->get(220000))
			{
				$growth = 30;
				$age30 = array(
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
					break;
					case "age":
					if(!array_key_exists($issuer->username, $cfg))
						{
							$output .= "[Life]You are not a villager.";
							break;
						}
						$age = $cfg[$issuer->username]['age'];
						$output .= "[Life]$age years old";
						break;
					case "jobs":
					$playername = $issuer->username;
					$jobs = $args[1];
					if(!file_exists("./plugins/Pocketjobs/config.yml") or !file_exists("./plugins/Pocketjobs/joblist.yml") or !file_exists("./plugins/Pocketjobs/playerlist.yml"))
					{
						$output .= "[Life]You don't have Pocketjobs loaded";
					}
					$page = $cfg[$playername]['age'];
					if($page <= 15)
					{
						$output .= "[Life]You are too young to get a job!!";
					}
					if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.");
							break;
						}
					if(!isset($args[2])){
							$output .= "Usage: /jobs join <jobname>\n";
							break;
						}
						$jobname = strtolower($args[2]);
						$jobExist = false;
						foreach($this->api->plugin->readYAML("./plugins/Pocketjobs/joblist.yml")->getAll(true) as $job){
							if(strtolower($job) === $jobname){
								$jobExist = true;
							}
						}
						if(!$jobExist){
							$output .= "[Life]$args[2] not found.";
							break;
						}
						$output .= $this->joinJob($issuer->username, $jobname);
						break;
					break;
				}
		}
		return $output;
	}
	
		private function joinJob($username, $jobname)
	{
		$jobname = strtolower($jobname);
		$cfg2 = $this->api->plugin->readYAML("./plugins/Pocketjobs/playerlist.yml")->get($username);
		if(isset($cfg['slot1'])){
			if(isset($cfg['slot2'])){
				return "[PocketJobs]Your job slot is full.\n";
			}else{
				$this->api->plugin->readYAML("./plugins/Pocketjobs/playerlist.yml")->set($username, array(
						'slot1' => $cfg['slot1'],
						'slot2' => $jobname
				));
				$this->api->plugin->readYAML("./plugins/Pocketjobs/playerlist.yml")->save();				
				return "[PocketJobs]Set $jobname to your job slot2.\n";
			}
		}else{
			$this->api->plugin->readYAML("./plugins/Pocketjobs/playerlist.yml")->set($username, array(
					'slot1' => $jobname,
					'slot2' => $cfg['slot2']
			));
			$this->api->plugin->readYAML("./plugins/Pocketjobs/playerlist.yml")->save();
			return "[PocketJobs]Set $jobname to your job slot1.\n";
		}
	}

	private function overwriteConfig($dat)
	{
		$cfg = array();
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		$result = array_merge($cfg, $dat);
		$this->api->plugin->writeYAML($this->path."config.yml", $result);
	}
}