{
    "extra_uniticons": ["elena", "noel"]
  , "rooms": {
        "start": {
            "id": 21010000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [3,9]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ふう…もう何日この森をさまよってるんだろう…完全に道に迷ったね…"
                      , "UMOVE %avatar% 2"
                      , "SPEAK %avatar% もじょ もういいかげん飽きたのだ。帰るのだ…"
                      , "UMOVE %avatar% 2"
                      , "LINES %avatar% 帰り道がわかってりゃとっくに帰ってるって"
                      , "LINES %avatar% そもそももじょが"
                      , "LINES %avatar% こっちに何か気配がするのだ"
                      , "LINES %avatar% とかなんとかしったかするからでしょ"
                      , "SPEAK %avatar% もじょ ひとのせいにするななのだ"
                      , "LINES %avatar% だってなーんもないじゃん"
                      , "UMOVE %avatar% 2"
                      , "LINES %avatar% ・・・"
                      , "LINES %avatar% …ん？"
                      , "LINES %avatar% 何か変わった足跡があるぞ？"
                      , "SPEAK %avatar% もじょ 動物じゃないのだ？"
                      , "LINES %avatar% うーん。なんかみたことないような…"
                      , "UMOVE %avatar% 2"
                      , "LINES %avatar% あ、あっちに続いてるけど…"
                      , "UMOVE %avatar% 6"
                      , "SPEAK %avatar% もじょ うん、足跡の方角に何か気配がするのだ"
                      , "LINES %avatar% まーたそれぇ？"
                      , "SPEAK %avatar% もじょ 信用できないなら勝手にするのだ"
                      , "UMOVE %avatar% 2"
                      , "LINES %avatar% …ちょっとまって！誰かいる…"
                    ]
	               , "chain": "elena_appear"
                }
              , "elena_appear": {
	                "type": "unit"
	              , "unit": {
	                      "pos": [6, 1]
	                    , "character_id":-20101
	                    , "icon":"elena"
                      , "code": "elena"
                      , "union": 1
                      , "act_brain": "rest"
                      , "brain_noattack": true
	                 }
	               , "chain": "elena_speak"
                }
              , "elena_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あっ。人が倒れてる…。あ、あの子は…人間じゃないよね？"
                      , "UMOVE %avatar% 226"
                      , "SPEAK %avatar% もじょ あのとんがった耳…たしか…"
                      , "LINES %avatar% 知ってるの"
                      , "SPEAK %avatar% もじょ あれは…エルフなのだ"
                      , "LINES %avatar% そうだよ！エルフだ！間違いない！わ、すご！はじめて見る！！"
                      , "SPEAK %avatar% もじょ でも、普通めったに見つからないはずなのだ…？何か様子が変なのだ"
                      , "LINES %avatar% 血…流してない？"
                      , "SPEAK %avatar% もじょ 怪我してるのだ？"
                      , "LINES %avatar% 大変！助けなきゃ！！"
                      , "UMOVE %avatar% 2"
                      , "UALGN %avatar% 2"
                      , "LINES %avatar% 大丈夫ですか？…ひどい怪我だ"
                      , "SPEAK %elena% エルフの娘 う…"
                      , "LINES %avatar% 生きてる。よかった"
                      , "LINES %avatar% 足を怪我してる・・。ん？これは…"
                      , "LINES %avatar% …銃創!?"
                      , "SPEAK %avatar% もじょ …"
                      , "LINES %avatar% これで大丈夫"
                      , "SPEAK %elena% エルフの娘 あ、ありがとう"
                      , "LINES %avatar% あの…エルフですよね？…ひょっとして人間に撃たれたんですか？"
                      , "UALGN %elena% 1"
                      , "SPEAK %elena% エルフの娘 人間のエルフ狩りにあってしまったんです"
                      , "SPEAK %avatar% もじょ ひどいことするのだ"
                      , "SPEAK %elena% エルフの娘 里から出てしまったのが悪いんです…"
                      , "SPEAK %elena% エルフの娘 兄さんからあんなに外に出るなっていわれてたのに…"
                      , "SPEAK %elena% エルフの娘 人間は嫌いですがあなたはどうやら違うようですね…私の名はエレナ"
                      , "LINES %elena% お礼にエルフの里に来てください"
                      , "LINES %avatar% え？いいの？"
                      , "SPEAK %avatar% もじょ エルフの里なんてめずらしいのだ"
                      , "LINES %avatar% 行きたい！行きたい！"
                      , "LINES %elena% 命の恩人ですから。どうぞ"
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
