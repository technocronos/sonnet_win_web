{
    "extra_uniticons": ["shadow"]
  , "extra_maptips": [2010,2011,2012,2013,2014,2015]
  , "rooms": {
        "start": {
            "id": 21010000
          , "battle_bg": "forest"
          , "environment": "rain"
          , "start_pos": [3,9]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% エレナ シルフが言うには確かトロルが100年戦争時に済んでいた集落はここら辺・・"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %avatar% もじょ しかし薄気味悪い森なのだ・・"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %avatar% もじょ どうしたのだ？"
                      , "LINES %avatar% ・・うん、間違いないと思う。"
                      , "SPEAK %avatar% もじょ わかるのだ！？"
                      , "LINES %avatar% なんとなくね・・。"
                    ]
                }
              , "escape_speak": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [5,0], "rb":[6,0]
                  , "type": "lead"
                  , "ornament": "goto"
                  , "leads": [
                        "SPEAK %avatar% エレナ あっ、森を抜けたわ。"
                      , "SPEAK %avatar% エレナ ここがトロルの里よ。間違いないわ。"
                    ]
                  , "chain": "goto_next"
                }
              , "tanpopo1": {
                    "pos": [4, 3]
                  , "ornament": "tanpopo"
                }
              , "tanpopo2": {
                    "pos": [4, 8]
                  , "ornament": "tanpopo"
                }
              , "goto_next": {
                    "type": "goto"
                  , "room": "floor1"
                }
              , "goto_next2": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [5,0], "rb":[6,0]
                  , "room": "floor1"
                  , "type": "goto"
                  , "ornament": "goto"
                }
            }
        }
        , "floor1": {
            "id": 21010001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "bgm": "bgm_wasteland"
          , "start_pos": [8,15]
          , "gimmicks": {
              "open_comment": {
                  "trigger": "rotation"
                , "rotation": 1
                , "condition": {"cleared":false}
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "LINES %avatar% ここが・・トロルの・・なんてこと・・すべてが廃墟・・"
                    , "SPEAK %avatar% エレナ ・・・なんか悲しいわね・・。"
                    , "LINES %avatar% でも立派な文明だったんだろうなぁ・・ものすごい立派な遺跡・・"
                    , "SPEAK %avatar% もじょ ちょっとうろうろしてみるのだ"
                  ]
              }
            , "house_in_TR": {
                  "trigger": "hero"
                , "pos": [10,3], "rb":[14,6]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% もじょ なーんもないのだ・・"
                    , "LINES %avatar% んー・・"
                  ]
              }
            , "ornament_tr": {
                    "trigger": "player"
                  , "pos":[12, 5]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "one_shot": 210100011
                  , "ornament": "twinkle"
                }
            , "house_in_BR": {
                  "trigger": "hero"
                , "pos": [10,10], "rb":[14,13]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% もじょ なーんもないのだ・・"
                    , "LINES %avatar% んー・・"
                  ]
              }
              , "ornament_br": {
                    "trigger": "player"
                  , "pos":[12, 11]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "one_shot": 210100012
                  , "ornament": "twinkle"
                }
            , "house_in_BL": {
                  "trigger": "hero"
                , "pos": [3,10], "rb":[7,12]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% もじょ なーんもないのだ・・"
                    , "LINES %avatar% んー・・"
                  ]
              }
            , "ornament_bl": {
                    "trigger": "player"
                  , "pos":[4, 11]
                  , "type": "treasure"
                  , "item_id": 13020
                  , "one_shot": 210100013
                  , "ornament": "twinkle"
                }
            , "house_in_TL": {
                  "trigger": "hero"
                , "pos": [2,3], "rb":[7,6]
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "LINES %avatar% ん？なんかあすこに石版があるね"
                    , "SPEAK %avatar% もじょ どうせ何書いてあるのかわかるわけないのだ"
                    , "LINES %avatar% ん・・"
                  ]
              }
            , "write_speak": {
                  "trigger": "hero"
                , "pos": [3,4], "rb":[5,5]
                , "condition": {"cleared":false}
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "LINES %avatar% なんて書いてあるのかなぁ・・"
                    , "SPEAK %avatar% エレナ これは古代トロル文字ね・・誰も読める人はいないんじゃないかしら"
                    , "SPEAK %avatar% もじょ 古い日記なのだ。"
                    , "LINES %avatar% 『私のかわいいレオン・・ブラックトロルの王・・』"
                    , "SPEAK %avatar% もじょ よ、読めるのだ！？"
                    , "LINES %avatar% 『悪魔の人間に魂を売った・・』"
                    , "LINES %avatar% レオン・・レオンってどこかで・・"
                    , "SPEAK %avatar% エレナ あら。知らないの？"
                    , "LINES %avatar% え？エレナしってるの？？"
                    , "SPEAK %avatar% エレナ レオンハルト王のことでしょ？"
                    , "SPEAK %avatar% エレナ マルティーニ王と対決して首をはねられたブラックトロル最後の王よ。"
                    , "LINES %avatar% え、そうだったんだ・・。"
                    , "SPEAK %avatar% もじょ じょじょに核心に近づいてるのだ・・"
                    , "LINES %avatar% これ以上はわかんないかな・・"
                  ]
              }
            , "write_speak2": {
                  "trigger": "hero"
                , "pos": [3,4], "rb":[5,5]
                , "condition": {"cleared":true}
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "LINES %avatar% なんて書いてあるのかなぁ・・"
                    , "SPEAK %avatar% もじょ 古い日記なのだ。"
                    , "LINES %avatar% 『私のかわいいレオン・・ブラックトロルの王・・』"
                    , "LINES %avatar% 『悪魔の人間に魂を売った・・』"
                    , "SPEAK %avatar% もじょ よ、読めるのだ！？"
                    , "LINES %avatar% これ以上はわかんないかな・・"
                  ]
              }
            , "goto_next": {
                    "trigger": "hero"
                  , "pos": [8,0]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
            }
        }
        , "floor2": {
            "id": 21010002
          , "battle_bg": "forest"
          , "environment": "grass"
          , "bgm": "bgm_wasteland"
          , "start_pos": [8,8]
          , "gimmicks": {
              "open_comment": {
                  "trigger": "rotation"
                , "condition": {"cleared":false}
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "UALGN %avatar% 3"
                    , "SPEAK %avatar% エレナ ここは・・墓場？？墓石がこんなにいっぱい・・"
                    , "SPEAK %avatar% エレナ これも、これも・・ここから見えるすべてがトロルの墓・・？"
                    , "LINES %avatar% 300年前にすべて死んだんだとしたら・・墓なんていったいだれが・・"
                    , "SPEAK %avatar% もじょ むこうにでっかい建物があるのだ"
                    , "LINES %avatar% あの前まで行ってみよう"
                  ]
              }
            , "open_comment2": {
                  "trigger": "rotation"
                , "condition": {"cleared":true}
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% ここは・・墓場？？墓石がこんなにいっぱい・・"
                    , "LINES %avatar% これも、これも・・ここから見えるすべてがトロルの墓・・？"
                    , "LINES %avatar% 300年前にすべて死んだんだとしたら・・墓なんていったいだれが・・"
                    , "SPEAK %avatar% もじょ むこうにでっかい建物があるのだ"
                    , "LINES %avatar% あの前まで行ってみよう"
                  ]
              }
            , "house_in": {
                  "trigger": "hero"
                , "condition": {"cleared":false}
                , "pos": [7,3], "rb":[9,3]
                , "type": "lead"
                , "leads": [
                      "SPEAK %avatar% エレナ 立派な霊廟ね。"
                    , "SPEAK %avatar% もじょ でも入り口には鍵がかかっているのだ"
                    , "LINES %avatar% ひょっとしたら・・この鍵・・？"
                    , "SPEAK %avatar% エレナ あら？その鍵は？"
                    , "LINES %avatar% 鍵穴がぴったり・・"
                  ]
                , "chain" : "open_door1"
              }
            , "house_in2": {
                  "trigger": "hero"
                , "condition": {"cleared":true}
                , "pos": [7,3], "rb":[9,3]
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% ひょっとしたら・・この鍵・・？"
                    , "LINES %avatar% 鍵穴がぴったり・・"
                  ]
                , "chain" : "open_door1"
              }
            , "open_door1": {
                    "type": "square_change"
                  , "change_pos": [7,1]
                  , "change_tip": 2010
                  , "chain" : "open_door2"
              }
            , "open_door2": {
                    "type": "square_change"
                  , "change_pos": [8,1]
                  , "change_tip": 2011
                  , "chain" : "open_door3"
              }
            , "open_door3": {
                    "type": "square_change"
                  , "change_pos": [9,1]
                  , "change_tip": 2012
                  , "chain" : "open_door4"
              }
            , "open_door4": {
                    "type": "square_change"
                  , "change_pos": [7,2]
                  , "change_tip": 2013
                  , "chain" : "open_door5"
              }
            , "open_door5": {
                    "type": "square_change"
                  , "change_pos": [8,2]
                  , "change_tip": 2014
                  , "chain" : "open_door6"
              }
            , "open_door6": {
                    "type": "square_change"
                  , "change_pos": [9,2]
                  , "change_tip": 2015
                  , "chain" : "after_open"
              }
            , "after_open": {
                  "type": "lead"
                , "leads": [
                      "NOTIF ガチャン！"
                    , "LINES %avatar% 開いた・・。"
                    , "SPEAK %avatar% もじょ どこでそんなものを手に入れたのだ・・？"
                    , "LINES %avatar% いや・・夢の中で・・"
                    , "SPEAK %avatar% もじょ 夢の中って何を言ってるのだ？"
                    , "LINES %avatar% そういえばレオンって・・確か・・"
                    , "SPEAK %avatar% もじょ とりあえず中入ってみるのだ？"
                    , "LINES %avatar% うん・・行ってみよう！"
                  ]
                  , "chain": "goal"
              }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
        }
    }
}
