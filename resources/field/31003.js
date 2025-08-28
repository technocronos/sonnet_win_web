{
    "extra_uniticons": ["shadow", "shadow2"]

  , "rooms": {
        "start": {
            "id": 31003000
          , "battle_bg": "desert"
          , "environment": "cave"
          , "start_pos": [12,13]
          , "gimmicks": {

                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわぁいるいる…"
                      , "SPEAK %avatar% もじょ 結構一杯いるのだ\nこれ全部片付けるのだ？"
                      , "LINES %avatar% や、やるしかないでしょ"
                      , "SPEAK %avatar% もじょ なんか近づくだけで\n爆発しそうなのだ"
                    ]
                }
              , "finish": {
                    "trigger": "termination"
                  , "termination": 26
                  , "type": "escape"
                  , "escape_result": "success"
                  , "touch": "finish_comment"
                }
              , "finish_comment": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ハァハァ…こ、これで全部…？"
                      , "SPEAK %avatar% もじょ ドッカンドッカン\nよく爆発したのだ…"
                      , "SPEAK %avatar% もじょ 終わったらアベジに報告に行くのだ"
                      , "LINES %avatar% よし！ポートモールに戻ろう！"
                    ]
                }

              , "enemy1-1": {
                    "trigger": "rotation"
                  , "rotation": 4
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [2,1]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy1-2"
                }
              , "enemy1-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [6,1]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy1-3"
                }
              , "enemy1-3": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [4,15]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy1-4"
                }
              , "enemy1-4": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [18,15]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy1-appear"
                }
              , "enemy1-appear": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 新手！？"
                      , "SPEAK %avatar% もじょ これはまだまだいそうなのだ"
                    ]
                }

              , "enemy2-1": {
                    "trigger": "rotation"
                  , "rotation": 7
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [18,6]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy2-2"
                }
              , "enemy2-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [17,14]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy2-3"
                }
              , "enemy2-3": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [6,1]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy2-4"
                }
              , "enemy2-4": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [4,10]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy2-5"
                }
              , "enemy2-5": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [11,4]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy2-appear"
                }
              , "enemy2-appear": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ おかわりが来たのだ"
                      , "LINES %avatar% 頼んでないよ～"
                    ]
                }

              , "enemy3-1": {
                    "trigger": "rotation"
                  , "rotation": 8
                  , "type": "unit"
                  , "unit": {
                        "character_id": -6001
                      , "union": 3
                      , "code": "subjugator"
                      , "pos": [8,15]
                      , "hp": 230
                    }
                  , "chain": "enemy3-appear"
                }
              , "enemy3-appear": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ん？あれは…\nマルティーニの討伐隊の\n生き残り？"
                      , "SPEAK %avatar% もじょ でも、敵と味方を区別してる\n顔じゃないのだ"
                    ]
                }
              , "treasure1": {
                    "one_shot": 310030001
                  , "trigger": "unit_exit"
                  , "unit_exit": "subjugator"
                  , "ignition": {"igniter":"avatar"}
                  , "type": "treasure"
                  , "item_id": 12016
                }

              , "enemy4-1": {
                    "trigger": "rotation"
                  , "rotation": 10
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [2,1]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy4-2"
                }
              , "enemy4-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [8,3]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy4-3"
                }
              , "enemy4-3": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [4,10]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy4-appear"
                }
              , "enemy4-appear": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ またおかわりなのだ"
                      , "LINES %avatar% サービスいいなぁ"
                    ]
                }

              , "enemy5-1": {
                    "trigger": "rotation"
                  , "rotation": 11
                  , "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [18,3]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-2"
                }
              , "enemy5-2": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [18,10]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-3"
                }
              , "enemy5-3": {
                    "rem": "これやっぱナシ"
                  , "chain": "enemy5-4"
                }
              , "enemy5-4": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [12,15]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-5"
                }
              , "enemy5-5": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [8,15]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-6"
                }
              , "enemy5-6": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [4,15]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-7"
                }
              , "enemy5-7": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -10055
                      , "icon": "shadow2"
                      , "pos": [2,1]
                      , "unit_class": "Blower"
                      , "brain_noattack": true
                      , "icon": "shadow2"
                    }
                  , "chain": "enemy5-appear"
                }
              , "enemy5-appear": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% もうムリおなかいっぱい"
                      , "SPEAK %avatar% もじょ たぶん最後なのだ\nなんとか全部食えなのだ"
                    ]
                }
            }
          , "units": [
                {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [8,8]
                  , "unit_class": "Blower"
                  , "act_brain": "excurse"
                  , "excurse_path": "666666222444444888"
                  , "excurse_step": 2
                  , "brain_noattack": true
                }
              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [8,12]
                  , "unit_class": "Blower"
                  , "act_brain": "excurse"
                  , "excurse_path": "22266668884444"
                  , "excurse_step": 2
                  , "brain_noattack": true
                }
              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [6,15]
                  , "unit_class": "Blower"
                  , "act_brain": "excurse"
                  , "excurse_path": "66662224444888"
                  , "excurse_step": 2
                  , "brain_noattack": true
                }
              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [15,14]
                  , "unit_class": "Blower"
                  , "act_brain": "excurse"
                  , "excurse_path": "866262224244848886"
                  , "excurse_step": 2
                  , "brain_noattack": true
                }

              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [3,7]
                  , "unit_class": "Blower"
                  , "brain_noattack": true
                  , "icon": "shadow2"
                }
              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [2,1]
                  , "unit_class": "Blower"
                  , "brain_noattack": true
                  , "icon": "shadow2"
                }
              , {
                    "character_id": -10055
                  , "icon": "shadow2"
                  , "pos": [17,5]
                  , "unit_class": "Blower"
                  , "brain_noattack": true
                  , "icon": "shadow2"
                }
            ]
        }
    }
}
