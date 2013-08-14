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
		//$this->api->addHandler("player.spawn", array($this, "eventHandler"));
		$this->api->addHandler("player.growth", array($this, "eventHandler"));
		$this->api->addHandler("school.pay", array($this, "eventHandler"));
		$this->api->console->register("life", "command that handles most of your life", array($this, "handleCommand"));
		$this->api->ban->cmdWhitelist("life");
		$this->path = $this->api->plugin->createConfig($this, array());
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
	$output = "";
	$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
	$playername = $data['player']->username;
	$playerbm = $this->api->dhandle("bank.player.get", array('username' => $username));
	switch($event){
	    /*
		case "player.spawn":
					if($this->config->get("gender") === 0){
					$data->sendChat("[Life]Please select your gender.\n/life <man/woman>\n");
						break;
					}
					break;
		*/
		case "school.pay":
		if($cfg[$playername]['age'] = 5 and $cfg[$playername]['school'] = 1)
		{
			$cost5 = $playerbm%5;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost5
								));
			$output .= "[Bank] $cost5 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 6 and $cfg[$playername]['school'] = 1)
		{
			$cost6 = $playerbm%6;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost6
								));
			$output .= "[Bank] $cost6 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 7 and $cfg[$playername]['school'] = 1)
		{
			$cost7 = $playerbm%7;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost7
								));
			$output .= "[Bank] $cost7 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 8 and $cfg[$playername]['school'] = 2)
		{
			$cost8 = $playerbm%8;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost8
								));
			$output .= "[Bank] $cost8 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 9 and $cfg[$playername]['school'] = 2)
		{
			$cost9 = $playerbm%9;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost9
								));
			$output .= "[Bank] $cost9 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 10 and $cfg[$playername]['school'] = 2)
		{
			$num1 = 1%2 + 9;
			$cost10 = $playerbm%$num1;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost10
								));
			$output .= "[Bank] $cost10 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 11 and $cfg[$playername]['school'] = 2)
		{
			$num2 = 10;
			$cost11 = $playerbm%$num2;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost11
								));
			$output .= "[Bank] $cost11 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 12 and $cfg[$playername]['school'] = 2)
		{
			$num3 = 1%2 + 10;
			$cost12 = $playerbm%$num3;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost12
								));
			$output .= "[Bank] $cost12 Decreased from your bank account \n";
			break;
		}
		if($cfg[$playername]['age'] = 13 and $cfg[$playername]['school'] =2)
		{
			$num4 = 12;
			$cost13 = $playerbm%$num4;
						$this->api->dhandle("bank.handle", array(
										'username' => $playername,
										'method' => 'grant',
										'amount' => $cost13
								));
			$output .= "[Bank] $cost13 Decreased from your bank account \n";
			break;
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
									'like' => LIKE_SOMEONE
							)
					));
					$this->api->chat->broadcast("[Life]$target is born in this town.\n");
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
				$this->api->chat->broadcast("[Life]everyone became 2 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 3 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 4 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 5 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 6 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 7 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 8 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 9 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 10 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 11 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 12 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 13 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 14 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 15 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 16 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 17 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 18 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 19 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 20 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 25 years old");
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
				$this->api->chat->broadcast("[Life]everyone became 30 years old");
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
					$output .= "  ==[ :::Lists of All Availible Commands::: ]== \n";
					$output .= "==[ ::Showing the Commands of {Life} Plugin:: ]== \n";
					$output .= "[Life]/life age ++Shows how old you are \n";
					$output .= "[Life]/life job ++Shows availible jobs you can get \n";
					break;
					case "age":
					if(!array_key_exists($issuer->username, $cfg))
						{
							$output .= "[Life]You are not a villager./n";
							break;
						}
						$age = $cfg[$issuer->username]['age'];
						$output .= "[Life]$age years old\n";
						break;
					case "jobs":
					$playername = $issuer->username;
					$jobs = $args[1];
					if(!file_exists("./plugins/jobs+/config.yml") or !file_exists("./plugins/jobs+/joblist.yml") or !file_exists("./plugins/jobs+/playerlist.yml"))
					{
						$output .= "[Life]You don't have jobs+ loaded/n";
					}
					$page = $cfg[$playername]['age'];
					if($page < 19)
					{
						$output .= "[Life]You are too young to get a job!!\n";
					}
					if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.\n");
							break;
						}
					if(!isset($args[2])){
							$output .= "Usage: /jobs join <jobname>\n";
							break;
						}
						$jobname = strtolower($args[2]);
						$jobExist = false;
						foreach($this->api->plugin->readYAML("./plugins/jobs+/joblist.yml")->getAll(true) as $job){
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
						if($cfg[$issuer]['married'] = 1)
						{
							$output .= "[Life]You are already married to someone\n";
						}
						if($cfg[$target]['married'] = 1)
						{
							$output .= "[Life] $target is already married to someone";
						}
						if($cfg[$issuer]['age'] < 15 or $cfg[$target]['age'] < 15)
						{
							$output .= "[Life]You or your bride is too young to marry each other\n";
						}
						if(!$cfg[$issuer]['like'] = $target or !$cfg[$target]['like'] = $issuer)
						{
							if($cfg[$target]['like'] = 1)
							{
								$output .= "[Life]He doesn't like you\n";
							}
							if($cfg[$target]['like'] = 2)
							{
								$output .= "[Life]She doesn't like you\n";
							}
						}
						if($marryto = $issuer)
						{
							$output .= "[Life]You can't marry yourself\n";
						}
						$result = array(
								$issuer->username => array(
										'married' => $marryto
								),
						);
						$result2 = array(
								$issuer->username => array(
										'married' => $issuer->username
								),
						);
						$this->overwriteConfig($result);
						$this->overwriteConfig($result2);
						break;
						case "like":
						$target = $player->username;
						$like = $args[1];
						if($cfg[$issuer]['gender'] = $cfg[$target]['gender'])
						{
							$output .= "[Life]You cannot marry a person with the same gender!\n";
						}
						if($cfg[$issuer]['married'] = 1)
						{
							$output .= "[Life]You are already married to someone\n";
						}
						if($cfg[$target]['married'] = 1)
						{
							$output .= "[Life] $target is already married to someone";
						}
						if($like = $issuer)
						{
							$output .= "[Life]You can't marry yourself\n";
						}
						$result = array(
								$issuer->username => array(
										'like' => $like
								),
						);
						$result2 = array(
								$issuer->username =>array(
										'like' => $issuer->username
								),
						);
						$this->overwriteConfig($result);
						$this->overwriteConfig($result2);
						break;
            case "woman":
				if($cfg[$issuer->username]["gender"] != 0){
					$output .= "[Life]You already selected your gender\n";
					break;					
				}else{
					$ret = array(
							$issuer => array(
									"gender" => 2
							),
					);
					$this->overwriteConfig($ret);
					$output  .= "[Life] You selected Woman\n";
					break;
				}
				break;
			case "man":
				if($cfg[$issuer->username]["gender"] != 0){
					$output .= "[Life]You already selected your gender\n";
					break;					
				}else{
					
					$ret1 = array(
							$issuer => array(
									"gender" => 1
							),
					);
					$this->overwriteConfig($ret1);
					$output  .= "[Life]You selected Man.\n";
					break;
				}
					break;
				}
				case "school":
				$plage = $cfg[$issuer->username]['age'];
				$school = $cfg[$issuer->username]['school'];
				if(!$issuer instanceof Player)
				{
					$output .= "Run this command in-game\n";
				}
				if($school != 0)
				{
					$output .= "[Life]You are already in school\n";
				}
				if($plage <= 4)
				{
					$output .= "[Life]You are too young to go to school \n";
				}
				if($plage >= 25)
				{
					$output .= "[Life]Your are too old to go to school \n";
				}
				if(!file_exists("./plugins/bank/config.yml"))
				{
					$output .= "[Life]Bank plugin is not loaded \n";
				}
				$schinput = $args[1];
				if($plage >= 5 and $plage <= 7)
				{
					$out = array(
							$issuer => array(
									"school" => 1
							),
					);
					$this->overwriteConfig($out);
					$output .= "[Life]You are now Kindergarden!! \n";
				}
				if($plage >=8 and $plage <=15)
				{
					$out2 = array(
							$issuer => array(
									"school" =>2
							),
					);
					$this->overwriteConfig($out2);
					$output .= "[Life]You are now Elementary schooler \n";
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
		$this->api->plugin->writeYAML($this->path ."config.yml", $result);
	}
}