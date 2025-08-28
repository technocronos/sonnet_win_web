{include file="include/header.tpl" title=`$help.help_title`}


{image_tag file='navi_mini.gif'}<br />

{* この世界 *}
{if     $smarty.get.id == 'about-game'}

  300年前に人間と亜人との戦争があった世界に生きる一人の女の子になって､みんなのお悩みを解決したり深めたりする様子をもじょに見せて楽しませるｹﾞｰﾑなのだ｡<br />
  …要するにRPGなのだ｡ｶﾞﾀｶﾞﾀ言わずにやってみればいいのだ｡<br />

{* …で､あんた誰? *}
{elseif $smarty.get.id == 'about-navigator'}

  <span style="color:{#termColor#}">もじょ</span>なのだｼﾝｼｭﾂｷﾎﾞﾂの精霊なのだ!<br />
  …たしか､最初に自己紹介したのだ｡<br />
  <br />
  おまえを案内しながらﾋﾏﾂﾌﾞｼしてやるからありがたく思えなのだ<br />

{* 経験値･Lv(ﾚﾍﾞﾙ)･ｽﾃｰﾀｽpt *}
{elseif $smarty.get.id == 'status-level'}

  <span style="color:{#termColor#}">ﾚﾍﾞﾙ</span>なんて説明の必要ないのだ?<span style="color:{#termColor#}">経験値</span>たまるとﾚﾍﾞﾙ上がるのだ｡<br />
  ﾚﾍﾞﾙ上がるとちょっぴり強くなるのだ｡<br />
  <br />
  経験値はﾊﾞﾄﾙしてると勝手にたまるのだ｡相手とのﾚﾍﾞﾙ差やﾀｰﾝ数､与えたﾀﾞﾒｰｼﾞの割合､倒したかどうかで量が違うのだ｡<br />
  <br />
  ﾚﾍﾞﾙ上がるとたまに<span style="color:{#termColor#}">ｽﾃｰﾀｽpt</span>が手に入るのだ｡<br />
  ｽﾃｰﾀｽptはそのままじゃ何の意味もないのだ｡<br />
  <a href="{url_for action='Status' _backto=true}">ｽﾃｰﾀｽ</a>画面の｢振り分け｣で､攻撃力とかに振り分けるのだ｡<br />
  <br />
  …それと､ﾚﾍﾞﾙ上がっても行動ptとかHP回復したりはしないのだ｡他のｹﾞｰﾑとはﾙｰﾙ違うのだ｡<br />

{* HP *}
{elseif $smarty.get.id == 'status-hp'}

  ｢ﾋｯﾄﾎﾟｲﾝﾄ｣の略なのだ｡知ってたのだ?<br />
  よくあるｱﾚなのだ｡攻撃受けると減るのだ｡0になるとﾀﾞｳﾝなのだ｡<br />
  <br />
  ｸｴｽﾄの<span style="color:{#termColor#}">HP</span>とﾕｰｻﾞ対戦の<span style="color:{#termColor#}">HP</span>は別々なのだ｡<br />
  普段表示されてる<span style="color:{#termColor#}">HP</span>はほとんどﾕｰｻﾞ対戦のものなのだ｡時間でちょっとずつ回復していくのだ｡大体8時間で全快するのだ｡<br />
  <span style="color:{#termColor#}">くすりびん</span>使えば一発で回復なのだ｡それか､一回ﾀﾞｳﾝしちゃえば全快するのだ｡<br />
  <br />
  そんで…ｸｴｽﾄのほうはいっつも満ﾀﾝからｽﾀｰﾄなのだ｡時間で回復したりはしないのだ｡<span style="color:{#termColor#}">くすりびん</span>とか使って回復するしかないのだ｡<br />
  ﾀﾞｳﾝしちゃうとｸｴｽﾄ終わりなのだ｡気をつけるのだ｡<br />
  <br />
  くどいけど別々なのだ｡ﾕｰｻﾞ対戦のﾀﾞﾒｰｼﾞがｸｴｽﾄのほうに反映されたり､ｸｴｽﾄでの回復がﾕｰｻﾞ対戦に反映されたりはしないのだ｡<br />

{* 攻撃力･防御力 *}
{elseif $smarty.get.id == 'status-attdef'}

  …なんて説明したらいいか分かんないのだ<br />
  <br />
  <span style="color:{#termColor#}">攻撃力</span>は相手にﾀﾞﾒｰｼﾞを与える強さなのだ｡逆に<span style="color:{#termColor#}">防御力</span>はﾀﾞﾒｰｼﾞ受けるのを抑える強さなのだ｡<br />
  攻撃側の攻撃力と､受け側の防御力の差でﾀﾞﾒｰｼﾞが決まるのだ｡<br />
  <br />
  あ､攻撃にも防御にも属性があるのだ｡炎の攻撃のときは炎の攻撃力と炎の防御力の差で決まるのだ｡<br />

