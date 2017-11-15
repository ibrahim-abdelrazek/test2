<?php

namespace App\Http\Controllers;

use App\HotelGuest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HotelGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->ableTo('view', HotelGuest::$model)) {
            if (Auth::user()->isAdmin()) {

                $hotelguests = HotelGuest::all();

            } elseif (Auth::user()->user_group_id == 2) {

                $hotelguests = HotelGuest::where('partner_id', Auth::user()->partner_id)->get();

            } else {

                $hotelguests = HotelGuest::where('user_id', Auth::user()->id)->get();
            }

            return view('hotelguest.index')->with('hotelguests', $hotelguests);
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
        //
        if (Auth::user()->ableTo('add', HotelGuest::$model)) {

            return view('hotelguest.create');
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
        //
        if (Auth::user()->ableTo('add', HotelGuest::$model)) {

            $request->validate([
                'name' => 'required|string|max:100',
                'officer_name' => 'required|string|max:100',
                'contact_number' => 'required|string|max:100',
                'guest_room_number' => 'required|string|max:100',
                'guest_first_name' => 'required|string|max:100',
                'guest_last_name' => 'required|string|max:100',
                'items' => 'required|string|max:100',
            ]);

            if ($request->has('partner_id')) {
                $guest = $request->all();
            } else {
                if (Auth::user()->user_group_id == 2) {
                    $guest = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
                } else {
                    $guest = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
                    $guest = array_merge($guest, ['user_id' => Auth::user()->id]);
                }
            }
            if (HotelGuest::create($guest))
                return redirect(route('hotelguest.index'));

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
        if (Auth::user()->ableTo('view', HotelGuest::$model)) {

            $hotelguest = HotelGuest::find($id);

            if (empty($hotelguest)) {
                return redirect(route('hotelguest.index'));
            }

            return view('hotelguest.show')->with('hotelguest', $hotelguest);
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
        if (Auth::user()->ableTo('edit', HotelGuest::$model)) {

            $hotelguest = HotelGuest::find($id);

            if (empty($hotelguest)) {
                return redirect(route('hotelguest.index'));
            }

            return view('hotelguest.edit')->with('hotelguest', $hotelguest);
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
        if (Auth::user()->ableTo('edit', HotelGuest::$model)) {

            $request->validate([
                'name' => 'required|string|max:100',
                'officer_name' => 'required|string|max:100',
                'contact_number' => 'required|string|max:100',
                'guest_room_number' => 'required|string|max:100',
                'guest_first_name' => 'required|string|max:100',
                'guest_last_name' => 'required|string|max:100',
                'items' => 'required|string|max:100',
            ]);


            $hotelguest = HotelGuest::find($id);

            if (empty($hotelguest)) {
                return redirect(route('hotelguest.index'));
            }


            if ($request->has('partner_id')) {
                $guest = $request->all();
            } else {
                if (Auth::user()->user_group_id == 2) {
                    $guest = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
                } else {
                    $guest = array_merge($request->all(), ['partner_id' => Auth::user()->partner_id]);
                    $guest = array_merge($guest, ['user_id' => Auth::user()->id]);
                }
            }

            $hotelguest->update($guest);

            return redirect(route('hotelguest.index'));
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
        if (Auth::user()->ableTo('delete', HotelGuest::$model)) {

            $hotelguest = HotelGuest::find($id);
            if (empty($hotelguest)) {
                return redirect(route('hotelguest.index'));
            }
            $hotelguest->delete($id);
            return redirect(route('hotelguest.index'));
        } else {
            return view('extra.404');

        }

    }

}