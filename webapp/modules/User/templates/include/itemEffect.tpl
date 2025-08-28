{*
    消費アイテムの効果を表示するテンプレート

    パラメータ)
        item    以下のキーを含む配列。
                    item_type
                    item_value
                    item_limitation
*}


{if $item.item_type == constant('Item_MasterService::RECV_HP')}

  HPを{$item.item_value}回復
  射程{$item.item_limitation}
  範囲{$item.item_spread+1}

{elseif $item.item_type == constant('Item_MasterService::RECV_AP')}

  行動ptを{$item.item_value}回復

{elseif $item.item_type == constant('Item_MasterService::RECV_MP')}

  対戦ptを{$item.item_value}回復

{elseif $item.item_type == constant('Item_MasterService::INCR_PARAM')}

  ｽﾃｰﾀｽptを{$item.item_value}獲得する｡ただし{$item.item_limitation}個まで

{elseif $item.item_type == constant('Item_MasterService::DECR_PARAM')}

  {php}$this->assign('HP_SCALE', Character_InfoService::HP_SCALE){/php}
  全ｽﾃｰﾀｽが{$item.item_value}ﾀﾞｳﾝ(HPは{$item.item_value*$HP_SCALE}ﾀﾞｳﾝ)する代わりにｽﾃｰﾀｽptを{$item.item_value*8}獲得する

{elseif $item.item_type == constant('Item_MasterService::INCR_EXP')}

  経験値獲得量が{$item.item_limitation}時間{$item.item_value}%増加<br />
  ※ﾒﾝﾃﾅﾝｽ等のｼｽﾃﾑ停止時間も含まれます

{elseif $item.item_type == constant('Item_MasterService::REPAIRE')}

  選択した装備の耐久値を{$item.item_value}回復

{elseif $item.item_type == constant('Item_MasterService::TACT_ATT')}

  威力{$item.item_value}
  射程{$item.item_limitation}
  範囲{$item.item_spread+1}

{elseif $item.item_type == constant('Item_MasterService::ATTRACT')}

  <span style="color:{#termColor#}">ﾓﾝｽﾀｰの洞窟でのﾚｱﾓﾝｽﾀｰ遭遇率が{$item.item_limitation}時間{if $item.item_value==2}さらに{/if}上昇<br />
  ﾒﾝﾃﾅﾝｽ等のｼｽﾃﾑ停止時間も含まれます

{elseif $item.item_type == constant('Item_MasterService::DTECH_UPPER')}

  階級による<span style="color:{#termColor#}">必殺技の発生率が{$item.item_limitation}時間{'Item_MasterService::DTECH_UPPER_INVOKE'|constant}%になる{if $item.item_value >= 2}｡さらに威力もｱｯﾌﾟ{/if}<br />
  ※ﾒﾝﾃﾅﾝｽ等のｼｽﾃﾑ停止時間も含まれます</br>
  ※階級ﾍﾞｲｸﾞの方は必殺技がありません

{elseif $item.item_type == constant('Item_MasterService::CONTINUE_BATTLE')}

  ※ﾕｰｻﾞｰﾊﾞﾄﾙはｺﾝﾃｨﾆｭｰできません</br>
  ※連続ｺﾝﾃｨﾆｭｰは回数制限があります</br>
  ※ｺﾝﾃｨﾆｭｰ時はｽﾀｰは破棄されます</br>

{/if}
