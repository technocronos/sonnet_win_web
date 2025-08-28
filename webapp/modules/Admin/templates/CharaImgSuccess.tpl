{include file='include/header.tpl'}


<h2>キャライメージ表示(デバック用)</h2>

<p>

  <form onSubmit="return form_Submit(this)">
    <input type="hidden" name="module" value="Admin" />
    <input type="hidden" name="action" value="{$smarty.get.action}" />
    <input type="hidden" id="type" name="type" />

    種族:<select name="race">
      <option value="PLA">PLA</option>
      <option value="MOB">MOB</option>
    </select><br />

    <table style="display:inline">
      <tr>
        <th>武器</th>
        <td>
          <select name="part[]" onChange="document.getElementById('sbm').click()">
            {foreach from=`$pla_weapon` item='item'}
              <option value="{$item.item_id}">{$item.item_name}</option>
            {/foreach}
          </select>
        </td>
        <th>服</th>
        <td>
          <select name="part[]" onChange="document.getElementById('sbm').click()">
            {foreach from=`$pla_body` item='item'}
              <option value="{$item.item_id}">{$item.item_name}</option>
            {/foreach}
          </select>
        </td>
        <th>頭</th>
        <td>
          <select name="part[]" onChange="document.getElementById('sbm').click()">
            {foreach from=`$pla_head` item='item'}
              <option value="{$item.item_id}">{$item.item_name}</option>
            {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <th>ｱｸｾｻﾘ</th>
        <td>
          <select name="part[]" onChange="document.getElementById('sbm').click()">
            {foreach from=`$pla_shield` item='item'}
              <option value="{$item.item_id}">{$item.item_name}</option>
            {/foreach}
          </select>
        </td>
      </tr>
    </table>
    <button type="submit" id="sbm" style="vertical-align:bottom">go</button>
    <br />
  </form>
</p>

<p>
  <table><tr>
    <td style="text-align:center">
      gif<br />
      <iframe name="webResult" style="width:180px;height:200px;"></iframe>
    </td>
    <td style="text-align:center">
      png<br />
      <iframe name="swfResult" style="width:180px;height:200px;"></iframe>
    </td>
    <td style="text-align:center">
      nail<br />
      <iframe name="nailResult" style="width:180px;height:200px;"></iframe>
    </td>
  </tr></table>
</p>


<script>{literal}

    function form_Submit(targetForm) {

        document.getElementById("type").value = "web";
        targetForm.target = "webResult"
        targetForm.submit();

        document.getElementById("type").value = "swf";
        targetForm.target = "swfResult"
        targetForm.submit();

        document.getElementById("type").value = "nail";
        targetForm.target = "nailResult"
        targetForm.submit();

        return false;
    }

{/literal}
</script>
