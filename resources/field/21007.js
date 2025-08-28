{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 21007000
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [8,13]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% エレナ ここがコバイヤ族の集落よ。"
                      , "LINES %avatar% あの火山すごいね。"
                      , "SPEAK %avatar% もじょ なんか煙がでてるのだ。"
                      , "SPEAK %avatar% エレナ そうね。まだ活火山だから。"
                      , "LINES %avatar% いつ噴火してもおかしくないね。"
                      , "SPEAK %avatar% エレナ 大丈夫よ。あそこにはサラマンダーがいるから"
                      , "SPEAK %avatar% もじょ 火の精なのだ？"
                      , "SPEAK %avatar% エレナ そうね。サラマンダーが火山エネルギーを栄養源にしちゃうから噴火しないのよ。"
                      , "LINES %avatar% へー。よくできてるね。"
                      , "SPEAK %avatar% もじょ 村人に聞き取り調査なのだ"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [3,10], "rb": [5,11]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% ここはあの火山でとれる夢の実を加工しているんだ。"
                      , "LINES %man1% 夢の実はコバイヤの一番の産業だからな。"
                      , "LINES %man1% 何にどう使われるかなんざ俺たちの知ったことじゃないがな。"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [10,10], "rb": [12,12]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man2%"
                      , "LINES %man2% ここはコバイヤの長老の家だよ。"
                      , "LINES %man2% 聞きたいことがあるなら聞いていくといい。"
                      , "LINES %man2% 長老の決定は絶対だからな！"
                    ]
                }
              , "old1_speak": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [14,10], "rb": [16,11]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "SPEAK %old1% 長老 ・・・なるほどのう。たしかにトロルの住んでいた所ならあの火山の向こうじゃな。"
                      , "SPEAK %old1% 長老 しかし火山はわしらの聖地じゃ。"
                      , "SPEAK %old1% 長老 だれであろうと通すわけにはいかん"
                      , "LINES %avatar% そんなぁ・・"
                      , "SPEAK %old1% 長老 さあ、さっさと帰るがよい。"
                      , "SPEAK %avatar% エレナ 長老、そこをなんとか・・んっ！？"
                      , "VIBRA 01"
                      , "SPEAK %avatar% もじょ な・・なんかゆれてるのだ・・"
                      , "VIBRA 02"
                      , "PFOCS 08 00"
                      , "DELAY 500"
                      , "EFFEC migt 0800"
                      , "DELAY 500"
                      , "EFFEC migt 0800"
                      , "DELAY 100"
                      , "EFFEC migt 0800"
                      , "EFFEC migt 0800"
                      , "LINES %avatar% な、なに！？"
                      , "SPEAK %old1% 長老 ま、まさか・・。火山の爆発・・。"
                      , "SPEAK %avatar% エレナ すごい煙が火山から・・。"
                      , "SPEAK %avatar% エレナ 長老！これは一体・・？"
                      , "SPEAK %old1% 長老 わ、わからん。はじめてのことじゃ・・。"
                      , "SPEAK %old1% 長老 まさか、サラマンダーに何かあったのか・・？"
                      , "LINES %avatar% ボクたちが火山に行って見てきます！"
                      , "LINES %avatar% このままじゃ亜人の棲家ごと全滅しちゃう。"
                      , "SPEAK %old1% 長老 わしらもサラマンダーが活動していないときに火山口に行って夢の実を取ってくるくらいじゃからの。"
                      , "SPEAK %old1% 長老 こうなってしまっては近づく術もない・・。"
                      , "SPEAK %old1% 長老 あいわかった。ならば火山に行くがよい。"
                      , "SPEAK %old1% 長老 ただし、サラマンダーの怒りに触れぬよう気をつけるがいい。"
                      , "LINES %avatar% はい！"
                      , "VIBRA 00"
                    ]
                  , "chain" : "goal"
                }
              , "old1_speak2": {
                     "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [14,10], "rb": [16,11]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "SPEAK %old1% 長老 トロルの住んでいた所ならあの火山の向こうじゃよ。"
                    ]
                }
              , "old2_speak": {
                    "trigger": "hero"
                  , "pos": [0,11], "rb": [1,12]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old2%"
                      , "SPEAK %old2% 老人 あの火山に行きたいじゃと？"
                      , "SPEAK %old2% 老人 それはあきらめい。"
                      , "SPEAK %old2% 老人 夢の実というのがあの火山口でとれるのじゃ。"
                      , "SPEAK %old2% 老人 しかしそれをめぐって争いがあるからの・・。"
                      , "SPEAK %old2% 老人 あそこには他の人間は足を踏み入れさせない決まりなのじゃ"
                    ]
                  , "chain" : "inner_speak"
                }
              , "inner_speak": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 夢の実って何？なんかおいしそうだね。"
                      , "SPEAK %avatar% もじょ ナーンも知らないのだ。"
                      , "LINES %avatar% 知ってるの？もじょ。"
                      , "SPEAK %avatar% もじょ 夢の実は名前は可愛らしい感じだけど危険なものなのだ。"
                      , "SPEAK %avatar% エレナ 他人の夢の中に入れると言われてるけど。"
                      , "SPEAK %avatar% エレナ 悪用すると他人の夢を覗けるし。"
                      , "SPEAK %avatar% エレナ 下手すると精神汚染を引き起こすわね。"
                      , "LINES %avatar% なんか怖いね・・。"
                      , "SPEAK %avatar% もじょ 夢の実目当てじゃないから通して欲しいとお願いするしかないのだ。"
                      , "SPEAK %avatar% エレナ 人間と繋がって幻覚剤として売ったりしてたこともあるようで"
                      , "SPEAK %avatar% エレナ コバイヤはあまり亜人の間でも人間の間でも評判よくないのよ。"
                      , "LINES %avatar% ・・・"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [5,11], "rb": [7,12]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                      , "LINES %woman1% ここはコバイヤ族の集落よ。"
                      , "LINES %woman1% あの火山はコバイヤの聖地よ。"
                    ]
                }
              , "woman2_speak": {
                    "trigger": "hero"
                  , "pos": [7,9], "rb": [9,11]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman2%"
                      , "LINES %woman2% あら？お客さん？"
                      , "LINES %woman2% 人間なんかもたまに訪ねてきたりするけどこんな村に何の用なのかしら。"
                    ]
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [4,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [11,11]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [15,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old2"
                  , "icon":"shisyou"
                  , "align": 2
                  , "pos": [0,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [6,11]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman2"
                  , "icon":"woman"
                  , "pos": [8,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