{* ｽﾋﾟｰﾄﾞ *}
{elseif $smarty.get.id == 'status-speed'}

  ﾊﾞﾄﾙのときの小回りの良さなのだ<br />
  <br />
  相手よりｽﾋﾟｰﾄﾞでたくさん勝ってると<span style="color:{#termColor#}">吸収</span>が成功しやすいのだ｡<span style="color:{#termColor#}">ﾘﾍﾞﾝｼﾞ</span>されたときの迎撃ｽﾋﾟｰﾄﾞも速くなるのだ｡<br />
  <br />
  逆にあんまり負けてると結構悲惨なのだ｡ﾊﾞﾝﾊﾞﾝ吸収されるし､せっかくﾘﾍﾞﾝｼﾞに持ち込んでもﾊﾞﾝﾊﾞﾝ迎撃されるのだ｡<br />

{* 階級､階級pt *}
{elseif $smarty.get.id == 'status-grade'}

  対戦してると<span style="color:{#termColor#}">階級pt</span>手に入るのだ｡<span style="color:{#termColor#}">階級pt</span>がたまると<span style="color:{#termColor#}">階級</span>が上がるのだ<br />
  <br />
  <span style="color:{#termColor#}">階級</span>上がるとユーザ対戦で<span style="color:{#termColor#}">{$smarty.const.GOLD_NAME}</span>が手に入りやすくなるし､必殺技も出せるようになるのだ｡<br />
  <br />
  けっこう<span style="color:{#termColor#}">階級</span>上がると､負けたときに<span style="color:{#termColor#}">階級pt</span>減ったりするのだ｡ﾏｲﾅｽがたまると<span style="color:{#termColor#}">階級</span>下がるのだ｡<br />
  <br />
  …あ､あと､他のﾕｰｻﾞからﾒｯｾｰｼﾞ受けたときにも<span style="color:{#termColor#}">階級pt</span>ちょっぴりたまるのだ｡<br />

{* 行動pt *}
{elseif $smarty.get.id == 'status-actionpt'}

  <span style="color:{#termColor#}">対戦</span>したり<span style="color:{#termColor#}">ｸｴｽﾄ</span>で行動するたびに減っていくのだ｡なくなると行動できないのだ｡<br />
  でも時間で回復するのだ｡からっぽになっても3時間たてば満ﾀﾝなのだ｡<br />
  <br />
  …ご他聞に漏れず､ｱｲﾃﾑの<span style="color:{#termColor#}">ﾆﾜﾄﾘの時計</span>買って使えば一発で回復なのだ｡<br />

{* ﾏｸﾞﾅ *}
{elseif $smarty.get.id == 'status-money'}

  この世界での通貨なのだ｡この世界の<a href="{url_for action='Shop' _backto=true}">ｼｮｯﾌﾟ</a>でﾓﾉ買うときに使うのだ｡<br />
  <br />
  当たり前だけど､他のｹﾞｰﾑとか{$smarty.const.PLATFORM_NAME}のｼｮｯﾌﾟで使えたりはしないのだ｡変なﾄｺに変なｸﾚｰﾑいれても理解してもらえないのだ｡<br />

{* ﾌｨｰﾘﾝｸﾞ *}
{elseif $smarty.get.id == 'status-feeling'}

  <a href="{url_for action='Status' _backto=true}">ｽﾃｰﾀｽ</a>画面で今の気持ちを入力できるのだ｡<br />
  <br />
  <span style="color:{#termColor#}">ﾌｨｰﾘﾝｸﾞ</span>は他のﾕｰｻﾞがお前のﾍﾟｰｼﾞを見たときに表示されるのだ｡<br />
  別にﾅﾆ入れてもいいのだ｡｢ﾌﾟﾚｾﾞﾝﾄくれ｣とか｢仲間ﾎﾞｼｭｰ｣とか書いとけば､誰か反応してくれるかもしれないのだ｡<br />
  でもﾋﾜｲなこととか他人を傷つけること書くななのだ｡｢(削除されました)｣ってなるのだ｡<br />

