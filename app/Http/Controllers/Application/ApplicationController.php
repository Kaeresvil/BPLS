<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\BusinessInformation;
use App\Models\BusinessActivity;
use App\Models\Lessor;
use App\Models\OwnerInformation;
use App\Models\Document;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\ApproveEmail;
use App\Mail\ReturnEmail;

class ApplicationController extends Controller
{


    public function generateRefNumber()
    {

        $count = Application::latest('created_at')->first();
        if($count){
        $count = $count->id + 1;
        }else{
            $count = 1;
        }

        if ($count < 10) {
            $ref_no = "BPLS-0000" . ($count);
        } else if ($count < 100) {
            $ref_no = "BPLS-000" . ($count);
        } else if ($count < 1000) {
            $ref_no = "BPLS-00" . ($count);
        } else {
            $ref_no = "BPLS-0" . ($count);
        }
            
        return $ref_no;
    }

    public function store(Request $request)
    {

        $result = $this->generateRefNumber();
        $request['ref_no'] = $result;
        $request['status'] = 'SUBMITTED';

        $application = new Application();

       $application->applicant_id = Auth::user()->id;
       $application->ref_no = $request->ref_no;
       $application->status = $request->status;
       $application->type_of_application = $request->type_of_application;
       $application->mode_of_payment = $request->mode_of_payment;
       $application->date_of_application = Carbon::parse($request->date_of_application)->format('Y-m-d');
       $application->tin = $request->tin;
       $application->type_of_business = $request->type_of_business;
       $application->ammendment_from = $request->ammendment_from;
       $application->ammendment_to = $request->ammendment_to;
       $application->dti_registration_no = $request->dti_registration_no;
       $application->dti_registration_date = Carbon::parse($request->dti_registration_date)->format('Y-m-d');
       $application->first_name = $request->first_name;
       $application->last_name = $request->last_name;
       $application->middle_name = $request->middle_name;
       $application->business_name = $request->business_name;;
       $application->trade_name = $request->trade_name;
       $application->save();

       $this->createBusinessActivity($request->business_activity, $application->id);
       $this->createBusinessInformations($request, $application->id);
       $this->createLessors($request, $application->id);
       $this->createOwnersInformation($request, $application->id);
       $this->createNotification($request, $application->id, true);

       return response()->json([
        'message' => 'Applicattion Submitted Successfully',
        'data' => $application
    ], 220);
    }

    public function createBusinessActivity($activities, $id)
    {
        if($activities[0]['remarks'] != 'No Event'){
            foreach($activities as $activity){
                Log::info($activity['line_of_business']);
                $businessActivity = new BusinessActivity();
                $businessActivity->application_id = $id;
                $businessActivity->line_of_business = $activity['line_of_business'];
                $businessActivity->units = $activity['units'];
                $businessActivity->capitalization = $activity['capitalization'];
                $businessActivity->essential = $activity['essential'];
                $businessActivity->non_essential = $activity['non_essential'];
                $businessActivity->save();
            }
        }
       

    }
    public function createBusinessInformations($information, $id)
    {
        $businessInformation = new BusinessInformation();
        $businessInformation->application_id = $id;
        $businessInformation->business_address = $information->business_address;
        $businessInformation->business_postal = $information->business_postal;
        $businessInformation->business_tel_no = $information->business_tel_no;
        $businessInformation->business_email = $information->business_email;
        $businessInformation->business_mobile_no = $information->business_mobile_no;
        $businessInformation->business_area = $information->business_area;
        $businessInformation->total_employee = $information->total_employee;
        $businessInformation->no_employee = $information->no_employee;
        $businessInformation->save();

    }
    public function createLessors($params, $id)
    {
        $lessor = new Lessor();
        $lessor->application_id = $id;
        $lessor->lessors_fullname = $params->lessors_fullname;
        $lessor->lessors_address = $params->lessors_address;
        $lessor->lessors_tel_no = $params->lessors_tel_no;
        $lessor->lessors_email = $params->lessors_email;
        $lessor->monthly_rental = $params->monthly_rental;
        $lessor->save();

    }
    public function createOwnersInformation($params, $id)
    {
        $ownerInformation = new OwnerInformation();
        $ownerInformation->application_id = $id;
        $ownerInformation->owner_address = $params->owner_address;
        $ownerInformation->owner_postal = $params->owner_postal;
        $ownerInformation->owner_tel_no = $params->owner_tel_no;
        $ownerInformation->owner_email = $params->owner_email;
        $ownerInformation->owner_mobile_no = $params->owner_mobile_no;
        $ownerInformation->contact_person_mobile_no = $params->contact_person_mobile_no;
        $ownerInformation->contact_person_email = $params->contact_person_email;
        $ownerInformation->save();

    }

