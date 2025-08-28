ここではフィールド定義と sphere_info.state のデータ構造について説明する。

フィールド定義は以下のような構造を持つ。

    extra_uniticons     追加使用するユニットアイコンの配列。
                        ただし、"avatar" のアイコンは標準で追加される。
                        各ルームの下に配置して、個別に適用することも可能。
    extra_maptips       追加使用するマップチップ番号の配列。
                        ルーム構造に始めから使用されているマップチップは自動で読み込まれるが、
                        そうではないチップを動的に使用する可能性がある場合はここで指定する。
                        各ルームの下に配置して、個別に適用することも可能。
    global_gimmicks     全ルーム共通のギミックがある場合は追加する。
                        各ルームでのローカルギミックと名前が重複している場合はローカルギミックのほうが優先される。
    hero_unit           主人公ユニットのプロパティに特殊な指定を行いたい場合に指定する。
    start_units         ルームチェンジしても引き継ぐユニットを指定する。指定の仕方は rooms.units と同じ。

    rooms               フィールドを構成する各ルームについて...
        (インデックス)      ルーム名。開始ルームとして必ず "start" のインデックス値を含めること。
                            値は以下のキーを持つ配列だが、単一文字列値を指定して別のファイルを読み込ませることもできる。
        id                  ルームID
        battle_bg           そのルームにおけるバトルのバックイメージ。クエスト毎でいいかと思ったがルームごとで場面が結構変わるので・・。
        map_class           マップユーティリティの名前。
                            省略、カラ文字列は標準のものであることを表す。
        start_pos           主人公ユニットのスタート位置
        start_pos_from      他のルームから遷移した場合のスタート位置
                            該当の値がない場合は start_pos が使用される。
            (インデックス)      前のルーム名
            (値)                スタート位置
        gimmicks            セットされているギミック(寸劇、トレジャー等)
            (インデックス)      ギミックID。自由文字列
            trigger             起動のきっかけとなる要素。以下のいずれか。
                                ロジックによってしか発動しないなら省略可能。
                                    hero        主人公ユニットの進入
                                    player      プレイヤー配下ユニットの進入
                                    all         いずれかのユニットの進入
                                    curtain     暗幕の開放。"curtain" キーでその暗幕の名前を指定する。
                                                配列での複数指定も可能。"*" ですべての暗幕になる。
                                    rotation    ターンの開始。"rotation" キーで発動ターンを指定する。
                                                配列での複数指定も可能。"*" ですべてのターンになる。
                                    termination 敵ユニットの一定数の打倒(退出含む)。"termination" キーでその数を指定する。
                                                配列での複数指定も可能。"*" ですべての打倒が対象になる。
                                                ギミックが起動したとき、すでに退出していることに留意。トリガユニットは退出させたユニット。
                                    unit_exit   特定のユニットの退出。"unit_exit" キーでそのユニットのcode値を指定する。
                                                配列での複数指定も可能。"*" ですべてのユニットになる。
                                                ギミックが起動したとき、すでに退出していることに留意。トリガユニットは退出させたユニット。
                                    unit_into   特定のユニットの進入。"unit_into" キーでそのユニットのcode値を指定する。
                                                配列での複数指定も可能。
            pos                 セットされている座標。[0]:X [1]:Y
                                ユニットの進入によって発動しないなら省略できる。
            rb                  発動範囲が1マスでない場合に右下の座標。"right-bottom" の略。
            mask                room_master.mats の "mask" と同様。
            lasting             ここで設定されている回数分発動するまでギミックが残り続ける。
            always              trueに評価される値の場合はユニットがとどまっているだけでも毎ターン発動する。
                                lastingを設定しておかないと、ギミックそのものが消えてしまうので注意。
            condition           ギミックの設置に条件に持たせるときに使う。
                                下記の条件名をキーに持つが、条件名の先頭に "!" をつけると反対の条件になる。
                cleared             trueなら、このクエストをクリアしたことがあるときのみ。falseなら逆。
                reason              この部屋にきた理由が、ここで指定した値と一致するときのみ。配列での複数指定も可能(OR)。
                mission             trueなら、ミッションが存在するときのみ。falseなら逆。
                yet_flag            設置の是非を保持するフラグID。このフラグがOFFなら設置する。
                                    flog_log.flag_group=6 のレコードを参照する。
                has_flag            "!yet_flag" と同じ
                yet_memory          yet_flagと同様だが、flag_logテーブルではなく
                                    sphere_info.state.memory を使う。
                has_memory          "!yet_memory" と同じ
                unit_exist          ここで指定したcodeを持つユニットが存在していること。配列での複数指定も可能(OR)。
                unit_nonexist       ここで指定したcodeを持つユニットが存在していないこと。配列での複数指定も可能(OR)。
                unit_alive          ここで指定したcodeを持つユニットのHPが0より大きいこと。配列での複数指定も可能(OR)。
                                    unit_exist はHPが0でもまだ存在していればマッチするが、unit_alive はマッチしない。
                igniter             ここで指定したcodeを持つユニットがギミックを起動していること。配列での複数指定も可能(OR)。
                call                指定のメソッドをコールして判断する。
                                    ここで指定するメソッドは以下の引数・戻り値仕様であること。
                                        第一引数    このギミック
                                        第二引数    設置の場合は、画面遷移した場合は遷移を引き起こしたギミック名
                                                    起動の場合はトリガーユニットのcodeの値
                                        戻り値      設置の可否。設置不可ならfalseを返す。
            ignition            起動時、ここで指定する条件を満たしていないと、起動しない。
                                条件の指定の仕方は condition と同じ。
                                conditionが設置時に判定されるのに対して、ignitionは起動時に判定される。
            flag_on             ギミックが発動したら、このIDでフラグを立てる。
                                flog_log.flag_group=6 のレコードで保持する。
            one_shot            flag_logレコードを使って、ゲーム全体を通して一回限りの起動であるようにする。
                                flag_on と condition.yet_flagを同時に同じ値で指定するときの省略形。
            memory_on           flag_onと同様だが、flag_logテーブルではなく sphere_info.state.memory を使う。
            memory_shot         同じく、one_shotのmemory版。
            ornament            このギミックを表示したいときに使用する置物のtype
            type                ギミックの種類。カラのギミック、あるいはロジックで処理するなら省略できる。
                                以下のいずれか。それぞれ付属のキーが追加される。
                                    drama       寸劇
                                        drama_id    寸劇ID
                                    treasure    トレジャーゲット
                                        item_id     アイテムID
                                        gold        お金
                                        treasure_catcher
                                                    通常、ギミックを起動したユニットがアイテムを所持するが、
                                                    この値が指定されている場合は、このコードを持つユニットが所持するようになる。
                                    ace_card    切り札(そのスフィア内だけで有効なもの)ゲット
                                        user_item_id     ユーザアイテムID
                                        treasure_catcher
                                                    通常、ギミックを起動したユニットがアイテムを所持するが、
                                                    この値が指定されている場合は、このコードを持つユニットが所持するようになる。
                                    ap_recov    行動ptの回復
                                    hp_recov    HPの回復
                                    lead        指揮
                                        leads       指揮の配列。以下の記述がある場合は置き換えが行われる。
                                                        %xxx%       コード xxx のユニットの番号に置き換えられる。
                                                        [NAME]      主人公キャラの名前に置き換えられる。
                                                    [※以下は廃止予定の仕様。代わりに type:square_change を使用する。]
                                                    RPBG1コマンドを発行する場合は以下の形式で記述する。
                                                        RPBG1 05 04 $0304
                                                    "$0304" はサーバー側チップ番号。あらかじめ読み込まれているものでなければならない。
                                        swf_return  trueに評価される値の場合、トリガが発動したあと一度フラッシュに制御を戻す。省略時はfalse。
                                    square_change   特定の座標のマップチップを変更する。
                                        change_pos      変更する座標。
                                        change_tip      変更後のチップ番号
                                    unit        ユニットの追加
                                        unit        追加するユニット。unitsの値と同様。
                                        quiet       trueに評価される値の場合、登場時のフォーカス、解説などが省略される。省略時はfalse。
                                    property    ユニットのプロパティ変化
                                        unit            対象ユニットのcode値。配列での複数指定も可能。
                                        change          変更するプロパティと値を列挙した配列。
                                    call        メソッドのコール
                                        call        コールするメソッド名。
                                                    ここで指定するメソッドはfireGimmick()と同じ引数リストと戻り値仕様でなければならない。
                                    unit_event      ユニットにイベントを送信する。
                                        target_unit     対象のユニットのcode値。省略した場合は起動したユニット。
                                        event           送信するイベント。詳細は SphereUnit::event() を参照。
                                    unit_exit   ユニットの退出
                                        exit_target     退出対象のユニットのcode値。省略した場合は起動したユニットが退出する。
                                        exit_reason     退出方法。"collapse":死亡、"room_exit":撤退・逃亡 のいずれか。
                                                        省略時は "room_exit"。
                                    goto        ルームチェンジ
                                        room        遷移先のルーム名
                                    escape      クエストを終了させる。
                                        escape_result   終了の種類 "success", "escape", "failure" のいずれか。
                                                        省略時は "escape"。
                                    goal        クエストを成功終了させる。廃止予定。
            chain               このギミックが発動したとき、ここで指定したインデックス値のギミックが続けて発動する。
                                配列で複数指定も可能。
                                ignition の判定で起動しなかった場合は処理されない。
            chain_delayed       chain と同様だが、イベントキューを使用して起動するので発動タイミングがすこし遅れる。
            touch               chain と同様だが、ギミックの起動よりも前に処理される。
                                ignition の判定よりも前に処理されることに留意。
            rem                 設定用のコメント。無視される。
            switch              条件によってギミックの内容を変更したい場合に使う。任意の要素数を持つ序数配列で、
                                先頭要素から順次追加判定して、最初に追加に成功したものが採用される。
                                例) クリアしているかどうかを表示するギミック
                                    {
                                        "type": "lead"
                                      , "switch": [
                                            {
                                                "condition": {"cleared":false}
                                              , "leads": ["NOTIF まだクリアしてないよ"]
                                            }
                                          , {
                                                "condition": {"cleared":true}
                                              , "leads": ["NOTIF もうクリアしてるよ"]
                                            }
                                        ]
                                    }
                                "switch" で挙げた要素のいずれも追加できない場合はギミック自体追加されない。
        ornaments           設置されている置物。ないなら省略可能。
            pos                 セットされている座標。
            type                置物の種類。以下のいずれか
                                    twinkle     きらめき
                                    goto        移動をしめす矢印
                                    goto2       特殊な移動をしめす矢印
                                    curious     発見を表す矢印
                                    escape      出口を表す記号
                                    ap_circle   青い円
                                    hp_circle   赤い円
        units               配置されているユニット。初期配置ユニットが一体もいないなら省略可能。
            condition           ユニットの設置に条件に持たせるときに使う。
                                gimmicks.condition と同様。
            unit_class          ユニット操作クラスの名前。
                                省略、カラ文字は標準のものであることを表す。
            code                ユニットのコードネーム。特定のユニットを探すためにつける。省略可能。
                                値 "avatar" はプレイヤーアバターのために予約されている。
            union               所属番号。1:プレイヤー、2:モンスター
                                省略時は2。
            icon                ユニットアイコン名
            pos                 位置している座標
            character_id        キャラクタID
            act_brain           行動の決定の仕方。標準で実装されているのは以下のいずれか。
                                それぞれ、追加のユニットプロパティが発生する。
                                    manual      プレイヤー操作
                                    generic     万能型
                                    rest        その場で待機
                                    target      狙ったユニットを攻撃する。
                                        target_unit     優先的に攻撃対象とするユニットのcode。
                                        target_union    優先的に攻撃対象とする所属番号。
                                                        target_unitと同時に指定した場合はtarget_unitのほうが優先的に思考される。
                                    destine     設定した座標を目指す。
                                        destine_pos     目標座標
                                    keep        設定した座標をキープする。周辺4マス以内で行動可能な選択があればそうするが、
                                                それより外には出ない。
                                        keep_pos        目標座標
                                    guard       設定したユニットを護衛する。ユニット周辺3マス以内で行動可能な選択があれば
                                                そうするが、それより外には出ない。
                                        guard_unit      対象ユニットのcode
                                以下の追加プロパティは共通で使える
                                    brain_noattack      敵に攻撃しない。
                                    brain_item_orient   アイテムの使用を優先して思考する。
                                                        ちゃんと対応しているのは今のところ generic のみ。
                                unit_classを "ExBrains" に設定することで利用可能な思考ルーチンもある。詳細は
                                SphereUnitExBrains を参照。
            items               所持しているアイテムのuser_item_idの配列。省略可能。
            sequip              スフィア上で装備しているuser_item_idの配列。省略可能。
                                指定すると、クエスト上で実際に持っているかのように振舞うが、
                                total_attack1 などのステータスには反映されない。
                                character_equipmentで装備を持っているのに sequip も指定するようなケースは想定していない。
            reward_exp          倒したときに得られる基本経験値
            reward_gold         倒したときに得られるお金
            trigger_gimmick     退出したとき、ここで指定されたギミックを起動する。配列での複数指定も可能。
            early_gimmick       trigger_gimmickと同じだが、ユニットが退出する直前にギミックが処理されるので、セリフの表示などが可能。
            name                ユニットの名前。省略した場合は character_id から取得される。
            battle_brain        バトル時のブレインレベル。0～100。省略した場合は character_id から取得される。
            move_pow            移動力。省略した場合は character_id から取得される。
            transcend_adapt     超越レベル調整を受けるかどうか。省略時はtrue。
                                値が true でも、quest_master に想定上限レベルが設定されていないと機能しないので注意。
			add_level			任意の敵のレベルを追加する。最大HPは追加レベル×3、その他パラメータも×1.5(山内追加)
            x_XXX               フィールドやルームによって任意に追加されるもの。
                                そのまま sphere_info.state.units に引き継がれる。
                                character_info や unit_master にも存在する列は同名の値を上書きする。
        x_XXX               フィールドやルームによって任意に追加される。


