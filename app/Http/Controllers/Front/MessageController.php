<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addMessageRequest;
use App\Mail\DynamicEmail;
use App\Models\Message\Message;
use App\Models\Site\Site;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;


class MessageController extends ESUBaseController
{
    public function sendMessage(addMessageRequest $request)
    {
        $site = Site::getSite($request->site_id);
        $config = array(
            'driver' => 'smtp',
            'host' => $site->mail_host,
            'port' => $site->mail_port,
            'username' => $site->mail_username,
            'password' => $site->mail_password,
            'encryption' => 'ssl',
            'from' => array('address' => $site->mail_username, 'name' => $site->mail_username),
        );
        Config::set('mail', $config);
        $toEmail = $site->receive_mail_address;
        $body = array(
            'name' => $request->name,
            'email' => $request->email_address,
            'comment' => $request->comment,
        );
        Mail::to($toEmail)->queue(new DynamicEmail($body));
        $messageId = Message::addMessage($request);
        $message = Message::getMessage($messageId);
        $data = $message;
        $code = 1;
        $msg = 'success';
        return $this->response($code, $data, $msg);
    }
}
