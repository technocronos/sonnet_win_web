{
    "extra_uniticons": ["man", "woman", "shisyou", "boy", "uncle"]
   ,"rooms": {
        "start": {
            "id": 98020000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [2, 9]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% ついたー・・。ここがアトランティスの街？"
                      , "SPEAK %avatar% もじょ そうみたいなのだ"
                      , "LINES %avatar% 何かシショーには悪いけどそんな金銀財宝はなさそうな・・"
                      , "SPEAK %avatar% もじょ 単なる寂れた村なのだ・・"
                      , "LINES %avatar% うーん・・。ま、とりあえずシャクワさんとこ行こう"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [7,9], "rb": [8,10]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man2%"
                      , "LINES %man2% ここはアトランティスの村だよ"
                      , "LINES %man2% もう1万年以上浮いては沈んでを繰り返しているんだ"
                      , "LINES %man2% だけどここじゃまだ２，３年しか時が過ぎてないんだ"
                      , "LINES %man2% どんどん世界から取り残されていくだけだ・・"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [5,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                       ,"LINES %woman1% あら、めずらしいわね。"
                       ,"LINES %woman1% どうやってここを見つけたの？"
                      , "LINES %woman1% なんだか最近結界に穴が空くことが多いのよね"
                      , "LINES %woman1% 呪いの力が弱まっているのかしら・・"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [6,7], "rb": [7,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% え？外では人間が亜人を支配してるって？"
                      , "LINES %man1% 300年前なら俺たちからしたらつい先々週くらいの話だな"
                    ]
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [5,11], "rb": [7,12]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "LINES %old1% ワシもいつかは死んで墓に入るんじゃのう"
                      , "LINES %old1% 嫌じゃ・・1分1秒でも長く生きたい・・"
                      , "LINES %old1% いつかは不老不死の薬が見つかるかもしれん"
                      , "LINES %old1% この呪いを解くなどとんでもない！"
                    ]
                }
              , "shakuwa_speak": {
                    "trigger": "hero"
                  , "pos": [2,0], "rb": [4,1]
                  , "type": "lead"
                  , "leads": [
                        "LINES %shakuwa% おお、[NAME]よく来てくれた。"
                      , "LINES %shakuwa% さあ、家に案内するから来てくれ。"
                    ]
                   , "chain": "tresD"
                }
              , "tresD": {
                    "trigger":"hero"
                  , "pos":[10, 1]
                  , "type": "drama"
                  , "drama_id": 9802001
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man2"
                  , "pos": [8,9]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "man1"
                  , "align": 1
                  , "pos": [7,7]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "woman1"
                  , "align": 2
                  , "pos": [4,4]
                  , "icon":"woman"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "old1"
                  , "pos": [6,12]
                  , "icon":"shisyou"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "shakuwa"
                  , "pos": [3,0]
                  , "icon":"uncle"
                  , "align": 2
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
