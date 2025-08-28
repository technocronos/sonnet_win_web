{include file='include/header.tpl'}


<h2>登録人数</h2>

<p>
  <table><tr>
    <th>iOS全登録数</th>
    <td class="value">{$sumup.ios_total}</td>
    <th>Android全登録数</th>
    <td class="value">{$sumup.android_total}</td>
    <th>全登録数</th>
    <td class="value">{$sumup.total}</td>
    <th>4日以内人数</th>
    <td class="value">{$sumup.living}</td>
    <th>4日以内残存率</th>
    <td class="value">{$sumup.living_rate*100|string_format:'%.2f'}%</td>
  </tr></table>
<p>

<h2>日別登録数</h2>

<form action="{$smart.server.PHP_SELF}">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="Regist" />
  <table>
    <tr>
      <th>期間</th>
      <td>
        {$validator->outputError('from', '開始')}
        {$validator->outputError('to', '終了')}
        {$validator->outputError('interval')}
        <input type="text" name="from" style="width:12ex" value="{$validator->input('from')}" />
        ～
        <input type="text" name="to" style="width:12ex" value="{$validator->input('to')}" />
        <input type="submit" value="検索">
      </td>
    </tr>
  </table>
</form>


{if $table}

  <p>
    {include file='include/show_resultset.tpl'
        resultset=`$table`
        colCaptions=`$colCaptions`
        colWidth=`$colWidth`
        colTypes=`$colTypes`
    }
  </p>

  <p>
    <dl>
      <dt>登録人数</dt><dd>登録日別で人数を集計したもの</dd>
      <dt>招待数</dt><dd>招待日別で招待数を集計したもの</dd>
      <dt>招待応諾数</dt><dd>応諾された招待数を招待日別で集計したもの</dd>
      <dt>最終アクセス</dt><dd>ラストアクセス日別で人数を集計したもの</dd>
      <dt>早期離脱数</dt><dd>
        登録からラストアクセスまでが3時間もない人数を登録日別で集計したもの。<br />
        アンインストールしているとは限らない。
      </dd>
      <dt>ｱﾝｲﾝｽﾄｰﾙ</dt><dd>アンインストール日別で人数を集計したもの</dd>
    </dl>
  </p>
{/if}


{include file='include/footer.tpl'}
