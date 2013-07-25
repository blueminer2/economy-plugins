<?php

/*
 __PocketMine Plugin__
name=jobs+
version=1.4.0
author=(MinecrafterJPN) / choco.M / miner / Omattyao_yk
class=PocketJobs
apiversion=9
*/

class PocketJobs implements Plugin
{
	private $api, $jobList, $playerList, $config;

	public function __construct(ServerAPI $api, $server = false)
	{
		$this->api = $api;
	}

	public function init()
	{
		$this->jobList = new Config($this->api->plugin->configPath($this) . "joblist.yml", CONFIG_YAML, array());
		$this->playerList = new Config($this->api->plugin->configPath($this) . "playerlist.yml", CONFIG_YAML, array());
		$this->config = new Config($this->api->plugin->configPath($this) . "config.yml", CONFIG_YAML, array("Default" => true));
		if($this->config->get("Default")){
			$this->defaultConfig();
			$this->config->set("Default", false);
		}
		$this->jobList->save();
		$this->playerList->save();
		$this->config->save();
		$this->api->console->register("jobs", "Super command of PocketJobs", array($this, "commandHandler"));
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->addHandler("player.block.break", array($this, "eventHandler"));
		$this->api->addHandler("player.block.place", array($this, "eventHandler"));
		
	}

	public function eventHandler($data, $event)
	{
		switch($event)
		{
			case "player.join":
				if(!$this->playerList->exists($data->username)){
					$this->playerList->set($data->username, array(
							'slot1' => null,
							'slot2' => null
					));
					$this->playerList->save();
				}
				break;
			case "player.block.break":
				$this->workCheck("break", $data['player']->username, $data['target']->getID(), $data['target']->getMetadata());
				break;
			case "player.block.place":
				$this->workCheck("place", $data['player']->username, $data['item']->getID(), $data['item']->getMetadata());
				break;
		}
	}

