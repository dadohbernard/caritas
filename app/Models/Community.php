<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $fillable =[
        'community_name',
        'user_id',
        'center_id',
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
        ->performedOn(Community::find($id))
       ->withProperties(['info' => Community::find($id)])
       ->event("status changed")
        ->log('Community Status activated');
        }else{
            activity()
            ->performedOn(Community::find($id))
           ->withProperties( Community::find($id))
           ->event("status changed")
        ->log('Community Status Diactivated');
        }
    return $model;
    }
}
