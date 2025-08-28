<?php

/**
 * 小数点以下を切り捨てて表示する修飾子。
 */
function smarty_modifier_int($numeric)
{
    return (int)$numeric;
}
