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
define("DEFAULT_LOANS", 0);

class bank implements Plugin{
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
									'bank' => DEFAULT_ASSETS, 
									'loans' => DEFAULT_LOANS
							)
					));
					$this->api->chat->broadcast("[Bank]$target has been registered.");
				}
		}
	}
	
	public function handleCommand($cmd, $arg, $issuer, $alias){
		$output = "";
		switch($cmd){
			case "bank":
			if (!($issuer instanceof Player))
			{
				$output .= "Please use this command in game.\n";
				break;
			}
			$subCommand = $args[0];
			$username = $issuer->username;
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "";
					  $output .= "===[Bank :: Commands]===\n";
					  $output .= "[Bank]/bank :: shows commands\n";
					  $output .= "[Bank]/bank deposite (amount) :: put in your pocketmoney into the bank\n";
					  $output .= "[Bank]/bank withdraw (amount) :: take out your pocketmoney from the bank\n";
					  $output .= "[Bank]/bank loan (amount) :: get a loan from the bank\n";
					  $output .= "[Bank]/bank payloan (amount) :: return your loan to the bank\n";
					  $output .= "[Bank]/bank money :: shows your bank total assets\n";
						break;
					case "money":
						$target = $username;
						if(!array_key_exists($target, $cfg))
						{
							$output .= "[Bank]You don't have a bank account\n";
							break;
						}
						$money = $cfg[$issuer->username]['bank'];
						$output .= "[Bank]You have $money in your bank account\n";
						break;
					case "deposite":
						if($issuer == $cmd)
						{
							$output .= "[Bank]Please run this command in game\n";
						}
						$playerBank = $issuer->username;
						$amount = $args[1];
						$bankMoney = $cfg[$playerBank]['bank'];
						$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
						if(!is_numeric($amount) or $amount <= 0 or $playerMoney < $amount)
						{
							$output .= "[Bank]check your money / check your input number / check your spelling\n";
							break;
						}
						$bankMoney += $amount;
						$playerMoney -= $amount;
						$this->api->dhandle("money.handle", array(
										'username' => $playerBank,
										'method' => 'grant',
										'amount' => $amount
								));
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney
								),
								);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have deposited to your bank acount safely\n";
						break;
					case "withdraw":
						if($issuer == $cmd)
						{
							$output .= "[Bank]Please run this command in game\n";
						}
						$playerBank = $issuer->username;
						$amount = $args[1];
						$bankMoney = $cfg[$playerBank]['bank'];
						$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
						if(!is_numeric($amount) or $amount <= 0 or $bankMoney < $amount)
						{
							$output .= "[Bank]check your money / check your input number / check your spelling\n";
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
						$output .= "[Bank]You have withdrawn to your bank acount safely\n";
						break;
					case "loan":
						if($issuer == $cmd)
						{
							$output .= "[Bank]Please run this command in game\n";
						}
						$loanAmount = $args[1];
						$playerBank = $issuer->username;
						$bankMoney = $cfg[$playerBank]['bank'];
						if(!is_numeric($loanAmount) or $loanAmount <= 0 or $loanAmount >= 3000)
						{
							$output .= "[Bank]check your loan amount / check your input number / check your spelling\n";
							$output .= "[Bank]make sure that your loan amount is smaller than 3000\n";
						}
						$bankMoney += $loanAmount;
						$result = array(
								$playerBank => array(
										'bank'=> $bankMoney, 
										'loans' => $loanAmount
										),
										);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have taken your loan from the bank... please return it\n";
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
