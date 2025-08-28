{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 11009000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [26,3]
          , "gimmicks": {

                "goal": {
                    "trigger": "hero"
                  , "pos": [0,9]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "curious"
                  , "touch": "goal_comment"
                }

              , "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "APDSW 0"
                      , "LINES %avatar% いつもシショーといっしょに行ってたから、ひとりで行くのは初めてだな～"
                      , "SPEAK %avatar% もじょ 西の森近いから、強いやつも出てくるのだ"
                      , "SPEAK %avatar% もじょ くすりびんもってないなら出直せなのだ"
                      , "APDSW 1"
                    ]
                }
              , "goal_comment": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% とーちゃく♪やったね"
                    ]
                }

              , "treasure1": {
                    "trigger": "player"
                  , "pos":[13,9]
                  , "type": "treasure"
                  , "gold": 150
                  , "one_shot": 110090001
                  , "ornament": "twinkle"
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[1,2]
                  , "type": "treasure"
                  , "item_id": 14002
                  , "one_shot": 110090002
                  , "ornament": "twinkle"
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[8,9]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 110090003
                  , "ornament": "twinkle"
                }
              , "supply": {
                    "type": "treasure"
                  , "condition": {"cleared":false}
                  , "item_id": 1004
                  , "lasting": 999
                }

              , "enemy2-1": {
                    "trigger": "player"
                  , "pos":[14,1], "rb":[17,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13,9]
                      , "character_id":-10001
                      , "icon":"shadow"
                      , "trigger_gimmick": "supply"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14,9]
                      , "character_id":-10001
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-3"
                }
              , "enemy2-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15,9]
                      , "character_id":-10001
                      , "icon":"shadow"
                      , "trigger_gimmick": "supply"
                    }
                }

              , "enemy3-1": {
                    "trigger": "player"
                  , "pos":[8,1], "rb":[11,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3,7]
                      , "character_id":-1201
                      , "icon":"shadow2"
                    }
                }

              , "enemy4-1": {
                    "trigger": "player"
                  , "pos":[3,1], "rb":[7,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [0,9]
                      , "character_id":-1201
                      , "icon":"shadow2"
                    }
                }

              , "enemy5-1": {
                    "trigger": "player"
                  , "pos":[1,2], "rb":[5,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1,2]
                      , "character_id":-10002
                      , "icon":"shadow2"
                      , "act_brain": "rest"
                    }
                }
            }

          , "units": [
                {
                    "pos": [23,7]
                  , "character_id":-1101
                  , "icon":"shadow"
                }
              , {
                    "pos": [24,7]
                  , "character_id":-1101
                  , "icon":"shadow"
                }
            ]
        }
    }
}
