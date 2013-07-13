<?php

/*
__PocketMine Plugin__
name=house
version=0.0.1
author=miner&omattyao
class=house
apiversion=7
*/

class house implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->console->register("houses", "This plugin will let you build houses with a command", array($this, "handleCommand"));
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output .= "";
		switch($cmd){
			case "houses":
				$output .= "===[Showing Availible Commands for the House plugin]===\n";
				$output .= "[house]/houses build (0 | 1 | 2) (bank | PM) \n";
				$output .= "[house]You will have to select which type and pay method";
				$output .= "===[Notice!! when you build house with this command, your money will go out]===";
				break;
				$subCommand = $args[0];
				switch($subCommand){
					case "build":
					/*
					
					you need to make the types of houses first
					
					0 should be about 5blocks on each sides
					
					 ㅡㅡㅡㅡㅡ
					|		  |
					|		  |
					|		  |
					|		  |
					|		  |
					 ㅡㅡㅡㅡㅡ
					 
					like this and for the height...
					
					6blocks will do
					
					and type 1 shouda be maybe about 15blocks on each sides?
					
					for type 2... 30 blocks will do
					
					you don't have to make it so impressive (you can use prisons from prison plugin and change it a bit)
					
					after you're done, I will implement the money part :D
					
					*/
					break;
				}
		}
	return $output;
	}
}
