{
    "extra_uniticons": ["elena"]
  , "rooms": {
        "start": {
            "id": 21012000
          , "battle_bg": "forest"
          , "environment": "snow"
          , "bgm": "bgm_home"
          , "start_pos": [11, 11]
          , "gimmicks": {
              "open_comment": {
                  "trigger": "rotation"
                , "rotation": 1
                , "type": "lead"
                , "leads": [
                      "LINES %avatar% エーレナ！"
                    , "UMOVE %avatar% 222"
                    , "LINES %elena% あ、[NAME]！！来てくれたのね！"
                    , "LINES %elena% もじょちゃんもこんにちは"
                    , "SPEAK %avatar% もじょ まあ、来てやったのだ"
                    , "LINES %elena% うふふ、ありがと"
                    , "LINES %avatar% あのさ、じつはこんなわけで・・"
                    , "LINES %elena% へぇー。トロル！！"
                    , "LINES %avatar% いや、まだ全然分からないよ"
                    , "LINES %elena% じゃ、亜人の仲間だね！うれしい！！"
                    , "LINES %avatar% いやー、なかなか複雑なんだけど・・"
                    , "LINES %avatar% で、それが本当なのかルーツが知りたくて"
                    , "LINES %avatar% トロルの里ってのをさがしてるんだけど・・"
                    , "LINES %elena% トロルの里・・"
                    , "LINES %avatar% どこにあるか知ってる？"
                    , "LINES %elena% うーん。なんたって絶滅しちゃってから随分経つから・・"
                    , "SPEAK %avatar% もじょ わからないのだ？"
                    , "LINES %elena% でも、シルフなら何か知ってるかもしれないわね・・。"
                    , "LINES %avatar% シルフ？"
                    , "SPEAK %avatar% もじょ 風の精なのだ"
                    , "LINES %elena% あら、よくしってるわね"
                    , "LINES %elena% わたしたちエルフは結構顔なじみなんだけど"
                    , "LINES %elena% ちょっと変わった性格してるのよね・・。"
                    , "LINES %avatar% 変わった性格？"
                    , "LINES %avatar% だーいじょうぶ!ボクの周りにはまともな人なんかいないから！"
                    , "LINES %elena% あ、明るく悲しいことを言うのね・・"
                    , "SPEAK %avatar% もじょ ホントに不憫な子なのだ・・"
                    , "LINES %elena% わかったわ。シルフの森に行きましょう"
                    , "LINES %elena% じゃ、道案内するね！"
                    , "SPEAK %avatar% もじょ なーんかいやな予感がするのだ・・"
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
                    "character_id": -20101
                  , "icon":"elena"
                  , "pos": [11,6]
                  , "code": "elena"
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}