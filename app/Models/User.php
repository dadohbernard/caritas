<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'email',
        'password',
        'first_name',
        'last_name',
        'phone_number',
        'community_id',
        'centrale_id',
        'email',
        'password',
        'role',
        'is_delete',
        'profile_picture',
        'created_by',
        'status'
    ];


    // protected static $recordEvents = ['created','updated','deleted'];
    // Implement the missing abstract method
    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()->logOnly(["first_name","last_name","email",]);
}

public function updateExistUser(array $data)
{
    // Your logic to update the existing user goes here

    // For example, you might use Eloquent's update method
    $this->update($data);

    // Log the activity
    // activity()->performedOn($this)
    //     ->withProperties(['data' =>$data])
    //     ->log('User updated');

    // Return the updated user
    return $this;
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

        'password',
        'remember_token'

    ];
    public function getAllUsersAjax($role='') {
        $query = $this->select('*', 'users.id as user_id');

        if($role){
             $query->where('users.role', $role);
         }

          $query->where('users.role', '!=', 0);

          $query->where('users.is_delete', '=', 0);

          return $query;
     }

      /**
     * This model function is used to create user
     *
     * @return bool
     * @author Techaffinity:kwizeraa
     */
    public static function createUser($info) {

        $user= \DB::table('users')->insertGetId([
                                 'first_name'=>$info['first_name'],
                                 'last_name'=>$info['last_name'],
                                 'email'=>$info['email'],
                                //  'phone_number'=>isset($info['phone_number'])?$info['phone_number']:'',

                                 'community_id'=>isset($info['community_id'])?$info['community_id']:'',
                                 'centrale_id'=>isset($info['centrale_id'])?$info['centrale_id']:'',

                                 'profile_picture'=>isset($info['profile_pic'])?$info['profile_pic']:'',
                                 'created_by'=>isset($info['created_by'])?$info['created_by']:'',
                                 'role'=>$info['role'],
                                 'status'=>$info['status'],

                                 'password'=>isset($info['encryptpassword'])?$info['encryptpassword']:Hash::make(rand(54542,55464)),
                                 'created_at'=>date('Y-m-d H:i:s'),
                                 'updated_at'=>date('Y-m-d H:i:s'),
                             ]);
                //           // Log the activity
                //          activity()
                //        ->performedOn(User::find($user))
                //       ->withProperties(['info' => $info])
                //       ->event('new user')
                //    ->log('New User added');
                 return $user;

     }
     /**
     * This model function is used to update user
     *
     * @return bool
     * @author Techaffinity:kwizeraa
     */
    public static function updateUser($info) {
        $data = [
                    'first_name'=>$info['first_name'],
                    'last_name'=>$info['last_name'],
                    'email'=>$info['email'],
                    'community_id' => $info['community_id'],
                    'centrale_id' => $info['centrale_id'],
                    // 'phone_number'=>isset($info['phone_number'])?$info['phone_number']:'',
                    'is_delete' => $info['is_delete'],
                    'status'=>$info['status'],
                    'role'=>$info['role'],
                ];

        if(isset($info['profile_pic']) && $info['profile_pic']!=''){
            $data['profile_picture'] = $info['profile_pic'];
        }
       $user= User::where('id', $info['id'])
        ->update($data);
        // Log the activity
    //     activity()
    //     ->performedOn(User::find($user))
    //     ->event("updated")
    //    ->withProperties(['info' => $info])
    // ->log('User updated');

       return $user;
    }
    public static function updateUserProfile($info) {
        $data = [
                    'first_name'=>$info['first_name'],
                    'last_name'=>$info['last_name'],
                    'email'=>$info['email'],
                    'phone_number'=>isset($info['phone_number'])?$info['phone_number']:'',
                    'is_delete' => $info['is_delete'],
                    'status'=>$info['status'],
                    'role'=>$info['role'],
                ];

        if(isset($info['profile_pic']) && $info['profile_pic']!=''){
            $data['profile_picture'] = $info['profile_pic'];
        }



       $user= User::where('id', $info['id'])
                    ->update($data);

                    // Log the activity
        // activity()
        // ->performedOn(User::find($user))
        // ->withProperties(['info' => $info])
        // ->event('update')
        // ->log('User profile updated');
    return $user;
    }
/**
     * This model function is used to delete user
     *
     * @return bool
     * @author Techaffinity:kwizera
     */
    public function deleteUser($id) {

       $user= $this->where('id', $id)->update(['is_delete'=>1]);
    //    activity()
    //     ->performedOn(User::find($id))
    //    ->withProperties(User::find($id))
    //    ->event('deleted')
    // ->log('User deleted');
    return $user;
     }
     /**
     * This model function is used to update status place
     *
     * @return bool
     * @author Techaffinity:kwizera
     */
    public function updateStatus($id,$status) {
        $user= $this->where('id', $id)->update(['status'=>$status]);
    //     if($status == 0){
    //     activity()
    //     ->performedOn(User::find($id))
    //    ->withProperties(['info' => User::find($id)])
    //    ->event("status changed")
    //     ->log('User Status activated');
    //     }else{
    //         activity()
    //         ->performedOn(User::find($id))
    //        ->withProperties( User::find($id))
    //        ->event("status changed")
    //     ->log('User Status Diactivated');
    //     }
       return $user;
     }
      /**
     * This model function is used to delete images
     *
     * @return bool
     * @author Techaffinity:karunakarans
     */

    public function deleteUserImages($id)
    {

        $imagePath = User::select('profile_picture')->where('id', $id)->first();

         $filePath = $imagePath->profile_picture;

        if (file_exists($filePath)) {

           unlink($filePath);

          return $this->where('id', $id)->update(['profile_picture'=>""]);

        }else{
         return $this->where('id', $id)->update(['profile_picture'=>""]);
        }
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

}