{* ﾊﾞﾄﾙﾁｭｰﾄﾘｱﾙ *}
{elseif $smarty.get.id == 'battle-tutorial'}

  もう一度ﾊﾞﾄﾙの基本教えてほしいのだ?<br />
  だったら<a href="{url_for module='Swf' action='TutorialBattle' help='1'}">ｺｺ</a>をｸﾘｯｸするのだ｡<br />

{* 戦術 *}
{elseif $smarty.get.id == 'battle-senjutu'}

  戦術について一つずつ教えるのだ｡<br />
  <br />
  <span style="color:{#termColor#}">強攻</span>はｲｹｲｹな戦術なのだ｡他の戦術に比べてﾀﾞﾒｰｼﾞ多くなる上に､相手の攻撃が同じ属性ならそれを無効にしちゃうのだ｡<br />
  ｵﾏｹに属性負けしてても無効にされず耐えられる場合があるのだ｡<br />
  でも､相手が<span style="color:{#termColor#}">吸収</span>選んでると吸収されやすいのだ｡<br />
  <br />
  <span style="color:{#termColor#}">吸収</span>はﾘﾍﾞﾝｼﾞ狙いの戦術なのだ｡相手の攻撃吸収してﾀﾞﾒｰｼﾞ0にして､その上ｽﾀｰが3つも増えるのだ｡<br />
  でも､ﾀﾞﾒｰｼﾞ増える上に成功率あんま高くないのだ｡ただ､相手が<span style="color:{#termColor#}">強攻</span>してきてるなら結構成功するのだ｡<br />
  <br />
  <span style="color:{#termColor#}">慎重</span>は基本的な戦術で､ちょっとだけﾀﾞﾒｰｼﾞ軽くできるのだ｡ﾀﾏ～に吸収もするのだ｡<br />
  <span style="color:{#termColor#}">強攻</span>も<span style="color:{#termColor#}">吸収</span>も選びにくいときに消去法で選ぶのだ｡<br />
  <br />
  <span style="color:{#termColor#}">ﾕﾆｿﾞﾝ</span>は選ぶ戦術じゃないのだ｡ｶｰﾄﾞ揃うと自動的にﾕﾆｿﾞﾝになるのだ｡<br />
  ﾕﾆｿﾞﾝは<span style="color:{#termColor#}">強攻</span>と同じようにﾀﾞﾒｰｼﾞが高くて､同じ属性でも相手のｶｰﾄﾞ無効にしちゃうのだ｡<br />
  さらに､無効にしたｶｰﾄﾞは相手のｽﾀｰにならないのだ｡おまけに属性負けしてても100%耐えられるのだ｡<br />
  さらにさらに､ﾕﾆｿﾞﾝすると相手は<span style="color:{#termColor#}">慎重</span>しか選択できないのだ｡だから吸収される心配も少ないのだ｡<br />

{* 戦術は何を選べば *}
{elseif $smarty.get.id == 'battle-senjutu2'}

  まずは相手によりなのだ｡<br />
  <span style="color:{#termColor#}">ｽﾋﾟｰﾄﾞ</span>で大きく勝ってるんなら<span style="color:{#termColor#}">吸収</span>成功しやすいのだから､吸収狙うのがいいと思うのだ｡<br />
  逆に<span style="color:{#termColor#}">ｽﾋﾟｰﾄﾞ</span>が大きく負けてると､<span style="color:{#termColor#}">吸収</span>選択してもあんま期待できないのだ｡<br />
  <br />
  そんで､状況によりなのだ｡<br />
  <br />
  攻撃ｶｰﾄﾞの属性が同じなら､同属性でも無効にできる<span style="color:{#termColor#}">強攻</span>がｾｵﾘｰなのだ｡<br />
  でもそう考えるのは相手も同じなのだ｡相手も<span style="color:{#termColor#}">強攻</span>してくるのだから､ｽﾋﾟｰﾄﾞ勝ってるなら裏をかいて<span style="color:{#termColor#}">吸収</span>するのもｱﾘなのだ｡<br />
  <span style="color:{#termColor#}">慎重</span>は…裏の<span style="color:{#termColor#}">吸収</span>の､さらに裏をかきたいときくらいなのだ?<br />
  <br />
  属性勝ちしてるなら…まず<span style="color:{#termColor#}">吸収</span>はあんま意味ないのだ｡属性で相手の攻撃無効にしちゃうのに､吸収狙っててもしょーがないのだ｡<br />
  ま､<span style="color:{#termColor#}">慎重</span>で着実にﾀﾞﾒｰｼﾞを与えるのがｾｵﾘｰなのだ?<br />
  <span style="color:{#termColor#}">強攻</span>でよりﾀﾞﾒｰｼﾞを増やしてもいいのだけど､相手がｽﾋﾟｰﾄﾞ速いときに<span style="color:{#termColor#}">吸収</span>選択されるとﾓｯﾀｲﾅｲ結果になるのだ｡<br />
  <br />
  属性負けしてると悩ましいのだ…<span style="color:{#termColor#}">吸収</span>で被害減少を狙うか､属性負けでも耐えられる場合がある<span style="color:{#termColor#}">強攻</span>で少しでも反撃を狙うか…なのだ｡<br />
  <span style="color:{#termColor#}">慎重</span>は…なんだか<span style="color:{#termColor#}">強攻</span>も<span style="color:{#termColor#}">吸収</span>も期待できないﾈｶﾞﾃｨﾌﾞな気分のときに選ぶといいのだ｡ちょっぴりだけど確実にﾀﾞﾒｰｼﾞ軽くなるのだ｡<br />

