<?php

/*
__PocketMine Plugin__
name=ExamplePlugin
version=0.0.1
author=shoghicp
class=ExamplePlugin
apiversion=7
*/

define("DFAULT_POS", 0);
define("DEFAULT_LAND", 0); //  0 = none

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("pos1", "Example command", array($this, "handleCommand"));
		$this->api->cmdWhitelist("pos1");
		$this->api->console->register("pos2", "Example command", array($this, "handleCommand"));
		$this->api->cmdWhitelist("pos2");
		$this->api->console->register("buyland", "Example command", array($this, "handleCommand"));
		$this->api->cmdWhitelist("buyland");
		$this->path = $this->api->plugin->createConfig($this, array());
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->addhandler("player.block.break", array($this, "eventHandler"));
		$this->path2 = $this->api->plugin->createConfig("./plugins/bank/config.yml", array());
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
		$output .= "[Houses]";
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($event){
			case "player.join":
				$target = $data->username;
				if(!array_key_exists($target, $cfg))
				{
					$this->api->plugin->createConfig($this,array(
							$target => array(
									'posx1' => DFAULT_POS,
									'posy1' => DFAULT_POS,
									'posz1' => DFAULT_POS,
									'posx2' => DFAULT_POS,
									'posy2' => DFAULT_POS,
									'posz2' => DFAULT_POS,
									'myland' => DEFAULT_LAND
							)
					));
				}
		break;
			case "player.block.break":
			$breaker = $data["player"]->username;
			if(!($cfg[$breaker]['posx1'] === $data["target"]->x and $cfg[$breaker]['posy1'] === $data["target"]->y and $cfg[$breaker]['posz1'] === $data["target"]->z and $cfg[$breaker]['posx2'] === $data["targe"]->x and $cfg[$breaker]['posy2'] === $data["target"]->y and $cfg[$breaker]['posz2'] === $data["target"]->z))
			{
				return false;
				$output .= "You cannot break other's property \n";
			}elseif($this->api->ban->isOp($breaker) === true)
			{
			return true;
			}
		}
		return $output;
	}

	public function handleCommand($cmd, $arg){
		$output .= "[Houses]";
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($cmd){
			case "pos1":
			if(!($issuer instanceof Player))
			{
				$output .= "Run issue this command in-game \n";
			}
			$posx1 = new Position($issuer->entity->x, $issuer->entity->level);
			$posy1 = new Position($issuer->entity->y, $issuer->entity->level);
			$posz1 = new Position($issuer->entity->z, $issuer->entity->level);
			$result1 = array(
					$issuer => array(
							'posx1' => $posx1,
							'posy1' => $posy1,
							'posz1' => $posz1
					)
			);
			$this->overwriteConfig($result1);
			$output .= "Your position 1 has been selected \n";
			break;
			case "pos2":
			if(!($issuer instanceof Player))
			{
				$output .= "Run this command in game \n";
			}
			$posx2 = new Position($issuer->entity->x, $issuer->entity->level);
			$posy2 = new Position($issuer->entity->y, $issuer->entity->level);
			$posz2 = new Position($issuer->entity->z, $issuer->entity->level);
			$result2 = array(
					$issuer => array(
							'posx2' => $posx2,
							'posy2' => $posy2,
							'posz2' => $posz2
					)
			);
			$this->overwriteConfig($result2);
			$output .= "Your position  has been selected \n";
			break;
			case "buyland":
			if(!($issuer instanceof Player))
			{
				$output .= "Please issue this command in-game \n";
			}
			if($cfg[$issuer]['myland'] === 1)
			{
				$output .= "You already have a land \n";
			}
			if(!is_array($selection) or $selection[0] === false or $selection[1] === false or $selection[0][3] !== $selection[1][3]){
				$output .= "positions are not selected.\n";
				return false;
			}
			$level = $selection[0][3];
			$startX = min($selection[0][0], $selection[1][0]);
			$endX = max($selection[0][0], $selection[1][0]);
			$startY = min($selection[0][1], $selection[1][1]);
			$endY = max($selection[0][1], $selection[1][1]);
			$startZ = min($selection[0][2], $selection[1][2]);
			$endZ = max($selection[0][2], $selection[1][2]);
			$count = $this->countBlocks($selection);
			$bank = $this->api->plugin->readYAML($this->path2 . "./plugins/bank/config.yml");
			$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
			$buyer = $data["player"]->username;
			if($count > $playerMoney and $bank[$buyer]['bank'] >= $count)
			{
				$reto = array(
						$bank[$buyer]['bank'] -= $count
				);
			$this->overwriteConfig2($reto);
			$output .= "Bought the selected land with your bank account (cost = $count). \n";
			}
			if($count <= $playerMoney and $bank[$buyer]['bank'] >= $count)
			{
			$this->api->dhandle("money.handle", array(
					'username' => $issuer,
					'method' => 'grant',
					'amount' => -$count
			));
			$output .= "Bought the selected land with $count PM. \n";
			}
			if($count <= $playerMoney and $bank[$buyer]['bank'] < $count)
			{
			$this->api->dhandle("money.handle", array(
					'username' => $issuer,
					'method' => 'grant',
					'amount' => -$count
			));
			$output .= "Bought the selected land with $count PM. \n";
			}
			break;
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
	
	private function overwriteConfig2($dat)
	{
		$cfg = array();
		$cfg = $this->api->plugin->readYAML($this->path2 . "config.yml");
		$result = array_merge($cfg, $dat);
		$this->api->plugin->writeYAML($this->path2 ."config.yml", $result);
	}
	
	public function &session(Player $issuer){
		if(!isset($this->sessions[$issuer->username])){
			$this->sessions[$issuer->username] = array(
				"selection" => array(false, false),
			);
		}
		return $this->sessions[$issuer->username];
	}
	
	private function countBlocks($selection, &$startX = null, &$startY = null, &$startZ = null){
		if(!is_array($selection) or $selection[0] === false or $selection[1] === false or $selection[0][3] !== $selection[1][3]){
			return false;
		}
		$cfg[$pl] = min($selection[0][0], $selection[1][0]);
		$endX = max($selection[0][0], $selection[1][0]);
		$startY = min($selection[0][1], $selection[1][1]);
		$endY = max($selection[0][1], $selection[1][1]);
		$startZ = min($selection[0][2], $selection[1][2]);
		$endZ = max($selection[0][2], $selection[1][2]);
		return ($endX - $startX + 1) * ($endY - $startY + 1) * ($endZ - $startZ + 1);
	}
	
}
