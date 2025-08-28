{
    "extra_uniticons": ["shisyou", "shadow", "shadow2"]
  , "extra_maptips": [2]
  , "rooms": {

        "start": {
            "id": 11007000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [7,9]
          , "gimmicks": {

                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %galuf% 1"
                      , "UALGN %avatar% 2"
                      , "SPEAK %galuf% 師匠 フォッフォッ1対1の勝負じゃ。どっからでもかかって来い"
                      , "LINES %avatar% よーし…。い、行くよー！"
                      , "SPEAK %avatar% もじょ ガンバレなのだ！もじょは激しく応援するのだ！"
                    ]
                }
              , "open_comment2": {
                    "condition": {"cleared":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %galuf% 1"
                      , "UALGN %avatar% 2"
                      , "SPEAK %galuf% 師匠 もう一度勝負などと調子づきおって…目にモノ見せてくれる！"
                    ]
                }

              , "derailment": {
                    "trigger": "hero"
                  , "pos": [1,6], "rb":[2,13]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %galuf% 師匠 フォッフォッ。ワシに恐れをなして逃げるとは、やはりヒヨッコよの"
                    ]
                  , "chain": "escape"
                }
              , "escape": {
                    "type": "escape"
                }

              , "finish": {
                    "type": "lead"
                  , "leads": [
                        "SPEAK %galuf% 師匠 なん…じゃと…。そ、そんなバカな…"
                    ]
                  , "chain": "success"
                }
              , "success": {
                    "type": "escape"
                  , "escape_result": "success"
                }

              , "first_summon_1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [2,5]
                      , "character_id":-1203
                      , "code": "stand1"
                    }
                  , "chain": "first_summon_2"
                }
              , "first_summon_2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4,4]
                      , "character_id":-1203
                    }
                  , "chain": "first_summon_3"
                }
              , "first_summon_3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8,3]
                      , "character_id":-1203
                    }
                  , "chain": "first_summon_4"
                }
              , "first_summon_4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,17]
                      , "character_id":-1303
                      , "items": [-2006, -2006]
                    }
                  , "chain": "first_summon_5"
                }
              , "first_summon_5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7,16]
                      , "character_id":-1303
                      , "items": [-2006, -2006]
                    }
                  , "chain": "first_summon_6"
                }
              , "first_summon_6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8,15]
                      , "character_id":-1303
                      , "items": [-2006, -2006]
                    }
                  , "chain": "after_summon"
                }
              , "after_summon": {
                    "type": "lead"
                  , "switch": [
                        {
                            "condition": {"cleared":false}
                          , "textsymbol": "sphere_11007_11007000_after_summon_1"
                          , "rem": [
                                "UALGN %avatar% 1"
                              , "LINES %avatar% え！？なに？シショーに従ってんの？どーなってんの？"
                              , "SPEAK %galuf% 師匠 言葉が通じずとも交渉を成し遂げるが真のびぢねすまん よ！"
                              , "SPEAK %galuf% 師匠 さあ行け、ものども！取り押さえんと報酬はゼロじゃぞ！！"
                              , "LINES %stand1% ウゲ～～～イ！"
                            ]
                        }
                      , {
                            "condition": {"cleared":true}
                          , "textsymbol": "sphere_11007_11007000_after_summon_2"
                          , "rem": [
                                "LINES %stand1% ウゲウゲ…！"
                            ]
                        }
                    ]
                }

              , "mojoh_anger": {
                    "trigger": "rotation"
                  , "rem": "rotationキーはロジックでセットする"
                  , "type": "lead"
                  , "switch": [
                        {
                            "condition": {"cleared":false}
                          , "textsymbol": "sphere_11007_11007000_mojoh_anger_1"
                          , "rem": [
                                "SPEAK %avatar% もじょ ジジイ！おめーの顔はもう見あきたのだ"
                              , "SPEAK %avatar% もじょ [NAME]、内緒でお前に精霊の力を貸してやるのだ。ジジィを消滅させるのだ！"
                            ]
                        }
                      , {
                            "condition": {"cleared":true}
                          , "textsymbol": "sphere_11007_11007000_mojoh_anger_2"
                          , "rem": [
                                "SPEAK %avatar% もじょ こりないジジィなのだ…もっぺんこれで吹き飛ばせなのだ"
                            ]
                        }
                    ]
                  , "chain": "proof_of_genius"
                }
              , "proof_of_genius": {
                    "type": "ace_card"
                  , "treasure_catcher": "avatar"
                  , "user_item_id": -3998
                  , "chain": "mojoh_supply"
                }
              , "mojoh_supply": {
                    "condition": {"cleared":false}
                  , "type": "call"
                  , "call": "processMojohSupply"
                }

              , "galuf_surprise": {
                    "condition": {"cleared":false}
                  , "ignition": {"unit_alive":"galuf"}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %galuf% 師匠 なんっじゃ！今のは！？"
                      , "SPEAK %galuf% 師匠 おい！もじょ！！お前なんかやったじゃろ！？"
                      , "SPEAK %avatar% もじょ はぁぁ～～～？知らんのぉぉ～～なのだ！"
                      , "SPEAK %galuf% 師匠 ぐぬ…このクソネコ…！精霊のくせに争いごとに加担するとは…"
                    ]
                }
              , "mojoh_surprise": {
                    "condition": {"cleared":false}
                  , "ignition": {"unit_alive":"galuf"}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 今のシショーに向けて撃って良かったのかなぁ・・"
                      , "SPEAK %avatar% もじょ あれをくらってもまだ生きてるとはさすがゴキブリなのだ"
                    ]
                }

              , "second_summon": {
                    "trigger": "rotation"
                  , "rem": "rotationキーはロジックでセットする"
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %galuf% 師匠 むむぅ……やりおるの。やむをえん追加投資の決定じゃ！！"
                      , "SPEAK %galuf% 師匠 出でよ！マイ・ガーディアンズ！！"
                    ]
                  , "chain": "second_summon_1"
                }
              , "second_summon_1": {
                    "type": "unit"
                  , "unit": {
                        "pos": [13,8]
                      , "character_id":-1401
                      , "code": "stand2"
                      , "items": [-1001]
                    }
                  , "chain": "second_summon_2"
                }
              , "second_summon_2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [13,10]
                      , "character_id":-1401
                      , "items": [-1001]
                    }
                  , "chain": "second_summon_3"
                }
              , "second_summon_3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4,4]
                      , "character_id":-1401
                      , "items": [-1001]
                    }
                  , "chain": "second_summon_4"
                }
              , "second_summon_4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4,18]
                      , "character_id":-1401
                      , "items": [-1001]
                    }
                  , "chain": "after_second"
                }
              , "after_second": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %galuf% 師匠 フォッフォッさあ行け！役立たずにはカネは出さんぞ！！"
                      , "LINES %stand2% ウゲウゲー！"
                    ]
                }

              , "set_out": {
                    "trigger": "rotation"
                  , "rem": "rotationキーはロジックでセットする"
                  , "type": "unit_event"
                  , "target_unit": "galuf"
                  , "event": {
                        "name": "x_set_out"
                    }
                }

              , "treasure1": {
                    "trigger": "player"
                  , "pos": [19,15]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "one_shot": 110070001
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos": [20,10]
                  , "type": "treasure"
                  , "item_id": 1002
                  , "one_shot": 110070002
                }
            }

          , "units": [
                {
                    "pos": [10,9]
                  , "character_id":-9902
                  , "icon":"shisyou"
                  , "code": "galuf"
                  , "unit_class": "11007Galuf"
                  , "brain_item_orient": true
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
    }
}
