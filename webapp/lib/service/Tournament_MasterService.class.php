<?php

class Tournament_MasterService extends Service {

    // IDの値。
    const TOUR_MAIN = 1;
    const TOUR_QUEST = 2;


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'tournament_id';

    protected $isMaster = true;
}