{* ﾘﾍﾞﾝｼﾞ *}
{elseif $smarty.get.id == 'battle-revenge'}

  ﾕﾆｿﾞﾝ以外で自分の攻撃無効にされたり､相手の攻撃吸収したりすると､足元の<span style="color:{#termColor#}">ｽﾀｰ</span>がたまっていくのだ｡10個たまると<span style="color:{#termColor#}">ﾘﾍﾞﾝｼﾞ</span>発動なのだ｡<br />
  いままでやられた分､まとめてやり返すのだ<br />
  <br />
  でも､ｽﾀｰはﾊﾞﾄﾙ終わると0にﾘｾｯﾄなのだ<br />
  一回のﾊﾞﾄﾙで10個ためるのはなかなか大変なのだ｡8ﾀｰﾝもあるﾕｰｻﾞ対戦ならともかく､4ﾀｰﾝしかないｸｴｽﾄのﾊﾞﾄﾙだと､<span style="color:{#termColor#}">吸収</span>決めないと難しいのだ<br />

{* ﾘﾍﾞﾝｼﾞの秘密 *}
{elseif $smarty.get.id == 'battle-revenge2'}

  ﾘﾍﾞﾝｼﾞ中に出てくるｶｰﾄﾞは､ﾘﾍﾞﾝｼﾞ前に何のｶｰﾄﾞをｽﾀｰにしてためたかで決まるのだ｡つまり､炎ばっか無効にされたなら炎ばっか出てくるのだ｡<br />
  <br />
  それと､ﾘﾍﾞﾝｼﾞのときのﾀﾞﾒｰｼﾞは自分のだけじゃなく相手の攻撃力も関わってるのだ｡<br />
  だから攻撃力ばっか高くて防御力が低い相手にﾘﾍﾞﾝｼﾞかけるとちょっとﾀﾞﾒｰｼﾞ大きいのだ｡逆に防御力のほうが高い相手はﾘﾍﾞﾝｼﾞの効果薄くなるのだ｡<br />

{* 攻撃力の秘密 *}
{elseif $smarty.get.id == 'battle-attsec'}

  他の属性と比べて攻撃力突出してるのがあるとその属性のｶｰﾄﾞ引きやすいのだ｡たとえば水の攻撃が突出してると水のｶｰﾄﾞ引きやすいのだ｡<br />
  だから水突出の状態で､炎突出してるﾔﾂに挑むとｵｲｼｰのだ｡逆に雷突出してるやつだとｲﾀﾀなのだ｡<br />

{* ｸｴｽﾄ一覧 *}
{elseif $smarty.get.id == 'quest-list'}
  今いる場所で実行できる<span style="color:{#termColor#}">ｸｴｽﾄ</span>の一覧なのだ｡<br />
  <span style="color:{#termColor#}">移動</span>で場所を変えると一覧も変わるのだ｡<br />
  <br />
  同じ場所でも条件次第で一覧が変わることもあるから要ﾁｪｯｸなのだ｡<br />

