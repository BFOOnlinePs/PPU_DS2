<?php

namespace App\Http\Controllers\apisControllers\company_manager\payments;

use App\Enums\NotificationTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\StudentCompany;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// only manager
class TraineePaymentsController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    // trainee payment in the manager's company
    public function getTraineePayments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
        ], [
            'student_id.required' => trans('messages.student_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');
        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $manager_id = auth()->user()->u_id;
        $company_id = Company::where('c_manager_id', $manager_id)->pluck('c_id')->first();
        $payments = Payment::where('p_student_id', $student_id)->where('p_company_id', $company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(7);
        // ->get();

        $payments->getCollection()->transform(function ($payment) use ($manager_id) {
            $payment->inserted_by_name = User::where('u_id', $manager_id)->pluck('name')->first();
            $payment->currency_symbol = Currency::where('c_id', $payment->p_currency_id)->pluck('c_symbol')->first();
            return $payment;
        });


        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total_items' => $payments->total(),
            ],
            'payments' => $payments->items(),
        ]);
    }


    // manager add payment
    public function addTraineePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'student_company_id' => 'required',
            'payment_value' => 'required',
            'currency_id' => 'required',
        ], [
            'student_id.required' => trans('messages.student_id_required'),
            'student_company_id.required' => trans('messages.training_id_required'),
            'payment_value.required' => trans('messages.student_id_not_exists'),
            'currency_id.required' => trans('messages.currency_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');
        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $student_company_id = $request->input('student_company_id');
        $student_company = StudentCompany::where('sc_id', $student_company_id)->first();
        if (!$student_company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.training_id_not_exists'),
            ]);
        }

        $currency_id = $request->input('currency_id');
        $currency = Currency::where('c_id', $currency_id)->first();
        if (!$currency) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.currency_id_not_exists')
            ]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folderPath = 'payments';
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $file->storeAs($folderPath, $fileName, 'public');
        }

        $manager = auth()->user();
        if ($manager->u_role_id != 6) {
            return response()->json([
                'status' => false,
                'message' => 'unauthorized, you have to be manager',
            ]);
        }

        $company_id = Company::where('c_manager_id', $manager->u_id)->pluck('c_id')->first();
        if (!$company_id) {
            return response()->json([
                'status' => false,
                'message' => 'no company',
            ]);
        }


        $payment = Payment::create([
            'p_student_id' => $student_id,
            'p_company_id' => $company_id,
            'p_student_company_id' => $student_company_id,
            'p_reference_id' => $request->input('reference_id'),
            'p_payment_value' => $request->input('payment_value'),
            'p_file' => $fileName ?? null,
            'p_inserted_by_id' => $manager->u_id,
            'p_status' => 0,
            'p_currency_id' => $currency_id,
            'p_company_notes' => $request->input('manager_notes'), // manager notes
        ]);

        // send Notification
        $this->fcmService->sendNotification(
            NotificationTypeEnum::PAYMENT,
            [$student_id]
        );


        return response()->json([
            'status' => true,
            'message' => trans('messages.payment_added_successfully'),
            'payment' => $payment
        ]);
    }




    // add payment from anyone (program coordinator)
    public function addPaymentByCoordinator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'company_id' => 'required',
            'student_company_id' => 'required',
            'payment_value' => 'required',
            'currency_id' => 'required',
        ], [
            'student_id.required' => trans('messages.student_id_required'),
            'company_id.required' => trans('messages.company_id_required'),
            'student_company_id.required' => trans('messages.training_id_required'),
            'payment_value.required' => trans('messages.student_id_not_exists'),
            'currency_id.required' => trans('messages.currency_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');
        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $student_company_id = $request->input('student_company_id');
        $student_company = StudentCompany::where('sc_id', $student_company_id)->first();
        if (!$student_company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.training_id_not_exists'),
            ]);
        }

        $currency_id = $request->input('currency_id');
        $currency = Currency::where('c_id', $currency_id)->first();
        if (!$currency) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.currency_id_not_exists')
            ]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folderPath = 'payments';
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $file->storeAs($folderPath, $fileName, 'public');
        }

        $added_by = auth()->user();
        if ($added_by->u_role_id != 8) {
            return response()->json([
                'status' => false,
                'message' => 'unauthorized, you have to be program coordinator',
            ]);
        }

        $payment = Payment::create([
            'p_student_id' => $student_id,
            'p_company_id' => $request->input('company_id'),
            'p_student_company_id' => $student_company_id,
            'p_reference_id' => $request->input('reference_id'),
            'p_payment_value' => $request->input('payment_value'),
            'p_file' => $fileName ?? null,
            'p_inserted_by_id' => $added_by->u_id,
            'p_status' => 0,
            'p_currency_id' => $currency_id,
        ]);

        $manager_id = Company::where('c_id', $request->input('company_id'))->first()->c_manager_id;
        Log::info('manager_id: ' . $manager_id);

        // send Notification
        $this->fcmService->sendNotification(
            NotificationTypeEnum::PAYMENT,
            [$student_id, $manager_id]
        );

        return response()->json([
            'status' => true,
            'message' => trans('messages.payment_added_successfully'),
            'payment' => $payment
        ]);
    }
}
