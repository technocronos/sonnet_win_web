{include file='include/header.tpl'}


<h2>チュートリアルステップ別人数</h2>

<p>
  {include file='include/show_resultset.tpl'
    resultset=`$list`
    colCaptions=`$colCaptions`
    colWidth=`$colWidth`
    colTypes=`$colTypes`
  }
</p>

<p>
  <dl>
    <dt>放置数</dt><dd>
      総人数のうち、ラストアクセスが7日以上前の人数<br />
      アンインストールしているとは限らない。
    </dd>
    <dt>ｱﾝｲﾝｽﾄｰﾙ</dt><dd>総人数のうち、アンインストールしている人数</dd>
  </dl>
</p>


{include file='include/footer.tpl'}
