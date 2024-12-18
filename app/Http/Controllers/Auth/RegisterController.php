<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\State;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeEmail;
use App\Models\Department;
use App\Models\Lga;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm($stage = 'personal')
    {
        $states = State::where('status', 'active')->get();
        $faculties = Faculty::where('status', 'active')->get();

        return view('auth.register', compact('stage', 'states', 'faculties'));
    }

    public function processStep(Request $request, $stage)
    {
        $validationRules = $this->getValidationRules($stage);
        $validated = $request->validate($validationRules);

        if (!$request->session()->has('registration_data')) {
            $request->session()->put('registration_data', []);
        }

        $registrationData = $request->session()->get('registration_data');

        // Handle file uploads
        if ($request->hasFile('member_image')) {
            $path = $request->file('member_image')->store('members', 'public');
            $validated['member_image'] = $path;
        }
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        $registrationData = array_merge($registrationData, $validated);
        $request->session()->put('registration_data', $registrationData);

        if ($stage == 'documents') {
            return $this->completeRegistration($request);
        }

        $nextStage = $this->getNextStage($stage);
        return redirect()->route('register.show', ['stage' => $nextStage]);
    }

    private function getValidationRules($stage)
    {
        $rules = [
            'personal' => [
                'title' => 'required',
                'surname' => 'required|string|max:100',
                'firstname' => 'required|string|max:100',
                'othername' => 'nullable|string|max:100',
                'dob' => 'required|date',
                'nationality' => 'required|string',
            ],
            'contact' => [
                'home_address' => 'required|string',
                'phone_number' => 'required|string',
                'email' => 'required|email|unique:users',
                'state_id' => 'required|exists:states,id',
                'lga_id' => 'required|exists:lgas,id',
            ],
            'employment' => [
                'staff_no' => 'required|string|unique:users',
                'faculty_id' => 'required|exists:faculties,id',
                'department_id' => 'required|exists:departments,id',
            ],
            'next_of_kin' => [
                'nok' => 'required|string',
                'nok_relationship' => 'required|string',
                'nok_address' => 'required|string',
                'nok_phone' => 'required|string',
            ],
            'financial' => [
                'monthly_savings' => 'required|numeric|min:0',
                'share_subscription' => 'required|numeric|min:0',
                'month_commence' => 'required|string',
            ],
            'documents' => [
                'member_image' => 'required|image|max:2048',
                'signature_image' => 'required|image|max:2048',
                'password' => 'required|string|min:8|confirmed',
                'salary_deduction_agreement' => 'required|accepted',
                'membership_declaration' => 'required|accepted',
            ],
        ];

        return $rules[$stage] ?? [];
    }

    private function getNextStage($currentStage)
    {
        $stages = [
            'personal' => 'contact',
            'contact' => 'employment',
            'employment' => 'next_of_kin',
            'next_of_kin' => 'financial',
            'financial' => 'documents',
        ];

        return $stages[$currentStage] ?? 'completed';
    }

    private function completeRegistration(Request $request)
    {
        $data = $request->session()->get('registration_data');
        $data['password'] = Hash::make($data['password']);
        // Add declarations
        $data['salary_deduction_agreement'] = $request->has('salary_deduction_agreement') ? true : false;
        $data['membership_declaration'] = $request->has('membership_declaration') ? true : false;



        $randomNumber = rand(1, 9999);
        $data['member_no'] = 'OASCMS' . '-Form-' . $randomNumber;




        //dd($data['member_no']);
        $data['date_join'] = now();
        try {
            $user = User::create($data);

            // Send welcome email
            Mail::to($user->email)->send(new WelcomeEmail($user));



            $request->session()->forget('registration_data');

            //auth()->login($user);

            return redirect()->route('login')->with('success', 'Registration completed successfully!

         Please check your email to continue');
        } catch (\Exception $e) {
            // Handle any errors
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getLgas($stateId)
    {
        $lgas = Lga::where('state_id', $stateId)->get();
        return response()->json($lgas);
    }

    public function getDepartments($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        return response()->json($departments);
    }
}
