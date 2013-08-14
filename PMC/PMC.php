<?php

/*
__PocketMine Plugin__
name=PMconomy
version=0.0.1
author=miner/AndKmh/KsyMC/Omattyao
class=PMC
apiversion=9
*/

define("DEFAULT_ASSETS", 100);
define("DEFAULT_LOANS", 0);
define("DEFAULT_POINT", 100);
define("DEFAULTR_CON", 0); // 0 is saving points

class PMC implements Plugin{
	private $api;
	private $minute = 30;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
		$this->server = ServerAPI::request();
	}
	
	public function init(){
	$ex = false;
	foreach($this->api->plugin->getList() as $p)
	{
		if($p["name"] == "PocketMoney")
		{
			$ex = true;
			break;
		}
	}
    if($ex == false)
	{
       console("[ERROR] PocketMoney does not exist");
       $this->api->console->run("stop");
    }
		$this->api->addHandler("player.join", array($this, "eventHandler"));
		$this->api->addHandler("bank.handle", array($this, "eventHandler"));
		$this->api->addHandler("server.stop", array($this, "eventHandler"));
		$this->api->console->register("bank", "bank for buying stuffs", array($this, "handleCommand"));
		$this->api->ban->cmdwhitelist("bank");
		$this->api->console->register("point", "point system that will give you discount everytime you spend PM", array($this, "handleCommand"));
		$this->path = $this->api->plugin->createConfig($this, array());
		$this->servname = $this->server->name;
		$this->server->name = "[PMC] ".$this->servname;
		$this->api->schedule(1200, array($this, "minuteSchedule"), array(), true);
		$this->api->console->register("cs", "commandshop", array($this, "handleCommand"));
		$this->config = new Config($this->api->plugin->configPath($this) . "config.yml", CONFIG_YAML);
		/*
		
		we will add these commands
		
		$this->api->console->alias("deposite", "bank");
		$this->api->console->alias("withdraw", "bank");
		$this->api->console->alias("loan", "bank");
		$this->api->console->alias("money", "bank");
		*/
	}
	
	public function __destruct(){
		$this->config->save();
	}
	
	public function eventHandler($data, $event){
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		$target = $data->username;
		switch($event){
			case "player.join":
				$target = $data->username;
				if(!array_key_exists($target, $cfg))
				{
					$this->api->plugin->createConfig($this,array(
							$target => array(
									'bank' => DEFAULT_ASSETS, 
									'loans' => DEFAULT_LOANS,
									'point' => DEFAULT_POINT,
									"point - on/off" => DEFAULTR_CON
							)
					));
					$this->api->chat->broadcast("[Bank]$target has registered to the bank.\n");
					$this->api->chat->broadcast("** Current Players: ".count($this->api->player->getAll())."/".$this->server->maxClients);
				}
				break;
			case "bank.handle":
			if(!isset($data['username']) or !isset($data['method']) or !isset($data['amount']) or !is_numeric($data['amount'])) return false;
			$issuer = isset($data['issuer']) ? $data['issuer'] : "external";
			$target = $data['username'];
			$method = $data['method'];
			$amount = $data['amount'];
			if ($this->api->player->get($target) === false or !$this->config->exists($target))
			{
					return false;
			}
			switch ($method) {
				case "grant":
					case "grant":
						$targetMoney = $this->config->get($target)['bank'] + $amount;
						if($targetMoney < 0) return false;
						$this->config->set($target, array('bank' => $targetMoney));
						break;
					default:
						return false;
				}
				$this->api->dhandle("bank.change", array(
						'issuer' => $issuer,
						'target' => $target,
						'method' => $method,
						'amount' => $amount
				)
				);
				$this->config->save();
				return true;
			case "bank.player.get":
				if ($this->config->exists($data['username'])) {
					return $this->config->get($data['username'])['bank'];
				}
				return false;
			case "server.stop":
			$this->config->save();
		}
	}
	
	public function handleCommand($cmd, $args, $issuer, $alias){
		$output = "";
		switch($cmd){
			case "bank":
			if (!($issuer instanceof Player))
			{
				$output .= "Please use this command in the game.\n";
				break;
			}
			$subCommand = strtolower(array_shift($args));
			$username = $issuer->username;
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "help":
					case "":
					  $output .= "===[Bank :: Commands]===\n";
					  $output .= "===[For more informaiton use help2]==\n";
					  $output .= "[Bank]/bank :: shows commands\n";
					  $output .= "[Bank]/bank deposite (amount) :: put in your pocketmoney into the bank\n";
					  $output .= "[Bank]/bank withdraw (amount) :: take out your pocketmoney from the bank\n";
					  	break;
					 case "help2":
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
						$money = $cfg[$username]['bank'];
						$output .= "[Bank]You have $money in your bank account\n";
						break;
					case "deposite":
						$playerBank = $username;
						$amount = array_shift($args);
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
										'amount' => -$amount
								));
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney
								),
								);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have deposited to your bank acount safely: ".$amount."\n";
						break;
					case "withdraw":
						$playerBank = $username;
						$amount = array_shift($args);
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
										'amount' => $amount
								));
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney
								),
								);
						$this->overwriteConfig($result);
						$output .= "[Bank]You have withdrawn to your bank acount safely: ".$amount."\n";
						break;
					case "loan":
						$loanAmount = array_shift($args);
						$playerBank = $username;
						$bankMoney = $cfg[$playerBank]['bank'];
						$loans = $cfg[$playerBank]['loans'];
						if(!is_numeric($loanAmount) or $loanAmount <= 0 or $loanAmount >= 3000 or $loans > 0)
						{
							$output .= "[Bank]check your loan amount / check your input number / check your spelling\n";
							$output .= "[Bank]make sure that your loan amount is smaller than 3000\n";
							$output .= "[Bank]make sure that you don't have any lonas!!\n";
							break;
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
						break;
					case "payloan":
						$payAmount = array_shift($args);
						$playerBank = $username;
						$bankMoney = $cfg[$playerBank]['bank'];
						$loans = $cfg[$playerBank]['loans'];
						if(!is_numeric($payAmount) or $payAmount <= 0 or $bankMoney < $payAmount or $loans > $payAmount)
						{
							$output .= "[Bank]check your bank money / check your imput number / check your spelling\n";
							break;
						}
						$totalLoans = $loans - $payAmount;
						$bankMoney -= $payAmount;
						$result = array(
								$playerBank => array(
										'bank' => $bankMoney, 
										'loans' => $totalLoans
								),
						);
						$this->overwriteConfig($result);
						$output .= "[Bank]Paid back your loan... come back again!!\n";
				}
				break;
			case "point":
			if (!($issuer instanceof Player))
			{
				$output .= "Please use this command in the game.\n";
				break;
			}
			$subCommand = strtolower(array_shift($args));
			$username = $issuer->username;
			$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
			switch($subCommand){
				case "help":
				    $output .= "===[point :: Commands]===\n";
				    $output .= "===[point :: Point Commands]==\n";
				    $output .= "[point]/point help :: shows commands\n";
				    $output .= "[point]/point check ::check you points\n";
				    $output .= "[point]/point exchange (amount) :: exchange points into money\n";
				case "check":
				    $target = $username;
					if(!array_key_exists($target, $cfg))
					{
						$output .= "[point]You don't have a bank account\n";
						break;
					}
					$pts = $cfg[$username]['point'];
					$output .= "[point]You have $pts in your bank account\n";
					break;
				case "exchange":
					$ptp = $username;
					$pt = $cfg[$ptp]['point'];
					$input = array_shift($args);
					$pm = $this->api->dhandle("money.player.get", array('username' => $username));
					if(!is_numeric($input) or $input <= 0 or $pt <= $input)
					{
					$output .= "[point]Check your points\n";
					}
					$pt -= $input;
					$pm += $input;
					$this->api->dhandle("money.handle", array(
											'username' => $playerBank,
											'method' => 'grant',
											'amount' => $input
									));
					$result = array(
									$ptp => array(
											'point' => $pt
									),
							);
					$this->overwriteConfig($result);
					$output .= "[point]Exchanged $input points to $input PM\n";
					break;
					case "use":
					$pop = $cfg[$username]['point - on/off'];
					$res = array(
							$pop => array(
									'point - on/off' =>1
							)
					);
					$this->overwriteConfig($res);
				}
				case "cs":
				if($issuer instanceof Player)
				{
					$output .= "[CS] Run this command in game \n";
				}
				$subCommand = strtolower(array_shift($args));
				$username = $issuer->username;
				$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch ($subCommand){
					case "buy":
					$items = array_shift($args);
					if(!is_numeric($items))
					{
						$output .= "[CS]Please enter the ID of the block / item" .$item. "is not a correct number \n";
					}
					$this->api->console->run("give", $items);
					$cost = $items * 10;
					$this->api->dhandle("bank.handle", array(
									'username' => $issuer,
									'method' => 'grant',
									'amount' => -$cost
					));
					$output .= "[CS]You have successfully bought from the Command shop \n";
				}
		}
		return $output;
	}
	
		public function minuteSchedule()
	{
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		$target2 = $this->api->player->get(username);
		$returned = $cfg[$target2];
		$this->minute--;
		if($cfg[$target2]["point - on/off"] = 0)
		{
			if($this->minute += 2)
			{
				$amts = $cfg[$taret2]['bank'] % 1000;
				$finalreturned = array(
						$returned => array(
								'point' + $amts
						)
				);
				$this->overwriteConfig($finalreturned);
			}
		}
		if($this->minute += 5)
		{
			$amts2 = $cfg[$target2]['bank'] % 500;
			$finalreturned2 = array(
					$returned => array(
							'point' + $amts2
					)
			);
			$this->overwriteConfig($finalreturned2);
			$this->api->chat->broadcast("[PMC]" .$this->servname. "'s players' bank credit went up!!");
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
