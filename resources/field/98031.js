{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "woman", "uncle"]
   ,"rooms": {
        "start": {
            "id": 98031000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [13, 2]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% とは言ったものの鬼になった人間って元にもどるもんなの？"
                      , "SPEAK %avatar% もじょ 戻らんと思うのだ"
                      , "LINES %avatar% だよねぇ・・聞いたことないもん"
                      , "SPEAK %avatar% もじょ まあ、魂を土に返してやるくらいなのだ"
                      , "LINES %avatar% うーん・・"
                    ]
                  , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980310000
                  , "leads": [
                        "SPEAK %avatar% もじょ あ、あと何度か言ってるけどイベントは上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [12,1], "rb": [14,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 8]
                      , "character_id":-9129
                      , "icon":"shadow"
                      , "code": "oni1"
                    }
                  , "chain": "oni1_speak"
                }
              , "oni1_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %oni1% オーン・・！"
                    ]
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 5]
                      , "character_id":-9129
                      , "icon":"shadow"
                      , "code": "oni2"
                    }
                  , "chain": "oni2_speak"
                }
              , "oni2_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %oni2% オーン・・！"
                    ]
                  , "chain": "speak0-1"
                }
              , "speak0-1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% で・・出た・・"
                      , "SPEAK %avatar% もじょ きもちわるいのだ"
                    ]
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [3,3], "rb": [8,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-9129
                      , "icon":"shadow"
                    }
                }
              , "torch1": {
                    "pos": [11, 5]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [15, 5]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [15, 8]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [11, 8]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [1, 6]
                  , "ornament": "fungi2"
                }
              , "fungi4": {
                    "pos": [1, 7]
                  , "ornament": "fungi2"
                }
              , "fungi5": {
                    "pos": [1, 11]
                  , "ornament": "fungi2"
                }
              , "onihime-appear": {
                    "trigger": "hero"
                  , "pos": [2,6], "rb": [8,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 8]
                      , "character_id":-9134
                      , "icon":"woman"
                      , "union": 1
                      , "act_brain": "rest"
                      , "code": "onihime"
                      , "brain_noattack": true
                      , "align": 3
                    }
                }
              , "onihime-speak": {
                    "trigger": "hero"
                  , "pos": [2,7]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %avatar% ・・あなたは誰？"
                      , "LINES %onihime% ・・我は鬼姫・・"
                      , "LINES %avatar% ・・あ、あなたが・・伝説の鬼姫？"
                      , "SPEAK %avatar% もじょ 間違いないのだ・・"
                      , "LINES %onihime% 一度鬼になったらもう元には戻らない。"
                      , "LINES %onihime% 我を殺さない限りね"
                      , "LINES %avatar% そんな・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                    ]
	               , "chain": "onihime-dissapear"
                }
             , "onihime-dissapear": {
                   "type": "unit_exit"
                 , "exit_target": "onihime"
	               , "chain": "hero-speak"
               }
              , "hero-speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あっ・・"
                      , "SPEAK %avatar% もじょ 行っちゃったのだ・・"
                    ]
	               , "chain": "gate-open"
                }
              , "gate-open": {
                    "type": "square_change"
                  , "change_pos": [2,8]
                  , "change_tip": 1850
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [15,8]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980310001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [2,5]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980310002
                }
              , "treasure0-3": {
                    "trigger": "player"
                  , "pos": [11,4]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [2,11]
                  , "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 98031001
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [1,3]
          , "start_pos_on": {
                "kakure_zone": [5,10]
            }
          , "gimmicks": {
                "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [1,3], "rb": [4,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 4]
                      , "character_id":-9129
                      , "icon":"shadow"
                    }
                }
              , "enemy1-2": {
                    "trigger": "hero"
                  , "pos": [5,3], "rb": [10,5]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 4]
                      , "character_id":-9129
                      , "icon":"shadow"
                    }
                }
              , "fungi1": {
                    "pos": [1, 6]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [1, 7]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [7, 7]
                  , "ornament": "fungi"
                }
              , "fungi4": {
                    "pos": [13, 3]
                  , "ornament": "fungi"
                }
              , "fungi5": {
                    "pos": [14, 3]
                  , "ornament": "fungi"
                }
              , "fungi6": {
                    "pos": [15, 4]
                  , "ornament": "fungi"
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [12,3]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [9,8]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980310011
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [6,10]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980310012
                }
              , "doorfrom2": {
                    "trigger": "hero"
                  , "pos": [7, 0]
                  , "type": "goto"
                  , "room": "passage3"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [12,11]
                  , "type": "goto"
                  , "room": "room2"
                }
              , "goto_room2-2": {
                    "trigger": "hero"
                  , "pos": [7,9]
                  , "type": "goto"
                  , "room": "room2"
                }
            }
          , "units": [
                {
                   "pos": [13,7]
                  , "character_id":-9130
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
              , {
                   "pos": [9,9]
                  , "character_id":-9130
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
            ]
        }
      , "room2": {
            "id": 98031002
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [18,12]
          , "start_pos_on": {
                "goto_room2-2": [18,12]
              , "hole1": [6,6]
              , "hole4": [12,13]
            }
          , "gimmicks": {
                "enemy2-1": {
                    "trigger": "hero"
                  , "pos": [16,4], "rb": [20,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 6]
                      , "character_id":-9131
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 6]
                      , "character_id":-9131
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [1,6], "rb": [7,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 9]
                      , "character_id":-9131
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 10]
                      , "character_id":-9131
                      , "icon":"shadow"
                    }
                }
              , "fungi1": {
                    "pos": [1, 8]
                  , "ornament": "fungi2"
                }
              , "fungi2": {
                    "pos": [6, 11]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [15, 5]
                  , "ornament": "fungi"
                }
              , "fungi4": {
                    "pos": [20, 8]
                  , "ornament": "fungi"
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [20,9]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980310025
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [17,0]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "one_shot": 980310021
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [12,0]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980310022
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [22,4]
                  , "type": "treasure"
                  , "item_id": 1906
                  , "ornament": "twinkle"
                  , "one_shot": 980310023
                }
              , "treasure2-5": {
                    "trigger": "player"
                  , "pos": [9,9]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "ornament": "twinkle"
                  , "one_shot": 980310024
                }
              , "treasure2-6": {
                    "trigger": "player"
                  , "pos": [2,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "hole1": {
                    "trigger": "hero"
                  , "pos": [10, 4]
                  , "type": "goto"
                  , "room": "room2"
                }
              , "hole4": {
                    "trigger": "hero"
                  , "pos": [22, 3]
                  , "type": "goto"
                  , "room": "room2"
                }
              , "kakure_zone": {
                    "trigger": "hero"
                  , "pos": [14, 12]
                  , "type": "goto"
                  , "room": "room1"
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [2,10]
                  , "type": "goto"
                  , "room": "room3"
                }
            }
          , "units": [
                {
                   "pos": [19,4]
                  , "character_id":-9130
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
              , {
                   "pos": [14,4]
                  , "character_id":-9130
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
              , {
                   "pos": [10,9]
                  , "character_id":-9132
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
            ]
        }
      , "room3": {
            "id": 98031003
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [5,11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% あっ！あれがコウ・ネリウスじゃない？"
                      , "SPEAK %avatar% もじょ ようやく見つけたのだ・・"
                    ]
                }
               , "nerius_speak": {
                     "trigger": "hero"
                   , "pos": [4,4], "rb": [6,5]
                   , "type": "lead"
                   , "leads": [
                          "UALGN %avatar% 3"
                        , "LINES %avatar% あなたがコウ・ネリウスさん？"
                        , "LINES %nerius% 何の事だ・・？鬼になる前の事などもはや忘れた"
                        , "LINES %nerius% 今はすべての人間を殺すのみ！貴様も食らってやろう！"
                     ]
                 }
               , "end_speak": {
                     "type": "lead"
                   , "leads": [
                          "UALGN %avatar% 3"
                        , "LINES %nerius% お・・思い出した・・私の名はコウ・ネリウス・・"
                        , "LINES %nerius% かつて吟遊詩人だった男・・"
                        , "LINES %avatar% ネリウスさん、大丈夫！？"
                        , "LINES %nerius% 私は・・もうこのまま消えたい・・罪を犯した償いだ・・"
                        , "SPEAK %avatar% もじょ それがいいのだ。戻っても行き場所は無いのだ。"
                        , "LINES %avatar% もじょ！"
                        , "LINES %avatar% ネリウスさん、このまま逃げてもいいの？待ってる人だって絶対いるよ。"
                        , "LINES %nerius% しかし・・もう人間には戻れない・・"
                        , "LINES %avatar% 鬼姫を倒せば戻れるって言ってたよ。人間に戻る気があるならボクが鬼姫を倒すから！"
                        , "LINES %nerius% ううっ・・。わかった。頼むよ。"
                        , "LINES %nerius% 人間に戻れたら今度こそまっとうに生きるよ"
                        , "LINES %avatar% わかった！待ってて！"
                     ]
                   , "chain": "goto_room4"
                 }
              , "torch1": {
                    "pos": [3, 4]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [7, 4]
                  , "ornament": "torch"
                }
              , "fungi1": {
                    "pos": [5, 3]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [6, 3]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [7, 3]
                  , "ornament": "fungi"
                }
              , "fungi4": {
                    "pos": [8, 4]
                  , "ornament": "fungi"
                }
              , "treasure3-1": {
                    "trigger": "player"
                  , "pos": [2,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-2": {
                    "trigger": "player"
                  , "pos": [4,11]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room4": {
                    "type": "goto"
                  , "room": "room4"
                }
            }
          , "units": [
                {
                    "pos": [5,4]
                  , "code":"nerius"
                  , "character_id":-9133
                  , "icon":"shadow2"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                  , "early_gimmick": "end_speak"
                }
              , {
                   "pos": [7,7]
                  , "character_id":-9132
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
              , {
                   "pos": [4,9]
                  , "character_id":-9132
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
            ]
        }
      , "room4": {
            "id": 98031004
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [5,8]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% いた・・鬼姫だ・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "SPEAK %avatar% もじょ 何か歌ってるのだ・・"
                      , "LINES %avatar% 聞いたことあるような・・"
                      , "LINES %onihime% よくここまで来たわね・・"
                      , "LINES %onihime% さあ、骨までしゃぶってあげる！"
                      , "LINES %avatar% ヒィィ！！"
                      , "SPEAK %avatar% もじょ や、やるしかないのだ・・"
                    ]
                }
              , "treasure4-1": {
                    "trigger": "player"
                  , "pos": [3,7]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "end_speak": {
                     "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %onihime% 子女のみ暮らす集落に・・"
                      , "LINES %onihime% とある男がやって来る・・"
                      , "LINES %onihime% 男は女を鬼に変え・・"
                      , "LINES %onihime% やがて炎で焼かれ死ぬ・・"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %avatar% もじょ これは何の詩なのだ・・？"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "LINES %avatar% 鬼姫伝説だ・・師匠から聞いたことがある・・"
                      , "LINES %avatar% 一人の男をとりあって集落の女を全員殺したっていう・・"
                      , "SPEAK %avatar% もじょ 恐ろしい話なのだ・・"
                      , "LINES %avatar% その後、その女は鬼になった・・でも悲しい詩・・"
                      , "LINES %onihime% ああ口惜しや、口惜しや・・"
                      , "SPEAK %avatar% もじょ 泣いてるのだ・・"
                      , "LINES %avatar% きっとボクらには分からないことが色々あったんだろうね・・"
                      , "SPEAK %avatar% もじょ 伝説の裏までは分からないのだ"
                      , "LINES %avatar% もう土にお帰り・・"
                    ]
                 }
              , "end_speak2": {
                     "type": "lead"
                   , "leads": [
                          "LINES %avatar% これで終わったね・・"
                        , "SPEAK %avatar% もじょ じゃ、じじいのとこに戻るのだ"
                        , "LINES %avatar% うん・・"
                        , "LINES %avatar% さようなら・・鬼姫・・"
                     ]
                   , "chain": "goal"
                 }
              , "fungi1": {
                    "pos": [5, 3]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [6, 3]
                  , "ornament": "fungi"
                }
              , "fungi3": {
                    "pos": [7, 3]
                  , "ornament": "fungi"
                }
              , "fungi4": {
                    "pos": [8, 4]
                  , "ornament": "fungi"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                   "pos": [5,4]
                  , "code":"onihime"
                  , "character_id":-9134
                  , "icon":"woman"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                  , "early_gimmick": "end_speak"
                  , "trigger_gimmick": "end_speak2"
                }
            ]
        }
    }
}
