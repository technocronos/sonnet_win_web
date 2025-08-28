{include file='include/header.tpl'}


{*
<!--
  ステージのスクリーンショットを取るときはこのコメントアウトを解除して、<table>タグに
  cellspacing="0" cellpadding="0" の属性を付ける。
 -->
*}
<style>{literal}
  td {
    border:none;
    vertical-align: top;
  }
{/literal}</style>

<h2>フィールドルーム表示(デバック用)</h2>

<form>
  <input type="hidden" name="module" value="Admin" />
  <input type="hidden" name="action" value="EditRoom" />
  ルームID<input type="text" name="id" value="{$smarty.get.id}" /> <br>
<hr />
  レイヤー<br/ >

  背景<input type="checkbox" id="show_background" name="show_background" {if $show_background}checked{/if}><br>
  本体<input type="checkbox" id="show_structure" name="show_structure" {if $show_structure}checked{/if}><br>
  レイヤ1<input type="checkbox" id="show_overlayer1" name="show_overlayer1" {if $show_overlayer1}checked{/if}><br>
  レイヤ2<input type="checkbox" id="show_overlayer2" name="show_overlayer2" {if $show_overlayer2}checked{/if}><br>
  カバー<input type="checkbox" id="show_cover" name="show_cover" {if $show_cover}checked{/if}><br>

<br/>
※id=0のチップはクライアント側では無視される特殊チップ。レイヤ1,レイヤ2,カバーのみで使用を想定。<br/>
※本体⇒レイヤ1⇒レイヤ2の順でコストは上書きされる。<br/>
※背景は常に通れないチップとして扱われる。<br/>
※カバーは常にコストは無しとして扱われる。隠し通路用途。<br/>

<!--
  チップ範囲<input type="text" name="fromId" value="{$smarty.get.fromId}" />～<input type="text" name="toId" value="{$smarty.get.toId}" /><br>
-->
<hr />
  チップカテゴリ<br/ >
  {foreach name='category' from=`$category` key='key' item='value'}
{$smarty.get.category}
    <input type="checkbox" id="{$key}" name="{$key}" {if $smarty.get.$key != ""}checked{/if}>
    <label for="{$key}">{$value}</label>
  {/foreach}

  <br>
  <input type="submit" value="表示" /><br>
</form>

<hr />

{if $tip_count}
  {$tip_count}個(最大{$max_tip_count}個まで)<br>
  {if $tip_count > $max_tip_count}<div style="color: red;">チップ種類数がオーバーしてます！{$tip_count-$max_tip_count}種類減らしてください！</div>{/if}
  ※エクストラマップチップは除いています。<br>
{/if}

<hr />

