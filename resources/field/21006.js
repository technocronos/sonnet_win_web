{
    "extra_uniticons": ["shadow", "shadow2", "elena", "man", "uncle"]
  , "start_units": [
        {
            "character_id": -20101
          , "icon":"elena"
          , "union": 1
          , "code": "elena"
          , "act_brain": "manual"
          , "items": [-1002, -1002, -1002, -1005, -1005, -1005, -3002, -3002, -3002, -3002, -3002, -3002, -3101, -3101, -3101, -3101]
		  , "add_level": 37
          , "early_gimmick": "elena_escape"
        }
    ]
  , "global_gimmicks": {
        "elena_escape": {
            "ignition": {"unit_exist":"elena"}
          , "type": "lead"
          , "textsymbol": "sphere_elena_escape"
          , "rem": [
                "LINES %elena% いたたた…\nごめん、ちょっと下がってるね…"
            ]
        }
    }
  , "rooms": {
        "start": {
            "id": 21006000
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos": [0,19]
          , "gimmicks": {
                "goto": {
                    "trigger": "hero"
                  , "pos": [6, 3]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
              , "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ふう・・またしても完全に道に迷ったぞ・・"
                      , "LINES %avatar% しかもおなかがすいた・・"
                      , "SPEAK %avatar% もじょ もう何回迷えば気がすむのだ・・。"
                      , "SPEAK %avatar% もじょ エレナもなんで迷うのだ！！"
                      , "LINES %elena% おっかしーなー・・ごめんね（てへぺろ）"
                      , "SPEAK %avatar% もじょ てへぺろ、じゃねーのだ！"
                      , "SPEAK %avatar% もじょ もうやってられんのだ"
                      , "SPEAK %avatar% もじょ 島に帰るのだ！！"
                      , "LINES %avatar% ま、まあまあそんな怒らないでよ・・"
                      , "SPEAK %avatar% もじょ もうだめなのだ・・"
                      , "SPEAK %avatar% もじょ このまんま行き倒れてしまうのだ"
                      , "LINES %avatar% と・・とりあえず行ってみよ"
                    ]
                }
            , "speak1": {
                  "trigger": "hero"
                , "pos": [11,12], "rb":[13,14]
                , "type": "lead"
                , "leads": [
                      "LINES %elena% ・・ん？なんかいいにおい・・"
                    , "LINES %avatar% 下の方からするね"
                    , "SPEAK %avatar% もじょ 行ってみるのだ！！"
                  ]
              }
            , "speak2": {
                  "trigger": "hero"
                , "pos": [11,15], "rb":[16,16]
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% ・・あれ？なんだ？"
                    , "FOCUS %dwarf%"
                    , "LINES %avatar% こんな森の奥に太ったおっさんがいる・・"
                    , "LINES %avatar% ・・しかも、小さっ！"
                    , "LINES %elena% あっ！あれは・・ドワーフよ！！"
                    , "LINES %avatar% ドワーフ？？"
                    , "SPEAK %avatar% もじょ どうやら湖のほとりで食事をしてるらしいのだ"
                  ]
              }
            , "speak3": {
                  "condition": {"cleared":false}
                , "trigger": "hero"
                , "pos": [6,6], "rb":[8,8]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% もじょ これはどっちなのだ？"
                    , "LINES %elena% うーん・・たしか左だったかな・・"
                    , "UALGN %elena% 1"
                    , "LINES %avatar% 左・・？こっち？"
                    , "UALGN %avatar% 1"
                    , "LINES %avatar% じゃ行ってみよう"
                    , "SPEAK %avatar% もじょ 嫌な予感がするのだ・・"
                  ]
              }
            , "speak4": {
                  "condition": {"cleared":false}
                , "trigger": "hero"
                , "pos": [0,3], "rb":[2,4]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% もじょ なーんもないのだ"
                    , "LINES %elena% うーん・・まちがえちゃったかな"
                    , "SPEAK %avatar% もじょ ・・・"
                  ]
              }
            , "speak_dwarf": {
                  "condition": {"cleared":false}
                , "trigger": "hero"
                , "pos": [17,15], "rb":[18,16]
                , "type": "lead"
                , "leads": [
                      "AALGN %avatar% %dwarf%"
                    , "LINES %dwarf% ん？何だお前人間か？"
                    , "LINES %avatar% はい・・森に迷っちゃいまして・・その・・"
                    , "LINES %dwarf% ふん人間なんかどうなろうとしったこっちゃないね"
                    , "LINES %elena% あ、あのー・・"
                    , "LINES %dwarf% ん？エルフか？めずらしいな・・"
                    , "LINES %dwarf% だが、人間なんぞつれてるやつは信用ならん"
                    , "LINES %avatar% そ、そんなー・・"
                    , "LINES %dwarf% んっ？"
                    , "LINES %dwarf% ・・・"
                    , "LINES %dwarf% お前からは何か人間以外のにおいがするな・・"
                    , "LINES %dwarf% ひょっとしたらお前には亜人の血が混じっとるのか？"
                    , "LINES %avatar% え？そういうのわかるの？"
                    , "LINES %dwarf% フーム・・"
                    , "LINES %dwarf% しかし、亜人が人間と交わることはできぬはず・・"
                    , "LINES %dwarf% 祖先が遠すぎるからの"
                    , "LINES %dwarf% 確か、1000年に一度くらいの確率でそういう者が誕生するという話は言い伝えで残っておる"
                    , "LINES %dwarf% その者はとてつもない能力と共に非常に数奇な運命をたどると言う・・"
                    , "LINES %avatar% よくは分からないんだけど"
                    , "LINES %avatar% 顔の痣がトロルが持ってたものに似てるってシショーが言ってた"
                    , "LINES %dwarf% ト、トロル・・だと？"
                    , "LINES %dwarf% トロルとの合いの子が祖先であったとしても"
                    , "LINES %dwarf% 300年前の亜人の血がこんなにも強いものか・・。"
                    , "LINES %avatar% で、トロルの里に行ってみようかと思ってて・・"
                    , "LINES %elena% で、シルフを探していたんです"
                    , "LINES %dwarf% フーム・・なるほど・・"
                    , "LINES %dwarf% シルフにとっては300年などほんの最近の出来事なはずじゃ。"
                    , "LINES %dwarf% 何せ森と共に生まれていきてるからの。"
                    , "LINES %avatar% ど、どこにいるの？"
                    , "LINES %dwarf% この先にシルフがいる塚があるぞ"
                    , "LINES %dwarf% 藪は通り過ぎることができるから近寄って調べてみろ"
                    , "LINES %elena% あ、そうそう！確かあっちあっち！"
                    , "SPEAK %avatar% もじょ いまさら遅いのだ・・"
                    , "LINES %avatar% 分かった！行ってみるよ"
                    , "LINES %dwarf% まあ、道は険しいからな気をつけて行くがいい"
                    , "LINES %avatar% ありがとう小さいおっさん！"
                    , "LINES %dwarf% ち、小さいおっさん、じゃと？"
                    , "LINES %dwarf% おれはドワーフ年齢で言ったらまだ10代じゃぞ！！"
                    , "LINES %dwarf% しかも身長も高いほうじゃい！！！"
                  ]
              }
            , "speak_dwarf_3": {
                  "condition": {"cleared":true}
                , "trigger": "hero"
                , "pos": [17,15], "rb":[18,16]
                , "type": "lead"
                , "leads": [
                      "AALGN %avatar% %dwarf%"
                    , "LINES %dwarf% この先にシルフがいる塚があるぞ"
                    , "LINES %dwarf% 藪は通り過ぎることができるから近寄って調べてみろ"
                    , "LINES %dwarf% まあ、道は険しいからな気をつけて行くがいい"
                    , "LINES %avatar% ありがとう小さいおっさん！"
                    , "LINES %dwarf% ち、小さいおっさん、じゃと？"
                    , "LINES %dwarf% おれはドワーフ年齢で言ったらまだ10代じゃぞ！！"
                    , "LINES %dwarf% しかも身長も高いほうじゃい！！！"
                  ]
              }
	          , "enemy1.1": {
	                "trigger": "player"
	              , "pos":[1,19], "rb":[6,20]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [8, 19]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "act_brain": "ThroughDwarf"
                     , "unit_class": "ExBrains"
	                 }
	               , "chain": "enemy1.2"
	             }
	          , "enemy1.2": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [8, 20]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	               , "chain": "enemy1.3"
	             }
	          , "enemy1.3": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [5, 16]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	             }
	          , "enemy1.4": {
	                "trigger": "player"
	              , "pos":[5,16], "rb":[9,17]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [0, 14]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	               , "chain": "enemy1.5"
	             }
	          , "enemy1.5": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [1, 14]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	             }
	          , "enemy1.6": {
	                "trigger": "player"
	              , "pos":[0,9], "rb":[9,11]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [5, 10]
	                   , "character_id":-10057
	                   , "icon":"shadow"
	                   , "add_level":10
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	               , "chain": "enemy1.7"
	             }
	          , "enemy1.7": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [6, 9]
	                   , "character_id":-10057
	                   , "icon":"shadow"
	                   , "add_level":10
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	               , "chain": "enemy1.8"
	             }
	          , "enemy1.8": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [9, 11]
	                   , "character_id":-10057
	                   , "icon":"shadow"
	                   , "add_level":10
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	             }

	          , "enemy1.9": {
	                "trigger": "player"
	              , "pos":[0,3], "rb":[3,6]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [1, 3]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	             }
	          , "enemy1.10": {
	                "trigger": "player"
	              , "pos":[9,6], "rb":[12,8]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [11, 10]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":12
	                 }
	               , "chain": "enemy1.11"
	             }
	          , "enemy1.11": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [11, 11]
	                   , "character_id":-10062
	                   , "icon":"shadow"
	                   , "add_level":12
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                 }
	             }

	          , "enemy1.12": {
	                "trigger": "player"
	              , "pos":[11,15], "rb":[15,16]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [15, 16]
	                   , "character_id":-10057
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "icon":"shadow"
	                   , "add_level":10
	                 }
	               , "chain": "enemy1.13"
	             }
	          , "enemy1.13": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [16, 16]
	                   , "character_id":-10057
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "icon":"shadow"
	                   , "add_level":10
	                 }
	             }
	          , "enemy1.14": {
	                "trigger": "player"
	              , "pos":[17,19], "rb":[18,19]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [11, 20]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":12
	                 }
	               , "chain": "enemy1.15"
	             }
	          , "enemy1.15": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [12, 20]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":12
	                 }
	             }
	          , "enemy1.16": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [13, 20]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":12
	                 }
	             }

	          , "enemy1.17": {
	                "trigger": "hero"
	              , "pos":[16,12], "rb":[20,13]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [16, 3]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "act_brain": "ThroughDwarf"
                     , "unit_class": "ExBrains"
	                   , "add_level":12
	                 }
	               , "chain": "enemy1.18"
	             }
	          , "enemy1.18": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [17, 3]
	                   , "character_id":-10062
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":12
	                 }
	             }
	          , "enemy1.19": {
	                "trigger": "hero"
	              , "pos":[19,3], "rb":[20,7]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [20, 3]
	                   , "character_id":-10058
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "icon":"shadow"
	                   , "add_level":9
	                 }
	             }

	          , "enemy1.20": {
	                "trigger": "hero"
	              , "pos":[13,3], "rb":[17,4]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [7, 3]
	                   , "character_id":-10057
	                   , "icon":"shadow"
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "add_level":10
	                 }
	               , "chain": "enemy1.21"
	             }
	          , "enemy1.21": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [8, 3]
	                   , "character_id":-10057
                     , "unit_class": "ExBrains"
                     , "act_brain": "ThroughDwarf"
	                   , "icon":"shadow"
	                   , "add_level":10
	                 }
	             }
              , "treasure1": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[8,14]
                  , "type": "treasure"
                  , "item_id": 11017
                  , "one_shot": 210060001
                }
              , "treasure2": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[0,3]
                  , "type": "treasure"
                  , "item_id": 14017
                  , "one_shot": 210060002
                }
              , "treasure3": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[18,19]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 210060003
                }
              , "treasure4": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[11,20]
                  , "type": "treasure"
                  , "item_id": 3004
                  , "one_shot": 210060004
                }
              , "treasure5": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[13,9]
                  , "type": "treasure"
                  , "item_id": 1002
                }
              , "treasure6": {
                    "trigger": "hero"
                  , "pos":[20,3]
                  , "type": "treasure"
                  , "item_id": 1202
                }
              , "tanpopo1": {
                    "pos": [5, 20]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [0, 15]
                  , "ornament": "tanpopo"
                }
              , "torch": {
                    "pos": [17, 15]
                  , "ornament": "torch2"
                }

            }
          , "units": [
                {
                       "pos": [18, 15]
                     , "character_id":-20105
	                   , "icon":"uncle"
                     , "union": 1
                     , "align": 1
                     , "code": "dwarf"
                     , "act_brain": "rest"
                     , "brain_noattack": true
                }
            ]
        }
      , "floor2": {
            "id": 21006001
          , "battle_bg": "forest2"
          , "environment": "rain"
          , "start_pos": [6,3]
          , "gimmicks": {

                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %elena% さあ、もう少しだよ"
                    ]
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
	          , "enemy2.1": {
	                "trigger": "hero"
	              , "pos":[8,3], "rb":[14,4]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [13, 3]
	                   , "character_id":-10058
	                   , "icon":"shadow"
	                   , "add_level":9
	                 }
	               , "chain": "enemy2.2"
	             }
	          , "enemy2.2": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [14, 3]
	                   , "character_id":-10058
	                   , "icon":"shadow"
	                   , "add_level":9
	                 }
	             }
	          , "enemy2.3": {
	                "trigger": "hero"
	              , "pos":[10,6], "rb":[13,9]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [5, 7]
	                   , "character_id":-10068
	                   , "icon":"shadow"
	                 }
	               , "chain": "enemy2.4"
	             }
	          , "enemy2.4": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [4, 7]
	                   , "character_id":-10068
	                   , "icon":"shadow"
	                 }
	             }
	          , "enemy2.5": {
	                "trigger": "hero"
	              , "pos":[0,9], "rb":[9,11]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [8, 11]
	                   , "character_id":-10058
	                   , "icon":"shadow"
	                   , "add_level":9
	                 }
	               , "chain": "enemy2.6"
	             }
	          , "enemy2.6": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [9, 11]
	                   , "character_id":-10058
	                   , "icon":"shadow"
	                   , "add_level":9
	                 }
	             }
	          , "enemy2.7": {
	                "trigger": "hero"
	              , "pos":[11,15], "rb":[15,16]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [5, 16]
	                   , "character_id":-10068
	                   , "icon":"shadow"
	                 }
	               , "chain": "enemy2.8"
	             }
	          , "enemy2.8": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [4, 16]
	                   , "character_id":-10068
	                   , "icon":"shadow"
	                 }
	             }
	          , "enemy2.9": {
	                "trigger": "hero"
	              , "pos":[0,3], "rb":[3,4]
	              , "type": "unit"
	              , "unit": {
	                     "pos": [1, 3]
	                   , "character_id":-10068
	                   , "icon":"shadow"
	                 }
	             }
              , "treasure1": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[11,12]
                  , "type": "treasure"
                  , "item_id": 1299
                  , "one_shot": 210060011
                }
              , "treasure2": {
                    "trigger": "hero"
                  , "ornament": "twinkle"
                  , "pos":[0,3]
                  , "type": "treasure"
                  , "item_id": 1003
                }
              , "meetshilf": {
                    "trigger": "hero"
                  , "pos": [1,15]
        				  , "type": "lead"
                  , "leads": [
                        "LINES %elena% シルフ！風のシルフ！"
                    ]
                  , "ornament": "curious"
	                , "chain": "enemy1"
                }
	          , "enemy1": {
	                "type": "unit"
	              , "unit": {
	                    "pos": [1, 13]
	                  , "character_id":-10049
	                  , "icon":"shadow2"
	                  , "code": "shilf"
        					  , "early_gimmick": "endspeak"
        					  , "trigger_gimmick": "goal"
                    , "bgm": "bgm_bossbattle"
	                }
	              , "chain": ["drama1", "drama2"]
	            }
              , "tanpopo1": {
                    "pos": [7, 3]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [2, 7]
                  , "ornament": "tanpopo"
                }
              , "tanpopo3": {
                    "pos": [12, 13]
                  , "ornament": "tanpopo"
                }
              , "blueflower1": {
                    "pos": [0, 13]
                  , "ornament": "blueflower"
                }
              , "blueflower2": {
                    "pos": [1, 13]
                  , "ornament": "blueflower"
                }
              , "blueflower3": {
                    "pos": [2, 13]
                  , "ornament": "blueflower"
                }
              , "blueflower4": {
                    "pos": [0, 14]
                  , "ornament": "blueflower"
                }
              , "blueflower5": {
                    "pos": [1, 14]
                  , "ornament": "blueflower"
                }
              , "blueflower6": {
                    "pos": [2, 14]
                  , "ornament": "blueflower"
                }
	          , "drama1": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %elena% 風のシルフ。お久しぶりです。"
                      , "LINES %elena% エルフのエレナです。"
                      , "LINES %shilf% エレナ・・これは久しぶりですね。"
                      , "LINES %shilf% どうしたのですか？今日は。"
                      , "LINES %elena% 今日は会わせたい人がいまして。"
	                    , "LINES %avatar% へへ。どうも・・。"
                      , "LINES %shilf% 私が人間嫌いなことは知ってるでしょう？エレナ。"
                      , "LINES %elena% えっとですね・・。"
                      , "LINES %shilf% ムッ！あなたは・・"
                      , "LINES %shilf% ・・トロルの血が混じってますね・・"
	                    , "LINES %avatar% え？分かるんですか？"
                      , "LINES %elena% ええ、それで実は・・。"
                      , "LINES %shilf% ま、まさか・・"
                      , "LINES %shilf% ・・私を食べに来たというのですか！？"
	                    , "LINES %avatar% い、いや。まさかそんな・・。"
                      , "LINES %elena% ち、違います。トロルの里の場所を・・"
                      , "LINES %shilf% 分かりました！人間への復讐ですね？"
                      , "LINES %shilf% またすべての亜人を巻き込もうとして私のところに！"
                      , "SPEAK %avatar% もじょ な、なんか早とちりばっかなのだ・・。"
                      , "LINES %shilf% まあ！こんなブサイクな妖精を従えて！"
                      , "LINES %shilf% エレナ、一体どういうつもりですか！？"
                      , "SPEAK %avatar% もじょ こ、この・・！バカようせ・・"
                      , "LINES %avatar% おおっと！それ以上言っちゃ駄目！"
                      , "LINES %avatar% 私はただエレナにお願いしてここにつれてきてもらっただけなのでエレナは関係ないです。"
                      , "LINES %shilf% エレナを脅してここまでつれて来させたというわけですね？"
                      , "LINES %shilf% ゆ、許せません・・！"
                      , "LINES %avatar% わー、もう何を言っても悪くとるよー。"
                      , "LINES %elena% ちょ、ちょっとシルフ。話を聞いてください！"
                      , "LINES %shilf% ・・あ、貴方はブラックトロルの血をついでますね？"
                      , "LINES %avatar% え？トロルとは違う種別なんですか？"
                      , "LINES %shilf% 亜人は人間を殺すと血が黒化するんです。"
                      , "LINES %shilf% 黒化した亜人はとても凶暴になり、人間でも亜人でもなんでも食べます。"
                      , "LINES %shilf% といってもトロルは人間と戦争してました。"
                      , "LINES %shilf% 最後の方はすべてのトロルがほとんど黒化していたはず。"
                      , "LINES %shilf% 人間に対する憎悪を持って"
                      , "LINES %shilf% 破壊のみをする邪悪な存在だったのです。"
                      , "LINES %shilf% しかしブラックトロルは300年前にとうに滅びたはず・・"
                      , "LINES %shilf% なのにあなたはとても強くブラックトロルの血が入っているようです。"
                      , "LINES %shilf% 世界にまたあの厄災を撒き散らすわけにはいきません！"
                      , "LINES %avatar% ・・・。"
                      , "LINES %shilf% ・・・。"
                      , "LINES %avatar% ・・・。"
                      , "LINES %shilf% ・・私が成敗します・・"
                      , "LINES %avatar% ・・・え？"
                      , "LINES %shilf% ・・私が貴方を成敗します！"
                      , "LINES %avatar% ・・・せい・・ばい・・？"
                      , "LINES %shilf% ・・貴方個人に怨みはありませんが悪く思わないで下さい！！"
                      , "LINES %avatar% ちょ、ちょっ！！"
                      , "SPEAK %avatar% もじょ どうしてこうなるのだ・・。"
                    ]
	            }
	          , "endspeak": {
	                 "type": "lead"
	               , "leads": [
	                     "LINES %shilf% ぐっ・・。私の負けです。"
	                   , "LINES %shilf% さあ、ひと思いに殺しなさい。情けは屈辱ですよ。"
	                   , "LINES %avatar% い、いや。だからですね・・。そんなつもりでは・・。"
	                   , "LINES %elena% エレナ 私達はただ、トロルの里に行きたいだけなんです。"
	                   , "LINES %shilf% えっ？そうなのですか？"
	                   , "LINES %shilf% それならそうと最初から言ってくれればよかったのですよ。"
	                   , "SPEAK %avatar% もじょ うーん・・イラつくのだ・・。"
	                   , "SPEAK %avatar% もじょ 望み通りトドメをさしてやるのだ。"
	                   , "LINES %avatar% もじょ！"
	                   , "LINES %avatar% というわけなので教えてもらえないでしょうか。"
	                   , "LINES %shilf% フム。戦ってみて分かりましたがあなたには正しい心があるようですね。"
	                   , "LINES %shilf% ブラックトロルの集落はここから東の火山の向こうにあります。"
	                   , "LINES %shilf% ですが、たどり着くには大変長い道のりですよ。気をつけて・・"
	                 ]
	            }
            }
        }
    }
}
