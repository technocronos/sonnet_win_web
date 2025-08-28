{
    "extra_uniticons": ["shadow", "shadowG", "shisyou", "man"]

  , "hero_unit": {
        "unit_class": "31009Hero"
    }
  , "rooms": {
        "start": {
            "id": 31009000
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [8,3]
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんとか逃げ切らなきゃ・・"
                      , "SPEAK %avatar% もじょ 廊下にもうろうろしてるのだ・・"
                      , "LINES %avatar% 外に行く扉を探さないとどこから入ってきたっけ？"
                      , "SPEAK %avatar% もじょ 案内されるままだったから忘れたのだ"
                    ]
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[13, 8], "rb":[17, 9]
                  , "type": "unit"
                  , "unit": {
                         "pos": [0, 8]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "enemy1.2"
                 }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                         "pos": [0, 9]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "speak1_1"
                 }
               , "speak1_1": {
                     "type": "lead"
                   , "leads": [
                        "UALGN %avatar% 1"
                      , "LINES %avatar% ・・げ！"
                      , "SPEAK %avatar% もじょ こっち向かってくるのだ・・"
                      , "UALGN %avatar% 2"
                      , "LINES %avatar% もじょ！あいつ・・"
                      , "FOCUS 07"
                      , "DELAY 1000"
                      , "LINES %avatar% なんだ？あの金ピカのヤツ・・"
                      , "SPEAK %avatar% もじょ めっちゃくちゃ強そうなのだ・・"
                      , "SPEAK %avatar% もじょ 絶対に戦っちゃだめなのだ"
                      , "LINES %avatar% あ、あそこが出口かな？"
                      , "SPEAK %avatar% もじょ 多分どっちかなのだ・・"
                     ]
                 }
               , "movewoden": {
                     "trigger": "hero"
                   , "pos":[17, 1], "rb":[23, 6]
                   , "type": "property"
                   , "unit": "woden1"
                   , "change": {
                        "act_brain": "generic"
                     }
                   , "chain": "speak1_3"
                 }
               , "speak1_3": {
                     "type": "lead"
                   , "leads": [
                        "LINES %woden1% あっ！そんなところに居やがったか！"
                      , "SPEAK %avatar% もじょ ヤバイ・・。見つかったのだ！"
                     ]
                 }
               , "speak1_4": {
                     "trigger": "unit_into"
                   , "unit_into": "woden1"
                   , "pos":[17, 1], "rb":[23, 6]
                   , "type": "lead"
                   , "leads": [
                        "LINES %woden1% 見つけたぞ！待てい！"
                      , "UALGN %avatar% 0"
                      , "LINES %avatar% もう戦うしかないかな・・"
                      , "SPEAK %avatar% もじょ やめとくのだ・・あいつめちゃくちゃ強そうなのだ・・"
                      , "LINES %avatar% だーいじょうぶだよ"
                     ]
                   , "chain": "Galuf1"
                 }
              , "Galuf1": {
                    "type": "unit"
                  , "unit": {
                         "pos": [20, 0]
                       , "character_id":-9902
                       , "icon":"shisyou"
                        , "union": 1
                       , "code": "Galuf1"
                        , "act_brain": "guard"
                       , "guard_unit": "avatar"
                       , "add_level": 26
                     }
                   , "chain": "speak1_5"
                 }
               , "speak1_5": {
                     "type": "lead"
                   , "leads": [
                        "LINES %Galuf1% まてい！"
                      , "UALGN %avatar% 3"
                      , "LINES %woden1% ムッ！なにやつだ。"
                      , "LINES %avatar% あ、師匠・・"
                      , "SPEAK %avatar% もじょ なんだ、じじいか・・なのだ。"
                      , "LINES %Galuf1% なんだとはなんじゃ"
                      , "LINES %Galuf1% そいつはとてつもなく強いぞい。戦ってはいかん"
                      , "LINES %avatar% えー！大体なんで師匠がここにいるのよ"
                      , "LINES %Galuf1% 実はおぬしの頬の痣、母親にも同じものがあってのう"
                      , "LINES %Galuf1% どこかで見たことがあると思って調べとったのじゃが"
                      , "LINES %Galuf1% 昔の本を引っ張り出してきての"
                      , "LINES %Galuf1% その痣じゃが、それはトロル族のものじゃぞ・・"
                      , "LINES %avatar% えっ・・うそ・・"
                      , "LINES %Galuf1% ・・キサマもそれが理由で追いかけまわしとるのじゃろ？"
                      , "LINES %woden1% ククク・・"
                      , "LINES %woden1% トロル族はマルティーニの宿敵だ"
                      , "LINES %woden1% いつキバをむくかわからんのでな・・"
                      , "LINES %Galuf1% そういうこともあろうかと思って追ってきたんじゃよ"
                      , "LINES %Galuf1% 居場所はアベジに聞いたんじゃ"
                      , "LINES %avatar% そ、そうなんだ・・"
                      , "LINES %Galuf1% 話は後じゃ。ここは逃げるぞ。それっ！"
                      , "LINES %woden1% あ！待てい！"
                     ]
                   , "chain": "next_stage"
                 }
              , "cage_key1": {
                    "trigger": "player"
                  , "pos": [21,12]
                  , "type": "lead"
                  , "textsymbol": "sphere_cage_key3"
                  , "leads": [
                        "LINES %avatar% ん？何だコリャ？鍵だ"
                      , "SPEAK %avatar% もじょ たぶん扉の鍵なのだ"
                    ]
                  , "ornament": "twinkle"
                  , "chain_delayed": "opendoor1"
                }
              , "opendoor1": {
                    "type": "square_change"
                  , "change_pos": [18, 10]
                  , "change_tip": 9904
                }
              , "cage_key2": {
                    "trigger": "player"
                  , "pos": [12,12]
                  , "type": "lead"
                  , "textsymbol": "sphere_cage_key3"
                  , "leads": [
                        "LINES %avatar% ん？何だコリャ？鍵だ"
                      , "SPEAK %avatar% もじょ たぶん扉の鍵なのだ"
                    ]
                  , "ornament": "twinkle"
                  , "chain_delayed": "opendoor2"
                }
              , "opendoor2": {
                    "type": "square_change"
                  , "change_pos": [18, 7]
                  , "change_tip": 9904
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[10, 13]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1005
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[24, 12]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1202
                  , "one_shot": 310090001
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos":[25, 12]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 99001
                  , "one_shot": 310090002
                }
              , "next_stage": {
                    "trigger": "player"
                  , "pos":[20, 0]
                  , "type": "goto"
                  , "room": "passage1"
                }
            }
          , "units": [
                {
                    "pos": [4,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                }
              , {
                    "pos": [4,4]
                  , "character_id":-10052
                  , "icon":"shadow"
                }
              , {
                    "pos": [5,8]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [14,9]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                }
              , {
                    "pos": [22,9]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6644"
                  , "excurse_step": 2
                }
              , {
                    "pos": [21,8]
                  , "character_id":-10102
                  , "icon":"shadowG"
                  , "code": "woden1"
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
      , "passage1": {
            "id": 31009001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [0,4]
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %Galuf2% このまま墓地まで行くぞい"
                      , "LINES %avatar% 墓地？そこに何があるの？"
                      , "LINES %Galuf2% 抜け道があるんじゃ。村にはどんどん役人が来るでな"
                      , "LINES %Galuf2% 今回はワシもプレイヤーがマニュアル操作できるぞい"
                      , "LINES %Galuf2% ワシをコマンド操作して敵と戦闘することもできるのじゃ"
                      , "LINES %Galuf2% ただし、行動ptも消費するでの気をつけるがよい"
                      , "LINES %Galuf2% では行くぞい！"
                    ]
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[9, 2], "rb":[10, 5]
                  , "type": "unit"
                  , "code": "enemy2.1"
                  , "unit": {
                         "pos": [0, 4]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.2"
                 }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                         "pos": [0, 5]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "enemy2.3"
                 }
              , "enemy2.3": {
                    "type": "unit"
                  , "unit": {
                         "pos": [1, 5]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "enemy2.4"
                 }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                         "pos": [24, 2]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.5"
                 }
              , "enemy2.5": {
                    "type": "unit"
                  , "unit": {
                         "pos": [24, 3]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "enemy2.6"
                 }
              , "enemy2.6": {
                    "type": "unit"
                  , "unit": {
                         "pos": [23, 2]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "speak2_1"
                 }
               , "speak2_1": {
                     "type": "lead"
                   , "leads": [
                        "LINES %avatar% ヤバイ！追ってきた"
                      , "SPEAK %avatar% もじょ あっちからも来たのだ"
                      , "LINES %avatar% ええい！"
                     ]
                 }
              , "enemy2.7": {
                    "trigger": "player"
                  , "pos":[16, 8], "rb":[20, 9]
                  , "type": "unit"
                  , "unit": {
                         "pos": [11, 8]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.8"
                 }
              , "enemy2.8": {
                    "type": "unit"
                  , "unit": {
                         "pos": [11, 9]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.9"
                 }
              , "enemy2.9": {
                    "type": "unit"
                  , "unit": {
                         "pos": [11, 8]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.10"
                 }
              , "enemy2.10": {
                    "type": "unit"
                  , "unit": {
                         "pos": [11, 9]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                 }
              , "enemy2.11": {
                    "trigger": "player"
                  , "pos":[22, 12], "rb":[24, 15]
                  , "type": "unit"
                  , "unit": {
                         "pos": [19, 13]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.12"
                 }
              , "enemy2.12": {
                    "type": "unit"
                  , "unit": {
                         "pos": [20, 13]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                 }
              , "enemy2.13": {
                    "trigger": "player"
                  , "pos":[18, 14], "rb":[21, 15]
                  , "type": "unit"
                  , "unit": {
                         "pos": [13, 13]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.14"
                 }
              , "enemy2.14": {
                    "type": "unit"
                  , "unit": {
                         "pos": [14, 13]
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                 }
               , "speak2_end": {
                     "trigger": "player"
                   , "pos":[1, 10], "rb":[10, 14]
                   , "type": "lead"
                   , "leads": [
                        "LINES %avatar% はあはあ・・墓地についた"
                      , "SPEAK %avatar% もじょ じじい隠し通路ってどれなのだ？"
                      , "LINES %Galuf2% 待つが良い。ホイッ！"
                     ]
                  , "chain": "opendoor3"
                 }
              , "opendoor3": {
                    "type": "square_change"
                  , "change_pos": [5, 15]
                  , "change_tip": 1
                }
              , "shisyou_escape": {
                    "type": "lead"
                  , "leads": [
                        "LINES %Galuf2% あいたたた…。スマン、ちょっと下がってるわい…"
                    ]
                }
              , "next_stage": {
                    "trigger": "player"
                  , "pos": [5,15]
                  , "type": "goto"
                  , "room": "passage2"
                }
            }
          , "units": [
                {
                    "character_id": -9902
                  , "icon":"shisyou"
                  , "union": 1
                  , "code": "Galuf2"
                  , "pos": [0,5]
                  , "act_brain": "manual"
                  , "add_level": 26
                  , "items": [-1002, -1002, -1002, -1005, -1005, -1005, -3002, -3002, -3002, -3002, -3002, -3002, -3101, -3101, -3101, -3101]
                  , "early_gimmick": "shisyou_escape"
                }
              , {
                    "pos": [9,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                }
              , {
                    "pos": [10,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                }
            ]
        }
      , "passage2": {
            "id": 31009002
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [3,4]
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                         "LINES %avatar% ふぅ・・ようやくまいたか・・"
                       , "LINES %avatar% ・・シショー・・ここは？"
                       , "LINES %Galuf% ここはワシがお前を拾ったところじゃよ・・"
                       , "LINES %Galuf% つまりお前の母親が身を投げたところじゃ"
                       , "LINES %avatar% ・・じゃ、じゃああの墓は・・"
                       , "LINES %Galuf% ワシが建てたんじゃ簡素なものじゃがな"
                       , "LINES %avatar% ・・・"
                       , "NOTIF ふふふ・・喜ぶのはまだ早い・・"
                       , "UALGN %avatar% 3"
                       , "UALGN %Galuf% 3"
                       , "LINES %Galuf% ムッ！！"
                    ]
                   , "chain": "woden_last"
                }
              , "woden_last": {
                    "type": "unit"
                  , "unit": {
                         "pos": [3, 0]
                       , "character_id":-10102
                       , "icon":"shadowG"
                       , "code": "woden_last"
                       , "act_brain": "rest"
                       , "brain_noattack": true
                       , "early_gimmick": "endspeak"
                       , "trigger_gimmick": "goal"
                       , "bgm": "bgm_bigboss"
                     }
                   , "chain": "speak3_1"
                 }
              , "speak3_1": {
                    "type": "lead"
                  , "leads": [
                         "LINES %Galuf% 全く、しつこいのう・・"
                       , "LINES %woden_last% 観念するんだな・・"
                       , "LINES %Galuf% 貴様、オーディンじゃな？"
                       , "LINES %woden_last% ほう、知っているか。いかにも・・"
                       , "LINES %avatar% シショー、知ってるの？"
                       , "LINES %Galuf% マルティーニでは有名な王直属の騎士じゃよ"
                       , "LINES %Galuf% 貴様ほどの者が来るとは王はよほどのことなのじゃな・・？"
                       , "LINES %avatar% ・・シショー。もういいよ"
                       , "LINES %avatar% ボクがやっつけてくる"
                       , "LINES %Galuf% あ、いかん！"
                       , "LINES %woden_last% ククク・・。ではお前を捕らえて連れて行くとしよう・・"
                       , "LINES %woden_last% かかってこい・・"
                    ]
                }
               , "disappear": {
                     "type": "unit_exit"
                   , "exit_target": "Galuf"
                   , "igniter": "Galuf"
                   , "chain": "Galuf_young"
                 }
              , "Galuf_young": {
                    "type": "unit"
                  , "unit": {
                         "pos": [3, 3]
                       , "character_id":-9905
                       , "icon":"man"
                       , "union": 1
                       , "code": "Galuf_young"
                       , "act_brain": "manual"
                     }
                   , "chain": "herorest"
                 }
               , "herorest": {
                     "type": "property"
                   , "unit": "avatar"
                   , "change": {
                        "act_brain": "rest"
                     }
                   , "chain": "speak3_2"
                 }
              , "speak3_2": {
                    "type": "lead"
                  , "leads": [
                         "LINES %woden_last% こ、これは・・"
                       , "LINES %Galuf_young% フッ。これで完了だ。さあ、時間がない。かかってこい"
                       , "LINES %avatar% シ、シショーすごい・・しかも・・"
                       , "LINES %avatar% ・・イケメン・・"
                       , "SPEAK %avatar% もじょ 信じられないのだ・・"
                       , "LINES %woden_last% ぐっ、しゃらくさい！"
                    ]
                }
               , "endspeak": {
                     "type": "lead"
                   , "leads": [
                         "UALGN %avatar% 3"
                       , "LINES %woden_last% ぐはっ！！"
                       , "LINES %Galuf_young% これに懲りて二度とこいつには近寄らないことだ・・"
                       , "LINES %woden_last% き、貴様、これほどの力を持っているのであれば"
                       , "LINES %woden_last% マルティーニの仕官になればいくらでも金が手にはいるぞ？"
                       , "LINES %Galuf_young% フッ？金だと？"
                       , "LINES %Galuf_young% 俺の魂が金ごときで買えるとおもっているのか？"
                       , "LINES %avatar% シ、シショーのセリフとは思えない・・"
                       , "SPEAK %avatar% もじょ 性格まで若返っているのだ"
                       , "SPEAK %avatar% もじょ 月日というのは何と残酷なものなのだ・・"
                       , "LINES %woden_last% ぐっ、では退却するとしよう。さらばだ！"
                     ]
                 }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9902
                  , "icon":"shisyou"
                  , "union": 1
                  , "code": "Galuf"
                  , "pos": [3,5]
                  , "add_level": 26
                   , "act_brain": "rest"
                }
            ]
        }
    }
}
