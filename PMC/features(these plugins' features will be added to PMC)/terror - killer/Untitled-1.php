<?php

/*
__PocketMine Plugin__
name=Terror - Killer
version=0.0.1
author=miner
class=tk
apiversion=7
*/

define("DEFAULT_ALLOW", 0); // 0 = allow
define("DEFAULT_DEALLOW", 1); // 1 = not allowed to modify world

class tk implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("차단", "플레이어를못들어오게합니다", array($this, "handleCommand"));
		$this->api->console->register("비허용", "특정플레이어에서블럭변경권한을없애버립니다", array($this, "handleCommand"));
		$this->api->console->register("다비허용", "모두블럭변경을할수없게됩니다", array($this, "handleCommand"));
		$this->api->console->register("허용", "특정플레이어에게블럭변경권한을줍니다", array($this, "handleCommand"));
		$this->api->console->register("다허용", "모두블럭을변경할수있게합니다", array($this, "handleCommand"));
		$this->api->console->register("데미지", "연속적으로데미지를가합니다", array($this, "handleCommand"));
		$this->api->console->register("연속텔포", "계속텔레포트시킵니다", array($this, "handleCommand"));
		$this->api->console->register("ㅋ", "즉시킬", array($this, "handleCommand"));
		$this->api->console->register("얼음", "플레이어가못움직이게됩니다", array($this, "handleCommand"));
		$this->api->console->register("불리", "플레이어를나갈때까지괴롭힙니다", array($this, "handleCommand"));
		$this->api->console->register("tpall", "teleport everyone", array($this, "handleCommand"));
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->path = $this->api->plugin->createConfig($this, array(
												'모두허용 / 비허용' => DEFAULT_ALLOW
		));
		$this->data[$data->username]->save();
	}

	public function __destruct()
	{
		$this->data[$data->username]->save();
	}
	
	public function eventHandler($data, $event){
		switch($event){
			case "player.join":
			$this->data[$data->username] = new Config(DATA_PATH."/plugins/Terror - Killer/player/".$data->username.".yml", CONFIG_YAML, array(
							'이름' => $data->username,
                            '비허용' => DEFAULT_DEALLOW
						));
			break;
		}
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "차단":
				$cmd = "ban";
				$player = $args[1];
				$this->api->ban->commandHandler($cmd, $player);
				break;
			case "비허용":
			$target = $args[1];
			if(!file_exists("/plugins/Terror - Killer/player/".$target.".yml"))
			{
				$output .= "[Terrorkill]That player isn't in the playerlists \n";
			}
		}
		return $output;
	}

}
