{
    "extra_uniticons": ["boy", "man", "woman", "shisyou", "uncle", "servant"]
  , "rooms": {
        "start": {
            "id": 31001000
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [0,11]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわー！！ここが\nマルティーニかぁ…"
                      , "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ そうみたいなのだ\nやっぱりイナカの島とは\nちがうのだ…"
                      , "UALGN %avatar% 2"
                      , "LINES %avatar% うーん。レンガ造りの\n町並み。キレイ…"
                      , "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ お前あまりキョロキョロするななのだ\nいなかもんと思われるのだ"
                      , "UALGN %avatar% 2"
                      , "LINES %avatar% なーにミエ張ってるのよ！"
                      , "SPEAK %avatar% もじょ ま、ちょっとうろうろしてみるのだ"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [18,12], "rb": [19,13]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "SPEAK %man1% 男 ねえ、君\nちょっと話があるんだけど\nいいかい？"
                      , "LINES %avatar% え？\nな、なんですか？"
                      , "SPEAK %man1% 男 実は・・この化粧品が\nほんとに安くてねえ"
                      , "SPEAK %man1% 男 これ、ちょっと他では\n手に入らないんだけど"
                      , "LINES %avatar% い、いや・・"
                      , "SPEAK %avatar% もじょ こいつが化粧品なんて\n使うように見えるのだ？"
                      , "LINES %avatar% もじょ！\nそういう言い方って・・"
                      , "SPEAK %man1% 男 だ・よ・ねー\n失礼なネコだな、ホント"
                      , "SPEAK %man1% 男 僕にはワカル\n君は輝く原石だよ"
                      , "LINES %avatar% え？そ、そうかな・・"
                      , "SPEAK %avatar% もじょ 全く口だけは上手いやつなのだ"
                      , "SPEAK %avatar% もじょ 乗せられるななのだ"
                      , "LINES %avatar% なはは\nだって悪い気しないじゃん"
                      , "SPEAK %avatar% もじょ いい加減にするのだ"
                      , "LINES %avatar% そ、そうだね"
                      , "LINES %avatar% あ、あの\nやっぱすいません\n使わないし・・"
                      , "SPEAK %man1% 男 ね、値段だけでも聞いてよ\n何と20000マグナのところを\nご奉仕価格で10000マグナ！"
                      , "LINES %avatar% た、高っか！！"
                      , "SPEAK %man1% 男 じゃ、じゃあおまけに\nもう一つつけちゃうよ！"
                      , "LINES %avatar% だったら、\nその分また半額にしてよ・・"
                      , "SPEAK %man1% 男 まあまあ、そういわずに・・"
                      , "LINES %avatar% 逃げろ！！"
                    ]
                  , "chain": "avatar_runaway"
                }
              , "avatar_runaway": {
                     "type": "runaway"
                   , "chain": "man1_speak_end"
                }
              , "man1_speak_end": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %man1% 男 あ、まってよーキミ！！！"
                      , "SPEAK %man1% 男 この化粧品はコラーゲンと\nデオキシリボ核酸と\nトラメキサムサン菌の作用が・・"
                      , "LINES %avatar% はあはあ、\nなんだったんだ・・"
                      , "SPEAK %avatar% もじょ 困った押し売りだったのだ"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [10,11], "rb": [12,12]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man2%"
                      , "SPEAK %man2% ワーカー ん？嬢ちゃんもよそから\n来たのかい？"
                      , "SPEAK %man2% ワーカー 私も出稼ぎでマルティーニに\n来てるんだ"
                      , "SPEAK %man2% ワーカー セメント山で働いてるんだけど\nあそこは魔物も出たりするし、\n危険な仕事だよ"
                      , "LINES %avatar% それでも働かなきゃだから\n出稼ぎは大変ですね"
                      , "SPEAK %man2% ワーカー いや、働いてればいつか\n市民証も取れるかも\nしれないしね"
                      , "SPEAK %man2% ワーカー そうなれば晴れて\nマルティーニ市民に\nなれるってわけさ"
                      , "LINES %avatar% マルティーニ市民になると\nいいことあるの？"
                      , "SPEAK %man2% ワーカー そりゃそうさ\n待遇が出稼ぎとは\n雲泥の差だよ"
                      , "LINES %avatar% へえ・・"
                      , "SPEAK %man2% ワーカー 出稼ぎでも\n働いたり税金を納めたりし続ければ\nマルティーニからポイントが与えられて"
                      , "SPEAK %man2% ワーカー そのポイントが十万ポイントに\nなったら市民証をゲットできるんだ"
                      , "LINES %avatar% へえー・・\n十万ポイントかあ・・"
                      , "SPEAK %avatar% もじょ おっちゃんは今何ポイント\n持ってるのだ？"
                      , "SPEAK %man2% ワーカー ・・2000ポイント・・"
                      , "LINES %avatar% ・・ってまだ2000なの？"
                      , "SPEAK %man2% ワーカー そういうな・・\nここに来て3年間\n働いたがこれがやっとなんだ・・"
                      , "SPEAK %man2% ワーカー だけどいつか！\n市民証をゲットして\n城下町の中に入りたいんだ！"
                      , "SPEAK %avatar% もじょ はるか霞がかかった\n目標なのだ・・"
                      , "SPEAK %man2% ワーカー それでもそれが頑張る\n甲斐なんだよ"
                      , "SPEAK %man2% ワーカー みんなそうさ"
                      , "SPEAK %avatar% もじょ マルティーニも\n上手いことやってるのだ"
                      , "LINES %avatar% もじょ！"
                      , "LINES %avatar% じゃ、がんばってください"
                      , "SPEAK %man2% ワーカー おう"
                    ]
                }
              , "man3_speak": {
                    "trigger": "hero"
                  , "pos": [0,3], "rb": [2,4]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %man3% ワーカー ハァ～\nなかなかポイントが貯まらないなぁ・・"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [3,9], "rb": [5,10]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                      , "SPEAK %woman1% お姉さん あら一人旅？\nここはポートモールの港町よ"
                      , "SPEAK %woman1% お姉さん ポートモールは港町\nだから毎日色んな人が\n来るから"
                      , "SPEAK %woman1% お姉さん あまり一人で\nうろうろしない方がいいわよ"
                    ]
                }
              , "woman2_speak": {
                    "trigger": "hero"
                  , "pos": [6,6], "rb": [7,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman2%"
                      , "SPEAK %woman2% お姉さん え？\nマルティーニ城？\n何か用があるの？"
                      , "LINES %avatar% いえ、ないですけど\nどこにあるのかなーなんて"
                      , "SPEAK %woman2% お姉さん あの山の向こうよ\n一時間くらいじゃないかしら"
                      , "SPEAK %avatar% もじょ あんなとこ\n一時間でいけるわけ\nないのだ"
                      , "SPEAK %woman2% お姉さん あら、ネコちゃん\n汽車に乗れば\nそれくらいで着くのよ"
                      , "SPEAK %avatar% もじょ 汽車？\nなんなのだ？それ"
                      , "SPEAK %woman2% お姉さん 乗り物なんだけど\n機会があったら乗って\nみるといいわよ"
                    ]
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [6,12], "rb": [8,13]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "SPEAK %old1% 老人 マルティーニ城は町自体\nが壁で囲まれてて\n門番が厳重に見張っておる"
                      , "SPEAK %old1% 老人 マルティーニ城下町へは\n市民証が必要じゃ"
                      , "LINES %avatar% なんでそんなに厳重なんだろ"
                      , "SPEAK %old1% 老人 そりゃマルティーニに\nうらみを持つ人は多いからのう"
                      , "SPEAK %old1% 老人 レジスタンス活動とかも\n最近活発で怖いことじゃ"
                      , "SPEAK %old1% 老人 おぬしも巻き込まれない\nように気をつけるがよい"
                      , "LINES %avatar% レジスタンスって\nなんですか？"
                      , "SPEAK %old1% 老人 マルティーニの反政府運動じゃよ"
                      , "SPEAK %old1% 老人 砂漠あたりを拠点にして\n破壊活動やってるってうわさじゃ"
                      , "LINES %avatar% へー・・"
                    ]
                }
              , "heishi1_speak": {
                    "trigger": "hero"
                  , "pos": [4,3], "rb": [6,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %heishi1%"
                      , "SPEAK %heishi1% 憲兵 私はマルティーニの憲兵だ"
                      , "SPEAK %heishi1% 憲兵 んー？\nなんだお前は"
                      , "SPEAK %heishi1% 憲兵 怪しいやつだな\n市民証見せてみろ"
                      , "LINES %avatar% あ、いや\n今日来たばっかなんですけど"
                      , "SPEAK %heishi1% 憲兵 どこから来たんだ？貴様"
                      , "LINES %avatar% いえ、\n名もないような島からでして・・"
                      , "LINES %avatar% ・・あの、その市民証って\nどうやって取れるんですか？"
                      , "SPEAK %heishi1% 憲兵 ははは、馬鹿を言うな"
                      , "SPEAK %heishi1% 憲兵 市民証はこの国で\n生まれたのを証明するものだ"
                      , "SPEAK %heishi1% 憲兵 お前のような馬の骨に\nやるものではない"
                      , "SPEAK %avatar% もじょ 今までであった\nどんなヤツより態度\nでかいのだ"
                      , "LINES %avatar% まあまあ、役人ともめて\nもいいことないよ"
                      , "LINES %avatar% いこ"
                    ]
                }
              , "heishi2_speak": {
                    "trigger": "hero"
                  , "pos": [7,3], "rb": [9,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %heishi2%"
                      , "SPEAK %heishi2% 憲兵 私はマルティーニの憲兵だ"
                      , "SPEAK %heishi2% 憲兵 最近はレジスタンスの連中の\n動きが活発だからな"
                      , "SPEAK %heishi2% 憲兵 しかし、我がマルティーニに\nたてつく奴らは捕まえて\n流刑地に流してやるのだ"
                      , "SPEAK %heishi2% 憲兵 お前もレジスタンスと\n通じては・・"
                      , "SPEAK %heishi2% 憲兵 ・・・いなさそうだな\n能天気なツラをしている"
                      , "LINES %avatar% むっ！！\nしっつれいな・・"
                      , "SPEAK %heishi2% 憲兵 もしレジスタンスの情報を\n聞いたら即、役所へ言う事だ\n黙っていたら罪になるぞ"
                      , "SPEAK %heishi2% 憲兵 逆にレジスタンスの\n重要情報をタレこめば\nポイントも高いぞ"
                      , "SPEAK %heishi2% 憲兵 超重要情報なら\n5000ポイントの情報も\nあるくらいだ"
                      , "LINES %avatar% 5000・・って"
                      , "SPEAK %heishi2% 憲兵 まあ、普通のワーカーの\n5年分には相当するだろう"
                      , "SPEAK %heishi2% 憲兵 もし、とっ捕まえたなら\nまあ1万は下らんだろうな"
                      , "LINES %avatar% い、一万・・"
                      , "SPEAK %heishi2% 憲兵 みんな血眼になって\n捕まえようとしておるわ\nハハハ・・"
                      , "LINES %avatar% イ、インケーン・・"
                      , "SPEAK %heishi2% 憲兵 あとは亜人だな"
                      , "LINES %avatar% え？\nあ、亜人・・？"
                      , "SPEAK %heishi2% 憲兵 そうだ。亜人も我々人間の\n敵だからな"
                      , "SPEAK %heishi2% 憲兵 とっ捕まえれば報酬になる\n場合もあるぞ"
                      , "SPEAK %heishi2% 憲兵 特にエルフなどは\n高値がつく"
                      , "LINES %avatar% エ、エルフ・・"
                      , "LINES %avatar% なんてことを・・"
                      , "SPEAK %avatar% もじょ 亜人狩りが横行する\nわけなのだ"
                      , "SPEAK %heishi2% 憲兵 まあ、そういうわけだ"
                      , "SPEAK %heishi2% 憲兵 ところで貴様、何か\n身分証明できるものが\nあったら見せろ"
                      , "LINES %avatar% ・・・気分悪いんで失礼\nもじょ、いこ"
                      , "SPEAK %heishi2% 憲兵 あ、待て貴様！"
                    ]
                }
              , "meet_abeji": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [18,5], "rb": [19,9]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %man4% 市民 ケッ、お前なんかこの町\nからでていきやがれ！！"
                      , "LINES %avatar% んっ？なんだ？"
                      , "SPEAK %avatar% もじょ どうやらケンカしてる\nらしいのだ"
                      , "SPEAK %avatar% もじょ というより一人を大勢で\nよってたかって袋だたき\nにしている感じなのだ"
                      , "LINES %avatar% ちょ、とめないのかな\n道行く人たちはみんな\n知らんぷりしてるけど…"
                      , "SPEAK %avatar% もじょ 都会の人間は冷たいのだ…"
                      , "SPEAK %avatar% もじょ ま、ほっとくのだ。\nカンケーないのだ"
                      , "LINES %avatar% 何言ってるの！とめな\nきゃだめでしょ！"
                      , "LINES %avatar% こらー！やめなさい！"
                    ]
                  , "chain": "avatar_go"
                }
              , "avatar_go": {
                     "type": "avatar_go"
                   , "chain": "meet_abeji_end"
                }
              , "meet_abeji_end": {
                    "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man5%"
                      , "AALGN %avatar% %man4%"
                      , "SPEAK %man5% 市民 何だお前は？"
                      , "SPEAK %man4% 市民 俺たちはマルティーニの\n市民だぞ？"
                      , "LINES %avatar% そんなの関係\nないでしょ？"
                      , "LINES %avatar% だいたい大勢でよってたかって\n何よあんたたち！"
                      , "SPEAK %man4% 市民 よそものには関係ねえだろ！"
                      , "LINES %avatar% や、役人を呼んだわよ！"
                      , "SPEAK %man5% 市民 ふん、役人だって見てみぬふりさ。"
                      , "SPEAK %man5% 市民 もういいや、行こうぜ"
                    ]
                  , "chain": "man4_disappear"
                }
             , "man4_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "man4"
	               , "chain": "man5_disappear"
               }
             , "man5_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "man5"
	               , "chain": "meet_abeji_end2"
               }
              , "meet_abeji_end2": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 行っちゃったのだ…"
                      , "SPEAK %avatar% もじょ ああいうのは\nｿﾈｯﾄでｹｼｽﾞﾐに\nしてやればいいのだ"
                      , "LINES %avatar% そういうわけには\nいかないでしょ！"
                      , "LINES %avatar% 大丈夫？おじさん"
                      , "AALGN %avatar% %abeji%"
                      , "SPEAK %abeji% おじさん ああ、すまないね\n助かったよ"
                      , "SPEAK %abeji% おじさん 私はマルティーニの\n市民ではない、出稼ぎ労働者だ"
                      , "SPEAK %abeji% おじさん …って[NAME]じゃないか！！"
                      , "LINES %avatar% んんっ？\nア、アベジさん？"
                      , "SPEAK %abeji% アベジ 以前島に帰った時、会ったよね"
                      , "SPEAK %abeji% アベジ ひさしぶりだなあ\nおおきくなったね"
                      , "LINES %avatar% はい。ｼｼｮｰに様子見てきて\nくれって言われてて探してたんですよ"
                      , "LINES %avatar% でもなんであんな目に？"
                      , "SPEAK %abeji% アベジ マルティーニ市民は差別が\nひどいんだ\nよそものを憎んでるからね。"
                      , "SPEAK %abeji% アベジ こんなのはいつものことさ"
                      , "LINES %avatar% いつものことって…\nそうまでしてマルティーニに\nいなきゃダメなの？"
                      , "SPEAK %abeji% アベジ 仕事はすべてマルティーニに\n集まってくるからね\nしかたないのさ"
                      , "LINES %avatar% いいところで仕事するのも\n大変なんだな…"
                      , "SPEAK %abeji% アベジ ああ、でもどちらにしても\nもう故郷に帰らないと\nいけないんだけどね"
                      , "LINES %avatar% え？どうして"
                      , "SPEAK %abeji% アベジ あそこの山があるだろう"
                      , "LINES %avatar% あの半分くらい削れてるやつ？"
                      , "SPEAK %abeji% アベジ 私はあそこで働いてるんだ"
                      , "SPEAK %abeji% アベジ あそこからセメントを\n採掘してるんだよ"
                      , "LINES %avatar% セメント？"
                      , "SPEAK %abeji% アベジ ああ、それがコンクリートの\n元になってるんだよ"
                      , "LINES %avatar% へえ"
                      , "SPEAK %abeji% アベジ 私は裏山でセメント採掘の\n現場監督をやっているんだが…"
                      , "SPEAK %abeji% アベジ そこが最近奇妙な魔物に\n棲みつかれてしまって\n困ってるんだ…"
                      , "LINES %avatar% 魔物？"
                      , "SPEAK %abeji% アベジ 何か気持ち悪い火の玉なんだよ"
                      , "LINES %avatar% そんなの駆除すればよくない？"
                      , "LINES %avatar% マルティーニの国営なんでしょ？\nそれくらい…"
                      , "SPEAK %abeji% アベジ そうしたいところなんだけどね…"
                      , "SPEAK %abeji% アベジ その火の玉は下手に傷つけたまま\n取り逃がすと自爆するんだよ"
                      , "SPEAK %abeji% アベジ 一匹駆除しようとしたんだけど\n自爆されてね"
                      , "SPEAK %abeji% アベジ 工事現場もめちゃくちゃになって\n駆除隊もお手上げなんだ"
                      , "SPEAK %avatar% もじょ うわー…怖そうなのだ"
                      , "SPEAK %abeji% アベジ このままあの現場ごと廃棄して\n爆破してしまおうということに\nなってる"
                      , "SPEAK %abeji% アベジ そういうわけで、ワーカーは\nみな首になってしまうんだ"
                      , "LINES %avatar% ボクがやっつけてくるよ…"
                      , "SPEAK %abeji% アベジ ええ？ダメだよ。\n爆発に巻き込まれたら大変なことに\nなるよ"
                      , "LINES %avatar% 大丈夫だよ。\n師匠だってやっつけたんだから！\nまかせといて！"
                      , "SPEAK %abeji% アベジ ええ？\nあの親父に負けを認めさせる\nなんてすごいなあ…"
                      , "SPEAK %abeji% アベジ じゃあ、ひょっとしたら大丈夫\nかもしれないね"
                      , "LINES %avatar% うん！行ってきます！"
                    ]
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "abeji_speak_clear": {
                    "condition": {
                        "cleared":true ,
                        "31003_cleared":false 
                    }
                  , "trigger": "hero"
                  , "pos": [18,2], "rb": [19,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %abeji%"
                      , "SPEAK %abeji% アベジ よう[NAME]\n気を付けていくんだぞ"
                    ]
                }
              , "abeji_speak_clear2": {
                    "condition": {
                        "cleared":true ,
                        "31003_cleared":true ,
                        "!has_flag":310010001
                    }
                  , "trigger": "hero"
                  , "pos": [18,2], "rb": [19,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %abeji%"
                      , "SPEAK %abeji% アベジ よう[NAME]\nどうだった？"
                      , "LINES %avatar% 倒したよ。\n全部やっつけてきた"
                      , "SPEAK %abeji% アベジ おお、ありがとう\n君は本当に強いんだな"
                      , "LINES %avatar% えへへ…まあね"
                      , "SPEAK %abeji% アベジ どうやら、あの爆発する\nモンスターだがね…"
                      , "SPEAK %abeji% アベジ 出所がどうやら霊子力\n研究所だったらしい…"
                      , "LINES %avatar% 霊子力研究所？"
                      , "SPEAK %abeji% アベジ ああ、マルティーニの\n城下町の中にあるらしいんだがね・・"
                      , "LINES %avatar% へー…なんですか？\n霊子力研究所って？"
                      , "SPEAK %abeji% アベジ 私も詳しくは分からないけどね"
                      , "SPEAK %abeji% アベジ ｿﾈｯﾄの魔術の源は\n霊子っていうやつらしいんだけど、"
                      , "SPEAK %abeji% アベジ 霊子から核だけを\n取り出して利用する\n研究をしてるらしい"
                      , "LINES %avatar% そこからその霊子の核が漏れたってこと？"
                      , "SPEAK %abeji% アベジ そういうことだろう"
                      , "SPEAK %abeji% アベジ それで何をするのか\nさっぱりわからんけどね"
                      , "SPEAK %avatar% もじょ 霊子なんて亜人から\nでないと取れないのだ"
                      , "SPEAK %avatar% もじょ どうやって取ってるのだ？"
                      , "SPEAK %abeji% アベジ そりゃ、わからんけど\n当然まともな方法じゃないだろう"
                      , "SPEAK %abeji% アベジ 亜人狩りをやってるのも\nあそこで研究するためなんだろうね"
                      , "LINES %avatar% そうなんだ・・"
                      , "LINES %avatar% やっぱりマルティーニと\n亜人はずっと敵対してるんだね・・"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %abeji% アベジ ・・まあ、とにかく無事でよかったよ\nで、今回は一人で来たのかい？"
                      , "LINES %avatar% はい、師匠のところから離れて\n世界を見て回ってます"
                      , "SPEAK %abeji% アベジ どうだい？マルティーニは。\n島の方がいいだろう"
                      , "LINES %avatar% うん…。ごちゃごちゃしてて\n危険な所ですね"
                      , "SPEAK %abeji% アベジ まあ、大都会だからね。\nいろいろあるさ"
                      , "SPEAK %abeji% アベジ でも、君が産まれたところでも\nあるんだから感慨深いだろ？"
                      , "LINES %avatar% え？そうなの？"
                      , "SPEAK %abeji% アベジ ええ？知らなかったのかい？"
                      , "SPEAK %abeji% アベジ オヤジが13年前、マルティーニに\n来た時に君のお母さんから\n預かったんだよ？"
                      , "LINES %avatar% え？じゃあお母さんが\nマルティーニに居るの？"
                      , "SPEAK %abeji% アベジ いや、君の母さんは\nレジスタンスのスパイ容疑を\nかけられて"
                      , "SPEAK %abeji% アベジ マルティーニ政府から\n追われてたらしい"
                      , "SPEAK %abeji% アベジ 小さかった君をオヤジに\n預けてそのあとすぐに…"
                      , "LINES %avatar% つ、捕まっちゃったの？"
                      , "SPEAK %abeji% アベジ いや、捕まる前に海に\n身を投げたらしくてね…"
                      , "LINES %avatar% …………"
                      , "SPEAK %abeji% アベジ オヤジは島の連絡船に乗って\nすぐに故郷の島に帰ったから君は\n捕まらなかったんだよ"
                      , "LINES %avatar% そうなんだ…"
                      , "SPEAK %abeji% アベジ お母さんについてはもっと詳しく\n知りたいなら君が産まれた\nランカスタの村に行ってみるといい"
                      , "SPEAK %abeji% アベジ 歩いて行くには遠すぎるだろう。\n汽車でいくといい"
                      , "LINES %avatar% え？汽車ってなあに？"
                      , "SPEAK %abeji% アベジ しらないのかい？ここからあっちに\n歩いていくとポートモール駅が\n見えるから…"
                      , "LINES %avatar% うん、ありがとう"
                      , "LINES %avatar% 気をつけてな！"
                    ]
                  , "chain": "goal2"
                }
              , "goal2": {
                    "type": "escape"
                  , "one_shot": 310010001
                  , "escape_result": "success"
                }
              , "abeji_speak_clear3": {
                    "condition": {
                        "cleared":true ,
                        "31003_cleared":true ,
                        "has_flag":310010001
                    }
                  , "trigger": "hero"
                  , "pos": [18,2], "rb": [19,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %abeji%"
                      , "SPEAK %abeji% アベジ よう[NAME]\nこの間はありがとうな"
                      , "SPEAK %abeji% アベジ あのときの赤ん坊が大きくなったなあ\n私は出稼ぎで10年前こっちに\n移ってきたから"
                      , "SPEAK %abeji% アベジ 前に島に帰ったのは2,3年前だから\n久しぶりだなぁ"
                      , "SPEAK %abeji% アベジ そういえばポボタは元気か？"
                      , "LINES %avatar% うん、ボポタさんは元気だよ"
                      , "SPEAK %avatar% もじょ マリーとモメテタけど\n今は仲良くやってるのだ"
                      , "SPEAK %abeji% アベジ そうか、なんか嫁と仲よくないって\n話はオヤジから聞いてたから…\nほっとしたよ"
                      , "SPEAK %avatar% もじょ アレは触らぬ神にたたりなし、なのだ・・"
                      , "SPEAK %abeji% アベジ ・・まあ、よかったよ"
                    ]
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [19,13]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [4,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman2"
                  , "icon":"woman"
                  , "pos": [7,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [7,13]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [11,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man3"
                  , "icon":"man"
                  , "pos": [1,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "heishi1"
                  , "icon":"servant"
                  , "pos": [5,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "heishi2"
                  , "icon":"servant"
                  , "pos": [8,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9906
                  , "code": "man4"
                  , "align": 3
                  , "icon":"man"
                  , "pos": [18,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9906
                  , "align": 3
                  , "code": "man5"
                  , "icon":"man"
                  , "pos": [19,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "abeji"
                  , "icon":"uncle"
                  , "pos": [19,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ]
        }
    }
}
