{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "woman", "uncle"]
   , "extra_maptips": [2908, 2929, 2930]
   ,"rooms": {
        "start": {
            "id": 98021000
          , "battle_bg": "wetlands"
          , "environment": "grass"
          , "start_pos": [5, 1]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ここが海底洞窟かぁ・・"
                      , "SPEAK %avatar% もじょ なかなか幻想的な所なのだ"
                    ]
                  , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980210000
                  , "leads": [
                        "SPEAK %avatar% もじょ あ、あと何度か言ってるけどイベントは上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "enemy1": {
                    "trigger": "hero"
                  , "pos": [1,2], "rb": [5,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 8]
                      , "character_id":-9123
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2"
                }
              , "enemy2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 9]
                      , "character_id":-9123
                      , "icon":"shadow"
                    }
                  , "chain": "speak1"
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんか出た・・"
                      , "SPEAK %avatar% もじょ 見たこともないモンスターなのだ"
                      , "LINES %avatar% 古代魚みたいな感じだね"
                    ]
                }
              , "enemy3": {
                    "trigger": "hero"
                  , "pos": [12,3], "rb": [16,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [16, 4]
                      , "character_id":-9123
                      , "icon":"shadow"
                    }
                  , "chain": "enemy4"
                }
              , "enemy4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 10]
                      , "character_id":-9123
                      , "icon":"shadow"
                    }
                }
              , "enemy5": {
                    "trigger": "hero"
                  , "pos": [14,8], "rb": [18,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 10]
                      , "character_id":-9124
                      , "icon":"shadow"
                    }
                  , "chain": "speak2"
                }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% まだいた・・"
                      , "SPEAK %avatar% もじょ また見たこともないモンスターなのだ"
                    ]
                }
              , "enemy6": {
                    "trigger": "hero"
                  , "pos": [2,11], "rb": [8,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 11]
                      , "character_id":-9124
                      , "icon":"shadow"
                    }
                  , "chain": "speak3"
                }
              , "speak3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% げげっ！"
                      , "SPEAK %avatar% もじょ やっぱなんかいたのだ"
                    ]
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [2,2]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980210001
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [7,3]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980210004
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [16,6]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980210002
                }
              , "treasure1-4": {
                    "trigger": "player"
                  , "pos": [14,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-5": {
                    "trigger": "player"
                  , "pos": [5,11]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980210003
                }
              , "find_speak": {
                     "trigger": "hero"
                   , "pos": [8,11], "rb": [12,11]
                   , "type": "lead"
                   , "leads": [
                          "LINES %avatar% あれ？何かここ通れるな・・"
                        , "SPEAK %avatar% もじょ 見えない道があるのだ"
                     ]
                 }
              , "bubble0-1": {
                    "pos": [17, 4]
                  , "ornament": "bubble"
                }
              , "bubble0-2": {
                    "pos": [1, 3]
                  , "ornament": "bubble"
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [14,10]
                  , "type": "goto"
                  , "room": "room1"
                  , "ornament": "goto"
                }
            }
        }
      , "room1": {
            "id": 98021001
          , "battle_bg": "wetlands"
          , "environment": "grass"
          , "start_pos": [16,11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 1"
                      , "LINES %avatar% 広いなぁ・・"
                      , "SPEAK %avatar% もじょ まだ奥がありそうなのだ"
                    ]
                }
              , "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [11,6], "rb": [16,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [11, 7]
                      , "character_id":-9124
                      , "icon":"shadow"
                    }
                }
              , "enemy1-2": {
                    "trigger": "hero"
                  , "pos": [6,7], "rb": [10,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 3]
                      , "character_id":-9124
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-3"
                }
              , "enemy1-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2, 5]
                      , "character_id":-9124
                      , "icon":"shadow"
                    }
                }
              , "enemy1-4": {
                    "trigger": "hero"
                  , "pos": [9,1], "rb": [15,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 2]
                      , "character_id":-9125
                      , "icon":"shadow"
                    }
                }
              , "enemy1-5": {
                    "trigger": "hero"
                  , "pos": [13,4], "rb": [18,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [19, 9]
                      , "character_id":-9125
                      , "icon":"shadow"
                    }
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [3,2]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [11,6]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "ornament": "twinkle"
                  , "one_shot": 980210011
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [16,6]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980210012
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [15,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "bubble1-1": {
                    "pos": [17, 5]
                  , "ornament": "bubble"
                }
              , "bubble1-2": {
                    "pos": [1, 3]
                  , "ornament": "bubble"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [18,9]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
            }
        }
      , "room2": {
            "id": 98021002
          , "battle_bg": "wetlands"
          , "environment": "grass"
          , "start_pos": [2,14]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% もうちょいかな・・"
                    ]
	               , "chain": "enemy2-1"
                }
              , "enemy2-1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 12]
                      , "character_id":-9125
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 13]
                      , "character_id":-9125
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [4,9], "rb": [9,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 8]
                      , "character_id":-9126
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 8]
                      , "character_id":-9126
                      , "icon":"shadow"
                    }
                }
              , "enemy2-5": {
                    "trigger": "hero"
                  , "pos": [4,3], "rb": [9,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 3]
                      , "character_id":-9126
                      , "icon":"shadow"
                    }
                }
              , "enemy2-6": {
                    "trigger": "hero"
                  , "pos": [11,6], "rb": [12,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 9]
                      , "character_id":-9126
                      , "icon":"shadow"
                    }
                }
              , "treasure3-1": {
                    "trigger": "player"
                  , "pos": [2,11]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980210021
                }
              , "treasure3-2": {
                    "trigger": "player"
                  , "pos": [9,13]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980210022
                }
              , "treasure3-3": {
                    "trigger": "player"
                  , "pos": [5,3]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-4": {
                    "trigger": "player"
                  , "pos": [12,8]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980210023
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [7,0]
                  , "type": "goto"
                  , "room": "room3"
                  , "ornament": "goto"
                }
              , "bubble2-1": {
                    "pos": [12, 3]
                  , "ornament": "bubble"
                }
              , "bubble2-2": {
                    "pos": [13, 4]
                  , "ornament": "bubble"
                }
              , "bubble2-3": {
                    "pos": [3, 7]
                  , "ornament": "bubble"
                }
            }
          , "units": [
                {
                   "pos": [9,2]
                  , "character_id":-9127
                  , "icon":"shadow2"
                  , "code":"seiren"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "room3": {
            "id": 98021003
          , "battle_bg": "wetlands"
          , "environment": "grass"
          , "start_pos": [6,7]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% あれがアトランティスの主？"
                      , "SPEAK %avatar% もじょ そうみたいなのだ・・。"
                      , "LINES %posei% なんだお主らは・・"
                      , "LINES %avatar% あの・・。この大陸にかかってる呪いを解きに来たんです"
                      , "LINES %posei% なんだと・・？ククク・・笑止！"
                      , "LINES %posei% 呪いを解きたければ我を倒してみよ"
                      , "LINES %posei% さすれば解く方法を教えてやろう"
                      , "LINES %avatar% やっぱそれしかないのか・・"
                      , "SPEAK %avatar% もじょ なんでこうなるのだ・・"
                    ]
                }
               , "end_speak": {
                     "type": "lead"
                   , "leads": [
                          "UALGN %avatar% 3"
                        , "LINES %posei% ば・・馬鹿な・・！"
                        , "LINES %avatar% 約束だよ！呪いの解除方法を教えて！"
                        , "LINES %posei% それはできんな。"
                        , "LINES %posei% 我の霊子を利用しているだけで呪い自体は我の呪いではない。"
                        , "LINES %avatar% え？じゃ、誰の・・？"
                        , "LINES %posei% 人間自身じゃよ。"
                        , "LINES %avatar% えっ・・？嘘でしょ？"
                        , "LINES %posei% 嘘ではない。永遠の命を願った人間が呪いを大陸ごとかけたんじゃ。"
                        , "LINES %posei% 100年間時を止めて、いずれどこかで不老不死のような薬ができるまで待つ気なのじゃろう。"
                        , "LINES %posei% 愚かな人間が考えそうなことじゃ"
                        , "LINES %avatar% そんな・・"
                        , "LINES %posei% 我の命を断てば呪いは解除できる。"
                        , "LINES %posei% しかし、この大陸は我の霊子を吸って生きているからの。"
                        , "LINES %posei% 我の命を断つことはこの大陸の死をも意味する。"
                        , "LINES %avatar% じゃ、その呪いをかけた人間が解除したら戻るの？"
                        , "LINES %posei% そうじゃな。そやつを見つけて呪いを解除するがよかろう。"
                        , "LINES %posei% そやつが死んでも同じことじゃ。少しづつでも時は過ぎるのじゃからいずれは必ず呪いは解ける"
                        , "LINES %posei% それこそ不老不死の薬でも飲んでない限りな"
                        , "SPEAK %avatar% もじょ 誰が呪いをかけたか分かるのだ？"
                        , "LINES %avatar% いや、そりゃ分からないんじゃないの？"
                        , "SPEAK %avatar% もじょ 精霊と契約するときは自らの名を名乗るから知ってるんじゃないのだ？"
                        , "LINES %posei% しかし昔のことじゃからのう・・。名前はたしか・・。"
                        , "LINES %posei% ｘｘｘ、と言ったかな？"
                        , "LINES %avatar% えっ！？"
                        , "SPEAK %avatar% もじょ どういうことなのだ？"
                        , "LINES %avatar% ・・急いで村に戻ろう！"
                     ]
                   , "chain": "goto_last_room"
                 }
              , "bubble3-1": {
                    "pos": [2, 2]
                  , "ornament": "bubble"
                }
              , "bubble3-2": {
                    "pos": [9, 2]
                  , "ornament": "bubble"
                }

              , "goto_last_room": {
                    "type": "goto"
                  , "room": "last_room"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                   "pos": [6,3]
                  , "code":"posei"
                  , "character_id":-9128
                  , "icon":"shadowG"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                  , "early_gimmick": "end_speak"
                }
            ]
        }
      , "last_room": {
            "id": 98021004
          , "battle_bg": "wetlands"
          , "environment": "none"
          , "bgm": "bgm_home"
          , "start_pos": [6,7]
          , "gimmicks": {
                "end_speak": {
                     "type": "lead"
                   , "leads": [
                          "LINES %avatar% シャクワさん！"
                        , "SPEAK %shakuwa% シャクワ これで呪いは解除される・・。これでいいんだ・・。ありがとう。"
                        , "SPEAK %shakuwa% シャクワ ソフィア・・愛している・・"
                        , "SPEAK %shakuwa% シャクワ 俺もこれから行くよ・・"
                        , "LINES %avatar% やめて！シャクワさん！！"
                        , "ENVCG FF7A7A" 
                        , "SEPLY se_arts1" 
                        , "VIBRA 10"
                        , "DELAY 500"
                        , "VIBRA 00"
                        , "ENVCG FFFFFF" 
                     ]
                 }
             , "sophia_die": {
                   "type": "unit_exit"
                 , "exit_target": "sophia"
	               , "chain": "change_tip1"
               }
             , "change_tip1": {
                   "type": "square_change"
                 , "change_pos": [1,3]
                 , "change_tip": 2929
	               , "chain": "change_tip2"
               }
             , "change_tip2": {
                   "type": "square_change"
                 , "change_pos": [2,3]
                 , "change_tip": 2930
	               , "chain": "shakuwa_die"
               }
             , "shakuwa_die": {
                   "type": "unit_exit"
                 , "exit_target": "shakuwa"
               }
             , "change_tip3": {
                   "type": "square_change"
                 , "change_pos": [3,4]
                 , "change_tip": 2908
	               , "chain": "end_speak2"
               }
              , "end_speak2": {
                     "type": "lead"
                   , "leads": [
                          "DELAY 500"
                        , "LINES %avatar% シャクワさん・・"
                        , "SPEAK %avatar% もじょ 呪いが・・解除されていくのだ・・"
                        , "LINES %avatar% うん・・"
                     ]
                   , "chain": "goal"
                 }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                   "pos": [3,3]
                  , "code":"shakuwa"
                  , "align": 1
                  , "character_id":-9906
                  , "icon":"uncle"
                  , "early_gimmick": "end_speak"
                  , "trigger_gimmick": "change_tip3"
                  , "union": 1
                }
              , {
                   "pos": [2,3]
                  , "code":"sophia"
                  , "character_id":-9906
                  , "icon":"woman"
                  , "act_brain": "rest"
                }
            ]
        }
    }
}
