<?php

class TestAction extends SwfBaseAction {

    protected function doExecute() {

        if(ENVIRONMENT_TYPE == 'prod')
            throw new MojaviException('本番でテストは実行できません');


    }
}
