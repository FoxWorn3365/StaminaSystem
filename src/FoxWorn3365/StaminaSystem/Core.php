<?php
declare(strict_types=1);

namespace FoxWorn3365\StaminaSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerMoveEvent as PlayerMove;
use pocketmine\event\player\PlayerJoinEvent as PlayerJoin;
use pocketmine\event\player\PlayerBedEnterEvent as PlayerSleep;
use pocketmine\event\entity\EntityDamageEvent as Damage;
use pocketmine\event\entity\EntityDamageByEntityEvent as DuplexDamage;
use pocketmine\event\player\PlayerItemConsumeEvent as PlayerEat;
use pocketmine\event\inventory\InventoryEvent;
use pocketmine\player\Player;
use pocketmine\Server;

use FoxWorn3365\StaminaSystem\Chrono\Backup;
use FoxWorn3365\StaminaSystem\Chrono\Displayer;

class Core extends PluginBase implements Listener {
    protected object $stamina;
    protected string $defaultConfig = "IyArLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKwojIHwgICAgIFN0YW1pbmFTeXN0ZW0gdjAuNiAgICB8CiMgKy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSsKIyB8IEF1dGhvcjogRm94V29ybjMzNjUKIyB8IEdpdEh1YjogaHR0cHM6Ly9naXRodWIuY29tL0ZveFdvcm4zMzY1CiMgfCBMaWNlbnNlOiBNSVQKIyB8IFBsZWFzZSBpbmNsdWRlIHRoZSBjcmVkaXRzIQojICstLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0rCmVuYWJsZWQ6IHRydWUgICAgICAgICAgICAgICAgICAgICAgICAgICMgW0JPT0xdIFdoZXRoZXIgdGhlIHBsdWdpbiBpcyBlbmFibGVkCgojIFNUQU1JTkEgT0JUQUlOSU5HIFNZU1RFTSBDT05GSUcKd2FpdGluZy1tb3Zlcy1iZWZvcmUtcmVnZW5lcmF0ZTogMTAgICAgIyBbSU5UXSBIb3cgbXVjaCBtb3ZlbWVudHMgbXVzdCBhIHBsYXllciBkbyBiZWZvcmUgdGhlIHN0YW1pbmEgcmVzdGFydCByZWdlbmVyYXRpbmc/Cm1heC1zdGFtaW5hOiAxMDAgICAgICAgICAgICAgICAgICAgICAgICMgW0lOVF0gVGhlIG1heCBzdGFtaW5hIGxldmVsCnN0YW1pbmEtcGVyLW1vdmU6IDAuNSAgICAgICAgICAgICAgICAgICMgW0lOVC1GTE9BVChkb3VibGUpXSBIb3cgbXVjaCBzdGFtaW5hIHNob3VsZCBiZSBhZGRlZCBmb3IgZXZlcnkgbW92ZT8KYmVkLXJlc3RvcmUtc3RhbWluYTogZmFsc2UgICAgICAgICAgICAgIyBbQk9PTF0gU2hvdWxkIGJlZCByZXN0b3JlIHN0YW1pbmE/CmJlZC1yZXN0b3JlLXN0YW1pbmEtbGV2ZWw6IDEwICAgICAgICAgICMgW0lOVC1GTE9BVChkb3VibGUpXSBIb3cgbXVjaCBzdGFtaW5hIHNob3VsZCBiZSBnaXZlbiB0byBhIHBsYXllciBobyBoYXZlIHNsZXB0PyBPTkxZIElGIGJlZC1yZXN0b3JlLXN0YW1pbmEgSVMgVFJVRSEKZG8tZm9vZC1yZWdlbjogdHJ1ZSAgICAgICAgICAgICAgICAgICAgIyBbQk9PTF0gRG8gdGhlIGZvb2QgcmVnZW4gdGhlIHN0YW1pbmE/CmxldmVsLWZvci1uZWdhdGl2ZS1lZmZlY3RzOiAxMCAgICAgICAgICMgW0lOVF0gSWYgc3RhbWluYSBpcyA8PSB0byB0aGlzIGxldmVsIHdpbGwgaGF2ZSBzb21lIG5lZ2F0aXZlcyBlZmZlY3QgbGlrZSBzbG93bmVzcyBhbmQgb3RoZXIKCiMgU1RBTUlOQSBQRVJLUwpzdGFtaW5hLWhpdC1jb3N0OiAxICAgICAgICAgICAgICAgICAgICAjIFtJTlQtRkxPQVQoZG91YmxlKV0gSG93IG11Y2ggc3RhbWluYSBkbyB3ZSBuZWVkIHRvIHJlbW92ZSBmcm9tIGEgcGxheWVyIGlmIHdlIGhlbHAgd2l0aCBhIGhpdD8Kc3RhbWluYS1ydW4tY29zdDogMC4xICAgICAgICAgICAgICAgICAgIyBbSU5ULUZMT0FUKGRvdWJsZSldIEhvdyBtdWNoIHN0YW1pbmEgZG8gd2UgaGF2ZSB0byByZW1vdmUgZnJvbSBhIHBsYXllciB3aGVuIGl0IHJ1bj8Kc3RhbWluYS1ibG9jay1icmVhay1jb3N0OiAwLjEgICAgICAgICAgIyBbSU5ULUZMT0FUKGRvdWJsZSldIEhvdyBtdWNoIHN0YW1pbmEgZG8gd2UgaGF2ZSB0byByZW1vdmUgZnJvbSBhIHBsYXllciBpZiB3ZSBoZWxwIGl0IHRvIGJyZWFrIGEgYmxvY2s/CgojIFNUQU1JTkEgUE9XRVJVUApydW4tcmVnZW5lcmF0ZS1zdGFtaW5hOiBmYWxzZQpzdGFtaW5hLXBvd2VydXA6ICAgICAgICAgICAgICAgICAgICAgICAjIFtPQkpFQ1RdIEFsbCBpdGVtcyBpbiB0aGlzIGxpc3Qgd2lsbCBiZSBpbmNyZWFzZSB0aGUgbWF4IHN0YW1pbmEgbGV2ZWwKICBpcm9uX3N3b3JkOiAxMAogIGRpYW1vbmRfc3dvcmQ6IDIwCiAgZGlhbW9uZF9ib290czogNTAKICBkaWFtb25kX2F4ZTogMTAwCgojIEZPT0QgUkVHRU5FUkFUSU9OIFNFVFRJTkdTCnN0YW1pbmEtZm9vZC1yZWdlbjoKICBkZWZhdWx0OiAyCiAgY29va2VkX3BvcmtjaG9wOiA1CiAgZ29sZGVyX2FwcGxlOiAxMAogIGVuY2hhbnRlZF9nb2xkZW5fYXBwbGU6IDc1CiAgYXBwbGU6IDQKCiMgSVRFTSBSRUdFTkVSQVRJT04gU0VUVElOR1MKaXRlbS1yZWdlbmVyYXRpb246CiAgZ29sZGVuX2F4ZToKICAgIHNob3VsZC1iZS1ob2xkZWQ6IHRydWUKICAgIGFtb3VudDogMgoKIyBTVEFNSU5BIE1BTkFHRU1FTlQKc3BlZWQ6CiAgZW5hYmxlZDogdHJ1ZQogIGNvc3Q6IDAuOAogIHBlcms6IDAuMDUKaGl0OgogIGVuYWJsZWQ6IHRydWUKICBjb3N0OiAxCnNoaWVsZDoKICBlbmFibGVkOiB0cnVlCiAgY29zdDogMgpzaGlmdC1rbm9ja2JhY2s6CiAgZW5hYmxlZDogdHJ1ZQogIGNvc3Q6IDAuOAoKIyBESVNQTEFZIFNFVFRJTkdTCnJvdW5kaW5nLXN0YW1pbmE6IHRydWUgICAgICAgICAgICAgICAgICMgW0JPT0xdIFNob3VsZCBzdGFtaW5hIGJlIHJvdW5kZWQgd2hlbiBkaXNwbGF5ZWQgdG8gYSBwbGF5ZXI/CnJvdW5kaW5nLWxldmVsOiAwICAgICAgICAgICAgICAgICAgICAgICMgW0lOVF0gSG93IG11Y2g/CnNob3ctc3RhbWluYS1sZXZlbDogdHJ1ZSAgICAgICAgICAgICAgICMgW0JPT0xdIFNob3VsZCB0aGUgc3RhbWluYSBsZXZlbCBpbmRpY2F0b3IgKGFzIFRpcCkgYmUgZGlzcGxheWVkIHRvIHBsYXllcj8Kc3RhbWluYS1sZXZlbC10ZXh0OiAnU3RhbWluYTogJXN0YW1pbmElLyV0b3RhbHN0YW1pbmElJyAgIyBbU1RSSU5HXSBTZXQgdGhlIHN0YW1pbmEgZGlzcGxheSB0ZXh0LiBQbGFjZWhvbGRlcnM6ICVzdGFtaW5hJSAtPiBjdXJyZW50IHN0YW1pbmEgbGV2ZWwgICAgJXRvdGFsc3RhbWluYSUgLT4gbWF4IHN0YW1pbmEgZm9yIHBsYXllcgpzdGFtaW5hLXJlZnJlc2gtcmF0ZTogMiAgICAgICAgICAgICAgICAjIFtJTlRdIEluIHRpY2ssIGV2ZXJ5IHdoZW4gdGljayhzKSBzaG91bGQgdGhlIHBsYXllciBzdGFtaW5hIHRpcCBiZSByZWZyZXNoZWQgKHNvIHVwZGF0ZWQpPw==";
    protected Config $config;