{* 移動 *}
{elseif $smarty.get.id == 'quest-move'}
  別の場所に移動するのだ｡<br />
  場所が変わると<span style="color:{#termColor#}">ｸｴｽﾄ</span>の一覧が変わるのだ｡場所によっては<span style="color:{#termColor#}">ｼｮｯﾌﾟ</span>も変わるのだ｡<br />
  <br />
  移動できる場所はお話の進行具合で増えてくのだ｡なんか別の場所に移動しそうなお話になったら移動してみるといいのだ<br />
  <br />
  <a href="{url_for action='QuestList'}">ｸｴｽﾄ一覧</a>に<span style="color:{#termColor#}">｢移動｣</span>っていうﾘﾝｸがあるから､そこからするのだ｡<br />
  {if ($carrier == 'iphone' || $carrier == 'android')}
	  なんと<a href="{url_for action='Move'}">ｺｺ</a>からもできちゃうのだ<br />
  {else}
	  なんと<a href="{url_for module='Swf' action='Move'}">ｺｺ</a>からもできちゃうのだ<br />
  {/if}
{* ｸｴｽﾄのﾀｲﾌﾟ *}
{elseif $smarty.get.id == 'quest-type'}
  <span style="color:{#termColor#}">ｸｴｽﾄ</span>にはいろいろ種類があるのだ｡<br />
  <br />
  <span style="color:{#termColor#}">探索</span>は情報収集なのだ｡なんか起きるかもしれないし､しょーもなく終わるかも知れないのだ<br />
  <br />
  <span style="color:{#termColor#}">ｲﾍﾞﾝﾄ</span>はﾅﾝｶの出来事なのだ｡そのまんまなのだ…実行してみれば分かるのだ｡<br />
  <br />
  <span style="color:{#termColor#}">ﾌｨｰﾙﾄﾞ</span>はﾌｨｰﾙﾄﾞなのだ｡最初の<span style="color:{#termColor#}">精霊の洞窟</span>みたいなやつなのだ｡<br />
  ﾌｨｰﾙﾄﾞｸｴｽﾄのｸﾘｱの仕方はいろいろなのだ｡<br />
  その都度説明されてるはずだからよく見るのだ｡<br />

{* ﾌｨｰﾙﾄﾞｸｴｽﾄについて *}
{elseif $smarty.get.id == 'quest-fieldrest'}
  ﾌｨｰﾙﾄﾞｸｴｽﾄは常にｾｰﾌﾞされてるから､いつでも中断して再開できるのだ｡<br />
  再開するときは<a href="{url_for action='Main'}">ｿﾈｯﾄﾒﾆｭｰ</a>から<span style="color:{#termColor#}">ｸｴｽﾄ</span>を選べば<span style="color:{#termColor#}">再開</span>っていうﾒﾆｭｰが出てくるのだ｡<br />
  <br />
  そんときに<span style="color:{#termColor#}">再開</span>と一緒に<span style="color:{#termColor#}">ｷﾞﾌﾞｱｯﾌﾟ</span>っていうのがあるのだ｡<br />
  ｷﾌﾞｱｯﾌﾟするとｸｴｽﾄ強制終了できるのだ｡失敗と同じ扱いで､<span style="color:{#termColor#}">{$smarty.const.GOLD_NAME}</span>取られちゃうけど､最初からやり直したいときは選ぶといいのだ｡<br />
  失敗とかｷﾞﾌﾞｱｯﾌﾟで取られる<span style="color:{#termColor#}">{$smarty.const.GOLD_NAME}</span>はｸｴｽﾄによってさまざまなのだ<br />
  <br />
  あとは…ﾌｨｰﾙﾄﾞｸｴｽﾄに出てる間は装備変更できないのだ｡<br />
  んで､ｸｴｽﾄに持ち出してる消費ｱｲﾃﾑはｸｴｽﾄ以外で使ったりﾌﾟﾚｾﾞﾝﾄしたりはできないのだ｡<br />
  そんで…ｸｴｽﾄ中は他の場所に<span style="color:{#termColor#}">移動</span>できないのだ｡<br />
  どーしてもしたいなら､ﾁｬｯﾁｬｯとｸﾘｱしちゃうか､<span style="color:{#termColor#}">ｷﾞﾌﾞｱｯﾌﾟ</span>で強制終了するしかないのだ｡<br />
  <br />
  それとそれと…ｸｴｽﾄ中のﾕｰｻﾞに対戦を挑むことはできないのだ｡でも何時間もほったらかしてるやつには挑めるのだ｡<br />
  <br />
  全部言えたのだ<br />

{* ﾓﾝｽﾀｰのｱｲﾃﾑﾄﾞﾛｯﾌﾟ *}
{elseif $smarty.get.id == 'quest-itemdrop'}
  ﾌｨｰﾙﾄﾞｸｴﾄでﾓﾝｽﾀｰ倒してると､ときとぎｱｲﾃﾑ落とすのだ｡<br />
  なに落とすかはﾓﾝｽﾀｰによっていろいろなのだ｡おんなじ名前のﾓﾝｽﾀｰでもｸｴｽﾄが違えば落とすものが変わるのだ｡<br />

