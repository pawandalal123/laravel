<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

trait ValidationTrait {

    public function validatordiscusion(array $data)
    {
        $messsages = [
        'title.required'=>'You cant leave title field empty',
        'description.required'=>'Please enter description'];
       

    $rules = [
        'title'=>'required|max:255',
        'description'=>'required|not_in:0'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }

    public function postdiscussion(Request $request)
    {
        $validator = $this->validatordiscusion($request->all());
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $this->discussion->saveform($request);
    }
    //////////////////// validate articles/////////

    protected function validator(array $data)
    {
        $messsages = [
        'title.required'=>'You cant leave title field empty',
        'category.required'=>'Please select category',
        'subcategory.required'=>'Please select subcategory',
        'articlefile.mimes'=>'Please select valid file'];
       

    $rules = [
        'title'=>'required|max:255',
        'category'=>'required|not_in:0',
        'subcategory' => 'required|not_in:0',
        'articlefile' => 'mimes:jpeg,png,bmp,gif,jpg'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }

    public function postarticle(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $this->articles->savearticle($request);
    }

   
     ///////////// validation for edit profile////
    protected function validateprofile(array $data)
    {
        $messsages = [
        'mobile.required'=>'Please enter mobile number',
        'mobile.numeric'=>'Please enter only number',
        'first_name.required'=>'Please enter first name',
        'dob.date'=>'Please enter valid date'];
       

        $rules = [
            'mobile'=>'required|numeric|min:10|max:12',
            'gender'=>'required',
            'first_name'=>'required|max:255',
            'dob' => 'date|date_format:Y-m-d'

        ];
        return Validator::make($data, $rules,$messsages);
    }
    //////////////save editprofil////
    public function userbasci_info(Request $request)
    {
        $validator = $this->validateprofile($request->all());
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $this->usersInterface->updateuserdetails($request);
    }

    /////////////post invitation///
   protected function validatorinvite(array $data)
    {
        $messsages = [
        'discussion_name.required'=>'Select discussion ',
        'name.required'=>'Please  enter name',
        'email.required'=>'Please enter email'];
       

    $rules = [
        'discussion_name'=>'required|not_in:0',
        'name' => 'required',
        'email' => 'required|email'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }
    public function sendinvite(Request $request)
    {
        $validator = $this->validatorinvite($request->all());
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }
    }

}