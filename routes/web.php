<?php

/*
  |--------------------------------------------------------------------------
  | Authentication Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Auth::routes();

Route::group(['prefix' => 'provider'], function () {
    Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
    Route::post('/login', 'ProviderAuth\LoginController@login');
    Route::post('/logout', 'ProviderAuth\LoginController@logout');

    Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'ProviderAuth\RegisterController@register');

    Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');
    //route for verifying user email using token for provider
    Route::get('verifyemail/{token}', ['as' => 'verifyemail', 'uses' => 'TokenController@providerVerifyemail']);
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('index');
});

Route::get('/ride', function () {
    return view('ride');
});

Route::get('/drive', function () {
    return view('drive');
});

Route::get('privacy', function () {
    $page = 'page_privacy';
    $title = 'Privacy Policy';
    return view('static', compact('page', 'title'));
});
Route::post('fileUpload', 'EditorController@fileUpload');
/*
  |--------------------------------------------------------------------------
  | User Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Route::group(['middleware' => ['verified']], function () {
    Route::get('/dashboard', 'HomeController@index');

// user profiles
    Route::get('/profile', 'HomeController@profile');
    Route::get('/edit/profile', 'HomeController@edit_profile');
    Route::post('/profile', 'HomeController@update_profile');

// update password
    Route::get('/change/password', 'HomeController@change_password');
    Route::post('/change/password', 'HomeController@update_password');

// ride
    Route::get('/confirm/ride', 'RideController@confirm_ride');
    Route::post('/create/ride', 'RideController@create_ride');
    Route::post('/cancel/ride', 'RideController@cancel_ride');
    Route::get('/onride', 'RideController@onride');
    Route::post('/payment', 'PaymentController@payment');
    Route::post('/rate', 'RideController@rate');

// status check
    Route::get('/status', 'RideController@status');

// trips
    Route::get('/trips', 'HomeController@trips');

// wallet
    Route::get('/wallet', 'HomeController@wallet');
    Route::post('/add/money', 'PaymentController@add_money');

// payment
    Route::get('/payment', 'HomeController@payment');

// card
    Route::resource('card', 'Resource\CardResource');

// promotions
    Route::get('/promotion', 'HomeController@promotion');
    Route::post('/add/promocode', 'HomeController@add_promocode');

// upcoming
    Route::get('/upcoming/trips', 'HomeController@upcoming_trips');
});
//route for verifying user email using token
Route::get('verifyemail/{token}', ['as' => 'verifyemail', 'uses' => 'TokenController@verifyemail']);
// send push notification
Route::get('/send/push', function() {
    $data = PushNotification::app('IOSUser')
           ->to('6132781294b4978e2f1dd7e86440e4c0efbe3ee50ea56f64728a66eb8c8bb85c')
//           ->to('163e4c0ca9fe084aabeb89372cf3f664790ffc660c8b97260004478aec61212c')
            ->send('Hello World, i`m a push message');
    dd($data);
});
// test mail
Route::get('/send/mailmail', function() {
    $to = ["liuwc0026@outlook.com", "stec_khc@hotmail.com"];
    $userdata = "kakdkfj sdjfkalsjdfkj aksdfjklasd f";
    foreach($to as $email) {
        $record = ['data' => $userdata,
            'email' => $email,
            'subject' => 'Test Email',
        ];
        Mail::send('emails.blukemail', $record, function ($message) use ($record) {
            $message->from(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
            $message->to($record['email']);
            $message->replyTo(env('MAIL_FROM_ADDRESS', 'info@autocopain.com'), 'Autocopain');
            $message->subject($record['subject']);
        });
    }
    var_dump( Mail::failures());
    exit;
});

Route::get('/asdfasdf', function(){
    DB::enableQueryLog();
    // $RequestResult = DB::table('user_requests')->where('status','SCHEDULED')
    // ->where('schedule_at','<=',\Carbon\Carbon::now()->addMinutes(5))
    // ->get();
    // dd(DB::getQueryLog());
    // echo json_encode($RequestResult);
    $UserRequest = DB::table('user_requests')->where('status','SCHEDULED')
    ->where('schedule_at','<=',\Carbon\Carbon::now()->addMinutes(5))
    ->get();
    echo json_encode($UserRequest);
    dd(DB::getQueryLog());

    $hour =  \Carbon\Carbon::now()->subHour();
    $futurehours = \Carbon\Carbon::now()->addMinutes(5);
    $date =  \Carbon\Carbon::now();           

    \Log::info("Schedule Service Request Started.".$date."==".$hour."==".$futurehours);

    if(!empty($UserRequest)){
        foreach($UserRequest as $ride){
            $now = \Carbon\Carbon::now();
            DB::table('user_requests')
                ->where('id',$ride->id)
                ->update(['status' => 'STARTED', 'assigned_at'=>$now, 'schedule_at' => null ]);

                //scehule start request push to user
                (new SendPushNotification)->user_schedule($ride->user_id);
                //scehule start request push to provider
                (new SendPushNotification)->provider_schedule($ride->provider_id);

            DB::table('provider_services')->where('provider_id',$ride->provider_id)->update(['status' =>'riding']);
        }
        echo json_encode('success');
    }
    echo json_encode('success1'); exit;
});