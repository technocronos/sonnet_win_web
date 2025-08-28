{
    "extra_uniticons": ["shadow", "shadow2", "shadowG", "elena"]
  , "start_units": [
        {
            "condition": {"cleared":false}
          , "character_id": -20101
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
            "id": 21008000
          , "battle_bg": "dungeon3"
          , "environment": "grass"
          , "start_pos": [2,19]
          , "start_pos_on": {
                "kakure_zone": [0,9]
               ,"goto_end": [4,9]
            }

          , "gimmicks": {
                "goto": {
                    "trigger": "hero"
                  , "pos": [10,10]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
              , "open_comment": {
                    "condition": {"!has_memory":"start_pass"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "memory_on": "start_pass"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UALGN %elena% 3"
                      , "LINES %avatar% うわー・・あそこが火口か・・"
                      , "LINES %elena% うん・・あそこにサラマンダーがいるはずよ"
                      , "SPEAK %avatar% もじょ しかしあっちいのだ・・"
                      , "LINES %avatar% 今にも爆発しそうだね・・"
                      , "LINES %elena% あっちからぐるっとまわっていけそうね"
                    ]
                }
  	          , "enemy1.1": {
  	                "trigger": "player"
  	              , "pos":[0,12], "rb":[6,17]
  	              , "type": "unit"
  	              , "unit": {
  	                     "pos": [9, 11]
  	                   , "character_id":-10060
  	                   , "icon":"shadow"
  	                 }
  	               , "chain": "enemy1.2"
  	             }
  	          , "enemy1.2": {
  	                "type": "unit"
  	              , "unit": {
  	                     "pos": [9, 12]
  	                   , "character_id":-10060
  	                   , "icon":"shadow"
  	                 }
  	               , "chain": "enemy1.3"
  	             }
  	          , "enemy1.3": {
  	                "type": "unit"
  	              , "unit": {
  	                     "pos": [10, 11]
  	                   , "character_id":-10060
  	                   , "icon":"shadow"
  	                 }
  	             }
  	          , "saram_speak": {
                    "condition": {"cleared":false}
                  , "type": "lead"
        				  , "trigger": "hero"
        				  , "pos": [0, 8], "rb":[4,9]
  	              , "leads": [
                         "LINES %avatar% あっ、火山に花が咲いてる・・"
  	                   , "SPEAK %avatar% もじょ あれが夢の実なのだ・・？"
                       , "UALGN %avatar% 3"
                       , "UALGN %elena% 3"
  	                   , "LINES %saram% 我の住処になんの用だ・・？"
  	                   , "LINES %saram% その実は我のうろこのかけらから生えたものだ。"
  	                   , "LINES %saram% 人間などにこの火山からたとえ小石一つであろうとも持って帰ることは許さん！！"
  	                   , "LINES %saram% う、ううっ・・"
  	                   , "SPEAK %avatar% もじょ ど・・どうしたのだ？"
  	                   , "LINES %elena% 私はエルフです！サラマンダー。火山が爆発しそうなのですがこれは一体・・。"
  	                   , "LINES %saram% エルフだと？珍しいな。"
  	                   , "LINES %saram% 何者かが我の力を抑えているようなのじゃ。"
  	                   , "LINES %saram% 何かがこの火山に入り込んだらしい"
  	                   , "LINES %saram% この火山のエネルギーは凄まじい。"
  	                   , "LINES %saram% 我が食うのをやめればあっというまにこの通りじゃ。"
  	                   , "LINES %avatar% そんな・・。"
  	                   , "LINES %avatar% トロルの里は目の前なのに・・。"
  	                   , "LINES %saram% ほう・・お前からは懐かしい臭いがするのう"
  	                   , "LINES %saram% トロルか・・。なるほど・・それでここを通ろうとしていたわけじゃな？"
  	                   , "LINES %avatar% はい。"
  	                   , "LINES %saram% では我の力を抑えている虫を退治してきてくれい。"
  	                   , "LINES %saram% できたらここは通してやろう。"
  	                 ]
                    , "chain": "saram_move"
  	            }
  	          , "saram_speak3": {
                    "condition": {"cleared":true}
  	              , "type": "lead"
        				  , "trigger": "hero"
        				  , "pos": [0, 8], "rb":[4,9]
  	              , "leads": [
  	                     "LINES %saram% お前たちか。通るがよい。"
  	                 ]
                    , "chain": "saram_move"
  	            }
              , "saram_move": {
                     "type": "unit_move"
                   , "chain": "square_change1"
                }
              , "square_change1": {
                    "type": "square_change"
                  , "change_pos": [4, 7]
                  , "change_tip": 1850
                  , "chain": "square_change2"
                }
   	          , "square_change2": {
           					"type": "square_change"
           	      , "change_pos": [4, 6]
           	      , "change_tip": 1850
   	            }
  	          , "yumenomi_speak": {
                    "trigger": "hero"
        				  , "pos": [5, 7]
  	              , "leads": [
  	                     "LINES %avatar% あ、これが夢の実・・？"
  	                   , "SPEAK %avatar% もじょ 持って帰っちゃだめなのだ。"
  	                 ]
  	            }
              , "tanpopo1": {
                    "pos": [3, 8]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [5, 8]
                  , "ornament": "tanpopo"
                }
              , "goal": {
                    "trigger": "hero"
                  , "pos": [4,6]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "goto"
                }
            }
          , "units": [
                {
                    "character_id": -10103
                  , "icon":"shadowG"
                  , "pos": [4,7]
                  , "code": "saram"
                  , "unit_class": "21008Saram"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
      , "floor2": {
            "id": 21008001
          , "battle_bg": "dungeon3"
          , "environment": "grass"
          , "start_pos": [0,11]
          , "gimmicks": {

                "goto": {
                    "trigger": "hero"
                  , "pos": [2,0]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor3"
                }
              , "goto2": {
                    "trigger": "hero"
                  , "pos": [3,0]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor3"
                }
            }
          , "units": [
                {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [4,9]
                  , "code": "enemy1.1"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [7,6]
                  , "code": "enemy1.2"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [3,1]
                  , "code": "enemy1.3"
                }

            ]
        }
      , "floor3": {
            "id": 21008002
          , "battle_bg": "dungeon3"
          , "environment": "grass"
          , "start_pos": [13,10]
          , "gimmicks": {

               "goto": {
                    "trigger": "hero"
                  , "pos": [0,7]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor4"
                }
            }
          , "units": [
                {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [9,5]
                  , "code": "enemy1.1"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [6,3]
                  , "code": "enemy1.2"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [7,3]
                  , "code": "enemy1.3"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [1,7]
                  , "code": "enemy1.4"
                }

            ]

        }
      , "floor4": {
            "id": 21008003
          , "battle_bg": "dungeon3"
          , "environment": "grass"
          , "start_pos": [10,2]
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
	          , "enemy4.4": {
                    "trigger": "unit_exit"
                  , "unit_exit": "enemy4.3"
	              , "type": "unit"
	              , "unit": {
	                     "pos": [4, 14]
	                   , "character_id":-10060
	                   , "icon":"shadow"
	                 }
	               , "chain": "enemy4.5"
	             }
	          , "enemy4.5": {
	                "type": "unit"
	              , "unit": {
	                     "pos": [5, 14]
	                   , "character_id":-10060
	                   , "icon":"shadow"
	                 }
	               , "chain": "speak4.1"
	             }
  		      , "speak4.1": {
  		            "type": "lead"
  		          , "leads": [
  		                "LINES %avatar% げげっ！！"
  		              , "SPEAK %avatar% もじょ まだいるのだ・・"
  		            ]
  		        }
              , "kakure_zone": {
                    "trigger": "hero"
                  , "pos": [10,17]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "start"
                }
            }
          , "units": [
                {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [5,5]
                  , "code": "enemy4.1"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [5,6]
                  , "code": "enemy4.2"
                }
             ,  {
                    "character_id": -10060
                  , "icon":"shadow"
                  , "pos": [4,10]
                  , "code": "enemy4.3"
                }
            ]
        }
    }
}
