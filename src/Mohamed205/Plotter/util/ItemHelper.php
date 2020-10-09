<?php

namespace Mohamed205\Plotter\util;

use Mohamed205\Plotter\Main;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

class ItemHelper
{

    private Main $plugin;
    private int $wandId;
    private string $lore;
    private string $customName;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = $plugin->getConfig();
        $this->wandId = $config->get("wand_item_id");
        $this->lore = $config->get("lore");
        $this->customName = $config->get("custom_name");
    }

    public function getWandItem() : Item
    {

        $item = Item::get($this->wandId);
        $nbt = $item->getNamedTag();
        $nbt->setInt("wand_item", 1);
        $item->setLore([$this->lore]);
        $item->setCustomName($this->customName);
        return $item;
    }

    public function isWandItem(Item $item) : bool
    {
        return $item->getNamedTag()->getInt("wand_item", 0) == 1;
    }


}