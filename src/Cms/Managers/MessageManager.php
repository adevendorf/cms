<?php
namespace Cms\Managers;

use Mail;

class MessageManager
{


    public static function send($data)
    {
        Mail::send('themes.' . config('cms.theme') . '.emails.form', $data, function ($message) use ($data) {
            $message->from(config('cms.contact_email'), config('cms.contact_email'));
            $message->to(config('cms.contact_email'));
            $message->subject('TEST Form ID: ' . $data['form']->id);
        });
    }
}