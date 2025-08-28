{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "elena", "noel"]
  , "rooms": {
        "start": {
            "id": 21013000
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [11,0]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% この洞窟だね・・"
                      , "SPEAK %avatar% もじょ なんだか不気味な洞窟なのだ"
                      , "LINES %avatar% エレナやノエルさん、無事かなぁ・・"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[6,2], "rb":[10,2]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-10073
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.2"
                }
              , "enemy0.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 6]
                      , "character_id":-10073
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% モンスターだ"
                      , "SPEAK %avatar% もじょ やっぱ出たのだ・・"
                    ]
                }
              , "enemy0.3": {
                    "trigger": "hero"
                  , "pos":[0,6], "rb":[6,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [0, 8]
                      , "character_id":-10073
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.4"
                }
              , "enemy0.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 11]
                      , "character_id":-10073
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                        "trigger": "hero"
                      , "pos": [11,7]
                      , "type": "goto"
                      , "room": "floor1"
                }
              , "fungi1": {
                    "pos": [2, 1]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [7, 3]
                  , "ornament": "fungi"
                }
              , "susuki1": {
                    "pos": [3, 11]
                  , "ornament": "susuki"
                }
              , "susuki2": {
                    "pos": [11, 8]
                  , "ornament": "susuki"
                }
            }
          , "units": [
                {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [8,10]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [5,9]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "floor1": {
            "id": 21013001
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [2,0]
          , "gimmicks": {
              "open_comment": {
                  "trigger": "rotation"
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% だだっ広いなぁ・・"
                    , "SPEAK %avatar% もじょ ヒルもうようよなのだ"
                  ]
              }
            , "enemy1.1": {
                  "trigger": "hero"
                , "pos":[1,2], "rb":[4,6]
                , "type": "unit"
                , "unit": {
                      "pos": [2, 7]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
                , "chain": "enemy1.2"
              }
            , "enemy1.2": {
                  "type": "unit"
                , "unit": {
                      "pos": [3, 7]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
              }
            , "enemy1.3": {
                  "trigger": "hero"
                , "pos":[11,5], "rb":[17,8]
                , "type": "unit"
                , "unit": {
                      "pos": [12, 6]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
                , "chain": "enemy1.4"
              }
            , "enemy1.4": {
                  "type": "unit"
                , "unit": {
                      "pos": [14, 6]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
              }
            , "enemy1.5": {
                  "trigger": "hero"
                , "pos":[14,2], "rb":[19,4]
                , "type": "unit"
                , "unit": {
                      "pos": [21, 7]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
                , "chain": "enemy1.6"
              }
            , "enemy1.6": {
                  "type": "unit"
                , "unit": {
                      "pos": [22, 7]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
              }
            , "enemy1.7": {
                  "trigger": "hero"
                , "pos":[20,6], "rb":[23,14]
                , "type": "unit"
                , "unit": {
                      "pos": [16, 3]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
                , "chain": "enemy1.8"
              }
            , "enemy1.8": {
                  "type": "unit"
                , "unit": {
                      "pos": [17, 3]
                    , "character_id":-10073
                    , "icon":"shadow"
                  }
              }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[17, 7]
                  , "type": "treasure"
	                , "ornament": "twinkle"
                  , "item_id": 1002
                }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [23,14]
                  , "type": "goto"
                  , "room": "floor2"
                }
              , "blueflower1": {
                    "pos": [6, 4]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [1, 6]
                  , "ornament": "blueflower"
                }
              , "fungi1": {
                    "pos": [6, 12]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [22, 3]
                  , "ornament": "fungi"
                }
              , "susuki1": {
                    "pos": [16, 6]
                  , "ornament": "susuki"
                }
            }
          , "units": [
                {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [8,2]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
              , {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [1,9]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [4,10]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [11,9]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [14,11]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [13,1]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [17,1]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [22,9]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "888222"
                  , "excurse_step": 3
                }
            ]
        }
      , "floor2": {
            "id": 21013002
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [11,4]
          , "gimmicks": {
              "open_comment": {
                   "condition": {"cleared":false}
                ,  "trigger": "rotation"
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 1"
                    , "LINES %avatar% あっ、いた！"
                    , "SPEAK %avatar% もじょ あいつらもいるのだ"
                    , "LINES %noel% うっ・・うう・・"
                    , "LINES %elena% に・・兄さん・・"
                    , "SPEAK %avatar% もじょ 一歩遅かったのだ・・"
                    , "LINES %boss% さんざん手こずらせてくれたな。だがこれで終わりだ！"
                    , "LINES %elena% 兄さん、危ない！！！"
                    , "UMOVE %elena% 4"
                    , "NOTIF ズシャッ！！"
                    , "LINES %elena% キャー！！！"
                    , "UMOVE %elena% 666"
                    , "LINES %avatar% エレナ！！！"
                    , "UMOVE %avatar% 24"
                    , "LINES %elena% ううっ・・"
                    , "SPEAK %avatar% もじょ 胸を貫かれてるのだ・・"
                    , "SPEAK %avatar% もじょ これはさすがに駄目なのだ"
                    , "LINES %noel% き、貴様・・"
                    , "LINES %elena% に、兄さん・・。駄目・・。力を解放しては・・。"
                    , "LINES %boss% な、何だ・・？"
                  ]
	               , "chain": "zako1_disappear"
              }
             , "zako1_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako1"
	               , "chain": "zako2_disappear"
               }
             , "zako1_escape": {
                    "type": "lead"
                  , "leads": [
                        "LINES %zako1% ぎゃっ！！"
                    ]
               }
             , "zako2_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "zako2"
	               , "chain": "speak2"
               }
             , "zako2_escape": {
                    "type": "lead"
                  , "leads": [
                        "LINES %zako2% うぎゃ！！"
                    ]
               }
             , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 部下が・・死んで行くのだ・・"
                      , "LINES %avatar% すごい力・・"
                      , "LINES %avatar% これが真のエルフの力・・"
                    ]
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
	               , "chain": "speak3"
               }
             , "speak3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %boss% ヒ・・ヒィィ・・！"
                      , "UMOVE %boss% 8"
                      , "UALGN %boss% 3"
                      , "LINES %boss% わかった！悪かった！許してくれ・・！"
                      , "UMOVE %boss% 8"
                      , "UALGN %boss% 3"
                      , "LINES %avatar% ノ、ノエルさん！もうやめて！"
                      , "LINES %noel% くたばれ！！！！！"
                      , "LINES %boss% ヒェェ！！！！！！"
                      , "UMOVE %boss% 6666"
                      , "LINES %boss% ウッ・・"
                      , "LINES %boss% うおおおおおお！！！！！！"
                      , "NOTIF グシャッ！！！"
                    ]
	               , "chain": "boss_disappear"
               }
             , "boss_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "boss"
	               , "chain": "speak4"
               }
             , "speak4": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ あっという間に全員粉々になってしまったのだ・・"
                      , "LINES %avatar% な、何と言う力・・。"
                      , "LINES %noel% はあ・・はあ・・。"
                      , "LINES %noel% う、ううっ！！！"
                      , "UMOVE %noel% 2"
                      , "LINES %avatar% あっ！ノ、ノエルさん！待って！！"
                    ]
	               , "chain": "noel_disappear"
               }
             , "noel_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "noel"
	               , "chain": "speak5"
               }
             , "speak5": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 行っちゃったのだ・・"
                      , "LINES %elena% う・・。うう・・・。"
                      , "LINES %avatar% エレナ！！しっかりして！！！"
                      , "LINES %elena% 私はもうだめ・・それより兄さんを・・"
                      , "LINES %elena% 兄さんはとうとう人間を殺してしまった"
                      , "LINES %elena% ダークエルフ化が始まってしまったの"
                      , "LINES %avatar% エレナ・・"
                      , "LINES %elena% お願い兄さんを止めて・・"
                      , "LINES %elena% さようなら・・友達になってくれて・・ありがとう・・"
                      , "LINES %avatar% エ、エレナァァァァァ！"
                    ]
	               , "chain": "elena_disappear"
               }
             , "elena_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "elena"
	               , "chain": "speak6"
               }
             , "speak6": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ ・・・"
                      , "LINES %avatar% エ、エレナ・・・。う・・。うう・・・。"
                      , "LINES %avatar% ・・・"
                      , "LINES %avatar% ノエルさんを追わなきゃ・・！"
                      , "SPEAK %avatar% もじょ ノエルは奥の方に逃げていったのだ"
                      , "LINES %avatar% 行こう・・！"
                    ]
	               , "chain": "goto_next_auto"
               }
            , "goto_next_auto": {
                    "type": "goto"
                  , "room": "floor3"
                }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [6,1]
                  , "type": "goto"
                  , "room": "floor3"
                }
              , "blueflower1": {
                    "pos": [4, 2]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [8, 2]
                  , "ornament": "blueflower"
                }
              , "fungi1": {
                    "pos": [1, 5]
                  , "ornament": "fungi2"
                }
            }
          , "units": [
                {
                    "pos": [6,2]
                  , "condition": {"cleared":false}
                  , "character_id":-20104
                  , "icon":"noel"
                  , "code": "noel"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -20101
                  , "condition": {"cleared":false}
                  , "pos": [7,3]
                  , "icon":"elena"
                  , "code": "elena"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [6, 4]
                  , "condition": {"cleared":false}
  	              , "character_id":-10054
  	              , "icon":"shadow2"
                  , "code": "boss"
                  , "align": 3
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [2, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
  	              , "icon":"shadow"
                  , "align": 3
                  , "code": "zako1"
                  , "early_gimmick": "zako1_escape"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [3, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
                  , "align": 3
  	              , "icon":"shadow"
                  , "code": "zako2"
                  , "early_gimmick": "zako2_escape"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [4, 5]
  	              , "character_id":-10052
                  , "condition": {"cleared":false}
  	              , "icon":"shadow"
                  , "code": "zako3"
                  , "align": 3
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [5, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
  	              , "icon":"shadow"
                  , "align": 3
                  , "code": "zako4"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [7, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
                  , "align": 3
  	              , "icon":"shadow"
                  , "code": "zako5"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [8, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
  	              , "icon":"shadow"
                  , "align": 3
                  , "code": "zako6"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [9, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
  	              , "icon":"shadow"
                  , "code": "zako7"
                  , "align": 3
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
  	                "pos": [10, 5]
                  , "condition": {"cleared":false}
  	              , "character_id":-10052
  	              , "icon":"shadow"
                  , "align": 3
                  , "code": "zako8"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor3": {
            "id": 21013003
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [3,10]
          , "gimmicks": {
              "open_comment": {
                  "condition": {"cleared":false}
                , "trigger": "rotation"
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% ノエルさん、どこまで行っちゃったのかなぁ・・"
                    , "SPEAK %avatar% もじょ とにかく追いかけるのだ"
                  ]
              }
            , "enemy3.1": {
                  "trigger": "hero"
                , "pos":[1,2], "rb":[5,7]
                , "type": "unit"
                , "unit": {
                      "pos": [1, 3]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
                , "chain": "enemy3.2"
              }
            , "enemy3.2": {
                  "type": "unit"
                , "unit": {
                      "pos": [2, 3]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
              }
            , "enemy3.3": {
                  "trigger": "hero"
                , "pos":[8,2], "rb":[15,7]
                , "type": "unit"
                , "unit": {
                      "pos": [10, 6]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
                , "chain": "enemy3.4"
              }
            , "enemy3.4": {
                  "type": "unit"
                , "unit": {
                      "pos": [11, 6]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
              }
            , "enemy3.5": {
                  "trigger": "hero"
                , "pos":[12,17], "rb":[15,10]
                , "type": "unit"
                , "unit": {
                      "pos": [8, 2]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
                , "chain": "enemy3.6"
              }
            , "enemy3.6": {
                  "type": "unit"
                , "unit": {
                      "pos": [9, 2]
                    , "character_id":-10093
                    , "icon":"shadow"
                  }
              }
            , "treasure1": {
                    "trigger": "player"
                  , "pos":[5, 7]
                  , "type": "treasure"
	                , "ornament": "twinkle"
                  , "item_id": 1002
              }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [15,10]
                  , "type": "goto"
                  , "room": "floor4"
                }
              , "blueflower1": {
                    "pos": [1, 6]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [1, 7]
                  , "ornament": "blueflower"
                }
              , "blueflower3": {
                    "pos": [14, 5]
                  , "ornament": "blueflower"
                }
            }
          , "units": [
                {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [1,1]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [4,0]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [8,1]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [12,0]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10108
                  , "icon":"shadowB"
                  , "pos": [12,8]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "8822"
                  , "excurse_step": 2
                }
            ]
        }
      , "floor4": {
            "id": 21013004
          , "battle_bg": "wetlands"
          , "environment": "none"
          , "start_pos": [6,7]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ あ・・ここだけ吹き抜けになってるのだ"
                      , "LINES %avatar% 月の明りであかるいね・・"
                      , "SPEAK %avatar% もじょ なにやらまがまがしいオーラが流れてくるのだ・・"
                      , "LINES %avatar% も・・もじょ・・。い・・いたよ・・"
                      , "LINES %avatar% ノ、ノエルさん？ノエルさんでしょ？"
                      , "LINES %noel% [NAME]か・・とうとう私は人を殺してしまった"
                      , "LINES %noel% 私は闇のエルフとなってしまう・・そうなる前に僕を殺してほしい"
                      , "LINES %avatar% そんなこと、できないよ！"
                      , "LINES %noel% ダークエルフは人間を食べるんだ・・戻る方法は無い"
                      , "LINES %noel% 最後のお願いだ・・頼む"
                      , "LINES %avatar% そ・・そんな・・"
                      , "LINES %noel% ううっ・・"
                    ]
	               , "chain": "noel_disappear"
                }
              , "noel_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "noel"
	                , "chain": "darkelf"
                }
              , "darkelf": {
                      "type": "unit"
                    , "unit": {
                          "pos": [6, 3]
                        , "character_id":-10109
                        , "code": "darkelf"
                        , "early_gimmick": "darkelf_end"
                        , "trigger_gimmick": "speak_end"
                        , "act_brain": "rest"
                        , "icon":"shadow2"
                        , "bgm": "bgm_bigboss"
                      }
  	               , "chain": "darkelf_speak"
                }
              , "darkelf_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %darkelf% グググ・・人間ガニクイ・・"
                      , "LINES %avatar% ノエルさん・・"
                      , "LINES %darkelf% キサマモコロシテヤル・・"
                      , "LINES %avatar% ノエルさん・・！！待って！！"
                      , "SPEAK %avatar% もじょ これはヤバいのだ！やらなきゃ殺されるのだ！"
                      , "LINES %avatar% だ・・だって・・！"
                    ]
                }
              , "darkelf_end": {
                    "type": "lead"
                  , "leads": [
                        "LINES %darkelf% [NAME]、ありがとう・・"
                      , "LINES %darkelf% 僕たちは人間とは相容れない存在だ・・これでよかったんだ。"
                      , "LINES %darkelf% 君に止めてもらえてよかったよ・・ありがとう・・"
                      , "LINES %avatar% ノエルさん・・ごめん・・"
                    ]
                }
              , "speak_end": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% ・・・"
                      , "SPEAK %avatar% もじょ これからどうするのだ？"
                      , "LINES %avatar% 砂漠に行こう"
                      , "LINES %avatar% レジスタンスに入る"
                      , "SPEAK %avatar% もじょ えー・・？あのゲバルとかいうやつにまた会うのだ？"
                      , "LINES %avatar% もうマルティーニは許せない・・！"
                      , "LINES %avatar% エレナとノエルさんのかたき！"
                      , "SPEAK %avatar% もじょ まーどのみち付け狙われてるわけだし、なのだ"
                      , "LINES %avatar% そういうこと！"
                      , "LINES %avatar% 一人じゃマルティーニは倒せないからね"
                      , "LINES %avatar% まずはゲバルさんに相談してみよう！"
                    ]
  	               , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "goal_cleared": {
                    "condition": {"cleared":true}
                  , "trigger": "player"
                  , "pos":[6,3]
                  , "type": "escape"
                  , "ornament": "goto"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "condition": {"cleared":false}
                  , "pos": [6,3]
                  , "character_id":-20104
                  , "icon":"noel"
                  , "code": "noel"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
