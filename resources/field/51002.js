{
    "extra_uniticons": ["shadow", "shadowB", "shadow2" ,"layla", "man"]
  , "start_units": [
        {
            "condition": {"cleared":false}
          , "character_id": -20103
          , "icon":"layla"
          , "union": 1
          , "code": "layla"
          , "act_brain": "manual"
          , "items": [-1002, -1002, -1002, -1005, -1005, -1005, -3002, -3002, -3002, -3002, -3002, -3002, -3101, -3101, -3101, -3101]
    		  , "add_level": 35
          , "early_gimmick": "layla_escape"
        }
    ]
  , "global_gimmicks": {
        "layla_escape": {
            "ignition": {"unit_exist":"layla"}
          , "type": "lead"
          , "textsymbol": "sphere_layla_escape"
          , "leads": [
                "LINES %layla% いたたた…\nごめん、ちょっと下がってるね…"
            ]
        }
    }
  , "rooms": {
        "start": {
            "id": 51002000
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_dungeon"
          , "start_pos": [3,8]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ひえ。カオス！"
                      , "LINES %layla% なんか荒れてるわねぇ・・"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[4,7], "rb":[8,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 7]
                      , "code": "enemy1"
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.2"
                }
              , "enemy0.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [16, 7]
                      , "character_id":-10125
                      , "code": "enemy2"
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %enemy1% YO!YO!俺たち生まれも育ちも流刑地育ち。悪い奴らは大体友達"
                      , "LINES %enemy2% ん～なんだぁ？あいつらは。やっちまうか！"
                      , "SPEAK %avatar% もじょ 変な奴らがうようよいるのだ・・"
                      , "LINES %avatar% こわいなぁ・・"
                    ]
                }
              , "enemy0.3": {
                    "trigger": "hero"
                  , "pos":[13,7], "rb":[16,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 7]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.4"
                }
              , "enemy0.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 8]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                }
              , "enemy0.5": {
                    "trigger": "hero"
                  , "pos":[12,2], "rb":[17,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 2]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.6"
                }
              , "enemy0.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 3]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.7"
                }
              , "enemy0.7": {
                    "type": "unit"
                  , "unit": {
                        "pos": [21, 2]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [22,2], "rb":[22,3]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor1"
                }
            }
          , "units": [
                {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [10,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [16,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "floor1": {
            "id": 51002001
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_dungeon"
          , "start_pos": [0,2]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわ・・落書きだらけ・・"
                      , "SPEAK %avatar% もじょ うーむ・・なのだ"
                    ]
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[3,7], "rb":[10,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 7]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 8]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[14,7], "rb":[19,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [19, 7]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [19, 8]
                      , "character_id":-10125
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [22,9]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
            }
          , "units": [
                {
                    "character_id": -10125
                  , "icon":"shadow"
                  , "pos": [9,2]
                }
             ,  {
                    "character_id": -10125
                  , "icon":"shadow"
                  , "pos": [9,3]
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [6,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [3,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [18,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "floor2": {
            "id": 51002002
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_dungeon"
          , "start_pos": [0,8]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %layla% まだまだ続きそうね・・"
                      , "LINES %avatar% 嫌だなぁ・・帰りたい・・"
                    ]
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[1,7], "rb":[5,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [8, 7]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                  , "chain": "enemy2.2"
                }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 8]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[9,2], "rb":[13,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 2]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                  , "chain": "enemy2.4"
                }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 3]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [13,11]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor3"
                }
            }
          , "units": [
                {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [3,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [6,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [11,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "888222"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [11,9]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "64"
                  , "excurse_step": 1
                }
            ]
        }
     , "floor3": {
            "id": 51002003
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_dungeon"
          , "start_pos": [2,0]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、いきどまりぽい"
                      , "SPEAK %avatar% もじょ もう帰るのだ・・"
                      , "LINES %layla% とりあえず向こう岸まで行ってみましょ"
                    ]
                }
              , "enemy3.1": {
                    "trigger": "hero"
                  , "pos":[8,2], "rb":[11,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 8]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                  , "chain": "enemy3.2"
                }
              , "enemy3.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [13, 9]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                }
              , "enemy3.3": {
                    "trigger": "hero"
                  , "pos":[10,7], "rb":[13,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 7]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                  , "chain": "enemy3.4"
                }
              , "enemy3.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 9]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                  , "chain": "enemy3.5"
                }
              , "enemy3.5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 2]
                      , "character_id":-10085
                      , "icon":"shadow"
                      , "add_level":2
                    }
                }
              , "treasure_find": {
                    "trigger": "hero"
                  , "pos":[0,9]
                  , "ornament": "twinkle"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、これだね"
                      , "SPEAK %avatar% もじょ よし、さっさとこんなとこから帰るのだ"
                    ]
                  , "chain": "boss_appear"
                }
              , "boss_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [0, 7]
                      , "character_id":-10121
                      , "icon":"shadow2"
                      , "trigger_gimmick": "endspeak"
                      , "bgm": "bgm_bossbattle"
                    }
                  , "chain": "speak2"
                }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あらら・・"
                      , "SPEAK %avatar% もじょ やっぱりなのだ・・"
                      , "LINES %avatar% やっつけなきゃ帰れないね・・"
                    ]
                }
	            , "endspeak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし。やっつけたぞ"
                      , "SPEAK %avatar% もじょ さっさとこんなとこから帰るのだ"
            	      ]
    	            , "chain": "regist_appear"
	              }
	            , "regist_appear": {
	                "type": "unit"
	              , "unit": {
	                      "pos": [3, 8]
	                    , "character_id":-9907
	                    , "icon":"man"
                      , "union": 1
                      , "code": "regist"
                      , "act_brain": "rest"
                      , "brain_noattack": true
	                  }
	                , "chain": "endspeak2"
	              }
	            , "endspeak2": {
                    "type": "lead"
                  , "switch": [
                        {
                            "condition": {"cleared":false}
                          , "textsymbol": "sphere_51002_floor2_endspeak2_1"
          	              , "leads": [
                                  "LINES %regist% おーい！朗報だ！"
                                , "LINES %avatar% あ。あのレジスタンスの人だ・・"
                                , "LINES %layla% どうしたのかしら"
                                , "LINES %regist% 例の重要情報を握っている人物が判明した"
                                , "LINES %layla% えっ？本当！？"
                                , "LINES %regist% ああ。だが重大な問題を抱えてしまっているようだ・・。"
                                , "LINES %regist% そのお金は私からあのワーカーに渡しておこう。"
                                , "LINES %regist% 取り急ぎ、西にあるヤズド村に向かってくれ"
                                , "LINES %layla% わかったわ"
                                , "SPEAK %avatar% もじょ やれやれ小忙しいのだ・・"
          	                ]
                        }
                      , {
                            "condition": {"cleared":true}
                          , "textsymbol": "sphere_51002_floor2_endspeak2_2"
          	              , "leads": [
                                  "LINES %avatar% よし帰ろう"
          	                ]
                        }
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
                    "character_id": -10085
                  , "icon":"shadow"
                  , "pos": [7,2]
                  , "add_level":2
                }
             ,  {
                    "character_id": -10085
                  , "icon":"shadow"
                  , "pos": [9,2]
                  , "add_level":2
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [0,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "222888"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [3,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10110
                  , "icon":"shadowB"
                  , "pos": [8,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
    }
}
