<?php

namespace FoxWorn3365\StaminaSystem\Chrono;

use pocketmine\scheduler\Task;

class Backup extends Task {
    protected string $dir;
    protected object $stamina;

    function __construct(object $stamina, string $dir) {
        $this->stamina = $stamina;
        $this->dir = $dir;
    }

    public function onRun() : void {
        // Run the async task to save all player data
        file_put_contents("{$this->dir}playerdata.json", json_encode($this->stamina));
    }
}