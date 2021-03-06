<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\HotelGuest;
use App\Nurse;
use App\Order;
use App\Partner;
use App\PartnerType;
use App\Patient;
use App\Product;
use App\Transaction;
use App\User;
use App\GetUserGroup;
use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->ableTo('view', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {
            if (Auth::user()->user_group_id == 1) {
    
                $users = User::where('id', '!=', Auth::user()->id)->get();
    
            } elseif (Auth::user()->user_group_id == 2) {
             
                $users = User::where('partner_id', Auth::user()->partner_id)->where('user_group_id', '!=', 2)->get();
            }
            $specialities = ["Cardiology","Child Psychiatry","Dermatology-Venereology","Emergency Medicine","Endocrinology","Family Medicine","Gastroenterology","General Practice","General Surgery","Geriatrics","Infectious Disease","Internal Medicine","Neonatology","Nephrology","Neurology","Neurosurgery","Obstetrics and Gynaecology","Ophthalmology","Orthodontics","Orthopaedics","Other","Paediatrics","Pathology","Physiotherapy and Rehabilitation","Plastic Surgery","Psychiatry","Public Health","Pulmonology","Radiology","Sports Medicine","Urology","Vascular Medicine","Vascular Surgery"];

             return view('users.index')->with('users', $users)->with('specialites', array_unique($specialities));
        }else{
               return view('extra.404');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
      if (Auth::user()->ableTo('create', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {
          $specialities = ["Cardiology","Child Psychiatry","Dermatology-Venereology","Emergency Medicine","Endocrinology","Family Medicine","Gastroenterology","General Practice","General Surgery","Geriatrics","Infectious Disease","Internal Medicine","Neonatology","Nephrology","Neurology","Neurosurgery","Obstetrics and Gynaecology","Ophthalmology","Orthodontics","Orthopaedics","Other","Paediatrics","Pathology","Physiotherapy and Rehabilitation","Plastic Surgery","Psychiatry","Public Health","Pulmonology","Radiology","Sports Medicine","Urology","Vascular Medicine","Vascular Surgery"];

        return view('users.create')->with('specialites', array_unique($specialities));
      }else{
               return view('extra.404');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->ableTo('create', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {
            $request->validate([
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:50',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@$#%^&*]).*$/|confirmed',
                'password_confirmation'=>'',
                'user_group_id' => 'required',
                'avatar' =>'image|mimes:jpeg,png,jpg,gif',

            ],
                ['password.regex' => 'Your Password must contain at least 6 characters as (Uppercase and Lowercase characters and Numbers and Special characters). ',
                    'username.regex' => 'Username not allowing space',
                ]);
    
            if(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
                $request->validate(['partner_id'=> 'required|numeric']);
    
    
            if (!$request->has('partner_id'))
                $user = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
            else $user = $request->all();
    
            $user['password'] = Hash::make($user['password']);

            $user['contact_number'] = str_replace('+', '', $request->full_number);
            unset($user['full_number']);

            if($user['user_group_id'] == 1 || $user['user_group_id'] == 28 || $user['user_group_id'] == 29){
                $user['partner_id'] = null;
            }elseif($user['user_group_id'] == 31){
                $doctorData = $user;
                $doctorData['contact_email'] = $user['email'];
                (!$request->hasFile('avatar')? : $doctorData['photo'] = $user['avatar']);
                unset($doctorData['email']);
                unset($doctorData['avatar']);
                unset($doctorData['user_group_id']);
                unset($doctorData['password']);
                unset($doctorData['password_confirmation']);
            }elseif($user['user_group_id'] == 32){
                $nurseData = $user;
                $nurseData['contact_email'] = $user['email'];
                (!$request->hasFile('avatar')? : $nurseData['photo'] = $user['avatar']);
                unset($nurseData['email']);
                unset($nurseData['avatar']);
                unset($nurseData['user_group_id']);
                unset($nurseData['specialty']);
                unset($nurseData['nurses']);
            }
            unset($user['full_number']);
            unset($user['specialty']);
            unset($user['nurses']);
            if ($userr = User::create($user)){
                if($user['user_group_id'] == 31){
                    $doctorData['user_id'] = $userr->id;
                    if ($doctor = Doctor::create($doctorData)){
                        // Assign new nurses to doctor
                        $nurses = $request->nurses;
                        $doctor->nurses()->attach(array_unique($nurses));
                    }
                }elseif($user['user_group_id'] == 32){
                    $nurseData['user_id'] = $userr->id;
                    $nurse = Nurse::create($nurseData);
                }

                if($request->hasFile('avatar')){
                    $img = $request->file('avatar');
                    $filename = time(). '.' . $img->getClientOriginalExtension();
                    //Image::configure(array('driver' => 'imagick'));
                    Image::make($img)->save( public_path('/upload/users/'.$filename));
                    Image::make($img)->save( public_path('/upload/avatars/'.$filename));
                    $userr->avatar = '/upload/users/'.$filename;
                    $userr->save();

                    if($user['user_group_id'] == 31){
                        Image::make($img)->save( public_path('/upload/doctors/'.$filename));
                        $doctor->photo = '/upload/doctors/'.$filename;
                        $doctor->save();
                    }elseif($user['user_group_id'] == 32){
                        Image::make($img)->save( public_path('/upload/nurses/'.$filename));
                        $nurse->photo = '/upload/nurses/'.$filename;
                        $nurse->save();
                    }
                }

                return redirect(route('users.index'));

                // remove old image
            }else{
                return back();
            }
        }else{
               return view('extra.404');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       if (Auth::user()->ableTo('view', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {

        $user = User::find($id);

        if (empty($user)) {
            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
       }else{
           return view('extra.404');
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->ableTo('edit', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {

        $user = User::find($id);

        if (empty($user)) {
            return redirect(route('users.index'));
        }

        //dd($user);
        return view('users.edit')->with('user', $user);
        }else{
           return view('extra.404');
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->ableTo('edit', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {
        $user = User::find($id);

        if (empty($user)) {
            return redirect(route('users.index'));
        }
        
        $request->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'required|unique:users,email,' . $id ,
            'user_group_id' => 'required',
            'avatar' =>'image|mimes:jpeg,png,jpg,gif',
        ]);

        if(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
            $request->validate(['partner_id'=> 'required']);

        if($request->has('password') && !empty($request->password ))

            $request->validate([
                'password' => 'min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@$#%^&*]).*$/|confirmed',
                'password_confirmation'=>''
            ],['password.regex' => 'Your Password must contain at least 6 characters as (Uppercase and Lowercase characters and Numbers and Special characters). ']);



      

        $data =array();
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['user_group_id'] = $request->user_group_id;
        
        if($request->user_group_id == 1 || $request->user_group_id == 28 || $request->user_group_id == 29){
            $data['partner_id'] = null;
        }elseif(Auth::user()->isAdmin() || Auth::user()->isCallCenter())
            $data['partner_id'] = $request->partner_id;
        else
            $data['partner_id'] = Auth::user()->partner_id;

        if (isset($request->password)) {
            $data['password'] = Hash::make($request->password);
        }


        $user->update($data);
        if($request->hasFile('avatar')){
            $img = $request->file('avatar');
            $filename = time(). '.' . $img->getClientOriginalExtension();
            //Image::configure(array('driver' => 'imagick'));
            Image::make($img)->save( public_path('/upload/users/'.$filename));
            Image::make($img)->save( public_path('/upload/avatars/'.$filename));
            $user->avatar = '/upload/avatars/'.$filename;
            $user->save();
        }
        return redirect(route('users.index'));
        }else{
            return view('extra.404');

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->ableTo('delete', User::$model) || Auth::user()->user_group_id == 1 || Auth::user()->user_group_id == 2) {

        $user = User::find($id);

        if (empty($user)) {
            return redirect(route('users.index'));
        }


        $user->delete($id);

        return redirect(route('users.index'));
        }else{
          return view('extra.404');
 
        }

    }


    public function getUserGroups($id) {

            $usergroups = UserGroup::where("partner_id",$id)->pluck("group_name","id");


        if(!empty($usergroups) && count($usergroups) > 0)
            return response()->json(['success'=>true, 'data'=>$usergroups], 200);
        return response()->json(['success'=>false], 200);
    }

    public function notifications()
    {
        return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
    }
    public function threads()
    {
        return auth()->user()->unreadMeessages()->limit(5)->get()->toArray();
    }
}
