<?php

namespace App\Http\Controllers;

use App\User;
use KS\Line\LineNotify;
use App\Http\Controllers\GlobalConstant;

class LineNotiController extends Controller
{
    public function auth_line()
    {
        $client_id = env('LINE_CLIENT_ID');
        $api_url = env('LINE_URL');
        $callback_url = env('LINE_CALLBACK_URL');

        $query = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'scope' => 'notify',
            'state' => 'mylinenotify',
        ];

        $result = $api_url . http_build_query($query);

        return $result;
    }

    public function auth_callback()
    {
        if ($_GET['code']) {
            $code = $_GET['code'];

            $client_id = env('LINE_CLIENT_ID');
            $client_secret = env('LINE_CLIENT_SCRET');
            $api_url = 'https://notify-bot.line.me/oauth/token';
            $callback_url = env('LINE_CALLBACK_URL');

            $fields = [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $callback_url,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ];

            try {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $res = curl_exec($ch);
                curl_close($ch);

                if ($res == false) {
                    error_log('error');
                }

                $json = json_decode($res);
                error_log($json->access_token);

                $username = auth()->user()->username;
                $user = User::where('username', $username)->update(['line_token' => $json->access_token]);

                error_log($user);

                if ($user) {
                    alert()->success(GlobalConstant::$SUCCESS_TITLE, 'สมัครรับการแจ้งเตือนผ่าน Line noti เรียบร้อยแล้ว');
                    return redirect()->route('user.detail');
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function notify($token = null)
    {
        if ($token) {
            $api_url = 'https://notify-api.line.me/api/notify';

            $headers = [
                'Authorization: Bearer ' . $token,
            ];
            $fields = [
                'message' => 'ทดสอบการส่งข้อความไปยังผู้ใช้งาน ',
            ];

            try {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $res = curl_exec($ch);
                curl_close($ch);

                if ($res == false) {
                    error_log('error');
                }

                $json = json_decode($res);
                //$status = $json->status;

                //var_dump($status);
            } catch (\Throwable $th) {
                throw $th;
            }
            return view('show_token')->with('ok', $json);
        }
    }

    public function test_noti()
    {
        $token = auth()->user()->line_token;
        if ($token) {
            $ln = new LineNotify($token);
            $ln->send('ทดสอบส่ง noti');
        }
    }
}
