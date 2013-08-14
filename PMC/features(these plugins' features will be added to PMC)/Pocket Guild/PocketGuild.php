<?php

/*
__PocketMine Plugin__
name=PocketGuild
version=0.1.1
author=Miner / Omattyao / Sekjun
class=pg
apiversion=9
*/

class pg implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init()
	{
	  $this->guildhome = new Config($this->api->plugin->configPath($this) . "guildhome.yml", CONFIG_YAML, array());
	  $this->playerList = new Config($this->api->plugin->configPath($this) . "playerlist.yml", CONFIG_YAML, array());
	  $this->guildlist = new Config($this->api->plugin->configPath($this) . "guildlist.yml", CONFIG_YAML, array());
	  $this->guildhome->save();
	  $this->playerList->save();
	  $this->guildlist->save();
      $this->api->addHandler("player.join", array($this, "eventHandler"));
	  $this->api->console->register("Guild", "Pocket Guild allows you to handle guild system", array($this, "handleCommand"));
	  $this->api->ban->cmdWhitelist("Guild");
	  $this->api->console->register("AdminGuild", "AdminGuild is for ops and permission users!!", array($this, "handleCommand"));
	}
	
	public function __destruct()
	{
		$this->guildhome->save();
		$this->playerList->save();
		$this->guildlist->save();
	}

    public function eventHandler($data, $event)
    {
	  switch ($event) 
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
      }
    }
	
	public function handleCommand($cmd, $params, $issuer, $alias)
	{
		$output = "";
		$cmd    = strtolower($cmd);
		switch($cmd)
		{
			case "guild":
			$subCommand = strtolower(array_shift($args));
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
			switch ($subCommand)
			{
				case "help":
				$output .= "[PocketGuild == Help (page1)]\n";
				$output .= "[PG]/guild join = joins a guild\n";
				$output .= "[PG]/guild mylist = shows your guild lists\n";
				$output .= "[PG]/guild list = list of all guild\n";
				$output .= "[PG]/guild create = create a guild\n";
				$output .= "[For more info type /guild help2]\n";
				break;
				
				case"help2":
				$output .= "[PocketGuild == Help (page2)]\n";
				$output .= "[PG]/guild home = spawn to the guild's spawn point\n";
				$output .= "[PG]/guild sethome = spawn to the guild's spawn point\n";
				$output .= "[PG]/guild leave = spawn to the guild's spawn point\n";
				break;
				
				case "join":
				if($issuer === "console")
					{
					console("[PG]Must be run on the world.");
					break;
					}
					if(!isset($args[1]))
					{
						$output .= "Usage: /guild join <guildname>\n";
						break;
					}
					$guildname = strtolower($args[1]);
					$guildExist = false;
					foreach($this->guildlist->getAll(true) as $guild)
					{
						if(strtolower($guild) === $guildname)
						{
							$guildExist = true;
						}
					}
					if(!$guildExist)
					{
						$output .= "[PG]$args[1] not found.";
						break;
					}
					$output .= $this->joinguild($issuer->username, $jobname);
					break;
				break;
				
            	case "mylist":
				if($issuer === "console"){
					console("[PG]Must be run on the world.");
					break;
					}
					$cfg = $this->playerList->get($issuer->username);
					$slot1 = $cfg['slot1'] === null ? "empty" : $cfg['slot1'];
					$slot2 = $cfg['slot2'] === null ? "empty" : $cfg['slot2'];
					$output .= "[PG]guildslot1:$slot1 guildslot2: $slot2\n";
					break;
              	break;
				
				case "list":
				$output .= "[PG]";
				foreach($this->guildlist->getall(true) as $list)
				{
					$output .= $list . " ";
				}
				$output .= "\n";
				break;
				
				case "create":
				if (!($issuer instanceof Player))
				{
            	$output .= "[PG]Please run this command in-game.\n";
            	break;
          		}
				$guildname = strtolower($args[1]);
				if (array_key_exists($guildname, $cfg))
				{
					$output .= "[PG]The name of the guild already exists";
				}
				$cfg = $this->playerList->get($username);
				if(isset($cfg['slot1']) && isset($cfg['slot2']))
				{
					$output .= "[PG]Your guild slot is full.\n";
				}
				$this->guildlist->set($guildname);
				$this->playerList->save();
				$output .= $this->joinguild($issuer->username, $guildname);
				$output .= "[PG]You have joined $guildname .\n";
         		break;
				case "leave":
				if($issuer === "console")
				{
					console("[PG]Must be run on the world.");
					break;
				}
				if(!isset($args[1])){
					$output .= "Usage: /guild leave <guilname>\n";
					break;
				}
				$guildname = strtolower($args[1]);
				$guildExist = false;
				foreach($this->guildlist->getAll(true) as $guild){
					if(strtolower($job) === $guildname){
						$guildExist = true;
					}
				}
				if(!$guildExist){
					$output .= "[PG]$args[1] not found.";
					break;
				}
				$output .= $this->leaveJob($issuer->username, $guildname);
				break;
			}
          	break;
		}
	}

	private function joinguild($username, $guildname)
	{
		$guildname = strtolower($guildname);
		$cfg = $this->playerList->get($username);
		if(isset($cfg['slot1'])){
			if(isset($cfg['slot2'])){
				return "[PG]Your guild slot is full.\n";
			}else{
				$this->playerList->set($username, array(
						'slot1' => $cfg['slot1'],
						'slot2' => $guildname
				));
				$this->playerList->save();				
				return "[PG]Set $guildname to your guild slot2.\n";
			}
		}else{
			$this->playerList->set($username, array(
					'slot1' => $guildname,
					'slot2' => $cfg['slot2']
			));
			$this->playerList->save();
			return "[PG]Set $guildname to your guild slot1.\n";
		}
	}
	
	private function leaveJob($username, $guildname)
	{
		$cfg = $this->playerList->get($username);
		if($cfg['slot1'] === $guildname){
			$this->playerList->set($username, array(
					'slot1' => null,
					'slot2' => $cfg['slot2']
			));
			$this->playerList->save();
			return "[PG]Remove $guildname from your guild slot1.\n";
		}elseif($cfg['slot2'] === $guildname){
			$this->playerList->set($username, array(
					'slot1' => $cfg['slot1'],
					'slot2' => null
			));
			$this->playerList->save();
			return "[PG]Remove $guildname from your guild slot2.\n";
		}else{
			return "[PG]You are not part of $guildname\n";
		}
	}

}
