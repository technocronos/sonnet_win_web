{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "man", "woman", "shisyou"]
  , "rooms": {

        "start": {
            "id": 15004000
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "start": [9,1]
              , "goto_top1": [1,4]
              , "goto_top2": [1,10]
              , "goto_top3": [16,10]
              , "goto_top4": [17,3]
            }
          , "gimmicks": {

                "goto_left1": {
                    "trigger": "hero"
                  , "pos": [0,4]
                  , "type": "goto"
                  , "room": "left"
                  , "ornament": "goto"
                }
              , "goto_left2": {
                    "trigger": "hero"
                  , "pos": [0,10]
                  , "type": "goto"
                  , "room": "left"
                  , "ornament": "goto"
                }
              , "goto_center1": {
                    "trigger": "hero"
                  , "pos": [16,11]
                  , "type": "goto"
                  , "room": "center"
                  , "ornament": "goto"
                }
              , "goto_right1": {
                    "trigger": "hero"
                  , "pos": [18,3]
                  , "type": "goto"
                  , "room": "right"
                  , "ornament": "goto"
                }

              , "escape1": {
                    "trigger": "hero"
                  , "pos": [9,0]
                  , "type": "escape"
                  , "ornament": "escape"
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 夫婦ゲンカなんかほっとけなのだ。犬も食わねーのだ"
                      , "LINES %avatar% そうかもだけどさ～。日頃、お世話になりまくってるんだから少しでも助けたいじゃん"
                      , "LINES %avatar% マリーさん強情だし、川の向こうまで行ってモンスターに出くわしちゃうかもだから。"
                      , "SPEAK %avatar% もじょ やれやれなのだ・・"
                    ]
                }
              , "fish_warn": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 2
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% なんか、キバウオがこっちをうかがってる気がする…"
                      , "SPEAK %avatar% もじょ たぶん腹へってんのだ"
                      , "LINES %avatar% マリーさん大丈夫かなぁ…"
                    ]
                }
              , "treasure0-1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [4,2]
                  , "item_id": 1905
                  , "one_shot": 150040001
                }
              , "treasure0-2": {
                    "trigger": "hero"
                  , "pos": [11,10]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 150040002
                }
              , "tanpopo1": {
                    "pos": [2, 10]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [6, 10]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [13, 9]
                  , "ornament": "blueflower"
                }
            }

          , "units": [
                {
                    "pos": [2,6]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
              , {
                    "pos": [9,5]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
              , {
                    "pos": [16,6]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
            ]
        }

      , "left": {
            "id": 15004001
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "goto_left1": [8,2]
              , "goto_left2": [8,8]
            }
          , "gimmicks": {

                "goto_top1": {
                    "trigger": "hero"
                  , "pos": [9,2]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goto_top2": {
                    "trigger": "hero"
                  , "pos": [9,8]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }

              , "blue_light": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% なんだろ、あの光…"
                      , "PFOCS 03 09"
                      , "DELAY 600"
                      , "SPEAK %avatar% もじょ あれは精霊のムーリトがあそこで休んでた跡なのだ"
                      , "LINES %avatar% ムーリトって時と空間の大精霊じゃん"
                      , "SPEAK %avatar% もじょ あそこにいけば行動ptが回復するのだ"
                    ]
                  , "memory_shot": "blue_light"
                }
              , "mourito_locus": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [3,9]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                  , "memory_shot": "mourito_locus"
                }

              , "wolf_territory": {
                    "trigger": "hero"
                  , "pos": [0,9], "rb": [9,22]
                  , "type": "lead"
                  , "leads": [
                        "LINES %wolf1% グル…"
                    ]
                  , "chain": "wolf_go"
                }
              , "wolf_go": {
                    "type": "property"
                  , "unit": ["wolf1", "wolf2"]
                  , "change": {
                        "act_brain": "target"
                    }
                }
              , "treasure1-1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [8,18]
                  , "item_id": 1902
                  , "one_shot": 150040011
                }
              , "treasure1-2": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [4,21]
                  , "item_id": 11005
                  , "one_shot": 150040012
                }
              , "susuki1": {
                    "pos": [1, 13]
                  , "ornament": "susuki"
                }
              , "tanpopo1": {
                    "pos": [3, 20]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [4, 19]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [6, 14]
                  , "ornament": "blueflower"
                }
            }

          , "units": [
                {
                    "pos": [8,6]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
              , {
                    "pos": [5,19]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "union": 3
                  , "act_brain": "rest"
                  , "target_union": 1
                  , "code": "wolf1"
                }
              , {
                    "pos": [7,21]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "union": 3
                  , "act_brain": "rest"
                  , "target_union": 1
                  , "code": "wolf2"
                }
            ]
        }

      , "right": {
            "id": 15004002
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "goto_right1": [1,1]
              , "goto_right2": [1,20]
            }
          , "gimmicks": {

                "goto_top4": {
                    "trigger": "hero"
                  , "pos": [0,1]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goto_center2": {
                    "trigger": "hero"
                  , "pos": [0,20]
                  , "type": "goto"
                  , "room": "center"
                  , "ornament": "goto"
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"goto_right1"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "ignition": {"unit_exist":"marie"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "SPEAK %avatar% もじょ あそこにいるのだ"
                      , "FOCUS %marie%"
                      , "DELAY 700"
                      , "LINES %avatar% あんな奥に…あれじゃ危ないよ"
                    ]
                }
              , "approach_comment": {
                    "condition": {"cleared":false, "reason":"goto_right1"}
                  , "trigger": "hero"
                  , "pos": [0,8], "rb": [9,12]
                  , "ignition": {"unit_exist":"marie"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% マリーさん、そんなに奥に行ったら危ないよ！"
                      , "LINES %marie% …"
                    ]
                }
              , "close_comment": {
                    "condition": {"cleared":false, "reason":"goto_right1"}
                  , "trigger": "hero"
                  , "pos": [0,14], "rb": [3,20]
                  , "ignition": {"unit_exist":"marie"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% ボポタさんから聞いて捜しに来たの。帰ろう。"
                      , "LINES %avatar% ボポタさん、離婚されたかもって心配してたよ。"
                      , "SPEAK %avatar% もじょ ちなみにそれはこいつのせいなのだ"
                      , "LINES %avatar% もじょじゃん！？なんでボクのせいになってんの！？"
                      , "SPEAK %avatar% もじょ もじょは離婚まで言ってないのだ離婚って言ったのお前なのだ"
                      , "LINES %marie% 離婚…そんなのダメ！指輪…絶対見つけなきゃ…指輪……"
                      , "LINES %avatar% あ！マリーさん待って！"
                      , "UMOVE %marie% 8884"
                    ]
                  , "chain": "marie_getout"
                }
              , "marie_getout": {
                    "condition": {"cleared":false, "reason":"goto_right1"}
                  , "type": "unit_exit"
                  , "exit_target": "marie"
                  , "exit_reason": "room_exit"
                  , "memory_on": "marie_step1"
                }
              , "treasure2-1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [8,13]
                  , "item_id": 1902
                  , "one_shot": 150040021
                }
              , "susuki1": {
                    "pos": [5, 10]
                  , "ornament": "susuki"
                }
              , "tanpopo1": {
                    "pos": [8, 12]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [5, 21]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [1, 18]
                  , "ornament": "blueflower"
                }

            }

          , "units": [
                {
                    "pos": [1,10]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
              , {
                    "pos": [7,14]
                  , "character_id":-3202
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }

              , {
                    "condition": {"cleared":false, "reason":"goto_right1", "yet_memory":"marie_step1"}
                  , "pos": [1,17]
                  , "character_id":-9901
                  , "icon":"woman"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                  , "code": "marie"
                  , "unit_class": "15004Marie"
                  , "union": 1
                }
            ]
        }

      , "center": {
            "id": 15004003
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "goto_center1": [16,1]
              , "goto_center2": [17,10]
              , "goto_center3": [11,15]
              , "goto_center4": [4,15]
            }
          , "gimmicks": {

                "goto_top3": {
                    "trigger": "hero"
                  , "pos": [16,0]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "goto_right2": {
                    "trigger": "hero"
                  , "pos": [18,10]
                  , "type": "goto"
                  , "room": "right"
                  , "ornament": "goto"
                }
              , "goto_bottom1": {
                    "trigger": "hero"
                  , "pos": [11,16]
                  , "type": "goto"
                  , "room": "bottom"
                  , "ornament": "goto"
                }
              , "goto_bottom2": {
                    "trigger": "hero"
                  , "pos": [4,16]
                  , "type": "goto"
                  , "room": "bottom"
                }

              , "escape2": {
                    "trigger": "hero"
                  , "pos": [0,14]
                  , "type": "escape"
                  , "ornament": "escape"
                }

              , "wolf_territory": {
                    "condition": {"reason":"goto_center1"}
                  , "trigger": "hero"
                  , "pos": [0,6], "rb": [11,16]
                  , "type": "lead"
                  , "leads": [
                        "LINES %wolf1% グル…"
                    ]
                  , "chain": "wolf_go"
                }
              , "wolf_go": {
                    "type": "property"
                  , "unit": ["wolf1", "wolf2"]
                  , "change": {
                        "act_brain": "target"
                    }
                }
              , "treasure1": {
                    "trigger": "hero"
                  , "pos": [3,11]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 150040301
                }
              , "treasure2": {
                    "trigger": "hero"
                  , "pos": [2,12]
                  , "type": "treasure"
                  , "item_id": 14004
                  , "ornament": "twinkle"
                  , "one_shot": 150040302
                  , "chain": "animal_trail"
                }
              , "animal_trail": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4,15]
                      , "character_id":-2301
                      , "icon":"shadow"
                      , "union": 3
                      , "act_brain": "target"
                      , "target_union": 1
                    }
                  , "chain": "animal_surprise"
                }
              , "animal_surprise": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あれ！？いつのまに？"
                      , "SPEAK %avatar% もじょ どこからわいてきたのだ？"
                    ]
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"goto_center2"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "ignition": {"unit_exist":"marie"}
                  , "type": "lead"
                  , "leads": [
                        "FOCUS %marie_eater1%"
                      , "DELAY 300"
                      , "LINES %marie% キャーーッ"
                      , "LINES %avatar% 危ない！"
                    ]
                }
              , "marie_rescue": {
                    "condition": {"cleared":false, "reason":"goto_center2"}
                  , "ignition": {"unit_exist":"marie"}
                  , "trigger": "unit_exit"
                  , "unit_exit": ["marie_eater1", "marie_eater2"]
                  , "ignition": {"!unit_exist":["marie_eater1", "marie_eater2"]}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% 大丈夫？マリーさん本当に帰ろう。危ないよ。"
                      , "LINES %marie% それでも…私は指輪を見つけないといけないの！"
                      , "UMOVE %marie% 8848"
                    ]
                  , "chain": "marie_getout"
                }
              , "marie_getout": {
                    "condition": {"cleared":false, "reason":"goto_center2"}
                  , "type": "unit_exit"
                  , "exit_target": "marie"
                  , "exit_reason": "room_exit"
                  , "chain_delayed": "after_getout"
                }
              , "after_getout": {
                    "condition": {"cleared":false, "reason":"goto_center2"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "SPEAK %avatar% もじょ あんなのほっとけばキレイに骨まで食われるのだ"
                      , "SPEAK %avatar% もじょ ボポタには黙って離婚したと思わせとけなのだ"
                      , "LINES %avatar% もじょ…ひどすぎ"
                    ]
                  , "memory_on": "marie_step2"
                }
              , "susuki1": {
                    "pos": [13, 10]
                  , "ornament": "susuki"
                }
              , "tanpopo1": {
                    "pos": [2, 14]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [1, 1]
                  , "ornament": "blueflower"
                }
            }

          , "units": [
                {
                    "pos": [6,8]
                  , "character_id":-3202
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "code": "marie_eater2"
                }
              , {
                    "pos": [15,13]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "code": "marie_eater1"
                }

              , {
                    "pos": [2,14]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "condition": {"reason":"goto_center1"}
                  , "union": 3
                  , "act_brain": "rest"
                  , "target_union": 1
                  , "code": "wolf1"
                }
              , {
                    "pos": [4,12]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "condition": {"reason":"goto_center1"}
                  , "union": 3
                  , "act_brain": "rest"
                  , "target_union": 1
                  , "code": "wolf2"
                }

              , {
                    "condition": {"cleared":false, "reason":"goto_center2", "yet_memory":"marie_step2"}
                  , "pos": [12,13]
                  , "character_id":-9901
                  , "icon":"woman"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                  , "code": "marie"
                  , "unit_class": "15004Marie"
                  , "union": 1
                }
            ]
        }

      , "bottom": {
            "id": 15004004
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "goto_bottom1": [13,1]
              , "goto_bottom2": [6,1]
            }
          , "gimmicks": {

                "goto_center3": {
                    "trigger": "hero"
                  , "pos": [13,0]
                  , "type": "goto"
                  , "room": "center"
                  , "ornament": "goto"
                }
              , "goto_center4": {
                    "trigger": "hero"
                  , "pos": [6,0]
                  , "type": "goto"
                  , "room": "center"
                }

              , "escape3": {
                    "trigger": "hero"
                  , "pos": [0,7]
                  , "type": "escape"
                  , "ornament": "escape"
                }
              , "treasure3": {
                    "trigger": "hero"
                  , "pos": [3,6]
                  , "type": "treasure"
                  , "item_id": 12006
                  , "ornament": "twinkle"
                  , "one_shot": 150040401
                }

              , "marie_open1": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% 指輪を……指輪………"
                      , "SPEAK %avatar% もじょ こいつ、なんかブツブツ言ってんのだ"
                    ]
                }
              , "marie_open2": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "rotation"
                  , "rotation": 2
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ おい、おまえ、そろそろあきらめろなのだ"
                      , "LINES %marie% 指輪は…指輪は……どこ……？"
                      , "SPEAK %avatar% もじょ [NAME]…こ、こいつちょっと変なのだ…"
                      , "LINES %avatar% こうなったマリーさんはちょっと難しいな…"
                      , "LINES %avatar% 先回りして守りながら説得しないと…"
                    ]
                }

              , "marie_arrival1": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [19,2], "rb":[20,3]
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% 指輪…指輪……わたしの指輪…あっちね…きっとそう………"
                      , "SPEAK %avatar% もじょ [NAME]～もういいのだ！ほっといて帰るのだ！もじょはちょっと泣き…"
                      , "LINES %avatar% そ…そういうわけにも…"
                    ]
                  , "chain": "marie_dest2"
                }
              , "marie_dest2": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "property"
                  , "unit": "marie"
                  , "change": {
                        "destine_pos": [18,10]
                    }
                }
              , "fish_supply1-1": {
                    "condition": {"reason":"goto_bottom1"}
                  , "trigger": "rotation"
                  , "rotation": 4
                  , "type": "unit"
                  , "unit": {
                        "pos": [3,9]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                      , "trigger_gimmick": "recov_supply"
                    }
                  , "chain": "fish_supply1-2"
                }
              , "fish_supply1-2": {
                    "condition": {"reason":"goto_bottom1"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [20,12]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                    }
                  , "chain": "fish_supply1-3"
                }
              , "fish_supply1-3": {
                    "condition": {"reason":"goto_bottom1"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [17,0]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                    }
                }

              , "marie_arrival2": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [18,9], "rb":[19,10], "mask":[
                        "10"
                      , "11"
                    ]
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% ない………？ううん。そんなことない。きっとわたしは導かれてるの…"
                      , "UALGN %marie% 0"
                      , "LINES %marie% あっち！？そう、あっちなのね！？"
                    ]
                  , "chain": "marie_dest3"
                }
              , "marie_dest3": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "property"
                  , "unit": "marie"
                  , "change": {
                        "destine_pos": [16,18]
                      , "move_pow": 70
                    }
                }
              , "marie_is_fast": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [17,14], "rb": [19,20]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% は、速い！？"
                      , "SPEAK %avatar% もじょ いったいどーなってんのだ！？"
                    ]
                }
              , "fish_supply2-1": {
                    "condition": {"reason":"goto_bottom1"}
                  , "trigger": "rotation"
                  , "rotation": 8
                  , "type": "unit"
                  , "unit": {
                        "pos": [13,12]
                      , "character_id":-3202
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                      , "trigger_gimmick": "recov_supply"
                    }
                  , "chain": "fish_supply2-2"
                }
              , "fish_supply2-2": {
                    "condition": {"reason":"goto_bottom1"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [15,21]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                    }
                }

              , "marie_arrival3": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [16,18], "rb":[17,19]
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% あ！なにか光ってる！これなのね！？"
                      , "LINES %marie% これは………貝殻？…貝殻………ちがう…指輪じゃない…"
                      , "UALGN %marie% 1"
                      , "LINES %marie% ………そっち？分かったわ、あなたそっちなのね……"
                    ]
                  , "chain": "marie_dest4"
                }
              , "marie_dest4": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "property"
                  , "unit": "marie"
                  , "change": {
                        "destine_pos": [5,13]
                      , "move_pow": 30
                    }
                  , "chain": "marie_treasure1"
                }
              , "fish_supply3-1": {
                    "condition": {"reason":"goto_bottom1"}
                  , "trigger": "rotation"
                  , "rotation": 9
                  , "type": "unit"
                  , "unit": {
                        "pos": [15,4]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                      , "trigger_gimmick": "recov_supply"
                    }
                  , "chain": "fish_supply3-2"
                }
              , "fish_supply3-2": {
                    "condition": {"reason":"goto_bottom1"}
                  , "type": "unit"
                  , "unit": {
                        "pos": [12,21]
                      , "character_id":-3102
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "wistful"
                      , "wistful_slow": 2
                    }
                }

              , "marie_arrival4": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [5,13], "rb":[6,14]
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% また貝殻…"
                      , "LINES %marie% ああ、あなた…わたしを貝殻で導いているのね…"
                      , "LINES %marie% …………こっちなのね？"
                    ]
                  , "chain": "marie_dest5"
                }
              , "marie_dest5": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "property"
                  , "unit": "marie"
                  , "change": {
                        "destine_pos": [2,16]
                      , "move_pow": 40
                    }
                }

              , "marie_arrival5": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "unit_into"
                  , "unit_into": "marie"
                  , "pos": [2,16], "rb":[3,17]
                  , "type": "lead"
                  , "leads": [
                        "LINES %marie% 貝殻…ゆびわ…"
                      , "LINES %marie% ユビワ…カイガラ……"
                      , "LINES %marie% ……ユビワ～"
                      , "SPEAK %avatar% もじょ と…止まったのだ…"
                      , "LINES %avatar% とりあえず、近づいて説得を…"
                      , "SPEAK %avatar% もじょ ち、近づくのだ！？"
                    ]
                  , "chain": "marie_rest"
                }
              , "marie_rest": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "property"
                  , "unit": "marie"
                  , "change": {
                        "act_brain": "rest"
                    }
                  , "memory_on": "marie_rest"
                }

              , "marie_persuade": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "hero"
                  , "pos": [1,15], "rb":[3,17], "mask": [
                        "010"
                      , "111"
                      , "010"
                    ]
                  , "ignition": {"has_memory":"marie_rest"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% もじょ……もしかして、こわい？"
                      , "SPEAK %avatar% もじょ そ…そんなわけねーのだ！もじょが人間が怖いわけないのだ！"
                      , "SPEAK %avatar% もじょ お…おい、おまえ、離婚なんて冗談なのだ。わ…笑って…指輪はあきらめろなのだ"
                      , "UALGN %marie% 0"
                      , "LINES %marie% うるさいわね！！殺すわよ！！！！"
                      , "SPEAK %avatar% もじょ ヒィィ！！！"
                      , "LINES %avatar% これはムリだな…なんとか指輪を見つけないと…"
                    ]
                }

              , "find_catfish": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "hero"
                  , "pos":[1,15], "rb":[6,18]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% …ん？"
                      , "PFOCS 07 20"
                      , "DELAY 800"
                      , "LINES %avatar% 見たことある…シショーのナマズとりに付いていったときに…"
                      , "LINES %avatar% 電撃ナマズの巣か…そういえばあのとき巣に金属がたくさんあったっけ…"
                      , "LINES %avatar% 電撃ナマズは金属集めるのが好きだって…もしかして…"
                    ]
                }
              , "catfish_appear1": {
                    "trigger": "hero"
                  , "pos":[1,15], "rb":[6,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6,20]
                      , "code": "catfish1"
                      , "character_id":-5101
                      , "items": [-2005, -2005]
                      , "icon":"shadow2"
                      , "act_brain": "keep"
                      , "keep_pos": [6,20]
                      , "trigger_gimmick": "memorial_treasure1"
                      , "bgm": "bgm_bossbattle"
                    }
                  , "chain": "catfish_appear2"
                }
              , "catfish_appear2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7,19]
                      , "code": "catfish2"
                      , "character_id":-5101
                      , "items": [-2005, -2005]
                      , "icon":"shadow2"
                      , "act_brain": "keep"
                      , "keep_pos": [7,19]
                      , "trigger_gimmick": "memorial_treasure2"
                      , "bgm": "bgm_bossbattle"
                    }
                }

              , "marriage_ring": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "trigger": "hero"
                  , "pos":[7,20]
                  , "ornament": "twinkle"
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 1"
                      , "LINES %avatar% あった！やっぱりあった！マリーさん、コレでしょ！？"
                      , "UALGN %marie% 2"
                      , "LINES %marie% え…あ、それ！すごい！[NAME]すごい！"
                      , "LINES %avatar% えへへ…やったね。さぁ帰ろうマリーさん"
                      , "SPEAK %avatar% もじょ 迫力はなくなったのだ…でもやっぱりコワいのだ…"
                    ]
                  , "chain": "goto_village"
                }
              , "goto_village": {
                    "type": "goto"
                  , "room": "village"
                }
              , "recov_supply": {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "type": "treasure"
                  , "item_id": 1001
                  , "lasting": 10
                }
              , "memorial_treasure1": {
                    "type": "treasure"
                  , "item_id": 2005
                  , "one_shot": 150040402
                }
              , "memorial_treasure2": {
                    "type": "treasure"
                  , "item_id": 2005
                  , "one_shot": 150040403
                }
              , "susuki1": {
                    "pos": [2, 1]
                  , "ornament": "susuki"
                }
              , "tanpopo1": {
                    "pos": [7, 11]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [1, 15]
                  , "ornament": "blueflower"
                }
            }

          , "units": [
                {
                    "pos": [4,8]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "condition": {"reason":"goto_bottom2"}
                }
              , {
                    "pos": [1,9]
                  , "character_id":-2301
                  , "icon":"shadow"
                  , "condition": {"reason":"goto_bottom2"}
                }

              , {
                    "condition": {"cleared":false, "reason":"goto_bottom1"}
                  , "pos": [13,2]
                  , "character_id":-9901
                  , "icon":"woman"
                  , "act_brain": "destine"
                  , "move_pow": 30
                  , "destine_pos": [20,3]
                  , "brain_noattack": true
                  , "code": "marie"
                  , "unit_class": "15004Marie"
                  , "union": 1
                }
              , {
                    "condition": {"reason":"goto_bottom1"}
                  , "pos": [11,7]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }
              , {
                    "condition": {"reason":"goto_bottom1"}
                  , "pos": [20,12]
                  , "character_id":-3102
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                }

            ]
        }

      ,"village": {
            "id": 15001000
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [14,10]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %popota% 2"
                      , "UALGN %marie% 1"
                      , "SPEAK %popota% ポポタ マ、マリー！帰ってきてくれたんだね！！"
                      , "SPEAK %marie% マリー あなた…ごめんなさい。私、意地はって。"
                      , "SPEAK %popota% ポポタ そんなことない！分かってあげられなかった僕が悪かったんだ。"
                      , "SPEAK %marie% マリー あなた見て…指輪よあなたがくれた私への愛の証。"
                      , "SPEAK %popota% ポポタ そんなものはどうでもいいんだ君さえ無事なら・・"
                      , "SPEAK %marie% マリー ああ・・あなた・・！"
                      , "LINES %avatar% ・・ボクたちのことは目に入ってないみたいだね・・"
                      , "SPEAK %avatar% もじょ こんなバカップルほっといてさっさとおいとまするのだ"
                      , "LINES %avatar% よし。シショーのとこに帰ろう。生活費はもらったんだし"
                      , "SPEAK %avatar% もじょ そーするのだ"
                    ]
                  , "chain": "finish"
                }
              , "finish": {
                    "condition": {"cleared":false}
                  , "ignition": {"!has_flag":1500100003}
                  , "one_shot": 1500100003
                  , "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [7,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man1"
                  , "pos": [6,12]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [15,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "popota"
                  , "icon":"man"
                  , "pos": [14,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [8,8]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "marie"
                  , "icon":"woman"
                  , "pos": [15,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
