<?php

namespace Jason8831\Anvil\Events;

use Jason8831\Anvil\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class InteracAnvil implements Listener
{

    public function ItecracrtAnvil(PlayerInteractEvent $event)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        if ($event->getBlock()->getId() === 145) {
            if ($event->getPlayer()->getInventory()->getItemInHand()->getId() !== 0) {
                $this->sendMenu($player);
            } else {
                $player->sendMessage($config->get("NoItem"));
            }
            $event->cancel();
        }
    }


    public function sendMenu(Player $player): void
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $ui = new SimpleForm(function (Player $player, $data) use ($config) {
            if (is_null($data)) return;
            switch ($data) {
                case 0:
                    if($player->getXpManager()->getXpLevel() >= $config->get("XPRepair")) {
                        $item = $player->getInventory()->getItemInHand();
                        if ($item instanceof Durable) {
                            if ($item->getDamage() >= 5) {
                                $item->setDamage(0);
                                $player->getInventory()->setItemInHand($item);
                                $player->sendMessage($config->get("SuccesRepair"));
                                $player->getXpManager()->subtractXpLevels($config->get("XPRepair"));
                            } else {
                                $player->sendMessage($config->get("NoUsed"));
                            }
                        } else {
                            $player->sendMessage($config->get("NoDurability"));
                        }
                    }else{
                        $player->sendMessage($config->get("NoXpRepair"));
                    }
                    break;
                case 1:
                    self::RenameUse($player);

            }
        });
        $ui->setTitle($config->get("Title"));
        $ui->setContent($config->get("Contente"));
        $ui->addButton($config->get("BoutonRepair"));
        $ui->addButton($config->get("BoutonRename"));
        $ui->sendToPlayer($player);
    }

    public static function RenameUse(Player $player): CustomForm
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $ui = new CustomForm(function (Player $player, array $data = null) use ($config) {
            if ($data === null) {
                $player->sendMessage($config->get("NoRename"));
                return;
            }
                    if ($player->getXpManager()->getXpLevel() >= $config->get("XPRename")) {
                        $item = $player->getInventory()->getItemInHand();
                        $item->setCustomName($data[1]);
                        $player->getInventory()->setItemInHand($item);
                        $player->getXpManager()->subtractXpLevels($config->get("XPRename"));
                        $messagesucces = str_replace("{Name}", $data[1], $config->get("SuccesRename"));
                        $player->sendMessage($messagesucces);
                    }else {
                        $player->sendMessage($config->get("NoXp"));
                    }
            });
    $ui->setTitle($config->get("RenameTitle"));
    $ui->addLabel($config->get("Label"));
    $ui->addInput($config->get("inpute"));
    $ui->sendToPlayer($player);
    return $ui;
    }
}