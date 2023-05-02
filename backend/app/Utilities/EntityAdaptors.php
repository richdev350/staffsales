<?php
namespace App\Utilities;

use App\Models\Entities\AdminUser;
use App\Models\Entities\Prefecture;

class EntityAdaptors
{
    public static function getPrefectureName($prefecture_id) {
        $result = Prefecture::find($prefecture_id);
        return ($result)?$result->name:'';
    }

    public static function getAdminUserName($admin_user_id) {
        $result = AdminUser::find($admin_user_id);
        return ($result)?$result->name:'';
    }
}
