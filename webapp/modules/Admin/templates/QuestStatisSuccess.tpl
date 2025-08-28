{include file='include/header.tpl'}


<h2>クエスト集計</h2>

<p>
  <form action="{$smart.server.PHP_SELF}">
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="QuestStatis" />
    <input type="submit" name="go" value="go">
    <span style="color:red">※少し重い。負荷の高い時間帯は禁止</span>
  </form>
</p>


<p>
  {if $table}
    {include file='include/show_resultset.tpl'
        resultset=  `$table`
        colCaptions=`$colCaptions`
        colWidth=   `$colWidth`
        colTypes=   `$colTypes`
        rowBgColor= `$rowBgColor`
    }

    ※初期クエストのクリア回数のカウントは正しくないので留意
  {/if}
</p>


{include file='include/footer.tpl'}
