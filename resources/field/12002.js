{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 12002000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [6, 16]
          , "start_pos_on": {
                "goto_passage1_from_main":  [18, 12]
            }
          , "gimmicks": {
                "shortcut_from_start": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [6, 12]
                  , "type": "goto"
                  , "room": "main"
                  , "ornament": "goto2"
                }

              , "branch": {
                    "trigger": "hero"
                  , "pos":[5,7], "rb":[11,14]
                  , "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% おおー 結構奥ありそう。いきなり道分かれてるし"
                      , "SPEAK %avatar% もじょ 岩喰いミミズの穴に見えるのだけど…"
                      , "SPEAK %avatar% もじょ なんか誰かが掘ったあともあるのだ"
                    ]
                }
              , "torch1": {
                    "pos": [8, 11]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [15, 13]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [14, 6]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [18, 5]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [17, 14]
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
                  , "pos":[16,11], "rb":[19,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [18, 12]
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
                  , "pos": [19, 12]
                  , "type": "goto"
                  , "room": "main"
                  , "ornament": "goto"
                }
              , "depths": {
                    "trigger": "hero"
                  , "pos": [18, 4]
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
                  , "pos":[14,4], "rb":[19,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [18, 5]
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
                  , "pos":[16,6]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 12004
                  , "one_shot": 120020201
                }
              , "treasure6": {
                    "trigger": "player"
                  , "pos":[18,13]
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
          , "start_pos": [7,20]
          , "gimmicks": {
                "goto_passage1_from_main": {
                    "trigger": "hero"
                  , "pos": [6,20]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goto_backdoor_from_main": {
                    "trigger": "hero"
                  , "pos": [21,12]
                  , "type": "goto"
                  , "room": "backdoor"
                  , "ornament": "goto"
                }
              , "dark_curtain": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos":[8,18], "rb":[11,21]
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
                        "pos": [10, 12]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "living_goblin2"
                }
              , "living_goblin2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 13]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "living_goblin3"
                }
              , "living_goblin3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 12]
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
                  , "pos":[15,16], "rb":[17,18]
                  , "type": "unit"
                  , "unit": {
                        "pos":[17,16]
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
                  , "pos":[17,12], "rb":[19,15]
                  , "type": "unit"
                  , "unit": {
                        "pos":[20,12]
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
                        "pos": [16,21]
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
                      , "pos": [14,10]
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
                        "pos": [8,19]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                  , "chain": "helper_goblins2"
                }
              , "helper_goblins2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7,19]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                  , "chain": "helper_goblins3"
                }
              , "helper_goblins3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8,20]
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
                  , "pos":[8,12]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 14003
                  , "one_shot": 120020301
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[15,22]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "gold": 170
                  , "one_shot": 120020302
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[16,9]
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
                  , "pos":[12,13]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item1"
                  , "ornament": "twinkle"
                }
              , "mission_item2": {
                    "condition": {"mission":true}
                  , "trigger": "player"
                  , "pos":[14,11]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item2"
                  , "ornament": "twinkle"
                }
              , "torch1": {
                    "pos": [8, 17]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [11, 17]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [9, 13]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [7, 15]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [17, 18]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [11, 20]
                  , "ornament": "fungi"
                }
            }
        }

      , "backdoor": {
            "id": 12002004
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [6,10]
          , "gimmicks": {
                "goal": {
                    "trigger": "hero"
                  , "pos":[9,21], "rb":[10,21]
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
                  , "pos":[9,15], "rb":[10,20]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あれ？外だ"
                      , "SPEAK %avatar% もじょ きっと外までは追ってこないのだ"
                      , "SPEAK %avatar% もじょ 外に逃げるのだ"
                    ]
                }
              , "torch3": {
                    "pos": [15, 14]
                  , "ornament": "torch"
                }

              , "open_water": {
                    "trigger": "curtain"
                  , "curtain": "water"
                  , "type": "unit"
                  , "unit": {
                        "pos": [14,13]
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
                  , "pos":[13,12]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1201
                  , "one_shot": 120020404
                }
              , "treasure4-2": {
                    "trigger": "player"
                  , "pos":[15,16]
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
                        "pos": [6,10]
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
                        "pos": [6,10]
                      , "character_id":-1102
                      , "icon":"shadow"
                    }
                }
              , "chaser3": {
                    "trigger": "rotation"
                  , "rotation": 4
                  , "type": "unit"
                  , "unit": {
                        "pos": [7,10]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                  , "chain": "chaser4"
                }
              , "chaser4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,10]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow2"
                    }
                }
              , "fungi1": {
                    "pos": [9, 16]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [9, 17]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [15, 15]
                  , "ornament": "fungi"
                }
              , "mission_item3": {
                    "condition": {"mission":true}
                  , "trigger": "player"
                  , "pos":[14,16]
                  , "type": "x_mission"
                  , "memory_shot": "mission_item3"
                  , "ornament": "twinkle"
                }
            }
        }
    }
}
