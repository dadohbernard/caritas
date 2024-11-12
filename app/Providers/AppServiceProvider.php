<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

         Passport::enablePasswordGrant();
       View::composer('*', function ($view) {
    // Initialize the data array
    $data = [];

    // Get the authenticated user
    $authUser = Auth::user();

    // Check if a user is authenticated
    if ($authUser) {
        // Define the query for user details
        $query = User::join('centers', 'centers.id', '=', 'users.centrale_id')
            ->join('communities', 'communities.id', '=', 'users.community_id')
            ->select('users.*', 'communities.community_name', 'centers.center_name')
            ->where('users.id', $authUser->id);

        // Apply conditions based on user role for the details query
        if ($authUser->role != 1 && $authUser->role != 5) {
            $query->where('users.community_id', $authUser->community_id);
        } elseif ($authUser->role == 4) {
            $query->where('users.centrale_id', $authUser->centrale_id);
        }

        // Execute the details query and get the first result
        $data['details'] = $query->first();

        // Now, calculate the total amount based on the user's role
        $userRole = $authUser->role;

        $sum = Income::join('users', 'users.id', '=', 'incomes.user_id')
            ->join('incomes_source', 'incomes_source.id', '=', 'incomes.income_source')
            ->leftJoin('centers', 'centers.id', '=', 'users.centrale_id')
            ->leftJoin('communities', 'communities.id', '=', 'users.community_id')
            ->select(
                DB::raw("
                    FLOOR(SUM(
                        CASE
                            WHEN incomes_source.id = 1 THEN
                                CASE
                                    WHEN $userRole = 2 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 4 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 5 THEN incomes.amount * 3 / 4
                                    WHEN $userRole = 3 THEN incomes.amount - (incomes.amount * 3 / 4)
                                    ELSE 0
                                END
                            WHEN incomes_source.id = 2 THEN
                                CASE
                                    WHEN $userRole = 2 THEN incomes.amount / 2
                                    WHEN $userRole = 4 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 5 THEN incomes.amount / 2
                                    WHEN $userRole = 3 THEN incomes.amount - incomes.amount
                                    ELSE 0
                                END
                            WHEN incomes_source.id = 3 THEN incomes.amount
                            ELSE 0
                        END
                    )) AS total_amount
                ")
            )
            ->where(function ($incomes) use ($authUser, $userRole) {
                // Apply role-based conditions
                if ($userRole == 2) {
                    $incomes->where('users.community_id', $authUser->community_id);
                } elseif ($userRole == 4) {
                    $incomes->where('users.centrale_id', $authUser->centrale_id);
                }
            })
            ->first();

        // Assign the total amount to the data array
        $data['amount'] = $sum->total_amount;
    }

    // Share the data with all views
    $view->with('data', $data);
});

    }

}
