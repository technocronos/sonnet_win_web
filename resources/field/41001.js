{
    "extra_uniticons": ["shadow", "shadow2", "man"]
  , "extra_maptips": [546, 516]
  , "rooms": {
        "start": {
            "id": 41001000
          , "battle_bg": "desert"
          , "environment": "grass"
          , "start_pos": [6,15]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UMOVE %avatar% 2"
                      , "RPBG1 06 15 $546"
                      , "LINES %avatar% ハア・・ハア・・"
                      , "UMOVE %avatar% 2"
                      , "RPBG1 06 14 $546"
                      , "LINES %avatar% ハア・・ハア・・"
                      , "LINES %avatar% もう半日くらいずっとこの砂漠をうろうろしてる・・"
                      , "LINES %avatar% 完全に迷ったかな・・"
                      , "SPEAK %avatar% もじょ もう[NAME]の方向音痴にはつきあいきれないのだ・・"
                      , "SPEAK %avatar% もじょ 地図の読めない脳をしてるにちがいないのだ・・"
                      , "LINES %avatar% うるさいなーもう・・"
                      , "UMOVE %avatar% 2"
                      , "RPBG1 06 13 $546"
                      , "SPEAK %avatar% もじょ 亜人の大陸目指してなんだってこんな砂漠に来ちまうのだ・・？"
                      , "SPEAK %avatar% もじょ ローカルマップじゃなくてグローバルマップでなんで迷うのだ"
                      , "SPEAK %avatar% もじょ グローバル方向音痴なのだ・・"
                      , "LINES %avatar% ほんっとにうるさいなー"
                      , "LINES %avatar% ぐちぐちぐちぐちと！！"
                      , "UMOVE %avatar% 2"
                      , "RPBG1 06 12 $546"
                      , "SPEAK %avatar% もじょ 水も尽きてきた・・のだ"
                      , "LINES %avatar% 話だとこんな砂漠でもモンスターがでるらしいから気をつけないと・・"
                      , "SPEAK %avatar% もじょ モンスター？こんな砂漠じゃ水の精もでてこなのだ・・"
                      , "UMOVE %avatar% 2"
                      , "RPBG1 06 11 $546"
                      , "LINES %avatar% ん？なんだあれ？"
                      , "SPEAK %avatar% もじょ 砂漠がうごめいてるのだ"
                    ]
                  , "chain": "warm_appear"
                }
              , "warm_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,6]
                      , "character_id":-10048
                      , "icon":"shadow"
                      , "code": "warm"
                    }
                  , "chain": "speak2"
                }
  	          , "speak2": {
  	                 "type": "lead"
  	               , "leads": [
                        "LINES %avatar% モンスターだ・・。ほーら、こんな砂漠でもやっぱいるじゃん。"
                      , "UMOVE %warm% 8"
                      , "SPEAK %avatar% もじょ そんなことはどうでもいいのだ。"
                      , "UMOVE %warm% 8"
                      , "SPEAK %avatar% もじょ こっちに近づいてくるのだ倒すのだ！！"
                      , "UMOVE %warm% 8"
                      , "LINES %avatar% ん～・・・ホイッ！"
                      , "EFFEC sprk 0609"
  	                  , "LINES %warm% キューン！！！"
  	                 ]
                  , "chain": "warm_exit"
  	            }
              , "warm_exit": {
                    "type": "unit_exit"
                  , "exit_target": "warm"
	                , "chain": "speak3"
                }
  	          , "speak3": {
  	                 "type": "lead"
  	               , "leads": [
                        "LINES %avatar% あービックリした。でもなんだか弱かったね・・。"
                      , "SPEAK %avatar% もじょ 最後の方は逃げて行ったのだ・・。"
  	                 ]
                  , "chain": "man_appear"
  	            }
              , "man_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,8]
                      , "character_id":-9907
                      , "icon":"man"
                      , "code": "man1"
                    }
                  , "chain": "speak4"
                }
  	          , "speak4": {
  	                 "type": "lead"
  	               , "leads": [
                        "SPEAK %man1% 男 見させてもらったよ。それはソネットかい？"
                      , "LINES %avatar% わ、あ、あなたは？"
                      , "SPEAK %man1% 男 おれはレジスタンスの人間だ。"
                      , "LINES %avatar% ・・レジスタンス？"
                      , "SPEAK %man1% レジスタンス あのモンスターは俺のペットだ"
                      , "SPEAK %man1% レジスタンス すまない、君の力を見たかったんだ。"
                      , "SPEAK %man1% レジスタンス ところで、君今のマルティーニ体制のことをどう思う？"
                      , "LINES %avatar% え？どう思うって？"
                      , "SPEAK %man1% レジスタンス 今、マルティーニ朝の独裁専制が民を苦しめているんだ。"
                      , "SPEAK %man1% レジスタンス 奴らはよそ者、とくに亜人間に容赦が無い。"
                      , "SPEAK %man1% レジスタンス いまだに馬鹿の一つ覚えに300年前の亜人戦争でどれだけ死者が出たかを強調している。"
                      , "SPEAK %man1% レジスタンス だが精霊と交流を持てる亜人間との共生を図らなくては人類に未来は無いんだ！！"
                      , "SPEAK %man1% レジスタンス どうだい？反政府大会に来てみないか？"
                      , "LINES %avatar% ・・うーん、あまり興味ないな・・"
                      , "LINES %avatar% でもおなかもすいたし・・"
                      , "SPEAK %man1% レジスタンス そうか、興味ないか。"
                      , "SPEAK %man1% レジスタンス しかしまず野営キャンプに来てみてくれ"
                      , "SPEAK %man1% レジスタンス あっちのオアシスにキャンプがあるんだ。"
                      , "SPEAK %man1% レジスタンス 君の力は特別なもののようだ"
                      , "SPEAK %man1% レジスタンス 我々の力になってほしい"
                      , "LINES %avatar% うーん。そこまではちょっと・・"
                      , "SPEAK %man1% レジスタンス ・・・"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %man1% レジスタンス ・・・あー、腹、へっただろう？"
                      , "SPEAK %man1% レジスタンス 俺たちはキャラバンもやってるからメシは好きなだけ・・"
                      , "LINES %avatar% ・・行く！"
                      , "SPEAK %man1% レジスタンス ・・・"
                      , "SPEAK %man1% レジスタンス ま、まあ来てくれるんならいい。"
                      , "SPEAK %man1% レジスタンス オアシスへはここから砂漠をしばらく歩かないといけないが、"
                      , "SPEAK %man1% レジスタンス まあ、いつも使ってる道はモンスターもいないからすぐにつくさ。"
                      , "LINES %avatar% わー助かった！！"
                      , "SPEAK %avatar% もじょ これで干物にならずにすんだのだ。"
                      , "LINES %avatar% ・・・"
                      , "LINES %avatar% ん？"
                      , "SPEAK %man1% レジスタンス どうした？まだ何か・・"
                      , "SPEAK %avatar% もじょ なんかまた砂漠の中からもごもごと何かがうごめいているのだ"
                      , "RPBG1 06 04 $516"
                      , "NOTIF ボコンッ！！！"
                      , "UALGN %man1% 3"
                      , "LINES %avatar% な、なんだ？？？"
  	                 ]
                  , "chain": "spider_appear"
  	            }
              , "spider_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6,4]
                      , "character_id":-10051
                      , "icon":"shadow2"
                      , "code": "spider"
                    }
                  , "chain": "speak5"
                }
  	          , "speak5": {
  	                 "type": "lead"
  	               , "leads": [
                        "LINES %avatar% あれ、またあなたのペット？ちょっと趣味悪すぎ・・"
                      , "SPEAK %man1% レジスタンス いや、違う・・あれは・・"
                      , "SPEAK %man1% レジスタンス 砂漠蜘蛛だ！恐ろしい毒をもったモンスターだ！"
                      , "UMOVE %spider% 888"
                      , "SPEAK %man1% レジスタンス うわー！！！"
  	                 ]
                  , "chain": "man1_exit"
  	            }
              , "man1_exit": {
                    "type": "unit_exit"
                  , "exit_target": "man1"
	                , "chain": "spider_exit"
                }
              , "spider_exit": {
                    "type": "unit_exit"
                  , "exit_target": "spider"
	                , "chain": "speak6"
                }
  	          , "speak6": {
  	                 "type": "lead"
  	               , "leads": [
                        "SPEAK %avatar% もじょ あー！！！さらわれた！！！"
                      , "LINES %avatar% 砂の中に潜っていっちゃったよ・・どうしよう"
                      , "SPEAK %avatar% もじょ あー、連れ去られたのだ・・。"
                      , "SPEAK %avatar% もじょ 砂漠蜘蛛の巣は砂の中にあるのだ。"
                      , "SPEAK %avatar% もじょ ま、ほっときゃいいのだ"
                      , "LINES %avatar% ふーん・・ところであの人これからどうなるの？"
                      , "SPEAK %avatar% もじょ セツメイするのだ"
                      , "SPEAK %avatar% もじょ 砂漠蜘蛛は今が繁殖期にあたるのだ"
                      , "LINES %avatar% フムフム・・"
                      , "SPEAK %avatar% もじょ まずあの男は毒で全身動けない状態にされた後"
                      , "SPEAK %avatar% もじょ 子蜘蛛を体内に産みつけられて生きながら全身をついばまれ・・"
                      , "LINES %avatar% フムフム・・"
                      , "SPEAK %avatar% もじょ しかる後、残酷な死をむかえるのだ"
                      , "LINES %avatar% フーン・・"
                      , "LINES %avatar% ・・で、ボクたちはどうなるの？"
                      , "SPEAK %avatar% もじょ セツメイするのだ"
                      , "SPEAK %avatar% もじょ 見知らぬ砂漠で水も食料もなく完全に遭難したのだ"
                      , "SPEAK %avatar% もじょ 体力もそろそろ尽きてそのうち動けなくなるのだ"
                      , "LINES %avatar% フムフム・・"
                      , "SPEAK %avatar% もじょ そしたらあの砂漠蜘蛛にとらえられることになるのだ"
                      , "LINES %avatar% フムフム・・"
                      , "SPEAK %avatar% もじょ そしたら同じく毒で全身動けない状態にされ（以下略"
                      , "LINES %avatar% フムフム・・"
                      , "SPEAK %avatar% もじょ しかる後、残酷な死をむかえるのだ"
                      , "LINES %avatar% じゃ、助けに行かなきゃさ・・"
                      , "SPEAK %avatar% もじょ ・・ほっとくのだ"
                      , "LINES %avatar% え？"
                      , "SPEAK %avatar% もじょ ほっときゃいーのだ！もじょは島に帰るのだ！"
                      , "SPEAK %avatar% もじょ エロイムエッサイムエロイムエッサイム\nヒャヒャヒャヒャ・・"
                      , "LINES %avatar% ・・あ。切れた・・"
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
