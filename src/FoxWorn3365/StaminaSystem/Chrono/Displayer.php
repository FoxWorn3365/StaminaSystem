<?php

namespace FoxWorn3365\StaminaSystem\Chrono;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;

class Displayer extends Task {
    protected ?object $stamina;
    protected string $text;
    protected Player $player;
    protected string $placeholder = "%stamina%";
    protected string $totalplaceholder = "%totalstamina%";

    function __construct(object $stamina, Player $player, string $text) {
        $this->stamina = $stamina->{$player->getName()};
        $this->player = $player;
        $this->text = $text;
    }

    public function onRun() : void {
        if ($this->stamina === null) {
            return;
        }

        if (!$this->player->isConnected()) {
            return;
        }

        // Round the stamina if requested
        if ($this->stamina->round) {
            $this->stamina->stamina = round($this->stamina->stamina, $this->stamina->roundlevel);
        }

        // Valid stamina object for player
        $this->player->sendTip(str_replace($this->placeholder, $this->stamina->stamina, str_replace($this->totalplaceholder, $this->stamina->maxstamina, $this->text)));
    }
}