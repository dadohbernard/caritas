<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    protected $fillable =[
        'reasons',
        'member_id',
        'amount',
        'user_id',
        'status'
    ];
     /** This model function is used to update status CTR
    *
    * @return bool
    * @author Caritas:kwizera
    */
   public function updateStatuses($id,$status) {

       $support = Support::where('id', $id)->update(['status'=>$status]);

        // Find the updated Support model
        $supportModel = Support::find($id);

        // Check if the model exists
        if ($supportModel) {
            // Log the activity
            activity()
                ->performedOn($supportModel)
                ->withProperties(['info' => $supportModel])
                ->event("status changed")
                ->log($status == 1 ? 'Support paid' : 'Support not paid');
        }

        return $support;
}
}
