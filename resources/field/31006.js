{
    "extra_uniticons": ["shadow", "shadow2"]

  , "rooms": {
        "start": {
            "id": 31006000
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [3,4]
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                      , "LINES %avatar% さて…どうしたものか"
                      , "SPEAK %avatar% もじょ 機関室行くんなら先頭車両なのだ"
                      , "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                    ]
                }
              , "next_stage": {
                    "trigger": "hero"
                  , "pos": [3,0]
                  , "type": "goto"
                  , "room": "passage1"
                }
            }
        }
      , "passage1": {
            "id": 31006001
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [7,15]
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% あれ？なんだここ"
                      , "SPEAK %avatar% もじょ 空間が広がっているのだ"
                    ]
                  , "chain": "enemy1.1"
                }
	          , "enemy1.1": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [9, 13]
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
	                     "SPEAK %tamane% ？？？ ようこそおいでくださいました"
	                   , "LINES %avatar% タ、タマネギン？？"
	                   , "SPEAK %tamane% ？？？ ワタクシ、主よりあなた方をご案内するよう仰せつかりました、ジェントル・タマネギンと申します♪"
	                   , "SPEAK %tamane% ？？？ 以後、お見知りおきのほどを…"
	                   , "LINES %avatar% ジェントル・タマネギン？"
	                   , "SPEAK %avatar% もじょ なんなのだ？お前は"
	                   , "LINES %tamane% ワタクシはタマネギン一族の中でも誉れ高いタマネ・タマネギン公爵の直系に当たる…"
	                   , "SPEAK %avatar% もじょ も・・も、もういいのだ。それよりここはどこなのだ？"
	                   , "LINES %tamane% この列車は異次元を走ってございます♪"
	                   , "LINES %tamane% わが主、闇の影様の世界の中に向かって驀進中なのでございます♪"
	                   , "LINES %avatar% 闇の影・・？とにかくもとの世界に戻しなさい！"
	                   , "LINES %tamane% それは誠に僭越ながらご承諾しかねるご相談でございます♪"
	                   , "SPEAK %avatar% もじょ ま、その闇の影ってのを倒すしかないのだ"
	                   , "LINES %tamane% ご理解ありがとうございます♪"
	                   , "LINES %tamane% では、イッツ・ショウタイム！"
	                 ]
	               , "chain_delayed": "changeflower1"
	             }
	          , "changeflower1": {
	                "type": "square_change"
	              , "change_pos": [7,14]
	              , "change_tip": 401
                  , "chain_delayed": "enemyspeak2"
	            }
	          , "enemyspeak2": {
	                 "type": "lead"
	               , "leads": [
	                     "LINES %tamane% これはお近づきの印でございます♪"
	                   , "LINES %tamane% 受け取ってくださいませ♪"
	                   , "LINES %avatar% は、花？"
	                 ]
	             }
            , "trap1": {
                    "trigger": "all"
                  , "pos": [7, 14]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                  , "chain": "enemyspeak3_1"
                }
            , "enemyspeak3_1": {
				            "type": "lead"
                  , "leads": [
	                     "LINES %avatar% し、しびれた・・"
	                   , "SPEAK %avatar% もじょ なんでうかつに近づくのだ・・"
                    ]
                  , "chain": "enemyspeak3_2"
                }
            , "enemyspeak3_2": {
				            "ignition": {"unit_exist":"tamane"}
                  , "type": "lead"
                  , "leads": [
	                     "LINES %tamane% 心づくしを受け取っていただき、幸いでございます♪"
	                   , "LINES %tamane% では、またお会いしましょう。アデュー♪"
                     , "UALGN %avatar% 2"
	                   , "LINES %avatar% あっ、待て、このぉ！"
                    ]
                  , "chain": "escapetamanegi"
                }
	          , "escapetamanegi": {
	                  "type": "property"
	                , "unit": "tamane"
				          , "change": {
						            "act_brain": "destine"
                      , "destine_pos": [11,13]
                  }
	              }
            , "enemyspeak4": {
                    "trigger": "unit_into"
                  , "unit_into": "tamane"
                  , "pos": [11, 13]
                  , "type": "lead"
                  , "leads": [
	                     "LINES %tamane% うっ・・"
	                   , "LINES %tamane% ・・・"
	                   , "SPEAK %avatar% もじょ ん？どしたのだ？"
	                   , "LINES %tamane% ひっ・・"
	                   , "LINES %avatar% ひ？"
	                   , "LINES %tamane% ひっ、ひでぶっ！"
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
	                     "LINES %avatar% な、なんだ？なんだ？？"
	                   , "SPEAK %avatar% もじょ なんか不条理な世界なのだ・・"
                    ]
                }
	          , "cage_key1": {
	                "trigger": "player"
	              , "pos": [4,14]
	              , "type": "lead"
                , "textsymbol": "sphere_cage_key"
	              , "leads": [
	                    "LINES %avatar% ん？何だコリャ？鍵だ"
	                  , "SPEAK %avatar% もじょ 多分あそこの扉の鍵なのだ"
	                  , "LINES %avatar% あ、ホントだ。開いた。"
	                ]
	              , "ornament": "twinkle"
                  , "chain_delayed": "opendoor1"
	            }
	          , "opendoor1": {
                  "type": "square_change"
	              , "change_pos": [3,12]
	              , "change_tip": 9904
	            }
	          , "closedoor1": {
	                "trigger": "player"
	              , "pos": [1,8], "rb": [5,11]
  				  , "type": "square_change"
	              , "change_pos": [3,12]
	              , "change_tip": 9905
                  , "chain_delayed": "speak1"
	            }
	          , "speak1": {
	                 "type": "lead"
                 , "textsymbol": "sphere_close_door"
	               , "leads": [
	                     "NOTIF 『ガチャン』"
	                   , "LINES %avatar% あ、閉まっちゃった"
	                   , "SPEAK %avatar% もじょ 閉じ込められたのだ・・"
	                 ]
	               , "chain": "enemy1.2"
	             }
	          , "enemy1.2": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [2, 8]
	                   , "character_id":-10023
	                   , "icon":"shadow"
                     , "code": "enemy1.2"
	                }
	               , "chain": "enemy1.3"
	             }
	          , "enemy1.3": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [4, 8]
	                   , "character_id":-10023
	                   , "icon":"shadow"
                     , "code": "enemy1.3"
	                 }
	               , "chain": "speak2"
	             }
            , "speak2": {
                    "type": "lead"
                  , "leads": [
	                     "LINES %avatar% あらら・・"
	                   , "SPEAK %avatar% もじょ こっちも精がでるのだ"
                    ]
                  , "chain": "tamane_exit"
                }
            , "tamane_exit": {
				            "ignition": {"unit_exist":"tamane"}
                  , "type": "unit_event"
                  , "target_unit": "tamane"
                  , "event": {
                          "name": "exit"
                        , "reason": "room_exit"
                    }
                }
	          , "cage_key2": {
	                "trigger": "player"
	              , "pos": [1,9]
	              , "type": "lead"
                , "textsymbol": "sphere_cage_key"
	              , "leads": [
	                    "LINES %avatar% ん？何だコリャ？鍵だ"
	                  , "SPEAK %avatar% もじょ 多分あそこの扉の鍵なのだ"
	                  , "LINES %avatar% あ、ホントだ。開いた。"
	                ]
	              , "ornament": "twinkle"
                , "chain_delayed": "opendoor2"
	            }
	          , "opendoor2": {
                    "type": "square_change"
	              , "change_pos": [3,7]
	              , "change_tip": 9904
	            }
	          , "closedoor2": {
	                "trigger": "player"
	              , "pos": [1,1], "rb": [5,6]
            , "type": "square_change"
	              , "change_pos": [3,7]
	              , "change_tip": 9905
                  , "chain_delayed": "speak3"
	            }
            , "speak3": {
                    "type": "lead"
                  , "textsymbol": "sphere_close_door_again"
                  , "leads": [
	                     "NOTIF 『ガチャン』"
	                   , "LINES %avatar% あ、また閉まっちゃった…"
                    ]
	               , "chain": "enemy1.4"
                }
	          , "enemy1.4": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [4, 1]
	                   , "character_id":-10023
	                   , "icon":"shadow"
                       , "code": "enemy1.4"
	                 }
	               , "chain": "enemy1.5"
	             }
	          , "enemy1.5": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [5, 1]
	                   , "character_id":-10024
	                   , "icon":"shadow"
                       , "code": "enemy1.5"
	                 }
	               , "chain": "speak2"
	             }
	          , "cage_key3": {
	                "trigger": "player"
	              , "pos": [1,1]
	              , "type": "lead"
                , "textsymbol": "sphere_cage_key"
	              , "leads": [
	                    "LINES %avatar% ん？何だコリャ？鍵だ"
	                  , "SPEAK %avatar% もじょ 多分あそこの扉の鍵なのだ"
	                  , "LINES %avatar% あ、ホントだ。開いた。"
	                ]
	              , "ornament": "twinkle"
                  , "chain_delayed": "opendoor3"
	            }
	          , "opendoor3": {
                    "type": "square_change"
	              , "change_pos": [6,3]
	              , "change_tip": 9904
	            }
              , "trap2": {
                    "trigger": "all"
                  , "pos": [1, 2]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                }
              , "treasure1": {
                    "type": "treasure"
                  , "trigger": "player"
                  , "pos":[2,1]
                  , "item_id": 1905
                  , "one_shot": 310060011
                }
              , "trap4": {
                    "trigger": "all"
                  , "pos": [1, 4]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                }
              , "trap5": {
                    "trigger": "all"
                  , "pos": [2, 4]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                }
              , "trap6": {
                    "trigger": "all"
                  , "pos": [4, 2]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                }
              , "trap7": {
                    "trigger": "all"
                  , "pos": [4, 4]
                  , "type": "trap"
                  , "lasting": 999
                  , "always": true
                }

	          , "closedoor3": {
	                "trigger": "player"
	              , "pos": [7,1], "rb": [13,6]
				  , "type": "square_change"
	              , "change_pos": [6,3]
	              , "change_tip": 9905
                  , "chain_delayed": "speak4"
	            }
              , "speak4": {
                    "type": "lead"
                  , "textsymbol": "sphere_close_door_again"
                  , "leads": [
	                     "NOTIF 『ガチャン』"
	                   , "LINES %avatar% あ、また閉まっちゃった…"
                    ]
	               , "chain": "enemy1.6"
                }
	          , "enemy1.6": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [12, 3]
	                   , "character_id":-10001
	                   , "icon":"shadow2"
                       , "code": "tamane2"
				       , "act_brain": "rest"
					   , "early_gimmick": "enemyspeak7"
	                 }
	               , "chain": "enemyspeak6"
	             }
              , "enemyspeak6": {
                    "type": "lead"
                  , "leads": [
	                     "LINES %tamane2% はぁい。まーたお会いしましたね。お嬢さん・・"
	                   , "LINES %avatar% まーたジェンタマ君？"
	                   , "LINES %tamane2% ジェントル・タマネギンです。略さないで呼んで下さい"
	                   , "SPEAK %avatar% もじょ で、そのジェンタマがまた何の用なのだ？"
	                   , "LINES %tamane2% 地獄へのご案内をと存じまして・・"
	                   , "LINES %avatar% そりゃご丁寧にどうも・・"
	                   , "LINES %tamane2% この部屋の隅をごらんください。マグマが漏れております"
	                   , "SPEAK %avatar% もじょ な、なんでそんなもんが漏れてるのだ・・"
	                   , "LINES %tamane2% ドンドン漏れております"
	                   , "LINES %tamane2% それはすぐに部屋いっぱいになってあなたを焼き尽くすことでしょう・・"
	                   , "LINES %tamane2% こんな風にね"
                    ]
                  , "chain": "escapetamanegi2"
                }
	           , "escapetamanegi2": {
	                  "type": "property"
                  , "unit": "tamane2"
				          , "change": {
						            "act_brain": "destine"
                      , "destine_pos": [12,1]
                  }
	             }
	          , "enemyspeak7": {
                    "type": "lead"
                  , "leads": [
	                     "LINES %tamane2% ほわちゃあーーー！！"
                    ]
                  , "chain_delayed": "speak5"
	            }
              , "speak5": {
                    "memory_shot": "magma_start"
                  , "type": "lead"
                  , "leads": [
	                     "LINES %avatar% あ、ちょっとなにやってんの！！"
	                   , "SPEAK %avatar% もじょ アホはほっといてとにかく早くあすこの鍵を取ってこの部屋を出るのだ"
                    ]
	               , "chain": "enemy1.7"
                }
	          , "enemy1.7": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [10, 2]
	                   , "character_id":-10024
	                   , "icon":"shadow"
                       , "code": "enemy1.7"
	                 }
	               , "chain": "enemy1.8"
	             }
	          , "enemy1.8": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [11, 3]
	                   , "character_id":-10024
	                   , "icon":"shadow"
                       , "code": "enemy1.8"
	                 }
	               , "chain": "enemy1.9"
	             }
	          , "enemy1.9": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [8, 6]
	                   , "character_id":-10024
	                   , "icon":"shadow"
                       , "code": "enemy1.9"
				       , "act_brain": "rest"
	                   , "brain_noattack": true
	                 }
	             }
	          , "cage_key4": {
	                "trigger": "player"
	              , "pos": [12,5]
                , "textsymbol": "sphere_cage_key2"
	              , "type": "lead"
	              , "leads": [
	                    "LINES %avatar% よし、鍵ゲット！"
	                  , "SPEAK %avatar% もじょ さ、早く出るのだ！"
	                ]
	              , "ornament": "twinkle"
                  , "chain_delayed": "opendoor4"
	            }
	          , "opendoor4": {
                    "type": "square_change"
	              , "change_pos": [8,7]
	              , "change_tip": 9904
                  , "chain": "enemy1.9"
	            }
	          , "closedoor4": {
	                "trigger": "player"
	              , "pos": [7,8], "rb": [12,11]
				  , "type": "square_change"
	              , "change_pos": [8,7]
	              , "change_tip": 9905
                  , "chain_delayed": "speak6"
	            }
              , "speak6": {
                    "type": "lead"
                  , "textsymbol": "sphere_close_door_again"
                  , "leads": [
	                     "NOTIF 『ガチャン』"
	                   , "LINES %avatar% あ、また閉まっちゃった…"
                    ]
                  , "chain": "enemy1.13"
                }
              , "firetrap1": {
                    "trigger": "all"
                  , "pos": [12, 1]
                  , "type": "trap2"
                  , "lasting": 999
                  , "always": true
                }

	          , "enemy1.13": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [14, 9]
	                   , "character_id":-10001
	                   , "icon":"shadow2"
                       , "code": "tamane3"
				       , "act_brain": "rest"
					   , "early_gimmick": "endspeak"
					   , "trigger_gimmick": "speak8"
	                 }
	               , "chain": "enemyspeak8"
	             }
              , "enemyspeak8": {
                    "type": "lead"
                  , "leads": [
	                     "LINES %tamane3% なんと、ここまでたどり着くとは・・"
	                   , "LINES %tamane3% スバラシイ・・イヤ、スバラシイ・・"
	                   , "LINES %avatar% また・・しつこい、って言うか何で生きてるの？"
	                   , "SPEAK %avatar% もじょ おいジェンタマ。いいかげんにするのだ！"
	                   , "LINES %tamane3% こうなったら機械の兵隊たちを呼び寄せましょう"
	                   , "LINES %tamane3% いでよマルティネーターども！"
                    ]
                  , "chain": "enemy1.10"
                }
	          , "enemy1.10": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [14, 8]
	                   , "character_id":-10025
	                   , "icon":"shadow"
                       , "code": "enemy1.10"
	                 }
	               , "chain": "enemy1.11"
	             }
	          , "enemy1.11": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [14, 10]
	                   , "character_id":-10025
	                   , "icon":"shadow"
                       , "code": "enemy1.11"
	                 }
	               , "chain": "enemyspeak9"
	             }
              , "enemyspeak9": {
                    "type": "lead"
                  , "leads": [
	                     "LINES %tamane3% こいつらはマルティーニの技術を結集した殺人マシーンです"
	                   , "LINES %tamane3% ワタクシ自らが改良し、どんな物でも殲滅してしまう爆弾を装備しました"
	                   , "LINES %tamane3% 一回しか使えないのが難点ですが貴方がたをこの世から消すには十分・・ンッフッフ・・"
	                   , "LINES %tamane3% さあこいつらをやっつけておしまいなさい！"
	                   , "LINES %enemy1.10% ピー、ガガガ・・"
	                   , "LINES %enemy1.11% ピー、ガガガ・・ピー"
	                   , "LINES %tamane3% ん？なんか様子が変でございますね・・"
	                   , "LINES %tamane3% ワタクシ自らが改良したのがまずかったのでしょうか・・？"
	                   , "LINES %enemy1.10% ピガーーーーー"
	                   , "LINES %enemy1.11% ピガガーーーーー"
	                   , "LINES %tamane3% あ、あれれ？？"
                    ]
	               , "chain": "tamane_death"
                }
	           , "tamane_death": {
	                 "type": "marutinator_fire"
	               , "target_unit": "tamane3"
	             }
	           , "endspeak": {
	                 "type": "lead"
	               , "leads": [
	                     "LINES %tamane3% おわたぁ"
	                 ]
	               , "chain": "change_enemy1.10"
	             }
              , "speak8": {
                    "type": "lead"
                  , "leads": [
	                     "SPEAK %avatar% もじょ 今度こそ成仏するのだ・・"
	                   , "LINES %avatar% もう出てくるなよー"
	                   , "LINES %avatar% ・・さて、この機械やっつけないとね・・"
                    ]
                }

              , "next_stage2": {
                    "trigger": "hero"
                  , "pos": [18, 10]
                  , "type": "goto"
                  , "room": "passage2"
                }
			}
        }
      , "passage2": {
            "id": 31006002
          , "battle_bg": "black"
          , "environment": "cave"
          , "start_pos": [4,5]
          , "bgm": "bgm_quest_horror"
          , "gimmicks": {

                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                      , "LINES %avatar% わ、真っ暗・・"
                      , "SPEAK %avatar% もじょ なんかいるのだ"
                      , "UALGN %avatar% 3"
                      , "LINES %avatar% あれが・・闇の影・・？"
                      , "LINES %avatar% ・・あなたは誰？"
                      , "LINES %yamikage% ・・私は闇の影・・"
                      , "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                      , "LINES %avatar% ここはどこなの？ボクたちを元の世界に返して！"
                      , "LINES %yamikage% ・・ここは異次元の世界"
                      , "SPEAK %avatar% もじょ 倒すしか無さそうなのだ"
                      , "LINES %yamikage% ・・それももう無駄だ・・"
                      , "LINES %yamikage% お前達はすでに元の世界にはいない"
                      , "LINES %avatar% ぐっ、こんなところで死んでたまるか"
                      , "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                    ]
                }
	           , "endstoryspeak": {
	                 "type": "lead"
	               , "leads": [
	                       "LINES %avatar% た、倒したけど・・"
                       , "LINES %avatar% ど、どうやってとめたらいいの？"
                       , "SPEAK %avatar% もじょ というより、この汽車もう異世界を走ってるのだ"
                       , "LINES %avatar% えー・・もう戻れないの？？"
                       , "SPEAK %avatar% もじょ 倒すのがちょっと遅かったのだ・・"
                       , "SPEAK %avatar% もじょ 闇の影の世界にとりこまれ・・"
                       , "LINES %avatar% もじょ！もじょ！！"
                       , "LINES %avatar% ・・う・・ボクもなんか息苦しくなってきた・・"
                       , "LINES %avatar% ・・もう・・駄目"
                       , "LINES %avatar% 助けて・・。誰か・・・。"
                       , "LINES %avatar% ・・・"
                       , "NOTIF ガタンゴトン・・・\nガタンゴトン・・・"
                       , "NOTIF ・・・\n"
                       , "NOTIF ククク・・\nようやく出会えたようだな・・"
                       , "NOTIF 捜し求めていたぞ・・\nお前を・・"
                       , "NOTIF ・・あなたは誰？\n"
                       , "NOTIF お前が呼んだのだろう？\n我を・・"
                       , "NOTIF 助けてくれ、とな\n"
                       , "NOTIF 我とお前の精神が共鳴したのだ\n"
                       , "NOTIF ククク・・・\n"
                       , "NOTIF ・・あなたは誰なの？\n"
                       , "NOTIF ・・我の名は・・\nレオンだ・・"
                       , "NOTIF ・・レオン・・？\n"
                       , "NOTIF そうだ。\nしかし・・まだ、生き残りが\nおったとはな・・！"
                       , "NOTIF 生き残りってなに？\n何のこと？"
                       , "NOTIF ・・すごい邪悪な気・・\n吸い込まれそうな・・"
                       , "NOTIF こんなところで死なれては困る\n"
                       , "NOTIF 我の力で元に戻してやろう\n"
                       , "NOTIF また、いつか我と会うことに\nなるだろうからな"
                       , "NOTIF それまでこれを預けておこう\n"
                       , "NOTIF 霊廟の鍵を手に入れた\n"
                       , "NOTIF さらばだ・・\n[NAME]"
                       , "LINES %avatar% ちょ、ちょっと待って！！\nあなたは・・"
                       , "LINES %avatar% ・・・"
	                 ]
	               , "chain": "goal"
	             }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }

              , "next_stage": {
                    "trigger": "hero"
                  , "pos": [1,0]
                  , "type": "goto"
                  , "room": "passage1"
                }
            }
          , "units": [
                {
                    "pos": [4,2]
                  , "character_id":-10050
                  , "icon":"shadow2"
                  , "code": "yamikage"
                  , "trigger_gimmick": "endstoryspeak"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
    }
}
