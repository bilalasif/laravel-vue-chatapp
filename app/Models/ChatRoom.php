<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    
    public function messeges(){
        return $this->hasMany('App\Models\ChatMessage');
    }
}