sphere_info.state は次のような構造を持つ。

    cleared         このフィールドクエストをクリアしたことがあるかどうか
    try_count       このフィールドクエストの挑戦回数
    rotation_all    クエスト開始からのターン数
    rotation        ルーム開始からのターン数
    memory          スフィア単位で保持するフラグ
    termination_all クエスト開始からの倒した敵の数(退出も含む)
    termination     ルーム開始からの倒した敵の数(退出も含む)
    mission_exists  ミッションが存在するかどうか
    mission_achieve ミッションを達成したかどうか
    treasures       クエスト中に手に入れたアイテムのIDの配列。
    story_before    直前の粗筋の内容。
    current_room    プレイヤーが居るルーム名
    change_room     ルームチェンジマーク。
                    次のターンチェンジ時、ルームチェンジが行われる。
    scene           現在のシーン。以下の値のいずれか。
                        field   フィールド
                        battle  バトル
                        drama   寸劇
    scene_id        scene=="battle"の場合   攻撃をしかけられたユニットのインデックス番号
                    scene=="drama"の場合    寸劇ID
    scene_trigger   scene=="battle"の場合   攻撃をしかけたユニットのインデックス番号
                    scene=="drama"の場合    寸劇の元になってるギミック名
    map_class       マップユーティリティの名前。
                    カラ文字列は標準のものであることを表す。
    structure       マップの構造。2次元配列で、値はmaptipsのインデックス値。
    maptips         マップチップのマスタ
        (インデックス)  1から始まる序数番号
        graph_no        グラフィック番号
        cost            移動コスト
    act_unit        現在行動中のユニット番号。
                    0 はルーム開始直後であることを表す。
    act_phase       行動の処理段階。以下の値のいずれか。
                        precomm     コマンド処理前
                        command     コマンド処理中
                        aftercomm   コマンド処理後、ギミックチェック直前
                        turnend     ギミックチェック終了後
    command         "command" フェーズで実行されるユニットコマンド。
                    次の構造を持つ。
                        move        移動する場合のみ存在する。
                            to          移動先座標。
                            path        移動するときのルート。省略可能
                        use         アイテムを使用する場合のみ存在する。
                            to          使用先座標。
                            page        装備品を使おうとしている場合は"equip"
                                        アイテムを使おうとしている場合は"item"
                            slot        使用対象のスロット番号
                            uitem       使用するユーザアイテムレコード
                        attack      攻撃の場合のみ存在する。攻撃対象のユニット番号。
                    "precomm" フェーズでリセットされる。
    gimmicks        セットされているギミック。field_masterのものと同じだが...
                        ・yet_flag, remキーはない。
                        ・ornament キーはなくなって、代わりに ornNo が追加される。
    ornaments       配置されている置物。field_masterのときと同じもの。
    units           存在しているユニット
        (インデックス)  ユニットID。1以上の数値。
        no              ユニット番号。インデックス値と同じ値。
        code            プレイヤーアバターの場合は "avatar" がセットされる。
                        field_master.composition.units に同じキーがある場合は引き継がれる。
        player_owner    プレイヤー配下のユニットかどうか。
                        この値が true のユニットは暗幕の解除、トレジャーの取得、死亡回数のカウントアップなどが行われる。
        turn            登場から何ターン目か
        その他          field_master.composition.units のキーはそのまま受け継がれる。
    unit_icons      ユニットアイコンの対応表
        (インデックス)  サーバ側アイコン名
        (値)            SWF側番号
    item_table      アイテムIDの対応表
        (インデックス)  サーバ側 user_item_id
        (値)            SWF側番号
    queue           ステートイベントキュー
        type            イベントのタイプ。以下の値のいずれか。
                            unit        ユニットに対して働くイベント
                            lead        指揮内容
                            gimmick     ギミックの発動
                            close       スフィアの終了
                            battle      バトルの終了。廃止。
                            battle2     バトルの終了。
                            call        メソッドのcall
        type:"unit" の場合
            no          type:"unit" の場合に、ユニット番号。
            name        イベント名
            reason      name:"exit" の場合に、退出方法。
                            collapse    死亡
                            room_exit   撤退・逃亡
            trigger     イベントの原因になったユニットがいる場合に、その番号。
        type:"lead" の場合
            leads       type:"lead" の場合に、指揮の配列。
            return      SWFへのリターンが必要ならtrue。省略可能。
        type:"gimmick" の場合
            name        発動したギミックの名前
            trigger     発動させたユニットの番号。いない場合は 0
        type:"close" の場合
            result      終了コード。Sphere_InfoServiceで定義されている定数のいずれか。
        type:"battle" の場合。
            challenger  挑戦側ユニットについて
                no          ユニット番号
                resultHp    バトル後のHP
            defender    同じく、挑戦側ユニットについて
        type:"battle2" の場合。
            challenger      挑戦側ユニット番号
            defender        防衛側ユニット番号
            flow            バトルの詳細内容。totalがある場合は省略可能。
            total           バトルの総合内容。flowがある場合は省略可能。
                challenger      挑戦側が受けたダメージ
                defender        防衛側が受けたダメージ
            reward_proc     経験値・ローカル通貨の付与処理を既に行ったかどうか。
        type:"call" の場合。
            call            コールするメソッド名。このメソッドはSphereCommon.progressEvent()と
                            同じ引数・戻り値仕様でなくてはならない。
        その他          イベントによって任意に追加される。
    x_XXX           フィールドやルームによって、任意に追加される。
