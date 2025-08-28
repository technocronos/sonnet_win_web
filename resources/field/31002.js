{
    "extra_uniticons": ["shadow", "shadow2"]

  , "rooms": {
        "start": {
            "id": 31002000
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "start": [2,15]
              , "1to0": [9,9]
              , "4to0": [2,1]
            }
          , "gimmicks": {

                "escape": {
                    "trigger": "hero"
                  , "pos": [3,15]
                  , "type": "escape"
                  , "ornament": "escape"
                }
              , "0to1": {
                    "trigger": "hero"
                  , "pos": [10,9]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "0to4": {
                    "trigger": "hero"
                  , "pos": [2,0]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ この山のどこかに採石場が\nあるのだ？…で、もちろん道は聞いてきたのだ？"
                      , "LINES %avatar% もちろん♪聞いてないよ！"
                      , "SPEAK %avatar% もじょ …………まぁこの太い道たどっていけば着けそうなのだ"
                    ]
                }

              , "treasure1": {
                    "one_shot": 310020001
                  , "trigger": "player"
                  , "pos": [3,4]
                  , "type": "treasure"
                  , "item_id": 11015
                  , "ornament": "twinkle"
                }
            }
          , "units": [
                {
                    "condition": {"reason":"start"}
                  , "character_id": -10017
                  , "pos": [8,8]
                }
              , {
                    "condition": {"reason":"start"}
                  , "character_id": -10017
                  , "pos": [8,11]
                }

              , {
                    "condition": {"reason":"4to0"}
                  , "character_id": -10019
                  , "icon": "shadow2"
                  , "pos": [2,2]
                  , "act_brain": "rest"
                }
            ]
        }

      , "area1": {
            "id": 31002001
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "0to1": [1,6]
              , "3to1": [9,1]
              , "2to1": [23,1]
            }
          , "gimmicks": {

                "1to0": {
                    "trigger": "hero"
                  , "pos": [0,6]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "1to2": {
                    "trigger": "hero"
                  , "pos": [23,0]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "1to3": {
                    "trigger": "hero"
                  , "pos": [9,0]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }

              , "enemy1-1": {
                    "condition": {"!reason":"2to1"}
                  , "trigger": "player"
                  , "pos":[9,5], "rb":[13,7]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [22,1]
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "condition": {"!reason":"2to1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [23,1]
                    }
                  , "chain": "enemy1-3"
                }
              , "enemy1-3": {
                    "condition": {"!reason":"2to1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [1,6]
                    }
                }
            }
          , "units": [
                {
                    "condition": {"reason":"0to1"}
                  , "character_id": -10017
                  , "pos": [6,3]
                }
              , {
                    "condition": {"reason":"0to1"}
                  , "character_id": -10017
                  , "pos": [18,4]
                }
            ]
        }

      , "area2": {
            "id": 31002002
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "1to2": [10,14]
              , "6to2": [10,1]
              , "3to2": [1,7]
            }
          , "gimmicks": {
                "2to1": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "2to3": {
                    "trigger": "hero"
                  , "pos": [0,7]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "2to6": {
                    "trigger": "hero"
                  , "pos": [10,0]
                  , "type": "goto"
                  , "room": "area6"
                  , "ornament": "goto"
                }

              , "enemy1": {
                    "trigger": "player"
                  , "pos":[4,6], "rb":[8,9]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [3,3]
                    }
                }
              , "enemy2": {
                    "condition": {"!reason":"6to2"}
                  , "trigger": "player"
                  , "pos":[3,3], "rb":[5,4]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10019
                      , "pos": [10,1]
                      , "icon": "shadow2"
                      , "act_brain": "rest"
                    }
                }
            }
          , "units": [
                {
                    "character_id": -10017
                  , "pos": [5,3]
                }
            ]
        }

      , "area3": {
            "id": 31002003
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "2to3": [11,7]
              , "1to3": [9,14]
              , "4to3": [0,5]
            }
          , "gimmicks": {
                "3to2": {
                    "trigger": "hero"
                  , "pos": [12,7]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "3to1": {
                    "trigger": "hero"
                  , "pos": [9,15]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "3to4": {
                    "trigger": "hero"
                  , "pos": [0,5]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }

              , "ap_recov1": {
                    "memory_shot": "ap_recov1"
                  , "trigger": "hero"
                  , "pos":[8,12]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }

              , "enemy1": {
                    "trigger": "player"
                  , "pos":[3,5], "rb":[7,7]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [4,10]
                    }
                }
            }
          , "units": [
                {
                    "character_id": -10017
                  , "pos": [4,10]
                }
              , {
                    "character_id": -10017
                  , "pos": [6,6]
                }
            ]
        }

      , "area4": {
            "id": 31002004
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "3to4": [9,15]
              , "0to4": [2,24]
              , "8to4-1": [1,7]
              , "8to4-2": [1,15]
              , "5to4": [9,3]
            }
          , "gimmicks": {
                "4to3": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "area3"
                  , "ornament": "goto"
                }
              , "4to0": {
                    "trigger": "hero"
                  , "pos": [2,25]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "4to8-1": {
                    "trigger": "hero"
                  , "pos": [0,7]
                  , "type": "goto"
                  , "room": "area8"
                  , "ornament": "goto"
                }
              , "4to8-2": {
                    "trigger": "hero"
                  , "pos": [0,15]
                  , "type": "goto"
                  , "room": "area8"
                  , "ornament": "goto"
                }
              , "4to5": {
                    "trigger": "hero"
                  , "pos": [10,3]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }

              , "enemy1": {
                    "condition": {"!reason":"8to4-2"}
                  , "trigger": "player"
                  , "pos":[3,6], "rb":[7,9]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [6,3]
                    }
                }


              , "ap_recov2": {
                    "memory_shot": "ap_recov2"
                  , "trigger": "hero"
                  , "pos":[8,20]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }
              , "enemy2-1": {
                    "condition": {"reason":"8to4-2"}
                  , "trigger": "player"
                  , "pos":[6,19], "rb":[8,20]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [2,18]
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "condition": {"reason":"8to4-2"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [3,24]
                    }
                }
            }
          , "units": [
                {
                    "condition": {"!reason":"8to4-2"}
                  , "character_id": -10017
                  , "pos": [6,8]
                }
              , {
                    "condition": {"!reason":"8to4-2"}
                  , "character_id": -10017
                  , "pos": [6,9]
                }
            ]
        }

      , "area5": {
            "id": 31002005
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "4to5": [1,3]
              , "7to5": [8,1]
              , "6to5": [11,5]
            }
          , "gimmicks": {
                "5to4": {
                    "trigger": "hero"
                  , "pos": [0,3]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "5to7": {
                    "trigger": "hero"
                  , "pos": [8,0]
                  , "type": "goto"
                  , "room": "area7"
                  , "ornament": "goto"
                }
              , "5to6": {
                    "trigger": "hero"
                  , "pos": [12,5]
                  , "type": "goto"
                  , "room": "area6"
                  , "ornament": "goto"
                }

              , "enemy1": {
                    "trigger": "player"
                  , "pos":[5,2], "rb":[8,5]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [2,2]
                    }
                }
            }
          , "units": [
                {
                    "character_id": -10018
                  , "pos": [4,2]
                }
              , {
                    "character_id": -10018
                  , "pos": [9,6]
                }
            ]
        }

      , "area6": {
            "id": 31002006
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "2to6": [10,14]
              , "5to6": [1,11]
            }
          , "gimmicks": {
                "goal": {
                    "trigger": "hero"
                  , "pos": [9,0]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "curious"
                  , "touch": "goal_comment"
                }
              , "6to2": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "6to5": {
                    "trigger": "hero"
                  , "pos": [0,11]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }

              , "goal_comment": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ついた！ここだね"
                      , "SPEAK %avatar% もじょ そうみたいなのだ。火の玉がたくさん出るらしいからちゃんと準備してから行くのだ"
                    ]
                }
            }
          , "units": [
                {
                    "character_id": -10018
                  , "pos": [6,5]
                }
              , {
                    "character_id": -10018
                  , "pos": [7,3]
                }
              , {
                    "character_id": -10018
                  , "pos": [10,2]
                }
              , {
                    "condition": {"reason":"2to6"}
                  , "character_id": -10019
                  , "icon": "shadow2"
                  , "pos": [7,10]
                  , "act_brain": "rest"
                }
            ]
        }

      , "area7": {
            "id": 31002007
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "5to7": [8,13]
              , "9to7": [1,10]
              , "10to7": [1,2]
            }
          , "gimmicks": {
                "7to5": {
                    "trigger": "hero"
                  , "pos": [8,14]
                  , "type": "goto"
                  , "room": "area5"
                  , "ornament": "goto"
                }
              , "7to9": {
                    "trigger": "hero"
                  , "pos": [0,10]
                  , "type": "goto"
                  , "room": "area9"
                  , "ornament": "goto"
                }
              , "7to10": {
                    "trigger": "hero"
                  , "pos": [0,2]
                  , "type": "goto"
                  , "room": "area10"
                  , "ornament": "goto"
                }

              , "enemy1-1": {
                    "trigger": "player"
                  , "pos":[4,6]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [1,2]
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [1,10]
                    }
                }

              , "treasure2": {
                    "one_shot": 310020002
                  , "trigger": "player"
                  , "pos": [10,7]
                  , "type": "treasure"
                  , "item_id": 13015
                  , "ornament": "twinkle"
                }
              , "escape": {
                    "trigger": "player"
                  , "pos": [11,6]
                  , "type": "escape"
                  , "ornament": "escape"
                }

              , "ap_recov4": {
                    "memory_shot": "ap_recov4"
                  , "trigger": "hero"
                  , "pos":[4,6]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }
              , "supply1": {
                    "memory_shot": "supply1"
                  , "trigger": "player"
                  , "pos": [4,4]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply2": {
                    "memory_shot": "supply2"
                  , "trigger": "player"
                  , "pos": [4,5]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply3": {
                    "memory_shot": "supply3"
                  , "trigger": "player"
                  , "pos": [4,7]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply4": {
                    "memory_shot": "supply4"
                  , "trigger": "player"
                  , "pos": [4,8]
                  , "type": "treasure"
                  , "item_id": 1005
                }
            }
          , "units": [
                {
                    "condition": {"reason":"5to7"}
                  , "character_id": -10019
                  , "icon": "shadow2"
                  , "pos": [9,9]
                  , "act_brain": "rest"
                }
            ]
        }

      , "area8": {
            "id": 31002008
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "4to8-1": [11,7]
              , "4to8-2": [11,15]
              , "9to8": [2,1]
            }
          , "gimmicks": {
                "8to4-1": {
                    "trigger": "hero"
                  , "pos": [12,7]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "8to4-2": {
                    "trigger": "hero"
                  , "pos": [12,15]
                  , "type": "goto"
                  , "room": "area4"
                  , "ornament": "goto"
                }
              , "8to9": {
                    "trigger": "hero"
                  , "pos": [2,0]
                  , "type": "goto"
                  , "room": "area9"
                  , "ornament": "goto"
                }

            }
          , "units": [
                {
                    "condition": {"!reason":"4to8-2"}
                  , "character_id": -10018
                  , "pos": [7,13]
                }
              , {
                    "character_id": -10018
                  , "pos": [3,4]
                }
            ]
        }

      , "area9": {
            "id": 31002009
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos_on": {
                "8to9": [2,6]
              , "7to9": [22,3]
              , "10to9": [11,1]
            }
          , "gimmicks": {
                "9to8": {
                    "trigger": "hero"
                  , "pos": [2,7]
                  , "type": "goto"
                  , "room": "area8"
                  , "ornament": "goto"
                }
              , "9to7": {
                    "trigger": "hero"
                  , "pos": [23,3]
                  , "type": "goto"
                  , "room": "area7"
                  , "ornament": "goto"
                }
              , "9to10": {
                    "trigger": "hero"
                  , "pos": [11,0]
                  , "type": "goto"
                  , "room": "area10"
                  , "ornament": "goto"
                }

              , "enemy1-1": {
                    "trigger": "player"
                  , "pos":[4,4], "rb":[6,5]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [11,1]
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10018
                      , "pos": [17,3]
                    }
                }
            }
          , "units": [
            ]
        }

      , "area10": {
            "id": 31002010
          , "battle_bg": "desert"
          , "environment": "grass"
          , "sphere_bg": "cloud"
          , "start_pos_on": {
                "9to10": [7,12]
              , "7to10": [18,10]
            }
          , "gimmicks": {
                "10to9": {
                    "trigger": "hero"
                  , "pos": [7,13]
                  , "type": "goto"
                  , "room": "area9"
                  , "ornament": "goto"
                }
              , "10to7": {
                    "trigger": "hero"
                  , "pos": [19,10]
                  , "type": "goto"
                  , "room": "area7"
                  , "ornament": "goto"
                }
              , "ap_recov3": {
                    "memory_shot": "ap_recov3"
                  , "trigger": "hero"
                  , "pos":[1,7]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }
              , "treasure3": {
                    "one_shot": 310020003
                  , "trigger": "player"
                  , "pos": [18,1]
                  , "type": "treasure"
                  , "item_id": 12015
                  , "ornament": "curious"
                  , "chain": "high_view"
                }
              , "high_view": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% うわぁいい眺め～"
                      , "SPEAK %avatar% もじょ 山登りしに来たんじゃないのだ"
                      , "LINES %avatar% あ、ここから採石場が見渡せるよ"
                      , "SPEAK %avatar% もじょ …でも、ここからじゃ降りられないのだ。素直に道を間違えたことを認めるのだ"
                    ]
                }
            }
          , "units": [
                {
                    "character_id": -10018
                  , "pos": [6,3]
                }
              , {
                    "character_id": -10018
                  , "pos": [12,10]
                }
              , {
                    "condition": {"reason":"7to10"}
                  , "character_id": -10018
                  , "pos": [16,1]
                }
              , {
                    "character_id": -10020
                  , "pos": [17,1]
                  , "icon": "shadow2"
                  , "act_brain": "rest"
                }
            ]
        }
    }
}
