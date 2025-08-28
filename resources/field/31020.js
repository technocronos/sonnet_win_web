{
    "extra_uniticons": ["servant"]
  , "rooms": {
        "start": {
            "id": 31020000
          , "battle_bg": "dungeon"
          , "environment": "rain"
          , "bgm": "bgm_home"
          , "start_pos": [8,6]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ここがマルティーニ城か・・"
                      , "LINES %avatar% 機械都市ってカンジ・・空気が悪いなあ・・"
                    ]
                }
              , "servant1_speak": {
                    "trigger": "hero"
                  , "pos": [7,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %servant1%"
                      , "SPEAK %servant1% 憲兵 ここは由緒正しきマルティーニ王国だ"
                      , "SPEAK %servant1% 憲兵 この国の市民証を持っていない人間は城下町の中には入れんぞ"
                      , "SPEAK %servant1% 憲兵 分かったらさっさとくににかえるんだな！"
                    ]
                }
              , "servant2_speak": {
                    "trigger": "hero"
                  , "pos": [9,4]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %servant2%"
                      , "SPEAK %servant2% 憲兵 ここは由緒正しきマルティーニ王国だ"
                      , "SPEAK %servant2% 憲兵 この国の市民証を持っていない人間は城下町の中には入れんぞ"
                      , "SPEAK %servant2% 憲兵 分かったらさっさとくににかえるんだな！"
                    ]
                }

              , "escape": {
                    "trigger": "hero"
                  , "pos": [7,7], "rb": [9,7]
                  , "type": "escape"
                  , "escape_result": "success"
                  , "ornament": "goto"
                }

            }
          , "units": [
                {
                    "character_id": -10052
                  , "code": "servant1"
                  , "icon":"servant"
                  , "pos": [7,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -10052
                  , "code": "servant2"
                  , "pos": [9,3]
                  , "icon":"servant"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
