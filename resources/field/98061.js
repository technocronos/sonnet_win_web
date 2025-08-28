{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "shisyou"]
   ,"rooms": {
        "start": {
            "id": 98061000
          , "battle_bg": "snow"
          , "environment": "snow"
          , "bgm": "bgm_home"
          , "start_pos": [5, 11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% うわぁ・・。ここにサンタさんがいるのか・・。"
                    ]
                }
              , "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [2,2], "rb": [10,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 5]
                      , "character_id":-9147
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 5]
                      , "character_id":-9147
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                }
              , "santa-appear": {
                    "trigger": "hero"
                  , "pos": [2,2], "rb": [8,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 2]
                      , "character_id":-9906
                      , "icon":"shisyou"
                      , "union": 1
                      , "code": "santa"
                      , "act_brain": "rest"
                    }
                  , "chain": "santaspeak"
                }
	           , "santaspeak": {
	                 "type": "lead"
	               , "leads": [
                        "UALGN %avatar% 1"
                      , "LINES %avatar% ん・・？サンタさん・・？"
                      , "SPEAK %santa% サンタ ホホホーイ！今年も全国の子供たちにプレゼント配ったぞい。"
                      , "SPEAK %santa% サンタ やれやれ・・ちょっと一休み・・"
                      , "LINES %avatar% やっぱそうだ・・！"
                      , "UALGN %santa% 2"
                      , "SPEAK %santa% サンタ ん？子供？"
                      , "LINES %avatar% あの・・サンタさん・・？"
                      , "SPEAK %santa% サンタ いかにもじゃが・・。こんな所まで来ちゃいかん"
                      , "LINES %avatar% ねえ、どうして今年も来てくれなかったの？ねえ！"
                      , "SPEAK %santa% サンタ そういう質問には答えられん"
                      , "SPEAK %santa% サンタ 危険じゃから早く家に帰りなさい！ええな！"
                      , "LINES %avatar% あっ！待って！"
	                 ]
	               , "chain": "santa_disappear"
	             }
             , "santa_disappear": {
                   "type": "unit_exit"
                 , "exit_target": "santa"
	               , "chain": "santaspeakafter"
               }
	           , "santaspeakafter": {
	                 "type": "lead"
	               , "leads": [
                        "LINES %avatar% 行っちゃった・・。逃がすか！"
                       ,"LINES %avatar% 追いかけるぞい！"
	                 ]
	               , "chain": "santa_disappear"
	             }
              , "enemy0-3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2, 2]
                      , "character_id":-9147
                      , "icon":"shadow"
                    }
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [11,8]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980610001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [3,2]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980610002
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [5,0]
                  , "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 98061001
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [5,0]
          , "gimmicks": {
                "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [2,1], "rb": [7,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 8]
                      , "character_id":-9147
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 13]
                      , "character_id":-9148
                      , "icon":"shadow"
                    }
                }
              , "enemy1-3": {
                    "trigger": "hero"
                  , "pos": [10,1], "rb": [14,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [11, 2]
                      , "character_id":-9148
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1-4"
                }
              , "enemy1-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 2]
                      , "character_id":-9148
                      , "icon":"shadow"
                    }
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [2,2]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [9,3]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980610012
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [10,5]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980610013
                }
              , "treasure1-4": {
                    "trigger": "player"
                  , "pos": [7,15]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-5": {
                    "trigger": "player"
                  , "pos": [13,10]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980610015
                }
              , "fungi1": {
                    "pos": [2, 3]
                  , "ornament": "fungi"
                }
              , "fungi2": {
                    "pos": [2, 8]
                  , "ornament": "fungi2"
                }
              , "fungi3": {
                    "pos": [9, 2]
                  , "ornament": "fungi2"
                }
              , "fungi4": {
                    "pos": [13, 6]
                  , "ornament": "fungi"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [13,1]
                  , "type": "goto"
                  , "room": "room2"
                }
            }
          , "units": [
                {
                   "pos": [7,10]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                   "pos": [10,12]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
            ]
        }
      , "room2": {
            "id": 98061002
          , "battle_bg": "wetlands"
          , "environment": "cave"
          , "start_pos": [3,14]
          , "start_pos_on": {
                "goto_room2-2": [18,12]
              , "hole1": [6,6]
              , "hole4": [12,13]
            }
          , "gimmicks": {
                "treasure2-1": {
                    "trigger": "player"
                  , "pos": [6,13]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [3,3]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980610022
                }
              , "treasure2-5": {
                    "trigger": "player"
                  , "pos": [11,8]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980610025
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [10,1]
                  , "type": "goto"
                  , "room": "room3"
                }
              , "fungi1": {
                    "pos": [7, 14]
                  , "ornament": "fungi"
                }
            }
          , "units": [
                {
                   "pos": [4,6]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                   "pos": [10,7]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
              , {
                   "pos": [7,3]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                   "pos": [10,11]
                  , "character_id":-9149
                  , "icon":"shadowB"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "444666"
                  , "excurse_step": 3
                }
            ]
        }
      , "room3": {
            "id": 98061003
          , "battle_bg": "snow"
          , "environment": "snow"
          , "start_pos": [0,11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% あれ、また外だ・・"
                    ]
                  , "chain": "enemy3.1"
                }
              , "enemy3.1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 4]
                      , "character_id":-9150
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                }
              , "enemy3.2": {
                    "trigger": "hero"
                  , "pos": [2,2], "rb": [7,7]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 4]
                      , "character_id":-9150
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                }
              , "enemy3.3": {
                    "trigger": "hero"
                  , "pos": [9,2], "rb": [14,10]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 10]
                      , "character_id":-9150
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                }
              , "enemy3.4": {
                    "trigger": "hero"
                  , "pos": [10,7], "rb": [20,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [18, 7]
                      , "character_id":-9150
                      , "icon":"shadow"
                      , "act_brain": "target"
                      , "target_unit": "avatar"
                    }
                }
	           , "santaspeak2": {
                    "trigger": "hero"
                  , "pos": [17,3]
                  , "type": "lead"
	                , "leads": [
                        "UALGN %santa% 0"
                      , "UALGN %avatar% 3"
	                    , "LINES %avatar% ようやく追いついた・・"
                      , "SPEAK %santa% サンタ 追いかけて来たんか？しつこいのう・・"
	                    , "LINES %avatar% どうしてボクのところには来てくれないの？"
                      , "SPEAK %santa% サンタ 仕方ないのう・・。えーっと[NAME]じゃな・・？"
                      , "SPEAK %santa% サンタ ワシはサンタになったばかりでのう・・。"
                      , "SPEAK %santa% サンタ 先代から引き継ぎがあっておぬしの所はリストから漏れとるぞい。"
	                    , "LINES %avatar% どっ・・どうして？？？"
                      , "SPEAK %santa% サンタ フーム・・。申し送り状によると・・。"
                      , "SPEAK %santa% サンタ おぬしがもっと小さい頃にサンタに「一生何もいらない」と言ったと・・。"
	                    , "LINES %avatar% なにそれ！覚えてないよ！嘘だ！"
                      , "SPEAK %santa% サンタ うそじゃないわい！一生何もいらないからそのかわり"
	                    , "LINES %avatar% そのかわり・・？"
                      , "SPEAK %santa% サンタ 「友達が欲しい」と。"
	                    , "LINES %avatar% えっ・・。"
                      , "SPEAK %santa% サンタ 思い出したか？それでおぬしのところに精霊を使わせたはずじゃ。"
	                    , "LINES %avatar% 精霊・・。思い出せない・・・・頭が痛い・・"
	                    , "LINES %avatar% 大事なことなはずなのに・・どうして・・"
                      , "SPEAK %santa% サンタ おぬし・・"
                      , "SPEAK %santa% サンタ 何か記憶を改竄されとるの・・？"
	                    , "LINES %avatar% えっ・・？"
                      , "SPEAK %santa% サンタ というよりその精霊が存在しない時空に飛ばされようとしておる"
	                    , "LINES %avatar% どういう・・こと・・？"
	                    , "LINES %avatar% ううっ・・"
                      , "NOTIF ・・忘れ・・ないで・・"
	                    , "LINES %avatar% だ‥誰！？"
                      , "NOTIF もじょのこと・・"
                      , "SPEAK %santa% サンタ その精霊がいた時空を無かったことにしようとして何物かがその精霊が存在していた時空を捻じ曲げて閉じようとしておるんじゃ"
                      , "NOTIF ・・忘れ・・"
	                    , "LINES %avatar% や、やめて！！！！"
                      , "SPEAK %santa% サンタ このままでは時空が完全に閉じてしまうわい・・"
                      , "SPEAK %santa% サンタ いかんぞ・・これはサンタとしての沽券にかかわることじゃ"
                      , "SPEAK %santa% サンタ サンタのプレゼントを無かったことにされてしまうんじゃからの・・"
	                    , "LINES %avatar% どうにかなりませんか？分からないけど忘れてはいけないことを忘れようとしてる気がするんです！"
                      , "SPEAK %santa% サンタ 仕方ないのう・・プレゼントを無かったことにはできん"
                      , "SPEAK %santa% サンタ サンタの神の力で時空への干渉を排除する！"
                      , "SPEAK %santa% サンタ ふぉおおおおおお！！！！"
                      , "EFFEC migt 1702"
                      , "DELAY 500"
	                 ]
	                , "chain": "enemy3.5"
	             }
              , "enemy3.5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 5]
                      , "character_id":-9152
                      , "icon":"shadowG"
                      , "code": "worm"
                      , "act_brain": "rest"
                      , "bgm": "bgm_bigboss"
                      , "align": 3
                      , "trigger_gimmick": "endspeak"
                    }
	                , "chain": "santaspeak3"
                }
	           , "santaspeak3": {
                    "type": "lead"
	                , "leads": [
                        "UALGN %avatar% 0"
	                    , "LINES %worm% キョェェェェ！！！！"
	                    , "LINES %avatar% うわっ！む・・虫・・！？"
                      , "SPEAK %santa% サンタ ディメンションワームじゃ"
                      , "SPEAK %santa% サンタ 何者かがそやつを使って時空を操作しようとしてたんじゃな"
                      , "SPEAK %santa% サンタ その虫を倒すのじゃ！さすれば時空は元に戻るぞい！"
	                    , "LINES %avatar% よーし！"
	                 ]
	             }
	           , "endspeak": {
                    "type": "lead"
	                , "leads": [
                        "LINES %avatar% た・・倒した・・"
	                    , "LINES %avatar% ・・お・・思い出した・・！"
	                    , "LINES %avatar% もじょ！もじょを助けにいかなくちゃ！"
                      , "SPEAK %santa% サンタ 友達が大変なことになっているようじゃな。"
                      , "SPEAK %santa% サンタ じゃが、すまないがこれ以上は手助けはできん・・。"
	                    , "LINES %avatar% ありがとうざいます！"
                      , "SPEAK %santa% サンタ ちょっと遅いがメリークリスマスじゃよ。特別プレゼントじゃ。"
                      , "SPEAK %santa% サンタ 気をつけてな・・。"
	                    , "LINES %avatar% よし！もじょを助けにいくよ！"
	                    , "LINES %avatar% 待ってて！もじょ！"
	                 ]
	                , "chain": "goal"
	             }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "chain": "treasure3-1"
                }
              , "treasure3-1": {
                    "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980610031
                }
              , "treasure3-2": {
                    "trigger": "player"
                  , "pos": [18,16]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980610032
                }
              , "treasure3-3": {
                    "trigger": "player"
                  , "pos": [7,7]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-4": {
                    "trigger": "player"
                  , "pos": [8,6]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980610034
                }
              , "treasure3-5": {
                    "trigger": "player"
                  , "pos": [15,6]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
            }
          , "units": [
                {
                   "pos": [15,12]
                  , "character_id":-9151
                  , "icon":"shadow2"
                  , "act_brain": "rest"
                  , "align": 3
                  , "bgm": "bgm_bossbattle"
                }
              , {
                   "pos": [17,2]
                  , "character_id":-9906
                  , "icon":"shisyou"
                  , "union": 1
                  , "code": "santa"
                  , "act_brain": "rest"
                }
            ]
        }
    }
}
