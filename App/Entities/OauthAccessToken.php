<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/12
 * Time: 2:17 PM
 */

namespace App\Entities;

use  Illuminate\Database\Eloquent\Model  as Eloquent;

class OauthAccessToken extends  Eloquent
{
    protected $fillable = [
        'id','user_id','client_id','name','scopes','revoked','created_at','updated_at','expires_at'
    ];
}