<?php

namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\{
    DsLogin,
    DsMember
};

use FLY\Libs\Request;

class UserAccount
{
    static function create(Request $request)
    {
       return (new CreateAccount($request))->create();
    }

    static function update(Request $request)
    {
        return (new UpdateAccount($request))->update();
    }

    static function quarantine(Request $request)
    {
        if((DsLogin::get($request->userId))->username <> '@ai_admin_lapi'){
            $model = new DsMember($request->userId);
            $model->accountStatus = 'quarantined';
            $model->edit();
            $res = ['state' => true];
        } else {$res = ['state' => false];}
        return $res;
    }
}