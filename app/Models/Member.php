<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Member extends Model
{
    use HasFactory;
    protected $fillable =[
       'user_id',
    'cat_id',
    'first_name',
    'last_name',
    'phone',
    'address',
    'bod',  // Corrected from 'bod' to 'dob' based on the validation rules
    'description',
    'support_status',
    // Newly added fields based on the validation rules
    'province_id',
    'district_id',
    'sector_id',
    'cell_id',
    'village_id',
    'resident',
    'identification',
    'disability',
    'parent_status',
    'father_name',
    'official_paper_type',
    'father_dob',
    'id_number',
    'phone_number',
    'job_type',
    'income_per_month',
    'house',
    'education_level',
    'disability_type',
    'head_of_family',
    'mother_name',
    'mother_dob',
    'mother_official_paper_type',
    'mother_id_number',
    'mother_phone_number',
    'mother_job_type',
    'mother_income_per_month',
    'mother_house',
    'mother_education_level',
    'mother_disability_type',
    'mother_head_of_family',
    // Existing additional fields
    'hospital',
    'school_name',
    'sdms_code',
    'other_support',
    'status'
    ];

    public function deleteModel($id) {

        $model = $this->where('id', $id)->update(['is_deleted'=>1]);
        activity()
        ->performedOn(Member::find($id))
       ->withProperties(Member::find($id))
       ->event('deleted')
       ->log('Category deleted');
       return $model;
     }
    /** This model function is used to update status CTR
    *
    * @return bool
    * @author Caritas:kwizera
    */
   public function updateStatus($id,$status,$support_status) {
       $model = $this->where('id', $id)->update(['status'=>$status,'support_status'=>$support_status]);
       if($status == 1){
        activity()
        ->performedOn(Member::find($id))
       ->withProperties(['info' => Member::find($id)])
       ->event("status changed")
        ->log('Member Status activated');
        }else{
            activity()
            ->performedOn(Member::find($id))
           ->withProperties(['info' => Member::find($id)])
           ->event("status changed")
        ->log('Member Status Diactivated');
        }
    return $model;
    }
     /** This model function is used to update status CTR
    *
    * @return bool
    * @author Caritas:kwizera
    */
   public function updateSupportStatus($id,$status) {
       $model = $this->where('id', $id)->update(['support_status'=>$status]);

    return $model;
    }
}
