<?php
namespace App\Services;

class UserServices
{
    public static function getDashboardRouteBasedOnUserRole($userRole){
        if ($userRole === 'participant') {
            return route('participant.dashboard.index');
        }
        if ($userRole === 'organization') {
            return route('organization.dashboard.index');
        }
    }
}
