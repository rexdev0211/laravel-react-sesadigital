<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    
    protected $fillable = [
        'from_id','to_id', 'estate_id', 'notifi_type','category','title','message','photo','location','location_json','is_read','is_seen','is_show_guard'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function from() {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function saveNotification($data, $sendPushNotification=false){

        $model = new Notification();
        $model->from_id=$data['fromId'];
        $model->estate_id=$data['estateId'];
        if(isset($data['toId'])) $model->to_id=$data['toId'];
        $model->notifi_type=$data['notifiType'];
        $model->title=$data['title'];
        $model->message=$data['body'];
        if(isset($data['location'])) $model->location=$data['location'];
        $model->save();

        if($sendPushNotification && isset($data['token']) && $data['token']){
            $SERVER_API_KEY = 'AAAA3o7KZ98:APA91bF1RrRdEqpiyhB3t50Pwzn1pKtOGxwjsIY40JR_LNC5v4q0YPoX0Xa6Go_evjKLIKw3X2-_12f42r28WhuRv2UeyB8ImJDThFswMkk0RllX_MJR61O5LdEVhoo092jpZWxaB0eS';

            $data = [
                "registration_ids" => $data['token'],
                "notification" => [
                    "title" => $data['title'],
                    "body" => $data['body'] 
                ],
                "data" => [
                    "title" => $data['title'],
                    "body" => $data['body']  
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            // return $response;
        }
    }
}
