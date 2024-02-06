<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    public static function addMessage($data)
    {
        $message = new Message();
        $message->name = $data['name'];
        $message->email_address = $data['email_address'];
        $message->comment = $data['comment'];
        $message->save();
        return $message->id;
    }

    public static function getMessage($id)
    {
        return self::query()->find($id);
    }
}
