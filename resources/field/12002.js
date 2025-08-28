{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 12002000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [1, 12]
          , "start_pos_on": {
                "goto_passage1_from_main":  [13, 8]
            }
          , "gimmicks": {
                "shortcut_from_start": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [1, 8]
                  , "type": "goto"
                  , "room": "main"
                  , "ornament": "goto2"
                }

              , "branch": {
                    "trigger": "hero"
                  , "pos":[0,3], "rb":[6,10]
                  , "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% おおー 結構奥ありそう。いきなり道分かれてるし"
                      , "SPEAK %avatar% もじょ 岩喰いミミズの穴に見えるのだけど…"
                      , "SPEAK %avatar% もじょ なんか誰かが掘ったあともあるのだ"
                    ]
                }
              , "torch1": {
                    "pos": [3, 7]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [10, 9]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [9, 2]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [13, 1]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [12, 10]
                  , "ornament": "fungi"
                }
              , "mission": {
                    "condition": {"mission":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ミッション\n(達成:+200マグナ)"
                      , "NOTIF ゴブリン家の石 ３つ\nすべて集めてゴール"
                    ]
                }
              , "passage_patrol": {
                    "trigger": "hero"
                  , "pos":[11,7], "rb":[14,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 8]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "find"
                }
              , "find": {
                    "condition": {"cleared":false}
                  , "memory_shot": "find_patrol"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわ 出た"
                      , "SPEAK %avatar% もじょ ホントどこにでもいるのだ"
                    ]
                }
              , "goto_main_from_passage1": {
                    "trigger": "hero"
                  , "pos": [14, 8]
                  , "type": "goto"
                  , "room": "main"
                  , "ornament": "goto"
                }
              , "depths": {
                    "trigger": "hero"
                  , "pos": [13, 0]
                  , "ornament": "goto"
                  , "one_shot": 120020201
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% う～ん…こっちは細い道がずっと続いてるな～"
                      , "LINES %avatar% 今回はやめとくか。また今度きてみよー"
                      , "SPEAK %avatar% もじょ モノ好きなのだ…"
                    ]
                }
              , "passage_patrol2": {
                    "trigger": "hero"
                  , "pos":[9,0], "rb":[14,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 1]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "find2"
                }
              , "find2": {
                    "condition": {"cleared":false}
                  , "memory_shot": "find_patrol"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわ 出た"
                      , "SPEAK %avatar% もじょ ホントどこにでもいるのだ"
                    ]
                }
              , "treasure5": {
                    "trigger": "player"
                  , "pos":[11,2]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 12004
                  , "one_shot": 120020201
                }
              , "treasure6": {
                    "trigger": "player"
                  , "pos":[13,9]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 120020202
                }
            }
        }
      , "main": {
            "id": 12002003
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [1,14]
          , "gimmicks": {
                "goto_passage1_from_main": {
                    "trigger": "hero"
                  , "pos": [0,14]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goto_backdoor_from_main": {
                    "trigger": "hero"
                  , "pos": [15,6]
                  , "type": "goto"
                  , "room": "backdoor"
                  , "ornament": "goto"
                }
              , "dark_curtain": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos":[2,12], "rb":[5,15]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% この奥よく見えないなぁ…"
                      , "SPEAK %avatar% もじょ 入ってみれば分かるのだ"
                    ]
                }
              , "throw_surprise": {
                    "condition": {"cleared":false}
                  , "memory_shot": "throw_surprise"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんか投げてきた！"
                      , "SPEAK %avatar% もじょ あいつら生意気なもの持ってるのだ…"
                      , "SPEAK %avatar% もじょ こっちも爆弾手に入れて投げ返してやるといいのだ"
                    ]
                }

              , "living_goblin1": {
                    "trigger": "curtain"
                  , "curtain": "living"
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "living_goblin2"
                }
              , "living_goblin2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 7]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "living_goblin3"
                }
              , "living_goblin3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 6]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                      , "trigger_gimmick": "memorial_treasure1"
                    }
                  , "chain": "open_living"
                }
              , "open_living": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% げ"
                      , "SPEAK %avatar% もじょ こ、ここはゴブリンの棲みかなのだ？"
                    ]
                }

              , "passage_goblin": {
                    "trigger": "player"
                  , "pos":[9,10], "rb":[11,12]
                  , "type": "unit"
                  , "unit": {
                        "pos":[11,10]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "passage_find"
                }
              , "passage_find": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% こんなとこにも"
                      , "SPEAK %avatar% もじょ な、なんかいっぱいいるのだ"
                    ]
                }

              , "close_goblin": {
                    "trigger": "player"
                  , "pos":[11,6], "rb":[13,9]
                  , "type": "unit"
                  , "unit": {
                        "pos":[14,6]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                      , "trigger_gimmick": "memorial_treasure2"
                    }
                }

              , "storage1_goblin": {
                    "trigger": "curtain"
                  , "curtain": "storage1"
                  , "type": "unit"
                  , "unit": {
                        "pos": [10,15]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                }

              , "storage2_goblin": {
                    "trigger": "curtain"
                  , "curtain": "storage2"
                  , "type": "unit"
                  , "unit": {
                        "code": "alerter"
                      , "pos": [8,4]
                      , "character_id":-1301
                      , "icon":"shadow2"
                    }
                  , "chain": ["close_encount", "helper_goblins1"]
                }
              , "close_encount": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %alerter% ゲダダ！！ウーゲゲ！！"
                    ]
                }

              , "helper_goblins1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,13]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "helper_goblins2"
                }
              , "helper_goblins2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1,13]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                  , "chain": "helper_goblins3"
                }
              , "helper_goblins3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,14]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                  , "chain": "find_helpers"
                }
              , "find_helpers": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% な、仲間を呼んだ？"
                      , "SPEAK %avatar% もじょ こ、これは…逃げたほうがいいかもなのだ"
                    ]
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[2,6]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 14003
                  , "one_shot": 120020301
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[9,16]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "gold": 170
                  , "one_shot": 120020302
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[10,3]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 120020303
                }
              , "memorial_treasure1": {
                    "type": "treasure"
                  , "item_id": 2001
                  , "one_shot": 120020304
                }
              , "memorial_treasure2": {
                    "type": "treasure"
                  , "item_id": 2001
                  , "one_shot": 120020305
                }

              , "mission_item1": {
                    "condition": {"mission":true}
                  , "trigger": "player"
                  , "pos":[6,7]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item1"
                  , "ornament": "twinkle"
                }
              , "mission_item2": {
                    "condition": {"mission":true}
                  , "trigger": "player"
                  , "pos":[8,5]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item2"
                  , "ornament": "twinkle"
                }
              , "torch1": {
                    "pos": [2, 11]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [5, 11]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [3, 7]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [1, 9]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [11, 12]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [5, 14]
                  , "ornament": "fungi"
                }
            }
        }

      , "backdoor": {
            "id": 12002004
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [0,4]
          , "gimmicks": {
                "goal": {
                    "trigger": "hero"
                  , "pos":[3,15], "rb":[4,15]
                  , "type": "goal"
                  , "ornament": "goto2"
                }

              , "open": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% な、なんか追ってきてる？"
                      , "SPEAK %avatar% もじょ たぶん逃げたほうがいいのだ"
                    ]
                }
              , "find_goal": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos":[3,9], "rb":[4,14]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あれ？外だ"
                      , "SPEAK %avatar% もじょ きっと外までは追ってこないのだ"
                      , "SPEAK %avatar% もじょ 外に逃げるのだ"
                    ]
                }
              , "torch3": {
                    "pos": [9, 8]
                  , "ornament": "torch"
                }

              , "open_water": {
                    "trigger": "curtain"
                  , "curtain": "water"
                  , "type": "unit"
                  , "unit": {
                        "pos": [8,7]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "find_water"
                }
              , "find_water": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ウェ～ンこっちにもいたー"
                      , "SPEAK %avatar% もじょ このバカモノなのだ…"
                    ]
                }
              , "treasure4": {
                    "trigger": "player"
                  , "pos":[7,6]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1201
                  , "one_shot": 120020404
                }
              , "treasure4-2": {
                    "trigger": "player"
                  , "pos":[9,10]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 120020405
                }
              , "chaser1": {
                    "trigger": "rotation"
                  , "rotation": 2
                  , "type": "unit"
                  , "unit": {
                        "pos": [0,4]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "find_chaser1"
                }
              , "find_chaser1": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ひー！追ってきたーー"
                      , "SPEAK %avatar% もじょ まだまだ追ってきてるのだ。とりあえず逃げるのだ"
                    ]
                }
              , "chaser2": {
                    "trigger": "rotation"
                  , "rotation": 3
                  , "type": "unit"
                  , "unit": {
                        "pos": [0,4]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                }
              , "chaser3": {
                    "trigger": "rotation"
                  , "rotation": 4
                  , "type": "unit"
                  , "unit": {
                        "pos": [1,4]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                  , "chain": "chaser4"
                }
              , "chaser4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [0,4]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                }
              , "fungi1": {
                    "pos": [3, 10]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [3, 11]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [9, 9]
                  , "ornament": "fungi"
                }
              , "mission_item3": {
                    "condition": {"mission":true}
                  , "trigger": "player"
                  , "pos":[8,10]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item3"
                  , "ornament": "twinkle"
                }
            }
        }
    }
}
