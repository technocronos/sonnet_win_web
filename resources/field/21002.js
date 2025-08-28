{
    "extra_uniticons": ["shadow", "shadow2", "elena"]
  , "start_units": [
        {
            "condition": {"cleared":false}
          , "character_id": -20101
          , "icon":"elena"
          , "union": 1
          , "code": "elena"
          , "act_brain": "guard"
          , "guard_unit": "avatar"
          , "items": [-1002, -1002, -1002, -1005, -1005, -1005, -3002, -3002, -3002, -3002, -3002, -3002, -3101, -3101, -3101, -3101]
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
            "id": 21002000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos_on": {
                "start": [19,13]
              , "next1": [1,1]
              , "next2": [1,3]
              , "next3": [1,9]
              , "next4": [1,13]
              , "next5": [1,19]
            }
          , "gimmicks": {

                "escape": {
                    "trigger": "hero"
                  , "pos": [20,13]
                  , "type": "escape"
                  , "ornament": "escape"
                }
              , "goal": {
                    "trigger": "hero"
                  , "pos": [20,19]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "goto2"
                  , "touch": "goal_comment"
                }

              , "next1": {
                    "trigger": "hero"
                  , "pos": [0,1]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "next2": {
                    "trigger": "hero"
                  , "pos": [0,3]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "next3": {
                    "trigger": "hero"
                  , "pos": [0,9]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "next4": {
                    "trigger": "hero"
                  , "pos": [0,13]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "next5": {
                    "trigger": "hero"
                  , "pos": [0,19]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %elena% エルフの里は下のほうの道よ"
                      , "LINES %avatar% え？道なんかある？"
                      , "LINES %elena% 実は通れる道があるの。この森にはこういうところがいくつかあるのよ"
                      , "LINES %avatar% へ～大変そう～。でもエレナが案内してくれるから、安心だね"
                    ]
                }
              , "dead_end": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "ignition": {"unit_exist":"elena"}
                  , "trigger": "hero"
                  , "pos": [18,17]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ここはどこが通れるの？"
                      , "LINES %elena% え？え～と…"
                      , "LINES %elena% あれ？おかしいな…こっちじゃなかったかしら…"
                      , "SPEAK %avatar% もじょ おまえ…まさか迷ってんのだ？"
                      , "LINES %elena% ん～…やっぱあっちの道だったかなぁ"
                      , "SPEAK %avatar% もじょ こいつ絶対迷ってんのだ"
                    ]
                  , "ornament": "curious"
                }
              , "doubt": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "ignition": {"unit_exist":"elena"}
                  , "trigger": "hero"
                  , "pos": [1,1], "rb": [5,1]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ ホントにこっちであってんのだ？"
                      , "LINES %elena% ウン。たぶん…"
                      , "SPEAK %avatar% もじょ ホントなのだ？さっき間違ってたのだ"
                      , "LINES %elena% 間違いないと思うんだけど…"
                      , "LINES %avatar% とりあえず行ってみようよ。もじょ"
                    ]
                }
              , "goal_comment": {
                    "condition": {"cleared":false}
                  , "ignition": {"unit_exist":"elena"}
                  , "type": "lead"
                  , "leads": [
                        "LINES %elena% あ！そこ！そこが入り口だよ！"
                      , "SPEAK %avatar% もじょ やっと着いたのだ…？"
                    ]
                }

              , "ap_recov2": {
                    "memory_shot": "ap_recov2"
                  , "trigger": "hero"
                  , "pos":[2,12]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }

              , "enemy1-1": {
                    "condition": {"reason":"start"}
                  , "trigger": "hero"
                  , "pos":[15,10], "rb":[19,11]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [19,5]
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "condition": {"reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [18,5]
                    }
                  , "chain": "enemy1-3"
                }
              , "enemy1-3": {
                    "condition": {"reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [15,1]
                    }
                }

              , "enemy2-1": {
                    "condition": {"reason":"start"}
                  , "trigger": "hero"
                  , "pos":[15,2], "rb":[19,4]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [13,1]
                    }
                }

              , "enemy3-1": {
                    "condition": {"reason":"start"}
                  , "trigger": "hero"
                  , "pos":[7,1], "rb":[11,3]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [14,1]
                    }
                  , "chain": "enemy3-2"
                }
              , "enemy3-2": {
                    "condition": {"reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [15,1]
                    }
                  , "chain": "enemy3-3"
                }
              , "enemy3-3": {
                    "condition": {"reason":"start"}
                  , "trigger": "hero"
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [5,1]
                    }
                  , "chain": "enemy3-2"
                }
              , "enemy3-4": {
                    "condition": {"reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [4,1]
                    }
                }

              , "enemy9-1": {
                    "condition": {"reason":"next4"}
                  , "trigger": "hero"
                  , "pos":[4,9], "rb":[5,11]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [1,13]
                    }
                  , "chain": "enemy9-2"
                }
              , "enemy9-2": {
                    "condition": {"reason":"next4"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10016
                      , "pos": [6,6]
                    }
                  , "chain": "enemy9-3"
                }
              , "enemy9-3": {
                    "condition": {"reason":"next4"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10016
                      , "pos": [7,6]
                    }
                }

              , "enemy10-1": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "trigger": "hero"
                  , "pos":[9,5], "rb":[10,7]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [11,9]
                    }
                  , "chain": "enemy10-2"
                }
              , "enemy10-2": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [3,5]
                    }
                  , "chain": "enemy10-3"
                }
              , "enemy10-3": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [4,5]
                    }
                }

              , "enemy11-1": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "trigger": "hero"
                  , "pos":[10,9], "rb":[11,11]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [7,13]
                    }
                  , "chain": "enemy11-2"
                }
              , "enemy11-2": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [8,13]
                    }
                  , "chain": "enemy11-3"
                }
              , "enemy11-3": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10016
                      , "pos": [7,5]
                    }
                  , "chain": "enemy11-4"
                }
              , "enemy11-4": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [4,17]
                    }
                  , "chain": "enemy11-5"
                }
              , "enemy11-5": {
                    "condition": {"reason":["next2", "next3", "next4"]}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [5,17]
                    }
                }

              , "enemy12-1": {
                    "condition": {"!reason":"start"}
                  , "trigger": "hero"
                  , "pos":[7,15], "rb":[8,19]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [12,13]
                    }
                  , "chain": "enemy12-2"
                }
              , "enemy12-2": {
                    "condition": {"!reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [13,13]
                    }
                  , "chain": "enemy12-3"
                }
              , "enemy12-3": {
                    "condition": {"!reason":"start"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10016
                      , "pos": [13,14]
                    }
                }

              , "enemy13": {
                    "condition": {"!reason":"start"}
                  , "trigger": "hero"
                  , "pos":[10,17], "rb":[12,19]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10017
                      , "pos": [16,19]
                      , "act_brain": "rest"
                      , "icon":"shadow2"
                    }
                }

              , "supply6": {
                    "memory_shot": "supply6"
                  , "trigger": "player"
                  , "pos": [8,11]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply7": {
                    "memory_shot": "supply7"
                  , "trigger": "player"
                  , "pos": [16,17]
                  , "type": "treasure"
                  , "item_id": 1005
                }

              , "treasure1": {
                    "one_shot": 210020001
                  , "trigger": "player"
                  , "pos": [5,3]
                  , "type": "treasure"
                  , "item_id": 12014
                  , "ornament": "twinkle"
                }
              , "secret_passage1": {
                    "trigger": "player"
                  , "pos": [5,3]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんだ、ココに道あるじゃん…"
                    ]
                  , "chain": "passage1-open"
                }
              , "passage1-open": {
                    "type": "square_change"
                  , "change_pos": [5,4]
                  , "change_tip": 5
                }

              , "treasure2": {
                    "one_shot": 210020002
                  , "trigger": "player"
                  , "pos": [5,17]
                  , "type": "treasure"
                  , "item_id": 14014
                  , "ornament": "twinkle"
                }
              , "secret_passage2": {
                    "trigger": "player"
                  , "pos": [5,17]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ん？ココ通れるな…"
                    ]
                  , "chain": "passage2-open"
                }
              , "passage2-open": {
                    "type": "square_change"
                  , "change_pos": [5,18]
                  , "change_tip": 5
                }
            }
          , "units": [
                {
                    "condition": {"reason":"start"}
                  , "character_id": -10014
                  , "pos": [15,8]
                }
              , {
                    "condition": {"reason":"next2"}
                  , "character_id": -10017
                  , "icon":"shadow2"
                  , "pos": [4,3]
                  , "act_brain": "rest"
                }
              , {
                    "condition": {"reason":"next5"}
                  , "character_id": -10017
                  , "icon":"shadow2"
                  , "pos": [4,19]
                  , "act_brain": "rest"
                }
            ]
        }

      , "room2": {
            "id": 21002001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos_on": {
                "next1": [18,1]
              , "next2": [18,3]
              , "next3": [18,9]
              , "next4": [18,13]
              , "next5": [18,19]
            }
          , "gimmicks": {

                "next1": {
                    "trigger": "hero"
                  , "pos": [19,1]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "next2": {
                    "trigger": "hero"
                  , "pos": [19,3]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "next3": {
                    "trigger": "hero"
                  , "pos": [19,9]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "next4": {
                    "trigger": "hero"
                  , "pos": [19,13]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "next5": {
                    "trigger": "hero"
                  , "pos": [19,19]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }

              , "irritating": {
                    "condition": {"cleared":false, "reason":"next1"}
                  , "ignition": {"unit_exist":"elena"}
                  , "trigger": "hero"
                  , "pos": [9,11], "rb": [12,11]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ あとどのくらいあるのだ？"
                      , "LINES %elena% えっと…ここからだと…というか、ここどこだっけ？"
                      , "SPEAK %avatar% もじょ おまえ、絶対テキトーに案内してるのだ！"
                      , "LINES %avatar% も～、さっきからウルサイ、もじょ"
                      , "LINES %avatar% 分からないものは分からないんだから責めちゃ可愛そうでしょ？"
                      , "LINES %elena% ゴメンね"
                      , "LINES %avatar% いーのいーの。このネコうるさいのだけがとりえだから"
                      , "SPEAK %avatar% もじょ なにがネコなのだ！"
                      , "LINES %avatar% あーウルサイ、ウルサイ"
                    ]
                }
              , "diverge": {
                    "condition": {"cleared":false, "reason":"next1"}
                  , "ignition": {"unit_exist":"elena"}
                  , "trigger": "hero"
                  , "pos": [1,14], "rb": [3,16]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ これはどっちなのだ？"
                      , "LINES %elena% ん～…どっちかな？"
                      , "SPEAK %avatar% もじょ お前が来た道思い出せばいいだけなのだ！"
                      , "LINES %avatar% もじょ怒んないでよしょーがないじゃん。じゃーさ…"
                      , "LINES %avatar% とってもスゴい精霊のもじょ君はどっちだと思うの？"
                      , "SPEAK %avatar% もじょ も、もじょはスゴい精霊なのだ？むむむむ…上なのだ。上に間違いないのだ！"
                    ]
                }
              , "dead_end2": {
                    "condition": {"cleared":false}
                  , "ignition": {"unit_exist":"elena"}
                  , "trigger": "hero"
                  , "pos": [1,1]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ ………"
                      , "LINES %avatar% もじょ先生、ココからはどう行けば…"
                      , "SPEAK %avatar% もじょ ……………"
                      , "LINES %avatar% 上も右も左も通れないようですが…"
                      , "SPEAK %avatar% もじょ …………………"
                      , "LINES %avatar% 分かった！ココからもじょ様のミラクルパワーでバビョーンとワープできるんだね！？"
                      , "SPEAK %avatar% もじょ う、うるさいのだ！いくら精霊でも知らないのだ！"
                      , "SPEAK %avatar% もじょ もじょはおまえの頭の上で寝てるから、さっさと里まで連れて行くのだ！"
                      , "LINES %avatar% (やっと黙ったか…)"
                    ]
                }

              , "ap_recov1": {
                    "memory_shot": "ap_recov1"
                  , "trigger": "hero"
                  , "pos":[3,1]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                }

              , "enemy4-1": {
                    "condition": {"reason":"next1"}
                  , "trigger": "hero"
                  , "pos":[9,2], "rb":[11,5]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [7,9]
                    }
                  , "chain": "enemy4-2"
                }
              , "enemy4-2": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [8,9]
                    }
                  , "chain": "enemy4-3"
                }
              , "enemy4-3": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [7,10]
                    }
                  , "chain": "enemy4-4"
                }
              , "enemy4-4": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [8,10]
                    }
                  , "chain": "enemy4-5"
                }
              , "enemy4-5": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [7,11]
                    }
                  , "chain": "enemy4-6"
                }
              , "enemy4-6": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10014
                      , "pos": [8,11]
                    }
                }

              , "enemy5-1": {
                    "condition": {"reason":"next1"}
                  , "trigger": "hero"
                  , "pos":[10,12], "rb":[12,13]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [2,15]
                    }
                  , "chain": "enemy5-2"
                }
              , "enemy5-2": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [3,15]
                    }
                  , "chain": "enemy5-3"
                }
              , "enemy5-3": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [4,15]
                    }
                }

              , "enemy6-1": {
                    "condition": {"reason":"next1"}
                  , "trigger": "hero"
                  , "pos":[1,12], "rb":[4,13]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [2,9]
                    }
                  , "chain": "enemy6-2"
                }
              , "enemy6-2": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [6,6]
                    }
                  , "chain": "enemy6-3"
                }
              , "enemy6-3": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [4,4]
                    }
                  , "chain": "enemy6-4"
                }
              , "enemy6-4": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [3,17]
                    }
                  , "chain": "enemy6-5"
                }
              , "enemy6-5": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [9,18]
                    }
                }

              , "enemy7-ignitter": {
                    "trigger": "hero"
                  , "pos":[1,1], "rb":[6,11]
                  , "type": "call"
                  , "call": "igniteEnemy7"
                  , "lasting": 9999
                  , "always": true
                }
              , "enemy7": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [9,18]
                    }
                  , "lasting": 9999
                }

              , "enemy8-1": {
                    "condition": {"reason":"next1"}
                  , "trigger": "hero"
                  , "pos":[8,17], "rb":[10,19]
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [14,15]
                    }
                  , "chain": "enemy8-2"
                }
              , "enemy8-2": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [15,15]
                    }
                  , "chain": "enemy8-3"
                }
              , "enemy8-3": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [14,19]
                    }
                  , "chain": "enemy8-4"
                }
              , "enemy8-4": {
                    "condition": {"reason":"next1"}
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10015
                      , "pos": [15,19]
                    }
                }

              , "supply1": {
                    "memory_shot": "supply1"
                  , "trigger": "player"
                  , "pos": [7,9]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply2": {
                    "memory_shot": "supply2"
                  , "trigger": "player"
                  , "pos": [7,10]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply3": {
                    "memory_shot": "supply3"
                  , "trigger": "player"
                  , "pos": [3,17]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply4": {
                    "memory_shot": "supply4"
                  , "trigger": "player"
                  , "pos": [18,5]
                  , "type": "treasure"
                  , "item_id": 1005
                }
              , "supply5": {
                    "memory_shot": "supply5"
                  , "trigger": "player"
                  , "pos": [13,9]
                  , "type": "treasure"
                  , "item_id": 1005
                }

              , "treasure3": {
                    "one_shot": 210020003
                  , "trigger": "player"
                  , "pos": [9,5]
                  , "type": "treasure"
                  , "gold": 800
                }
            }
          , "units": [
                {
                    "condition": {"reason":"next1"}
                  , "character_id": -10015
                  , "pos": [9,1]
                }
              , {
                    "condition": {"reason":"next3"}
                  , "character_id": -10015
                  , "pos": [17,3]
                }
              , {
                    "condition": {"reason":"next3"}
                  , "character_id": -10015
                  , "pos": [18,3]
                }
            ]
        }
    }
}
