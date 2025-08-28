<?php

class Drama_MasterService extends Service {

    // drama_id の値を表す定数。
    const PROLOGUE =   9900001;         // プロローグ
    const TUTORIAL0 =  9900002;         // チュートリアル用
    const TUTORIAL30 = 9900003;         //
    const TUTORIAL40 = 9900004;         //
    const TUTORIAL60 = 9900005;         //
    const TUTORIAL90 = 9900006;         //


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'drama_id';

    protected $isMaster = true;
}