{* ｼｮｯﾌﾟ *}
{elseif $smarty.get.id == 'item-shop'}

  ｱｲﾃﾑや装備は<a href="{url_for action='Shop' _backto=true}">ｼｮｯﾌﾟ</a>で<span style="color:{#termColor#}">{$smarty.const.GOLD_NAME}</span>を使って買うのだ｡場所や条件が変わると売ってるものが変わるのだ｡<br />
  <br />
  <span style="color:{#termColor#}">{$smarty.const.PLATFORM_CURRENCY_NAME}</span>で買い物するなら､いつでもどこでも同じもの買えるのだ｡<span style="color:{#termColor#}">{$smarty.const.PLATFORM_CURRENCY_NAME}</span>でしか買えない物もたくさんあるのだ｡<br />
  <br />
  ｼｮｯﾌﾟの奥のほうに<a href="{url_for action='GachaList' _backto=true}">ｶﾞﾁｬｼｮｯﾌﾟ</a>っていうのがあるのだ｡例によって<span style="color:{#termColor#}">{$smarty.const.PLATFORM_CURRENCY_NAME}</span>なのだけど､<span style="color:{#termColor#}">ﾌﾘｰﾁｹｯﾄ</span>あるならﾀﾀﾞでできるのだ｡やっぱり例によって､ｶﾞﾁｬにはいいものがあるのだ｡<br />

{* 装備品について *}
{elseif $smarty.get.id == 'item-soubi'}

  装備品は持ってるだけじゃ意味ないのだ｡装備しなきゃﾀﾞﾒなのだ｡<a href="{url_for action='Status' _backto=true}">ｽﾃｰﾀｽ</a>画面から装備変更できるのだ｡<br />
  <br />
  装備してﾊﾞﾄﾙしてるとたまに<span style="color:{#termColor#}">装備品のﾚﾍﾞﾙ</span>が上がるのだ｡上がると性能良くなるのだ｡…たいていは｡<br />
  でもﾚﾍﾞﾙ上がる代わりに<span style="color:{#termColor#}">耐久値</span>落ちていくのだ｡<span style="color:{#termColor#}">耐久値</span>が<span style="color:{#statusValueColor#}">0</span>を下回ると<span style="color:{#strongColor#}">いつか壊れる</span>のだ｡<span style="color:{#statusValueColor#}">{const name='User_ItemService::USEFUL_LIMIT'}</span>まで行くと100%壊れるのだ｡<br />
  <br />
  耐久値は<span style="color:{#termColor#}">ﾏﾙﾃｨｰﾆの槌</span>とかで回復できるのだ｡回復させてもﾚﾍﾞﾙ落ちたりはしないのだ｡<br />
  <br />
  <span style="color:{#termColor#}">武器</span>は攻撃力､<span style="color:{#termColor#}">服</span>は防御力､<span style="color:{#termColor#}">頭</span>はｽﾋﾟｰﾄﾞを主に上げるのだ｡<br />
  <span style="color:{#termColor#}">ｱｸｾｻﾘ</span>はいろいろ上げるのだけど､壊れやすいのだ｡<br />

{* ｱｲﾃﾑについて *}
{elseif $smarty.get.id == 'item-item'}

  消費ｱｲﾃﾑには<span style="color:{#termColor#}">くすりびん</span>とか<span style="color:{#termColor#}">にわとりの時計</span>とかいろいろあるのだ｡<br />
  共通して言えるのは一回使うとなくなることだけなのだ｡<br />
  どれも効果全然違うから説明書きをよく読むのだ｡<br />
  <br />
  HP回復するやつとか敵を攻撃するやつはﾌｨｰﾙﾄﾞｸｴｽﾄに持ち出せるのだ｡<br />
  持ち出しても使うまでは数減らないから､いるかな?と思ったらとりあえず持ち出しとけなのだ｡<br />

{* 仲間 *}
{elseif $smarty.get.id == 'he-member'}

  他のﾕｰｻﾞに<span style="color:{#termColor#}">仲間申請</span>を送って､承認されたら<span style="color:{#termColor#}">仲間</span>にできるのだ｡<br />
  <br />
  だれでもいいから申請してみるのだ｡<br />
  他のﾕｰｻﾞｰに仲間申請を送って承認されたら仲間にできるのだ｡<br />
  <br />
  仲間になればﾁｰﾑ対戦で一緒に戦ったり､つぶやきのﾀｲﾑﾗｲﾝを仲間と共有できるのだ｡<br />
  仲間が何をやってるかも見れるのだ｡<br />
  <br />
  もし困ったことがあったら装備くださいとかくすりびんくださいとか呼びかけてみるのもいいのだ｡だれか助けてくれるかもしれないのだ｡<br />

