{
    "extra_uniticons": ["shadow", "shadow2", "shadowB"]
  , "rooms": {
        "start": {
            "id": 13004000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos_on": {
                "start": [1,15]
              , "goto_start": [3,16]
            }
          , "gimmicks": {

                "goto_cage": {
                    "ignition": {"has_flag":130040004}
                  , "trigger": "hero"
                  , "pos": [3,17]
                  , "type": "goto"
                  , "room": "cage"
                  , "ornament": "goto"
                }

              , "cage_locked": {
                    "ignition": {"!has_flag":130040004}
                  , "trigger": "hero"
                  , "pos": [3,17]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あれ？南の柵に鍵がかかってる\n鍵とってこなきゃ…"
                      , "SPEAK %avatar% もじょ 鍵の場所知ってるのだ？"
                      , "LINES %avatar% たぶんあの納屋の中だよ"
                    ]
                  , "lasting": 9999
                }

              , "backdoor-find": {
                    "trigger": "player"
                  , "pos": [16,5]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% こっから出れるじゃん\nヨイショっと…"
                    ]
                  , "chain": "backdoor-open"
                  , "ornament": "curious"
                }
              , "backdoor-open": {
                    "type": "square_change"
                  , "change_pos": [16,6]
                  , "change_tip": 1702
                  , "chain": "backdoor_enemy-1"
                }
              , "backdoor_enemy-find": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あら…\nコンニチワ…"
                    ]
                }
              , "gate": {
                    "trigger": "player"
                  , "pos": [12,12]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% この柵、こっちから\n開けられるんだ"
                    ]
                  , "ornament": "curious"
                  , "chain": "gate-open"
                }
              , "gate-open": {
                    "type": "square_change"
                  , "change_pos": [12,13]
                  , "change_tip": 1702
                }

              , "enemy1-1": {
                    "trigger": "player"
                  , "pos": [1,1], "rb": [8,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3,2]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,3]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow"
                    }
                }
              , "enemy2-1": {
                    "trigger": "player"
                  , "pos": [7,1], "rb": [10,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13,2]
                      , "character_id":-10005
                      , "icon":"shadow2"
                      , "act_brain": "rest"
                      , "trigger_gimmick": "enemy2-2"
                    }
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [16,4]
                      , "character_id":-10005
                      , "icon":"shadow2"
                    }
                }
              , "backdoor_enemy-1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15,12]
                      , "character_id":-10004
                      , "icon":"shadow"
                    }
                  , "chain": "backdoor_enemy-2"
                }
              , "backdoor_enemy-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17,11]
                      , "character_id":-10004
                      , "icon":"shadow"
                    }
                  , "chain": "backdoor_enemy-find"
                }
              , "enemy4-1": {
                    "trigger": "player"
                  , "pos": [9,13], "rb": [12,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4,16]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow"
                    }
                  , "chain": "enemy4-2"
                }
              , "enemy4-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1,15]
                      , "character_id":-1301
                      , "items": [-2001]
                      , "icon":"shadow"
                    }
                  , "chain": "enemy4-3"
                }
              , "enemy4-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12,7]
                      , "character_id":-10005
                      , "icon":"shadow2"
                    }
                }

              , "cage_key": {
                    "trigger": "player"
                  , "pos": [15,1]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 柵の鍵見っけ♪"
                      , "SPEAK %avatar% もじょ これで、次からはすぐに柵の中に行けるのだ"
                      , "SPEAK %avatar% もじょ いったん出直してくすりびん補充したほうがいいのだ"
                    ]
                  , "ornament": "twinkle"
                  , "one_shot": 130040004
                }

              , "treasure1": {
                    "trigger": "player"
                  , "pos": [6,12]
                  , "type": "treasure"
                  , "gold": 200
                  , "ornament": "twinkle"
                  , "one_shot": 130040001
                }
              , "treasure1_trap": {
                    "trigger": "player"
                  , "pos": [6,11], "rb": [8,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [8,13]
                      , "character_id":-10004
                      , "icon":"shadow"
                    }
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos": [14,6]
                  , "type": "treasure"
                  , "item_id": 11003
                  , "ornament": "twinkle"
                  , "one_shot": 130040002
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos": [16,1]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 130040003
                }
              , "treasure5": {
                    "trigger": "player"
                  , "pos": [15,12]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 130040005
                }
            }
          , "units": [
                {
                    "pos": [1,7]
                  , "character_id":-10004
                  , "icon":"shadow"
                }
              , {
                    "pos": [9,16]
                  , "character_id":-10004
                  , "icon":"shadow"
                }
            ]
        }

      , "cage": {
            "id": 13004001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [3,1]
          , "gimmicks": {

                "goto_start": {
                    "trigger": "hero"
                  , "pos": [3,0]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goal": {
                    "trigger": "hero"
                  , "pos": [16,16]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "curious"
                  , "touch": "goal_comment"
                }

              , "goal_comment": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ！牛さん一匹残ってる！"
                      , "SPEAK %avatar% もじょ ワラの中に隠れてたのだ？頭いいのだ"
                      , "LINES %avatar% もう大丈夫だよ。ここにいたオオカミは全部やっつけたからね"
                      , "LINES %avatar% よし。あとはおじさんの息子さんを追わなきゃ"
                    ]
                }

              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [8,17]
                  , "type": "treasure"
                  , "item_id": 13003
                  , "ornament": "twinkle"
                  , "one_shot": 130040101
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [14,10]
                  , "type": "treasure"
                  , "gold": 200
                  , "ornament": "twinkle"
                  , "one_shot": 130040102
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [10,3]
                  , "type": "treasure"
                  , "item_id": 1907
                  , "ornament": "twinkle"
                  , "one_shot": 130040103
                  , "chain": "finddead"
                }
              , "finddead": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 人間の骨だ・・"
                      , "SPEAK %avatar% もじょ 食べられちゃったのだ・・"
                      , "LINES %avatar% なんかメッセージがあるね"
                      , "SPEAK %avatar% もじょ ダイイングメッセージなのだ？"
                      , "LINES %avatar% 「水辺に近寄るべからず」"
                      , "LINES %avatar% なんだこりゃ？"
                      , "SPEAK %avatar% もじょ よくわからんのだ"
                    ]
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [2,7]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 130040105
                }
              , "treasureX": {
                    "type": "treasure"
                  , "item_id": 12011
                  , "one_shot": 130040104
                }

              , "enemy1-1": {
                    "trigger": "player"
                  , "pos": [0,6], "rb": [6,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4,1]
                      , "character_id":-10004
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,12]
                      , "character_id":-10004
                      , "icon":"shadow"
                    }
                }
              , "enemy2-1": {
                    "trigger": "player"
                  , "pos": [4,13], "rb": [8,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10,11]
                      , "character_id":-10005
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4,9]
                      , "character_id":-10005
                      , "icon":"shadow"
                      , "trigger_gimmick": "supply"
                    }
                }
              , "enemy3-1": {
                    "trigger": "player"
                  , "pos": [5,16], "rb": [8,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7,10]
                      , "character_id":-2101
                      , "icon":"shadow2"
                    }
                }
              , "enemy4-1": {
                    "trigger": "player"
                  , "pos": [7,6], "rb": [13,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7,4]
                      , "character_id":-10005
                      , "icon":"shadow"
                      , "trigger_gimmick": "supply"
                    }
                  , "chain": "enemy4-2"
                }
              , "enemy4-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12,1]
                      , "character_id":-10005
                      , "icon":"shadow"
                    }
                  , "chain": "enemy4-3"
                }
              , "enemy4-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17,9]
                      , "character_id":-10005
                      , "icon":"shadow"
                      , "trigger_gimmick": "supply"
                    }
                }
              , "enemy5-1": {
                    "trigger": "player"
                  , "pos": [13,10], "rb": [17,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13,14]
                      , "character_id":-2101
                      , "icon":"shadow2"
                    }
                }
              , "enemyX": {
                    "trigger": "player"
                  , "pos": [13,2]
                  , "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "pos": [15,6]
                      , "character_id":-2301
                      , "icon":"shadowB"
                      , "trigger_gimmick": "treasureX"
                    }
                  , "chain": "enemyX-find"
                }
              , "enemyX-find": {
                    "type": "lead"
                  , "leads": [
                        "PFOCS 15 06"
                      , "NOTIF 水の中から、ただならぬ気配のオオカミが…"
                    ]
                }

              , "goodbye-digger": {
                    "trigger": "player"
                  , "pos": [1,5], "rb": [5,8]
                  , "ignition": {"unit_exist":"rock_digger"}
                  , "type": "lead"
                  , "leads": [
                        "FOCUS %rock_digger%"
                      , "NOTIF ロックディッガーは地面に潜っていった…"
                    ]
                  , "chain": "treasure_sweep"
                }
              , "treasure_sweep": {
                    "type": "property"
                  , "unit": "rock_digger"
                  , "change": {"trigger_gimmick":null}
                  , "chain": "dig-out"
                }
              , "dig-out": {
                    "type": "unit_exit"
                  , "exit_target": "rock_digger"
                }

              , "supply": {
                    "type": "treasure"
                  , "item_id": 1004
                  , "lasting": 9999
                }
              , "get_bomb": {
                    "type": "treasure"
                  , "item_id": 2001
                }
            }
          , "units": [
                {
                    "pos": [1,1]
                  , "character_id":-10006
                  , "icon":"shadow2"
                  , "act_brain": "rest"
                  , "code": "rock_digger"
                  , "trigger_gimmick": "get_bomb"
                }
            ]
        }
    }
}