	public function commandHandler($cmd, $args, $issuer, $alias)
	{
		$output = "";
		$cmd = strtolower($cmd);
		switch($cmd)
		{
			case "jobs":
				$subCmd = strtolower($args[0]);
				switch($subCmd){
					case "":
						$output .= "[PocketJobs]/jobs : show the commands available to you\n";
						$output .= "[PocketJobs]/jobs browse : browse the jobs available to you\n";
						$output .= "[PocketJobs]/jobs join <jobname> : join the selected job\n";
						$output .= "[PocketJobs]/jobs leave <jobname> : leave the selected job\n";
						$output .= "[PocketJobs]/jobs info <jobname> : show the detail of selected job\n";
						$output .= "[PocketJobs]/jobs reset : reset the job list to default\n";
						break;
					case "my":
						if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.");
							break;
						}
						$cfg = $this->playerList->get($issuer->username);
						$slot1 = $cfg['slot1'] === null ? "empty" : $cfg['slot1'];
						$slot2 = $cfg['slot2'] === null ? "empty" : $cfg['slot2'];
						$output .= "[PocketJobs]slot1:$slot1 slot2: $slot2\n";
						break;
					case "browse":
						$output .= "[PocketJobs]";
						foreach($this->jobList->getAll(true) as $job){
							if($job !== "Default") $output .= $job . " ";
						}
						$output .= "\n";
						break;
					case "join":
						if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.");
							break;
						}
						if(!isset($args[1])){
							$output .= "Usage: /jobs join <jobname>\n";
							break;
						}
						$jobname = strtolower($args[1]);
						$jobExist = false;
						foreach($this->jobList->getAll(true) as $job){
							if(strtolower($job) === $jobname){
								$jobExist = true;
							}
						}
						if(!$jobExist){
							$output .= "[PocketJobs]$args[1] not found.";
							break;
						}
						$output .= $this->joinJob($issuer->username, $jobname);
						break;
					case "leave":
						if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.");
							break;
						}
						if(!isset($args[1])){
							$output .= "Usage: /jobs leave <jobname>\n";
							break;
						}
						$jobname = strtolower($args[1]);
						$jobExist = false;
						foreach($this->jobList->getAll(true) as $job){
							if(strtolower($job) === $jobname){
								$jobExist = true;
							}
						}
						if(!$jobExist){
							$output .= "[PocketJobs]$args[1] not found.";
							break;
						}
						$output .= $this->leaveJob($issuer->username, $jobname);
						break;
					case "info":
						if(isset($args[1]) === false){
							$output .= "Usage: /jobs info <jobname>\n";
							break;
						}
						$jobname = strtolower($args[1]);
						$jobExist = false;
						foreach($this->jobList->getAll(true) as $job){
							if(strtolower($job) === $jobname){
								$jobExist = true;
							}
						}
						if(!$jobExist){
							$output .= "[PocketJobs]$args[1] not found.";
							break;
						}
						$output .= $this->infoJob($jobname);
						break;
					case "stat":
						if($issuer === "console"){
							console("[PocketJobs]Must be run on the world.");
							break;
						}
						$output .= "[PocketJobs]Unimplemented wait for a moment :)";
						break;
					case "reset":
						if($issuer !== "console"){
							$output .= "[PocketJobs]Must be run on the console.\n";
							break;
						}
						console("[PocketJobs]Reseting the config...");
						$this->defaultConfig();
						console("[PocketJobs]Completed");
						break;
				}
		}
		return $output;
	}

	private function joinJob($username, $jobname)
	{
		$jobname = strtolower($jobname);
		$cfg = $this->playerList->get($username);
		if(isset($cfg['slot1'])){
			if(isset($cfg['slot2'])){
				return "[PocketJobs]Your job slot is full.\n";
			}else{
				$this->playerList->set($username, array(
						'slot1' => $cfg['slot1'],
						'slot2' => $jobname
				));
				$this->playerList->save();				
				return "[PocketJobs]Set $jobname to your job slot2.\n";
			}
		}else{
			$this->playerList->set($username, array(
					'slot1' => $jobname,
					'slot2' => $cfg['slot2']
			));
			$this->playerList->save();
			return "[PocketJobs]Set $jobname to your job slot1.\n";
		}
	}

	private function leaveJob($username, $jobname)
	{
		$cfg = $this->playerList->get($username);
		if($cfg['slot1'] === $jobname){
			$this->playerList->set($username, array(
					'slot1' => null,
					'slot2' => $cfg['slot2']
			));
			$this->playerList->save();
			return "[PocketJobs]Remove $jobname from your job slot1.\n";
		}elseif($cfg['slot2'] === $jobname){
			$this->playerList->set($username, array(
					'slot1' => $cfg['slot1'],
					'slot2' => null
			));
			$this->playerList->save();
			return "[PocketJobs]Remove $jobname from your job slot2.\n";
		}else{
			return "[PocketJobs]You are not part of $jobname\n";
		}
	}

	private function infoJob($jobname){
		$output = "";
		$jobname = strtolower($jobname);
		foreach($this->jobList->getAll(true) as $job){
			if(strtolower($job) === $jobname){
				$info = $this->jobList->get($job);
				$output .= "[PocketJobs]$job \n";
				foreach($info as $type => $detail){
					foreach($detail as $value){
						$id = $value['ID'];
						$meta = $value['meta'];
						$amount = $value['amount'];
						$output .= "[PocketJobs]$type $id:$meta $amount\n";
					}
				}
			}
		}
		return $output;
	}

	private function workCheck($type, $username, $id, $meta){
		$flag = false;
		foreach($this->jobList->getAll() as $jobname => $jobinfo){
			if(isset($jobinfo[$type])){
				foreach($jobinfo[$type] as $values)
				{
					if($values['ID'] === $id
							and $values['meta'] === $meta)
					{
						console("ATTA!");
						$targetJob = strtolower($jobname);
						$amount = $values['amount'];
						$flag = true;
					}
				}
			}
				
		}
		if(!$flag) return;
		$cfg = $this->playerList->get($username);
		if($cfg['slot1'] === $targetJob or $cfg['slot2'] === $targetJob)
		{
			console("KOKODAYO");
			$this->api->dhandle("money.handle", array(
					'username' => $username,
					'method' => 'grant',
					'amount' => $amount
			));
		}
	}

	private function defaultConfig(){
		$this->jobList->set("Woodcutter", array(
				'break' => array(
						array(
								'ID' => 17,
								'meta' => 0,
								'amount' => 25
						),
						array(
								'ID' => 17,
								'meta' => 1,
								'amount' => 25
						),
						array(
								'ID' => 17,
								'meta' => 2,
								'amount' => 25
						),
						array(
								'ID' => 17,
								'meta' => 3,
								'amount' => 25
						),
				),
				'place' => array(
						array(
								'ID' => 6,
								'meta' => 0,
								'amount' => 1
						),
						array(
								'ID' => 6,
								'meta' => 1,
								'amount' => 1
						),
						array(
								'ID' => 6,
								'meta' => 2,
								'amount' => 1
						),
						array(
								'ID' => 6,
								'meta' => 3,
								'amount' => 1
						)
				)
		));
		$this->jobList->set("Miner", array(
				'break' => array(
						array(
								'ID' => 1,
								'meta' => 0,
								'amount' => 3
						),
						array(
								'ID' => 14,
								'meta' => 0,
								'amount' => 25
						),
						array(
								'ID' => 15,
								'meta' => 0,
								'amount' => 20
						),
						array(
								'ID' => 21,
								'meta' => 0,
								'amount' => 17
						),
						array(
								'ID' => 49,
								'meta' => 0,
								'amount' => 9
						),
						array(
								'ID' => 56,
								'meta' => 0,
								'amount' => 80
						),
						array(
								'ID' => 73,
								'meta' => 0,
								'amount' => 10
						)
				)
		));
		$this->jobList->save();
		$this->playerList->save();
		$this->config->save();
		console("[PocketJobs]Set to default config.");
	}

	public function __destruct()
	{
		$this->jobList->save();
		$this->playerList->save();
		$this->config->save();
	}
}