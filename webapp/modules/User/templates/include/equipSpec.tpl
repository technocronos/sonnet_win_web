{*
    装備系アイテムの詳細情報を表示するテンプレート。

    パラメータ)
        item    Item_MasterService::getExRecordで取得したアイテムレコード、
                あるいは、User_ItemService で取得した user_item レコード。
*}
  {image_tag file='picon_att1.gif'}<span style="color:{if $item.attack1}{#statusValueColor#}{/if}; text-decoration:{if $item.attack1 < 0}blink{/if}">{$item.attack1|space_format:'%-+4d'}</span>
  {image_tag file='picon_att2.gif'}<span style="color:{if $item.attack2}{#statusValueColor#}{/if}; text-decoration:{if $item.attack2 < 0}blink{/if}">{$item.attack2|space_format:'%-+4d'}</span>
  {image_tag file='picon_att3.gif'}<span style="color:{if $item.attack3}{#statusValueColor#}{/if}; text-decoration:{if $item.attack3 < 0}blink{/if}">{$item.attack3|space_format:'%-+4d'}</span>
  {image_tag file='picon_speed.gif'}<span style="color:{if $item.speed}{#statusValueColor#}{/if}; text-decoration:{if $item.speed < 0}blink{/if}">{$item.speed|space_format:'%-+4d'}</span>
  <br />
  {image_tag file='picon_def1.gif'}<span style="color:{if $item.defence1}{#statusValueColor#}{/if}; text-decoration:{if $item.defence1 < 0}blink{/if}">{$item.defence1|space_format:'%-+4d'}</span>
  {image_tag file='picon_def2.gif'}<span style="color:{if $item.defence2}{#statusValueColor#}{/if}; text-decoration:{if $item.defence2 < 0}blink{/if}">{$item.defence2|space_format:'%-+4d'}</span>
  {image_tag file='picon_def3.gif'}<span style="color:{if $item.defence3}{#statusValueColor#}{/if}; text-decoration:{if $item.defence3 < 0}blink{/if}">{$item.defence3|space_format:'%-+4d'}</span>
  {if $item.defenceX}
    {image_tag file='picon_defX.gif'}<span style="color:{if $item.defenceX}{#statusValueColor#}{/if}; text-decoration:{if $item.defenceX < 0}blink{/if}">{$item.defenceX|space_format:'%-+4d'}</span>
  {/if}
  <br />
