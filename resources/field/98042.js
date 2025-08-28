{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "man", "servant"]
   ,"rooms": {
        "start": {
            "id": 98042000
          , "battle_bg": "snow"
          , "sphere_bg": "cloud"
          , "environment": "grass"
          , "start_pos_on": {
                "start": [15,2]
            }
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% わー、ここがケットシーの国？"
                      , "SPEAK %avatar% もじょ そうなのだ。懐かしいのだ・・"
                      , "SPEAK %avatar% オーラ よし、ちゃんと移動できたな。"
                      , "SPEAK %avatar% オーラ 最近、時空の歪みが激しくてな。下手すると別の時空に飛ばされてることもある"
                      , "LINES %avatar% そうなんだ・・はは・・。"
                      , "LINES %avatar% （実は飛ばされてたりして・・）"
                    ]
                  , "chain": "zohyo_appear"
                }
            , "zohyo_appear": {
                "type": "unit"
              , "one_shot": 980420000
              , "unit": {
                      "pos": [15, 3]
                    , "character_id":-9906
                    , "icon":"servant"
                    , "code": "man1"
                    , "union": 1
                    , "act_brain": "rest"
                    , "brain_noattack": true
                 }
               , "chain": "zohyo_speak"
              }
              , "zohyo_speak": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %man1% 3"
                      , "SPEAK %man1% 兵士 オーラ様！ご無事で！"
                      , "SPEAK %avatar% オーラ ただいま帰った。もじょを連れて来た。城に行くぞ"
                      , "SPEAK %man1% 兵士 おお！もじょ様！ご無事で・・"
                      , "SPEAK %avatar% オーラ 話は後だ、城に行くぞ"
                      , "SPEAK %man1% 兵士 それが・・今、敵が総攻撃を仕掛けてきており・・"
                      , "SPEAK %avatar% オーラ な？？"
                      , "SPEAK %man1% 兵士 城には近寄れない状態なのです"
                      , "SPEAK %avatar% オーラ くそ！一足遅かったか！"
                      , "LINES %avatar% ねえ、敵って誰なの？"
                      , "SPEAK %avatar% オーラ ゾット教だよ"
                      , "LINES %avatar% ゾット教？"
                      , "SPEAK %avatar% もじょ 破壊神なのだ。すごく危険な邪教信仰なのだ・・。"
                      , "SPEAK %avatar% オーラ ああ、最近ゾット教を信仰する怪しい新興宗教ができてな。"
                      , "SPEAK %avatar% オーラ その教祖が精霊界を統一するとか言い出した。"
                      , "SPEAK %avatar% オーラ ケットシーの国もその標的にされたってわけだ。"
                      , "SPEAK %man1% 兵士 奴らはゾット神をこの世界に降臨させると・・"
                      , "SPEAK %avatar% もじょ そんなの危険すぎるのだ・・。みんな死ぬのだ"
                      , "SPEAK %avatar% オーラ ああ、何としても食い止めないといけないが・・"
                      , "SPEAK %man1% 兵士 最近の情報ですと、かなり降臨の儀式は進んでしまってるようです"
                      , "SPEAK %avatar% オーラ 時空がゆがんでるのもその影響なのかもな・・。"
                      , "LINES %avatar% うーん、じゃ破壊神が復活する前にその教祖を倒すしかないんだね？"
                      , "SPEAK %avatar% もじょ そういうことなのだ。早いとこ倒すのだ。"
                      , "SPEAK %avatar% オーラ ああ、そうするしかない。行くぞ！"
                      , "SPEAK %man1% 兵士 オーラ様、もじょ様、お気をつけて"
                    ]
                  , "chain": "zohyo_disappear"
                }
              , "zohyo_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "man1"
	                , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ あ、あと何度か言ってるけどイベントは上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [8,1], "rb": [14,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 4]
                      , "character_id":-9153
                      , "code": "enemy1"
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 10]
                      , "character_id":-9153
                      , "icon":"shadow"
                      , "code": "enemy2"
                    }
                  , "chain": "speak_1"
                }
              , "speak_1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %enemy1% オヒョーン・・"
                      , "LINES %enemy2% オヒョーン・・"
                      , "LINES %avatar% あれ？犬だ・・。変な鳴き声だけど・・"
                      , "SPEAK %avatar% もじょ オヒョン族なのだ。普段はおとなしい犬の精霊なのだ"
                      , "SPEAK %avatar% オーラ ああ・・だがもうオヒョン族も洗脳されてみんな教団の手先だ・・"
                    ]
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [2,8], "rb": [8,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 16]
                      , "character_id":-9153
                      , "icon":"shadow"
                    }
                }
              , "enemy0-4": {
                    "trigger": "hero"
                  , "pos": [9,12], "rb": [13,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 12]
                      , "character_id":-9153
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-5"
                }
              , "enemy0-5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [5, 15]
                      , "character_id":-9154
                      , "icon":"shadow"
                    }
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [18,10]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980420001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [4,2]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980420002
                }
              , "treasure0-3": {
                    "trigger": "player"
                  , "pos": [12,16]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [15,10]
                  , "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 98042001
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [3,19]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% オーラ さあ、ついた。ここが敵のアジトだ"
                      , "SPEAK %avatar% もじょ なんともブキミなとこなのだ・・"
                    ]
                }
              , "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [6,17], "rb": [14,19]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 15]
                      , "character_id":-9154
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 9]
                      , "character_id":-9154
                      , "icon":"shadow"
                    }
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [9,5], "rb": [13,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 5]
                      , "character_id":-9154
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-4"
                }
              , "enemy0-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 12]
                      , "character_id":-9154
                      , "icon":"shadow"
                    }
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [15,7]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980420011
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [3,7]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980420012
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [3,13]
                  , "type": "treasure"
                  , "item_id": 1203
                  , "ornament": "twinkle"
                  , "one_shot": 980420013
                }
              , "treasure1-4": {
                    "trigger": "player"
                  , "pos": [16,19]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "mark1": {
                    "pos": [6, 16]
                  , "ornament": "mark1"
                }
              , "mark2": {
                    "pos": [4, 6]
                  , "ornament": "mark1"
                }
              , "mark3": {
                    "pos": [10, 3]
                  , "ornament": "mark2"
                }
              , "mark4": {
                    "pos": [13, 5]
                  , "ornament": "mark2"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [8,4]
                  , "type": "goto"
                  , "room": "room2"
                }
            }
        }
    , "room2": {
            "id": 98042002
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [8,5]
          , "gimmicks": {
                "enemy2-1": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 11]
                      , "character_id":-9155
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 11]
                      , "character_id":-9155
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [19,8], "rb": [24,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [22, 17]
                      , "character_id":-9155
                      , "icon":"shadow"
                    }
                }
              , "enemy2-4": {
                    "trigger": "hero"
                  , "pos": [18,13], "rb": [24,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 17]
                      , "character_id":-9155
                      , "icon":"shadow"
                    }
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [4,16]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980420021
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [11,12]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [23,11]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980420023
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [23,17]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "mark1": {
                    "pos": [9, 4]
                  , "ornament": "mark1"
                }
              , "mark2": {
                    "pos": [4, 6]
                  , "ornament": "mark1"
                }
              , "mark3": {
                    "pos": [17, 15]
                  , "ornament": "mark2"
                }
              , "mark4": {
                    "pos": [12, 12]
                  , "ornament": "mark2"
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [13,17]
                  , "type": "goto"
                  , "room": "room3"
                }
            }
        }
      , "room3": {
            "id": 98042003
          , "battle_bg": "room1"
          , "environment": "cave"
          , "bgm": "bgm_quest_horror"
          , "start_pos": [9,17]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% あっ、いた！"
                      , "SPEAK %avatar% オーラ 何かの儀式をやっているようだな・・"
                      , "LINES %boss% アラエッサーラ・・アラエッサラーム・・"
                      , "NOTIF アラエッサーラ・・アラエッサラーム・・"
                      , "LINES %boss% アラエッサーラ・・アラエッサラーム・・"
                      , "NOTIF アラエッサーラ・・アラエッサラーム・・"
                      , "LINES %boss% さあ、わが破壊神の復活はすぐそこだ"
                      , "SPEAK %religious1% 信者 イエス！クランズマン！"
                      , "LINES %boss% もっとヘイトを集めろ！すべてのものを殺すのだ！"
                      , "SPEAK %religious2% 信者 イエス！クランズマン！"
                      , "LINES %boss% おい、そこの貴様！"
                      , "SPEAK %religious3% 信者 イエス！クランズマン！"
                      , "LINES %boss% ・・死ね！"
                      , "SPEAK %religious3% 信者 イエス！喜んで！"
                      , "ENVCG FF7A7A" 
                      , "SEPLY se_arts1" 
                      , "VIBRA 10"
                      , "DELAY 500"
                      , "VIBRA 00"
                      , "ENVCG FFFFFF" 
                      , "SPEAK %religious3% 信者 ぐはっ・・"
                    ]
                  , "chain": "religious3_disappear"
                }
              , "religious3_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious3"
	                , "chain": "speak_1"
                }
              , "speak_1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %boss% よし。"
                      , "LINES %boss% ・・今死んだ貴様に再び命ずる・・\n生まれろ！"
                      , "NOTIF ・・・"
                      , "LINES %boss% 再び命ずる・・\n生まれろ！生まれるのだ！！"
                      , "NOTIF ・・・"
                      , "LINES %boss% フハハハ・・！そう・・！"
                      , "LINES %boss% 人は自殺することはできる！\nしかし・・自分で生まれることはできないのだ！"
                      , "LINES %boss% だとしたら・・我々はどこから来たのか・・？"
                      , "LINES %boss% 一番最初にこの生のスイッチを押した存在とは・・？"
                      , "LINES %boss% その謎を解き明かすのだ！"
                      , "NOTIF イエス！クランズマン！"
                      , "LINES %boss% 行け！皆のものよ！\nケットシーどもを一人残らず殺せ！"
                      , "NOTIF イエス！クランズマン！"
                    ]
	                , "chain": "religious1_disappear"
                }
              , "religious1_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious1"
	                , "chain": "religious2_disappear"
                }
              , "religious2_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious2"
	                , "chain": "religious4_disappear"
                }
              , "religious4_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious4"
	                , "chain": "religious5_disappear"
                }
              , "religious5_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious5"
	                , "chain": "religious6_disappear"
                }
              , "religious6_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious6"
	                , "chain": "religious7_disappear"
                }
              , "religious7_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious7"
	                , "chain": "religious8_disappear"
                }
              , "religious8_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "religious8"
	                , "chain": "speak_2"
                }
              , "speak_2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %boss% ククク・・今日でケットシーは終わりだ・・"
                      , "LINES %boss% そしてこの世界も・・"
                      , "LINES %boss% もうすぐ会えるよ・・ママ・・"
                    ]
	                , "chain": "boss_disappear"
                }
              , "boss_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "boss"
	                , "chain": "speak_3"
                }
              , "speak_3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% ・・"
                      , "SPEAK %avatar% もじょ 恐ろしい奴らなのだ。まともな教団じゃないのだ"
                      , "LINES %avatar% あの教祖はどこ行ったんだろ？"
                      , "SPEAK %avatar% オーラ 奥の部屋に行ったんだな"
                      , "SPEAK %avatar% もじょ よし！追うのだ！"
                    ]
                }
              , "enemy3-1": {
                    "trigger": "hero"
                  , "pos": [12,8], "rb": [14,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [17, 8]
                      , "character_id":-9156
                      , "icon":"shadow"
                    }
	                , "chain": "enemy3-2"
                }
              , "enemy3-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 3]
                      , "character_id":-9156
                      , "icon":"shadow"
                    }
                }
              , "enemy3-3": {
                    "trigger": "hero"
                  , "pos": [10,1], "rb": [19,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 3]
                      , "character_id":-9156
                      , "icon":"shadow"
                    }
                }
              , "enemy3-4": {
                    "trigger": "hero"
                  , "pos": [0,0], "rb": [11,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 6]
                      , "character_id":-9156
                      , "icon":"shadow"
                    }
                }
              , "treasure3-1": {
                    "trigger": "player"
                  , "pos": [14,14]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980420031
                }
              , "treasure3-2": {
                    "trigger": "player"
                  , "pos": [18,2]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-3": {
                    "trigger": "player"
                  , "pos": [8,8]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980420033
                }
              , "mark1": {
                    "pos": [11, 12]
                  , "ornament": "mark1"
                }
              , "mark2": {
                    "pos": [14, 12]
                  , "ornament": "mark1"
                }
              , "mark3": {
                    "pos": [15, 1]
                  , "ornament": "mark2"
                }
              , "mark4": {
                    "pos": [4, 1]
                  , "ornament": "mark2"
                }
              , "mark5": {
                    "pos": [8, 7]
                  , "ornament": "mark1"
                }
              , "goto_room4": {
                    "trigger": "hero"
                  , "pos": [9,9]
                  , "type": "goto"
                  , "room": "room4"
                }
            }
          , "units": [
                {
                    "pos": [12,15]
                  , "character_id":-9157
                  , "icon":"shadowG"
                  , "act_brain": "rest"
                  , "code": "boss"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [11,16]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious1"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [12,16]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious2"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [13,16]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious3"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [14,16]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious4"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [11,17]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious5"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [12,17]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious6"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [13,17]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious7"
                }
             ,  {
                    "character_id": -9156
                  , "icon":"shadow"
                  , "pos": [14,17]
                  , "align": 3
                  , "act_brain": "rest"
                  , "code": "religious8"
                }
            ]
        }
      , "room4": {
            "id": 98042004
          , "bgm": "bgm_quest_horror"
          , "battle_bg": "room1"
          , "environment": "cave"
          , "start_pos": [8,10]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% いた！あいつだ！"
                      , "UALGN %boss% 0"
                      , "LINES %boss% ほう、よくここまで来たな・・"
                      , "SPEAK %avatar% オーラ お前が教祖か？"
                      , "LINES %boss% ・・ケットシーの娘か・・フッフッフ・・。"
                      , "SPEAK %avatar% オーラ 覚悟しろ！"
                      , "LINES %boss% 一足遅かったな・・。もはや破壊神ゾットの復活はすぐそこだ"
                      , "SPEAK %avatar% もじょ その卵は何なのだ？"
                      , "LINES %avatar% すごい禍々しい気を感じる・・"
                      , "LINES %boss% これは破壊神の卵だ・・。これを手に入れて以来人を意のままに操ることができるようになった。"
                      , "SPEAK %avatar% もじょ じゃ、その破壊神の力がみんなを洗脳してるのだ？"
                      , "LINES %boss% オヒョーン・・。その通りだぁ・・。"
                      , "SPEAK %avatar% オーラ ん？お前、オヒョン族か・・？"
                      , "LINES %boss% ククク・・今となってはもうどうでもいいことだ"
                      , "LINES %boss% 破壊神はもう目覚める・・"
                      , "SPEAK %avatar% もじょ これが目覚めたら世界の終わりなのだ・・"
                      , "SPEAK %avatar% もじょ なんでこんなことするのだ？"
                      , "NOTIF ウウ・・ウウ・・"
                      , "UALGN %boss% 3"
                      , "LINES %boss% マ・・ママ・・！ママなんだね・・！？"
                      , "NOTIF モット・・死ヲ・・\nモット・・恐怖・・ヲ・・"
                      , "LINES %boss% ・・ママ・・！"
                      , "LINES %avatar% マ、ママぁ？"
                      , "SPEAK %avatar% もじょ ど、どういうことなのだ？"
                      , "UALGN %boss% 0"
                      , "LINES %boss% 破壊神は無から生を生むことができる原初のスイッチを持つ・・"
                      , "LINES %boss% 死んだママを・・生まれさせるんだ・・！もう一度・・"
                      , "SPEAK %avatar% オーラ そ・・それが目的だったのか・・許せん！"
                      , "LINES %avatar% とにかくあいつを倒して破壊神の誕生を止めないと"
                      , "LINES %boss% オヒョーン・・。やれるもんならやってみろ・・"
                    ]
                }
              , "egg": {
                    "pos": [8, 5]
                  , "ornament": "egg"
                }
              , "end_speak": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% 倒した・・でも・・"
                       , "LINES %boss% ぐっ・・バカな・・"
                       , "LINES %boss% だがもう遅い。破壊神ゾットがもう生まれる・・"
                       , "UALGN %avatar% 3"
                       , "UALGN %boss% 3"
                     ]
	                , "chain": "birth"
                }
              , "birth": {
                    "type": "call"
                  , "call": "processBirthEgg"
                  , "chain": "enemy4"
                }
              , "enemy4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 5]
                      , "character_id":-9158
                      , "icon":"shadowG"
                      , "code": "chaos"
                      , "act_brain": "rest"
                    }
	                , "chain": "afterbirth"
                }
              , "afterbirth": {
                     "type": "lead"
                   , "leads": [
                         "LINES %avatar% う・・生まれた・・"
                       , "SPEAK %avatar% オーラ なんてことだ・・遅かったか"
                       , "UMOVE %boss% 2"
                       , "LINES %boss% マ・・ママ・・ママなんだね・・？"
                       , "NOTIF グ・・グゲゲ・・"
                       , "SPEAK %avatar% もじょ 何か様子が変なのだ・・"
                       , "LINES %boss% ママじゃ・・ない・・なんだこいつは・・？"
                       , "SPEAK %avatar% オーラ ケイオスだ・・こいつは破壊神なんかじゃない！"
                       , "NOTIF グゲゲゲゲ・・！"
                       , "LINES %boss% うわぁぁ！！！助けてくれぇ！！！！"
                     ]
                }
              , "endspeak2": {
                     "type": "lead"
                   , "leads": [
                         "SPEAK %avatar% もじょ あー！吸収されたのだ"
                       , "NOTIF グゲゲゲゲ・・！"
                       , "UMOVE %chaos% 222"
                     ]
	                , "chain": "chaos_disappear"
                }
              , "chaos_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "chaos"
	                , "chain": "speak_3"
                }
              , "speak_3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 行っちゃった・・あいつ一体なんなの？ケイオスって？"
                      , "SPEAK %avatar% オーラ 破壊神ゾットに仕える腐敗した世を原初の状態に戻す神だ"
                      , "SPEAK %avatar% もじょ どうりで・・破壊神ゾットなんて召喚できるもんじゃないと思ってたのだ・・"
                      , "LINES %avatar% ゾット神じゃなくてその使い魔だったわけか・・"
                      , "SPEAK %avatar% オーラ だがどのみちとんでもないものが現れたのは違いない"
                      , "SPEAK %avatar% オーラ あいつは危険だ・・このままじゃ・・"
                      , "SPEAK %avatar% もじょ 奥の方に行ったのだ・・"
                      , "SPEAK %avatar% オーラ このままにしてはおけない！あいつを追おう！"
                      , "LINES %avatar% よし！行こう！"
                      , "NOTIF 後編に続く"
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
                    "pos": [8,7]
                  , "character_id":-9157
                  , "icon":"shadowG"
                  , "code": "boss"
                  , "align": 3
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                  , "early_gimmick": "end_speak"
                  , "trigger_gimmick": "endspeak2"
                }
            ]
        }
    }
}
