<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sponsor;
use App\Models\Transaction;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
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
        return view('backend.member.list');
    }

    public function get_table_data()
    {
        $members = Member::select('members.*')
            ->with('branch')
            ->with('sponsor')
            ->orderBy("members.id", "desc");

        return Datatables::eloquent($members)
            ->editColumn('branch.name', function ($member) {
                return $member->branch->name;
            })
            ->editColumn('photo', function ($member) {
                $photo = $member->photo != null ? profile_picture($member->photo) : asset('public/backend/images/avatar.png');
                return '<div class="profile_picture text-center">'
                    . '<img src="' . $photo . '" class="thumb-sm img-thumbnail">'
                    . '</div>';
            })
            ->addColumn('action', function ($member) {
                return '<div class="dropdown text-center">'
                    . '<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . _lang('Action')
                    . '&nbsp;</button>'
                    . '<div class="dropdown-menu">'
                    . '<a class="dropdown-item" href="' . route('members.edit', $member->id) . '"><i class="ti-pencil-alt"></i> ' . _lang('Edit') . '</a>'
                    . '<a class="dropdown-item" href="' . route('members.show', $member->id) . '"><i class="ti-eye"></i>  ' . _lang('View') . '</a>'
                    // . '<a class="dropdown-item" href="' . route('member_documents.index', $member->id) . '"><i class="ti-files"></i>  ' . _lang('Documents') . '</a>'
                    . '<form action="' . route('members.destroy', $member->id) . '" method="post">'
                    . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="dropdown-item btn-remove" type="submit"><i class="ti-trash"></i> ' . _lang('Delete') . '</button>'
                    . '</form>'
                    . '</div>'
                    . '</div>';
            })
            ->setRowId(function ($member) {
                return "row_" . $member->id;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
    }

    public function pending_requests()
    {
        $data            = array();
        $data['members'] = Member::where('status', 0)
            ->withoutGlobalScopes(['status'])
            ->paginate(10);
        return view('backend.member.pending_requests', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.member.create');
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
            // Member Details
            'title' => 'required',
            'customer_id' => 'required|unique:members,customer_id',
            'civil_status' => 'required',
            'gender' => 'required',
            'full_name' => 'required',
            'name_with_initial' => 'required',
            'nic' => 'required|unique:members,nic',
            'dob' => 'required|unique:members,dob',
            'contact_number' => 'required|max:10',
            'mobile1' => 'required|max:10',
            'mobile2' => 'max:10',
            'branch_id' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'photo' => 'nullable|image',

            // Sponsor Details
            'sponser_full_name' => 'required',
            'sponser_nic' => 'required',
            'sponser_mobile1' => 'required|max:10',
            'sponser_mobile2' => 'max:10',
            'sponser_address1' => 'required',
            'sponser_address2' => 'required',
            'sponser_city' => 'required',
            'sponser_state' => 'required',
            'sponser_zip' => 'required',
            'sponser_photo' => 'nullable|image',
            'sponser_doc_image1' => 'nullable|image',
            'sponser_doc_image2' => 'nullable|image',
        ];

        $validationMessages = [
            // Member Details
            'title.required' => 'Title is required',
            'customer_id.required' => 'Customer ID is required',
            'customer_id.unique' => 'Customer ID must be unique',
            'civil_status.required' => 'Civil status is required',
            'gender.required' => 'Gender is required',
            'full_name.required' => 'Full name is required',
            'name_with_initial.required' => 'Name with initials is required',
            'nic.required' => 'NIC is required',
            'nic.unique' => 'NIC must be unique',
            'dob.required' => 'Date of birth is required',
            'dob.unique' => 'Date of birth must be unique',
            'contact_number.required' => 'Contact number is required',
            'contact_number.max' => 'Contact number cannot exceed 10 characters',
            'mobile1.required' => 'Primary mobile number is required',
            'mobile1.max' => 'Primary mobile number cannot exceed 10 characters',
            'mobile2.max' => 'Secondary mobile number cannot exceed 10 characters',
            'branch_id.required' => 'Branch ID is required',
            'address1.required' => 'Address line 1 is required',
            'address2.required' => 'Address line 2 is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'zip.required' => 'ZIP code is required',
            'photo.image' => 'Photo must be an image file',

            // Sponsor Details
            'sponser_full_name.required' => 'Sponsor full name is required',
            'sponser_nic.required' => 'Sponsor NIC is required',
            'sponser_mobile1.required' => 'Sponsor primary mobile number is required',
            'sponser_mobile1.max' => 'Sponsor primary mobile number cannot exceed 10 characters',
            'sponser_mobile2.max' => 'Sponsor secondary mobile number cannot exceed 10 characters',
            'sponser_address1.required' => 'Sponsor address line 1 is required',
            'sponser_address2.required' => 'Sponsor address line 2 is required',
            'sponser_city.required' => 'Sponsor city is required',
            'sponser_state.required' => 'Sponsor state is required',
            'sponser_zip.required' => 'Sponsor ZIP code is required',
            'sponser_photo.image' => 'Sponsor photo must be an image file',
            'sponser_doc_image1.image' => 'Sponsor document image 1 must be an image file',
            'sponser_doc_image2.image' => 'Sponsor document image 2 must be an image file',
        ];

        $validator = Validator::make($request->all(), $validationRules, $validationMessages);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('members.create')
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

        $sponser_photo = 'default.png';
        if ($request->hasfile('sponser_photo')) {
            $file  = $request->file('sponser_photo');
            $sponser_photo = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponser_photo);
        }

        $sponser_doc_image1 = 'default.png';
        if ($request->hasfile('sponser_doc_image1')) {
            $file  = $request->file('sponser_doc_image1');
            $sponser_doc_image1 = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponser_doc_image1);
        }

        $sponser_doc_image2 = 'default.png';
        if ($request->hasfile('sponser_doc_image2')) {
            $file  = $request->file('sponser_doc_image2');
            $sponser_doc_image2 = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponser_doc_image2);
        }

        DB::beginTransaction();

        // First, create or retrieve the Sponsor
        $sponsor = new Sponsor();
        $sponsor->full_name   = $request->input('sponser_full_name');
        $sponsor->nic         = $request->input('sponser_nic');
        $sponsor->mobile1     = $request->input('sponser_mobile1');
        $sponsor->mobile2     = $request->input('sponser_mobile2');
        $sponsor->address1    = $request->input('sponser_address1');
        $sponsor->address2    = $request->input('sponser_address2');
        $sponsor->city        = $request->input('sponser_city');
        $sponsor->state       = $request->input('sponser_state');
        $sponsor->zip         = $request->input('sponser_zip');
        $sponsor->photo       = $sponser_photo;
        $sponsor->doc_image1  = $sponser_doc_image1;
        $sponsor->doc_image2  = $sponser_doc_image2;
        $sponsor->save();

        // Now, create the Member and associate the Sponsor
        $member = new Member();
        $member->title             = $request->input('title');
        $member->customer_id       = $request->input('customer_id');
        $member->civil_status      = $request->input('civil_status');
        $member->gender            = $request->input('gender');
        $member->full_name         = $request->input('full_name');
        $member->name_with_initial = $request->input('name_with_initial');
        $member->nic               = $request->input('nic');
        $member->dob               = $request->input('dob');
        $member->contact_number    = $request->input('contact_number');
        $member->mobile1           = $request->input('mobile1');
        $member->mobile2           = $request->input('mobile2');
        $member->branch_id         = auth()->user()->user_type == 'admin' ? $request->input('branch_id') : auth()->user()->branch_id;
        $member->address1          = $request->input('address1');
        $member->address2          = $request->input('address2');
        $member->city              = $request->input('city');
        $member->state             = $request->input('state');
        $member->zip               = $request->input('zip');
        $member->photo             = $photo;
        $member->sponser_id        = $sponsor->id;
        $member->save();


        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('members.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $member, 'table' => '#members_table']);
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
        $member = Member::withoutGlobalScopes(['status'])
            ->with('sponsor')
            ->find($id);
        if (!$member) {
            abort(404, 'Member not found or inactive');
        }

        if (!$request->ajax()) {
            return view('backend.member.view', compact('member', 'id'));
        } else {
            return view('backend.member.modal.view', compact('member', 'id'));
        }
    }


    public function get_member_transaction_data($member_id)
    {
        $transactions = Transaction::select('transactions.*')
            ->with(['member', 'account', 'account.savings_type'])
            ->where('member_id', $member_id)
            ->orderBy("transactions.trans_date", "desc");

        return Datatables::eloquent($transactions)
            ->editColumn('member.first_name', function ($transactions) {
                return $transactions->member->first_name . ' ' . $transactions->member->last_name;
            })
            ->editColumn('dr_cr', function ($transactions) {
                return strtoupper($transactions->dr_cr);
            })
            ->editColumn('status', function ($transactions) {
                return transaction_status($transactions->status);
            })
            ->editColumn('amount', function ($transaction) {
                $symbol = $transaction->dr_cr == 'dr' ? '-' : '+';
                $class  = $transaction->dr_cr == 'dr' ? 'text-danger' : 'text-success';
                return '<span class="' . $class . '">' . $symbol . ' ' . decimalPlace($transaction->amount, currency($transaction->account->savings_type->currency->name)) . '</span>';
            })
            ->editColumn('type', function ($transaction) {
                return str_replace('_', ' ', $transaction->type);
            })
            ->filterColumn('member.first_name', function ($query, $keyword) {
                $query->whereHas('member', function ($query) use ($keyword) {
                    return $query->where("first_name", "like", "{$keyword}%")
                        ->orWhere("last_name", "like", "{$keyword}%");
                });
            }, true)
            ->addColumn('action', function ($transaction) {
                return '<div class="dropdown text-center">'
                    . '<button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . _lang('Action')
                    . '&nbsp;</button>'
                    . '<div class="dropdown-menu">'
                    . '<a class="dropdown-item" href="' . route('transactions.edit', $transaction['id']) . '"><i class="ti-pencil-alt"></i> ' . _lang('Edit') . '</a>'
                    . '<a class="dropdown-item" href="' . route('transactions.show', $transaction['id']) . '"><i class="ti-eye"></i>  ' . _lang('View') . '</a>'
                    . '<form action="' . route('transactions.destroy', $transaction['id']) . '" method="post">'
                    . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="dropdown-item btn-remove" type="submit"><i class="ti-trash"></i> ' . _lang('Delete') . '</button>'
                    . '</form>'
                    . '</div>'
                    . '</div>';
            })
            ->setRowId(function ($transaction) {
                return "row_" . $transaction->id;
            })
            ->rawColumns(['action', 'status', 'amount'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $member = Member::withoutGlobalScopes(['statua'])
            ->with('sponsor')
            ->find($id);

        if (!$request->ajax()) {
            return view('backend.member.edit', compact('member', 'id'));
        } else {
            return view('backend.member.modal.edit', compact('member', 'id'));
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
        $member = Member::findOrFail($id);
        $sponsor = Sponsor::findOrFail($member->sponser_id);

        $validationRules = [
            // Member Details
            'title' => 'required',
            'customer_id' => 'required|unique:members,customer_id,' . $id,
            'civil_status' => 'required',
            'gender' => 'required',
            'full_name' => 'required',
            'name_with_initial' => 'required',
            'nic' => 'required|unique:members,nic,' . $id,
            'dob' => 'required',
            'contact_number' => 'required|max:10',
            'mobile1' => 'required|max:10',
            'mobile2' => 'max:10',
            'branch_id' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'photo' => 'nullable|image',

            // Sponsor Details
            'sponser_full_name' => 'required',
            'sponser_nic' => 'required',
            'sponser_mobile1' => 'required|max:10',
            'sponser_mobile2' => 'max:10',
            'sponser_address1' => 'required',
            'sponser_address2' => 'required',
            'sponser_city' => 'required',
            'sponser_state' => 'required',
            'sponser_zip' => 'required',
            'sponser_photo' => 'nullable|image',
            'sponser_doc_image1' => 'nullable|image',
            'sponser_doc_image2' => 'nullable|image',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('members.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        // Update Sponsor Details
        $sponsor->full_name = $request->input('sponser_full_name');
        $sponsor->nic = $request->input('sponser_nic');
        $sponsor->mobile1 = $request->input('sponser_mobile1');
        $sponsor->mobile2 = $request->input('sponser_mobile2');
        $sponsor->address1 = $request->input('sponser_address1');
        $sponsor->address2 = $request->input('sponser_address2');
        $sponsor->city = $request->input('sponser_city');
        $sponsor->state = $request->input('sponser_state');
        $sponsor->zip = $request->input('sponser_zip');

        // Handle Sponsor Photo
        if ($request->hasFile('sponser_photo')) {
            if ($sponsor->photo && $sponsor->photo != 'default.png' && file_exists(public_path("uploads/profile/{$sponsor->photo}"))) {
                unlink(public_path("uploads/profile/{$sponsor->photo}"));
            }
            $file = $request->file('sponser_photo');
            $sponsor->photo = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponsor->photo);
        }

        // Handle Sponsor Document Image 1
        if ($request->hasFile('sponser_doc_image1')) {
            if ($sponsor->doc_image1 && $sponsor->doc_image1 != 'default.png' && file_exists(public_path("uploads/profile/{$sponsor->doc_image1}"))) {
                unlink(public_path("uploads/profile/{$sponsor->doc_image1}"));
            }
            $file = $request->file('sponser_doc_image1');
            $sponsor->doc_image1 = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponsor->doc_image1);
        }

        // Handle Sponsor Document Image 2
        if ($request->hasFile('sponser_doc_image2')) {
            if ($sponsor->doc_image2 && $sponsor->doc_image2 != 'default.png' && file_exists(public_path("uploads/profile/{$sponsor->doc_image2}"))) {
                unlink(public_path("uploads/profile/{$sponsor->doc_image2}"));
            }
            $file = $request->file('sponser_doc_image2');
            $sponsor->doc_image2 = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $sponsor->doc_image2);
        }

        $sponsor->save();

        // Update Member Details
        $member->title = $request->input('title');
        $member->customer_id = $request->input('customer_id');
        $member->civil_status = $request->input('civil_status');
        $member->gender = $request->input('gender');
        $member->full_name = $request->input('full_name');
        $member->name_with_initial = $request->input('name_with_initial');
        $member->nic = $request->input('nic');
        $member->dob = $request->input('dob');
        $member->contact_number = $request->input('contact_number');
        $member->mobile1 = $request->input('mobile1');
        $member->mobile2 = $request->input('mobile2');
        $member->branch_id = auth()->user()->user_type == 'admin' ? $request->input('branch_id') : auth()->user()->branch_id;
        $member->address1 = $request->input('address1');
        $member->address2 = $request->input('address2');
        $member->city = $request->input('city');
        $member->state = $request->input('state');
        $member->zip = $request->input('zip');

        // Handle Member Photo
        if ($request->hasFile('photo')) {
            if ($member->photo && $member->photo != 'default.png' && file_exists(public_path("uploads/profile/{$member->photo}"))) {
                unlink(public_path("uploads/profile/{$member->photo}"));
            }
            $file = $request->file('photo');
            $member->photo = time() . $file->getClientOriginalName();
            $file->move(public_path() . "/uploads/profile/", $member->photo);
        }

        $member->save();

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('members.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $member, 'table' => '#members_table']);
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
        $member = Member::find($id);
        if ($member->user) {
            $member->user->delete();
        }
        $member->delete();
        return redirect()->route('members.index')->with('success', _lang('Deleted Successfully'));
    }

    // public function accept_request(Request $request, $id)
    // {
    //     if ($request->isMethod('get')) {
    //         $member = Member::withoutGlobalScopes(['status'])->find($id);
    //         return view('backend.member.modal.accept_request', compact('member'));
    //     } else {
    //         $validator = Validator::make($request->all(), [
    //             'member_no' => [
    //                 'required',
    //                 Rule::unique('members')->ignore($id),
    //             ],
    //         ]);

    //         if ($validator->fails()) {
    //             if ($request->ajax()) {
    //                 return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
    //             } else {
    //                 return back()->withErrors($validator)->withInput();
    //             }
    //         }

    //         DB::beginTransaction();

    //         $member            = Member::withoutGlobalScopes(['status'])->find($id);
    //         $member->member_no = $request->member_no;
    //         $member->status    = 1;
    //         $member->save();

    //         $member->user->status = 1;
    //         $member->user->save();

    //         DB::commit();

    //         if ($member->status == 1) {
    //             try {
    //                 $member->notify(new MemberRequestAccepted($member));
    //             } catch (\Exception $e) {
    //             }
    //         }

    //         if (!$request->ajax()) {
    //             return redirect()->route('members.index')->with('success', _lang('Member Request Accepted'));
    //         } else {
    //             return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Member Request Accepted'), 'data' => $member, 'table' => '#members_table']);
    //         }
    //     }
    // }

    // public function reject_request($id)
    // {
    //     $member = Member::withoutGlobalScopes(['status'])->find($id);
    //     $member->user->delete();
    //     $member->delete();
    //     return redirect()->back()->with('error', _lang('Member Request Rejected'));
    // }

    // public function import(Request $request)
    // {
    //     if ($request->isMethod('get')) {
    //         return view('backend.member.import');
    //     } else if ($request->isMethod('post')) {
    //         @ini_set('max_execution_time', 0);
    //         @set_time_limit(0);

    //         $validator = Validator::make($request->all(), [
    //             'file' => 'required|mimes:xlsx',
    //         ]);

    //         if ($validator->fails()) {
    //             return back()->withErrors($validator)->withInput();
    //         }

    //         $new_rows = 0;

    //         DB::beginTransaction();

    //         $previous_rows = Member::count();

    //         $data   = array();
    //         $import = Excel::import(new MembersImport($data), $request->file('file'));

    //         $current_rows = Member::count();

    //         $new_rows = $current_rows - $previous_rows;

    //         DB::commit();

    //         if ($new_rows == 0) {
    //             return back()->with('error', _lang('Nothing Imported, Data may already exists !'));
    //         }
    //         return back()->with('success', $new_rows . ' ' . _lang('Rows Imported Sucessfully'));
    //     }
    // }
}