{if $smarty.get.id}

  <form action="{$smarty.server.REQUEST_URI}" method="post" onSubmit="return confirm('保存しますか?')">

    <div style="float:left">
      敷物: <br />
      <textarea name="mats" id="mats" style="width:20em; height:40em; font-family:monospace; white-space:nowrap">{$mats}</textarea>
    </div>

    {if $room}

      <table cellspacing="0" cellpadding="0">
        <tr>
          <td></td>
          {foreach name='cols' from=`$room.0` item='cell'}
            <td>{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}</td>
          {/foreach}
        </tr>
        {foreach name='rows' from=`$room` item='line'}
          <tr>
            <td>{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}</td>
            {foreach name='cols' from=`$line` item='cell'}
              <td id="cell{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}" style="width:32px;height:32px;">
                <img style="width:32px;height:32px;position: absolute;display: none;" id="{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}_background" src="about:blank" onClick="changeColor(this, 'background')" />
                <img style="width:32px;height:32px;position: absolute;display: none;" id="{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}" src="about:blank" onClick="changeColor(this)" />
                <img style="width:32px;height:32px;position: absolute;display: none;" id="{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}_overlayer1" src="about:blank" onClick="changeColor(this, 'overlayer1')" />
                <img style="width:32px;height:32px;position: absolute;display: none;" id="{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}_overlayer2" src="about:blank" onClick="changeColor(this, 'overlayer2')" />

                <img style="width:32px;height:32px;position: absolute;display: none;" id="{if $head_count == 0}{$smarty.foreach.rows.index}{else}{$smarty.foreach.rows.index-$head_count}{/if}_{if $head_count == 0}{$smarty.foreach.cols.index}{else}{$smarty.foreach.cols.index-$left_count}{/if}_cover" src="about:blank" onClick="changeColor(this, 'cover')" />

              </td>
            {/foreach}
          </tr>
        {/foreach}
      </table>

    {else}
      <div style="padding:1em">レコードがありません</div>
    {/if}
    <br style="clear:both" />

    <div style="text-align:center">
      <table>
        {foreach from=`$tips` item='row'}
          <tr>
            {foreach from=`$row` item='tip' key='index'}
              <td style="width:15ex">
                <img id="{$tip.tip_no}" src="?module=Admin&action=EditRoom&tip={$tip.tip_no}" style="border:solid 2px white; float:left;width:32px;height:32px;" onClick="changeFocus(this)" title="{$tip.tip_no}" />
                id:{$tip.tip_no}<br />
                cost:{$tip.cost}<br />
                aqua:{$tip.cost_aquatic}<br />
                amph:{$tip.cost_amphibia}<br />
                {$tip.category}<br />
              </td>
              {if $index%12==11}</tr><tr>{/if}
            {/foreach}
          </tr>
        {/foreach}
      </table>
      <button type="button" onClick="refreshSrc(true)">↓反映して保存</button>
    </div>

    本体: <input type="submit" id="save" value="保存" />
    <textarea name="data" id="data" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>背景</span><br>
    <textarea name="data_background" id="data_background" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>レイヤ1</span><br>
    <textarea name="data_overlayer1" id="data_overlayer1" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>レイヤ2</span><br>
    <textarea name="data_overlayer2" id="data_overlayer2" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>カバー</span><br>
    <textarea name="data_cover" id="data_cover" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>ヘッダ</span><br>
    <textarea name="data_head" id="data_head" style="width:95%; height:10em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>左</span><br>
    <textarea name="data_left" id="data_left" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>右</span><br>
    <textarea name="data_right" id="data_right" style="width:95%; height:50em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>
  <br>

    <span>フッタ</span><br>
    <textarea name="data_foot" id="data_foot" style="width:95%; height:15em; font-family:monospace; white-space:{if false===strpos($smarty.server.HTTP_USER_AGENT, 'Trident')}nowrap{/if}; overflow:scroll"></textarea>

  </form>

{/if}


<script>

    // ルームの構造。
    var room = {$room|@json_encode};
    var structure = {$structure|@json_encode};
    var background = {$background|@json_encode};
    var cover = {$cover|@json_encode};
    var overlayer1 = {$overlayer1|@json_encode};
    var overlayer2 = {$overlayer2|@json_encode};

    var head = {$head|@json_encode};
    var left = {$left|@json_encode};
    var right = {$right|@json_encode};
    var foot = {$foot|@json_encode};

    var head_count={$head_count};
    var left_count={$left_count};
    var show_structure="{$show_structure}";
    var show_cover="{$show_cover}";
    var show_background="{$show_background}";
    var show_overlayer1="{$show_overlayer1}";
    var show_overlayer2="{$show_overlayer2}";

