<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=[
        'category_name',
        'description',
        'created_by',
        'is_deleted',
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
        ->performedOn(Category::find($id))
       ->withProperties(['info' => Category::find($id)])
       ->event("status changed")
        ->log('Category Status activated');
        }else{
            activity()
            ->performedOn(Category::find($id))
           ->withProperties( Category::find($id))
           ->event("status changed")
        ->log('Category Status Diactivated');
        }
    return $model;
    }
}
