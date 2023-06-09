# StaminaSystem
With **StaminaSystem** you can implement stamina in many common areas in minecraft such as PVP and racing!
This plugin, thanks to the incredible customization will always reflect your needs and will be easily adaptable to any context!

## Installation
> **Warning**<br>
> The `main` branch contains working versions of the plugin but only in beta.<br>
> Public relases are contained only in the various branches with the version name!

To install the plugin you will just need to download the `StaminaSystem.phar` file from the latest relase and put it in the `/plugin/` folder of your PMMP server!

## Mechanics
The plugin offers various mechanics focused on stamina, this is a complete list:
| Name | Activable\Deactivable | Default |
| --- | --- | --- |
|Regeneration by walking|true|true|
|Regeneration by item|true|false|
|Regeneration by food|true|true|
|Hit enhancer|true|true|
|Stamina-shield|true|true|
|Stamina shift-knockback|true|false|

## Configuration
Here explained in a table all the configurable values.<br>
In the case of object is used the PHP notation so `parent->child`

| Name | Type | Default | Nullable | Outdated | Description | 
| --- | --- | --- | --- | --- | --- |
| `enabled` | `bool` | `true` | ❌ | ✔️ | Whether the plugin is enabled |
| `waiting-moves-before-regenerate` | `int` | `5` | ❌ | ❌ | How much movements must a player do before the stamina restart regenerating? |
| `max-stamina` | `int` | `100` | ❌ | ❌ | The max amout of stamina |
| `stamina-per-move` | `float/int` | `0.5` | ❌ | ❌ | How much stamina should be removed for every move (only when sprinting)? |
| `bed-restore-stamina` | `bool` | `false` | ❌ | ❌ | Should bed restore stamina? |
| `bed-restore-stamina-level` | `int` | `10` | ❌ | ❌ | Should bed restore stamina? If yes, how much? |
| `do-food-regen` | `bool` | `true` | ❌ | ❌ | Do food regen stamina? |
| `level-for-negative-effects` | `int` | `10` | ❌ | ❌ | If stamina is <= to this level will have some negatives effect like slowness and other |
| `stamina-hit-cost` | `int/float` | `1` | ✔️ | ✔️ | OUTDATED, NOW IN `hit` ELEMENT! |
| `stamina-run-cost` | `int/float` | `0.1` | ✔️ | ✔️ | OUTDATED, NOW IN `sprint` ELEMENT! |
| `stamina-block-break-cost` | `int/float` | `0.5` | ✔️ | ✔️ | OUTDATED, ABANDONED FEATURE |
| `run-regenerate-stamina` | `bool` | `false` | ❌ | ❌ | If true, running wont' consume stamina |
| `stamina-powerup` | `object` | Object | ✔️ | ❌ | All items in this object will be increase the max stamina level |
| `stamina-powerup->iron_sword` | `int` | `10` | ❌ | ❌ | The `iron_sword` item will increase the max stamina level by 10 |
| `stamina-food-regen` | `object` | Object | ✔️ | ❌ | All foods in this object when eaten will be regenerate stamina |
| `stamina-food-regen->default` | `int` | `2` | ❌ | ❌ | If the food is not in the list this value will be used |
| `stamina-food-regen->apple` | `int` | `4` | ✔️ | ❌ | If an apple is eat 4 stamina points will be rigenerated! |
| `item-regeneration` | `object` | Object | ✔️ | ❌ | All items in this list will rigenerate stamina by having it in the hand / inventory |
| `item-regeneration->iron_axe` | `object` | Object | ❌ | ❌ | All regeneration settings for the item `iron_axe` | 
| `item-regeneration->iron_axe->should-be-holded` | `bool` | `false` | ❌ | ❌ | The item will regenerate only if holded |
| `item-regeneration->iron_axe->amount` | `int/float` | N/D | ❌ | ❌ | How much? |
| `speed` | `perkObject` | perkObject | ❌ | ❌ | The perk object for speed powerup |
| `hit` | `perkObject` | perkObject | ❌ | ❌ | The perk object for speed powerup |
| `shield` | `perkObject` | perkObject | ❌ | ❌ | The perk object for speed powerup |
| `shift-knockback` | `perkObject` | perkObject | ❌ | ❌ | The perk object for speed powerup |
| `rounding-stamina` | `bool` | `true` | ❌ | ❌ | Should stamina be rounded when displayed to a player? |
| `rounding-level` | `int` | `0` | ❌ | ❌ | How much? |
| `show-stamina-level` | `bool` | `true` | ❌ | ❌ | Should the stamina level indicator (as Tip) be displayed to player? |
| `stamina-level-text` | `string` | Stamina: %stamina%/%totalstamina% | ❌ | ❌ | Set the stamina display text. Placeholders: %stamina% -> current stamina level    %totalstamina% -> max stamina for player |
| `stamina-refresh-rate` | `int` | `2` | ❌ | ❌ | In tick, every when tick(s) should the player stamina tip be refreshed (so updated)? |In tick, every when tick(s) should the player stamina tip be refreshed (so updated)?

### perkObject
| Name | Type | Default |
|---|---|---|
| enabled | bool | true|
| cost | int/float | N/D |
| perk | ?int/float | N/D |

## Roadmap
- [x] Run perk
- [x] Walk for stamina regeneration
- [x] Autosave stamina
- [x] Eat for stamina regeneration
- [x] Item powerup and weight
- [x] Async task to show stamina level
- [x] More damage with stamina
- [x] Stamina shield
- [x] Stamina knockback
- [ ] Stamina haste

## Contact me
You can contact me via:
- **Email:** `foxworn3365@gmail.com`
- **Discord:** `@foxworn`
