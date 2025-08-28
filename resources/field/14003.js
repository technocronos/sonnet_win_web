{
    "extra_uniticons": ["shadow", "shadow2", "shadowB"]
  , "extra_maptips": [1720]
  , "rooms": {
        "start": {
            "id": 14003000
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos_on": {
                "start": [8,4]
              , "goto_b1_normal": [2,5]
              , "goto_b1_afloat": [5,8]
            }
          , "gimmicks": {

                "goto_b2_normal": {
                    "trigger": "hero"
                  , "pos": [2,6]
                  , "type": "goto"
                  , "room": "b2"
                }
              , "goto_b2_afloat": {
                    "trigger": "hero"
                  , "pos": [6,8]
                  , "type": "goto"
                  , "room": "b2"
                }
              , "goto_b2_fall": {
                    "type": "goto"
                  , "room": "b2"
                }
              , "shortcut_mission": {
                    "condition": {"mission":true}
                  , "trigger": "hero"
                  , "pos": [7,4]
                  , "type": "goto"
                  , "room": "fishpond2"
                  , "ornament": "goto2"
                }
              , "shortcut_non_mission": {
                    "condition": {"cleared":true, "mission":false}
                  , "trigger": "hero"
                  , "pos": [7,4]
                  , "type": "goto"
                  , "room": "seaporch"
                  , "ornament": "goto2"
                }

              , "escape1": {
                    "trigger": "hero"
                  , "pos": [9,4]
                  , "type": "escape"
                  , "ornament": "escape"
                }
              , "escape2": {
                    "trigger": "hero"
                  , "pos": [11,9]
                  , "type": "escape"
                  , "ornament": "escape"
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわー・・この洞窟初めて入るけど・・"
                      , "LINES %avatar% たしかにこの洞窟は子供をひきつける魅力があるね"
                      , "SPEAK %avatar% もじょ たしかに今もガキっぽいの一匹誘い込んでるのだ"
                      , "LINES %avatar% うっさいなー！でも実は前から一回来てみたかったんだよね・・"
                      , "SPEAK %avatar% もじょ やーっぱガキなのだ"
                    ]
                }

              , "mission_comment": {
                    "condition": {"mission":true, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ミッション\n(達成:+400マグナ)"
                      , "NOTIF 釣堀にいるゴブリンを\n満足させて脱出"
                    ]
                }
              , "stream": {
                    "trigger": "hero"
                  , "pos": [5,5]
                  , "type": "lead"
                  , "leads": [
                        "RPBG1 05 02 $1842"
                      , "LINES %avatar% なんだろう、この穴\n入ってみようかぁ"
                      , "RPBG1 05 03 $1842"
                      , "SPEAK %avatar% もじょ こんなの上がってこれないのだ\nどうやって戻るつもりなのだ"
                      , "RPBG1 05 04 $1842"
                      , "LINES %avatar% だよねーアハハ"
                      , "RPBG1 05 05 $1842"
                      , "LINES %avatar% うわ、水っ"
                      , "SPEAK %avatar% もじょ 鉄砲水なのだ！"
                      , "RPBG1 05 06 $1842"
                      , "UMOVE %avatar% 8"
                    ]
                  , "chain": "goto_b2_fall"
                }
              , "fungi1": {
                    "pos": [2, 4]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [7, 3]
                  , "ornament": "fungi2"
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos":[8,13]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 12005
                  , "one_shot": 140030001
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos":[12,11]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 140030002
                }
            }

          , "units": [
                {
                    "condition": {"reason":"goto_b1_afloat"}
                  , "pos": [3,12]
                  , "character_id":-3101
                  , "icon":"shadowB"
                }
              , {
                    "condition": {"reason":"goto_b1_afloat"}
                  , "pos": [12,12]
                  , "character_id":-3101
                  , "icon":"shadowB"
                }
            ]
        }
      , "b2": {
            "id": 14003001
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos_on": {
                "goto_b2_normal": [1,8]
              , "goto_b2_fall": [3,9]
              , "goto_b2_afloat": [4,11]
              , "leave_goblinroom": [10,10]
              , "stream_carry": [16,12]
            }
          , "gimmicks": {

                "goto_b1_normal": {
                    "trigger": "hero"
                  , "pos": [1,9]
                  , "type": "goto"
                  , "room": "start"
                }
              , "goto_b1_afloat": {
                    "trigger": "hero"
                  , "pos": [5,11]
                  , "type": "goto"
                  , "room": "start"
                }
              , "enter_goblinroom": {
                    "trigger": "hero"
                  , "pos": [10,11]
                  , "type": "goto"
                  , "room": "goblinroom"
                  , "ornament": "goto"
                }
              , "stream_carry": {
                    "type": "goto"
                  , "room": "fishpond"
                }

              , "fall_comment": {
                    "condition": {"cleared":false, "reason":"goto_b2_fall"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あいたた…"
                      , "SPEAK %avatar% もじょ けっきょく落ちてるのだ"
                    ]
                }
              , "fall_comment2": {
                    "trigger": "hero"
                  , "pos":[3,10], "rb":[5,11]
                  , "condition": {"cleared":false}
                  , "memory_shot": "fall_comment2"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 腰までつかってると動きづらいな…"
                      , "SPEAK %avatar% もじょ こんなとこで水につかってるとキバウオにガブっとやられるのだ"
                    ]
                }

              , "open_comment": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 水がたくさん流れてるなぁ"
                      , "SPEAK %avatar% もじょ キバウオもウヨウヨいるのだ。水辺には近づかないほうがいいのだ"
                    ]
                }

              , "find_goblin": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "hero"
                  , "pos":[0,2], "rb":[4,7]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、ゴブリンだ"
                      , "PFOCS 07 07"
                      , "DELAY 500"
                      , "LINES %avatar% なにやってんだろ"
                      , "SPEAK %avatar% もじょ …………\nもしかして、\n魚釣りしてるのだ？"
                      , "SPEAK %avatar% もじょ ウゲウゲしか喋れないくせに生意気な文化持ってるのだ"
                      , "LINES %avatar% 近づくとバレるよね…"
                      , "SPEAK %avatar% もじょ ちょっとここで様子見てみるのだ"
                    ]
                }
              , "approach_goblin": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "player"
                  , "pos":[5,2], "rb":[9,7]
                  , "type": "property"
                  , "unit": ["fisher1", "fisher2"]
                  , "change": {
                        "act_brain": "target"
                      , "target_unit": null
                      , "target_union": 1
                    }
                  , "chain": "goblin_attack"
                }
              , "goblin_attack": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "ignition": {"unit_exist":"fisher1"}
                  , "type": "lead"
                  , "leads": [
                        "LINES %fisher1% ウゲ！ウゲゲゲ！"
                    ]
                }
              , "hit_fish": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "rotation"
                  , "rotation": -1
                  , "rem": "ゴブリンがキバウオを吊り上げるギミック。ロジックで処理する"
                }
              , "fish_great": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "rem": "ロジックで起動する"
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ さすがキバウオ。陸に上げられても恐ろしい戦闘力なのだ"
                    ]
                }
              , "shoot_fish": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "unit_exit"
                  , "unit_exit": "fish"
                  , "ignition": {"unit_exist":"fisher1"}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% しとめた…"
                      , "SPEAK %avatar% もじょ ちょっと涙出そうな生活してるのだ…"
                    ]
                  , "chain": "take_back"
                }
              , "take_back": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "type": "property"
                  , "unit": ["fisher1", "fisher2"]
                  , "change": {
                        "act_brain": "destine"
                      , "destine_pos": [10,11]
                    }
                }
              , "home_goblin": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "trigger": "unit_into"
                  , "unit_into": ["fisher1", "fisher2"]
                  , "pos": [10, 11]
                  , "type": "unit_exit"
                  , "exit_reason": "room_exit"
                  , "lasting": 2
                  , "chain": "send_off"
                }
              , "send_off": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal", "yet_flag":140030101}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 行っちゃった…"
                      , "SPEAK %avatar% もじょ もじょは悲哀を通り越して感動の涙が出そうなのだ"
                      , "LINES %avatar% あ、なんか落ちてる"
                    ]
                  , "chain": "treasure_fishing"
                }
              , "treasure_fishing": {
                    "condition": {"cleared":false, "reason":"goto_b2_normal"}
                  , "one_shot": 140030101
                  , "type": "treasure"
                  , "item_id": 1902
                }

              , "stream_continue": {
                    "condition": {"reason":"stream_carry"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "chain": "stream"
                }
              , "enter_stream": {
                    "condition": {"reason":["goto_b2_normal", "leave_goblinroom"]}
                  , "trigger": "hero"
                  , "pos": [14,10], "rb": [16,12]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ？？？\nなんか水の音、大きくなった？"
                      , "SPEAK %avatar% もじょ ………\nもじょはちょっと宙に浮くのだ"
                    ]
                  , "chain": "stream"
                }
              , "stream": {
                    "type": "stream"
                  , "begin": [16,12]
                  , "path": "442266"
                }
              , "carry_out": {
                    "trigger": "hero"
                  , "pos": [16,10]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 流されるーー！"
                      , "SPEAK %avatar% もじょ もじょは浮かんでられるからへっちゃらなのだ"
                    ]
                  , "ornament": "goto"
                  , "chain": "stream_carry"
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos":[5,10]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 140030011
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos":[10,9]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 140030012
                }
              , "torch1-1": {
                    "pos": [9, 7]
                  , "ornament": "torch"
                }
            }

          , "units": [
                {
                    "condition": {"reason":["goto_b2_normal"], "cleared":false}
                  , "pos": [7,7]
                  , "character_id":-1202
                  , "union": 3
                  , "icon":"shadow"
                  , "act_brain": "rest"
                  , "code": "fisher1"
                  , "target_unit": "fish"
                }
              , {
                    "condition": {"reason":["goto_b2_normal"], "cleared":false}
                  , "pos": [8,7]
                  , "character_id":-1103
                  , "union": 3
                  , "icon":"shadow"
                  , "act_brain": "rest"
                  , "code": "fisher2"
                  , "target_unit": "fish"
                }

              , {
                    "condition": {"reason":["goto_b2_normal", "leave_goblinroom"]}
                  , "pos": [4,4]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
              , {
                    "condition": {"reason":["goto_b2_normal", "leave_goblinroom"]}
                  , "pos": [9,2]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "8822"
                  , "excurse_step": 2
                  , "code": "fish"
                  , "target_unit": "fisher2"
                }
              , {
                    "condition": {"reason":["goto_b2_normal", "leave_goblinroom"]}
                  , "pos": [11,4]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "goblinroom": {
            "id": 14003002
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos_on": {
                "enter_goblinroom": [5,1]
              , "return_goblinroom": [12,8]
            }
          , "gimmicks": {

                "leave_goblinroom": {
                    "trigger": "hero"
                  , "pos": [5,0]
                  , "type": "goto"
                  , "room": "b2"
                  , "ornament": "goto"
                }
              , "deep_goblinroom": {
                    "trigger": "hero"
                  , "pos": [13,8]
                  , "type": "goto"
                  , "room": "fishpond"
                  , "ornament": "goto"
                }

              , "open_comment": {
                    "condition": {"reason":"enter_goblinroom"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% この奥よく見えないなぁ…"
                      , "SPEAK %avatar% もじょ ……前に似たようなセリフを聞いたことあるのだ"
                    ]
                }

              , "living_goblin1": {
                    "condition": {"reason":"enter_goblinroom"}
                  , "trigger": "curtain"
                  , "curtain": "living"
                  , "type": "unit"
                  , "unit": {
                        "pos": [9,5]
                      , "character_id":-1103
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_union": 1
                    }
                  , "chain": "living_goblin2"
                }
              , "living_goblin2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 4]
                      , "character_id":-1302
                      , "items": [-2001, -2001]
                      , "icon":"shadow2"
                      , "act_brain": "target"
                      , "target_union": 1
                    }
                  , "chain": "living_goblin3"
                }
              , "living_goblin3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 8]
                      , "character_id":-1202
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_union": 1
                    }
                  , "chain": "open_living"
                }
              , "open_living": {
                    "condition": {"reason":"enter_goblinroom"}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% げ"
                      , "SPEAK %avatar% もじょ ……前に似たようなことがあったのだ"
                    ]
                }

              , "treasure2-1": {
                    "trigger": "player"
                  , "pos":[11,6]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1299
                  , "one_shot": 140030201
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos":[0,10]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 140030204
                  , "chain": "findclock"
                }
              , "findclock": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、こんなとこに時計みっけ"
                      , "SPEAK %avatar% もじょ 海にはいろんなもんが流れてくるのだ"
                      , "LINES %avatar% あの奥・・なんかアヤシイな・・。"
                    ]
                }
              , "treasureX1": {
                    "trigger": "player"
                  , "pos":[0,0]
                  , "type": "treasure"
                  , "item_id": 1201
                  , "one_shot": 140030202
                }
              , "treasureX2": {
                    "trigger": "player"
                  , "pos":[0,2]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 140030203
                }
              , "torch1": {
                    "pos": [4, 4]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [4, 8]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [9, 8]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [3, 6]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [12, 7]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [6,11]
                  , "character_id":-3201
                  , "icon":"shadowB"
                  , "union": 3
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
            ]
        }
      , "fishpond": {
            "id": 14003003
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos_on": {
                "deep_goblinroom": [1,17]
              , "stream_carry": [0,3]
              , "return_fishpond": [11,16]
            }
          , "gimmicks": {

                "return_goblinroom": {
                    "trigger": "hero"
                  , "pos": [0,17]
                  , "type": "goto"
                  , "room": "goblinroom"
                  , "ornament": "goto"
                }
              , "stream_carry": {
                    "trigger": "hero"
                  , "pos": [0,7]
                  , "type": "goto"
                  , "room": "b2"
                  , "ornament": "goto"
                }
              , "goto_seaporch": {
                    "trigger": "hero"
                  , "pos": [12,16]
                  , "type": "goto"
                  , "room": "seaporch"
                  , "ornament": "goto"
                }

              , "stream_continue": {
                    "condition": {"reason":"stream_carry"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "stream"
                  , "begin": [0,3]
                  , "path": "6666"
                  , "chain": ["falldown", "last_stream"]
                }
              , "falldown": {
                    "condition": {"reason":"stream_carry"}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% お、落ちるーー！"
                    ]
                  , "chain": "last_stream"
                }
              , "last_stream": {
                    "condition": {"reason":"stream_carry"}
                  , "type": "stream"
                  , "begin": [4,3]
                  , "path": "6"
                  , "chain": ["after_stream", "after_stream_cleared"]
                }
              , "after_stream": {
                    "condition": {"reason":"stream_carry", "cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% いったた…"
                      , "RPBG1 00 03 $1842"
                      , "SPEAK %avatar% もじょ まるでトイレのアレのような流されっぷりだったのだ"
                      , "RPBG1 01 03 $1842"
                      , "LINES %avatar% もーもじょのバカっ"
                      , "RPBG1 02 03 $1842"
                      , "LINES %avatar% うわ、しょっぱい！これ海水だ"
                      , "RPBG1 03 03 $1842"
                      , "LINES %avatar% ぜんぜん浅いけど、下のほうまで流されたってことか…"
                      , "RPBG1 04 03 $1846"
                      , "SPEAK %avatar% もじょ おまえこんなの登れないのだどうやって帰るのだ？"
                      , "RPBG1 05 03 $1720"
                      , "LINES %avatar% ケケル君どこにいるのかな・・"
                    ]
                }
              , "after_stream_cleared": {
                    "condition": {"reason":"stream_carry", "cleared":true}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% いったた…"
                      , "RPBG1 00 03 $1842"
                      , "DELAY 200"
                      , "RPBG1 01 03 $1842"
                      , "DELAY 200"
                      , "RPBG1 02 03 $1842"
                      , "DELAY 200"
                      , "RPBG1 03 03 $1842"
                      , "DELAY 200"
                      , "RPBG1 04 03 $1846"
                      , "DELAY 200"
                      , "RPBG1 05 03 $1720"
                      , "DELAY 200"
                    ]
                }

              , "fall_stream": {
                    "trigger": "hero"
                  , "pos": [0,7], "rb": [2,9]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ？？なんか水の音が聞こえる…"
                      , "SPEAK %avatar% もじょ 水ならいっぱい流れてるのだ"
                      , "LINES %avatar% 上から聞こえる…？"
                    ]
                  , "chain": "stream1"
                }
              , "stream1": {
                    "type": "stream"
                  , "begin": [2,9]
                  , "path": "2244"
                  , "on_carry": [
                        "LINES %avatar% いっぱい落ちてきた！"
                      , "SPEAK %avatar% もじょ 上から鉄砲水なのだ！"
                    ]
                }

              , "find_cliff": {
                    "condition": {"cleared":false}
                  , "memory_shot": "find_cliff"
                  , "trigger": "hero"
                  , "pos": [2,13], "rb": [6,15]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 崖かぁ"
                      , "SPEAK %avatar% もじょ ちょっとこれは通るのは無理なのだ"
                    ]
                }

              , "treasure3": {
                    "trigger": "player"
                  , "pos":[1,11]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 13005
                  , "one_shot": 140030301
                }
              , "treasure4": {
                    "trigger": "player"
                  , "pos":[2,17]
                  , "ornament": "twinkle"
                  , "type": "treasure"
                  , "item_id": 1911
                  , "one_shot": 140030303
                }

              , "torch3-1": {
                    "pos": [1, 14]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [1, 12]
                  , "ornament": "fungi2"
                }
            }

          , "units": [
                {
                    "pos": [3,14]
                  , "character_id":-1302
                  , "items": [-2001, -2001, -2001, -2001]
                  , "icon":"shadow2"
                  , "union": 3
                }
              , {
                    "pos": [10,5]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "86884442222668"
                  , "excurse_step": 2
                }
              , {
                    "pos": [7,8]
                  , "character_id":-3201
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "48848866622224"
                  , "excurse_step": 3
                }
              , {
                    "pos": [7,16]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "22262668884844"
                  , "excurse_step": 2
                }
            ]
        }
      , "fishpond2": {
            "id": 14003003
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [7,14]
          , "gimmicks": {

                "goto_seaporch": {
                    "trigger": "hero"
                  , "pos": [12,16]
                  , "type": "goto"
                  , "room": "seaporch"
                  , "ornament": "goto"
                }
              , "escape": {
                    "trigger": "hero"
                  , "pos": [5,17]
                  , "type": "escape"
                  , "ornament": "escape"
                }

              , "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %fisher1% 2"
                      , "LINES %fisher1% ウゲウゲウゲゲウゲゲ！\n(いつもいつもゴブリンばっか\nいじめやがって！)"
                      , "LINES %fisher1% ウゲゲゲゲゲ、ウゲゲゲ！\n(お詫びの印に俺様に\n魚をしとめさせろ！)"
                      , "LINES %fisher1% ウゲゲウゲゲ、ウゲゲンゲ！\n(崖の正面２マス以内に\n魚５匹さそってこい！)"
                    ]
                }
              , "fish_supply": {
                    "type": "unit"
                  , "lasting": 99
                  , "unit": {
                        "pos": [11,4]
                      , "character_id":-3201
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "44444488886666662222"
                      , "excurse_step": 3
                    }
                }
              , "avatar_angry": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 1"
                      , "LINES %avatar% いったーい!"
                      , "SPEAK %avatar% もじょ あのバカをなんとかする方法ないのだ…？"
                    ]
                  , "chain": "sea_eater"
                }
              , "sea_eater": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5,5]
                      , "character_id":-4201
                      , "icon":"shadow2"
                      , "code": "seaeater"
                      , "move_pow": 40
                    }
                  , "chain": "avatar_angry2"
                }
              , "avatar_angry2": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ゲッ！なんであいつが来るの"
                      , "SPEAK %avatar% もじょ ・・イイこと思いついたのだ・・\nアイツをここにおびきよせるってのは？"
                      , "LINES %avatar% ・・そりゃ名案！"
                      , "SPEAK %avatar% もじょ 戦わないように気をつけるのだ"
                    ]
                }
              , "bye_foolman": {
                    "trigger": "unit_exit"
                  , "unit_exit": "fisher1"
                  , "ignition": {"igniter":"seaeater"}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ バカにふさわしい\n最期なのだ"
                      , "SPEAK %avatar% もじょ さ、さっさとおいとまするのだ"
                      , "LINES %avatar% あ、なんか落ちてきた"
                    ]
                  , "chain": "treasure_foolman"
                }
              , "treasure_foolman": {
                    "type": "treasure"
                  , "item_id": 14004
                  , "one_shot": 140030302
                  , "chain": "missiongoal"
                }
              , "missiongoal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "torch3-1-2": {
                    "pos": [1, 14]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [1, 11]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [1, 12]
                  , "ornament": "fungi2"
                }
            }

          , "units": [
                {
                    "pos": [3,13]
                  , "character_id":-1202
                  , "icon":"shadow"
                  , "align": 2
                  , "code": "fisher2"
                  , "union": 3
                }
              , {
                    "pos": [3,15]
                  , "character_id":-1202
                  , "icon":"shadow"
                  , "code": "fisher3"
                  , "align": 2
                  , "union": 3
                }
              , {
                    "pos": [2,14]
                  , "character_id":-1302
                  , "icon":"shadow2"
                  , "code": "fisher1"
                  , "align": 2
                  , "union": 3
                }

              , {
                    "pos": [5,9]
                  , "character_id":-3201
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "222222666888888888444222"
                  , "excurse_step": 3
                }
              , {
                    "pos": [8,12]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "84888662622244"
                  , "excurse_step": 3
                }
              , {
                    "pos": [11,9]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444888666222"
                  , "excurse_step": 3
                }
              , {
                    "pos": [10,17]
                  , "character_id":-3201
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "622222444848888666"
                  , "excurse_step": 3
                }
              , {
                    "pos": [11,7]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "88888888844442222622222222668868"
                  , "excurse_step": 3
                }
            ]
        }
      , "seaporch": {
            "id": 14003004
          , "battle_bg": "wetlands"
          , "environment": "grass"
          , "start_pos": [1,3]
          , "gimmicks": {

                "return_fishpond": {
                    "trigger": "hero"
                  , "pos": [0,3]
                  , "type": "goto"
                  , "room": "fishpond"
                  , "ornament": "goto"
                }

              , "sea_escape": {
                    "trigger": "hero"
                  , "pos": [15,13]
                  , "type": "escape"
                  , "ornament": "goto2"
                }

              , "kekeru_rescue1": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [2,0]
                  , "one_shot": 140030401
                  , "type": "drama"
                  , "drama_id": 1400301
                }
              , "kekeru_rescue2": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [2,0]
                  , "ornament": "curious"
                  , "memory_shot": "kekeru_rescue"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし！じゃ、行こっか！"
                      , "SPEAK %avatar% ケケル …うん"
                    ]
                }

              , "fish2": {
                    "trigger": "rotation"
                  , "rotation": 2
                  , "type": "unit"
                  , "unit": {
                        "pos": [2,19]
                      , "character_id":-3101
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "22222222222668888888888844"
                      , "excurse_step": 2
                    }
                }
              , "fish3": {
                    "trigger": "rotation"
                  , "rotation": 5
                  , "type": "unit"
                  , "unit": {
                        "pos": [7,19]
                      , "character_id":-3201
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "2222222224488888888866"
                      , "excurse_step": 2
                    }
                }
              , "fish4": {
                    "trigger": "rotation"
                  , "rotation": 7
                  , "type": "unit"
                  , "unit": {
                        "pos": [2,21]
                      , "character_id":-3101
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "222222222222266888888888888844"
                      , "excurse_step": 2
                    }
                }

              , "fish5": {
                    "trigger": "hero"
                  , "pos": [7,16], "rb": [9,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15,18]
                      , "character_id":-3101
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "44444444226688666666"
                      , "excurse_step": 3
                    }
                  , "chain": "fish6"
                }
              , "fish6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [0,19]
                      , "character_id":-3201
                      , "icon":"shadowB"
                      , "unit_class": "ExBrains"
                      , "act_brain": "excurse"
                      , "excurse_path": "6666666666666662244444488444444444"
                      , "excurse_step": 3
                    }
                }

              , "inlet_warn": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [8,7], "rb": [10,9]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% こっちのほうは波がゆるいけど…"
                      , "SPEAK %avatar% もじょ こういうとこは変なのが休憩してたりするのだ"
                    ]
                }
              , "inlet_enemy": {
                    "trigger": "hero"
                  , "pos": [11,6], "rb": [14,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14,6]
                      , "character_id":-4201
                      , "unit_class": "14003SeaEater"
                      , "icon":"shadow2"
                      , "code": "seaeater"
                    }
                }
              , "vomit_enemy": {
                    "type": "unit"
                  , "unit": {
                        "character_id":-3201
                      , "icon":"shadowB"
                    }
                  , "lasting": 5
                  , "rem": "シーイーターが敵を吐き出すときに使う。'pos'などはロジックでセット。"
                }
              , "treasure_seaeater": {
                    "trigger": "unit_exit"
                  , "unit_exit": "seaeater"
                  , "type": "treasure"
                  , "item_id": 1906
                  , "one_shot": 140030402
                }
            }

          , "units": [
                {
                    "pos": [3,4]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "88662244"
                  , "excurse_step": 2
                }
            ]
        }
    }
}
