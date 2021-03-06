<?php

namespace App\Http\Controllers;

use App\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;


class NurseController extends Controller
{

    public function viewCard($id)
    {
        $nurse = Nurse::find($id);
        $person = new \stdClass();
        $person->name = $nurse->first_name. ' ' .$nurse->last_name;
        $person->job_title = 'Nurse at ' . $nurse->partner->first_name . ' '.$nurse->partner->last_name;
        $person->email = $nurse->contact_email;
        $person->phone = '+' .$nurse->contact_number;
        $person->photo = $nurse->photo;
        if (!empty($nurse))
            return view('extras.card')->with('person', $person);
    }

    public function index()
    {
        //
        if (Auth::user()->ableTo('view', Nurse::$model)) {

            if (Auth::user()->isAdmin() || Auth::user()->isCallCenter()) {

                $nurses = Nurse::all();

            } else {

                $nurses = Nurse::where('partner_id', Auth::user()->partner_id)->get();
            }


            return view('nurses.index')->with('nurses', $nurses);
        } else {
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
        return view('extra.404');

        if (Auth::user()->ableTo('add', Nurse::$model)) {

            return view('nurses.create');
        } else {
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
        return view('extra.404');
        if (Auth::user()->ableTo('add', Nurse::$model)) {

            $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'contact_email' => 'required|email|unique:nurses,contact_email',
                'contact_number' => 'required|string|max:10',
                'photo' => 'image|mimes:jpg,jpeg,png'
            ]);

            if ($request->has('partner_id')) {
                $nurses = $request->all();
            } else {

                $nurses = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
            }

            if($request->hasFile('photo')){
                $avatar = $request->file('photo');
                $filename = time(). '.' . $avatar->getClientOriginalExtension();
                //Image::configure(array('driver' => 'imagick'));
                Image::make($avatar)->resize(300, 300)->save( public_path('/upload/nurses/'.$filename));
                $nurses['photo'] = '/upload/nurses/'.$filename;
            }

            if ($request->has('full_number')) {
                $nurses['contact_number'] = str_replace('+', '', $request->full_number);
            }
            unset($nurses['full_number']);

            if (Nurse::create($nurses))
                return redirect(route('nurses.index'));


        } else {
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
        //
        if (Auth::user()->ableTo('view', Nurse::$model)) {

            $nurse = Nurse::find($id);

            if (empty($nurse)) {
                return redirect(route('nurses.index'));
            }

            return view('nurses.show')->with('nurse', $nurse);
        } else {
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
        //
        if (Auth::user()->ableTo('edit', Nurse::$model)) {

            $nurse = Nurse::find($id);

            if (empty($nurse)) {
                return redirect(route('nurses.index'));
            }else{
                $nurse->contact_number = (!empty($nurse->contact_number))? '+'.$nurse->contact_number:$nurse->contact_number;
            }

            return view('nurses.edit')->with('nurse', $nurse);
        } else {
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
        //
        if (Auth::user()->ableTo('edit', Nurse::$model)) {
            $nurse = Nurse::find($id);
            if(empty($nurse))
                return redirect(route('nurses.index'));

            $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'contact_email' => 'required|email|unique:nurses,contact_email,'. $nurse->id,
                'contact_number' => 'required|string',
                'photo' => 'image|mimes:jpg,jpeg,png'
            ]);



            if (empty($nurse)) {
                return redirect(route('nurses.index'));
            }

            if ($request->has('partner_id')) {
                $nurses = $request->all();
            } else {
                    $nurses = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
            }
            if($request->hasFile('photo')){
                $avatar = $request->file('photo');
                $filename = time(). '.' . $avatar->getClientOriginalExtension();
                //Image::configure(array('driver' => 'imagick'));
                Image::make($avatar)->resize(300, 300)->save( public_path('/upload/nurses/'.$filename));
                $nurses['photo'] = '/upload/nurses/'.$filename;
                // remove old image
                //unlink(asset($nurse->photo));
            }

            if ($request->has('full_number')) {
                $nurses['contact_number'] = str_replace('+', '', $request->full_number);
            }
            unset($nurses['full_number']);

            if ($nurse->update($nurses))
                return redirect(route('nurses.index'));

        } else {
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
        //
        if (Auth::user()->ableTo('delete', Nurse::$model)) {

            $nurse = Nurse::find($id);
            if (empty($nurse)) {
                return redirect(route('nurses.index'));
            }


            $nurse->delete($id);
            $nurse->doctors()->detach();

            return redirect(route('nurses.index'));
        } else {
            return view('extra.404');
        }
    }
}