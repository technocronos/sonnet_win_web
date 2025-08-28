<?php

/**
 * 装備品の耐久値を表示する。
 */
function smarty_modifier_durability($durability) {

    return ($durability == Item_MasterService::INFINITE_DURABILITY) ? '∞' : $durability;
}
