{include file='include/header.tpl'}


<h2>テキストマスタ</h2>

<p>
  <button type="button" onClick="window.open('?module=Admin&action=TextMasterEdit', '_blank', 'menubar=no,toolbar=no,width=800,height=1000')">新規作成</button>
</p>


<h2>検索</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="TextMaster" />
  <input type="hidden" name="go" value="1" />

  <table style="width:30em">
    <tr>
      <th nowrap="nowrap">シンボル</th>
      <td>
        {$validator->outputError('symbol')}
        <input type="text" name="symbol" style="width:12em" value="{$validator->input('symbol')}" />
      </td>
    </tr>
    <tr>
      <th nowrap="nowrap">日本語</th>
      <td>
        {$validator->outputError('ja')}
        <input type="text" name="ja" style="width:12em" value="{$validator->input('ja')}" />
      </td>
    </tr>
    <tr>
      <th nowrap="nowrap">英語</th>
      <td>
        {$validator->outputError('en')}
        <input type="text" name="en" style="width:12em" value="{$validator->input('en')}" />
      </td>
    </tr>

    <tr>
      <th nowrap="nowrap">カテゴリ</th>
      <td>
        {foreach name='category' from=`$category` key='key' item='value'}
          {$smarty.get.category}
          <input type="checkbox" id="{$key}" name="{$key}" {if $smarty.get.$key != ""}checked{/if}>
          <label for="{$key}">{$value.title}</label>
        {/foreach}
      </td>
    </tr>

    <tr>
      <th nowrap="nowrap">進捗</th>
      <td>
        {foreach name='progress' from=`$progress` key='key' item='value'}
          <input type="radio" value="{$key}" name="progress" {if $smarty.get.progress == $key}checked{/if}>
          <label for="{$key}">{$value}</label>
        {/foreach}
      </td>
    </tr>

    <tr>
      <td colspan="2" style="text-align:center"><input type="submit" value="検索"></td>
    </tr>
  </table>
</form>

<p>
        {foreach name='category' from=`$category` key='key' item='value'}
          {if $smarty.get.$key != ""}
            {if $value.explain != ''}
              ●{$value.title}</br>
              {$value.explain|smarty:nodefaults}</br>
            {/if}
          {/if}
        {/foreach}
</p>

{if $smarty.get.go}

<p>
  {if $list.resultset}

    <table>
      <tr>
          全{$list.totalRows}件/{$list.totalPages}ページ
      </tr>


      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3.5em">編集</th>
        <th style="width:3.5em">シンボル</th>
        <th style="width:12em">日本語</th>
        <th style="width:12em">英語</th>
        <th style="width:15ex">文字数</th>
        <th style="width:15ex">カテゴリ</th>
      </tr>

      {foreach from=`$list.resultset` item=item}
        <tr>
          <td style="text-align:center">
            <a href="?module=Admin&action=TextMasterEdit&id={$item.text_id}" onClick="window.open(this.href, '_blank', 'menubar=no,toolbar=no,width=800,height=1000'); return false;">編集</a>
          </td>
          <td>{$item.symbol}</td>
          <td nowrap="nowrap">{$item.ja|nl2br}</td>
          <td nowrap="nowrap">{$item.en|nl2br}</td>
          <td>{$item.characount}</td>
          <td>{$item.category}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}
</p>



{/if}


{include file='include/footer.tpl'}
