{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "man", "boy"]
  , "extra_maptips": [2757,2758,2759]
   ,"rooms": {
        "start": {
            "id": 98012000
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [11, 16]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ようやくここまでこれたね"
                      , "SPEAK %avatar% もじょ とりあえず最上階まで行くのだ"
                      , "SPEAK %avatar% もじょ 多分今回の件で糸を引いている奴がいるのだ"
                    ]
                }
              , "gate1": {
                    "pos": [6, 15]
                  , "ornament": "gate"
                }
              , "unlock1": {
                    "trigger": "hero"
                  , "pos": [6,16], "rb": [7,16]
                }
              , "gate2": {
                    "pos": [6, 11]
                  , "ornament": "gate"
                }
              , "unlock2": {
                    "trigger": "hero"
                  , "pos": [6,12], "rb": [7,12]
                }
              , "gate3": {
                    "pos": [2, 7]
                  , "ornament": "gate"
                }
              , "unlock3": {
                    "trigger": "hero"
                  , "pos": [3,14]
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [9,3]
                  , "type": "goto"
                  , "room": "room1"
                  , "ornament": "goto"  
                }
              , "box0-1": {
                    "pos": [1, 16]
                  , "ornament": "box1"
                }
              , "box0-2": {
                    "pos": [2, 16]
                  , "ornament": "box1"
                }
              , "dirt0-1": {
                    "pos": [8, 16]
                  , "ornament": "dirt1"
                }
              , "dirt0-2": {
                    "pos": [10, 13]
                  , "ornament": "dirt1"
                }
              , "dirt0-3": {
                    "pos": [2, 9]
                  , "ornament": "dirt1"
                }
              , "dirt0-4": {
                    "pos": [4, 4]
                  , "ornament": "dirt1"
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [11,9]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980120001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [11,13]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980120002
                }
            }
          , "units": [
                {
                    "pos": [8,13]
                  , "character_id":-9116
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [8,8]
                  , "character_id":-9116
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
              , {
                    "pos": [1,13]
                  , "character_id":-9116
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [4,3]
                  , "character_id":-9117
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "room1": {
            "id": 98012001
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [11, 3]
          , "gimmicks": {
                "gate4": {
                    "pos": [6, 6]
                  , "ornament": "gate"
                }
              , "unlock4": {
                    "trigger": "hero"
                  , "pos": [6,7], "rb": [7,7]
                }
              , "gate5": {
                    "pos": [2, 11]
                  , "ornament": "gate"
                }
              , "unlock5": {
                    "trigger": "hero"
                  , "pos": [2,3], "rb": [3,4]
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [10,14]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "dirt1-1": {
                    "pos": [8, 16]
                  , "ornament": "dirt1"
                }
              , "dirt1-2": {
                    "pos": [10, 13]
                  , "ornament": "dirt1"
                }
              , "dirt1-3": {
                    "pos": [2, 9]
                  , "ornament": "dirt1"
                }
              , "dirt1-4": {
                    "pos": [4, 4]
                  , "ornament": "dirt1"
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [8,4]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980120011
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [4,13]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [11,12]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980120012
                }
            }
          , "units": [
                {
                    "pos": [5,7]
                  , "character_id":-9117
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [5,3]
                  , "character_id":-9117
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [2,14]
                  , "character_id":-9117
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [7,15]
                  , "character_id":-9118
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "room2": {
            "id": 98012002
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [10, 12]
          , "gimmicks": {
                "gate6": {
                    "pos": [2, 10]
                  , "ornament": "gate"
                }
              , "unlock6": {
                    "trigger": "hero"
                  , "pos": [2,11], "rb": [3,11]
                }
              , "gate7": {
                    "pos": [7, 6]
                  , "ornament": "gate"
                }
              , "unlock7": {
                    "trigger": "hero"
                  , "pos": [8,3], "rb": [9,4]
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [5,8]
                  , "type": "goto"
                  , "room": "room3"
                  , "ornament": "goto"
                }
              , "box2-1": {
                    "pos": [11, 3]
                  , "ornament": "box1"
                }
              , "box2-2": {
                    "pos": [11, 4]
                  , "ornament": "box1"
                }
              , "dirt1-1": {
                    "pos": [8, 11]
                  , "ornament": "dirt1"
                }
              , "dirt1-2": {
                    "pos": [10, 13]
                  , "ornament": "dirt1"
                }
              , "dirt1-3": {
                    "pos": [2, 11]
                  , "ornament": "dirt1"
                }
              , "dirt1-4": {
                    "pos": [4, 10]
                  , "ornament": "dirt1"
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [2,2]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980120021
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [1,13]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [11,8]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980120022
                }
              , "enemy1": {
                    "trigger": "hero"
                  , "pos": [10,7], "rb": [11,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 7]
                      , "character_id":-9119
                      , "icon":"shadow2"
                    }
                }
            }
          , "units": [
                {
                    "pos": [2,12]
                  , "character_id":-9118
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [2,4]
                  , "character_id":-9118
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "222888"
                  , "excurse_step": 3
                }
              , {
                    "pos": [6,4]
                  , "character_id":-9119
                  , "icon":"shadow2"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "room3": {
            "id": 98012003
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [6, 7]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ここが最上階か・・"
                      , "SPEAK %avatar% もじょ 誰かいるのだ"
                    ]
                   , "chain": "drama1"
                }
              , "drama1": {
                    "type": "drama"
                  , "drama_id": 9801201
                }
               , "endspeak": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 0"
                       , "LINES %avatar% はやく二人の対決を止めなきゃ！"
                       , "SPEAK %avatar% もじょ 急ぐのだ！"
                     ]
                   , "chain": "goto_lastbattle"
                 }
              , "goto_lastbattle": {
                    "type": "goto"
                  , "room": "lastbattle"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "pos": [6,2]
                  , "character_id":-9120
                  , "code":"chonos"
                  , "icon":"boy"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                  , "trigger_gimmick": "endspeak"
                }
            ]
        }
      , "lastbattle": {
            "id": 98012004
          , "battle_bg": "forest2"
          , "environment": "grass"
          , "sphere_bg": "cloud"
          , "start_pos": [7, 13]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UALGN %mars% 2"
                      , "UALGN %reon% 1"
                      , "LINES %avatar% ハアハア・・二人はどこ？"
                      , "SPEAK %avatar% もじょ あすこにいたのだ！"
                      , "LINES %mars% さあ、追い詰めたぞ・・貴様はここまでだ"
                      , "LINES %mars% 覚悟しろ！"
                      , "LINES %reon% フフフ、人間の科学の力とは大したものだな。ここまで我を追い詰めるとは・・。"
                      , "LINES %mars% 私も同じだよ・・。亜人の力は大したものだ。さあ、最後の決着だ。"
                      , "LINES %avatar% まって！対決しちゃ駄目！"
                    ]
                   , "chain": "herorest"
                }
               , "herorest": {
                     "type": "property"
                   , "unit": "avatar"
                   , "change": {
                        "act_brain": "rest"
                     }
                 }
               , "mars_escape": {
                     "type": "lead"
                   , "leads": [
                          "LINES %mars% ぐはっ・・！"
                        , "LINES %reon% 勝った！我の勝ちだ！亜人の勝利だ！！！"
                     ]
                 }
               , "end_speak2": {
                     "type": "lead"
                   , "leads": [
                          "UALGN %avatar% 3"
                        , "LINES %avatar% ま・・負けた・・"
                        , "LINES %avatar% そんな馬鹿な・・"
                        , "SPEAK %avatar% もじょ 歴史がねじ曲がるのだ・・"
                        , "NOTIF ゲームが停止されます・・"
                        , "SPEAK %avatar% もじょ 目的が達成されたからゲーム自体が停止されるのだ"
                        , "LINES %avatar% 現実に・・引き戻される・・！"
                        , "LINES %avatar% うわぁぁぁ！！！！！"
                     ]
                   , "chain": "goal"
                 }
              , "watar_fall1": {
                    "pos": [9, 15]
                  , "ornament": "watar_fall"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "pos": [5,7]
                  , "character_id":-9121
                  , "code":"mars"
                  , "icon":"man"
                  , "act_brain": "manual"
                  , "union": 1
                  , "early_gimmick": "mars_escape"
                  , "trigger_gimmick": "end_speak2"
                 }
              , {
                    "pos": [8,7]
                  , "character_id":-9122
                  , "code":"reon"
                  , "icon":"shadowG"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
    }
}
