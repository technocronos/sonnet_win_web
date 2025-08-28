{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 12001000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "sphere_bg": "cloud"
          , "start_pos": [20,1]
          , "gimmicks": {

                "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "touch": "goal_comment"
                }

              , "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 来てはみたものの…"
                      , "SPEAK %avatar% もじょ 前に採ったばかりなのにもうなってるはずないのだ"
                      , "LINES %avatar% だよね～。一応見て回るか…"
                    ]
                }
              , "goal_comment": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% んーー！ないない！ないものはない!"
                      , "LINES %avatar% もういいや。適当に時間つぶして「なかったよ」って言えばＯＫ♪"
                      , "SPEAK %avatar% もじょ 師匠が師匠なら弟子も弟子なのだ…"
                      , "LINES %avatar% よーし、時間つぶしに洞窟行ってみよー♪"
                      , "SPEAK %avatar% もじょ あんなとこ行くのだ？ああいうとこは強いのがいるのだ…"
                    ]
                }

              , "check1": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [17,2]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ん～…ないな"
                    ]
                }
              , "check2": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [11,3]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 葉っぱしかないのだ"
                    ]
                }
              , "check3": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [4,3]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% はいはい。なしなし"
                    ]
                  , "memory_on": "check3_pass"
                  , "chain": "treasure2_find"
                }
              , "check4": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [15,13]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、ちょっぴりできてる！でも、これだけじゃね～…"
                      , "LINES %avatar% いーや。食べちゃお。ん、おいし"
                      , "SPEAK %avatar% もじょ あ、食っちゃったのだ…"
                      , "LINES %avatar% いーのいーの。こんなチョッピリ持ってったってお金になんないし"
                      , "SPEAK %avatar% もじょ …もじょにはないのだ？"
                      , "LINES %avatar% …食べちゃった"
                    ]
                }
              , "check5": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [9,10]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ すっぱそーーーなのしかないのだ"
                    ]
                }
              , "check6": {
                    "x_check": true
                  , "trigger": "hero"
                  , "pos": [3,15]
                  , "ornament": "curious"
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% あれ、あんなところに洞窟があるよ。もじょ知ってた？"
                      , "SPEAK %avatar% もじょ もじょは西の森よく知らないのだ"
                      , "LINES %avatar% あとで行ってみようかな…"
                    ]
                }

              , "enemy3-1": {
                    "trigger": "player"
                  , "pos":[5,3], "rb":[9,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1,4]
                      , "character_id":-1201
                      , "icon":"shadow"
                    }
                }
              , "enemy3-2": {
                    "trigger": "player"
                  , "pos":[5,3], "rb":[9,3]
                  , "ignition": {"has_memory":"check3_pass"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [1,4]
                      , "character_id":-1201
                      , "icon":"shadow"
                    }
                  , "chain": "find1"
                }
               , "find1": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 1"
                       , "LINES %avatar% わ、ここにも・・"
                       , "SPEAK %avatar% もじょ 何かあいつ・・どっから出て来たのだ？"
                       , "LINES %avatar% 何か藪からいきなり出て来た"
                       , "SPEAK %avatar% もじょ あそこ、アヤシイのだ・・"
                     ]
                 }
              , "enemy4-1": {
                    "trigger": "player"
                  , "pos":[10,3], "rb":[12,5]
                  , "ignition": {"has_memory":"check3_pass"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [16,8]
                      , "character_id":-1201
                      , "icon":"shadow"
                    }
                }
              , "enemy4-2": {
                    "trigger": "player"
                  , "pos":[16,10], "rb":[16,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [20,9]
                      , "character_id":-10003
                      , "icon":"shadow2"
                    }
                }
              , "enemy6": {
                    "trigger": "player"
                  , "pos":[5,13], "rb":[13,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9,12]
                      , "character_id":-10003
                      , "icon":"shadow2"
                      , "act_brain":"rest"
                    }
                }
              , "enemy7-1": {
                    "trigger": "player"
                  , "pos":[1,5], "rb":[8,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2,13]
                      , "character_id":-10003
                      , "icon":"shadow2"
                    }
                  , "chain": "enemy7-2"
                }
              , "enemy7-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [16,13]
                      , "character_id":-1201
                      , "icon":"shadow"
                    }
                }

              , "supply": {
                    "trigger": "all"
                  , "pos":[11,14]
                  , "type": "hp_recov"
                  , "lasting": 999
                  , "always": true
                  , "ornament": "hp_circle"
                }

              , "treasure1": {
                    "trigger": "player"
                  , "pos":[2,9]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 120010001
                }
              , "treasure2_find": {
                    "condition": {"yet_flag":120010002}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ん？なんか見っけ"
                    ]
                  , "chain": "treasure2"
                }
              , "treasure2": {
                    "type": "treasure"
                  , "item_id": 13002
                  , "one_shot": 120010002
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[13,1]
                  , "type": "treasure"
                  , "gold": 70
                  , "one_shot": 120010003
                  , "ornament": "twinkle"
                }
              , "treasure4": {
                    "trigger": "player"
                  , "pos":[11,9]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 120010004
                  , "ornament": "twinkle"
                }
            }
          , "units": [
                {
                    "pos": [13,1]
                  , "character_id":-1201
                  , "icon":"shadow"
                }
              , {
                    "pos": [20,9]
                  , "character_id":-1201
                  , "icon":"shadow"
                }
            ]
        }
    }
}
