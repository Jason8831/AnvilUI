<?php

namespace Jason8831\Anvil;

use Jason8831\Anvil\Events\InteracAnvil;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

    public Config $config;
    /**
     * @var Main
     */
    private static $instance;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->getLogger()->info("§f[§l§4AnvilUI§r§f]: activée");
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents(new InteracAnvil(), $this);
    }

    public static function getInstance(): self{
        return self::$instance;
    }
}