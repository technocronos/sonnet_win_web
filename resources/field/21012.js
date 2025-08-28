{
    "extra_uniticons": ["shadow", "shadow2", "elena", "noel"]
  , "rooms": {
        "start": {
            "id": 21012000
          , "battle_bg": "dungeon"
          , "environment": "snow"
          , "bgm": "bgm_home"
          , "start_pos": [10,7]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"!has_memory":"start_pass"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "memory_on": "start_pass"
                  , "leads": [
                        "LINES %avatar% さー着いた"
                      , "SPEAK %avatar% もじょ 今度はまっすぐにこれたのだ"
                      , "LINES %elena% ありがとう。書き置きはしてきたけど、兄さんも心配してるだろうから・・"
                      , "LINES %avatar% そうだね・・心配かけちゃったな。"
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
                      , "act_brain": "rest"
                      , "brain_noattack": true
	                 }
	               , "chain": "noel_speak"
	             }
            , "noel_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %noel% エレナ！エレナ！"
                      , "UMOVE %noel% 8886666"
                      , "UALGN %avatar% 1"
                      , "UALGN %elena% 1"
                      , "LINES %noel% どうしてたんだ？心配してたぞ・・"
                      , "LINES %elena% ごめん兄さん、こういうわけで・・"
                      , "LINES %noel% なんと！そうか、君はトロル族の生き残りだったのか・・"
                      , "LINES %avatar% うん。どうやらそうみたいで・・"
                      , "LINES %avatar% エレナを連れまわしちゃってごめんなさい"
                      , "LINES %noel% いや、足手まといになってないならよかったよ"
                      , "SPEAK %avatar% もじょ 方向音痴だけはどうにかならんのだ・・？"
                      , "LINES %elena% もじょちゃん。ご・め・ん・ね？"
                      , "LINES %avatar% もじょ！"
                      , "LINES %noel% ハハハ・・それは昔からだ"
                      , "LINES %noel% それはそれとして、同じ亜人である以上、僕らは仲間だ"
                      , "LINES %noel% また何かあったらエレナに会いにきてやってくれ"
                      , "LINES %elena% 絶対よ。約束だよ。"
                      , "LINES %avatar% うん。必ずまた来るから。"
                      , "LINES %avatar% じゃ、このへんでボクらは行くよ"
                      , "LINES %elena% うん。またね。"
                      , "LINES %noel% ・・・。"
                      , "LINES %noel% 誰かいるな・・？"
                      , "LINES %noel% 誰だ！！！"
                      , "UALGN %avatar% 0"
                      , "UALGN %elena% 0"
                      , "UALGN %noel% 0"
                    ]
	               , "chain": "enemy1.1"
                }

	          , "enemy1.1": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [9, 9]
                     , "align": 3
	                   , "character_id":-10054
	                   , "icon":"shadow2"
                     , "code": "boss"
	                 }
	               , "chain": "enemy1.2"
	             }
	          , "enemy1.2": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [5, 10]
	                   , "character_id":-10052
                     , "align": 3
	                   , "icon":"shadow"
                     , "code": "zako1"
	                 }
	               , "chain": "enemy1.3"
	             }
	          , "enemy1.3": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [6, 10]
	                   , "character_id":-10052
                     , "align": 3
	                   , "icon":"shadow"
                     , "code": "zako2"
	                 }
	               , "chain": "enemy1.4"
	             }
	          , "enemy1.4": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [7, 10]
	                   , "character_id":-10052
	                   , "icon":"shadow"
                     , "align": 3
                     , "code": "zako3"
	                 }
	               , "chain": "enemy1.5"
	             }
	          , "enemy1.5": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [8, 10]
	                   , "character_id":-10052
	                   , "icon":"shadow"
                     , "align": 3
                     , "code": "zako4"
	                 }
	               , "chain": "enemy1.6"
	             }
	          , "enemy1.6": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [10, 10]
	                   , "character_id":-10052
                     , "align": 3
                     , "code": "zako5"
	                   , "icon":"shadow"
	                 }
	               , "chain": "enemy1.7"
	             }
	          , "enemy1.7": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [11, 10]
	                   , "character_id":-10052
                     , "align": 3
	                   , "icon":"shadow"
                     , "code": "zako6"
	                 }
	               , "chain": "enemy1.8"
	             }
	          , "enemy1.8": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [12, 10]
	                   , "character_id":-10052
                     , "align": 3
	                   , "icon":"shadow"
                     , "code": "zako7"
	                 }
	               , "chain": "enemy1.9"
	             }
	          , "enemy1.9": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [13, 10]
	                   , "character_id":-10052
                     , "align": 3
	                   , "icon":"shadow"
                     , "code": "zako8"
	                 }
	               , "chain": "boss_speak1"
	             }
            , "boss_speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %boss% ようやく見つけたぞ。ここがエルフの里だな？"
                      , "LINES %boss% 長い間探したが二人もいるではないか"
                      , "LINES %noel% 誰だ、貴様らは！"
                      , "LINES %boss% われわれはマルティーニの亜人狩り部隊だ"
                      , "LINES %boss% そのエルフの二人を連行する。邪魔をするな"
                      , "LINES %noel% 貴様ら・・。ここを出ていけ！さもなくば・・"
                      , "LINES %boss% さもなくばなんだ？貴様らエルフは人間を殺したら黒化して呪われた身になるのだろう？"
                      , "LINES %noel% くっ・・"
                      , "LINES %elena% に・・兄さん・・"
                      , "LINES %boss% フフフ。おとなしくするがよい"
                      , "LINES %noel% エレナ、ここは逃げるぞ"
                      , "LINES %elena% う・・うん"
                    ]
	               , "chain": "noel_disappear"
                }
             , "noel_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "noel"
	               , "chain": "elena_disappear"
               }
             , "elena_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "elena"
	               , "chain": "boss_speak2"
               }
            , "boss_speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %boss% まてい！必ず追っていくぞ！"
                      , "LINES %zako4% 隊長・・亜人レーダーによると奴らは北の方の洞窟に逃げたようです。"
                      , "LINES %boss% ククク・・文明の力をみくびったな"
                      , "LINES %boss% やつらの行動は筒抜けだ。もはや捕獲は目の前だ"
                      , "LINES %boss% 行くぞ！！！"
                    ]
	               , "chain": "boss_disappear"
                }
             , "boss_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "boss"
	               , "chain": "zako1_disappear"
               }
             , "zako1_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako1"
	               , "chain": "zako2_disappear"
               }
             , "zako2_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako2"
	               , "chain": "zako3_disappear"
               }
             , "zako3_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako3"
	               , "chain": "zako4_disappear"
               }
             , "zako4_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako4"
	               , "chain": "zako5_disappear"
               }
             , "zako5_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako5"
	               , "chain": "zako6_disappear"
               }
             , "zako6_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako6"
	               , "chain": "zako7_disappear"
               }
             , "zako7_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako7"
	               , "chain": "zako8_disappear"
               }
             , "zako8_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako8"
	               , "chain": "end_speak"
               }
            , "end_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 大変だ・・。場所がバレてる・・。"
                      , "LINES %avatar% あいつらより先にエレナたちを探して追いつかないと！"
                      , "SPEAK %avatar% もじょ 北の洞窟に行くのだ"
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
                  , "pos": [9,7]
                  , "code": "elena"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
