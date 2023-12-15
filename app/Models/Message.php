<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema (
 *     schema="Message",
 *     title="schema of message",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="sender_id", type="integer")
 *
 * )
 *
 *
 */
class Message extends Model
{

    public function sender(){
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

}



