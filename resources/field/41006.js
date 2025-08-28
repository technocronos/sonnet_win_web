{
    "extra_uniticons": ["shadow", "shadow2","man", "woman", "boy", "layla"]
  , "rooms": {
        "start": {
            "id": 41006000
          , "battle_bg": "desert"
          , "bgm": "bgm_registance"
          , "environment": "grass"
          , "start_pos": [6,7]
          , "gimmicks": {
                "open_comment": {
                    "ignition": {"!has_flag":410060001}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %gebal% ようこそ、キャラバンの野営キャンプへ"
                      , "LINES %gebal% 俺がリーダーのゲバルだ"
                      , "LINES %gebal% うちのものを砂漠蜘蛛から助けてくれたそうだな礼を言おう"
                      , "LINES %gebal% 君もレジスタンスの一員ってわけだな。ハハハ！"
                      , "LINES %avatar% いや・・ボクはそんなつもりは・・"
                      , "LINES %gebal% だが、そんなに簡単にレジスタンス入りを認めるわけにはいかない"
                      , "LINES %gebal% まずは下積みからだ"
                      , "LINES %avatar% いや・・だから・・ボクはそんなつもりじゃなく・・"
                      , "LINES %gebal% 今夜、このオアシスで反政府大会をやるんだ。"
                      , "LINES %gebal% さっそくだがステージ設営を手伝って欲しい。"
                      , "LINES %avatar% だから何でボクがそんなことを・・。"
                      , "LINES %gebal% 頼んだぞ！！"
                      , "LINES %avatar% ・・押しの強いひとだなあ"
                      , "SPEAK %avatar% もじょ 人の話をきかないやつなのだ・・。"
                      , "SPEAK %avatar% もじょ 人の上に立つ奴なんてあんなんばっかなのだ。"
                      , "SPEAK %avatar% もじょ めしも食ったし、ばっくれるのだ。"
                      , "LINES %avatar% って言ったってどうやって帰るのさ・・。"
                      , "SPEAK %avatar% もじょ ・・・"
                      , "LINES %gebal% じゃ、さっそくだがその左端にある台を"
                      , "LINES %gebal% そこの木の前の緑のところに移動してくれ"
                      , "LINES %avatar% はー・・なんでこんなことに・・"
                    ]
                }
              , "goto_next": {
                    "condition": {"has_flag":410060001}
                  , "trigger": "hero"
                  , "pos": [6,6]
                  , "type": "goto"
                  , "room": "spider_area"
                  , "ornament": "goto"
                }
              , "audience1": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [4,6]
                      , "align": 3
                      , "icon":"man"
                      , "union": 1
                    }
                  , "chain": "audience2"
                }
              , "audience2": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [5,6]
                      , "align": 3
                      , "icon":"man"
                      , "union": 1
                    }
                  , "chain": "audience3"
                }
              , "audience3": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [6,6]
                      , "icon":"boy"
                      , "union": 1
                      , "align": 3
                    }
                  , "chain": "audience4"
                }
              , "audience4": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [7,6]
                      , "icon":"man"
                      , "union": 1
                      , "align": 3
                    }
                  , "chain": "audience5"
                }
              , "audience5": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [8,6]
                      , "icon":"woman"
                      , "align": 3
                      , "union": 1
                    }
                  , "chain": "audience6"
                }
              , "audience6": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [9,6]
                      , "icon":"shadow"
                      , "align": 3
                      , "union": 1
                    }
                }
              , "audience7": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [4,7]
                      , "icon":"man"
                      , "union": 1
                      , "align": 3
                    }
                  , "chain": "audience8"
                }
              , "audience8": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [5,8]
                      , "icon":"man"
                      , "align": 3
                      , "union": 1
                    }
                  , "chain": "audience9"
                }
              , "audience9": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [6,7]
                      , "icon":"boy"
                      , "align": 3
                      , "union": 1
                    }
                  , "chain": "audience10"
                }
              , "audience10": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [7,7]
                      , "icon":"man"
                      , "align": 3
                      , "union": 1
                    }
                  , "chain": "audience11"
                }
              , "audience11": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [8,8]
                      , "icon":"woman"
                      , "align": 3
                      , "union": 1
                    }
                  , "chain": "audience12"
                }
              , "audience12": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10017
                      , "pos": [9,7]
                      , "icon":"shadow"
                      , "align": 3
                      , "union": 1
                    }
                }
              , "drama1": {
                    "type": "drama"
                  , "drama_id": 4100601
                }
              , "layla": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -20103
                      , "pos": [8,4]
                      , "icon":"layla"
                      , "union": 1
                      , "code": "layla"
                      , "act_brain": "rest"
                    }
                  , "chain": "speak1_1"
                }
               , "speak1_1": {
                     "type": "lead"
                   , "leads": [
                        "LINES %layla% あら？あなた新入り？"
                      , "LINES %avatar% え？いや、新入りというかなんというか・・"
                      , "LINES %avatar% あなたもレジスタンスなんですか？"
                      , "LINES %layla% 私、レイラ。レジスタンスではリーダーの護衛を担当してるの。"
                      , "LINES %layla% よろしくね！"
                      , "LINES %avatar% は、はい。よろしくお願いします・・。"
                      , "LINES %layla% あとはたまに諜報活動もやってるの"
                      , "LINES %avatar% それってつまりスパイ？"
                      , "LINES %layla% そうね。マルティーニに市民を装って潜入して・・"
                      , "LINES %layla% 情報をつかんだらこっちに報告にきてるの。"
                      , "LINES %avatar% あのーひょっとしてレイラさん、ちょっとまざってません？"
                      , "LINES %layla% あら、よく分かったわね。私は実は亜人なの。ラミア族。"
                      , "LINES %avatar% ラミア族！！"
                      , "SPEAK %avatar% もじょ めずらしいのだ・・絶滅したかと思ってたのだ"
                      , "LINES %layla% そうね。マルティーニの亜人狩りで仲間は全部殺されたの"
                      , "LINES %avatar% だからレジスタンスに？"
                      , "LINES %layla% ええ、亜人と人間の共生を訴えるリーダーを尊敬してるの。"
                      , "FOCUS %gebal%"
                      , "NOTIF ゲバルー！！"
                      , "NOTIF ゲバルーさーん！！"
                      , "DELAY 700"
                      , "LINES %avatar% ・・なんとなく分かる・・かな"
                      , "LINES %layla% 私も分かるわ。"
                      , "LINES %layla% あなたも亜人でしょ？私たち仲間ね。よろしく。"
                      , "LINES %avatar% え、わかるんですか？"
                      , "LINES %avatar% ぼくはどうやら遠い祖先がトロル族みたいですけど。"
                      , "LINES %layla% ・・！"
                      , "LINES %layla% トロル族！？そっちの方がよっぽど珍しいじゃない！！"
                      , "LINES %avatar% まだ全然分からないんですけど"
                      , "LINES %layla% そう・・まだ他に生きてたんだ・・"
                      , "LINES %avatar% えっ？ほ、ほかに・・？"
                      , "LINES %layla% う、ううん。こっちの話"
                      , "NOTIF そこまでだ！！"
                      , "LINES %avatar% んっ？？何だ！？"
                     ]
                  , "chain": "enemy1_1"
                 }
              , "enemy1_1": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10054
                      , "pos": [11,5]
                      , "icon":"shadow2"
                      , "align": 1
                      , "code": "taityo"
                    }
                  , "chain": "enemy1_2"
                }
             , "enemy1_2": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [12,4]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_3"
                }
             , "enemy1_3": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [12,5]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_4"
                }
             , "enemy1_4": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [12,6]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_5"
                }
             , "enemy1_5": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [13,3]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_6"
                }
             , "enemy1_6": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [13,4]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_7"
                }
             , "enemy1_7": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [13,5]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_8"
                }
             , "enemy1_8": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [13,6]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "enemy1_9"
                }
             , "enemy1_9": {
                    "type": "unit"
                  , "quiet": true
                  , "unit": {
                        "character_id": -10052
                      , "pos": [13,7]
                      , "align": 1
                      , "icon":"shadow"
                    }
                  , "chain": "speak1_3"
                }
               , "speak1_3": {
                     "type": "lead"
                   , "leads": [
                        "UALGN %avatar% 2"
                      , "UALGN %gebal% 2"
                      , "UALGN %layla% 2"
                      , "LINES %taityo% 今日、大規模な反政府集会が開かれるとタレコミがあった！！"
                      , "LINES %taityo% 情報は確かだったらしいな。きさまら、マルティーニ朝に逆らう危険分子だ！！"
                      , "LINES %avatar% す、すごい数・・"
                      , "LINES %gebal% ま、まさか！情報は極秘だったはず・・。"
                      , "LINES %taityo% 愚か者め、マルティーニの情報網を甘く見るとはな！"
                      , "LINES %taityo% 貴様らは王に逆らう賊を仲間に引き入れて隠匿してもいるだろう。"
                      , "LINES %taityo% 最近、[NAME]というやつがスパイの疑いでマルティーニで指名手配された。"
                      , "LINES %taityo% そいつもここにいるのだろう？"
                      , "LINES %avatar% げげっ！！"
                      , "SPEAK %avatar% もじょ 情報早すぎなのだ。"
                      , "LINES %gebal% 君のことか？まさか指名手配されてたとはな・・。"
                      , "LINES %avatar% な、なんもしてないんですけど・・"
                      , "LINES %taityo% レジスタンス討伐隊よ。全員ひっとらえてしまえ！"
                      , "LINES %gebal% グッ！！みんな、一旦逃げるんだ！！"
                     ]
                  , "chain": "goto"
                 }
              , "goto": {
                    "type": "goto"
                  , "room": "spider_area"
                  , "one_shot": 410060001
                }
              , "lose": {
                    "type": "escape"
                  , "escape_result": "failure"
                }
            }
          , "units": [
                {
                   "condition": {"!has_flag":410060001}
                  , "pos": [7,7]
                  , "character_id":-20102
                  , "icon":"man"
                  , "union": 1
                  , "code": "gebal"
                  , "act_brain": "rest"
                  , "trigger_gimmick": "lose"
                }
            ]
        }
      , "spider_area": {
            "id": 41006001
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos": [0,6]
          , "gimmicks": {
               "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %taityo% さあ、もう逃げられないぞ！！"
                      , "LINES %taityo% 全員、あのリーダーを狙え！討ち取ったら勝ちだ！"
                      , "LINES %avatar% わ・・追い詰められちゃった・・"
                      , "LINES %gebal% フフフただ逃げてたわけじゃないぞ！"
                      , "LINES %avatar% そういえばどこかで見たことのある地形・・"
                      , "LINES %gebal% その通り！ここは砂漠蜘蛛の巣の跡だ！"
                      , "LINES %gebal% 穴から穴へ伝って奇襲をかけれるぞ！"
                      , "LINES %layla% 確かにあいつらは砂漠のことは知らないし"
                      , "LINES %layla% ここで迎撃しましょう！"
                      , "LINES %gebal% 隊長をやっつけたら指揮系統を失うだろう。こっちの勝ちだ！"
                      , "LINES %gebal% ただな・・俺は体力には全く自信ないんだよ・・"
                      , "SPEAK %avatar% もじょ ・・情けないのだ。自分の身くらい自分で守るのだ"
                      , "LINES %avatar% もじょ！いいじゃない！誰だって不得手なことくらいあるんだからっ！！"
                      , "SPEAK %avatar% もじょ 恋は盲目なのだ・・"
                      , "LINES %layla% 私も。レジスタンスはリーダーを失うわけにはいかないもの。"
                      , "LINES %gebal% そうかい？んじゃ、よろしく～！"
                      , "SPEAK %avatar% もじょ ・・軽い男なのだ。"
                      , "LINES %layla% じゃあ、私か[NAME]どちらかがオフェンス、どちらかがディフェンスね"
                   ]
                }
              , "finish": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "touch": "finish_comment"
                }
              , "finish_comment": {
                     "type": "lead"
                   , "leads": [
                         "LINES %gebal% よし！！敵は指揮系統を失った！！"
                       , "LINES %gebal% このままアジトまで逃げるぞ！！"
                     ]
                }
               , "losespeak": {
                     "type": "lead"
                   , "leads": [
                         "LINES %gebal% ぐはっ！！"
                     ]
                 }
              , "lose": {
                    "type": "escape"
                  , "escape_result": "failure"
                }
              , "enemy12": {
                    "trigger": "termination"
                  , "termination": 3
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,6]
                      , "code": "zohyo12"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy13"
                }
              , "enemy13": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,7]
                      , "code": "zohyo13"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy14"
                }
              , "enemy14": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,8]
                      , "code": "zohyo12"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                }
              , "enemy15": {
                    "trigger": "termination"
                  , "termination": 7
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,6]
                      , "code": "zohyo15"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy16"
                }
              , "enemy16": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,7]
                      , "code": "zohyo16"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy17"
                }
              , "enemy17": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,8]
                      , "code": "zohyo17"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                }
              , "enemy18": {
                    "trigger": "termination"
                  , "termination": 12
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,6]
                      , "code": "zohyo18"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy19"
                }
              , "enemy19": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,7]
                      , "code": "zohyo19"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                }
              , "enemy20": {
                    "trigger": "termination"
                  , "termination": 18
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,6]
                      , "code": "zohyo20"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy21"
                }
              , "enemy21": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,7]
                      , "code": "zohyo21"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                  , "chain": "enemy22"
                }
              , "enemy22": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10052
                      , "icon": "shadow"
                      , "pos": [18,8]
                      , "code": "zohyo22"
                      , "union": 2
                      , "act_brain": "target"
                      , "target_union": 1
                      , "add_level": 8
                    }
                }
            }
          , "units": [
                {
                    "pos": [0,7]
                  , "character_id":-20102
                  , "icon":"man"
                  , "union": 1
                  , "code": "gebal"
                  , "act_brain": "rest"
                  , "items": [-1002, -1002, -1002, -1002, -3004, -3004, -3004, -3004, -3201, -3201, -3201, -3201]
                  , "early_gimmick": "losespeak"
                  , "trigger_gimmick": "lose"
                }
              , {
                    "pos": [0,8]
                  , "character_id":-20103
                  , "icon":"layla"
                  , "union": 1
                  , "code": "layla"
                  , "items": [-1002, -1002, -1002, -1002, -3004, -3004, -3004, -3004, -3201, -3201, -3201, -3201]
                  , "act_brain": "manual"
                }
              , {
                    "pos": [19,7]
                  , "character_id":-10054
                  , "icon":"shadow2"
                  , "code": "taityo"
                  , "union": 2
                  , "act_brain": "rest"
                  , "trigger_gimmick": "finish"
                  , "bgm": "bgm_bossbattle"
                }
              , {
                    "pos": [18,6]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo1"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [18,7]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo2"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [18,8]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo3"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [13,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo4"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [13,6]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo5"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [13,11]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo6"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [8,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo7"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [8,7]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo8"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [7,11]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo9"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [2,2]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo10"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
              , {
                    "pos": [3,15]
                  , "character_id":-10052
                  , "icon":"shadow"
                  , "code": "zohyo11"
                  , "union": 2
                  , "act_brain": "target"
                  , "target_union": 1
                  , "add_level": 8
                }
            ]
        }
    }
}
