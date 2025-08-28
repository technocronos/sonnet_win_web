{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 31009001
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [14,8]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ここがランカスタ村・・"
                      , "LINES %avatar% ボクが生まれた所か・・"
                      , "SPEAK %avatar% もじょ なかなかこぎれいな村なのだ"
                      , "SPEAK %avatar% もじょ どうなのだ？生まれたとこに来た感想は"
                      , "LINES %avatar% っていわれてもねえ・・"
                      , "LINES %avatar% さっき知ったばっかだし、全然実感わかないけど・・"
                      , "LINES %avatar% とりあえず村長さんの家に行って村長さんに話を聞こうか"
                      , "SPEAK %avatar% もじょ そうするのだ"
                    ]
                  , "chain": "goto"
                }
              , "goto": {
                    "type": "goto"
                  , "room": "room1"
                }
            }
        }
      , "room1": {
            "id": 31008000
          , "battle_bg": "dungeon"
          , "bgm": "bgm_home"
          , "environment": "cave"
          , "start_pos": [5,3]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %sontyo% 3"
                      , "SPEAK %sontyo% 村長 おお、なんと！あの時の赤ん坊かね？"
                      , "SPEAK %sontyo% 村長 その頬の痣もそっくりじゃな。"
                      , "SPEAK %sontyo% 村長 ずっと母親と一緒に身を投げたとばかり思っておったが"
                      , "SPEAK %sontyo% 村長 生きてたのじゃな！よかった、よかった！！"
                      , "LINES %avatar% あの・・お母さんについて知ってることあったら"
                      , "LINES %avatar% 教えてほしいんですけど・・。"
                      , "SPEAK %sontyo% 村長 おぬしの母親は普通に暮らしておっただけなのに"
                      , "SPEAK %sontyo% 村長 なぜか突然マルティーニ政府からスパイの容疑をかけられての"
                      , "SPEAK %sontyo% 村長 家族も全員捕まって処刑されてしもたのじゃ"
                      , "LINES %avatar% ・・なぜお母さんはいきなり狙われたか分かりますか？"
                      , "SPEAK %sontyo% 村長 さあのう・・"
                      , "SPEAK %sontyo% 村長 スパイ容疑といっても何が根拠だったのか・・"
                      , "SPEAK %sontyo% 村長 マルティーニ政府の考えることはようわからん・・"
                      , "SPEAK %sontyo% 村長 何か別の理由があって狙われたような気がしてならん"
                      , "SPEAK %sontyo% 村長 なんにせよスパイ容疑がかかったが最後"
                      , "SPEAK %sontyo% 村長 一族すべて消されることになっておる。"
                      , "SPEAK %avatar% もじょ じゃ、13年前のこととはいえ今でも危ないには違いないのだ"
                      , "LINES %avatar% そうだね。マルティーニにはこれ以上長居はしない方がいいね"
                      , "SPEAK %avatar% もじょ じゃ、そろそろおいとまするのだ"
                      , "SPEAK %sontyo% 村長 ま、待ちなさい。いくらなんでも13年前のことじゃからの"
                      , "SPEAK %sontyo% 村長 いくらなんでも今も探してるとかいうことはないと思うぞい"
                      , "LINES %avatar% うーん、そうですかねぇ・・"
                      , "SPEAK %sontyo% 村長 ま、遠いところせっかく来たんじゃから茶くらい・・"
                      , "SPEAK %avatar% もじょ なーにをそんなにひきとめてるのだ・・？"
                      , "NOTIF ドンドンドン！ドンドンドン！"
                      , "NOTIF あー、マルティーニの役人の者だ！"
                      , "LINES %avatar% ・・え？"
                      , "NOTIF 先ほどの通報でやってきたのだがここを開けなさい"
                      , "UALGN %sontyo% 0"
                      , "SPEAK %sontyo% 村長 ああ、はいはい・・"
                      , "LINES %avatar% ・・う。まさか・・"
                      , "SPEAK %avatar% もじょ 役人に通報しやがったのだ・・？"
                    ]
                  , "chain": "enemy2.1"
                }
              , "enemy2.1": {
                    "type": "unit"
                  , "unit": {
                         "pos": [2, 6]
                       , "character_id":-10052
                       , "code": "servant1"
                       , "icon":"shadow"
                     }
                  , "chain": "enemy2.2"
                 }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                         "pos": [3, 6]
                       , "code": "servant2"
                       , "character_id":-10052
                       , "icon":"shadow"
                     }
                   , "chain": "end_speak"
                 }
              , "end_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %servant1% こいつらか？例の件は"
                      , "SPEAK %sontyo% 村長 ええ、間違いありません。頬に例の痣が・・"
                      , "SPEAK %sontyo% 村長 王が捜し求めている者かと・・。ええ・・"
                      , "LINES %servant2% フム。よくやった。"
                      , "LINES %servant2% お前には追ってポイントが与えられるだろう。政府からの沙汰を待つがよい"
                      , "SPEAK %sontyo% 村長 ははーありがたきしあわせ"
                      , "SPEAK %avatar% もじょ ・・・こ、この・・じじい・・"
                      , "LINES %avatar% とりあえずどうしよう・・"
                      , "SPEAK %avatar% もじょ どうもこうも逃げるしかないのだ"
                      , "LINES %servant1% おい貴様ら。おとなしくついて来い"
                      , "LINES %avatar% よし、逃げろ！！"
                      , "LINES %servant2% あ、待てーい！！"
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
                    "pos": [3,5]
                  , "character_id":-9906
                  , "icon":"shisyou"
                  , "union": 1
                  , "code": "sontyo"
                  , "act_brain": "rest"
                }
            ]
        }
    }
}
