{include file='include/header.tpl'}

<h2>キャラ経験値修正ユーザー検索（重）</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return confirm('よろしいですか？')">
  <input type="submit" value="go" />
  <input type="hidden" name="func" value="show" />
</form><br/><br/>


<h2>キャラ経験値修正</h2>

<form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return confirm('よろしいですか？')">
  キャラクターID<input type="text" name="charaId" value="{$smarty.get.charaId}" />
  を処理
  <label for="npcflg"><input type="checkbox" checked="true" name="npcflg" value="1" id="npcflg" />NPCユーザーとして処理する</label>
  <input type="submit" value="go" />
  <input type="hidden" name="func" value="fix" />
</form><br><br>
※NPCとして処理すればレベルと階級だけ上げておけば自動的に適切なパラメータを振り分けます<br>
※一般の場合は振り分けPTは振り分けPTとしてパラメータを振り分けます<br>
<hr />

{if $smarty.get.charaId}
  <pre>{$chara|@var_dump}</pre>
{/if}


{include file='include/footer.tpl'}
