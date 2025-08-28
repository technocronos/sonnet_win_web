{
    "extra_uniticons": ["man", "woman", "shisyou"]
  , "extra_maptips": [9839]
  , "rooms": {
        "start": {
            "id": 14001000
          , "battle_bg": "dungeon"
          , "bgm": "bgm_home"
          , "environment": "grass"
          , "start_pos": [7,12]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "ignition": {"!has_flag":1400100001}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "one_shot": 1400100001
                  , "leads": [
                        "LINES %avatar% シショーのバカ!カネカネ言うな"
                      , "LINES %avatar% 無駄使いしてるクセに"
                      , "SPEAK %avatar% もじょ で、何しに来たのだ？"
                      , "LINES %avatar% 散歩して海を見るの"
                      , "SPEAK %avatar% もじょ 海を見てどうするのだ？"
                      , "LINES %avatar% 知らないっ!"
                    ]
                }
              , "change_tip": {
                    "ignition": {"!has_flag":1400100002}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "square_change"
                  , "change_pos": [7,7]
                  , "change_tip": 9839
                }
              , "woman1_speak": {
                    "ignition": {"!has_flag":1400100002}
                  , "trigger": "hero"
                  , "pos": [4,10], "rb": [6,11]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %woman1% あら[NAME]。お散歩？"
                      , "LINES %avatar% うん、海を見に来たの。"
                      , "LINES %woman1% 今、マルティーニの連絡船が来てるから人が多いのよ"
                      , "LINES %avatar% へ～そうなんだ。いつ来たの？"
                      , "LINES %woman1% 10日くらい前ね、たしか。"
                      , "LINES %avatar% そっか、じゃ、もう少しいそうだね。"
                      , "LINES %woman1% そうね。いつも半月くらいいるから、それまでは賑やかね。"
                    ]
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos": [3,9]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 1400100001
                  , "chain": "find"
                }
              , "find": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あ、こんなとこに時計みっけ"
                      , "SPEAK %avatar% もじょ もらっとけなのだ"
                    ]
                }
              , "woman1_speak2": {
                    "ignition": {"has_flag":1400100002}
                  , "trigger": "hero"
                  , "pos": [4,10], "rb": [6,11]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %woman1% あら[NAME]。お散歩？"
                      , "LINES %woman1% 今日はいい天気よね"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [11,6], "rb": [13,7]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %man1% よう[NAME]。"
                      , "LINES %man1% あっちの洞窟はいっちゃダメだぞ"
                      , "LINES %man1% 入ったらキバウオに噛み付かれるぞ。"
                      , "LINES %avatar% 今、引潮だから入口がぽっかりだね・・"
                      , "LINES %avatar% でもあそこトンネルがいっぱいあって面白そうなんだよね～"
                      , "LINES %man1% マルティーニから来た客人が間違えて行かないようにしないとな"
                    ]
                   , "chain": "man2_appear"
                }
              , "man2_appear": {
                    "type": "unit"
                  , "ignition": {"14003_cleared":false}
                  , "unit": {
                          "character_id": -9906
                        , "code": "man2"
                        , "icon":"man"
                        , "pos": [12,8]
                        , "union": 1
                        , "act_brain": "rest"
                        , "brain_noattack": true
                     }
                   , "chain": "man2_speak"
                 }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [12,3], "rb": [16,5]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %man2% おじさん おーい、ケケルー！ケケルー！"
                      , "UALGN %avatar% 0"
                      , "LINES %avatar% ？"
                      , "SPEAK %man2% おじさん ケケルー！"
                      , "SPEAK %man2% おじさん やっぱりいない！？"
                      , "UALGN %man2% 3"
                      , "SPEAK %man2% おじさん 君、男の子を見なかったか？私の息子なんだが…"
                      , "LINES %avatar% え？さ、さあ…ボクいま来たばかり…"
                      , "LINES %avatar% ケケル君って名前なんですか？"
                      , "SPEAK %man2% おじさん そうだ。見かけたら教えてくれ。危ないところに行ってなければいいが。"
                      , "LINES %avatar% 変なとこに行ってたら危ないよ。まさかあの洞窟に行ってるんじゃ・・"
                      , "SPEAK %man2% おじさん んっ？なんだあの洞窟・・さっきはなかったぞ？"
                      , "LINES %avatar% 引潮になったら現れるんですよあそこは・・"
                      , "LINES %man1% しかし私は見張ってたけど誰も来なかったぞ？"
                      , "SPEAK %avatar% もじょ ガキってのは分からないもんなのだ"
                      , "LINES %avatar% だとしたら大変だ・・"
                      , "LINES %man1% もうすぐ満潮だ・・洞窟が塞がったらおしまいだ"
                      , "LINES %avatar% ちょっと探してきます！"
                    ]
                  , "chain": "goal"
                }
              , "man3_speak": {
                    "condition": {"11007_cleared":true}
                  , "ignition": {"!has_flag":1400100002}
                  , "trigger": "hero"
                  , "pos": [9,10], "rb": [10,12]
                  , "type": "lead"
                  , "one_shot": 1400100002
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "UALGN %man3% 1"
                      , "SPEAK %man3% おじさん やあ。この間はケケルが世話になったね"
                      , "LINES %avatar% あ、ケケルくんのお父さん師匠の許可とってきました！"
                      , "SPEAK %man3% おじさん おお、そうか。では船に乗っていくといい。"
                      , "LINES %avatar% やったー！！！"
                      , "SPEAK %man3% おじさん でも、マルティーニに行く前に亜人の大陸に2,3日停泊するよ？"
                      , "SPEAK %man3% おじさん ドワーフと物品交換をするんだ。"
                      , "LINES %avatar% 全然かまいません！"
                      , "SPEAK %man3% おじさん よし！じゃ、出発だ！"
                      , "SPEAK %man3% おじさん この故郷の島に戻って来たければグローバルマップからいつでも戻ってこれるから"
                      , "SPEAK %avatar% もじょ グローバルマップは右上のグローバルボタンから行けるのだ"
                      , "LINES %avatar% おっけー！"
                    ]
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "escape": {
                    "trigger": "hero"
                  , "pos": [13,12]
                  , "type": "escape"
                  , "ornament": "escape"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man1"
                  , "pos": [12,5]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [5,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"11007_cleared":true, "!has_flag":1400100002}
                  , "character_id": -9906
                  , "code": "man3"
                  , "pos": [11,11]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
