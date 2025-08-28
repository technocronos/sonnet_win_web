{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "noel", "shadowM"]
   ,"rooms": {
        "start": {
            "id": 98043000
          , "battle_bg": "room4"
          , "environment": "cave"
          , "start_pos_on": {
                "start": [2,17]
            }
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% うわ・・なにこれ・・"
                      , "SPEAK %avatar% もじょ ケイオスの影響なのだ"
                      , "SPEAK %avatar% オーラ もはやあの世と繋がってしまっているな・・"
                      , "LINES %avatar% はやくあいつを追おう！"
                    ]
	                , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980430000
                  , "leads": [
                        "SPEAK %avatar% もじょ あ、あと何度か言ってるけどイベントは上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [0,8], "rb": [10,14]
                  , "type": "unit"
                  , "unit": {
                        "pos": [4, 8]
                      , "character_id":-9159
                      , "code": "enemy1"
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 9]
                      , "character_id":-9159
                      , "icon":"shadow"
                      , "code": "enemy2"
                    }
                  , "chain": "speak_1"
                }
              , "speak_1": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %enemy1% オオオオ・・"
                      , "LINES %enemy2% オオオオ・・"
                      , "LINES %avatar% うえっ・・なにあれ・・"
                      , "SPEAK %avatar% もじょ 亡者なのだ・・壁から這い出てくるのだ・・"
                      , "SPEAK %avatar% オーラ ケイオスを召喚した出口から魑魅魍魎が大量に押し寄せて来てる"
                      , "SPEAK %avatar% オーラ もしかしたらもっととんでもない事が起こってるかもしれん"
                      , "LINES %avatar% うへぇ・・"
                    ]
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [11,8], "rb": [18,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [13, 4]
                      , "character_id":-9159
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-4"
                }
              , "enemy0-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 4]
                      , "character_id":-9159
                      , "icon":"shadow"
                    }
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [6,9]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980430001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [17,13]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980430002
                }
              , "treasure0-3": {
                    "trigger": "player"
                  , "pos": [12,5]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "mark1": {
                    "pos": [10, 7]
                  , "ornament": "mark2"
                }
              , "mark2": {
                    "pos": [4, 6]
                  , "ornament": "mark1"
                }
              , "goto_room1": {
                    "trigger": "hero"
                  , "pos": [15,2]
                  , "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 98043001
          , "battle_bg": "room4"
          , "environment": "cave"
          , "start_pos": [16,17]
          , "gimmicks": {
                "enemy0-1": {
                    "trigger": "hero"
                  , "pos": [9,8], "rb": [18,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [16, 9]
                      , "character_id":-9160
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-2"
                }
              , "enemy0-2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 11]
                      , "character_id":-9160
                      , "icon":"shadow"
                    }
                }
              , "enemy0-3": {
                    "trigger": "hero"
                  , "pos": [0,0], "rb": [10,14]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 8]
                      , "character_id":-9160
                      , "icon":"shadow"
                    }
                  , "chain": "enemy0-4"
                }
              , "enemy0-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 5]
                      , "character_id":-9160
                      , "icon":"shadow"
                    }
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [14,9]
                  , "type": "treasure"
                  , "item_id": 1911
                  , "ornament": "twinkle"
                  , "one_shot": 980430011
                }
              , "treasure1-2": {
                    "trigger": "player"
                  , "pos": [2,12]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure1-3": {
                    "trigger": "player"
                  , "pos": [1,7]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980430013
                }
              , "mark1": {
                    "pos": [7, 7]
                  , "ornament": "mark1"
                }
              , "mark2": {
                    "pos": [11, 7]
                  , "ornament": "mark1"
                }
              , "mark3": {
                    "pos": [14, 7]
                  , "ornament": "mark2"
                }
              , "mark4": {
                    "pos": [5, 2]
                  , "ornament": "mark2"
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [3,2]
                  , "type": "goto"
                  , "room": "room2"
                }
            }
        }
    , "room2": {
            "id": 98043002
          , "battle_bg": "room4"
          , "environment": "cave"
          , "start_pos": [1,17]
          , "gimmicks": {
                "enemy2-1": {
                    "trigger": "hero"
                  , "pos": [0,11], "rb": [6,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 12]
                      , "character_id":-9161
                      , "icon":"shadow"
                    }
                }
              , "enemy2-2": {
                    "trigger": "hero"
                  , "pos": [6,7], "rb": [10,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 12]
                      , "character_id":-9161
                      , "icon":"shadow"
                    }
                }
              , "enemy2-3": {
                    "trigger": "hero"
                  , "pos": [9,12], "rb": [18,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [17, 10]
                      , "character_id":-9161
                      , "icon":"shadow"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 14]
                      , "character_id":-9161
                      , "icon":"shadow"
                    }
                }
              , "enemy2-5": {
                    "trigger": "hero"
                  , "pos": [12,4], "rb": [18,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 5]
                      , "character_id":-9162
                      , "icon":"shadow2"
                      , "act_brain": "rest"
                      , "bgm": "bgm_bossbattle"
                    }
                  , "chain": "enemy2-6"
                }
              , "enemy2-6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [15, 3]
                      , "character_id":-9162
                      , "icon":"shadow2"
                      , "act_brain": "rest"
                      , "bgm": "bgm_bossbattle"
                    }
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [7,5]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "one_shot": 980430021
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [17,7]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [16,15]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980430023
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [9,8]
                  , "type": "treasure"
                  , "item_id": 1203
                  , "one_shot": 980430024
                }
              , "mark1": {
                    "pos": [11, 10]
                  , "ornament": "mark1"
                }
              , "mark2": {
                    "pos": [4, 10]
                  , "ornament": "mark1"
                }
              , "mark3": {
                    "pos": [6, 10]
                  , "ornament": "mark2"
                }
              , "mark4": {
                    "pos": [11, 2]
                  , "ornament": "mark2"
                }
              , "goto_room3": {
                    "trigger": "hero"
                  , "pos": [15,2]
                  , "type": "goto"
                  , "room": "room3"
                }
            }
        }
      , "room3": {
            "id": 98043003
          , "bgm": "bgm_quest_horror"
          , "battle_bg": "room4"
          , "environment": "cave"
          , "start_pos": [8,10]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% い・・いた・・でもなにあれ・・\n空間に穴があいてる・・"
                      , "SPEAK %avatar% もじょ すごいエネルギーなのだ・・"
                      , "LINES %boss% ギョギョギョ・・"
                      , "LINES %boss% コレハ・・時空ノ穴ダ・・"
                      , "SPEAK %avatar% オーラ なんだと？"
                      , "LINES %boss% ワレワレノ世界ト・・ツナガルノダ・・"
                      , "LINES %boss% ワレワレノ神ヲ・・コノ世界二・・"
                      , "SPEAK %avatar% オーラ まさか・・そんなことが・・"
                      , "LINES %avatar% そんなことしたら世界の終わりだ・・"
                      , "SPEAK %avatar% もじょ ・・・"
                      , "LINES %boss% ギョギョギョ・・"
                      , "SPEAK %avatar% オーラ まずいぞ・・このままじゃ、すぐに空間の穴が開く・・"
                      , "SPEAK %avatar% オーラ あれが開ききったらゾット神が召喚されてしまう"
                      , "SPEAK %avatar% もじょ ・・しょうがないのだ・・もじょがあの穴をふさぐのだ"
                      , "SPEAK %avatar% オーラ 馬鹿な！死ぬぞ！やめるんだ！"
                    ]
	                , "chain": "mojyo_appear"
                }
              , "mojyo_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 4]
                      , "character_id":-20106
                      , "icon":"shadowM"
                      , "act_brain": "rest"
                      , "union": 1
                      , "align": 3
                      , "code": "mojo"
                    }
	                , "chain": "speak2"
                }
              , "speak2": {
                     "type": "lead"
                   , "leads": [
                          "LINES %avatar% もじょ！やめて！"
                        , "LINES %mojo% とにかくそいつを倒すのだ！そいつがいる限り穴はふさがらないのだ！"
                        , "LINES %avatar% くっ・・！"
                     ]
                }
              , "whitehall": {
                    "pos": [8, 3]
                  , "ornament": "whitehall"
                }
              , "endspeak": {
                     "type": "lead"
                   , "leads": [
                         "BGMPL bgm_dungeon"
                       , "UALGN %avatar% 3"
                       , "UALGN %mojo% 3"
                       , "LINES %avatar% 倒した！もじょ！倒したよ！"
                       , "LINES %mojo% よくやったのだ！こっちもすべての力を使って破壊するのだ！"
                       , "LINES %avatar% やめて！もじょ！"
                       , "SPEAK %avatar% オーラ やめるんだ！死ぬ気か？"
                       , "EFFEC sprk 0804"
                       , "LINES %mojo% はああああっ！！！！！！！！！"
                       , "BGMPL bgm_mute"
                       , "EFFEC migt 0804"
                       , "DELAY 500"
                     ]
	                , "chain": "birth"
                }
              , "birth": {
                    "type": "call"
                  , "call": "processWhitehallOut"
                  , "chain": "speak_goodby"
                }
              , "speak_goodby": {
                    "type": "lead"
                  , "leads": [
                         "SPEAK %avatar% オーラ やった！時空の穴が閉じた！"
                       , "LINES %avatar% も、もじょ！大丈夫！？"
                       , "UALGN %mojo% 0"
                       , "BGMPL bgm_op"
                       , "LINES %mojo% どうやらここまでみたいなのだ・・"
                       , "EFFEC sprk 0804"
                       , "LINES %mojo% これで終わりなのだ。さよなら・・さよならなのだ・・"
                       , "EFFEC sprk 0804"
                       , "LINES %avatar% やめて！もじょ！ボクを一人にしないでよ！"
                    ]
	                , "chain": "mojo_disappear"
                }
              , "mojo_disappear": {
                    "type": "unit_exit"
                  , "exit_target": "mojo"
	                , "chain": "endspeak_2"
                }
              , "endspeak_2": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UMOVE %avatar% 22"
                      , "LINES %avatar% そんな・・もじょ・・"
                      , "SPEAK %avatar% オーラ ・・"
                      , "LINES %avatar% うううう・・"
                      , "EFFEC recv 0804"
                      , "DELAY 100"
                      , "SPEAK %avatar% オーラ ま、待て・・！様子が変だぞ・・？"
                      , "EFFEC recv 0804"
                      , "DELAY 100"
                      , "LINES %avatar% な・・なにが・・？？"
                      , "EFFEC recv 0804"
                      , "DELAY 100"
                      , "BGMPL bgm_theme"
                    ]
	                , "chain": "mojyo_rebirth"
                }
              , "mojyo_rebirth": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 4]
                      , "character_id":-20106
                      , "icon":"noel"
                      , "union": 1
                      , "act_brain": "rest"
                      , "code": "newmojo"
                    }
	                , "chain": "endspeak_3"
                }
              , "endspeak_3": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% も・・もじょ？"
                      , "LINES %newmojo% い・・生きてる・・？"
                      , "SPEAK %avatar% オーラ なんてことだ・・再生した・・！"
                      , "LINES %avatar% ど・・どうして？しかも人間になってる・・"
                      , "SPEAK %avatar% オーラ だ・・脱皮したんだ・・"
                      , "SPEAK %avatar% オーラ ケットシーは脱皮したら力が倍増するが・・"
                      , "SPEAK %avatar% オーラ このタイミングで・・奇跡だ"
                      , "LINES %avatar% もじょ・・よかった！！！"
                      , "LINES %newmojo% 何か・・ただいま・・！"
                      , "SPEAK %avatar% オーラ 生きるか死ぬかの間際で力が解放されたんだ。"
                      , "SPEAK %avatar% オーラ 良かった・・ほんとに・・"
                      , "LINES %newmojo% 心配かけたな、ねえちゃん"
                      , "SPEAK %avatar% オーラ ちょっと早いけど成人式だな。"
                      , "LINES %newmojo% さ・・これで解決だ。約束通り元の世界に戻るよ。"
                      , "SPEAK %avatar% オーラ か・・帰るのか・・？"
                      , "LINES %newmojo% もう今更、俺が生きてたってことにはできないだろ？"
                      , "SPEAK %avatar% オーラ うっ・・。"
                      , "SPEAK %avatar% オーラ ・・そうだな・・影の歴史ってことになるな"
                      , "LINES %newmojo% そういうことだ・・。オーラねえちゃん、元気でな"
                      , "SPEAK %avatar% オーラ ああ。ありがとう。元気でな。"
                      , "LINES %avatar% よし！元の世界に戻ろう！"
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
                    "pos": [8,6]
                  , "character_id":-9158
                  , "icon":"shadowG"
                  , "code": "boss"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                  , "trigger_gimmick": "endspeak"
                }
            ]
        }
    }
}
