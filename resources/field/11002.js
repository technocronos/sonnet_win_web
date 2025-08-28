{
    "extra_uniticons": ["shadow", "shadow2"]
  , "rooms": {
        "start": {
            "id": 11002000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [3, 10]
          , "gimmicks": {
                "goal_drama": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [19, 5]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% とーちゃく"
                      , "ここはロジックで置き換える"
                      , "SPEAK %avatar% もじょ じじぃに水汲みできたことを自慢するのだ"
                    ]
                }
              , "goal": {
                    "trigger": "hero"
                  , "pos": [19, 5]
                  , "type": "goal"
                  , "ornament": "curious"
                }

              , "enemy1": {
                    "trigger": "hero"
                  , "pos":[2,2], "rb":[4,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 2]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                  , "chain": "ロジックで書き換える"
                }
              , "enemy2": {
                    "trigger": "hero"
                  , "pos":[6,5], "rb":[10,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 9]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                  , "chain": "ロジックで書き換える"
                }
              , "find1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% なんか出た・・"
                      , "SPEAK %avatar% もじょ やっぱりいたのだ。戦って撃退するのだ"
                      , "SPEAK %avatar% もじょ 隣接するマスを選択すると攻撃できる敵に赤いマーカーが出るのだ"
                      , "SPEAK %avatar% もじょ 赤いマーカーを選択したら攻撃することができるのだ"
                      , "SPEAK %avatar% もじょ もちろん向こうから近寄ってきて攻撃されることもあるのだ"
                    ]
                  , "condition": {"cleared":false}
                }
              , "find2": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% こっちにも出た・・"
                      , "SPEAK %avatar% もじょ 向こうも精が出るのだ…。慌てずに撃退するのだ"
                    ]
                  , "condition": {"cleared":false}
                }

              , "enemy3": {
                    "trigger": "hero"
                  , "pos":[11,2], "rb":[16,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 9]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                  , "chain": "enemy3.5"
                }
              , "enemy3.5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 9]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                  , "chain": "enemy4"
                }
              , "enemy4": {
                    "condition": {"cleared":false}
                  , "type": "unit"
                  , "unit": {
                        "pos": [14, 3]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                  , "chain": "find3"
                }
              , "find3": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% いっぱい出た・・"
                      , "SPEAK %avatar% もじょ 一度に戦うのはマズいのだ。片方ずつ倒すのだ"
                    ]
                }

              , "treasure1": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [4,8]
                  , "item_id": 12002
                  , "one_shot": 110020001
                  , "chain": "equip_explain"
                }
              , "equip_explain": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "SPEAK %avatar% もじょ クエスト中に拾った装備品はアイテムとして使えば装備できるのだ"
                      , "SPEAK %avatar% もじょ とりあえず装備しとけなのだ。メニューの｢アイテム｣から選ぶのだ"
                    ]
                }

              , "treasure2": {
                    "type": "treasure"
                  , "ornament": "twinkle"
                  , "trigger": "player"
                  , "pos": [3,2]
                  , "item_id": 11002
                  , "one_shot": 110020002
                }

              , "after_fight": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ ユーザ対戦は8ターンで終わるのだけど"
                      , "SPEAK %avatar% もじょ クエストの戦闘は4ターンで終わるのだ"
                      , "SPEAK %avatar% もじょ あと、クエストのHPとユーザ対戦のHPは別々なのだ"
                      , "SPEAK %avatar% もじょ クエストのHP回復したかったらクエストでくすりびん使うのだ"
                      , "SPEAK %avatar% もじょ 装備・合成画面から使っちゃうとユーザ対戦のほうが回復しちゃうのだ"
                    ]
                }

              , "enemy5": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos":[8,2], "rb":[11,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [9, 3]
                      , "character_id":-1201
                      , "icon":"shadow2"
                    }
                }
              , "enemy6": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos":[13,2], "rb":[17,4]
                  , "type": "unit"
                  , "unit": {
                        "pos": [16, 2]
                      , "character_id":-1101
                      , "icon":"shadow"
                    }
                }

              , "submission_explain": {
                    "condition": {"mission":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "NOTIF ミッション\n(達成:+100マグナ)"
                      , "NOTIF ゴブリン６体を撃破して\n7ターン以内にゴール"
                    ]
                }
              , "last_enemy_explain": {
                    "condition": {"mission":true}
                  , "trigger": "termination"
                  , "termination": 5
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ふう…これで全部かな？"
                      , "SPEAK %avatar% もじょ …まだ気配がするのだ。たぶんどっかに隠れてるのだ"
                    ]
                }

              , "relief_speak": {
                    "condition": {"cleared":false}
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ なかなか苦労してるのだ"
                      , "SPEAK %avatar% もじょ ここはこのもじょ様がくすりびんをめぐんでやるのだ。感謝するのだ"
                    ]
                  , "chain": "relief_item"
                }
              , "relief_item": {
                    "type": "treasure"
                  , "item_id": 1001
                }
            }
        }
    }
}