    public function onLoad() : void {
        $this->stamina = new \stdClass();
    }

    public function onEnable() : void {
        // Create the config folder if it does not exists
        @mkdir($this->getDataFolder());

        // Load config if it does not exists
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            file_put_contents($this->getDataFolder() . "config.yml", base64_decode($this->defaultConfig));
        }

        // Create the config object
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        // Listen to all events here
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        // Check if a backup exists
        if (file_exists($this->getDataFolder() . "playerdata.json")) {
            // And then import the data
            $this->stamina = json_decode(file_get_contents($this->getDataFolder() . "playerdata.json")) ?? new \stdClass;
        }

        // Add the autosave async function as a task
        $this->getScheduler()->scheduleRepeatingTask(new Backup($this->stamina, $this->getDataFolder()), 30*20);
    }

    public function onPlayerJoin(PlayerJoin $event) : void {
        if (@$this->stamina->{$event->getPlayer()->getName()} === null) {
            $playerstamina = new \stdClass;
            $playerstamina->regenerating = false;
            $playerstamina->rgtimer = 0;
            $playerstamina->stamina = $this->config->get('max-stamina', 100);
            $playerstamina->maxstamina = $this->config->get('max-stamina', 100);
            $playerstamina->round = $this->config->get('rounding-stamina', true);
            $playerstamina->roundlevel = $this->config->get('rounding-level', 0);
            $this->stamina->{$event->getPlayer()->getName()} = $playerstamina;
        }

        // Setup the async timer for player
        // But first, let's check the config
        if ($this->config->get('show-stamina-level', true)) {
            $this->getScheduler()->scheduleRepeatingTask(new Displayer($this->stamina, $event->getPlayer(), $this->config->get('stamina-level-text', "Stamina: %stamina%/%totalstamina%")), $this->config->get('stamina-refresh-rate', 2));
        }
    }

    public function onPlayerMove(PlayerMove $event) : void {
        $player = $event->getPlayer();
        $stamina = $this->stamina->{$player->getName()};
        if ($this->config->get('run-regenerate-stamina', false)) {
            $running = false;
        } else {
            $running = $player->isSprinting();
        }

        if (!$running && !$player->isSwimming() && !$player->isSleeping()) {
            // Can regenerate or pass timer
            if (!$stamina->regenerating && $stamina->rgtimer <= $this->config->get('waiting-moves-before-regenerate', 10)) {
                $this->stamina->{$player->getName()}->rgtimer++;
            } elseif ($stamina->regenerating) {
                if (($this->stamina->{$player->getName()}->stamina + $this->config->get('stamina-per-move', 0.1)) <= $stamina->maxstamina) {
                    $this->stamina->{$player->getName()}->stamina = $this->stamina->{$player->getName()}->stamina + $this->config->get('stamina-per-move', 0.1);
                }
            } else {
                $this->stamina->{$player->getName()}->rgtimer = 0;
                if (($this->stamina->{$player->getName()}->stamina + $this->config->get('stamina-per-move', 0.1)) <= $stamina->maxstamina) {
                    $this->stamina->{$player->getName()}->stamina = $this->stamina->{$player->getName()}->stamina + $this->config->get('stamina-per-move', 0.1);
                }
                $this->stamina->{$player->getName()}->regerenating = true;
            }
        } else {
            if ($stamina->regenerating) {
                $this->stamina->{$player->getName()}->regerenating = false;
                $this->stamina->{$player->getName()}->rgtimer = 0;
            } else {
                $this->stamina->{$player->getName()}->rgtimer = 0;
            }
        }

        // 0.1 IS THE DEFAULT!
        $sprintperk = (array)$this->config->get('speed', ['enabled' => true, 'cost' => 0.2, 'perk' => 0.05]);
        if ($player->isSprinting()) {
            if ($sprintperk['cost'] < $this->stamina->{$player->getName()}->stamina) {
                $player->setMovementSpeed(0.1 + $sprintperk['perk']);
                if (!$this->config->get('run-regenerate-stamina', false)) {
                    $this->stamina->{$player->getName()}->stamina = $this->stamina->{$player->getName()}->stamina - $sprintperk['cost'];
                }
            } else {
                $player->setMovementSpeed(0.1); 
            }
        } else {
            $player->setMovementSpeed(0.1);
        }

        // Show up your stamina with a hint
        $updatedstamina = $this->stamina->{$player->getName()}->stamina;
        if ($this->config->get('rounding-stamina', true)) {
            $updatedstamina = round($updatedstamina, 0);
        }

        // Check if the inventory have some perks
        $morestamina = 0;
        $configitems = (array)$this->config->get('stamina-powerup', []);
        for ($a = 0; $a < $player->getInventory()->getSize()+1; $a++) {
            if (!$player->getInventory()->slotExists($a)) {
                continue;
            }
            $item = $player->getInventory()->getItem($a);
            if (@$configitems[str_replace(' ', '_', strtolower($item->getVanillaName()))] !== null && @$configitems[str_replace(' ', '_', strtolower($item->getVanillaName()))] > 0) {
                $morestamina = $morestamina + $configitems[str_replace(' ', '_', strtolower($item->getVanillaName()))];
            }
        }

        if ($morestamina > 0) {
            $this->stamina->{$player->getName()}->maxstamina = $this->config->get('max-stamina', 100) + $morestamina;
        }

        // item-regeneration calculator
        $regenitems = (array)$this->config->get('item-regeneration', []);
        foreach ($regenitems as $name => $item) {
            // Check if is needed to be holded
            if ($item['should-be-holded']) {
                if (str_replace(' ', '_', strtolower($player->getInventory()->getItemInHand()->getVanillaName())) == $name) {
                    if ($item['amount'] + $this->stamina->{$player->getName()}->stamina <= $this->stamina->{$player->getName()}->maxstamina) {
                        $this->stamina->{$player->getName()}->stamina = $this->stamina->{$player->getName()}->stamina + $item['amount'];
                    } else {
                        $this->stamina->{$player->getName()}->stamina = $this->stamina->{$player->getName()}->maxstamina;
                    }
                }
            }
        }
    }

    public function onPlayerSleep(PlayerSleep $event) : void {
        if ($this->config->get('bed-restore-stamina', false)) {
            $this->stamina->{$event->getPlayer()->getName()}->stamina = $this->stamina->{$event->getPlayer()->getName()}->stamina + $this->config->get('bed-restore-stamina-level', 1);
            if ($this->stamina->{$event->getPlayer()->getName()}->stamina > $this->stamina->{$event->getPlayer()->getName()}->maxstamina) {
                $this->stamina->{$event->getPlayer()->getName()}->stamina = $this->stamina->{$event->getPlayer()->getName()}->maxstamina;
            }
        }
    }

    public function onDamage(Damage $event) : void {
        if($event instanceof DuplexDamage) {
            $damager = $event->getDamager();
            $victim = $event->getEntity();
            if ($damager instanceof Player) {
                // Ok, damager is a player so let's calc it's stamina and because of this we can use stamina to increase or decrease damage
                $damagerstamina = $this->stamina->{$damager->getName()};
                if ($damagerstamina->stamina <= $this->config->get('level-for-negative-effects', 10)) {
                    // Negative effect, ouch!
                    $event->setBaseDamage($event->getBaseDamage() - 0.5);
                } elseif ($damagerstamina->stamina >= $this->config->get('hit', ['cost' => 0.2])['cost'] && $this->config->get('hit', ['enabled' => true])['enabled']) {
                    // Now check the damage for shift-knockback config:
                    $data = $this->config->get('shift-knockback', ['enabled' => true, 'cost' => 0.85]);
                    if ($data['enabled'] && $damager->isSneaking()) {
                        $event->setKnockBack($event->getKnockBack() + ($damagerstamina->stamina/(($damagerstamina->maxstamina/100)*100)));
                    }

                    $event->setBaseDamage($event->getBaseDamage() + ($damagerstamina->stamina/(($damagerstamina->maxstamina/100)*20)));
                    $this->stamina->{$damager->getName()}->stamina = $this->stamina->{$damager->getName()}->stamina - $this->config->get('hit', ['cost' => 0.2])['cost'];
                }

                // Now check if the victim is a player so we can use his stamina to defend!
                if ($victim instanceof Player) {
                    $victimstamina = $this->stamina->{$victim->getName()};
                    // If the stamina is negative we do nothing because we're good
                    if ($victimstamina->stamina > $this->config->get('level-for-negative-effects', 10) && $victimstamina->stamina >= $this->config->get('shield', ['cost' => 0.2])['cost'] && $this->config->get('shield', ['enabled' => true])['enabled']) {
                        $event->setBaseDamage($event->getBaseDamage() - ($victimstamina->stamina/(($victimstamina->maxstamina/100)*35)));
                        $this->stamina->{$victim->getName()}->stamina = $this->stamina->{$victim->getName()}->stamina - $this->config->get('shield', ['cost' => 0.2])['cost'];
                    }
                }
            } 
        }
    }

    public function onPlayerItemConsume(PlayerEat $event) : void {
        if ($this->config->get('do-food-regen', true)) {
            // Only if the config say yes or idk
            foreach ($this->config->get('stamina-food-regen', []) as $food => $value) {
                if (str_replace(' ', '_', strtolower($event->getItem()->getVanillaName())) == $food) {
                    if ($this->stamina->{$event->getPlayer()->getName()}->stamina + $value <= $this->stamina->{$event->getPlayer()->getName()}->maxstamina) {
                        $this->stamina->{$event->getPlayer()->getName()}->stamina = $this->stamina->{$event->getPlayer()->getName()}->stamina + $value;
                        return;
                    } else {
                        $this->stamina->{$event->getPlayer()->getName()}->stamina = $this->stamina->{$event->getPlayer()->getName()}->maxstamina;
                        return;
                    }
                }
            }

            // No in the config, let's use the default object
            if ($this->stamina->{$event->getPlayer()->getName()}->stamina + $this->config->get('stamina-food-regen', ['default' => 0])['default'] <= $this->stamina->{$event->getPlayer()->getName()}->maxstamina) {
                $this->stamina->{$event->getPlayer()->getName()}->stamina = $this->stamina->{$event->getPlayer()->getName()}->stamina + $this->config->get('stamina-food-regen', ['default' => 0])['default'];
            }
        }
    }
}