{* 通常対戦 *}
{elseif $smarty.get.id == 'he-battle'}

  他ﾕｰｻﾞと1vs1で対戦するのだ｡ﾕｰｻﾞ同士で対戦してると<span style="color:{#termColor#}">階級pt</span>が入って<span style="color:{#termColor#}">階級</span>上がるのだ｡<br />
  <br />
  ﾕｰｻﾞ対戦は8ﾀｰﾝまであるのだ｡8ﾀｰﾝ経過すると引き分けなのだ<br />
  <br />
  <span style="color:{#termColor#}">仲間</span>でもﾊﾞﾄﾙできるのだ｡したかったらすればいいのだ｡<br />
  <br />
  あと､一人のﾕｰｻﾞとﾊﾞﾄﾙできるのは一日<span style="color:{#statusValueColor#}">{$smarty.const.DUEL_LIMIT_ON_DAY_RIVAL}</span>回までなのだ｡<br />

{* ﾁｰﾑ対戦 *}
{elseif $smarty.get.id == 'he-teambattle'}

  自分のﾕｰｻﾞの<span style="color:{#termColor#}">仲間</span>達と､他のﾕｰｻﾞの<span style="color:{#termColor#}">仲間</span>達で対戦するのだ<br />
  <br />
  ﾁｰﾑﾒﾝﾊﾞｰは対戦するたびに自分の仲間から<span style="color:{#statusValueColor#}">2</span>人選ぶのだ｡同じ仲間は一日一回しか選べないから注意するのだ<br />
  <br />
  相手のﾒﾝﾊﾞｰは相手の仲間からﾗﾝﾀﾞﾑに選ばれるのだ｡最高で<span style="color:{#statusValueColor#}">7</span>人になるのだけど､相手の<span style="color:{#termColor#}">ﾚﾍﾞﾙ</span>合計がこっちの<span style="color:{#termColor#}">ﾚﾍﾞﾙ</span>合計を超えたらそれ以上追加されないのだ<br />
  <br />
  <span style="color:{#termColor#}">ﾁｰﾑ対戦</span>するには<span style="color:{#termColor#}">ﾁｰﾑ対戦ﾁｹｯﾄ</span>がいるのだ｡一日一枚なら<span style="color:{#termColor#}">通常対戦</span>するともらえるのだ｡<span style="color:{#termColor#}">ｼｮｯﾌﾟ</span>でも<a href="{url_for action='Shop' cat='ITM' currency='coin' buy='99002'}">買える</a>のだ

{* ﾒｯｾｰｼﾞについて *}
{elseif $smarty.get.id == 'he-message'}

  他のﾕｰｻﾞに<span style="color:{#termColor#}">ﾒｯｾｰｼﾞ</span>送れるのだ｡…と言ってもﾅｲｼｮのお話じゃないのだ｡他人からも見れるのだ｡<br />
  ｴﾛいこととか傷つけるようなこと書くななのだ｡｢(削除されました)｣ってなるのだ｡<br />
  <br />
  あと､ﾒｯｾｰｼﾞ送ると受けたほうが<span style="color:{#termColor#}">階級pt</span>ﾁｮｯﾋﾟﾘ増えるのだ｡<br />

{* ﾌﾟﾚｾﾞﾝﾄについて *}
{elseif $smarty.get.id == 'he-present'}

  自分が持ってるｱｲﾃﾑ､他人にﾌﾟﾚｾﾞﾝﾄできるのだ｡<br />
  …でも､ﾌﾟﾚｾﾞﾝﾄしても特に何もないのだ｡いらなくなったもの､捨てるつもりでﾌﾟﾚｾﾞﾝﾄするのだ｡<br />

