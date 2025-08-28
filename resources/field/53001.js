{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 53001000
          , "bgm": "bgm_home"
          , "environment": "grass"
          , "battle_bg": "dungeon"
          , "start_pos": [0,4]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわぁ・・"
                      , "LINES %avatar% ここが廃棄物処理場か・・"
                      , "SPEAK %avatar% もじょ めっちゃくっさいのだ・・（涙）"
                      , "LINES %avatar% さびしいとこだなぁ・・"
                      , "SPEAK %avatar% もじょ 一応人がいるのだ"
                    ]
                }
              , "open_comment2": {
                    "condition": {"reishi_cleared":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし。こないだの人にもっかい話を聞こう"
                   ]
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [2,3], "rb": [3,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "LINES %old1% ここは廃棄物処理場じゃ。"
                      , "LINES %old1% 全世界の産業廃棄物、核廃棄物、生き物まで何でも捨てられてる場所じゃ。"
                    ]
                }
              , "old2_speak": {
                    "trigger": "hero"
                  , "pos": [4,6], "rb": [6,7]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old2%"
                      , "LINES %old2% 人、モノ、思い出・・すべての不要なものを捨てる場所・・"
                      , "LINES %old2% ここそういうところなのじゃ・・"
                      , "LINES %old2% だが、あらゆる法の目の届かない場所でもある。"
                      , "LINES %old2% 探し物があるんならでてくるかもねぇ・・"
                    ]
                }
              , "old3_speak": {
                    "trigger": "hero"
                  , "pos": [5,0], "rb": [6,1]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old3%"
                      , "LINES %old3% ここには物乞いしかいないよ・・"
                      , "LINES %old3% お願いだ・・食べ物を・・めぐんでくだせえ・・"
                    ]
                }
              , "man1_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [10,3], "rb": [11,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% ・・君は・・[NAME]か？私はレジスタンスのものだ。"
                      , "LINES %man1% 抜け道がこの流刑地のどこかにあると噂されているのだが"
                      , "LINES %man1% なにしろこの広さだから皆目見当がつかないんだ。"
                      , "LINES %man1% まあ、俺のカンだがここはくさいと思ってる。ここには何かある・・！"
                      , "SPEAK %avatar% もじょ そりゃここはくさいのだ"
                      , "LINES %avatar% もじょ、そういう意味じゃないよ・・"
                    ]
                }
              , "man1_speak2": {
                    "condition": {"reishi_cleared":true}
                  , "trigger": "hero"
                  , "pos": [10,3], "rb": [11,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% やあ、[NAME]。よく来てくれた。"
                      , "LINES %man1% 話は聞いた。本部は壊滅したらしいな。"
                      , "LINES %man1% 霊子力研究所が停止してマルティーニは大騒ぎらしい。"
                      , "LINES %man1% 捕まっていた亜人たちもどさくさで相当逃げたらしい。"
                      , "LINES %avatar% それは・・よかったです・・"
                      , "LINES %avatar% でも、ゲバルさんもみんな死んじゃった・・"
                      , "LINES %man1% ああ・・それだがね・・"
                      , "LINES %man1% マルティーニから明日、捕えたレジスタンスの公開処刑をやると発表があったんだ"
                      , "LINES %avatar% えっ？？ってことは・・生きてるのかな？？"
                      , "LINES %man1% 分からないが、これは明らかに罠だな。"
                      , "LINES %man1% 残るレジスタンスがリーダーを助けに来るだろうと踏んでね。"
                      , "LINES %man1% ・・つまり[NAME]。君をおびき出すのが狙いだよ。"
                      , "SPEAK %avatar% もじょ まあ、十中八九そうなのだ。"
                      , "LINES %avatar% ・・生きてる・・。ゲバルさんが生きてるかも・・！"
                      , "LINES %man1% 我々としても何とかみんな助けたいと思ってるんだ。"
                      , "LINES %man1% そして我々はとうとうマルティーニの抜け道を見つけたんだ！"
                      , "LINES %man1% 頼む。みんなを助けてほしい・・。"
                      , "LINES %avatar% もちろんそのつもり！"
                      , "SPEAK %avatar% もじょ なんか元気出てきたのだ。"
                      , "LINES %avatar% 抜け道からマルティーニまで乗り込んでみんなを助けるよ"
                      , "LINES %man1% 時間も無い。頼んだよ！"
                    ]
                  , "chain" : "goal"
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
                  , "pos": [3,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old2"
                  , "icon":"shisyou"
                  , "pos": [5,6]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old3"
                  , "icon":"shisyou"
                  , "pos": [5,1]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9907
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [10,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
