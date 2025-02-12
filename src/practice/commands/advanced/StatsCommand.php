<?php

declare(strict_types=1);

namespace practice\commands\advanced;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use practice\commands\BaseCommand;
use practice\commands\parameters\BaseParameter;
use practice\commands\parameters\Parameter;
use practice\commands\parameters\SimpleParameter;
use practice\player\PracticePlayer;
use practice\PracticeCore;
use practice\PracticeUtil;

class StatsCommand extends BaseCommand{
	public function __construct(){
		parent::__construct("stats", "The base stats command.", "/stats help");
		$parameters = [
			0 => [
				new BaseParameter("help", Parameter::NO_PERMISSION, "Displays all of the stats commands.")
			],
			1 => [
				new BaseParameter("me", Parameter::NO_PERMISSION, "Displays your stats.")
			],
			2 => [
				new SimpleParameter("player-name", Parameter::PARAMTYPE_TARGET, Parameter::NO_PERMISSION, "Displays the stats of another player.")
			],
			3 => [
				new BaseParameter("reset", $this->getPermission(), "Resets the server stats.")
			]
		];
		$this->setParameters($parameters);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{

		$msg = null;

		if($this->canExecute($sender, $args)){

			$name = strval($args[0]);

			switch($name){
				case "help":
					$msg = $this->getFullUsage();
					break;
				case "me":
					$this->getStats($sender);
					break;
				case "reset":
					$this->resetStats($sender);
					break;
				default:
					$this->getStats($sender, $name);
			}
		}

		if(!is_null($msg)) $sender->sendMessage($msg);
	}

	/**
	 * @param CommandSender $sender
	 * @param string|null   $player
	 *
	 * @return void
	 */
	private function getStats(CommandSender $sender, string $player = null) : void{
		$msg = null;
		$statsOf = null;

		if($player === null){
			if($sender instanceof Player){
				$statsOf = PracticeCore::getPlayerHandler()->getPlayer($sender->getName());
			}else $msg = PracticeUtil::getMessage("console-usage-command");
		}else{
			if(PracticeCore::getPlayerHandler()->isPlayerOnline($player)){
				$statsOf = PracticeCore::getPlayerHandler()->getPlayer($player);
			}else{
				$msg = PracticeUtil::getMessage("not-online");
				$msg = strval(str_replace("%player-name%", $player, $msg));
			}
		}

		if(!is_null($statsOf) and $statsOf instanceof PracticePlayer){
			if(PracticeUtil::canExecBasicCommand($sender, true, true)){
				$msg = "";
				$arr = PracticeCore::getPlayerHandler()->getStats($statsOf->getPlayerName(), false);
				$keys = array_keys($arr);
				foreach($keys as $key){
					$value = $arr[$key] . "\n";
					$msg .= $value;
				}
			}
		}

		if(!is_null($msg)) $sender->sendMessage($msg);
	}

	/**
	 * @param CommandSender $sender
	 *
	 * @return void
	 */
	private function resetStats(CommandSender $sender) : void{
		if(PracticeUtil::canExecBasicCommand($sender, true)) PracticeCore::getPlayerHandler()->resetStats();
	}
}