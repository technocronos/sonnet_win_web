{include file='include/header.tpl'}


<h2>寸劇チェック(デバック用)</h2>

<form target="drama">
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="DramaSwf" />

  <table>
    <tr>
      <td>寸劇ID</td>
      <td><input type="text" name="id" value="" /><input type="submit" value="チェック" /></td>
    </tr>
    <tr>
      <td>言語</td>
      <td>{form_select name='lang' src=`$lang`}</td>
    </tr>
    <tr>
      <td>文字列置き換え</td>
      <td>
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
        <input type="text" name="holder[]" value="" /><input type="text" name="value[]" value="" /><br />
      </td>
    </tr>
  </table>


</form>

<br />
<iframe name="drama" style="width:250px; height:800px;" scrolling="no"></iframe>


{include file='include/footer.tpl'}
