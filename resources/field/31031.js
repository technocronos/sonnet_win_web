{
    "extra_uniticons": ["shadow", "shadow2", "shadowG", "layla", "man"]
  , "rooms": {
        "start": {
            "id": 31031000
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [6,12]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% ゲバル さあ！とうとうこの日がやってきた！"
                      , "SPEAK %avatar% ゲバル この霊子力研究所を破壊し、捕らわれた亜人たちを解放する！"
                      , "SPEAK %avatar% ゲバル これより本作戦をメジロ作戦と呼称する！"
                      , "SPEAK %avatar% もじょ ちょ・・アニメの見すぎなのだ・・"
                      , "SPEAK %avatar% ゲバル 俺と[NAME]はマザーコンピューターを目指す。"
                      , "SPEAK %avatar% ゲバル [NAME]君は研究所を侵入者から守っているロボットをすべて停止させてほしい。"
                      , "SPEAK %avatar% ゲバル 中央制御室にマザーコンピューターがあるはずだ。"
                      , "SPEAK %avatar% ゲバル 俺がそれを破壊する。霊子力研究所はすべて停止する。"
                      , "LINES %avatar% はい！"
                      , "SPEAK %avatar% ゲバル レイラとみんなは別行動で捕まっている亜人の救出に回ってくれ"
                      , "LINES %layla% わかったわ。そっちは頼んだわね。"
                      , "SPEAK %avatar% ゲバル では、作戦開始！"
                    ]
                  , "chain": "layla_exit"
                }
             , "layla_exit": {
                   "type": "unit_exit"
                 , "exit_target": "layla"
	               , "chain": "regist1_exit"
               }
             , "regist1_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist1"
	               , "chain": "regist2_exit"
               }
             , "regist2_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist2"
	               , "chain": "regist3_exit"
               }
             , "regist3_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist3"
	               , "chain": "regist4_exit"
               }
             , "regist4_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist4"
	               , "chain": "regist5_exit"
               }
             , "regist5_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist5"
	               , "chain": "regist6_exit"
               }
             , "regist6_exit": {
                   "type": "unit_exit"
                 , "exit_target": "regist6"
               }
             , "enemy0.1": {
                   "trigger": "hero"
                 , "pos":[1,2], "rb":[11,8]
                 , "type": "unit"
                 , "unit": {
                       "pos": [5, 2]
                     , "character_id":-10112
                     , "code": "enemy0.1"
                     , "icon":"shadow"
                     , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                   }
                 , "chain": "speak0.1"
               }
             , "speak0.1": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy0.1% ピー・・ガー・・"
                    , "LINES %enemy0.1% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy0.1% 排除シマス・・"
                  ]
                , "chain": "enemy0.2"
               }
             , "enemy0.2": {
                   "type": "unit"
                 , "unit": {
                       "pos": [7, 2]
                     , "character_id":-10112
                     , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                     , "code": "enemy0.2"
                     , "icon":"shadow"
                   }
                 , "chain": "speak0.2"
               }
             , "speak0.2": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy0.2% ピー・・ガー・・"
                    , "LINES %enemy0.2% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy0.2% 排除シマス・・"
                  ]
                , "chain": "speak_end"
               }
             , "speak_end": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% ゲバル ムッ！あれは・・"
                      , "SPEAK %avatar% ゲバル ブラスターロボットの最新機だ。霊子弾を撃ってくるぞ！"
                      , "LINES %avatar% らじゃ！"
                    ]
               }
             , "goto_next": {
                        "trigger": "hero"
                      , "pos": [6,2]
                      , "type": "goto"
                      , "ornament": "goto"
                      , "room": "floor1"
               }
              , "lamp1": {
                    "pos": [4, 0]
                  , "ornament": "lamp2"
                }
              , "lamp2": {
                    "pos": [8, 0]
                  , "ornament": "lamp2"
                }
              , "lamp3": {
                    "pos": [3, 10]
                  , "ornament": "lamp2"
                }
              , "lamp4": {
                    "pos": [9, 10]
                  , "ornament": "lamp2"
                }
              , "floor_1": {
                    "pos": [5, 4]
                  , "ornament": "floor"
                }
              , "floor_2": {
                    "pos": [6, 4]
                  , "ornament": "floor"
                }
              , "floor_3": {
                    "pos": [7, 4]
                  , "ornament": "floor"
                }
              , "floor_4": {
                    "pos": [5, 5]
                  , "ornament": "floor"
                }
              , "floor_5": {
                    "pos": [6, 5]
                  , "ornament": "floor"
                }
              , "floor_6": {
                    "pos": [7, 5]
                  , "ornament": "floor"
                }
              , "floor_7": {
                    "pos": [5, 6]
                  , "ornament": "floor"
                }
              , "floor_8": {
                    "pos": [6, 6]
                  , "ornament": "floor"
                }
              , "floor_9": {
                    "pos": [7, 6]
                  , "ornament": "floor"
                }
            }
          , "units": [
                {
                    "condition": {"cleared":false}
        				  , "character_id": -20103
                  , "pos": [6,13]
                  , "icon":"layla"
                  , "union": 1
                  , "align": 3
                  , "code": "layla"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [3,13]
                  , "code": "regist1"
                  , "icon":"man"
                  , "align": 3
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [4,13]
                  , "code": "regist2"
                  , "icon":"man"
                  , "align": 3
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [5,13]
                  , "code": "regist3"
                  , "icon":"man"
                  , "align": 3
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [7,13]
                  , "code": "regist4"
                  , "align": 3
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [8,13]
                  , "code": "regist5"
                  , "align": 3
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "pos": [9,13]
                  , "code": "regist6"
                  , "align": 3
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor1": {
            "id": 31031001
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [3,14]
          , "gimmicks": {
              "enemy1.1": {
                  "trigger": "hero"
                , "pos":[0,3], "rb":[6,9]
                , "type": "unit"
                , "unit": {
                      "pos": [5, 3]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.1"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.1"
              }
             , "speak1.1": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.1% ピー・・ガー・・"
                    , "LINES %enemy1.1% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.1% 排除シマス・・"
                  ]
                , "chain": "enemy1.2"
               }
            , "enemy1.2": {
                  "type": "unit"
                , "unit": {
                      "pos": [6, 3]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.2"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.2"
              }
             , "speak1.2": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.2% ピー・・ガー・・"
                    , "LINES %enemy1.2% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.2% 排除シマス・・"
                  ]
               }
            , "enemy1.3": {
                  "trigger": "hero"
                , "pos":[7,3], "rb":[11,9]
                , "type": "unit"
                , "unit": {
                      "pos": [16, 7]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.3"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.3"
              }
             , "speak1.3": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.3% ピー・・ガー・・"
                    , "LINES %enemy1.3% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.3% 排除シマス・・"
                  ]
                , "chain": "enemy1.4"
               }
            , "enemy1.4": {
                  "type": "unit"
                , "unit": {
                      "pos": [2, 7]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.4"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.4"
              }
             , "speak1.4": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.4% ピー・・ガー・・"
                    , "LINES %enemy1.4% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.4% 排除シマス・・"
                  ]
               }
            , "enemy1.5": {
                  "trigger": "hero"
                , "pos":[14,12], "rb":[20,15]
                , "type": "unit"
                , "unit": {
                      "pos": [20, 15]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.5"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.5"
              }
             , "speak1.5": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.5% ピー・・ガー・・"
                    , "LINES %enemy1.5% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.5% 排除シマス・・"
                  ]
               }
            , "enemy1.7": {
                  "trigger": "hero"
                , "pos":[14,4], "rb":[20,8]
                , "type": "unit"
                , "unit": {
                      "pos": [8, 6]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy1.7"
                  }
                , "chain": "speak1.7"
              }
             , "speak1.7": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.7% ピー・・ガー・・"
                    , "LINES %enemy1.7% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.7% 排除シマス・・"
                  ]
                , "chain": "enemy1.8"
               }
            , "enemy1.8": {
                  "type": "unit"
                , "unit": {
                      "pos": [8, 7]
                    , "character_id":-10112
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy1.8"
                    , "icon":"shadow"
                  }
                , "chain": "speak1.8"
              }
             , "speak1.8": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy1.8% ピー・・ガー・・"
                    , "LINES %enemy1.8% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy1.8% 排除シマス・・"
                  ]
               }
            , "treasure1": {
                  "type": "treasure"
                , "ornament": "twinkle"
                , "trigger": "player"
                , "pos": [20,12]
                , "item_id": 1003
              }
            , "treasure2": {
                  "type": "treasure"
                , "ornament": "twinkle"
                , "trigger": "player"
                , "pos": [20,13]
                , "item_id": 1003
              }
            , "treasure3": {
                  "type": "treasure"
                , "ornament": "twinkle"
                , "trigger": "player"
                , "pos": [11,3]
                , "item_id": 1003
              }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [20,2], "rb":[20,3]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
              , "lamp1": {
                    "pos": [0, 1]
                  , "ornament": "lamp2"
                }
              , "lamp2": {
                    "pos": [1, 1]
                  , "ornament": "lamp2"
                }
              , "lamp3": {
                    "pos": [2, 1]
                  , "ornament": "lamp2"
                }
              , "lamp4": {
                    "pos": [3, 1]
                  , "ornament": "lamp2"
                }
              , "lamp5": {
                    "pos": [4, 1]
                  , "ornament": "lamp2"
                }
              , "lamp6": {
                    "pos": [0, 10]
                  , "ornament": "lamp2"
                }
              , "lamp7": {
                    "pos": [2, 10]
                  , "ornament": "lamp2"
                }
              , "lamp8": {
                    "pos": [4, 10]
                  , "ornament": "lamp2"
                }
              , "lamp9": {
                    "pos": [12, 3]
                  , "ornament": "lamp2"
                }
              , "lamp10": {
                    "pos": [13, 3]
                  , "ornament": "lamp2"
                }
              , "lamp11": {
                    "pos": [16, 0]
                  , "ornament": "lamp2"
                }
              , "lamp12": {
                    "pos": [18, 0]
                  , "ornament": "lamp2"
                }
              , "lamp13": {
                    "pos": [15, 10]
                  , "ornament": "lamp2"
                }
              , "lamp14": {
                    "pos": [17, 10]
                  , "ornament": "lamp2"
                }
              , "lamp15": {
                    "pos": [18, 10]
                  , "ornament": "lamp2"
                }
              , "floor_1": {
                    "pos": [0, 4]
                  , "ornament": "floor"
                }
              , "floor_2": {
                    "pos": [1, 4]
                  , "ornament": "floor"
                }
              , "floor_3": {
                    "pos": [2, 4]
                  , "ornament": "floor"
                }
              , "floor_4": {
                    "pos": [3, 4]
                  , "ornament": "floor"
                }
              , "floor_5": {
                    "pos": [4, 4]
                  , "ornament": "floor"
                }
              , "floor_6": {
                    "pos": [0, 5]
                  , "ornament": "floor"
                }
              , "floor_7": {
                    "pos": [1, 5]
                  , "ornament": "floor"
                }
              , "floor_8": {
                    "pos": [2, 5]
                  , "ornament": "floor"
                }
              , "floor_9": {
                    "pos": [3, 5]
                  , "ornament": "floor"
                }
              , "floor_10": {
                    "pos": [4, 5]
                  , "ornament": "floor"
                }
              , "floor_11": {
                    "pos": [2, 13]
                  , "ornament": "floor"
                }
              , "floor_12": {
                    "pos": [3, 13]
                  , "ornament": "floor"
                }
              , "floor_13": {
                    "pos": [4, 13]
                  , "ornament": "floor"
                }
              , "floor_14": {
                    "pos": [2, 14]
                  , "ornament": "floor"
                }
              , "floor_15": {
                    "pos": [3, 14]
                  , "ornament": "floor"
                }
              , "floor_16": {
                    "pos": [4, 14]
                  , "ornament": "floor"
                }
              , "floor_17": {
                    "pos": [15, 13]
                  , "ornament": "floor"
                }
              , "floor_18": {
                    "pos": [16, 13]
                  , "ornament": "floor"
                }
              , "floor_19": {
                    "pos": [15, 14]
                  , "ornament": "floor"
                }
              , "floor_20": {
                    "pos": [16, 14]
                  , "ornament": "floor"
                }
              , "floor_21": {
                    "pos": [16, 2]
                  , "ornament": "floor"
                }
              , "floor_22": {
                    "pos": [17, 2]
                  , "ornament": "floor"
                }
              , "floor_23": {
                    "pos": [18, 2]
                  , "ornament": "floor"
                }
              , "floor_24": {
                    "pos": [16, 3]
                  , "ornament": "floor"
                }
              , "floor_25": {
                    "pos": [17, 3]
                  , "ornament": "floor"
                }
              , "floor_26": {
                    "pos": [18, 3]
                  , "ornament": "floor"
                }
              , "floor_27": {
                    "pos": [22, 3]
                  , "ornament": "floor"
                }
              , "floor_28": {
                    "pos": [23, 3]
                  , "ornament": "floor"
                }
              , "floor_29": {
                    "pos": [24, 3]
                  , "ornament": "floor"
                }
              , "floor_30": {
                    "pos": [22, 3]
                  , "ornament": "floor"
                }
              , "floor_31": {
                    "pos": [23, 3]
                  , "ornament": "floor"
                }
              , "floor_32": {
                    "pos": [24, 3]
                  , "ornament": "floor"
                }
            }
        }
      , "floor2": {
            "id": 31031002
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [2,20]
          , "gimmicks": {
              "enemy2.1": {
                  "trigger": "hero"
                , "pos":[5,19], "rb":[13,20]
                , "type": "unit"
                , "unit": {
                      "pos": [13, 19]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "code": "enemy2.1"
                    , "icon":"shadow"
                  }
                , "chain": "speak2.1"
              }
            , "speak2.1": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.1% ピー・・ガー・・"
                    , "LINES %enemy2.1% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.1% 排除シマス・・"
                  ]
              }
            , "enemy2.2": {
                  "trigger": "hero"
                , "pos":[10,19], "rb":[15,20]
                , "type": "unit"
                , "unit": {
                      "pos": [5, 19]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy2.2"
                  }
                , "chain": "speak2.2"
              }
            , "speak2.2": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.2% ピー・・ガー・・"
                    , "LINES %enemy2.2% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.2% 排除シマス・・"
                  ]
              }
            , "enemy2.3": {
                  "trigger": "hero"
                , "pos":[14,15], "rb":[20,21]
                , "type": "unit"
                , "unit": {
                      "pos": [16, 11]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy2.3"
                  }
                , "chain": "speak2.3"
              }
            , "speak2.3": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.3% ピー・・ガー・・"
                    , "LINES %enemy2.3% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.3% 排除シマス・・"
                  ]
                , "chain": "enemy2.4"
              }
            , "enemy2.4": {
                  "type": "unit"
                , "unit": {
                      "pos": [18, 11]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy2.4"
                  }
                , "chain": "speak2.4"
              }
            , "speak2.4": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.4% ピー・・ガー・・"
                    , "LINES %enemy2.4% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.4% 排除シマス・・"
                  ]
              }
            , "enemy2.5": {
                  "trigger": "hero"
                , "pos":[14,7], "rb":[20,11]
                , "type": "unit"
                , "unit": {
                      "pos": [10, 8]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy2.5"
                  }
                , "chain": "speak2.5"
              }
            , "speak2.5": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.5% ピー・・ガー・・"
                    , "LINES %enemy2.5% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.5% 排除シマス・・"
                  ]
                , "chain": "enemy2.6"
              }
            , "enemy2.6": {
                  "type": "unit"
                , "unit": {
                      "pos": [10, 9]
                    , "character_id":-10113
                    , "items": [-2007,-2007,-2007,-2007,-2007,-2007,-2007]
                    , "icon":"shadow"
                    , "code": "enemy2.6"
                  }
                , "chain": "speak2.6"
              }
            , "speak2.6": {
                  "type": "lead"
                , "leads": [
                      "LINES %enemy2.6% ピー・・ガー・・"
                    , "LINES %enemy2.6% 敵の潜入ヲ感知シマシタ"
                    , "LINES %enemy2.6% 排除シマス・・"
                  ]
              }
             , "speak1": {
                  "condition": {"cleared":false}
                , "trigger": "hero"
                , "pos":[5,8], "rb":[10,9]
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 0"
                    , "LINES %avatar% わー、ここガラス張りになってるね"
                    , "SPEAK %avatar% ゲバル 見ろ・・下の部屋を・・"
                    , "LINES %avatar% なんかカプセルみたいのがあるね・・"
                    , "LINES %avatar% あっ・・！"
                    , "SPEAK %avatar% ゲバル 気が付いたか？あの中に入ってるのはみんな亜人だ"
                    , "LINES %avatar% ・・・エルフもいる"
                    , "SPEAK %avatar% ゲバル 霊子力を吸い取られてるんだ"
                    , "SPEAK %avatar% もじょ あのまま死ぬまで吸い取られるのだ？"
                    , "SPEAK %avatar% ゲバル ああ・・。霊子力を吸われ続けてるからそんなにもたないがな"
                    , "LINES %avatar% なんてことを・・"
                  ]
               }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [2,8], "rb":[2,9]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor3"
                }
              , "lamp1": {
                    "pos": [1, 5]
                  , "ornament": "lamp2"
                }
              , "lamp2": {
                    "pos": [3, 5]
                  , "ornament": "lamp2"
                }
              , "lamp3": {
                    "pos": [6, 0]
                  , "ornament": "lamp2"
                }
              , "lamp4": {
                    "pos": [10, 0]
                  , "ornament": "lamp2"
                }
              , "lamp5": {
                    "pos": [13, 0]
                  , "ornament": "lamp2"
                }
              , "lamp6": {
                    "pos": [16, 0]
                  , "ornament": "lamp2"
                }
              , "lamp6_2": {
                    "pos": [18, 0]
                  , "ornament": "lamp2"
                }
              , "lamp7": {
                    "pos": [20, 0]
                  , "ornament": "lamp2"
                }
              , "lamp8": {
                    "pos": [1, 15]
                  , "ornament": "lamp2"
                }
              , "lamp9": {
                    "pos": [13, 15]
                  , "ornament": "lamp2"
                }
              , "lamp10": {
                    "pos": [8, 11]
                  , "ornament": "lamp2"
                }
              , "lamp11": {
                    "pos": [10, 11]
                  , "ornament": "lamp2"
                }
              , "lamp12": {
                    "pos": [6, 17]
                  , "ornament": "lamp2"
                }
              , "lamp13": {
                    "pos": [8, 17]
                  , "ornament": "lamp2"
                }
              , "lamp14": {
                    "pos": [10, 17]
                  , "ornament": "lamp2"
                }
              , "lamp14_2": {
                    "pos": [12, 17]
                  , "ornament": "lamp2"
                }
              , "lamp15": {
                    "pos": [15, 13]
                  , "ornament": "lamp2"
                }
              , "lamp16": {
                    "pos": [20, 13]
                  , "ornament": "lamp2"
                }
              , "floor_1": {
                    "pos": [2, 3]
                  , "ornament": "floor"
                }
              , "floor_2": {
                    "pos": [2, 9]
                  , "ornament": "floor"
                }
              , "floor_3": {
                    "pos": [17, 3]
                  , "ornament": "floor"
                }
              , "floor_4": {
                    "pos": [18, 3]
                  , "ornament": "floor"
                }
              , "floor_5": {
                    "pos": [17, 4]
                  , "ornament": "floor"
                }
              , "floor_6": {
                    "pos": [18, 4]
                  , "ornament": "floor"
                }
              , "floor_7": {
                    "pos": [16, 8]
                  , "ornament": "floor"
                }
              , "floor_8": {
                    "pos": [17, 8]
                  , "ornament": "floor"
                }
              , "floor_9": {
                    "pos": [18, 8]
                  , "ornament": "floor"
                }
              , "floor_10": {
                    "pos": [16, 9]
                  , "ornament": "floor"
                }
              , "floor_11": {
                    "pos": [17, 9]
                  , "ornament": "floor"
                }
              , "floor_12": {
                    "pos": [18, 9]
                  , "ornament": "floor"
                }
              , "floor_13": {
                    "pos": [16, 10]
                  , "ornament": "floor"
                }
              , "floor_14": {
                    "pos": [17, 10]
                  , "ornament": "floor"
                }
              , "floor_15": {
                    "pos": [18, 10]
                  , "ornament": "floor"
                }
              , "floor_16": {
                    "pos": [2, 19]
                  , "ornament": "floor"
                }
              , "floor_17": {
                    "pos": [3, 19]
                  , "ornament": "floor"
                }
              , "floor_18": {
                    "pos": [2, 20]
                  , "ornament": "floor"
                }
              , "floor_19": {
                    "pos": [3, 20]
                  , "ornament": "floor"
                }
              , "floor_20": {
                    "pos": [15, 19]
                  , "ornament": "floor"
                }
              , "floor_21": {
                    "pos": [16, 19]
                  , "ornament": "floor"
                }
              , "floor_22": {
                    "pos": [15, 20]
                  , "ornament": "floor"
                }
              , "floor_23": {
                    "pos": [16, 20]
                  , "ornament": "floor"
                }
            }
        }
      , "floor3": {
            "id": 31031003
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [6,9]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UALGN %gebaru% 3"
                      , "LINES %gebaru% よし、ついたぞ！ここがマザーコンピューターだ。"
                      , "LINES %gebaru% これを破壊するぞ。"
                      , "NOTIF まてい！！"
                      , "LINES %avatar% ヤバイ！援軍だ・・。"
                    ]
                  , "chain": "woden_appear"
                }
              , "woden_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,6]
                      , "character_id":-10102
                      , "icon":"shadowG"
                      , "code": "woden"
                    }
                  , "chain": "enemy3.1"
                }
              , "enemy3.1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.2"
                }
              , "enemy3.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.3"
                }
              , "enemy3.3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.4"
                }
              , "enemy3.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.5"
                }
              , "enemy3.5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.6"
                }
              , "enemy3.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 5]
                      , "character_id":-10052
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %woden% 貴様ら、袋のネズミだな。"
                      , "LINES %woden% この場でとっ捕まえてくれるわ。"
                      , "LINES %avatar% お・・おまえは・・オーディン・・"
                      , "SPEAK %avatar% もじょ ランカスタのやつなのだ。こいつはむちゃくちゃ強いのだ・・"
                      , "LINES %woden% ククク・・久方ぶりだな・・今度は逃がさんぞ・・"
                      , "LINES %gebaru% なぜだ・・？こんなタイミングよくあらわれるはずがない・・"
                      , "LINES %gebaru% まさか・・"
                      , "LINES %woden% ふっ・・バカめ・・すべてお見通しだ"
                      , "SPEAK %avatar% もじょ あまりにタイミング良すぎるのだ"
                      , "SPEAK %avatar% もじょ 情報が漏れてたんじゃないのか？"
                      , "LINES %avatar% とにかく逃げないと・・"
                    ]
                  , "chain": "goto_next"
                }
              , "goto_next": {
                    "type": "goto"
                  , "room": "floor4"
                }
              , "goal": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [11, 3]
                  , "type": "escape"
                  , "ornament": "goto"
                  , "escape_result": "success"
                }
              , "lamp1": {
                    "pos": [6, 0]
                  , "ornament": "lamp2"
                }
            }
          , "units": [
                {
                    "condition": {"cleared":false}
                  , "character_id": -20102
                  , "pos": [7,9]
                  , "icon":"man"
                  , "union": 1
                  , "code": "gebaru"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor4": {
            "id": 31031002
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [3,3]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %gebaru% よし、しばらくこの部屋に隠れているんだ・・"
                      , "LINES %gebaru% 隙を見て逃げるぞ！"
                      , "LINES %avatar% はい！"
                      , "SPEAK %avatar% もじょ とは言っても逃げられるのかこりゃなのだ・・"
                    ]
                  , "chain": "woden_appear"
                }
              , "woden_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,8]
                      , "character_id":-10102
                      , "icon":"shadowG"
                      , "code": "woden"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %woden% 逃げ足の速いやつめ・・どこに行きおった"
                      , "LINES %woden% レイラ！おるか？？？レイラ！！！"
                      , "LINES %avatar% ん？？？"
                    ]
                  , "chain": "layla_appear"
                }
              , "layla_appear": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -20103
                      , "pos": [2,7]
                      , "icon":"layla"
                      , "union": 1
                      , "code": "layla"
                      , "act_brain": "rest"
                    }
                  , "chain": "speak2"
                }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "AALGN %layla% %woden%"
                      , "LINES %layla% お呼びでしょうか。オーディン様。"
                      , "LINES %avatar% レ・・レイラさん・・？"
                      , "LINES %gebaru% レ・・レイラ・・"
                      , "LINES %woden% レイラ。ご苦労だったな・・。忍び込んでいたレジスタンスの者どもを一網打尽にできたわ。"
                      , "LINES %woden% これでレジスタンスは壊滅だ"
                      , "LINES %layla% はっ・・マルティーニ王のおおせのままに"
                      , "LINES %woden% ただ、二人ばかりネズミどもが逃げておる"
                      , "LINES %woden% あ奴らを何としても捕まえい！よいな！？"
                      , "LINES %layla% はっ・・"
                    ]
                  , "chain": "woden_exit"
                }
              , "woden_exit": {
                    "type": "unit_exit"
                  , "exit_target": "woden"
	                , "chain": "speak3"
                }
              , "speak3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんてことだ！レイラさんは逆スパイだったんだ！"
                      , "NOTIF ガタン！！"
                      , "LINES %avatar% し、しまった！！"
                      , "UALGN %layla% 3"
                      , "LINES %layla% 誰？？？"
                      , "UMOVE %layla% 222"
                      , "LINES %avatar% ・・しまった!気づかれた・・"
                      , "LINES %layla% あら、あなたたち・・今の会話を聞いたのね・・？"
                      , "LINES %layla% もう遅いわ。すでに討伐軍は最大規模で待ち構えていたのよ。今日でレジスタンスは全滅よ。"
                      , "LINES %avatar% レイラさん・・どうして・・"
                      , "LINES %layla% ごめんなさいね。私、マルティーニ王に魂を売ったの"
                      , "LINES %layla% スパイなんだから人を疑わなきゃだめよ・・"
                      , "LINES %layla% 仲がいいフリするくらい朝飯前なんだから。フフフ。"
                      , "LINES %gebaru% レイラ・・ウソだろう・・？"
                      , "LINES %layla% ・・・"
                      , "LINES %gebaru% そんな・・結婚するって・・約束したじゃないか・・"
                      , "LINES %layla% フッ・・あんたみたいな貧乏人、利用してただけよ。"
                      , "LINES %layla% ついでだからあなたたちも始末しなきゃね！！"
                      , "LINES %gebaru% や、やめろ！レイラ！"
                    ]
                  , "chain": "layla_exit"
                }
             , "layla_exit": {
                   "type": "unit_exit"
                 , "exit_target": "layla"
	               , "chain": "boss"
               }
              , "boss": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2, 3]
                      , "code": "lamia"
                      , "character_id":-10114
                      , "icon":"shadow2"
                      , "unit_class": "ExBrains"
                      , "act_brain": "ThroughGebaru"
          					  , "early_gimmick": "endspeak"
          					  , "trigger_gimmick": "endspeak2"
                      , "bgm": "bgm_bossbattle"
                    }
                }
              , "endspeak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% レイラさん・・どうして・・"
                      , "LINES %avatar% 全部･･嘘だったの？ボクとの会話も・・ゲバルさんとの関係も・・"
                      , "LINES %lamia% ・・・"
                      , "SPEAK %avatar% もじょ もう死んでるのだ・・"
                    ]
                }
              , "endspeak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% レイラ・・さん・・"
                      , "LINES %gebaru% 行こう・・マザーコンピューターを破壊しなければ・・！"
                      , "LINES %avatar% う・・うん・・。"
                      , "UMOVE %gebaru% 6888"
                      , "LINES %gebaru% ・・・"
                      , "LINES %gebaru% さよなら・・レイラ・・"
                    ]
  	              , "chain": "goto_next"
                }
              , "goto_next": {
                    "type": "goto"
                  , "room": "floor5"
                }
              , "lamp1": {
                    "pos": [1, 5]
                  , "ornament": "lamp2"
                }
              , "lamp1": {
                    "pos": [1, 5]
                  , "ornament": "lamp2"
                }
              , "lamp2": {
                    "pos": [3, 5]
                  , "ornament": "lamp2"
                }
              , "lamp3": {
                    "pos": [6, 0]
                  , "ornament": "lamp2"
                }
              , "lamp4": {
                    "pos": [10, 0]
                  , "ornament": "lamp2"
                }
              , "lamp5": {
                    "pos": [13, 0]
                  , "ornament": "lamp2"
                }
              , "lamp6": {
                    "pos": [16, 0]
                  , "ornament": "lamp2"
                }
              , "lamp6_2": {
                    "pos": [18, 0]
                  , "ornament": "lamp2"
                }
              , "lamp7": {
                    "pos": [20, 0]
                  , "ornament": "lamp2"
                }
              , "lamp8": {
                    "pos": [1, 15]
                  , "ornament": "lamp2"
                }
              , "lamp9": {
                    "pos": [13, 15]
                  , "ornament": "lamp2"
                }
              , "lamp10": {
                    "pos": [8, 11]
                  , "ornament": "lamp2"
                }
              , "lamp11": {
                    "pos": [10, 11]
                  , "ornament": "lamp2"
                }
              , "lamp12": {
                    "pos": [6, 17]
                  , "ornament": "lamp2"
                }
              , "lamp13": {
                    "pos": [8, 17]
                  , "ornament": "lamp2"
                }
              , "lamp14": {
                    "pos": [10, 17]
                  , "ornament": "lamp2"
                }
              , "lamp14_2": {
                    "pos": [12, 17]
                  , "ornament": "lamp2"
                }
              , "lamp15": {
                    "pos": [15, 13]
                  , "ornament": "lamp2"
                }
              , "lamp16": {
                    "pos": [20, 13]
                  , "ornament": "lamp2"
                }
              , "floor_1": {
                    "pos": [2, 3]
                  , "ornament": "floor"
                }
              , "floor_2": {
                    "pos": [2, 9]
                  , "ornament": "floor"
                }
              , "floor_3": {
                    "pos": [17, 3]
                  , "ornament": "floor"
                }
              , "floor_4": {
                    "pos": [18, 3]
                  , "ornament": "floor"
                }
              , "floor_5": {
                    "pos": [17, 4]
                  , "ornament": "floor"
                }
              , "floor_6": {
                    "pos": [18, 4]
                  , "ornament": "floor"
                }
              , "floor_7": {
                    "pos": [16, 8]
                  , "ornament": "floor"
                }
              , "floor_8": {
                    "pos": [17, 8]
                  , "ornament": "floor"
                }
              , "floor_9": {
                    "pos": [18, 8]
                  , "ornament": "floor"
                }
              , "floor_10": {
                    "pos": [16, 9]
                  , "ornament": "floor"
                }
              , "floor_11": {
                    "pos": [17, 9]
                  , "ornament": "floor"
                }
              , "floor_12": {
                    "pos": [18, 9]
                  , "ornament": "floor"
                }
              , "floor_13": {
                    "pos": [16, 10]
                  , "ornament": "floor"
                }
              , "floor_14": {
                    "pos": [17, 10]
                  , "ornament": "floor"
                }
              , "floor_15": {
                    "pos": [18, 10]
                  , "ornament": "floor"
                }
              , "floor_16": {
                    "pos": [2, 19]
                  , "ornament": "floor"
                }
              , "floor_17": {
                    "pos": [3, 19]
                  , "ornament": "floor"
                }
              , "floor_18": {
                    "pos": [2, 20]
                  , "ornament": "floor"
                }
              , "floor_19": {
                    "pos": [3, 20]
                  , "ornament": "floor"
                }
              , "floor_20": {
                    "pos": [15, 19]
                  , "ornament": "floor"
                }
              , "floor_21": {
                    "pos": [16, 19]
                  , "ornament": "floor"
                }
              , "floor_22": {
                    "pos": [15, 20]
                  , "ornament": "floor"
                }
              , "floor_23": {
                    "pos": [16, 20]
                  , "ornament": "floor"
                }
            }
          , "units": [
                {
                    "character_id": -20102
                  , "pos": [1,3]
                  , "icon":"man"
                  , "union": 1
                  , "code": "gebaru"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor5": {
            "id": 31031003
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [11,3]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 1"
                      , "UALGN %gebaru% 1"
                      , "LINES %gebaru% よし、ついたぞ。敵はまだ気づいてない。"
                      , "LINES %gebaru% しかしすぐに気づかれるだろう。"
                      , "UALGN %gebaru% 2"
                      , "LINES %gebaru% もう十分だ。君は役目を終えた。今すぐに脱出しろ。"
                      , "LINES %gebaru% そこは緊急用脱出ポットになってるんだ。"
                      , "LINES %avatar% え・・やだよ・・"
                      , "LINES %avatar% 最後までいる！！"
                      , "LINES %gebaru% 流刑地の廃棄物処理場に最後の仲間が残っている。"
                      , "LINES %gebaru% そこでマルティーニ朝が使っていた抜け道があるという・・"
                      , "LINES %avatar% ゲバルさん・・"
                      , "LINES %gebaru% 霊子力研究所だけは命に替えても破壊してみせる。最後の希望は君に託す・・。"
                      , "LINES %avatar% ゲバルさん、待って！最後に言いたいことが・・"
                      , "LINES %gebaru% あとは頼んだぞ。"
                      , "LINES %avatar% ゲバルさぁぁん！！！！！！！"
                    ]
                  , "chain": "goal"
                }
              , "lamp1": {
                    "pos": [6, 0]
                  , "ornament": "lamp2"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -20102
                  , "pos": [10,3]
                  , "icon":"man"
                  , "union": 1
                  , "code": "gebaru"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
