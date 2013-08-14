<?php
/*
__PocketMine Plugin__
name=AutoMessage
description=AutoMessage
version=1.0
author=AndKmh
class=AutoMessage
apiversion=9
*/

/*

변경사항
========

1.0Error

*/

class AutoMessage implements Plugin{
	private $secondsLeft, $api, $path, $empty;
public function __construct(ServerAPI $api, $server =false){$this->api =$api;
$this->secondsLeft =0;
$this->empty =false;
}public function init(){
	$this->api->schedule(200 ,array($this, 'PostingTimer'), array(), true, 'server.schedule');
$this->api->console->register('rmmgs', 'Remove messages!', array($this, 'RemoveMessage'));
$this->api->console->register('addmgs', 'Add messages!', array($this, 'AddMessage'));
$this->path =$this->api->plugin->createConfig($this, array("Minutes for broadcast" => array ("Minutes" => 10),"Messages" => array("1" => "","2" => "","3" => "","4" => "","5" => "","6" => "","7" => "","8" => "","9" => "","10" => "")));
}public function __destruct() {}public function AddMessage($cmd, $arg, $issuer) {$cfg =$this->api->plugin->readYAML($this->path ."config.yml");
switch($cmd) {case "addmgs":$mgs =$arg;
if($this->api->ban->isOp($issuer) === true) {$empty =false;
$mNum =11;
foreach($cfg as $Messages => $elements) {if ($Messages == "Messages") {while($empty == false) {if ($mNum != 0) {--$mNum;
if ($cfg['Messages']["$mNum"] === "") {$this->arrayReplace($mNum, $mgs);
 $output .= "Message Added!";
$empty =true;
 break;
}}else {$output .= "There is no available space to add your message. please delete one!";
}}}}}else {$output .= "You have no permission";
}}return $output;
}public function RemoveMessage($cmd, $arg, $issuer) {switch($cmd) {case "rmmgs":if($this->api->ban->isOp($issuer) === false) {$num =$arg[0];
$this->arrayReplace($num, "");
$output .= "Message removed!";
}else {$output .= "You have no permission";
}}return $output;
}public function PostingTimer() {$cfg =$this->api->plugin->readYAML($this->path ."config.yml");
 $minutes =$cfg["Minutes for broadcast"]["Minutes"];
 $seconds =$minutes *60;
 $checkSeconds =$seconds /300;
$this->secondsLeft =$this->secondsLeft -10;
if (!is_int($checkSeconds)) {console("[AutoBroadcast] Minutes must be at least 5!");
 console("[AutoMessage] No message will be broadcasted if the time is not change in the config!");
 }else {if ($this->secondsLeft <= 0) {$this->secondsLeft =$seconds;
$test =11;
 while($this->empty === false) {if($test === 0) {break;
$this->empty =true;
} --$test;
 if($cfg["Messages"]["$test"] == "") {$this->empty =false;
break;
 }}$success =false;
 while($success == false) {$rand =rand(1, 10);
 if ($cfg["Messages"]["$rand"] == "") {$success =false;
 $rand =rand(1, 20);
 }else {$success =true;
 $this->api->chat->broadcast($cfg["Messages"]["$rand"]);
 }}}}}private function findKey($num) {$cfg =$this->api->plugin->readYAML($this->path ."config.yml");
$text =$cfg["Messages"][$num];
return $text;
 }private function arrayReplace($lineToReplace, $message) {$array= array("Messages" => array());
 $done =false;
 $nums =1;
 while ($done == false) {if($nums == $lineToReplace) {$array["Messages"][$lineToReplace] =implode(' ',$message);
 }else {$array["Messages"]["$nums"] =$this->findKey($nums);
 }++$nums;
 if ($nums == 11) {break;
 }}safe_var_dump($array);
 $this->overwriteConfig($array);
 }private function overwriteConfig($dat){ $cfg =array();
 $cfg =$this->api->plugin->readYAML($this->path ."config.yml");
 $result =array_merge($cfg, $dat);
 $this->api->plugin->writeYAML($this->path."config.yml", $result);
 }}