{literal}

    // 現在パレットから選択されているチップの値。
    var color = "";


    //-----------------------------------------------------------------------------------------------------
    /**
     * ロード時の処理。
     */
    function initialize() {

        if(!room)
            return;

        if(show_structure){
            // 各マスの画像を初期状態に。
            for(var y in room) {
                for(var x in room[y]) {
                    if(head[0].length > 0){
                        refreshColor(x - left_count, y - head_count, room[y][x]["id"], room[y][x]["edit"]);
                    }else{
                        refreshColor(x, y, room[y][x]["id"], room[y][x]["edit"]);
                    }
                }
            }
        }

        // ソースエリアにソースを表示。
        refreshSrc(false);

        // 敷物のソースを取得。カラならリターン。
        matsSrc = document.getElementById('mats').value;
        if(matsSrc != ""){
            // 敷物のデータを取得。
            try {
                var mats = eval("(" + matsSrc + ")");
            }catch(e) {
                alert("暗幕のソースを解析できません。\n" + e);
                return;
            }

            // 敷物を反映。
            for(var type in mats) {
                for(var name in mats[type]) {

                    var mat = mats[type][name];

                    for(var y = mat["pos"][1] ; y <= mat["rb"][1] ; y++) {
                        for(var x = mat["pos"][0] ; x <= mat["rb"][0] ; x++) {

                            if(mat["mask"]) {
                                innerX = x - mat["pos"][0];
                                innerY = y - mat["pos"][1];
                                if(mat["mask"][innerY].charAt(innerX) == "0")
                                    continue;
                            }

                            document.getElementById("cell" + y + "_" + x).style.backgroundColor = "green";
                        }
                    }
                }
            }
        }

        //背景チップを反映
        if(background && show_background){
            // 各マスの画像を初期状態に。
            for(var y in background) {
                for(var x in background[y]) {
                    if(background[y][x]["id"] > 0){

                        if(head[0].length > 0){
                            refreshColor(x - left_count, y - head_count, background[y][x]["id"], background[y][x]["edit"]);
                        }else{
                            refreshColor(x, y, background[y][x]["id"], background[y][x]["edit"]);
                        }

                    }
                }
            }
        }

        //レイヤ１チップを反映
        if(overlayer1 && show_overlayer1){
            // 各マスの画像を初期状態に。
            for(var y in overlayer1) {
                for(var x in overlayer1[y]) {
                    refreshColor(x, y, overlayer1[y][x]["id"], overlayer1[y][x]["edit"]);
                }
            }
        }

        //レイヤ１チップを反映
        if(overlayer2 && show_overlayer2){
            // 各マスの画像を初期状態に。
            for(var y in overlayer2) {
                for(var x in overlayer2[y]) {
                    refreshColor(x, y, overlayer2[y][x]["id"], overlayer2[y][x]["edit"]);
                }
            }
        }

        //オーバーレイチップを反映
        if(cover && show_cover){
            // 各マスの画像を初期状態に。
            for(var y in cover) {
                for(var x in cover[y]) {
                    refreshColor(x, y, cover[y][x]["id"], cover[y][x]["edit"]);
                }
            }
        }


    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * パレットのチップがクリックされたら呼ばれる。
     */
    function changeFocus(firer) {

        // 現在選択中のチップがあるならマークを解除。
        if(color)
            document.getElementById(color).style.borderColor = "white";

        // クリックされたチップを選択中としてマークする。
        color = firer.id
        firer.style.borderColor = "red";
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ルームのセルがクリックされたら呼ばれる。
     */
    function changeColor(firer, inputkind = null) {

        // 現在選択中のチップがないなら何もしない。
        if(!color)
            return;

        // どの座標のセルが選択されたのかを取得。
        var matches = /([-+]?\d*.?\d+)_([-+]?\d*.?\d+)/.exec(firer.id);

        if(head[0].length > 0){
            y = parseInt(matches[1]) + head_count;
            x = parseInt(matches[2]) + left_count;
        }else{
            y = matches[1];
            x = matches[2];
        }

        var kind = room[ y ][ x ]["edit"];

        if(inputkind != null)
            kind = inputkind;

        var id;
        var edit;

        // 選択されたセルを変更。
        if(kind == "structure"){
            structure[ matches[1] ][ matches[2] ]["id"] = color;

            id = structure[ matches[1] ][ matches[2] ]["id"];
            edit = structure[ matches[1] ][ matches[2] ]["edit"];
        }else if(kind == "background"){
            background[ matches[1] ][ matches[2] ]["id"] = color;

            id = background[ matches[1] ][ matches[2] ]["id"];
            edit = background[ matches[1] ][ matches[2] ]["edit"];
        }else if(kind == "overlayer1"){
            overlayer1[ matches[1] ][ matches[2] ]["id"] = color;

            id = overlayer1[ matches[1] ][ matches[2] ]["id"];
            edit = overlayer1[ matches[1] ][ matches[2] ]["edit"];
        }else if(kind == "overlayer2"){
            overlayer2[ matches[1] ][ matches[2] ]["id"] = color;

            id = overlayer2[ matches[1] ][ matches[2] ]["id"];
            edit = overlayer2[ matches[1] ][ matches[2] ]["edit"];
        }else if(kind == "cover"){
            cover[ matches[1] ][ matches[2] ]["id"] = color;

            id = cover[ matches[1] ][ matches[2] ]["id"];
            edit = cover[ matches[1] ][ matches[2] ]["edit"];

        }else if(kind == "head"){
            y = parseInt(matches[1]) + head_count;
            x = parseInt(matches[2]) + left_count;

            head[ y ][ x ]["id"] = color;

            id = head[ y ][ x ]["id"];
            edit = head[ y ][ x ]["edit"];
        }else if(kind == "left"){
            y = parseInt(matches[1]);
            x = parseInt(matches[2]) + left_count;

            left[ y ][ x ]["id"] = color;

            id = left[ y ][ x ]["id"];
            edit = left[ y ][ x ]["edit"];
        }else if(kind == "right"){
            y = parseInt(matches[1]);
            x = parseInt(matches[2]) - structure[0].length;

            right[ y ][ x ]["id"] = color;

            id = right[ y ][ x ]["id"];
            edit = right[ y ][ x ]["edit"];
        }else if(kind == "foot"){
            y = parseInt(matches[1]) - structure.length;
            x = parseInt(matches[2]) + left_count;

            foot[ y ][ x ]["id"] = color;

            id = foot[ y ][ x ]["id"];
            edit = foot[ y ][ x ]["edit"];
        }


        refreshColor(matches[2], matches[1], id, edit);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された座標のセルに、グローバル変数 room で保持しているチップを反映させる。
     */
    function refreshColor(x, y, id, edit) {
        var img = document.getElementById(y + "_" + x);

        if(edit == "cover" || edit == "background"  || edit == "overlayer1"  || edit == "overlayer2" ){
            img = document.getElementById(y + "_" + x + "_" + edit);
        }

        if(img != null){
            img.style.display ="block";        
        }else{
console.log("error" + y + "_" + x + "_" + edit);
            return;
        }

        if(edit === "head" || edit === "foot" || edit === "left" || edit === "right"){
            try {
                img.style.webkitFilter = "grayscale(70%)";
            }catch(e) {
                console.log("IDがありません。" + y + "_" + x + e);
                return;
            }
        }

        img.src = "?module=Admin&action=EditRoom&tip=" + id;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マップの状態をソースエリアに反映する。
     *
     * @param bool  保存も行うかどうか。
     */
    function refreshSrc(withSave) {

        if(!room)
            return;

        // ソースを作成する。
        var data = "";
        for(var y in structure) {

            for(var x in structure[y])
                data += formatFigure(structure[y][x]["id"]) + " ";

            data += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data").value = data;

        // ソースを作成する。
        var data_background = "";
        for(var y in background) {

            for(var x in background[y])
                data_background += formatFigure(background[y][x]["id"]) + " ";

            data_background += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_background").value = data_background;

        // ソースを作成する。
        var data_overlayer1 = "";
        for(var y in overlayer1) {

            for(var x in overlayer1[y])
                data_overlayer1 += formatFigure(overlayer1[y][x]["id"]) + " ";

            data_overlayer1 += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_overlayer1").value = data_overlayer1;

        // ソースを作成する。
        var data_overlayer2 = "";
        for(var y in overlayer2) {

            for(var x in overlayer2[y])
                data_overlayer2 += formatFigure(overlayer2[y][x]["id"]) + " ";

            data_overlayer2 += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_overlayer2").value = data_overlayer2;


        // ソースを作成する。
        var data_cover = "";
        for(var y in cover) {

            for(var x in cover[y])
                data_cover += formatFigure(cover[y][x]["id"]) + " ";

            data_cover += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_cover").value = data_cover;

        // ソースを作成する。
        var data_head = "";
        for(var y in head) {

            for(var x in head[y])
                data_head += formatFigure(head[y][x]["id"]) + " ";

            data_head += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_head").value = data_head;


        // ソースを作成する。
        var data_left = "";
        for(var y in left) {

            for(var x in left[y])
                data_left += formatFigure(left[y][x]["id"]) + " ";

            data_left += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_left").value = data_left;


        // ソースを作成する。
        var data_right = "";
        for(var y in right) {

            for(var x in right[y])
                data_right += formatFigure(right[y][x]["id"]) + " ";

            data_right += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_right").value = data_right;

        // ソースを作成する。
        var data_foot = "";
        for(var y in foot) {

            for(var x in foot[y])
                data_foot += formatFigure(foot[y][x]["id"]) + " ";

            data_foot += "\n";
        }

        // ソース用のテキストエリアに反映。
        document.getElementById("data_foot").value = data_foot;

        // 保存も行うことになっているなら保存ボタンを押す。
        if(withSave)
          document.getElementById("save").click();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * チップ番号の桁数を正規化して返す。
     */
    function formatFigure(tipNo) {

        var result = tipNo.toString();

        while(result.length < 4)
            result = "0" + result;

        return result;
    }


    //-----------------------------------------------------------------------------------------------------
    // 初期化処理。
    initialize();

{/literal}
</script>


{include file='include/footer.tpl'}
