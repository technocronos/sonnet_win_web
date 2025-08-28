{
    "extra_uniticons": ["boy", "man", "woman", "shisyou", "layla"]
  , "rooms": {
        "start": {
            "id": 41009000
          , "battle_bg": "dungeon"
          , "bgm": "bgm_registance"
          , "environment": "cave"
          , "start_pos": [7,9]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %gebaru%"
                      , "LINES %gebaru% おお！[NAME]じゃないか！戻ってきたんだね！"
                      , "LINES %avatar% ゲバルさん。話があるんだけど。"
                      , "LINES %avatar% ボクも正式にレジスタンスに参加してマルティーニを倒したいと思って・・"
                      , "LINES %gebaru% それはありがたい！君を待ってたよ！"
                      , "LINES %layla% ゲバル、ちょっと待って。"
                      , "LINES %layla% どうして気が変わったのか教えてもらえるかしら？"
                      , "LINES %gebaru% おい・・レイラ・・"
                      , "LINES %avatar% レイラさん、いつだったか・・ボクがトロル族って言ったとき、『まだ生き残りが』って言ってましたよね？"
                      , "LINES %layla% え？ええ・・。憶えてたの・・"
                      , "LINES %avatar% 実はボク、トロルの里に行ったんです。その時に・・"
                      , "LINES %layla% なるほどね・・。そういうわけだったの・・。"
                      , "LINES %layla% じゃ、もう知ってるかもしれないけど教えてあげる。確かにマルティーニ王はトロルよ。"
                      , "LINES %layla% しかも遠い祖先が、とかじゃない。純正のね。私、実際見たから分かるわ。"
                      , "LINES %avatar% すごい。側近しか姿を見たことがないって話なのに・・"
                      , "LINES %layla% 変装は得意だから。マルティーニ城の中も何回か忍び込んだことあるの。"
                      , "LINES %gebaru% トロルって言ったら寿命は300年以上らしいからなり代わっててもありえない話じゃないな。"
                      , "SPEAK %avatar% もじょ まー。亜人ならそれくらいは生きるのだ"
                      , "LINES %layla% でもどうして同じ亜人を滅ぼそうとするのかしら・・"
                      , "LINES %avatar% 魂を売った、って・・"
                      , "LINES %layla% 魂を売った、か・・"
                      , "LINES %gebaru% 事情は分かった。レイラ、理由としては十分だろう？"
                      , "LINES %layla% そうね。"
                      , "LINES %gebaru% じゃ、レイラとこれからは行動を共にしてほしい。"
                      , "LINES %gebaru% これから流刑地に行ってほしい。実はそこにはレジスタンスの支部があるんだ。"
                      , "LINES %gebaru% 実はウラのミッションもあるんだがそこはおいおいレイラから説明してくれ"
                      , "LINES %layla% はい。"
                      , "LINES %avatar% はい！レイラさん。またよろしくね！"
                      , "LINES %layla% よろしくね！また一緒に戦えてうれしいわ！"
                      , "LINES %avatar% んー・・。ところでレイラさんその薬指の指輪・・"
                      , "LINES %layla% え？ええ、実はゲバルが事が上手く運んだら私たち結婚しようって・・"
                      , "SPEAK %avatar% もじょ 顔が真っ赤になってるのだ・・"
                      , "LINES %layla% もしそうなったら、結婚式はぜひ来てね？"
                      , "LINES %avatar% は、はい・・・"
                      , "SPEAK %avatar% もじょ ・・キズに塩塗りこむのだ・・"
                      , "SPEAK %avatar% もじょ 結構天然なのだ・・"
                      , "LINES %gebaru% やあ、レイラ。そんなこと今言わんでもいいだろう！は・は・は！"
                      , "SPEAK %avatar% もじょ やっぱもじょはこのバカップル苦手なのだ・・"
                    ]
                  , "chain": "goal"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [12, 0]
                  , "ornament": "lamp"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -20103
                  , "code": "layla"
                  , "icon":"layla"
                  , "pos": [8,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -20102
                  , "code": "gebaru"
                  , "icon":"man"
                  , "pos": [7,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [4,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [11,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [0,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [6,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "woman2"
                  , "icon":"woman"
                  , "pos": [12,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