{* 自分のﾍﾟｰｼﾞへのﾘﾝｸ *}
{elseif $smarty.get.id == 'other-link'}

  {$smarty.const.PLATFORM_COMMUNITY_NAME}とかに､自分のﾍﾟｰｼﾞへのﾘﾝｸを貼っつける方法なのだ｡<br />
  <br />

  {if $smarty.const.PLATFORM_TYPE == 'gree'}
    この枠の内容､ｹｰﾀｲでｺﾋﾟｰして…<form action=""><input type="text" name="" value='&lt;a href=&quot;{$url}&quot;&gt;{$userInfo.short_name}のﾍﾟｰｼﾞ&lt;/a&gt;' /></form><br />
    <span style="color:{#termColor#}">{$smarty.const.PLATFORM_COMMUNITY_NAME}</span>とかにﾍﾟｰｽﾄするだけなのだ｡<br />

  {elseif $smarty.const.PLATFORM_TYPE == 'mbga'}

    んと…まずはこのﾍﾟｰｼﾞをMOBAGEのﾌﾞｸﾏとして登録するのだ｡<br />
    ﾌﾞｸﾏ登録したら<span style="color:{#termColor#}">『ﾘﾝｸ:{$smarty.const.SITE_NAME}』</span>っていうのが出てくるのだ｡それをｹｰﾀｲでｺﾋﾟｰして､<span style="color:{#termColor#}">{$smarty.const.PLATFORM_COMMUNITY_NAME}</span>とかにﾍﾟｰｽﾄするするのだ｡<br />
    ﾌﾞｸﾏはｷｰを押したらできるのだ｡<br />

  {elseif $smarty.const.PLATFORM_TYPE == 'mixi'}
    この枠の内容､ｹｰﾀｲでｺﾋﾟｰして…<form action=""><input type="text" name="" value="{$url}" /></form><br />
    <span style="color:{#termColor#}">{$smarty.const.PLATFORM_COMMUNITY_NAME}</span>とかにﾍﾟｰｽﾄするだけなのだ｡<br />

  {else}
    あんだーこんすとらくしょんなのだ｡<br />
    あるいはただ忘れられてるだけなのだ…<br />
  {/if}

  <br />
  {if $smarty.const.OFFICIAL_COMMUNITY_URL}
    {$smarty.const.PLATFORM_COMMUNITY_NAME}､<a href="{$smarty.const.OFFICIAL_COMMUNITY_URL}">ｺｺ</a>からも行けるのだ<br />
  {/if}

{* ランキングについて *}
{elseif $smarty.get.id == 'other-ranking'}

  ﾕｰｻﾞ対戦で獲得した<span style="color:{#termColor#}">階級pt</span>を週ごとに集計して順位付けしてるのが<span style="color:{#termColor#}">ﾊﾞﾄﾙｲﾍﾞﾝﾄ</span>なのだ｡<br />
  ﾊﾞﾄﾙｲﾍﾞﾝﾄで上位に入ると…<br />
  <br />
  1～3位 <span style="color:{#termColor#}">ｶﾞﾁｬ共通ﾌﾘｰﾁｹｯﾄ</span><br />
  4～10位 <span style="color:{#termColor#}">ﾆﾜﾄﾘの時計</span><br />
  11～20位 <span style="color:{#termColor#}">ﾏﾙﾃｨｰﾆの槌</span><br />
  <br />
  がもらえるのだ!<br />
  <br />
  日曜0時から次の日曜0時までが期間で､木曜と土曜のAM4:00に途中集計されるのだ｡日曜のAM4:00に最後の集計があって賞品が出るのだ!<br />

{* 友達招待について *}
{elseif $smarty.get.id == 'other-shoutai'}

  {$smarty.const.PLATFORM_NAME}の友だちをこのｹﾞｰﾑに誘うのだ｡友だちが応じてくれたら…<br />
  {foreach from=`$ibonus` item='item'}
    {item_image id=`$item.item_id`}<span style="color:{#termColor#}">{$item.item_name}</span>{if $ibonusNum[$item.item_id] >= 2}<span style="color:{#statusValueColor#}">x{$ibonusNum[$item.item_id]}</span>{/if}<br />
  {/foreach}
  が手に入るのだ｡<br />
  <br />

  さらに応じてくれた友だちにも…<br />
  {foreach from=`$abonus` item='item'}
    {item_image id=`$item.item_id`}<span style="color:{#termColor#}">{$item.item_name}</span>{if $abonusNum[$item.item_id] >= 2}<span style="color:{#statusValueColor#}">x{$abonusNum[$item.item_id]}</span>{/if}<br />
  {/foreach}
  が手に入るのだ｡<br />
  <br />

  <span style="color:{#noticeColor#}">特典が発生するのはﾁｭｰﾄﾘｱﾙが終了したときです</span><br />
  <br />

  とりあえず誘っとけなのだ｡<br />

  <div style="text-align:center">
    <a href="{$url}" class="buttonlike next">ｹﾞｰﾑに招待する</a><br />
  </div>

{/if}

<br />
{if $smarty.get.backto}<a href="{backto_url}" class="buttonlike back">←戻る</a><br />{/if}
{if ($carrier != 'iphone' && $carrier != 'android')}
<a href="{url_for _self=true id=''}" class="buttonlike back">←ﾍﾙﾌﾟ一覧へ</a><br />
{/if}

{include file="include/footer.tpl"}
