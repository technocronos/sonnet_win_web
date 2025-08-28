{
    "extra_uniticons": ["elena", "noel"]
  , "rooms": {
        "start": {
            "id": 21012000
          , "battle_bg": "forest"
          , "environment": "snow"
          , "bgm": "bgm_home"
          , "start_pos": [5, 6]
          , "gimmicks": {
              "open_comment": {
                  "trigger": "rotation"
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "UALGN %elena% 3"
                    , "LINES %elena% ようこそ、ここがエルフの里です\n私の家に案内するね"
                  ]
	               , "chain": "noel_appear"
              }
            , "noel_appear": {
                "type": "unit"
              , "unit": {
                      "pos": [4, 3]
                    , "character_id":-20104
                    , "icon":"noel"
                    , "code": "noel"
                    , "union": 1
                    , "act_brain": "rest"
                    , "brain_noattack": true
                 }
               , "chain": "noel_speak"
              }
            , "noel_speak": {
                  "type": "lead"
                , "leads": [
                      "LINES %elena% ただいま、ノエル兄さん\nこの人間は[NAME]\n私を助けてくれたの"
                    , "UMOVE %noel% 88"
                    , "LINES %noel% エ、エレナ！どこに行ってたんだ？ひどい傷じゃないか！"
                    , "LINES %noel% 人間にはあれほど注意しろって言ったのに人間なんか連れてきて…"
                    , "LINES %elena% [NAME]はそんな人間じゃないわ！私を助けてくれたのよ！"
                    , "LINES %noel% 人間なんて皆同じだ！これまでどんなひどい目にあってきたか分かってるのか？"
                    , "SPEAK %avatar% もじょ （どうやらそんなに歓迎されてるわけではないらしいのだ…）"
                    , "LINES %noel% 君には礼を言わなければならないな"
                    , "LINES %noel% しかし、私は人間に恨みを持っている。この里の所在も絶対に知られてはならないんだ"
                    , "LINES %noel% ここに来たことは忘れてすまないがこのまま帰って欲しい"
                    , "LINES %avatar% うん…わかったよ"
                    , "LINES %elena% まって、[NAME]！！"
                  ]
               , "chain": "goto_next"
              }
            , "goto_next": {
                    "type": "goto"
                  , "room": "floor1"
                }
            }
          , "units": [
                {
                    "character_id": -20101
                  , "icon":"elena"
                  , "pos": [4,6]
                  , "code": "elena"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor1": {
            "id": 21010000
          , "battle_bg": "forest"
          , "bgm": "bgm_home"
          , "start_pos": [5,1]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %elena%"
                      , "LINES %elena% ごめんね、お兄さんは悪い人じゃないんだけど…"
                      , "LINES %elena% 人間のエルフ狩りで父さん、母さんを殺されてるから人間を憎んでるの"
                      , "LINES %avatar% でもエルフってすごい能力を持ってるんでしょ？"
                      , "LINES %elena% 私たちは人間を殺すと呪いがかかって血に飢えたダークエルフになってしまうから…"
                      , "LINES %avatar% そうか…"
                      , "LINES %elena% マルティーニが亜人を制圧して以来、面白半分でエルフを見つけ出して連れ去ったり殺したり…"
                      , "LINES %elena% でも[NAME]は違うわ。なんとなくわかるもの"
                      , "LINES %avatar% うん、ありがとう・・。でもやっぱり早目に里を離れるよ"
                      , "LINES %avatar% エレナ、ともだちになってくれてありがとう"
                      , "LINES %elena% うん、あそこが出口だから。またいつでも来てね"
                    ]
                  , "chain": "elena_disappear"
                }
              , "elena_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "elena"
	                , "chain": "last_speak"
                }
              , "last_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし。じゃ、マルティーニに行きますか！"
                    ]
                 , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -20101
                  , "icon":"elena"
                  , "pos": [6,1]
                  , "code": "elena"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
