{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "woman", "uncle"]
   ,"rooms": {
        "start": {
            "id": 98051000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [1, 11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% あれ・・どこだここ・・？"
                    ]
                  , "chain": "enemy1.1"
                }
	          , "enemy1.1": {
	                "type": "unit"
	              , "unit": {
	                      "pos": [4, 11]
	                    , "character_id":-10001
	                    , "icon":"shadow2"
                      , "code": "tamane"
                      , "act_brain": "rest"
	                    , "brain_noattack": true
	                 }
	               , "chain": "enemyspeak1"
	             }
	           , "enemyspeak1": {
	                 "type": "lead"
	               , "leads": [
                        "UALGN %tamane% 1"
                      , "SPEAK %tamane% ？？？ ハァイ！またお会いしましたね！"
	                    , "LINES %avatar% お・・お前は・・ｗ"
	                    , "SPEAK %tamane% ？？？ 何を草生やしてるんですか・・。"
	                    , "SPEAK %tamane% ？？？ 改めて自己紹介させていただきますとワタクシ運営よりあなた方をご案内するよう仰せつかりました、ジェントル・タマネギンと申します"
	                    , "LINES %tamane% 以後、お見知りおきのほどを・・"
	                    , "LINES %avatar% ジェンタマくん、久しぶりだね"
	                    , "LINES %tamane% ジェンタマというのはおやめなさい。ジェントルタマネギンです。"
	                    , "LINES %tamane% ワタクシはタマネギン一族の中でも誉れ高いタマネ・タマネギン公爵の直系に当たる・・"
	                    , "LINES %avatar% わかったわかった・・で、何しに出てきたの？"
	                    , "LINES %tamane% 今回のイベントのご案内を、と存じまして・・"
	                    , "LINES %avatar% それはどうもご丁寧に"
	                    , "LINES %tamane% 今回はハロウィンイベントでございます"
	                    , "LINES %tamane% ハロウィンにちなんだモンスターが出てきますからそれを倒していただくという大変オモシロいイベントとなっております"
	                    , "LINES %avatar% そんなのこの時期どこでもやってんじゃん"
	                    , "LINES %avatar% もじょをまだ取り返してないんだけど・・続きはどうなったのよ"
	                    , "LINES %tamane% さあ・・"
	                    , "LINES %avatar% よーするに続きまだできてないのね"
	                    , "LINES %tamane% 運営もいろいろあるのですよ・・"
	                    , "LINES %tamane% では、お楽しみ下さい。あでゅー！"
	                    , "LINES %avatar% あっ、ちょっとまっ・・"
	                 ]
	               , "chain": "tamanegiexplode"
	             }
              , "tamanegiexplode": {
                    "type": "uncondition_explode"
                  , "chain_delayed": "enemyspeak5"
                }
              , "enemyspeak5": {
                    "type": "lead"
                  , "leads": [
	                      "LINES %avatar% もう！なんで毎回毎回！"
	                    , "LINES %avatar% 静かに退場してほしいなぁ・・"
                    ]
                  , "chain": "enemy0-1"
                }
              , "enemy0-1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 9]
                      , "character_id":-9141
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 10]
                      , "character_id":-9141
                      , "icon":"shadow"
                    }
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [11,5], "rb": [15,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 3]
                      , "character_id":-9141
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-4"
                }
              , "enemy0-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [14, 3]
                      , "character_id":-9141
                      , "icon":"shadow"
                    }
                }
              , "enemy0-5": {
                    "trigger": "hero"
                  , "pos": [5,3], "rb": [10,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 7]
                      , "character_id":-9142
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-6"
                }
              , "enemy0-6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 7]
                      , "character_id":-9142
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
                    "pos": [1, 10]
                  , "ornament": "fungi2"
                }
              , "fungi6": {
                    "pos": [1, 11]
                  , "ornament": "fungi2"
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [4,3]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980510001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [14,2]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980510002
                }
              , "treasure0-3": {
                    "trigger": "player"
                  , "pos": [7,7]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [2,5]
                  , "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 98051001
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [12,11]
          , "gimmicks": {
                "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [9,4], "rb": [15,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 4]
                      , "character_id":-9142
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 4]
                      , "character_id":-9142
                      , "icon":"shadow"
                    }
                }
              , "enemy1-3": {
                    "trigger": "hero"
                  , "pos": [0,0], "rb": [8,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 8]
                      , "character_id":-9143
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-4"
                }
              , "enemy1-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 10]
                      , "character_id":-9143
                      , "icon":"shadow"
                    }
                }
              , "enemy1-5": {
                    "trigger": "hero"
                  , "pos": [0,6], "rb": [8,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 11]
                      , "character_id":-9143
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-6"
                }
              , "enemy1-6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 13]
                      , "character_id":-9143
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
                    "pos": [11, 8]
                  , "ornament": "fungi"
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [10,11]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [9,6]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980510012
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [2,11]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980510013
                }
              , "treasure1-4": {
                    "trigger": "player"
                  , "pos": [16,2]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980510014
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [9,14]
                  , "type": "goto"
                  , "room": "room2"
                }
            }
        }
      , "room2": {
            "id": 98051002
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "start_pos": [9,17]
          , "start_pos_on": {
                "goto_room2-2": [18,12]
              , "hole1": [6,6]
              , "hole4": [12,13]
            }
          , "gimmicks": {
                "enemy2-1": {
                    "trigger": "hero"
                  , "pos": [5,12], "rb": [10,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 7]
                      , "character_id":-9144
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 8]
                      , "character_id":-9144
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [8,8], "rb": [12,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 12]
                      , "character_id":-9144
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 14]
                      , "character_id":-9144
                      , "icon":"shadow"
                    }
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [1,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [4,11]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980510022
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [8,6]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [15,4]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980510024
                }
              , "treasure2-5": {
                    "trigger": "player"
                  , "pos": [15,8]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980510025
                }
              , "fungi1": {
                    "pos": [1, 7]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [6, 15]
                  , "ornament": "fungi2"
                }
              , "fungi4": {
                    "pos": [6, 16]
                  , "ornament": "fungi2"
                }
              , "fungi5": {
                    "pos": [15, 8]
                  , "ornament": "fungi"
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [10,15]
                  , "type": "goto"
                  , "room": "room3"
                }
            }
          , "units": [
                {
                   "pos": [11,7]
                  , "character_id":-9145
                  , "icon":"shadow2"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "room3": {
            "id": 98051003
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
                      , "LINES %avatar% あれ、誰もいない・・"
                      , "LINES %avatar% ト・・トリックオアトリート・・？"
                    ]
                  , "chain": "enemy3.1"
                }
	          , "enemy3.1": {
	                "type": "unit"
	              , "unit": {
	                      "pos": [5, 1]
	                    , "character_id":-10001
                      , "code": "tamane"
                      , "character_id":-9146
                      , "icon":"shadowG"
                      , "act_brain": "rest"
                      , "bgm": "bgm_bigboss"
                      , "trigger_gimmick": "goal"
                      , "early_gimmick": "endspeak"
	                 }
	               , "chain": "enemyspeak1"
	             }
	           , "enemyspeak1": {
	                 "type": "lead"
	               , "leads": [
                        "UALGN %tamane% 0"
                      , "LINES %tamane% おお、よくぞここまでたどり着きました"
                      , "LINES %tamane% スバラシイ・・イヤ、スバラシイ・・"
	                    , "LINES %avatar% あら、まだジェンタマくん"
                      , "LINES %tamane% 最後のボスはワタクシが務めさせていただきます"
	                    , "LINES %avatar% ジェンタマくんが？"
                      , "LINES %tamane% ンッフッフ・・ハロウィン仕様にチューンナップしてもらったのですよ"
                      , "LINES %tamane% 今日のワタクシは最強のハロウィン戦士ジェントルパンプキンとなったのです！"
	                    , "LINES %avatar% た、たしかに強そうだ・・"
                      , "LINES %tamane% さあ、かかってきなさい！"
	                 ]
	               , "chain": "tamanegiexplode"
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
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "chain": "endspeak2"
                }
               , "endspeak": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% 勝った・・"
                       , "LINES %tamane% あべしっ！"
                     ]
                 }
               , "endspeak2": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% さ、これで終わり"
                       , "LINES %avatar% また次回をお楽しみに！"
                     ]
                  , "chain": "treasure1"
                 }
              , "treasure1": {
                    "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980510031
                }
            }
        }
    }
}
