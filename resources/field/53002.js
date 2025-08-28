{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "shadowG"]
  , "rooms": {
        "start": {
            "id": 53002000
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [12,11]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% おおう・・ここが抜け穴か・・"
                      , "SPEAK %avatar% もじょ 道がせっまいのだ・・"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[2,10], "rb":[10,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 11]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.2"
                }
              , "enemy0.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [0, 11]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                }
              , "enemy0.3": {
                    "trigger": "hero"
                  , "pos":[0,2], "rb":[1,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 3]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.4"
                }
              , "enemy0.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 2]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                }
              , "enemy0.5": {
                    "trigger": "hero"
                  , "pos":[4,2], "rb":[8,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 2]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0.6"
                }
              , "enemy0.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 2]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [12,2], "rb":[12,3]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor1"
                }
              , "lamp1": {
                    "pos": [4, 8]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [8, 8]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [12, 0]
                  , "ornament": "lamp"
                }
            }
        }
      , "floor1": {
            "id": 53002001
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [0,2]
          , "gimmicks": {
                "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[0,2], "rb":[4,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 2]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 8]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[0,7], "rb":[7,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [11, 7]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 8]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy1.5": {
                    "trigger": "hero"
                  , "pos":[8,7], "rb":[13,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 8]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.6"
                }
              , "enemy1.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 8]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy1.7": {
                    "trigger": "hero"
                  , "pos":[14,7], "rb":[21,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 8]
                      , "character_id":-10091
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1.8"
                }
              , "enemy1.8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [13, 8]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [22,9]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [10, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [15, 0]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [20, 0]
                  , "ornament": "lamp"
                }
            }
          , "units": [
                {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [6,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":18
                }
             ,  {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [2,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":18
                }
             ,  {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [19,5]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":18
                }
            ]
        }
      , "floor2": {
            "id": 53002002
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [0,2]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわ・・なんだここ"
                      , "SPEAK %avatar% もじょ あなぼこだらけなのだ"
                    ]
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[4, 5]
                  , "type": "treasure"
	                , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[10, 10]
                  , "type": "treasure"
	                , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[19, 3]
                  , "type": "treasure"
	                , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "square_change_speak1": {
                    "trigger": "hero"
                  , "pos":[3,2], "rb":[4,3]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 04 04 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 05 05 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change1"
                }
              , "square_change_speak3": {
                    "trigger": "hero"
                  , "pos":[9,2], "rb":[10,3]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 09 04 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 09 05 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change3"
                }
              , "square_change_speak5": {
                    "trigger": "hero"
                  , "pos":[9,7], "rb":[11,8]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 12 07 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 12 08 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 09 09 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change5"
                }
              , "square_change_speak8": {
                    "trigger": "hero"
                  , "pos":[0,6], "rb":[1,7]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 00 09 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change8"
                }
              , "square_change_speak9": {
                    "trigger": "hero"
                  , "pos":[4,11], "rb":[5,12]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 06 13 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 07 13 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change9"
                }
              , "square_change_speak11": {
                    "trigger": "hero"
                  , "pos":[12,12], "rb":[13,13]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 15 09 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 15 10 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 15 11 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change11"
                }
              , "square_change_speak14": {
                    "trigger": "hero"
                  , "pos":[19,12], "rb":[20,13]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 21 11 $0933"
                      , "NOTIF ボコン！"
                      , "RPBG1 21 12 $0933"
                      , "NOTIF ボコン！"
                      , "LINES %avatar% あれ・・あなぼこが開いた"
                      , "SPEAK %avatar% もじょ 通れなくなったのだ・・"
                    ]
                  , "chain" : "square_change14"
                }
              , "square_change1": {
                    "type": "square_change"
                  , "change_pos": [4, 4]
                  , "change_tip": 933
                  , "chain_delayed": "square_change2"
                }
              , "square_change2": {
                    "type": "square_change"
                  , "change_pos": [5, 5]
                  , "change_tip": 933
                }
              , "square_change3": {
                    "type": "square_change"
                  , "change_pos": [9, 4]
                  , "change_tip": 933
                  , "chain_delayed": "square_change4"
                }
              , "square_change4": {
                    "type": "square_change"
                  , "change_pos": [9, 5]
                  , "change_tip": 933
                }
              , "square_change5": {
                    "type": "square_change"
                  , "change_pos": [12, 7]
                  , "change_tip": 933
                  , "chain_delayed": "square_change6"
                }
              , "square_change6": {
                    "type": "square_change"
                  , "change_pos": [12, 8]
                  , "change_tip": 933
                  , "chain_delayed": "square_change7"
                }
              , "square_change7": {
                    "type": "square_change"
                  , "change_pos": [9, 9]
                  , "change_tip": 933
                }
              , "square_change8": {
                    "type": "square_change"
                  , "change_pos": [0, 9]
                  , "change_tip": 933
                }
              , "square_change9": {
                    "type": "square_change"
                  , "change_pos": [6, 13]
                  , "change_tip": 933
                  , "chain_delayed": "square_change10"
                }
              , "square_change10": {
                    "type": "square_change"
                  , "change_pos": [7, 13]
                  , "change_tip": 933
                }
              , "square_change11": {
                    "type": "square_change"
                  , "change_pos": [15, 9]
                  , "change_tip": 933
                  , "chain_delayed": "square_change12"
                }
              , "square_change12": {
                    "type": "square_change"
                  , "change_pos": [15, 10]
                  , "change_tip": 933
                  , "chain_delayed": "square_change13"
                }
              , "square_change13": {
                    "type": "square_change"
                  , "change_pos": [15, 11]
                  , "change_tip": 933
                }
              , "square_change14": {
                    "type": "square_change"
                  , "change_pos": [21, 11]
                  , "change_tip": 933
                  , "chain_delayed": "square_change15"
                }
              , "square_change15": {
                    "type": "square_change"
                  , "change_pos": [21, 12]
                  , "change_tip": 933
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[1,2], "rb":[2,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.2"
                }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 6]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[5,2], "rb":[8,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 5]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.5": {
                    "trigger": "hero"
                  , "pos":[7,6], "rb":[10,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.6"
                }
              , "enemy2.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 3]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.7": {
                    "trigger": "hero"
                  , "pos":[12,7], "rb":[14,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 7]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.8"
                }
              , "enemy2.8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 8]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.9": {
                    "trigger": "hero"
                  , "pos":[0,6], "rb":[4,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 11]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.10"
                }
              , "enemy2.10": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 11]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.11": {
                    "trigger": "hero"
                  , "pos":[6,12], "rb":[11,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 12]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.12"
                }
              , "enemy2.12": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 13]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.13": {
                    "trigger": "hero"
                  , "pos":[17,2], "rb":[18,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [21, 5]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.14"
                }
              , "enemy2.14": {
                    "type": "unit"
                  , "unit": {
                        "pos": [22, 5]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "enemy2.15": {
                    "trigger": "hero"
                  , "pos":[21,2], "rb":[22,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [21, 10]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                  , "chain": "enemy2.16"
                }
              , "enemy2.16": {
                    "type": "unit"
                  , "unit": {
                        "pos": [22, 10]
                      , "character_id":-10089
                      , "icon":"shadow"
                      , "add_level":7
                    }
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [22,11]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor3"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [10, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [15, 0]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [20, 0]
                  , "ornament": "lamp"
                }
            }
        }
      , "floor3": {
            "id": 53002003
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [5,6]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ようやくついたかな・・？"
                      , "SPEAK %avatar% もじょ あの穴が出口なのだ。"
                      , "LINES %avatar% よし！行こう！"
                    ]
                  ,"chain" : "woden_appear"
                }
              , "woden_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 2]
                      , "character_id":-10115
                      , "icon":"shadowG"
                      , "code":"woden"
                      , "act_brain": "rest"
                      , "early_gimmick": "woden_end"
                      , "trigger_gimmick": "speak_end"
                      , "bgm": "bgm_bigboss"
                    }
                  ,"chain" : "woden_speak"
                }
              , "woden_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %woden% 待っていたぞ・・！"
                      , "LINES %avatar% お前は・・オーディン・・！"
                      , "LINES %woden% 霊子力研究所では世話になったな・・"
                      , "LINES %woden% あの失態で私は王のお怒りを買ってしまった。"
                      , "LINES %woden% その礼をするためにここで待ち伏せしていたのだ・・。"
                      , "LINES %avatar% ゲバルさんはどうしたの！？"
                      , "LINES %woden% 知りたければ私を倒してみろ。"
                      , "LINES %woden% さあ、決着をつけるのだ！かかってこい！"
                    ]
                }
              , "woden_end": {
                    "type": "lead"
                  , "leads": [
                        "LINES %woden% ぐっ・・まさか私がやられるとは・・。"
                      , "LINES %avatar% さあ！ゲバルさんはどうなってるか言いなさい！"
                      , "LINES %woden% フン・・。虫の息ではあるが処刑のため殺してはおらん。"
                      , "LINES %woden% どのみちもう死ぬだろうがな・・！"
                      , "LINES %woden% ぐはっ！"
                    ]
                }
              , "speak_end": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 生きてる・・！やっぱゲバルさんは生きてるんだよ！"
                      , "LINES %avatar% 必ず助けるよ・・待ってて！"
                    ]
                  ,"chain" : "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "torch1": {
                    "pos": [3, 2]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [7, 2]
                  , "ornament": "torch"
                }
            }
        }
    }
}