    //// Update Application

    public function update(Request $request , $id)
    {

        $application =  Application::find($id);

       $application->type_of_application = $request->type_of_application;
       $application->mode_of_payment = $request->mode_of_payment;
       $application->date_of_application = Carbon::parse($request->date_of_application)->format('Y-m-d');
       $application->tin = $request->tin;
       $application->type_of_business = $request->type_of_business;
       $application->ammendment_from = $request->ammendment_from;
       $application->ammendment_to = $request->ammendment_to;
       $application->dti_registration_no = $request->dti_registration_no;
       $application->dti_registration_date = Carbon::parse($request->dti_registration_date)->format('Y-m-d');
       $application->first_name = $request->first_name;
       $application->last_name = $request->last_name;
       $application->middle_name = $request->middle_name;
       $application->business_name = $request->business_name;;
       $application->trade_name = $request->trade_name;
       $application->status = $request->status == 'RETURNED' ? "SUBMITTED":$application->status;
       $application->update();

       $this->updateBusinessActivity($request->business_activity, $application->id);
       $this->updateBusinessInformations($request, $application->id);
       $this->updateLessors($request, $application->id);
       $this->updateOwnersInformation($request, $application->id);

       if($request->status == 'RETURNED'){
        $this->createNotification($request, $application->id, false);
       }

       return response()->json([
        'message' => 'Applicattion Updated Successfully',
        'data' => $application
    ], 200);
    }

    public function updateBusinessActivity($activities, $id)
    {
        $activity_ids = [];
        if($activities[0]['remarks'] != 'No Event'){
           
            foreach($activities as $activity){
                log::info('activity');
                log::info($activity);
                if($activity['id'] != NULL){
                $businessActivity = BusinessActivity::find($activity['id']);
                $businessActivity->line_of_business = $activity['line_of_business'];
                $businessActivity->units = $activity['units'];
                $businessActivity->capitalization = $activity['capitalization'];
                $businessActivity->essential = $activity['essential'];
                $businessActivity->non_essential = $activity['non_essential'];
                $businessActivity->update();
                array_push($activity_ids, $activity['id']);
             }else{
                $businessActivity = new BusinessActivity();
                $businessActivity->application_id = $id;
                $businessActivity->line_of_business = $activity['line_of_business'];
                $businessActivity->units = $activity['units'];
                $businessActivity->capitalization = $activity['capitalization'];
                $businessActivity->essential = $activity['essential'];
                $businessActivity->non_essential = $activity['non_essential'];
                $businessActivity->save();
                array_push($activity_ids, $businessActivity->id);
             }
            }
            BusinessActivity::whereNotIn('id', $activity_ids)->where('application_id', $id)->delete();
        }else{
            BusinessActivity::where('application_id', $id)->delete();
        }
       

    }

