<?php

/*
__PocketMine Plugin__
name=Notice
version=1.0
description=Fixed in time and message config.yml
author=CHOCO.M
class=notice
apiversion=7,8,9
*/

class notice implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	public function init(){
		$this->createConfig();
		if($this->config["notice on/off"] == "on"){
		if($this->config["notice-option (always | player-exist)"] == "always"){
			$this->api->schedule(20 * $this->config["time-for-notice(second)"] , array($this, "AlwaysHandler"), array(), true);
			}elseif($this->config["notice-option (always | player-exist)"] == "player-exist"){
			$this->api->addHandler("player.move",array($this,"PlayerExistHandler"),15);
			}else{
				console("\x1b[31m[NOTICE] The setting of notice option is not avaliable. Please change the option");
				$this->api->console->defaultCommands("stop","","Notice",false);
			}
		}
		$this->api->console->register("notice", "<notice>", array($this, "cmdH"));
	}

	public function cmdH($cmd, $param, $issuer, $alias = false){
	$output = "[NOTICE] ";
		switch($cmd){
			case "notice":
				$n = implode(" " ,$param);
				$this->config["notice"] = $n;
				$output .= "Notice changed to \"$n\"";
				return $output;
			break;
		}
	}
	public function AlwaysHandler(){
				$this->api->chat->broadcast(str_replace("\\n","\n",$this->config["notice"]));
	}
	public function PlayerExistHandler(&$data, $event){
			switch($event){
				case "player.move":
				$time = unserialize(file_get_contents("./plugins/Notice/data.dat"));
				$sec = time("s");
				if($sec - $time+1 > $this->config["time-for-notice(second)"]){
				$time = time("s");
				$this->api->chat->broadcast(str_replace("\\n","\n",$this->config["notice"]));
				file_put_contents("./plugins/Notice/data.dat", serialize($time));
				break;
				}
				file_put_contents("./plugins/Notice/data.dat", serialize($time));				
				break;
			}
	}
	public function createConfig(){
		$this->path = $this->api->plugin->createConfig($this,array(
			"notice on/off" => "on",
			"time-for-notice(second)" => 300,
			"notice-option (always | player-exist)" => "always",
			"notice" => "[NOTICE]\nNOTICE message",
		));	
		$this->config = $this->api->plugin->readYAML($this->path."config.yml");
	}
	public function __destruct(){
		$this->api->plugin->writeYAML("./plugins/Notice/config.yml", $this->config);
	}
}