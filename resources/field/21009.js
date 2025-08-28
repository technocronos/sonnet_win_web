{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "shadowG"]
  , "rooms": {
        "start": {
            "id": 21009000
          , "battle_bg": "dungeon3"
          , "environment": "cave"
          , "start_pos": [1,6]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわ・・あっつ・・"
                      , "SPEAK %avatar% もじょ たしかに虫がいるのだ・・"
                      , "LINES %avatar% 今回は狭いからエレナを連れてこれなかったけど"
                      , "LINES %avatar% うじゃうじゃいるなぁ・・"
                    ]
                }
              , "next_comment": {
                    "trigger": "rotation"
                  , "rotation": 2
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 赤い蟲がマグマを吐いてる・・"
                      , "SPEAK %avatar% もじょ 腐ったマグマを吐き出してるのだ・・"
                      , "LINES %avatar% サラマンダーさん、あれを食べてお腹を壊したんだね"
                      , "SPEAK %avatar% もじょ 青い蟲もいるのだ何かマグマを食べてるのだ"
                      , "LINES %avatar% あの子にうまいこと食べてもらわないと先進めないね"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [8,4]
                  , "type": "goto"
                  , "room": "floor1"
                }
            }
          , "units": [
                {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [4,5]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [7,6]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [9,2]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [2,2]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [5,2]
                  , "unit_class": "21009BlueWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [9,4]
                  , "unit_class": "21009BlueWarm"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "floor1": {
            "id": 21009001
          , "battle_bg": "dungeon3"
          , "environment": "cave"
          , "start_pos": [1,8]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うーん・・まだまだいるなぁ"
                      , "SPEAK %avatar% もじょ 足の踏み場もないのだ"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [19,2]
                  , "type": "goto"
                  , "room": "floor2"
                }
            }
          , "units": [
                {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [9,7]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [12,3]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [14,6]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [19,5]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [12,8]
                  , "unit_class": "21009BlueWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [14,4]
                  , "unit_class": "21009BlueWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [19,7]
                  , "unit_class": "21009BlueWarm"
                  , "act_brain": "excurse"
                }
            ]
        }
      , "floor2": {
            "id": 21009002
          , "battle_bg": "dungeon3"
          , "environment": "cave"
          , "start_pos": [7,14]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あっ、変なトカゲがいる"
                      , "SPEAK %avatar% もじょ あいつがムシを産み出してるのだ"
                      , "SPEAK %saram% サラマンダーもどき ギョギョギョ・・これで火山はワイのものなんやで"
                      , "LINES %avatar% あいつを倒せばいいんだな"
                    ]
                }
              , "endspeak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし、サラマンダーさんに報告だ"
                    ]
                  , "chain": "goto_end"
                }
                , "goto_end": {
                    "type": "goto"
                  , "room": "volcano"
                }
            }
          , "units": [
                {
                    "character_id": -10104
                  , "icon":"shadow2"
                  , "pos": [7,8]
                  , "code": "saram"
                  , "act_brain": "rest"
                  , "trigger_gimmick": "endspeak"
                  , "bgm": "bgm_bossbattle"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [3,7]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [9,4]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [5,12]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [10,11]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [6,7]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10105
                  , "icon":"shadow"
                  , "pos": [8,9]
                  , "unit_class": "21009RedWarm"
                  , "act_brain": "excurse"
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [5,7]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "8886666222244448"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [7,10]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "6622224444888866"
                  , "excurse_step": 3
                }
             ,  {
                    "character_id": -10106
                  , "icon":"shadowB"
                  , "pos": [9,7]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "2444488886666222"
                  , "excurse_step": 3
                }
            ]
        }
      , "volcano": {
            "id": 21008000
          , "battle_bg": "dungeon3"
          , "environment": "grass"
          , "start_pos": [4,8]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %saram% よくやったな。助かったわい"
                      , "LINES %saram% 約束通り、ここは通してやろう"
                      , "LINES %avatar% ありがとうございます！"
                      , "LINES %saram% お前がトロルの血を継いでるのなら森の中を歩いて行けば着くじゃろう"
                    ]
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
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
                  , "brain_noattack": true
                }
            ]
        }
    }
}