    public function updateBusinessInformations($information, $id)
    {
        $businessInformation = BusinessInformation::where('application_id',$id)->first();
        $businessInformation->business_address = $information->business_address;
        $businessInformation->business_postal = $information->business_postal;
        $businessInformation->business_tel_no = $information->business_tel_no;
        $businessInformation->business_email = $information->business_email;
        $businessInformation->business_mobile_no = $information->business_mobile_no;
        $businessInformation->business_area = $information->business_area;
        $businessInformation->total_employee = $information->total_employee;
        $businessInformation->no_employee = $information->no_employee;
        $businessInformation->update();

    }
    public function updateLessors($params, $id)
    {
        $lessor = Lessor::where('application_id', $id)->first();
        $lessor->application_id = $id;
        $lessor->lessors_fullname = $params->lessors_fullname;
        $lessor->lessors_address = $params->lessors_address;
        $lessor->lessors_tel_no = $params->lessors_tel_no;
        $lessor->lessors_email = $params->lessors_email;
        $lessor->monthly_rental = $params->monthly_rental;
        $lessor->update();

    }
    public function updateOwnersInformation($params, $id)
    {
        $ownerInformation = OwnerInformation::where('application_id', $id)->first();
        $ownerInformation->application_id = $id;
        $ownerInformation->owner_address = $params->owner_address;
        $ownerInformation->owner_postal = $params->owner_postal;
        $ownerInformation->owner_tel_no = $params->owner_tel_no;
        $ownerInformation->owner_email = $params->owner_email;
        $ownerInformation->owner_mobile_no = $params->owner_mobile_no;
        $ownerInformation->contact_person_mobile_no = $params->contact_person_mobile_no;
        $ownerInformation->contact_person_email = $params->contact_person_email;
        $ownerInformation->update();

    }

    public function index(Request $request)
    {
        $params = $request->all();

        Log::info(Auth::user()->role);

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search'])) {
            $search = $params['search'];
            $searchable = ['name', 'description'];
            $query = Application::where(function ($query) use ($search) {
                $query->where('ref_no', 'like', '%' . $search . '%')
                      ->orWhere('type_of_business', 'like', '%' . $search . '%')
                      ->orWhere('type_of_application', 'like', '%' . $search . '%')
                      ->orWhere('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%');
            });
            if(Auth::user()->role == 'Applicant'){
           $query = $query->where('applicant_id',Auth::user()->id)->orderBy($orderByColumn, $direction)->paginate($limit);
           }else{
            $query = $query->orderBy($orderByColumn, $direction)->paginate($limit);
           }
        }else{
            if(Auth::user()->role == 'Applicant'){
            $query = Application::where('applicant_id',Auth::user()->id)->orderBy($orderByColumn, $direction)->paginate($limit);
            }else{
                $query = Application::orderBy($orderByColumn, $direction)->paginate($limit);
            }
        }

