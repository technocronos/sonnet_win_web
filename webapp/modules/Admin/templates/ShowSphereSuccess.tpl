{include file='include/header.tpl'}


<h2>スフィア表示(デバック用)</h2>

<form>
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="ShowSphere" />
  スフィアID<input type="text" name="id" value="{$smarty.get.id}" /><input type="submit" value="チェック" />
</form>


{if $smarty.get.id}
  <hr />

  <iframe style="width:250px; height:320px" src="?module=Admin&action=SphereSwf&id={$smarty.get.id}"></iframe>

  <hr />

  <form action="{$smarty.server.REQUEST_URI}" method="post">
    <table>
      <tr>
        <td>ポジションチェンジ</td><td><input type="text" name="pos" /></td>
      </tr>
    </table>
    <input type="submit" value="送信" />
  </form>
{/if}


{include file='include/footer.tpl'}
