{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "man", "layla"]
  , "extra_maptips": [1205]
  , "rooms": {
        "start": {
            "id": 51004000
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [6,3]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ ここが夢の中なのだ・・？"
                      , "LINES %avatar% そうなのかな？"
                      , "LINES %man% ここは・・夢の中だ・・"
                      , "LINES %man% 間違いない・・"
                      , "SPEAK %avatar% もじょ 悪趣味にもほどがあるのだ"
                      , "LINES %avatar% これからどうすればいいのかなぁ・・"
                      , "LINES %avatar% この夢自体を作ってる夢魔をやっつけないといけないんだけど"
                      , "SPEAK %avatar% もじょ どこにいるのだ・・？"
                      , "LINES %man% ん？なんだこの黒い壁は・・"
                      , "UMOVE %man% 22"
                      , "SPEAK %avatar% もじょ おーい。かってにうろうろしたら・・"
                      , "LINES %man% ん～？"
                      , "DELAY 1500"
                    ]
                  , "chain": "horrorwall"
                }
              , "horrorwall": {
                    "type": "square_change"
                  , "change_pos": [5,0]
                  , "change_tip": 1205
                  , "chain": "mansurprise"
                }
              , "mansurprise": {
                    "type": "lead"
                  , "leads": [
                        "SEPLY se_scream" 
                      , "DELAY 1000"
                      , "LINES %man% うわああああ！！！！！"
                      , "UMOVE %man% 8"
                      , "LINES %man% で！"
                      , "UMOVE %man% 8"
                      , "LINES %man% た！"
                      , "UMOVE %man% 8"
                      , "LINES %man% あああああ！！！！"
                      , "UMOVE %man% 8"
                    ]
	               , "chain": "man_disappear"
                }
             , "man_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "man"
	               , "chain": "speak2"
               }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ いわんこっちゃないのだ"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %avatar% もじょ どうしたのだ？"
                      , "LINES %avatar% な・・なんでもない・・ははは・・"
                      , "SPEAK %avatar% もじょ 怖かったのだ・・？"
                      , "LINES %avatar% ははは・・ははは・・んなわきゃ"
                      , "SPEAK %avatar% もじょ ならさっさとあの男を追いかけるのだ"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [5,5]
                  , "type": "goto"
                  , "room": "floor1"
                }
            }
          , "units": [
                {
                    "character_id": -9907
                  , "code": "man"
                  , "icon":"man"
                  , "pos": [5,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor1": {
            "id": 51004001
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [6,0]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ストマックの間"
                      , "LINES %avatar% うわ・・なんだここ"
                      , "SPEAK %avatar% もじょ 胃みたいな形してるのだ"
                    ]
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[6,1], "rb":[8,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 8]
                      , "character_id":-10120
                      , "icon":"shadow"
                      , "code": "enemy1"
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 8]
                      , "character_id":-10120
                      , "icon":"shadow"
                      , "code": "enemy2"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %enemy1% ギ・・ギ・・"
                      , "LINES %enemy2% アッアッアッアッアッ・・・"
                      , "LINES %avatar% ヒィィ・・ばけもの・・"
                      , "SPEAK %avatar% もじょ うげげ・・きもちわるいのだ・・"
                    ]
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[4,7], "rb":[10,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 2]
                      , "character_id":-10120
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 2]
                      , "character_id":-10120
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [2,15]
                  , "type": "goto"
                  , "room": "floor2"
                }
            }
          , "units": [
                {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [7,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [4,11]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [2,12]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
            ]
        }
      , "floor2": {
            "id": 51004002
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [6,13]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ラングの間"
                      , "LINES %avatar% うわ・・なんだここ"
                      , "SPEAK %avatar% もじょ 肺みたいな形してるのだ"
                    ]
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[1,11], "rb":[6,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 5]
                      , "character_id":-10124
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.2"
                }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 5]
                      , "character_id":-10124
                      , "icon":"shadow"
                    }
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[1,5], "rb":[7,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 4]
                      , "character_id":-10124
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.4"
                }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 5]
                      , "character_id":-10124
                      , "icon":"shadow"
                    }
                }
              , "enemy2.5": {
                    "trigger": "hero"
                  , "pos":[9,3], "rb":[15,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 5]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.6"
                }
              , "enemy2.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 13]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [11,14]
                  , "type": "goto"
                  , "room": "floor3"
                }
            }
          , "units": [
                {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [3,8]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [3,11]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [11,8]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [10,11]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":10
                }
            ]
        }
      , "floor3": {
            "id": 51004003
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [3,12]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF 腸の間"
                      , "LINES %avatar% うわ・・なんだここ"
                      , "SPEAK %avatar% もじょ なんか汚い感じなのだ・・"
                      , "LINES %avatar% う○こになった気分"
                    ]
                }
              , "enemy3.1": {
                    "trigger": "hero"
                  , "pos":[1,1], "rb":[3,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [8, 1]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.2"
                }
              , "enemy3.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 2]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                }
              , "enemy3.3": {
                    "trigger": "hero"
                  , "pos":[7,1], "rb":[15,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 5]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.4"
                }
              , "enemy3.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 5]
                      , "character_id":-10122
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [14,14]
                  , "type": "goto"
                  , "room": "floor4"
                }
              , "torch1": {
                    "pos": [5, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [7, 3]
                  , "ornament": "torch"
                }
            }
          , "units": [
                {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [1,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "888666"
                  , "excurse_step": 3
                  , "add_level":10
                }

             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [8,2 ]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "82"
                  , "excurse_step": 1
                  , "add_level":10
                }
             ,  {
                    "character_id": -10123
                  , "icon":"shadowB"
                  , "pos": [15,10]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "2288"
                  , "excurse_step": 2
                  , "add_level":10
                }
            ]
        }
      , "floor4": {
            "id": 51004004
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [6,8]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ハートの間"
                      , "LINES %avatar% あっ！いた！"
                      , "LINES %muma% ギョギョギョ・・そろそろ喰らってやろう"
                      , "LINES %man% ヒィィ！！！助けてくれー！！！"
                      , "LINES %avatar% あいつが夢魔か・・"
                      , "SPEAK %avatar% もじょ 死にそうになってるのだ"
                      , "LINES %avatar% 助けなきゃ！"
                    ]
                }
              , "speak_end": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %man% や、やった！倒した！！"
                      , "LINES %avatar% よし、現実に戻ろう"
                    ]
  	               , "chain": "goto_next"
                }
              , "goto_next": {
                    "type": "goto"
                  , "room": "floor5"
                }
              , "goal": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [8, 1]
                  , "type": "escape"
                  , "ornament": "goto"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9907
                  , "code": "man"
                  , "icon":"man"
                  , "pos": [7,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -10111
                  , "icon":"shadowB"
                  , "code": "muma"
                  , "pos": [6,3]
                  , "act_brain": "rest"
                  , "trigger_gimmick": "speak_end"
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
      , "floor5": {
            "id": 51004005
          , "battle_bg": "room1"
          , "environment": "grass"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [3,1]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% う・・うーん・・"
                      , "LINES %man% う・・うーん・・"
                      , "SPEAK %avatar% もじょ うーん・・なのだ・・"
                      , "LINES %layla% あ！目が覚めた？？"
                      , "LINES %layla% 戻ってこれたみたいね。よかった・・。"
                      , "SPEAK %avatar% もじょ 夢魔はやっつけたのだ"
                      , "LINES %man% ありがとう・・。本当に助かったよ。"
                      , "LINES %avatar% い、いえ・・。よかったです。"
                      , "LINES %layla% さあ、霊子力研究所のことを話せる？"
                      , "LINES %man% ああ・・。入口も内部もばっちりだ"
                      , "LINES %layla% よし！レジスタンスの力を集結して霊子力研究所をたたくわよ！"
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
                    "character_id": -9907
                  , "code": "man"
                  , "icon":"man"
                  , "pos": [2,2]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -20103
                  , "icon":"layla"
                  , "pos": [4,1]
                  , "union": 1
                  , "code": "layla"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
