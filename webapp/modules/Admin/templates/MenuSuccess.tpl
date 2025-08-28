{include file='include/header.tpl'}


<h2>{$smarty.const.SITE_NAME}[{$smarty.const.PLATFORM_TYPE}]管理</h2>

<p>
  参照
  <ul>
    <li>
      ユーザ<br />
      <ul>
        <li><a href="?module=Admin&action=FindUser" target="result">検索</a></li>
        <li><a href="?module=Admin&action=Regist" target="result">登録数</a></li>
        <li><a href="?module=Admin&action=Tutorial" target="result">チュートリアル別人数</a></li>
        <li><a href="?module=Admin&action=ItemDistribute" target="result">配布受取数</a></li>
        <li><a href="?module=Admin&action=GiveItem" target="result">アイテム付与</a></li>
        <li><a href="?module=Admin&action=BitcoinGet" target="result">BTC取得ログ</a></li>
        <li><a href="?module=Admin&action=Bitcoin" target="result">BTC出金ログ</a></li>
      </ul>
    </li>
    <li><a href="?module=Admin&action=Uriage" target="result">売り上げ(課金)</a></li>
    {if PLATFORM_TYPE == "nati"}<li><a href="?module=Admin&action=UriageNative" target="result">売り上げ(コイン)</a></li>{/if}
    <!--<li><a href="?module=Admin&action=PageStatis" target="result">ページ集計</a></li>-->
    <li><a href="?module=Admin&action=QuestStatis" target="result">クエスト集計</a></li>
    <li><a href="?module=Admin&action=DeliveryList" target="result">メッセージ配信</a></li>
    <li><a href="?module=Admin&action=UserBunpu" target="result">ユーザ分布(レベル＆階級)</a></li>
    <li><a href="?module=Admin&action=UserMessage" target="result">ユーザ間メッセージ</a></li>
    <li><a href="?module=Admin&action=OshiraseList" target="result">お知らせ</a></li>
  </ul>
</p>

<p>
  データ更新
  <ul>
    <li><a href="?module=Admin&action=ItemLevel" target="result">装備パラメータ変更</a></li>
    <li><a href="?module=Admin&action=EditRoom" target="result">フィールドルーム編集</a></li>
  </ul>
</p>

<p style="margin-top:20em">
  デバック用
  <ul>
    <li><a href="?module=Admin&action=TextMaster" target="result">テキストマスタ</a></li>
    <li><a href="?module=Admin&action=RaidDungeonList" target="result">レイドダンジョン表示</a></li>
    <li><a href="?module=Admin&action=BattleRankInfo" target="result">バトルランキング情報表示</a></li>
    <li><a href="?module=Admin&action=SearchBattle" target="result">バトルデータ検索</a></li>
    <li><a href="?module=Admin&action=ShowBattle" target="result">バトルデータ表示</a></li>
    <li><a href="?module=Admin&action=CharaImg" target="result">キャライメージ表示</a></li>
    <li><a href="?module=Admin&action=ShowDrama" target="result">寸劇表示</a></li>
    <li><a href="?module=Admin&action=ResetUser" target="result">ユーザリセット</a></li>
    <li><a href="?module=Admin&action=FlagonValid" target="result">汎用フラグオン</a></li>
    <li><a href="?module=Admin&action=ShowSphere" target="result">スフィア表示</a></li>
    <li><a href="?module=Admin&action=GainExp" target="result">キャラ経験値付与</a></li>
    <li><a href="?module=Admin&action=FixExp" target="result">キャラ経験値修正</a></li>
    <li><a href="?module=Admin&action=NFTList" target="result">NFT一覧表示</a></li>
    <!--<li><a href="?module=Admin&action=Battlelog" target="result">バトルログ閲覧</a></li>-->
    <li><a href="?module=Admin&action=CheckJson" target="result">JSONデコードチェック</a></li>
    <li><a href="?module=Admin&action=TextInspect" target="result">監査テキスト</a></li>
    <li><a href="?module=Admin&action=ShowUser" target="result">ユーザ問い合わせ</a></li>
    <li><a href="?module=Admin&action=TextGroup" target="result">テキストグループ(モバゲ)</a></li>
    <li><a href="?module=Admin&action=PhpInfo" target="result">phpinfo</a></li>
  </ul>
</p>


{include file='include/footer.tpl'}
