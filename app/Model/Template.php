<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

use App\Email\SendEmail;

class Template extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'name','template_type','subject_tags','subject', 'template_tags','template','slug','status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function sendEmail($data){
        $data['tags']['{SITE_TITLE}'] = config('site_vars.siteTitle');
        $templateId = ($data['templateId'] && $data['templateId']>0)?$data['templateId']:0;
        $template = Template::where([['id','=',$data['templateId']],['status','=','active']])->get()->first();
        
        if($template){
            $subject = $template->subject;
            if($template->subject_tags){
                $subjectTags = explode(',',$template->subject_tags);
                foreach($subjectTags as $value){
                    $subject = str_replace($value,$data['tags'][trim($value)],$subject);
                }
            }
            $templateTags = explode(',',$template->template_tags);
            $templateBody = $template->template;
            foreach($templateTags as $value){
                $templateBody = str_replace($value,$data['tags'][trim($value)],$templateBody);
                $templateBody = nl2br($templateBody);
            }
            $templateBody = str_replace('<br/>','\n',$templateBody);
            $mailData = [
                'subject'=>$subject,
                'body'=>$templateBody,
            ];
            return Mail::to($data['to'])->send(new SendEmail($mailData));
        }
    }

    public function sendSMS($data){
        $data['tags']['{SITE_TITLE}'] = config('site_vars.siteTitle');
        $templateId = ($data['templateId'] && $data['templateId']>0)?$data['templateId']:0;
        $template = Template::where([['id','=',$data['templateId']],['status','=','active']])->get()->first();
        
        if($template){
            $estateId = isset($data['estateId'])?$data['estateId']:0;
            $smsSettingModel = SmsSetting::where('status','=','active');
            $smsSettingModel->where('estate_id','=',$estateId);
            if($estateId && isset($data['userId'])){
                if(isset($data['userId']) && ($data['userId'] && $data['userId']>0)){
                    $smsSettingModel->where('user_id','=',$data['userId']);
                }
            }            
            
            $smsSetting = $smsSettingModel->get()->first();
            if(!$smsSetting){
                return false;
            }

            $templateTags = explode(',',$template->template_tags);
            $templateBody = $template->template;
            foreach($templateTags as $value){
                $templateBody = str_replace($value,$data['tags'][trim($value)],$templateBody);
            }

            $message = str_replace('&nbsp;','',$templateBody);
            $message = str_replace('</div>','<br/>',$message);
            $message = strip_tags($message);

            $request_data = array();
            $request_data['username'] = $smsSetting->api_username;
            $request_data['password'] = $smsSetting->api_password;
            $request_data['message'] = $message;
            $request_data['sender'] = $smsSetting->sender_id;
            $request_data['phone_numbers'] = $data['phones'];

            $url = $smsSetting->api_url;
            $client = new Client();

            $response = $client->post($url, [
                'verify'    =>  false,
                'form_params' => $request_data,
            ]);

            $response = $response->getBody();   

            $logs = array();
            $logs['estate_id'] = $estateId;
            $logs['template_id'] = $template->id;
            if($data['toId']) $logs['to_id'] = $data['toId'];
            if($data['toIdName']) $logs['to_name'] = $data['toIdName'];
            $logs['phone'] = $data['phones'];
            $logs['request_data'] = json_encode($request_data);
            $logs['response_data'] = $response;
            DB::table('sms_logs')->insert($logs);
            return true;
        }
    }

    public function sendSMSWithoutTemplate($data){
        $estateId = (isset($data['estateId']) && $data['estateId']>0)?$data['estateId']:0;
        $messageId = (isset($data['messageId']) && $data['messageId']>0)?$data['messageId']:'';
        $smsSettingModel = SmsSetting::where('status','=','active');
        if($estateId)
            $smsSettingModel->where('estate_id','=',$estateId);
        
        $smsSetting = $smsSettingModel->get()->first();

        if(!$smsSetting) return false;
        if(!isset($data['phones']) || empty($data['phones'])) return false;

        $messageBody = $data['message'];

        $message = str_replace('&nbsp;','',$messageBody);
        $message = str_replace('</div>','<br/>',$message);
        $message = strip_tags($message);

        $request_data = array();
        $request_data['username'] = $smsSetting->api_username;
        $request_data['password'] = $smsSetting->api_password;
        $request_data['message'] = $message;
        $request_data['sender'] = $smsSetting->sender_id;
        $request_data['phone_numbers'] = $data['phones'];

        $url = $smsSetting->api_url;
        $client = new Client();

        $response = $client->post($url, [
            'verify'    =>  false,
            'form_params' => $request_data,
        ]);

        $response = $response->getBody();   

        $logs = array();
        $logs['estate_id'] = $estateId;
        $logs['message_id'] = $messageId;
        if(isset($data['toId']) && $data['toId']) 
            $logs['to_id'] = $data['toId'];
        if(isset($data['toIdName']) && $data['toIdName']) 
            $logs['to_name'] = $data['toIdName'];
        $logs['phone'] = $data['phones'];
        $logs['request_data'] = json_encode($request_data);
        $logs['response_data'] = $response;
        DB::table('sms_logs')->insert($logs);
        return true;
    }

    public function sendEmailWithoutTemplate($data){
        $estateId = (isset($data['estateId']) && $data['estateId']>0)?$data['estateId']:0;
        $messageId = (isset($data['messageId']) && $data['messageId']>0)?$data['messageId']:'';

        $subject = (isset($data['subject']) && $data['subject']>0)?$data['subject']:config('site_vars.siteTitle');
        $message = (isset($data['message']) && $data['message']>0)?$data['message']:config('site_vars.siteTitle');
        
        $mailData = [
            'subject'=>$subject,
            'body'=>$message,
        ];
        $emailLogModel = new EmailLog();
        $emailLogModel->estate_id = $estateId;
        $emailLogModel->message_id = $messageId;
        if(isset($data['toId']) && $data['toId']) 
            $emailLogModel->toId = $data['toId'];
        if(isset($data['toName']) && $data['toName']) 
            $emailLogModel->toName = $data['toName'];
        $emailLogModel->email = $data['to'];
        $emailLogModel->save();
        return Mail::to($data['to'])->send(new SendEmail($mailData));
    }
}
