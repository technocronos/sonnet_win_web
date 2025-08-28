{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "woman", "uncle"]
   ,"rooms": {
        "start": {
            "id": 98041000
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos_on": {
                "start": [9,1]
            }
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわー・・ヌメール湿原久しぶり・・"
                      , "LINES %avatar% たしかムーリトは左だったと思うけど道がふさがっちゃってるな"
                      , "LINES %avatar% 回り道するしかないか・・"
                    ]
                  , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980410000
                  , "leads": [
                        "LINES %avatar% あ、あと何度か言ってるけどイベントは上級者向けでムズカシイよ"
                      , "LINES %avatar% ストーリーやモンスターの洞窟でレベルを上げて挑もうね"
                      , "LINES %avatar% 時間かけてじっくり攻略するといいよ"
                    ]
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [13,1]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980410001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [0,4]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980410002
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
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [18,4]
                  , "type": "goto"
                  , "room": "room1"
                  , "ornament": "goto"
                }
            }
          , "units": [
                {
                    "pos": [2,6]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "add_level":-13
                }
              , {
                    "pos": [9,5]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "add_level":-13
                }
              , {
                    "pos": [16,6]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "add_level":-13
                }
            ]
        }
      , "room1": {
            "id": 98041001
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos": [0,2]
          , "gimmicks": {
                "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [0,7], "rb": [9,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 12]
                      , "character_id":-9135
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 16]
                      , "character_id":-9135
                      , "icon":"shadow"
                    }
                }
              , "enemy1-3": {
                    "trigger": "hero"
                  , "pos": [0,15], "rb": [9,21]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 20]
                      , "character_id":-9135
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-4"
                }
              , "enemy1-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 21]
                      , "character_id":-9135
                      , "icon":"shadow"
                    }
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [0,11]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1905
                  , "one_shot": 980410011
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [8,21]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [1,20]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "goto_room2_2": {
                    "trigger": "hero"
                  , "pos": [1,21]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
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
                    "pos": [1,6]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "add_level":-13
                }
            ]
        }
      , "room2": {
            "id": 98041002
          , "battle_bg": "forest2"
          , "environment": "cave"
          , "start_pos": [16,16]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% どんどん暗くなってきた・・"
                      , "LINES %avatar% もう夜になっちゃうよ・・。"
                      , "LINES %avatar% でも何か前に来た時と全然違うなぁ・・"
                    ]
                }
              , "enemy2-1": {
                    "trigger": "hero"
                  , "pos": [9,12], "rb": [13,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 10]
                      , "character_id":-9136
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 11]
                      , "character_id":-9136
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [9,5], "rb": [15,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 3]
                      , "character_id":-9136
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 3]
                      , "character_id":-9136
                      , "icon":"shadow"
                    }
                }
              , "enemy2-5": {
                    "trigger": "hero"
                  , "pos": [0,3], "rb": [5,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 9]
                      , "character_id":-9137
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-6"
                }
              , "enemy2-6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 9]
                      , "character_id":-9137
                      , "icon":"shadow"
                    }
                }
              , "enemy2-7": {
                    "trigger": "hero"
                  , "pos": [0,8], "rb": [5,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 13]
                      , "character_id":-9137
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-8"
                }
              , "enemy2-8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 14]
                      , "character_id":-9137
                      , "icon":"shadow"
                    }
                }
              , "speak2-1": {
                    "trigger": "hero"
                  , "pos": [13,3], "rb": [16,7]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% あれは・・リリパットだ・・"
                      , "LINES %avatar% 何か強そう・・無理して戦わなくてもいいか"
                      , "LINES %avatar% でもあんな高位の妖精がうろついてるなんて"
                      , "LINES %avatar% 精霊の世界とはもう繋がってるな・・"
                    ]
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [14,13]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [5,16]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [20,5]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980410021
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [0,6]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                }
              , "treasure2-5": {
                    "trigger": "player"
                  , "pos": [20,3]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [0,16]
                  , "type": "goto"
                  , "room": "room3"
                  , "ornament": "goto"
                }
              , "moon": {
                    "pos": [9, -3]
                  , "ornament": "moon"
                }
              , "tanpopo1": {
                    "pos": [9, 16]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [15, 14]
                  , "ornament": "tanpopo"
                }
              , "tanpopo3": {
                    "pos": [15, 15]
                  , "ornament": "tanpopo"
                }
              , "tanpopo4": {
                    "pos": [14, 16]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [9, 9]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [18, 7]
                  , "ornament": "blueflower"
                }
              , "blueflower3": {
                    "pos": [0, 5]
                  , "ornament": "blueflower"
                }
              , "blueflower4": {
                    "pos": [0, 4]
                  , "ornament": "blueflower"
                }
              , "blueflower5": {
                    "pos": [1, 4]
                  , "ornament": "blueflower"
                }
              , "blueflower6": {
                    "pos": [13, 11]
                  , "ornament": "blueflower"
                }
              , "blueflower7": {
                    "pos": [14, 10]
                  , "ornament": "blueflower"
                }
              , "blueflower8": {
                    "pos": [14, 11]
                  , "ornament": "blueflower"
                }
              , "blueflower9": {
                    "pos": [0, 13]
                  , "ornament": "blueflower"
                }
              , "blueflower10": {
                    "pos": [0, 14]
                  , "ornament": "blueflower"
                }
              , "blueflower11": {
                    "pos": [1, 13]
                  , "ornament": "blueflower"
                }
              , "blueflower12": {
                    "pos": [1, 14]
                  , "ornament": "blueflower"
                }
              , "susuki1": {
                    "pos": [0, 11]
                  , "ornament": "susuki"
                }
              , "susuki2": {
                    "pos": [2, 7]
                  , "ornament": "susuki"
                }
            }
          , "units": [
                {
                    "pos": [6,6]
                  , "character_id":-3101
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "wistful"
                  , "wistful_slow": 2
                  , "add_level":-13
                }
              , {
                    "pos": [17,5]
                  , "character_id":-9139
                  , "icon":"shadow2"
                  , "align": 1
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "room3": {
            "id": 98041003
          , "battle_bg": "forest2"
          , "environment": "cave"
          , "start_pos": [10,21]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あっ、ムーリトだ！"
                      , "LINES %avatar% ここらへんにいるはず・・"
                    ]
                }
              , "enemy3-1": {
                    "trigger": "hero"
                  , "pos": [4,14], "rb": [10,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 15]
                      , "character_id":-9138
                      , "icon":"shadow"
                    }
                }
              , "enemy3-2": {
                    "trigger": "hero"
                  , "pos": [0,20], "rb": [10,24]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 24]
                      , "character_id":-9138
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3-3"
                }
              , "enemy3-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 24]
                      , "character_id":-9138
                      , "icon":"shadow"
                    }
                }
              , "treasure4-1": {
                    "trigger": "player"
                  , "pos": [10,17]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "ornament": "twinkle"
                  , "one_shot": 980410031
                }
              , "treasure4-2": {
                    "trigger": "player"
                  , "pos": [1,23]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980410032
                }
              , "treasure4-3": {
                    "trigger": "player"
                  , "pos": [0,15]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "mourito_locus2": {
                    "trigger": "hero"
                  , "pos": [9,15]
                  , "type": "ap_recov"
                  , "ornament": "ap_circle"
                  , "memory_shot": "mourito_locus2"
                }
              , "mourito_locus3": {
                    "pos": [6,7]
                  , "ornament": "ap_circle"
                }
              , "speak1": {
                    "trigger": "hero"
                  , "pos": [0,6], "rb": [10,9]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% あっ、いた！"
                    ]
                   , "chain": "tresD"
                }
              , "tresD": {
                    "type": "drama"
                  , "drama_id": 9804101
                }
              , "end_speak": {
                     "type": "lead"
                  , "leads": [
                        "SPEAK %ohra% オーラ ううっ・・馬鹿な・・"
                      , "LINES %avatar% さあ、もじょ。帰ろう"
                      , "SPEAK %ohra% もじょ なあ、姉ちゃん・・どうして兄者は死んだのだ・・？"
                      , "SPEAK %ohra% オーラ 戦争が起こってな。それで殺されたんだ。かわいそうに・・。"
                      , "SPEAK %ohra% もじょ 戦争・・いつの間にそんなことになったのだ？"
                      , "SPEAK %ohra% オーラ ちょうど5年前にな。"
                      , "SPEAK %ohra% オーラ 皇太子が殺されたことをみんなに悟られるわけにはいかないんだよ。"
                      , "SPEAK %ohra% もじょ でも姿形はそっくりでもいずれバレるのだ・・。"
                      , "SPEAK %ohra% オーラ いいんだ。今だけでも。実は産まれたばかりだが弟もいる。"
                      , "SPEAK %ohra% もじょ え？そうなのだ？"
                      , "SPEAK %ohra% オーラ しかしまだ赤ん坊だ。皇太子に即位なんて無理だ。"
                      , "SPEAK %ohra% もじょ まーそりゃそうなのだ・・"
                      , "SPEAK %ohra% オーラ 実は父上も殺されたんだ。その後即位する直前に皇太子も殺された。"
                      , "SPEAK %ohra% オーラ 戻ってもお前もまた殺されるだけだ。それだけむこうの軍勢は強烈なんだよ。"
                      , "SPEAK %ohra% もじょ ・・・"
                      , "SPEAK %ohra% もじょ ・・・姉ちゃん。国を助けるためだけにひとまず戻るんならいいのだ。"
                      , "SPEAK %ohra% オーラ も・・もじょ・・"
                      , "LINES %avatar% もじょ、何言ってるの？"
                      , "SPEAK %ohra% もじょ 相手をやっつけるまで、ひとまず戻って・・"
                      , "SPEAK %ohra% もじょ その後、その弟に皇太子は譲ってもじょはこの世界に戻るのだ。"
                      , "SPEAK %ohra% もじょ 姉ちゃん。それでいいのだ？"
                      , "SPEAK %ohra% オーラ ・・あ、ああ・・。ありがたいよ。"
                      , "LINES %avatar% そういうことならボクも行くよ。戦力は多い方がいいでしょ？"
                      , "SPEAK %ohra% オーラ そうだな。ここまでの力があるならありがたい。"
                      , "LINES %avatar% そうと決まったら精霊の世界に行こう！"
                      , "SPEAK %ohra% もじょ 行くのだ！"
                      , "SPEAK %ohra% オーラ よし、では精霊の世界に転送するぞ・・！"
                      , "NOTIF 後編に続く・・"
                    ]
                  , "chain": "goal"
                 }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "moon": {
                    "pos": [9, 0]
                  , "ornament": "moon"
                }
              , "blueflower1": {
                    "pos": [10, 15]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [10, 16]
                  , "ornament": "blueflower"
                }
              , "blueflower3": {
                    "pos": [9, 14]
                  , "ornament": "blueflower"
                }
              , "tanpopo1": {
                    "pos": [8, 20]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [3, 7]
                  , "ornament": "tanpopo"
                }
              , "tanpopo3": {
                    "pos": [4, 6]
                  , "ornament": "tanpopo"
                }
              , "tanpopo4": {
                    "pos": [6, 7]
                  , "ornament": "tanpopo"
                }
              , "tanpopo5": {
                    "pos": [9, 7]
                  , "ornament": "tanpopo"
                }
              , "tanpopo6": {
                    "pos": [10, 7]
                  , "ornament": "tanpopo"
                }

            }
          , "units": [
                {
                    "pos": [6,6]
                  , "character_id":-9140
                  , "icon":"shadowG"
                  , "act_brain": "rest"
                  , "code": "ohra"
                  , "bgm": "bgm_bigboss"
                  , "early_gimmick": "end_speak"
                }
            ]
        }
    }
}
