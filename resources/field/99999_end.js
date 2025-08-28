{
    "id": 99999999
  , "battle_bg": "dungeon"
  , "environment": "cave"
  , "start_pos": [2,9]
  , "gimmicks": {

        "back": {
            "trigger": "hero"
          , "pos": [1,10]
          , "type": "goto"
        }
      , "open": {
            "trigger": "rotation"
          , "rotation": 1
          , "type": "lead"
          , "leads": [
                "LINES %k% WORKING'面白いよ\nやまだ～"
              , "LINES %y% しごとしろよ\nさっさと洞窟掘り進めろ"
              , "LINES %k% 子供元気に育ってる？"
              , "LINES %y% 植物みたいに言うな\n元気なんじゃない？"
              , "LINES %k% 2人いるらしいじゃないスか\n10才になったら片方\n嫁にください"
              , "LINES %y% 両方息子だよ。犯罪者！"
              , "LINES %k% ……あれ？だれか来た"
              , "LINES %y% え？ホントだ…\nこれ最後まで来たら\nどうなるの？"
              , "LINES %k% さあ…"
              , "LINES %y% …………"
              , "LINES %k% …………"
              , "LINES %y% …………\nやっちゃう？"
              , "LINES %k% それが簡単でいいな"
            ]
        }
      , "clear": {
            "trigger": "unit_exit"
          , "unit_exit": ["k", "y"]
          , "type": "escape"
          , "escape_result": "success"
          , "ignition": {"!unit_exist":["k", "y"]}
        }
    }
  , "units": [
        {
            "pos": [5,6]
          , "character_id":-9903
          , "code":"k"
          , "bgm": "bgm_bigboss"
        }
      , {
            "pos": [8,5]
          , "character_id":-9904
          , "code":"y"
          , "bgm": "bgm_bigboss"
        }
    ]
}
