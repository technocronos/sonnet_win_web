{include file="include/header.tpl" title=""}


<br />

{if $smarty.get.id == 'BattleStartError'}

  すでに開始されている対決を再度開始することはできません。<br />

  <br />
  <a href="{backto_url}" class="buttonlike back">←戻る</a><br />

{elseif $smarty.get.id == 'Timeout'}

  表示しようとしたページは有効期限が切れています。<br />

  <br />
  <a href="{backto_url}" class="buttonlike back">←戻る</a><br />

{elseif $smarty.get.id == 'NoRegisterAccess'}

  表示しようとしたﾍﾟｰｼﾞはﾕｰｻﾞ登録が必要です｡<br />
  <br />
  ｹﾞｰﾑﾄｯﾌﾟからｹﾞｰﾑを開始して､ﾕｰｻﾞ登録を行ってください｡<br />
  <a href="{url_for action='Index'}" class="buttonlike back">←ｹﾞｰﾑﾄｯﾌﾟ</a><br />

{elseif $smarty.get.id == 'UnderConstruct'}

  {image_tag file='jikai_yokoku.jpg'}<br />
  <br />

  ようやく亜人の大陸にたどり着いた一行<br />
  失われたトロルの里を求めて亜人の大陸で大暴れ！！<br />
  圧制を敷くﾏﾙﾃｨｰﾆと滅び行く亜人の間で苦闘する主人公の活躍をお楽しみに!<br />

  <br />
  <a href="{url_for action='QuestList'}" class="buttonlike back">←戻る</a><br />

{elseif $smarty.get.id == 'Shoutai'}

  {image_tag file='navi_mini.gif' float='left'}
  誰かさそったのだ?<br />
  招待特典は招待に応じたやつがﾁｭｰﾄﾘｱﾙ終わったときにもらえるのだ<br />

  <br />
  {if $smarty.get.backto}
    <a href="{backto_url}" class="buttonlike back">←戻る</a><br />
  {else}
    <a href="{url_for action='Main'}" class="buttonlike back">←ｿﾈｯﾄﾒﾆｭｰ</a><br />
  {/if}

{elseif $smarty.get.id == 'community'}

  公開後は公式{$smarty.const.PLATFORM_COMMUNITY_NAME}に遷移します。<br />
  <br />
  <a href="{url_for action='Index'}" class="buttonlike back">←戻る</a><br />

{elseif $smarty.get.id == 'operator'}

  【特定商取引法に基づく表記】 <br />
  ●事業者名称:<br />
  {$smarty.const.COMPANY_NAME}<br />
  <br />
  ●所在地:<br />
  〒107-0061 東京都港区北青山1-4-1 ランジェ青山805<br />
  <br />
  ●お問合わせ先:<br />
  毎日 10:00～18:00 03-6459-2398<br />
  <br />
  ●代表者:<br />
  山内 陽一郎<br />
  <br />
  ●販売価格:<br />
  対象となるデジタルコンテンツ(以下「コンテンツ」)ごとに表示される価格。<br />
  <br />
  ●販売価格以外の費用:<br />
  パケット代等の携帯電話の利用に伴う通信費用。<br />
  <br />
  ●代金のお支払時期およびお支払方法:<br />
  (1)お支払時期<br />
  コンテンツ提供の前<br />
  (2)お支払方法<br />
  {$smarty.const.PLATFORM_CURRENCY_NAME}によるお支払いとなります。<br />
  <br />
  ●提供時期:<br />
  コンテンツは、お支払手続完了後、直ちに提供いたします。<br />
  <br />
  ●返品について:<br />
  コンテンツの返品及び交換はできないものとします。<br />
  <br />
  ●対応端末一覧:<br />
  docomo:FOMA70xi,90xi,STYLEｼﾘｰｽﾞ,PRIMEｼﾘｰｽﾞ,PROｼﾘｰｽﾞのちSH端末<br />
  AU:WIN対応機種<br />
  Softbank:ﾑｰﾋﾞｰ写メール対応3G機種※Xｼﾘｰｽﾞ及び iPhone 3G ではご利用いただけません<br />
  <br />
  <br />
  【プライバシーポリシー】 <br />
  当アプリでは、ユーザのこのアプリ上での活動を、このアプリをインストールしている他のユーザに表示することがあります。<br />
  このとき、{$smarty.const.PLATFORM_NAME}上におけるユーザ名を取得し、他のユーザに表示しています。<br />
  <br />
  取得したユーザ名の、漏洩、滅失またはき損の防止その他の安全管理のために必要かつ適切な措置を講じます。
  <br />
  その他にユーザ個人を特定するような情報は収集しておりませんので、個人情報の漏洩や電話、手紙、メールによるセールスはありません。<br />

{elseif $smarty.get.id == 'non-compliant'}

  申し訳ありません。<br />
  <br />
  お客様がお使いの端末には対応しておりません。<br />
{elseif $smarty.get.id == 'non-compliant_browser'}

  申し訳ありません。<br />
  <br />
  お客様がお使いのブラウザには対応しておりません。<br />
  ※推奨環境 Chrome/Firefox<br />

{elseif $smarty.get.id == 'data-delete'}

データ削除の際は下記までご連絡下さい<br><br>

sonnet.userhelp@gmail.com<br><br>

{/if}


{include file="include/footer.tpl" hideNavigator=`$hideNavigator`}
