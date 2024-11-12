<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;
    protected $fillable =[
        'center_name',
        'user_id',
        'status',
    ];

    public function deleteModel($id) {

        $model = $this->where('id', $id)->update(['is_deleted'=>1]);
        activity()
        ->performedOn(Category::find($id))
       ->withProperties(Category::find($id))
       ->event('deleted')
       ->log('Category deleted');
       return $model;
     }
    /** This model function is used to update status CTR
    *
    * @return bool
    * @author Caritas:kwizera
    */
   public function updateStatus($id,$status) {
       $model = $this->where('id', $id)->update(['status'=>$status]);
       if($status == 0){
        activity()
        ->performedOn(Center::find($id))
       ->withProperties(['info' => Center::find($id)])
       ->event("status changed")
        ->log('Centrale Status activated');
        }else{
            activity()
            ->performedOn(Center::find($id))
           ->withProperties( Center::find($id))
           ->event("status changed")
        ->log('Centrale Status Diactivated');
        }
    return $model;
    }
}
