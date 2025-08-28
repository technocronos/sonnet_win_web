{
    "rooms": {
        "start": {
            "id": 11001000
          , "battle_bg": "forest"
          , "environment": "cave"
          , "start_pos": [6, 3]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% …で､ここからどうすんの?もじょ"
                      , "SPEAK %avatar% もじょ まず移動できる範囲にマーカーが出るから行きたい所をタップして選ぶのだ"
                      , "SPEAK %avatar% もじょ カーソルが移動したら同じとこもっかいタップで選ぶのだ"
                      , "SPEAK %avatar% もじょ 一番奥の祭壇まで行くのだとにかくマーカーをタップしてみるのだ"
                      , "SPEAK %avatar% もじょ GOなのだ！"
                    ]
                }
              , "torch1": {
                    "pos": [7, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [5, 3]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [4, 6]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [8, 6]
                  , "ornament": "torch"
                }
              , "torch5": {
                    "pos": [4, 10]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [8, 10]
                  , "ornament": "torch"
                }
              , "cristaltower1": {
                    "pos": [6, 15]
                  , "ornament": "cristaltower"
                }

              , "tresD": {
                    "trigger":"hero"
                  , "pos":[4, 11]
                  , "type": "lead"
                  , "condition": {"yet_flag":110010001}
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% あ！シショー見て見て"
                      , "LINES %avatar% くすりびん 見っけ"
                      , "SPEAK %avatar% 師匠 目ざといやつ…"
                      , "SPEAK %avatar% 師匠 落とし物か？だいぶ古そうじゃし､"
                      , "SPEAK %avatar% 師匠 ま､もらっとけ"
                      , "LINES %avatar% これ､大丈夫かな？"
                      , "SPEAK %avatar% 師匠 なに、大丈夫じゃろ"
                      , "SPEAK %avatar% 師匠 ………おまえなら"
                    ]
                }
              , "tresT": {
                    "trigger": "player"
                  , "pos":[4, 11]
                  , "type": "treasure"
                  , "item_id": 1001
                  , "one_shot": 110010001
                  , "ornament": "twinkle"
                }
              , "goalD": {
                    "trigger":"hero"
                  , "pos":[6, 13]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% 師匠 うむ…ここで大精霊に祈りをささげるのだ"
                      , "SPEAK %avatar% 師匠 詩は覚えておるかな？"
                      , "LINES %avatar% もちろん!"
                      , "SPEAK %avatar% 師匠 では始めるとよい"
                      , "LINES %avatar% 闇夜を照らす猛る炎よ\n炎の精霊よ､我に力を与えたまえ…"
                      , "LINES %avatar% 大気よ､水と成り、氷となれ\n光なき氷に閉ざされん…"
                      , "LINES %avatar% 雷よ、一条の光となり\n天より裁きを降りおろさん…"
                      , "EFFEC recv 0613"
                      , "LINES %avatar% …これで僕もソネットが使えるようになったの？"
                      , "SPEAK %avatar% 師匠 そうじゃの。あー、言い忘れとったが"
                      , "SPEAK %avatar% 師匠 クエストはいつでも中断することができるぞい"
                      , "SPEAK %avatar% 師匠 メニューで｢中断｣を選んでもいいし"
                      , "SPEAK %avatar% 師匠 いきなり別の画面飛んでもOKじゃ"
                      , "SPEAK %avatar% 師匠 再開したいときはメインページから再開すればよい"
                      , "SPEAK %avatar% 師匠 まぁとりあえず家に帰るぞ"
                      , "LINES %avatar% はーい"
                    ]
                    , "chain": "goal"
                }
              , "goal": {
                    "type": "goal"
                  , "escape_result": "success"
                }
            }
        }
    }
}
