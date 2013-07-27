<?php

/*
__PocketMine Plugin__
name=Life
version=0.0.2
author=miner&omattyao&chaosruin
class=life
apiversion=9
*/

define("DEFAULT_AGE", 1);
define("DEHAULT_SCHOOL", 0);//None:0 Kindergarten:1 Elementary:2 Middle:3 High:4 University:5
define("DEFAULT_GEN", 0);//Man:1 Woman:2
define("MARRIED_TO", 0); //if not married, it will be 0
define("LIKE_SOMEONE", 0);//if set as "I don't want to marry" then it should be 0

class life implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->addHandler("player.spawn", array($this, "eventHandler"));
		$this->api->addHandler("player.growth", array($this, "eventHandler"));
		$this->api->console->register("life", "command that handles most of your life", array($this, "handleCommand"));
		$this->api->ban->cmdWhitelist("life");
		$this->api->addHandler("server.start", array($this, "eventHandler"));
		$this->path = $this->api->plugin->createConfig($this, array());
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
	$output = "";
	$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
	switch($event){
	    
		case "player.spawn":
					if($this->data[$data->username]->get("gender") === 0){
					$data->sendChat("[Life]Please select your gender.\n/life gender <man/woman>\n");
						break;
					}
					break;

		case "server.start":
		if(!file_exists("./plugins/PocketMoney.php"))
		{
			console("[Life]No PocketMoney!!!");
			$cmd = "stop";
			$this->api->block->commandHandler($cmd);
		}
		case "player.join":
		$target = $data->username;
				if(!array_key_exists($target, $cfg))
				{
					$this->api->plugin->createConfig($this,array(
							$target => array(
									'age' => DEFAULT_AGE,
									'gender' => DEFAULT_GEN,
									'school' => DEFAULT_SCHOOL,
									'married' => MARRIED_TO,
									'like' => LIKE_SOMEONE,
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
				$this->overwriteConfig($age3);
			}
			if($this->api->time->get(40000))
			{
				$growth = 4;
				$age4 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age4);
			}
			if($this->api->time->get(50000))
			{
				$growth = 5;
				$age5 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age5);
			}
			if($this->api->time->get(60000))
			{
				$growth = 6;
				$age6 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age6);
			}
			if($this->api->time->get(70000))
			{
				$growth = 7;
				$age7 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age7);
			}
			if($this->api->time->get(80000))
			{
				$growth = 8;
				$age8 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age8);
			}
			if($this->api->time->get(90000))
			{
				$growth = 9;
				$age9 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age9);
			}
			if($this->api->time->get(100000))
			{
				$growth = 10;
				$age10 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age10);
			}
			if($this->api->time->get(110000))
			{
				$growth = 11;
				$age11 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age11);
			}
			if($this->api->time->get(120000))
			{
				$growth = 12;
				$age12 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age12);
			}
			if($this->api->time->get(130000))
			{
				$growth = 13;
				$age13 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age13);
			}
			if($this->api->time->get(140000))
			{
				$growth = 14;
				$age14 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age14);
			}
			if($this->api->time->get(150000))
			{
				$growth = 15;
				$age15 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age15);
			}
			if($this->api->time->get(160000))
			{
				$growth = 16;
				$age16 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age16);
			}
			if($this->api->time->get(170000))
			{
				$growth = 17;
				$age17 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age17);
			}
			if($this->api->time->get(180000))
			{
				$growth = 18;
				$age18 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age18);
			}
			if($this->api->time->get(190000))
			{
				$growth = 19;
				$age19 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age19);
			}
			if($this->api->time->get(200000))
			{
				$growth = 20;
				$age20 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age20);
			}
			if($this->api->time->get(250000))
			{
				$growth = 25;
				$age25 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age25);
			}
			if($this->api->time->get(300000))
			{
				$growth = 30;
				$age30 = array(
						$players => array(
								'age' => $growth
								)
						);
				$this->overwriteConfig($age30);
			}
		break;
		}
	}
	
	public function handleCommand($cmd, $args, $issuer, $alias){
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
					if($page < 15)
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
						case "marriage":
						$target = $player->username;
						$marryto = $args[1];
						if($cfg[$issuer]['gender'] = $cfg[$target]['gender'])
						{
							$output .= "[Life]You cannot marry a person with the same gender!\n";
						}
						if($cfg[$issuer]['married'] == 1)
						{
							$output .= "[Life]You are already married to someone\n";
						}
						if($cfg[$target]['married'] == 1)
						{
							$output .= "[Life] $target is already married to someone";
						}
						if($cfg[$issuer]['age'] < 15 or $cfg[$target]['age'] < 15)
						{
							$output .= "[Life]You or your bride is too young to marry each other\n";
						}
						if(!$cfg[$issuer]->get('like') = $target or !$cfg[$target]->get('like') = $issuer)
						{
							if($cfg[$target]->get('gender') = 1)
							{
								$output .= "[Life]He doesn't like you\n";
							}
							if($cfg[$target]->get('gender') = 2)
							{
								$output .= "[Life]She doesn't like you\n";
							}
						}
						if($marryto = $issuer)
						{
							$output .= "[Life]You can't marry yourself\n";
						}
						break;
						case "gender":
				switch($params[0]){
			default:
				$output .="[Life]Oops?\n";
				break;
			case "":
					$output .= "Usage:/life gender <Man/Woman>\n";
					break;
			case "woman":
				if($cfg[$issuer->username]->get("gender") !== 0){
					$output .= "[Life]You already selected your gender\n";
					break;					
				}else{
					$cfg[$issuer->username]->set("gender", 2);
					$output  .= "[Life] Your Woman\n";
					break;
				}
				break;
			case "man":
				if($cfg[$issuer->username]->get("gender") !== 0){
					$output .= "[Life]You already selected your gender\n";
					break;					
				}else{
					$cfg[$issuer->username]->set("gender", 1);
					$output  .= "[Life]Your Man.\n";
					break;
				}
					break;
				}
					break;
				}
				break;
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