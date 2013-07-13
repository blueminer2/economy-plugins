<?php

/*
__PocketMine Plugin__
name=command shop
version=0.0.1
author=miner&omattyao
class=cs
apiversion=9
*/

class ExamplePlugin implements Plugin{
	private $api;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		$this->api->ban->cmdWhitelist("bank");
		$this->api->console->register("cs", "command shop gives you access to the admin shop anywhere / anytime you want", array($this, "handleCommand"));
		$this->path = $this->api->plugin->createConfig($this, array(
				"air" => 0,
				"stone" => 0,
				"grass" => 0,
				"dirt" => 0,
				"cobblestone" => 0,
				"woodenplank" => 0,
				"sapling" => 0,
				"bedrock" => 0,
				"water" => 0,
				"stationarywater" => 0,
				"lava" => 0,
				"stationarylava" => 0,
				"sand" => 0,
				"gravel" => 0,
				"goldore" => 0,
				"ironore" => 0,
				"coalore" => 0,
				"wood" => 0,
				"leaves" => 0,
				"sponge" => 0,
				"glass" => 0,
				"lapislazuliore" => 0,
				"lapislazuliblock" => 0,
				"dispenser" => 0,
				"sandstone" => 0,
				"noteblock" => 0,
				"bed" => 0,
				"poweredrail" => 0,
				"detectorrail" => 0,
				"stickypiston" => 0,
				"cobweb" => 0,
				"tallgrass" => 0,
				"deadshrub" => 0,
				"wool" => 0,
				"yellowflower" => 0,
				"cyanflower" => 0,
				"brownmushroom" => 0,
				"redmushroom" => 0,
				"blockofgold" => 0,
				"blockofiron" => 0,
				"stoneslab" => 0,
				"brick" => 0,
				"tnt" => 0,
				"bookcase" => 0,
				"mossstone" => 0,
				"obsidian" => 0,
				"torch" => 0,
				"fire" => 0,
				"mobspawner" => 0,
				"woodenstairs" => 0,
				"diamondore" => 0,
				"blockofdiamond" => 0,
				"workbench" => 0,
				"wheat" => 0,
				"farmland" => 0,
				"furnace" => 0,
				"wooddoor" => 0,
				"ladder" => 0,
				"rails" => 0,
				"cobblestonestairs" => 0,
				"irondoor" => 0,
				"redstoneore" => 0,
				"snow" => 0,
				"ice" => 0,
				"snowblock" => 0,
				"cactus" => 0,
				"clayblock" => 0,
				"sugarcane" => 0,
				"fence" => 0,
				"netherrack" => 0,
				"soulsand" => 0,
				"glowstone" => 0,
				"trapdoor" => 0,
				"stonebricks" => 0,
				"brownmushroom" => 0,
				"redmushroom" => 0,
				"glasspane" => 0,
				"melon" => 0,
				"melonvine" => 0,
				"fencegate" => 0,
				"brickstairs" => 0,
				"stonebrickstairs" => 0,
				"netherbrick" => 0,
				"netherbrickstairs" => 0,
				"sandstonestairs" => 0,
				"blockofquartz" => 0,
				"quartzstairs" => 0,
				"stonecutter" => 0,
				"glowingobsidian" => 0,
				"netherreactorcore" => 0,
				"ironshovel" => 0,
				"ironpickaxe" => 0,
				"ironaxe" => 0,
				"flintandsteel" => 0,
				"apple" => 0,
				"bow" => 0,
				"arrow" => 0,
				"coal" => 0,
				"diamondgem" => 0,
				"ironingot" => 0,
				"goldingot" => 0,
				"ironsword" => 0,
				"woodensword" => 0,
				"woodenshovel" => 0,
				"woodenpickaxe" => 0,
				"woodenaxe" => 0,
				"stonesword" => 0,
				"stoneshovel" => 0,
				"stonepickaxe" => 0,
				"stoneaxe" => 0,
				"diamondsword" => 0,
				"diamondshovel" => 0,
				"diamondpickaxe" => 0,
				"diamondaxe" => 0,
				"stick" => 0,
				"bowl" => 0,
				"mushroomstew" => 0,
				"goldsword" => 0,
				"goldshovel" => 0,
				"goldpickaxe" => 0,
				"goldaxe" => 0,
				"string" => 0,
				"feather" => 0,
				"gunpowder" => 0,
				"woodenhoe" => 0,
				"stonehoe" => 0,
				"ironhoe" => 0,
				"diamondhoe" => 0,
				"goldhoe" => 0,
				"wheatseeds" => 0,
				"wheat" => 0,
				"bread" => 0,
				"leatherhelmet" => 0,
				"leatherchestplate" => 0,
				"leatherleggings" => 0,
				"leatherboots" => 0,
				"chainmailhelmet" => 0,
				"chainmailchestplate" => 0,
				"chainmailleggings" => 0,
				"chainmailboots" => 0,
				"ironhelmet" => 0,
				"ironchestplate" => 0,
				"ironleggings" => 0,
				"ironboots" => 0,
				"diamondhelmet" => 0,
				"diamondchestplate" => 0,
				"diamondleggings" => 0,
				"diamondboots" => 0,
				"goldhelmet" => 0,
				"goldchestplate" => 0,
				"goldleggings" => 0,
				"goldboots" => 0,
				"flint" => 0,
				"rawporkchop" => 0,
				"cookedporkchop" => 0,
				"painting" => 0,
				"goldapple" => 0,
				"sign" => 0,
				"snowball" => 0,
				"leather" => 0,
				"claybrick" => 0,
				"clay" => 0,
				"sugarcane" => 0,
				"paper" => 0,
				"book" => 0,
				"egg" => 0,
				"glowstonedust" => 0,
				"bone" => 0,
				"sugar" => 0,
				"bed" => 0,
				"shears" => 0,
				"melon" => 0,
				"melonseeds" => 0,
				"rawbeef" => 0,
				"steak" => 0,
				"rawchicken" => 0,
				"cookedchicken" => 0,
				"netherbrick" => 0,
				"camera" => 0
		));
	}
	
	public function __destruct(){
	
	}
	
	public function handleCommand($cmd, $arg){
		$output = "";
		switch($cmd){
			case "cs":
				if (!($issuer instanceof Player))
				{
					$output .= "Please use this command in game.\n";
					break;
				}else{
					$output .= "[Command Shop Commands]\n";
					$output .= "[cs]/cs [shows commands]\n";
					$output .= "[cs]/cs buy (item-code / item-name) [buys items]\n";
					$output .= "===========================\n";
					break;
				}
				$subCommand = $args[0];
				$username = $issuer->username;
				$cfg = $this->api->plugin->readYAML($this->path . "config.yml");
				switch($subCommand){
					case "buy":
					$itemcode = $args[1];
					$blocklists = $this->blockcodes;
					$itemlists = $this->itemcodes;
					if (!($issuer instanceof Player))
					{
						$output .= "Please use this command in game\n";
					}
					if(!($itemcode === $blocklists or $itemcode === $itemlists))
					{
						$output .= "[cs]please check the itemcode / name\n";
					}else{
					$cmd = "give";
					$params = array($username, $itemcode);
					$issuer = $this->api->player->get($username);
					$alias = false;
					$this->api->block->commandHandler($cmd, $params, $issuer, $alias);
					$playerMoney = $this->api->dhandle("money.player.get", array('username' => $username));
					$price = $cfg;
					if($playerMoney < $amount)
					{
						$output .= "[PocketMoney]You don't have enough money to buy from Command Shop";
					}
					$playerMoney -= $price;
					$this->api->dhandle("money.handle", array(
										'username' => $playerBank,
										'method' => 'grant',
										'amount' => -$price
								));
					}
				}
			break;
		}
		return $output;
	}
	public function blockcodes (){
	$blocks = array(
		 	0 => "air",
			1 => "stone",
			2 => "grass",
			3 => "dirt",
			4 => "cobblestone",
			5 => "woodenplank",
			6 => "sapling",
			7 => "bedrock",
			8 => "water",
			9 => "stationarywater",
			10 => "lava",
			11 => "stationarylava",
			12 => "sand",
			13 => "gravel",
			14 => "goldore",
			15 => "ironore",
			16 => "coalore",
			17 => "wood",
			18 => "leaves",
			19 => "sponge",
			20 => "glass",
			21 => "lapislazuliore",
			22 => "lapislazuliblock",
			23 => "dispenser",
			24 => "sandstone",
			25 => "noteblock",
			26 => "bed",
			27 => "poweredrail",
			28 => "detectorrail",
			29 => "stickypiston",
			30 => "cobweb",
			31 => "tallgrass",
			32 => "deadshrub",
			35 => "wool",
			37 => "yellowflower",
			38 => "cyanflower",
			39 => "brownmushroom",
			40 => "redmushroom",
			41 => "blockofgold",
			42 => "blockofiron",
			44 => "stoneslab",
			45 => "brick",
			46 => "tnt",
			47 => "bookcase",
			48 => "mossstone",
			49 => "obsidian",
			50 => "torch",
			51 => "fire",
			52 => "mobspawner",
			53 => "woodenstairs",
			56 => "diamondore",
			57 => "blockofdiamond",
			58 => "workbench",
			59 => "wheat",
			60 => "farmland",
			61 => "furnace",
			62 => "furnace",
			64 => "wooddoor",
			65 => "ladder",
			66 => "rails",
			67 => "cobblestonestairs",
			71 => "irondoor",
			73 => "redstoneore",
			78 => "snow",
			79 => "ice",
			80 => "snowblock",
			81 => "cactus",
			82 => "clayblock",
			83 => "sugarcane",
			85 => "fence",
			87 => "netherrack",
			88 => "soulsand",
			89 => "glowstone",
			96 => "trapdoor",
			98 => "stonebricks",
			99 => "brownmushroom",
			100 => "redmushroom",
			102 => "glasspane",
			103 => "melon",
			105 => "melonvine",
			107 => "fencegate",
			108 => "brickstairs",
			109 => "stonebrickstairs",
			112 => "netherbrick",
			114 => "netherbrickstairs",
			128 => "sandstonestairs",
			155 => "blockofquartz",
			156 => "quartzstairs",
			245 => "stonecutter",
			246 => "glowingobsidian",
			247 => "netherreactorcore"
	);
	}
	public function itemcodes (){
		$items = array(
			256 => "ironshovel",
			257 => "ironpickaxe",
			258 => "ironaxe",
			259 => "flintandsteel",
			260 => "apple",
			261 => "bow",
			262 => "arrow",
			263 => "coal",
			264 => "diamondgem",
			265 => "ironingot",
			266 => "goldingot",
			267 => "ironsword",
			268 => "woodensword",
			269 => "woodenshovel",
			270 => "woodenpickaxe",
			271 => "woodenaxe",
			272 => "stonesword",
			273 => "stoneshovel",
			274 => "stonepickaxe",
			275 => "stoneaxe",
			276 => "diamondsword",
			277 => "diamondshovel",
			278 => "diamondpickaxe",
			279 => "diamondaxe",
			280 => "stick",
			281 => "bowl",
			282 => "mushroomstew",
			283 => "goldsword",
			284 => "goldshovel",
			285 => "goldpickaxe",
			286 => "goldaxe",
			287 => "string",
			288 => "feather",
			289 => "gunpowder",
			290 => "woodenhoe",
			291 => "stonehoe",
			292 => "ironhoe",
			293 => "diamondhoe",
			294 => "goldhoe",
			295 => "wheatseeds",
			296 => "wheat",
			297 => "bread",
			298 => "leatherhelmet",
			299 => "leatherchestplate",
			300 => "leatherleggings",
			301 => "leatherboots",
			302 => "chainmailhelmet",
			303 => "chainmailchestplate",
			304 => "chainmailleggings",
			305 => "chainmailboots",
			306 => "ironhelmet",
			307 => "ironchestplate",
			308 => "ironleggings",
			309 => "ironboots",
			310 => "diamondhelmet",
			311 => "diamondchestplate",
			312 => "diamondleggings",
			313 => "diamondboots",
			314 => "goldhelmet",
			315 => "goldchestplate",
			316 => "goldleggings",
			317 => "goldboots",
			318 => "flint",
			319 => "rawporkchop",
			320 => "cookedporkchop",
			321 => "painting",
			322 => "goldapple",
			323 => "sign",
			332 => "snowball",
			334 => "leather",
			336 => "claybrick",
			337 => "clay",
			338 => "sugarcane",
			339 => "paper",
			340 => "book",
			344 => "egg",
			348 => "glowstonedust",
			352 => "bone",
			353 => "sugar",
			355 => "bed",
			359 => "shears",
			360 => "melon",
			362 => "melonseeds",
			363 => "rawbeef",
			364 => "steak",
			365 => "rawchicken",
			366 => "cookedchicken",
			405 => "netherbrick",
			456 => "camera"
	);
	}
}