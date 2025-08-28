{include file="include/header.tpl" title=`$gacha.gacha_name`}

{if $gold}
    {image_tag file='navi_mini.gif' float='left'}
    {$gacha.gacha_name}を引くのだ。いいのだ？
    <br clear="all" /><div style="clear:both"></div>

    <form action="{url_for _self=true}" method="post">
      <div style="text-align:center"><input type="submit" name="gold_ok" value="使う" /></div>
    </form>

{else}
    {image_tag file='navi_mini.gif' float='left'}
    お､ｺｺでﾀﾀﾞ券使うのだ？
    <br clear="all" /><div style="clear:both"></div>

    <form action="{url_for _self=true}" method="post">
      <div style="text-align:center"><input type="submit" name="ok" value="使う" /></div>
    </form>
{/if}

<a href="{url_for _self=true go=null}" class="buttonlike back">←ｷｬﾝｾﾙ</a>


{include file="include/footer.tpl" showCompanyName=true}
