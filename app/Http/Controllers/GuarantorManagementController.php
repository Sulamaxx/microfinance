<?php

namespace App\Http\Controllers;


use App\Models\Guarantor;
use App\Models\Member;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class GuarantorManagementController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone', 'Asia/Colombo'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.guarantor_management.list');
    }

    public function get_table_data()
    {
        $guarantors = Guarantor::orderBy("id", "desc");

        return Datatables::eloquent($guarantors)
            ->editColumn('photo', function ($guarantor) {
                $photo = $guarantor->photo != null ? profile_picture($guarantor->photo) : asset('public/backend/images/avatar.png');
                return '<div class="profile_picture text-center">'
                    . '<img src="' . $photo . '" class="thumb-sm img-thumbnail">'
                    . '</div>';
            })
            ->addColumn('action', function ($guarantor) {
                return '<div class="dropdown text-center">'
                    . '<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . _lang('Action')
                    . '&nbsp;</button>'
                    . '<div class="dropdown-menu">'
                    . '<a class="dropdown-item" href="' . route('guarantor_managements.edit', $guarantor->id) . '"><i class="ti-pencil-alt"></i> ' . _lang('Edit') . '</a>'
                    . '<a class="dropdown-item" href="' . route('guarantor_managements.show', $guarantor->id) . '"><i class="ti-eye"></i>  ' . _lang('View') . '</a>'
                    . '<form action="' . route('guarantor_managements.destroy', $guarantor->id) . '" method="post">'
                    . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="dropdown-item btn-remove" type="submit"><i class="ti-trash"></i> ' . _lang('Delete') . '</button>'
                    . '</form>'
                    . '</div>'
                    . '</div>';
            })
            ->setRowId(function ($guarantor) {
                return "row_" . $guarantor->id;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.guarantor_management.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required',
            'full_name' => 'required',
            'name_with_initial' => 'required',
            'nic' => 'required|unique:members,nic',
            'mobile1' => 'required|max:10',
            'mobile2' => 'max:10',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'photo' => 'nullable|image',
            'doc_image' => 'nullable|image',

        ];

        $validationMessages = [
            'title.required' => 'Title is required',
            'full_name.required' => 'Full name is required',
            'name_with_initial.required' => 'Name with initials is required',
            'nic.required' => 'NIC is required',
            'nic.unique' => 'NIC must be unique',
            'mobile1.required' => 'Primary mobile number is required',
            'mobile1.max' => 'Primary mobile number cannot exceed 10 characters',
            'mobile2.max' => 'Secondary mobile number cannot exceed 10 characters',
            'address1.required' => 'Address line 1 is required',
            'address2.required' => 'Address line 2 is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'zip.required' => 'ZIP code is required',
            'photo.image' => 'Photo must be an image file',
            'photo.doc_image' => 'Dovument Photo must be an image file',
        ];

        $validator = Validator::make($request->all(), $validationRules, $validationMessages);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('guarantor_managements.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $photo = 'default.png';
        if ($request->hasfile('photo')) {
            $file  = $request->file('photo');
            $photo = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $photo);
        }

        $doc_image = 'default.png';
        if ($request->hasfile('doc_image')) {
            $file  = $request->file('doc_image');
            $doc_image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $doc_image);
        }

        DB::beginTransaction();

        // First, create or retrieve the guarantor
        $guarantor = new Guarantor();
        $guarantor->title   = $request->input('title');
        $guarantor->full_name   = $request->input('full_name');
        $guarantor->name_with_initial   = $request->input('name_with_initial');
        $guarantor->nic         = $request->input('nic');
        $guarantor->mobile1     = $request->input('mobile1');
        $guarantor->mobile2     = $request->input('mobile2');
        $guarantor->address1    = $request->input('address1');
        $guarantor->address2    = $request->input('address2');
        $guarantor->city        = $request->input('city');
        $guarantor->state       = $request->input('state');
        $guarantor->zip         = $request->input('zip');
        $guarantor->photo       = $photo;
        $guarantor->doc_image  = $doc_image;
        $guarantor->save();

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('guarantor_managements.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $guarantor, 'table' => '#guarantors_table']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $guarantor = Guarantor::withoutGlobalScopes(['status'])->find($id);
        Log::info($guarantor);
        if (!$guarantor) {
            abort(404, 'Guarantor not found or inactive');
        }

        if (!$request->ajax()) {
            return view('backend.guarantor_management.view', compact('guarantor', 'id'));
        } else {
            return view('backend.guarantor_management.modal.view', compact('guarantor', 'id'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $guarantor = Guarantor::withoutGlobalScopes(['status'])->find($id);
        Log::info($guarantor);
        if (!$request->ajax()) {
            return view('backend.guarantor_management.edit', compact('guarantor', 'id'));
        } else {
            return view('backend.guarantor_management.modal.edit', compact('guarantor', 'id'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $guarantor = Guarantor::findOrFail($id);

        $validationRules = [
            'title' => 'required',
            'full_name' => 'required',
            'name_with_initial' => 'required',
            'nic' => 'required|unique:members,nic,' . $id,
            'mobile1' => 'required|max:10',
            'mobile2' => 'max:10',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'photo' => 'nullable|image',
            'doc_image' => 'nullable|image',

        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('guarantor_managements.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        $guarantor->title   = $request->input('title');
        $guarantor->full_name   = $request->input('full_name');
        $guarantor->name_with_initial   = $request->input('name_with_initial');
        $guarantor->nic         = $request->input('nic');
        $guarantor->mobile1     = $request->input('mobile1');
        $guarantor->mobile2     = $request->input('mobile2');
        $guarantor->address1    = $request->input('address1');
        $guarantor->address2    = $request->input('address2');
        $guarantor->city        = $request->input('city');
        $guarantor->state       = $request->input('state');
        $guarantor->zip         = $request->input('zip');

        // Handle  Photo
        if ($request->hasFile('photo')) {
            if ($guarantor->photo && $guarantor->photo != 'default.png' && file_exists(public_path("uploads/profile/{$guarantor->photo}"))) {
                unlink(public_path("uploads/profile/{$guarantor->photo}"));
            }
            $file = $request->file('photo');
            $guarantor->photo = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $guarantor->photo);
        }

        // Handle guarantor Document Image 
        if ($request->hasFile('doc_image')) {
            if ($guarantor->doc_image && $guarantor->doc_image != 'default.png' && file_exists(public_path("uploads/profile/{$guarantor->doc_image}"))) {
                unlink(public_path("uploads/profile/{$guarantor->doc_image}"));
            }
            $file = $request->file('doc_image');
            $guarantor->doc_image = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $guarantor->doc_image);
        }

        $guarantor->save();

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('guarantor_managements.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $guarantor, 'table' => '#guarantors_table']);
        }
    }

    // public function send_email(Request $request)
    // {
    //     @ini_set('max_execution_time', 0);
    //     @set_time_limit(0);

    //     Overrider::load("Settings");

    //     $validator = Validator::make($request->all(), [
    //         'user_email' => 'required',
    //         'subject'    => 'required',
    //         'message'    => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         if ($request->ajax()) {
    //             return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
    //         } else {
    //             return back()->withErrors($validator)
    //                 ->withInput();
    //         }
    //     }

    //     //Send email
    //     $subject = $request->input("subject");
    //     $message = $request->input("message");

    //     $mail          = new \stdClass();
    //     $mail->subject = $subject;
    //     $mail->body    = $message;

    //     try {
    //         Mail::to($request->user_email)->send(new GeneralMail($mail));
    //     } catch (\Exception $e) {
    //         if (!$request->ajax()) {
    //             return back()->with('error', _lang('Sorry, Error Occured !'));
    //         } else {
    //             return response()->json(['result' => 'error', 'message' => _lang('Sorry, Error Occured !')]);
    //         }
    //     }

    //     if (!$request->ajax()) {
    //         return back()->with('success', _lang('Email Send Sucessfully'));
    //     } else {
    //         return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Email Send Sucessfully'), 'data' => $contact]);
    //     }
    // }

    // public function send_sms(Request $request)
    // {
    //     @ini_set('max_execution_time', 0);
    //     @set_time_limit(0);

    //     $validator = Validator::make($request->all(), [
    //         'phone'   => 'required',
    //         'message' => 'required:max:160',
    //     ]);

    //     if ($validator->fails()) {
    //         if ($request->ajax()) {
    //             return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
    //         } else {
    //             return back()->withErrors($validator)
    //                 ->withInput();
    //         }
    //     }

    //     //Send message
    //     $message = $request->input("message");

    //     if (get_option('sms_gateway') == 'none') {
    //         return back()->with('error', _lang('Sorry, SMS Gateway is disabled !'));
    //     }

    //     try {
    //         $sms = new SmsHelper();
    //         $sms->send($request->phone, $message);
    //     } catch (\Exception $e) {
    //         if (!$request->ajax()) {
    //             return back()->with('error', _lang('Sorry, Error Occured !'));
    //         } else {
    //             return response()->json(['result' => 'error', 'message' => _lang('Sorry, Error Occured !')]);
    //         }
    //     }

    //     if (!$request->ajax()) {
    //         return back()->with('success', _lang('SMS Send Sucessfully'));
    //     } else {
    //         return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('SMS Send Sucessfully'), 'data' => $contact]);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $guarantor = Guarantor::find($id);
        $guarantor->delete();
        return redirect()->route('guarantor_managements.index')->with('success', _lang('Deleted Successfully'));
    }
}
