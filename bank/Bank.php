<?php

/*
__PocketMine Plugin__
name=bank(beta)
version=0.0.1
author=miner&omattyao
class=bank
apiversion=9
*/

define("DEFAULT_ASSETS", 100);

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->console->register("bank", "bank commands", array($this, "handleCommand"));
		$this->path = $this->api->plugin->createConfig($this, array());
		/*
		
		we will add these commands
		
		$this->api->console->alias("deposite", "bank");
		$this->api->console->alias("withdraw", "bank");
		$this->api->console->alias("loan", "bank");
		$this->api->console->alias("money", "bank");
		*/
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($event){
			case "player.join":
				$target = $data->username;
				if(!array_key_exists($target, $cfg))
				{
					$this->api->plugin->createConfig($this,array(
							$target => array(
									'bank' => DEFAULT_ASSETS
							)
					));
					$this->api->chat->broadcast("[PocketMoney]$target has been registered.");
				}
		}
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "bank":
			$subCommand = $args[0];
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "";
					  $output .= "===[Bank :: Commands]===";
					  $output .= "[Bank]/bank :: shows commands";
					  $output .= "[Bank]/bank deposite (amount) :: put in your pocketmoney into the bank";
					  $output .= "[Bank]/bank withdraw (amount) :: take out your pocketmoney from the bank";
					  $output .= "[Bank]/bank loan (amount) :: get a loan from the bank";
					  $output .= "[Bank]/bank payloan (amount) :: return your loan to the bank";
					  $output .= "[Bank]/bank money :: shows your bank total assets";
						break;
					case "money":
						if(!array_key_exists($target, $cfg))
						{
							$output .= "[Bank]You don't have a bank account";
							break;
						}
						$money = $cfg[$issuer->username]['bank'];
						$output .= "[Bank]You have $money in your bank account";
						break;
					case "deposite":
						$playerBank = $issuer->username;
						$amount = $args[2];
						$bankMoney = $cfg[$playerBank]['bank'];
						$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
						if(!is_numeric($amount) or $amount < 0 or $playerMoney < $amount)
						{
							$output .= "[Bank]check your money / check your input number / check your spelling";
							break;
						}
						$bankMoney += $amount;
						$playerMoney -= $amount;
						$this->api->dhandle("money.handle", array(
										'username' => $playerBank,
										'method' => 'grant',
										'amount' => -$amount
								));
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney
								),
								);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have deposited to your bank acount safely";
						break;
					case "withdraw":
						$playerBank = $issuer->username;
						$amount = $args[2];
						$bankMoney = $cfg[$playerBank]['bank'];
						$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
						if(!is_numeric($amount) or $amount < 0 or $bankMoney < $amount)
						{
							$output .= "[Bank]check your money / check your input number / check your spelling";
							break;
						}
						$bankMoney -= $amount;
						$playerMoney += $amount;
						$this->api->dhandle("money.handle", array(
										'username' => $playerBank,
										'method' => 'grant',
										'amount' => -$amount
								));
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney
								),
								);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have deposited to your bank acount safely";
						break;
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
}