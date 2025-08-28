{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 41004000
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos": [8,11]
          , "gimmicks": {

                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% もじょ、少しは落ち着いた？"
                      , "SPEAK %avatar% もじょ と、取り乱してすまなかったのだ"
                      , "LINES %avatar% あれがさらった蜘蛛？なんかさっきより小さいね・・"
                      , "SPEAK %avatar% もじょ あれは子供の蜘蛛なのだ"
                      , "LINES %avatar% 蜘蛛の巣を作ってる・・"
                      , "SPEAK %avatar% もじょ あいつら蜘蛛の巣の上だけで動くのだ"
                      , "LINES %avatar% ボクたちは蜘蛛の巣の上はネバネバしてそんなに移動できないね"
                      , "SPEAK %avatar% もじょ あいつらが巣を作り終わる前に進むのだ"
                      , "LINES %avatar% 蜘蛛の巣やだよー"
                    ]
                }
              , "goto_area1": {
                    "trigger": "hero"
                  , "pos": [8,3]
                  , "type": "goto"
                  , "room": "area1"
                }
            }
          , "units": [
                {
                    "pos": [6,3]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [10,3]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [15,8]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area1": {
            "id": 41004001
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "goto_area1": [6,12]
              , "2to1_1": [13,1]
              , "2to1_2": [14,1]
              , "3to1_1": [1,1]
              , "3to1_2": [2,1]
              , "3to1_3": [3,1]
            }
          , "gimmicks": {
               "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あれ、なんだか涼しいね・・"
                      , "SPEAK %avatar% もじょ 地下だからかえって上より涼しいもんなのだ"
                    ]
                }
              , "1to2_1": {
                    "trigger": "hero"
                  , "pos": [13,0]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "1to2_2": {
                    "trigger": "hero"
                  , "pos": [14,0]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "1to3_1": {
                    "trigger": "hero"
                  , "pos": [1,0]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "1to3_2": {
                    "trigger": "hero"
                  , "pos": [2,0]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "1to3_3": {
                    "trigger": "hero"
                  , "pos": [3,0]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
            }
          , "units": [
                {
                    "pos": [5,5]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [3,2]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [9,4]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [13,2]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area2": {
            "id": 41004002
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "1to2_1": [2,12]
              , "1to2_2": [3,12]
              , "4to2_3": [13,7]
            }
          , "gimmicks": {

                "2to1_1": {
                    "trigger": "hero"
                  , "pos": [2,13]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "2to1_2": {
                    "trigger": "hero"
                  , "pos": [3,13]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "2to4_1": {
                    "trigger": "hero"
                  , "pos": [13,7]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
            }
          , "units": [
                {
                    "pos": [3,9]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [5,6]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [12,5]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area3": {
            "id": 41004003
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "1to3_1": [10,14]
              , "1to3_2": [11,14]
              , "1to3_3": [12,14]
              , "4to3_1": [15,1]
              , "4to3_2": [16,1]
              , "4to3_3": [17,1]
              , "5to3_1": [3,1]
              , "5to3_2": [4,1]
            }
          , "gimmicks": {

                "3to1_1": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "3to1_2": {
                    "trigger": "hero"
                  , "pos": [11,15]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "3to1_3": {
                    "trigger": "hero"
                  , "pos": [12,15]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "3to4_1": {
                    "trigger": "hero"
                  , "pos": [15,0]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "3to4_2": {
                    "trigger": "hero"
                  , "pos": [16,0]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "3to4_3": {
                    "trigger": "hero"
                  , "pos": [17,0]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "3to5_1": {
                    "trigger": "hero"
                  , "pos": [3,0]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }
              , "3to5_2": {
                    "trigger": "hero"
                  , "pos": [4,0]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[3, 7]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1002
                }
            }
          , "units": [
                {
                    "pos": [10,9]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [14,8]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [4,7]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [15,4]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area4": {
            "id": 41004004
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "3to4_1": [3,10]
              , "3to4_2": [4,10]
              , "3to4_3": [5,10]
              , "2to4_1": [4,3]
            }
          , "gimmicks": {

                "4to3_1": {
                    "trigger": "hero"
                  , "pos": [3,11]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "4to3_2": {
                    "trigger": "hero"
                  , "pos": [4,11]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "4to3_3": {
                    "trigger": "hero"
                  , "pos": [5,11]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "4to2_3": {
                    "trigger": "hero"
                  , "pos": [4,3]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[1, 1]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1002
                }
            }
          , "units": [
                {
                    "pos": [5,7]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [3,5]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area5": {
            "id": 41004005
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "3to5_1": [10,14]
              , "3to5_2": [11,14]
              , "6to5_1": [15,1]
              , "6to5_2": [16,1]
            }
          , "gimmicks": {

                 "5to3_1": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "5to3_2": {
                    "trigger": "hero"
                  , "pos": [11,15]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "5to6_1": {
                    "trigger": "hero"
                  , "pos": [15,0]
                  , "type": "goto"
                  , "room": "area6"
                  , "ornament": "goto"
                }
              , "5to6_2": {
                    "trigger": "hero"
                  , "pos": [16,0]
                  , "type": "goto"
                  , "room": "area6"
                  , "ornament": "goto"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[3, 7]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1002
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[3, 8]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1902
                  , "one_shot": 410040001
                }
            }
          , "units": [
                {
                    "pos": [11,8]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [7,8]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [4,7]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
              , {
                    "pos": [15,3]
                  , "character_id":-10053
                  , "icon":"shadow"
                  , "unit_class": "41004Spider"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "area6": {
            "id": 41004006
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos_on": {
                "5to6_1": [3,8]
              , "5to6_2": [4,8]
            }
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、あれが親蜘蛛？"
                      , "SPEAK %avatar% もじょ そうに違いないのだ。やっつけて脱出なのだ！"
                      , "LINES %avatar% で・・でか・・"
                    ]
                }
              , "endspeak": {
                    "type": "drama"
                  , "drama_id": 4100401
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              ,  "6to5_1": {
                    "trigger": "hero"
                  , "pos": [3,9]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }
              , "6to5_2": {
                    "trigger": "hero"
                  , "pos": [4,9]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }
            }
          , "units": [
                {
                    "pos": [4,4]
                  , "character_id":-10051
                  , "icon":"shadow2"
                  , "act_brain": "rest"
                  , "trigger_gimmick": "endspeak"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
    }
}
