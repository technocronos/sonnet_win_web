{
    "extra_uniticons": ["shadow", "shadowB", "shadowG" ,"elena"]
  , "start_units": [
        {
            "condition": {"cleared":false}
          , "character_id": -20101
          , "icon":"elena"
          , "union": 1
          , "code": "elena"
          , "act_brain": "manual"
          , "items": [-1002, -1002, -1002, -1005, -1005, -1005, -3002, -3002, -3002, -3002, -3002, -3002, -3101, -3101, -3101, -3101]
    		  , "add_level": 47
          , "early_gimmick": "elena_escape"
        }
    ]
  , "global_gimmicks": {
        "elena_escape": {
            "ignition": {"unit_exist":"elena"}
          , "type": "lead"
          , "textsymbol": "sphere_elena_escape"
          , "rem": [
                "LINES %elena% いたたた…\nごめん、ちょっと下がってるね…"
            ]
        }
    }
  , "rooms": {
        "start": {
            "id": 21011000
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [5,10]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% うわぁ・・不気味なとこだなぁ・・"
                      , "LINES %elena% なんかすでに出てきそうな雰囲気・・"
                      , "LINES %avatar% とりあえず地下行ってみるか・・"
                      , "SPEAK %avatar% もじょ もじょはもう帰りたいのだ"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[3,4], "rb":[7,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 4]
                      , "code" : "enemy1"
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %enemy1% この王家の墓を荒らすのは誰だ・・"
                      , "LINES %avatar% oh.."
                      , "SPEAK %avatar% もじょ やっぱ出たのだ・・"
                    ]
                }
              , "torch1": {
                    "pos": [4, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [6, 3]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [4, 7]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [6, 7]
                  , "ornament": "torch"
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [5,3]
                  , "type": "goto"
                  , "room": "floor1"
                }
            }
        }
      , "floor1": {
            "id": 21011001
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [6,19]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% これは・・地下の大聖堂？ものすごい大きい！"
                      , "SPEAK %avatar% もじょ どうやら王族の墓場らしいのだ"
                      , "LINES %avatar% なんか火の玉がぐるぐるまわってるね・・"
                      , "SPEAK %avatar% もじょ 近づかなければ襲ってはこないのだ"
                    ]
                }
              , "RIP1": {
                    "trigger": "hero"
                  , "pos":[2,4]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% 『我が母・・安らかに・・』"
                      , "SPEAK %avatar% もじょ 母親の墓なのだ・・"
                    ]
                }
              , "RIP2": {
                    "trigger": "hero"
                  , "pos":[6,4], "rb":[7,4]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% 『我が兄弟たち・・安らかに・・』"
                      , "SPEAK %avatar% もじょ 兄弟たちの墓なのだ・・"
                    ]
                }
              , "torch1": {
                    "pos": [1, 13]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [1, 19]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [13, 13]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [13, 19]
                  , "ornament": "torch"
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[1,13], "rb":[4,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 14]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 15]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% にゃあ！"
                      , "SPEAK %avatar% もじょ ミイラが化けて出たのだ"
                    ]
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[1,3], "rb":[3,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "speak2"
                }
              , "enemy1.5": {
                    "trigger": "hero"
                  , "pos":[5,3], "rb":[8,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.6"
                }
              , "enemy1.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "speak2"
                }
              , "enemy1.7": {
                    "trigger": "hero"
                  , "pos":[10,3], "rb":[13,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.8"
                }
              , "enemy1.8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 3]
                      , "character_id":-10067
                      , "icon":"shadow"
                    }
                  , "chain": "speak2"
                }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% こっちもか"
                      , "SPEAK %avatar% もじょ やっぱ出たのだ・・"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [13,3]
                  , "type": "goto"
                  , "room": "floor2"
                }
            }
          , "units": [
                {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [4,14]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6666888844442222"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [8,15]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "8884444222266668"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [9,16]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "226668884442"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [12,17]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444222666888"
                  , "excurse_step": 2
                }
            ]
        }
      , "floor2": {
            "id": 21011002
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [12,20]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うーんまただだっ広いね"
                      , "SPEAK %avatar% もじょ 3方向あるのだ・・"
                    ]
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[1,11], "rb":[3,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 11]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.2"
                }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 11]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% にゃあ！"
                      , "SPEAK %avatar% もじょ ミイラが化けて出たのだ"
                    ]
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[21,11], "rb":[23,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [23, 11]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.4"
                }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [23, 16]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy2.5": {
                    "trigger": "hero"
                  , "pos":[10,3], "rb":[14,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 3]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2.6"
                }
              , "enemy2.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 3]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                }
              , "goto_next1": {
                    "trigger": "hero"
                  , "pos": [2,11]
                  , "type": "goto"
                  , "room": "floor3"
                }
              , "goto_next2": {
                    "trigger": "hero"
                  , "pos": [12,3]
                  , "type": "goto"
                  , "room": "floor3"
                }
              , "goto_next3": {
                    "trigger": "hero"
                  , "pos": [22,11]
                  , "type": "goto"
                  , "room": "floor3"
                }
              , "torch1": {
                    "pos": [1, 16]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [23, 16]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [10, 3]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [10, 12]
                  , "ornament": "torch"
                }
              , "torch5": {
                    "pos": [14, 12]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [10, 15]
                  , "ornament": "torch"
                }
              , "torch7": {
                    "pos": [14, 15]
                  , "ornament": "torch"
                }
            }
          , "units": [
                {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [8,10]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666888444222"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [11,13]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444222666888"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [13,12]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "226668884442"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [16,11]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "884442226668"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [8,15]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "266688844422"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [11,15]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "884442226668"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [13,14]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666888444222"
                  , "excurse_step": 2
                }
             ,  {
                    "character_id": -10068
                  , "icon":"shadowB"
                  , "pos": [16,17]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444222666888"
                  , "excurse_step": 2
                }
            ]
        }
     , "floor3": {
            "id": 21011003
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos_on": {
                "goto_next1": [1,14]
               ,"goto_next2": [12,3]
               ,"goto_next3": [23,13]
            }
          , "gimmicks": {
                "RIP_Last": {
                    "trigger": "hero"
                  , "pos":[11, 15], "rb":[13,15]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% 石版だ・・何か彫ってある・・"
                      , "SPEAK %avatar% もじょ なんて書いてあるのだ？"
                      , "LINES %avatar% 『わが父、すべてのトロル族と共にここに眠る・・』"
                      , "LINES %avatar% 『人間に魂を売り、すべてのトロル族を絶滅させた・・ここに100年戦争終結す・・』"
                      , "LINES %avatar% 『今日、これより人間、マルス・マルティーニとして生きる・・』"
                      , "LINES %avatar% 『せめてもの償いにすべての同胞の墓を作りここに葬るものとする・・』"
                      , "LINES %avatar% 『トロル暦4500年、レオンハルト』"
                      , "SPEAK %avatar% もじょ レオンハルト王が作ったのだ・・"
                      , "LINES %avatar% ・・今から300年前・・ということは今のマルティーニ朝は一体・・"
                      , "SPEAK %avatar% もじょ ・・・"
                    ]
                  ,"chain" : "enemy_boss"
                }
              , "enemy_boss": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 17]
                      , "character_id":-10107
                      , "icon":"shadowG"
    					        , "trigger_gimmick": "endspeak"
                      , "bgm": "bgm_bigboss"
                    }
                  , "chain": "RIP_Last2"
                }
              , "RIP_Last2": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% まさか・・伝説のブラックトロルに生き残りが・・？"
                      , "SPEAK %avatar% もじょ うっ！すごい腐臭なのだ・・"
                      , "SPEAK %avatar% もじょ どうやらすでに屍になっているようなのだ・・"
                      , "LINES %avatar% 怨念で侵入者を襲っているんだ・・"
                      , "SPEAK %avatar% もじょ 襲いかかってくるのだ・・！"
                    ]
                }
	            , "endspeak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% ・・・"
                      , "LINES %elena% これで大体のことははっきりしたわね"
                      , "LINES %avatar% そうだね・・。"
                      , "LINES %avatar% マルティーニ王は・・ブラックトロルの王だったんだ。"
                      , "LINES %avatar% でも・・どうして人間として生きようと思ったんだろう。"
                      , "SPEAK %avatar% もじょ それはわからんのだ。"
                      , "LINES %avatar% 人間とか亜人とか・・一体なんなんだろうね・・。"
                      , "LINES %elena% ・・・。"
                      , "LINES %avatar% エレナ、自分のことも分かったし、もう道案内は大丈夫だよ。"
                      , "LINES %avatar% 送っていくから里に戻ろう"
                      , "LINES %elena% うん。ありがと。"
                    ]
    	            , "chain": "goal"
	            }
              , "enemy3.1.1": {
                    "trigger": "hero"
                  , "pos":[2,4], "rb":[2,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 5]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.1.2": {
                    "trigger": "hero"
                  , "pos":[2,17], "rb":[2,21]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.1.3": {
                    "trigger": "hero"
                  , "pos":[21,6], "rb":[22,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [21, 5]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.1.4": {
                    "trigger": "hero"
                  , "pos":[22,18], "rb":[22,21]
                  , "type": "unit"
                  , "unit": {
                        "pos": [22, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% うーん・・しつこい"
                      , "SPEAK %avatar% もじょ もうちょいなのだ"
                    ]
                }
              , "enemy3.3": {
                    "trigger": "hero"
                  , "pos":[3,4], "rb":[9,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 4]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.4"
                }
              , "enemy3.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 5]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                }
              , "enemy3.5": {
                    "trigger": "hero"
                  , "pos":[3,22], "rb":[11,23]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.6"
                }
              , "enemy3.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 23]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.7"
                }
              , "enemy3.7": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.8": {
                    "trigger": "hero"
                  , "pos":[16,4], "rb":[20,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [16, 4]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.9"
                }
              , "enemy3.9": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 4]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.10": {
                    "trigger": "hero"
                  , "pos":[13,22], "rb":[21,23]
                  , "type": "unit"
                  , "unit": {
                        "pos": [16, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.11"
                }
              , "enemy3.11": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 23]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.12"
                }
              , "enemy3.12": {
                    "type": "unit"
                  , "unit": {
                        "pos": [18, 22]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "enemy3.13": {
                    "trigger": "hero"
                  , "pos":[8,10], "rb":[16,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 14]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.14"
                }
              , "enemy3.14": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 14]
                      , "character_id":-10081
                      , "icon":"shadow"
                    }
                }
              , "torch1": {
                    "pos": [10, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [14, 3]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [4, 16]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [23, 16]
                  , "ornament": "torch"
                }
              , "torch5": {
                    "pos": [11, 15]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [13, 15]
                  , "ornament": "torch"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
        }
    }
}
