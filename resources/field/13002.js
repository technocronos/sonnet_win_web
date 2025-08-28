{
    "rem": "フラグ 130020298 は狼をトラップに巻き込んだ数として使っているので注意"
  , "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 13002000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [0,4]
          , "start_pos_on": {
                "goto_start_from_area1": [11,1]
              , "goto_start_from_area2": [11,6]
            }
          , "gimmicks": {

                "goto_area1_from_start": {
                    "trigger": "hero"
                  , "pos": [12,1]
                  , "type": "goto"
                  , "room": "area1"
                  , "ornament": "goto"
                }
              , "goto_area2_from_start": {
                    "trigger": "hero"
                  , "pos": [12,6]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto"
                }
              , "shortcut_from_start": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [0,3]
                  , "type": "goto"
                  , "room": "area2"
                  , "ornament": "goto2"
                }
               , "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "memory_shot": "open_comment"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 狼の森はこの川の向こうって言ってたね"
                      , "SPEAK %avatar% もじょ ほんと調子乗りなのだ。西の森の洞窟でなんもこりてないのだ"
                      , "LINES %avatar% そんなことないよ。今回は草刈り…じゃなくて"
                      , "LINES %avatar% 息子さん助けないとなんだから。狼もほっとけないしね"
                    ]
                }
              , "find_vanguard": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 2
                  , "memory_shot": "find_vanguard"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% いたいた…"
                      , "SPEAK %avatar% もじょ 向こうはまだ気づいてないのだ。でも近づいたら気づくのだ"
                    ]
                }
              , "treasure0-1": {
                    "trigger": "player"
                  , "pos": [8,1]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 1300200001
                }
              , "treasure0-2": {
                    "trigger": "player"
                  , "pos": [11,7]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 1300200002
                }
              , "mission": {
                    "condition": {"mission":true, "reason":"start"}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ミッション\n(達成:+300マグナ)"
                      , "NOTIF 一番広いエリアで\n狼６体を倒して\n奥の脱出口から脱出"
                    ]
                }
            }
          , "units": [
                {
                    "pos": [10,4]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
            ]
        }

      , "area1": {
            "id": 13002001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [1,5]
          , "gimmicks": {
                "goto_start_from_area1": {
                    "trigger": "hero"
                  , "pos": [0,5]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }

              , "red_warn": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "memory_shot": "red_warn"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あんなとこにもいる…"
                      , "SPEAK %avatar% もじょ …あいつちょっと強そうなのだ。気をつけるのだ"
                      , "SPEAK %avatar% もじょ たぶん当たるとやられるのだ"
                    ]
                }

              , "treasure1": {
                    "trigger": "player"
                  , "pos": [2,1]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 130020101
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos": [8,5]
                  , "type": "treasure"
                  , "item_id": 14008
                  , "ornament": "twinkle"
                  , "one_shot": 130020102
                }
              , "treasure3": {
                    "trigger": "player"
                  , "pos": [5,1]
                  , "type": "treasure"
                  , "item_id": 11010
                  , "ornament": "twinkle"
                  , "one_shot": 130020103
                }
              , "treasure4": {
                    "trigger": "unit_exit"
                  , "unit_exit": "boss"
                  , "type": "treasure"
                  , "item_id": 2004
                  , "one_shot": 130020105
                  , "rem": "フラグ 130020104 は前のアイテムのときに使っていた"
                }
            }
          , "units": [
                {
                    "pos": [5,1]
                  , "code": "boss"
                  , "character_id":-2201
                  , "icon":"shadow2"
                  , "items": [-2004, -2004, -2004, -2004, -2004, -2004]
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }

      , "area2": {
            "id": 13002002
          , "battle_bg": "forest"
          , "start_pos": [1,2]
          , "environment": "grass"
          , "gimmicks": {
                "goto_start_from_area2": {
                    "trigger": "hero"
                  , "pos": [0,2]
                  , "type": "goto"
                  , "room": "start"
                  , "ornament": "goto"
                }
              , "area2_open": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "memory_shot": "area2_open"
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわぁ、いるいる…"
                      , "LINES %avatar% ここの狼を全部片付けたら牛さんも平和になりそうだね"
                      , "SPEAK %avatar% もじょ そんなことができればなのだ"
                    ]
                }
              , "area2_open_reverse": {
                    "rem": "このギミックで思考ルーチンチェンジを行っている"
                  , "condition": {"cleared":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわぁ、いるい…"
                      , "LINES %avatar% あ、あれ？なんかみんなこっちに気づいてる？"
                      , "SPEAK %avatar% もじょ ま、前と違うのだ！？"
                    ]
                }

              , "rescue1": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [1,3]
                  , "ornament": "curious"
                  , "one_shot": 130020299
                  , "type": "drama"
                  , "drama_id": 1300201
                }
              , "rescue2": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [1,3]
                  , "ornament": "curious"
                  , "memory_shot": "rescue"
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% 農夫の息子 あの草がなくなってるところに網があるんだ"
                      , "PFOCS 13 07"
                      , "DELAY 1000"
                      , "SPEAK %avatar% 農夫の息子 この小石をあそこに投げ込んだら、僕が罠を作動させるからね"
                      , "SPEAK %avatar% 農夫の息子 なるべくたくさんの狼をあそこに誘い込んで小石を投げ込むんだ"
                      , "SPEAK %avatar% 農夫の息子 自分もかからないように気をつけてね"
                      , "SPEAK %avatar% 農夫の息子 かからなかったやつは頼むよ"
                    ]
                  , "chain": "get_signal"
                }
              , "get_signal": {
                    "condition": {"cleared":false}
                  , "type": "ace_card"
                  , "user_item_id": -2003
                }

              , "bravo": {
                    "rem": "条件次第で、ロジックで台詞の書き換えを行っている"
                  , "condition": {"cleared":false}
                  , "trigger": "termination"
                  , "termination": 6
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% 農夫の息子 やったね！全部しとめたよ！！"
                      , "SPEAK %avatar% 農夫の息子 とりあえず牧場に戻ろうか"
                    ]
                  , "chain": "goal"
                }
              , "goal": {
                    "condition": {"cleared":false}
                  , "type": "goal"
                }

              , "extra_helper": {
                    "condition": {"cleared":true}
                  , "trigger": "rotation"
                  , "rotation": 4
                  , "type": "unit"
                  , "unit": {
                        "pos": [1,2]
                      , "character_id":-2201
                      , "icon":"shadow2"
                      , "items": [-2004, -2004, -2004, -2004, -2004, -2004]
                    }
                  , "chain": "extra_surprise"
                }
              , "extra_surprise": {
                    "condition": {"cleared":true}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% でたーー！"
                      , "SPEAK %avatar% もじょ た、大将こっち来ちゃったのだ！！"
                    ]
                }

              , "mission_countdown": {
                    "condition": {"mission":true}
                  , "trigger": "termination"
                  , "termination": "*"
                  , "type": "x_countdown"
                  , "lasting": 9999
                }
            }
          , "units": [
                {
                    "pos": [1,11]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
              , {
                    "pos": [6,1]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
              , {
                    "pos": [6,12]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
              , {
                    "pos": [9,3]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
              , {
                    "pos": [14,2]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
              , {
                    "pos": [14,10]
                  , "character_id":-2101
                  , "icon":"shadow"
                }
            ]
        }
    }
}
