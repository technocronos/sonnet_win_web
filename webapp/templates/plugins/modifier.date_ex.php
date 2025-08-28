<?php

/**
 * PHP標準のdate関数のような変換を行うが、もう少し柔軟に対応する。
 * 詳しくは、DateTimeUtil::dateEx を参照。
 */
function smarty_modifier_date_ex($timestamp, $format, $ifNull = '')
{
    return DateTimeUtil::dateEx($format, $timestamp, $ifNull);
}
