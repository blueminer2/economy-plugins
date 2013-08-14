<?php

/*
__PocketMine Plugin__
name=PocketMoneyAirlines
version=0.0.1
author=miner&omattyao&chaosruin
class=airline
apiversion=9
*/

define("DEFAULT_ASSETS", 100);
define("DEFAULT_LOANS", 0);
define("DEFAULT_POINT", 100);

class bank implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		foreach($this->api->plugin->getList() as $p)
		{
            if($p["name"] == "PocketMoney")
			{
                $exist = true;
                break;
			}
		}
		if(!isset($exist)){
           console("[ERROR]PocketMoney not found");
           $this->api->console->defaultCommands("stop", "", "PocketMoneyAirlines", false);
		   if(!file_exists("./plugins/PMairlines"))
		   {
			   mkdir("./plugins/PMairlines");
		   }
		   $this->api->event("tile.update", array($this, "eventHandler"));
		   $this->api->event("server.close", array($this, "eventHandler"));
		   $this->api->event("player.block.touch", array($this, "eventHandler"));
		   $this->dep = new Config("./plugins/PMairlines/Departuredata.yml", CONFIG_YAML);
		   $this->arr = new Config("./plugins/PMairlines/arrivalsdata.yml", CONFIG_YAML);
		   $this->config = new Config("./plugins/PMairlines/airlinesconfig.yml", CONFIG_YAML, array(
		   			"business-class" => "true",
					"planecrash-enabled" => "true",
					"planecrash-rate" => 1,
		   ));
        }
	}
	
	public function __destruct(){
	
	}
	
	public function eventHandler($data, $event){
		$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
		switch($event){
			case "player.block.touch":
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
				  $output .= "===[Bank :: Commands]===\n";
				  $output .= "===[Bank :: Point Commands]==\n";
				  $output .= "[Bank]/point help :: shows commands\n";
				  $output .= "[Bank]/point check ::check you points\n";
				  $output .= "[Bank]/point exchange (amount) :: exchange points into money\n";
				case "check":
				$target = $username;
						if(!array_key_exists($target, $cfg))
						{
							$output .= "[Bank]You don't have a bank account\n";
							break;
						}
						$pts = $cfg[$username]['point'];
						$output .= "[Bank]You have $pts in your bank account\n";
						break;
				case "exchange":
				$ptp = $username;
				$pt = $cfg[$ptp]['point'];
				$input = array_shift($args);
				$pm = $this->api->dhandle("money.player.get", array('username' => $username));
				if(!is_numeric($input) or $input <= 0 or $pt <= $input)
				{
					$output .= "[Bank]Check your points\n";
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
				$output .= "[Bank]Exchanged $input points to $input PM\n";
			}
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
