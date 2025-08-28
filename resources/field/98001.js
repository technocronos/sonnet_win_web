{
    "extra_uniticons": ["shadowG", "avatarE", "avatarF", "avatarS", "shisyou"]
  , "rooms": {

        "start": {
            "id": 98001000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [0,6]
          , "start_pos_on": {
                "kakure_zone": [8,1]
            }
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわー、ここにドッペルゲンガーが出るのか・・"
                      , "SPEAK %avatar% もじょ そうみたいなのだ"
                    ]
                  , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980010000
                  , "leads": [
                        "SPEAK %avatar% もじょ あ、これはイベント用洞窟なのだ。上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "next_stage": {
                    "trigger": "hero"
                  , "pos": [8,11]
                  , "type": "goto"
                  , "room": "passage1"
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[1,6], "rb":[5,9]
                  , "type": "unit"
                  , "unit": {
                         "pos": [6, 11]
                       , "character_id":-9101
                       , "icon":"avatarF"
                     }
                   , "chain": "enemy1.2"
                 }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 10]
                      , "character_id":-9101
                      , "icon":"avatarF"
                    }
                  , "chain": "find1"
                }
              , "enemy1.3": {
                    "condition": {"reason":"kakure_zone"}
                  , "trigger":"rotation"
                  , "rotation":1
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 2]
                      , "character_id":-9107
                      , "icon":"shadowG"
                      , "trigger_gimmick": "treasure3"
                      , "bgm": "bgm_bigboss"
                    }
                  , "chain": "find2"
                }
              , "next_stage2": {
                    "condition": {"reason":"kakure_zone"}
                  , "trigger": "hero"
                  , "pos": [8,2]
                  , "type": "goto"
                  , "room": "passage4"
                  , "ornament": "goto"
                }
              , "fungi1": {
                    "pos": [2, 3]
                  , "ornament": "fungi2"
                }
              , "fungi4": {
                    "pos": [6, 3]
                  , "ornament": "fungi"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[7,1]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980010004
                }
              , "treasure2": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[7,5]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "one_shot": 980010002
                }
              , "treasure3": {
                    "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980010003
                }
              , "torch1": {
                    "pos": [1, 5]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [6, 6]
                  , "ornament": "torch"
                }
               , "find1": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 0"
                       , "LINES %avatar% で、出た！ボクにそっくり！"
                       , "LINES %avatar% もじょ、あれがドッペルなんとか？"
                       , "SPEAK %avatar% もじょ ドッペルゲンガー、なのだ。レベルもコピーされたから気をつけるのだ"
                       , "LINES %avatar% も、もう一つ聞いていい？もじょ"
                       , "SPEAK %avatar% もじょ なんなのだ？"
                       , "LINES %avatar% ボ、ボクってあんなにムチムチしてるかな・・"
                       , "SPEAK %avatar% もじょ こんな時に何を気にしてるのだ！あんなもんなのだ！"
                     ]
                 }
               , "find2": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 0"
                       , "LINES %avatar% にゃあー！ば、ばけもん！"
                       , "SPEAK %avatar% もじょ あ、あれはリュムナデスなのだ！"
                       , "SPEAK %avatar% もじょ 幻覚を見せて人の心を食う化け物なのだ"
                       , "LINES %avatar% あいつが裏で糸を引いてるのか・・"
                       , "SPEAK %avatar% もじょ なんとかやっつけたいけどめちゃ強そうなのだ"
                       , "SPEAK %avatar% もじょ 無理そうなら逃げるのだ"
                     ]
                 }
              }
        }
      , "passage1": {
            "id": 98001001
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [1,10]

          , "gimmicks": {
                "next_stage2": {
                    "trigger": "hero"
                  , "pos": [1, 4]
                  , "type": "goto"
                  , "room": "passage2"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[14,1]
                  , "type": "treasure"
                  , "lv_recv": true
                }
              , "treasure2": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[1,6]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "one_shot": 980010011
                }
              , "treasure4": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[13,6]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 980010013
                }
              , "treasure5": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[4,1]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 980010014
                }
              , "enemy2.1": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "unit"
                  , "unit": {
                         "pos": [6, 6]
                       , "character_id":-9101
                       , "icon":"avatarF"
                       , "code": "stand1"
                     }
                   , "chain": "enemy2.2"
                 }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 7]
                      , "character_id":-9101
                      , "icon":"avatarF"
                      , "code": "stand2"
                    }
                }
              , "enemy2.10": {
                    "trigger": "hero"
                  , "pos":[7,8], "rb":[10,11]
                  , "type": "unit"
                  , "unit": {
                         "pos": [5, 6]
                       , "character_id":-9102
                       , "icon":"avatarF"
                       , "code": "stand10"
                     }
                   , "chain": "enemy2.11"
                 }
              , "enemy2.11": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 6]
                      , "character_id":-9102
                      , "icon":"avatarF"
                      , "code": "stand11"
                    }
                   , "chain": "enemy2.4"
                }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 8]
                      , "character_id":-9106
                      , "icon":"avatarE"
                      , "code": "stand4"
                      , "align": 2
                      , "act_brain": "destine"
                      , "destine_pos": [12,4]
                    }
                  , "chain": "find2"
                }
               , "find2": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 3"
                       , "LINES %stand10% ウ・・ウウウ・・"
                       , "LINES %stand11% ウ・・ウウウ・・"
                       , "LINES %avatar% わ、また出た。でもナニあれ・・"
                       , "SPEAK %avatar% もじょ 化けそこないなのだ・・取り込んだ姿が保てなくなってるのだ"
                       , "LINES %avatar% き・・気持ちわる・・"
                       , "UALGN %avatar% 2"
                       , "LINES %stand4% シクシク・・シクシク・・"
                       , "LINES %avatar% ん？なんだろ？子供のボク・・？"
                       , "SPEAK %avatar% もじょ なんで泣いてるのだ？"
                       , "LINES %avatar% ボクに聞かれてもわかるわけないでしょ・・"
                     ]
                 }
               , "find3": {
                     "type": "lead"
                   , "leads": [
                         "LINES %stand4% シクシク・・シクシク・・"
                       , "UALGN %avatar% 2"
                       , "LINES %avatar% あっ、ちょっと待って・・！"
                     ]
                   , "trigger": "all"
                   , "pos": [15, 7], "rb":[17,10]
                   , "igniter": "stand4"
                 }
               , "disappear1": {
                     "type": "unit_exit"
                   , "exit_target": "stand4"
                   , "trigger": "all"
                   , "pos": [12, 4]
                   , "igniter": "stand4"
                   , "chain": "find4"
                 }
               , "find4": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% き、消えた？"
                       , "SPEAK %avatar% もじょ どこいったのだ・・？"
                       , "LINES %avatar% とりあえずあそこまで行ってみよう"
                       , "SPEAK %avatar% もじょ もうほっとくのだ。どうしたのだ？"
                       , "LINES %avatar% だって・・泣いてるし・・"
                       , "LINES %avatar% ボクも小さい頃は・・よく泣いてたから・・"
                       , "SPEAK %avatar% もじょ ・・・"
                     ]
                 }
              , "enemy2.100": {
                    "trigger": "hero"
                  , "pos":[4,1], "rb":[8,3]
                  , "type": "unit"
                  , "unit": {
                         "pos": [2, 3]
                       , "character_id":-9106
                       , "icon":"avatarE"
                       , "code": "stand100"
                       , "act_brain": "destine"
                       , "destine_pos": [1,4]
                     }
                   , "chain": ["find5", "galuf_attack"]
                 }
               , "find5": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 0"
                       , "LINES %stand100% シクシク・・"
                       , "LINES %galuf% どうした・・またいじめられたか？"
                       , "LINES %stand100% シクシク・・"
                       , "LINES %stand100% おとうさんも…おかあさんもいないの…"
                       , "LINES %avatar% やめて・・"
                       , "LINES %stand100% ボクは呪われた血が入っているから…"
                       , "LINES %avatar% もうやめて！！なんのこと！？"
                       , "SPEAK %avatar% もじょ ドッペルゲンガーの精神汚染なのだ…"
                       , "SPEAK %avatar% もじょ 記憶までコピーしてるのだ。危ないからもう逃げるのだ"
                       , "UALGN %stand100% 2"
                       , "LINES %stand100% …ニゲラレルトオモッテルノ？"
                       , "UALGN %galuf% 2"
                       , "LINES %galuf% グググ…ゲゲゲ…！"
                       , "LINES %stand100% イヒヒヒヒ！！！！"
                       , "LINES %avatar% ひ、ひえぇ・・"
                     ]
                 }
               , "disappear2": {
                     "type": "unit_exit"
                   , "exit_target": "stand100"
                   , "trigger": "all"
                   , "pos": [1, 4]
                   , "igniter": "stand100"
                   , "chain": "find6"
                 }
               , "galuf_attack": {
                     "type": "property"
                   , "unit": "galuf"
                   , "change": {"act_brain": "generic"}
                 }
               , "find6": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 1"
                       , "LINES %avatar% あっ、待ちなさい！"
                       , "UALGN %galuf% 3"
                       , "LINES %galuf% ヒョヒョヒョ・・！ここは通さんぞい"
                       , "UALGN %avatar% 0"
                       , "LINES %avatar% き・・気持ち悪い。どっか行け！ハゲ！"
                       , "LINES %galuf% な！師匠をなんじゃと思っとるか！"
                       , "LINES %avatar% あんたドッペルゲンガーでしょ？"
                       , "LINES %galuf% そうじゃが・・人の心は無いんかい"
                       , "SPEAK %avatar% もじょ じじいに化けたが運の尽きなのだ"
                       , "SPEAK %avatar% もじょ 遠慮はいらないのだ。本人と思ってぶちのめすのだ！"
                       , "LINES %galuf% ･･ならばこのじじいの真の姿を見せてやろう･･"
                       , "SPEAK %avatar% もじょ 姿が変わっていくのだ･･"
                       , "SPEAK %avatar% もじょ プ、プクク・・"
                       , "LINES %avatar% プクク・・プククク・・"
                       , "LINES %galuf% ･･何がおかしい。本人はこの姿を見るだに退散したぞ？"
                       , "LINES %galuf% 覚悟するがよい！！"
                     ]
                 }
              , "treasure3": {
                    "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980010015
                }
              , "fungi2": {
                    "pos": [1, 8]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [3, 6]
                  , "ornament": "fungi"
                }
              , "fungi5": {
                    "pos": [6, 1]
                  , "ornament": "fungi"
                }
              , "fungi6": {
                    "pos": [11, 10]
                  , "ornament": "fungi"
                }
              , "fungi8": {
                    "pos": [15, 7]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [2,4]
                  , "align": 3
                  , "character_id":-9103
                  , "icon":"shisyou"
                  , "code": "galuf"
                  , "act_brain": "rest"
                  , "trigger_gimmick": "treasure3"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "passage2": {
            "id": 98001002
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [5,7]
          , "gimmicks": {
                "door11": {
                    "trigger": "hero"
                  , "pos": [3, 0]
                  , "type": "goto"
                  , "room": "passage3"
                }
              , "door12": {
                    "trigger": "hero"
                  , "pos": [5, 0]
                  , "type": "goto"
                  , "room": "passage4"
                }
              , "door13": {
                    "trigger": "hero"
                  , "pos": [7, 0]
                  , "type": "goto"
                  , "room": "passage3"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[1,5]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 980010021
                }
              , "torch1": {
                    "pos": [1, 1]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [1, 3]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [9, 1]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [9, 3]
                  , "ornament": "torch"
                }
              , "fungi2": {
                    "pos": [1, 7]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [8, 5]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [3,1]
                  , "character_id":-9104
                  , "icon":"avatarS"
                  , "code": "stand1"
                  , "act_brain": "rest"
                }
              , {
                    "pos": [5,1]
                  , "character_id":-9104
                  , "icon":"avatarS"
                  , "code": "stand2"
                  , "act_brain": "rest"
                }
              , {
                    "pos": [7,1]
                  , "character_id":-9104
                  , "icon":"avatarS"
                  , "code": "stand3"
                  , "act_brain": "rest"
                }
            ]
        }
      , "passage3": {
            "id": 98001003
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [4,5]
          , "gimmicks": {
                "next_stage": {
                    "condition": {"reason":"door11"}
                  , "trigger": "hero"
                  , "pos": [1, 1]
                  , "type": "goto"
                  , "room": "passage2"
                  , "ornament": "goto"
                }
              , "next_stage2": {
                    "condition": {"reason":"door13"}
                  , "trigger": "hero"
                  , "pos": [1, 1]
                  , "type": "goto"
                  , "room": "passage2"
                  , "ornament": "goto"
                }
              , "next_stage3": {
                    "condition": {"reason":"door21"}
                  , "trigger": "hero"
                  , "pos": [1, 1]
                  , "type": "goto"
                  , "room": "passage4"
                  , "ornament": "goto"
                }
              , "treasure1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[4,1]
                  , "item_id": 1905
                  , "one_shot": 980010031
                }
              , "treasure2": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[7,3]
                  , "lv_recv": true
                }
              , "fungi1": {
                    "pos": [1, 3]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [1, 4]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [7, 4]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [7,1]
                  , "character_id":-9105
                  , "icon":"avatarS"
                  , "code": "stand1"
                }
            ]
        }
      , "passage4": {
            "id": 98001004
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [5,7]
          , "gimmicks": {
                "door21": {
                    "trigger": "hero"
                  , "pos": [4, 0]
                  , "type": "goto"
                  , "room": "passage3"
                }
              , "door22": {
                    "trigger": "hero"
                  , "pos": [6, 0]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "kakure_zone": {
                    "trigger": "hero"
                  , "pos": [0, 5]
                  , "type": "goto"
                  , "room": "start"
                  , "chain": "find1"
                }
               , "find1": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% おっ、落ちる！！"
                     ]
                 }
              , "treasure1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[9,4]
                  , "lv_recv": true
                }
              , "torch1": {
                    "pos": [1, 1]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [1, 3]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [9, 1]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [9, 3]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [1, 6]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [4,1]
                  , "character_id":-9104
                  , "icon":"avatarS"
                  , "code": "stand1"
                  , "act_brain": "rest"
                }
              , {
                    "pos": [6,1]
                  , "character_id":-9104
                  , "icon":"avatarS"
                  , "code": "stand2"
                  , "act_brain": "rest"
                }
            ]
        }
      , "passage5": {
            "id": 98001005
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [9,19]
          , "start_pos_on": {
                "warp1": [13,12]
              , "warp2": [13,10]
              , "warp3": [3,11]
              , "warp4": [13,10]
              , "warp5": [17,9]
            }

          , "gimmicks": {
                "start": {
                    "condition": {"reason":"door22"}
                  , "trigger": "rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                       ,"LINES %avatar% わ、なんだここ・・？"
                       , "SPEAK %avatar% もじょ 迷路みたいなのだ・・"
                    ]
                }
              , "next_stage": {
                    "trigger": "hero"
                  , "pos": [9, 2]
                  , "type": "goto"
                  , "room": "passage6"
                }
              , "treasure1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[15,17]
                  , "lv_recv": true
                }
              , "treasure2": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[7,18]
                  , "lv_recv": true
                }
              , "treasure3": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[7,5]
                  , "lv_recv": true
                }
              , "treasure4": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos":[17,3]
                  , "lv_recv": true
                }
              , "treasure5": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[5,11]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "one_shot": 980010051
                }
              , "treasure6": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[9,5]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 980010052
                }
              , "treasure7": {
                    "trigger": "player"
                  , "ornament": "twinkle"
                  , "pos":[18,11]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 980010053
                }
              , "warp1": {
                    "trigger": "hero"
                  , "pos": [15, 15]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "warp2": {
                    "trigger": "hero"
                  , "pos": [7, 15]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "warp3": {
                    "trigger": "hero"
                  , "pos": [17, 17]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "warp4": {
                    "trigger": "hero"
                  , "pos": [1, 7]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "warp5": {
                    "trigger": "hero"
                  , "pos": [2, 13]
                  , "type": "goto"
                  , "room": "passage5"
                }
              , "warp_speak1": {
                    "condition": {"reason": ["warp1", "warp3", "warp4"]}
                  , "trigger": "rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ん？どこだここ・・？"
                       , "SPEAK %avatar% もじょ どうやらハズレのようなのだ・・"
                    ]
                }
              , "enemy5.1": {
                    "trigger": "hero"
                  , "pos":[9,15]
                  , "type": "unit"
                  , "unit": {
                         "pos": [11, 16]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.2": {
                    "trigger": "hero"
                  , "pos":[11,17]
                  , "type": "unit"
                  , "unit": {
                         "pos": [9, 15]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.3": {
                    "trigger": "hero"
                  , "pos":[7,17]
                  , "type": "unit"
                  , "unit": {
                         "pos": [1, 16]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.4": {
                    "trigger": "hero"
                  , "pos":[17,13], "rb":[18,14]
                  , "type": "unit"
                  , "unit": {
                         "pos": [18, 17]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                  , "chain": "enemy5.5"
                 }
              , "enemy5.5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 14]
                      , "character_id":-9105
                      , "icon":"avatarS"
                    }
                }
              , "enemy5.6": {
                    "trigger": "hero"
                  , "pos":[9,12]
                  , "type": "unit"
                  , "unit": {
                         "pos": [4, 11]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.7": {
                    "trigger": "hero"
                  , "pos":[1,15], "rb":[1,17]
                  , "type": "unit"
                  , "unit": {
                         "pos": [7, 13]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.8": {
                    "trigger": "hero"
                  , "pos":[7,10], "rb":[10,10]
                  , "type": "unit"
                  , "unit": {
                         "pos": [6, 5]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                  , "chain": "enemy5.9"
                 }
              , "enemy5.9": {
                    "type": "unit"
                  , "unit": {
                         "pos": [1, 11]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                  , "chain": "enemy5.10"
                 }
              , "enemy5.10": {
                    "type": "unit"
                  , "unit": {
                         "pos": [2, 5]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.11": {
                    "trigger": "hero"
                  , "pos":[16,5], "rb":[18,8]
                  , "type": "unit"
                  , "unit": {
                         "pos": [13, 6]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                  , "chain": "enemy5.12"
                 }
              , "enemy5.12": {
                    "type": "unit"
                  , "unit": {
                         "pos": [12, 7]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                  , "chain": "enemy5.13"
                 }
              , "enemy5.13": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 8]
                      , "character_id":-9106
                      , "icon":"avatarE"
                      , "code": "stand1"
                      , "act_brain": "destine"
                      , "destine_pos": [9,2]
                    }
                  , "chain": "find1"
                }
              , "enemy5.14": {
                    "trigger": "hero"
                  , "pos":[3,11], "rb":[5,14]
                  , "type": "unit"
                  , "unit": {
                         "pos": [7, 13]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
              , "enemy5.15": {
                    "trigger": "hero"
                  , "pos":[2,3], "rb":[5,3]
                  , "type": "unit"
                  , "unit": {
                         "pos": [1, 6]
                       , "character_id":-9105
                       , "icon":"avatarS"
                     }
                 }
               , "disappear1": {
                     "type": "unit_exit"
                   , "exit_target": "stand1"
                   , "trigger": "all"
                   , "pos": [9, 2]
                   , "igniter": "stand1"
                 }
               , "find1": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% あっ！いた！"
                     ]
                 }
              , "torch1": {
                    "pos": [8, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [10, 3]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [8, 17]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [10, 17]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [1, 5]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [1, 10]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [1, 11]
                  , "ornament": "fungi2"
                }
              , "fungi4": {
                    "pos": [1, 17]
                  , "ornament": "fungi2"
                }
              , "fungi5": {
                    "pos": [16, 3]
                  , "ornament": "fungi"
                }
              , "fungi6": {
                    "pos": [17, 3]
                  , "ornament": "fungi"
                }
              , "fungi7": {
                    "pos": [18, 11]
                  , "ornament": "fungi"
                }
            }
        }
      , "passage6": {
            "id": 98001006
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [5,6]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% あっ、見つけた！ようやく追いついたわね"
                      , "LINES %stand1% ・・・"
                      , "LINES %avatar% …で､あなたは誰？"
                      , "LINES %stand1% ボクはあなただよ"
                      , "LINES %stand1% あなたの心で一番弱い部分、かな"
                      , "LINES %avatar% ボクにはシショーがいるもん。一人じゃないよ"
                      , "SPEAK %avatar% もじょ あんなゲスじじいでも・・泣かせるのだ・・"
                      , "LINES %avatar% それよりさっき言ってた呪われた血とか何とか"
                      , "LINES %stand1% 知らないの？うふふ"
                      , "LINES %avatar% どういうこと？"
                      , "LINES %stand1% …うふふ。勝ったら教えてあげるよ"
                    ]
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "chain": "endspeak2"
                }
               , "endspeak": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% 勝った・・"
                       , "LINES %avatar% 約束だよ！教えて！さっきのこと！"
                       , "LINES %stand1% ・・・マ、マルティーニ"
                       , "LINES %stand1% ・・・"
                     ]
                 }
               , "endspeak2": {
                     "type": "lead"
                   , "leads": [
                         "SPEAK %avatar% もじょ 消えてしまったのだ・・"
                       , "LINES %avatar% 機械都市マルティーニ・・何の関係があるんだろ"
                       , "LINES %avatar% そういや小さいころのことは全然覚えてないや"
                       , "SPEAK %avatar% もじょ ま、つらいことは忘れた方がいいのだ"
                       , "SPEAK %avatar% もじょ 必要なことならいつか思い出すのだ"
                       , "LINES %avatar% そだね。･･じゃ、シショーのとこにもどろっか"
                       , "SPEAK %avatar% もじょ そうするのだ"
                     ]
                  , "chain": "treasure1"
                 }
              , "treasure1": {
                    "type": "treasure"
                  , "item_id": 1906
                  , "one_shot": 980010061
                }
              , "torch1": {
                    "pos": [1, 1]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [1, 2]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [1, 3]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [9, 1]
                  , "ornament": "torch"
                }
              , "torch5": {
                    "pos": [9, 2]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [9, 3]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [2, 1]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [3, 4]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [4, 4]
                  , "ornament": "fungi"
                }
              , "fungi4": {
                    "pos": [8, 1]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                    "pos": [5,1]
                  , "character_id":-9106
                  , "icon":"avatarE"
                  , "code": "stand1"
                  , "act_brain": "rest"
                  , "early_gimmick": "endspeak"
                  , "trigger_gimmick": "goal"
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
    }
}
