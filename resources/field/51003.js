{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 51003000
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [0,7]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ここがヤズドの村？"
                      , "SPEAK %avatar% もじょ どうやらそうっぽいのだ"
                      , "LINES %avatar% レジスタンスの人はどこにいるのかなぁ"
                      , "SPEAK %avatar% もじょ 探してみるのだ"
                    ]
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [5,5], "rb": [7,6]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "LINES %old1% ここはヤズドの村じゃよ"
                      , "LINES %old1% この村はこの島に昔からある。伝統ある村じゃよ。"
                      , "LINES %avatar% へー。囚人しか住んでないのかと思った。"
                      , "LINES %old1% マルティーニがここを勝手に囚人の捨て場にしたんじゃ。"
                      , "SPEAK %avatar% もじょ そりゃ迷惑な話なのだ"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [7,8], "rb": [9,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                      , "LINES %woman1% いらっしゃい。ここはヤズドの村よ"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [13,5], "rb": [15,6]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% こんな村に訪ねてくるなんてめずらしいな。別に何もないぜ。"
                      , "LINES %man1% そういやちょっと前にも来た奴がいたな。"
                      , "LINES %man1% 向かいの家に住んで何か調べてるみたいだな。"
                      , "LINES %avatar% あ・・その人かなぁ・・"
                      , "LINES %man1% 最近様子がおかしいようだけどな。"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [5,9], "rb": [7,10]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man2%"
                      , "LINES %man2% ここからさらに西に行くと廃棄物処理場があるよ"
                      , "LINES %man2% マルティーニが罪人と一緒に産業廃棄物も捨てに来るんだ"
                      , "LINES %man2% おかげでこの村も衛生環境が悪くなったもんだ"
                    ]
                }
              , "man3_speak": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [13,9], "rb": [15,10]
                  , "memory_on": "man_sleep"
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man3%"
                      , "SPEAK %avatar% レイラ あっ、この人よ"
                      , "LINES %avatar% えっ？この人？"
                      , "SPEAK %avatar% もじょ 様子がおかしいのだ・・"
                      , "LINES %man3% あいつだ・・またあいつがやってくる・・殺人鬼だ・・！！"
                      , "LINES %man3% もう何日も寝ていない。眠れないんだ・・夢を見るたびにどんどん俺のほうに近づいてくる・・"
                      , "LINES %man3% 血みどろのナタを持った恐ろしい。マスクをかぶったやつだ・・"
                      , "LINES %man3% 前の夢であいつはドアの向こうまで来ていた・・"
                      , "LINES %man3% 次こそ殺されてしまう・・助けてくれ・・たのむ！！"
                      , "SPEAK %avatar% もじょ これは多分、夢魔の仕業なのだ。"
                      , "LINES %avatar% あっ、波止場で言ってたあれ？"
                      , "SPEAK %avatar% もじょ みんな最後はおかしくなって死んでいくって言ってたのだ"
                      , "LINES %avatar% そんな・・。どうすればいいの？"
                      , "SPEAK %avatar% レイラ 一つだけ方法を聞いたことがあるわ"
                      , "SPEAK %avatar% レイラ 夢の実という他人の夢の中に潜入できるアイテムがあるとか・・"
                      , "SPEAK %avatar% レイラ これは悪用すれば精神汚染を引き起こすことができる危険なものなんだけど"
                      , "SPEAK %avatar% レイラ しかし夢魔の精神汚染に対抗するとしたらそれしかないわ"
                      , "SPEAK %avatar% もじょ でも、そんなのめったに手に入らないのだ"
                      , "LINES %avatar% えっと・・。これのこと？"
                      , "SPEAK %avatar% レイラ あ、あなた・・それどうしたの？？？？"
                      , "LINES %avatar% いや・・コバイヤ火山に行ったときにちょっと失敬してきて・・"
                      , "SPEAK %avatar% もじょ もじょも気づかなかったのだ・・抜け目ないのだ・・"
                      , "LINES %man3% おお、それではその夢の実で俺の夢の中に入って殺人鬼をやっつけてくれるんだな！"
                      , "LINES %avatar% うん。"
                      , "LINES %man3% ありがとうよ。ではこれから俺は眠ることにする。たのんだ・・"
                      , "LINES %man3% zzz.."
                      , "SPEAK %avatar% もじょ さっそく眠ってしまったようなのだ・・"
                      , "LINES %avatar% じゃ、ボクも夢の実を飲んで夢に潜入してきます！"
                      , "SPEAK %avatar% レイラ 夢の中だと私は行けないわね・・。頼んだわよ。気をつけてね！"
                      , "LINES %avatar% あーん・・。ポリポリポリ・・"
                      , "LINES %avatar% あー・・眠くなってきた・・"
                      , "SPEAK %avatar% もじょ もじょもちょっともらうのだ。"
                      , "SPEAK %avatar% もじょ ポリポリポリなのだ・・"
                      , "LINES %avatar% お・・おやふみ～・・"
                      , "LINES %avatar% zzz.."
                    ]
                  , "chain": "goal"
                }
              , "man3_speak2": {
                    "condition": {"muma_cleared":false}
                  , "ignition": {"!has_memory":"man_sleep"}
                  , "trigger": "hero"
                  , "pos": [13,9], "rb": [15,10]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man3%"
                      , "LINES %man3% うーん・・うーん・・"
                    ]
                }
              , "man3_speak3": {
                    "condition": {"muma_cleared":true}
                  , "trigger": "hero"
                  , "pos": [13,9], "rb": [15,10]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man3%"
                      , "LINES %man3% やあ[NAME]。こないだはありがとうな！"
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
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [6,5]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [14,5]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [6,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9907
                  , "code": "man3"
                  , "icon":"man"
                  , "pos": [14,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [8,8]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
