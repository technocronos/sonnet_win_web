{include file='include/header.tpl'}


<h2>装備パラメータ変更</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return form_Submit()">

  <p>
    対象:{form_select id='itemId' name='itemId' src=`$items`}<br />
  </p>

  <p>
    データ:exp - attack1 - attack2 - attack3 - defence1 - defence2 - defence3 - speed - defenceX の順で水平タブで区切って指定する。<br />
    <textarea name="data" style="width:95%; height:15em"></textarea><br />
  </p>

  <p>
    <button type="submit">反映</button>(データが入力されていない場合は確認だけです)
  </p>
</form>

<hr />
結果:
{include file='include/show_resultset.tpl' resultset=`$levels`}


<script>{literal}
    function form_Submit() {

        if(document.getElementById('data').value == "")
            return true;

        return confirm('よろしいですか？');
    }
{/literal}</script>


{include file='include/footer.tpl'}