        return response()->json([
            'message' => 'Application List Fetch Successfully',
            'data' => $query
        ], 200);
 
    }

    public function uploadDocuments(Request $request)
    {

        if (isset($request['files'])) {
            $c = 0;
            foreach ($request['files'] as $key => $value) {
                $fileName = time() . '_' . $value->getClientOriginalName();
                $folderName = 'public';
                $path = Storage::disk('local')->putFileAs($folderName, $value, $fileName);

                Document::create([
                    'application_id' => $request['id'],
                    'file_name' => $fileName,
                    'document' => $request['document'][$c],
                    'key' => $request['key'][$c],
                    'file_path' =>  $path
                ]);
                $c++;
            }
        }
       

        return response()->json([
            'message' => 'Application Uploaded Successfully',
            'data' => []
        ], 200);
    }
    public function updateDocuments(Request $request)
    {


       if (isset($request['remove'])) {
        foreach ($request['remove'] as $path) {
            Storage::disk('local')->delete("public/".$path);
        }
       }

        if (isset($request['files'])) {
            $c = 0;
            foreach ($request['files'] as $key => $value) {
                $fileName = time() . '_' . $value->getClientOriginalName();
                $folderName = 'public';
                $path = Storage::disk('local')->putFileAs($folderName, $value, $fileName);

                Document::where('id', $request['f_id'][$c])->update([
                    'file_name' => $fileName,
                    'file_path' =>  $path
                ]);
                $c++;
            }
        }
       

        return response()->json([
            'message' => 'Application Updated Successfully',
            'data' => []
        ], 200);
    }

    public function approve(Request $request, $id)
    {
    DB::beginTransaction();
    try {
        $assesment = Assessment::where('application_id',$id)->first();
        if(!$assesment){
            return response()->json([
                'errors' => ['Assessment not found, please set assessment to approve.'],
                'data' => []
            ], 422);

        }
        $post = Application::find($id);
        $post->status = 'APPROVED';
        $post->remarks = $request->remarks;
        $post->update();

        $user = User::find($post->applicant_id);
        Mail::to($user->email)->send(new ApproveEmail($post->ref_no));
        $this->ApproveAndReeturnNotification($post, $id);

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return $e->getMessage();
    }
        return response()->json([
            'message' => 'Application Approved Successfully',
            'data' => $post
        ], 200);
    }

    public function return(Request $request, $id)
    {
     DB::beginTransaction();
    try {
        Log::info($request->remarks);
        $post = Application::find($id);
        $post->status = 'RETURNED';
        $post->remarks = $request->remarks;
        $post->update();
        $this->ApproveAndReeturnNotification($post, $id);
        $user = User::find($post->applicant_id);
        Mail::to($user->email)->send(new ReturnEmail($post->ref_no));

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return $e->getMessage();
    }

    return response()->json([
        'message' => 'Application Returned Successfully',
        'data' => $post
    ], 200);

    }
    public function claimed(Request $request, $id)
    {


        $appointment = Appointment::where('application_id',$id)->first();
        if(!$appointment){
            throw ValidationException::withMessages([
                'authentication' => 'No appointment, please set appointment to claim!',
            ]);
        }
        $appointment->date_claimed = Carbon::now()->format('Y-m-d');
        $appointment->is_claimed = 1;
        $appointment->update();

        $post = Application::find($id);
        $post->status = 'CLAIMED';
        $post->claimed_by = $request->claimed_by;
        $post->claimed_date = Carbon::now()->format('Y-m-d H:i:s');
        $post->update();

        $this->ApproveAndReeturnNotification($post, $id);
        return response()->json([
            'message' => 'Application Claimed Successfully',
            'data' => $post
        ], 200);
    }
    public function show($id)
    {
        $post = Application::find($id);
        return response()->json($post);
    }
    public function delete($id)
    {
        $post = Application::find($id);
        $post->delete();
        BusinessActivity::where('application_id', $id)->delete();
        BusinessInformation::where('application_id', $id)->delete();
        Lessor::where('application_id', $id)->delete();
        OwnerInformation::where('application_id', $id)->delete();
        Appointment::where('application_id', $id)->delete();
        Notification::where('application_id', $id)->delete();

        $documents = Document::where('application_id', '=', $id)->get();

        foreach($documents as $docu){
            Document::where('id', $docu->id)->delete();
            Storage::disk('local')->delete($docu->file_path);
        }

        $response = [
            'data' => $post,
            'message' => 'Application Deleted Successfully',
    
        ];
        return response()->json($response);
    }

    public function createNotification($params, $id, $iscreated)
    {
        $notification = new Notification();

        $description = $iscreated ? "Reference No.: ".$params->ref_no." needs your approval.":"Reference No.: ".$params->ref_no." RE-SUBMITTED, needs your approval.";
        $notification->application_id = $id;
        $notification->description = $description;
        $notification->is_read = 0;
        $notification->is_ForStaff = 1;
        $notification->usermanagement = 0;
        $notification->save();
    }
    public function ApproveAndReeturnNotification($params, $id)
    {
        $notification = new Notification();

        $status = $params->status;
        $notification->user_id = $params->applicant_id;
        $notification->application_id = $id;
        $notification->description = "Reference No.: ".$params->ref_no." has been ".$status.".";
        $notification->is_read = 0;
        $notification->is_ForStaff = 0;
        $notification->usermanagement = 0;
        $notification->save();
    }


}
