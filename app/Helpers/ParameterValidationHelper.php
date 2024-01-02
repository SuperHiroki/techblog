<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use InvalidArgumentException;

class ParameterValidationHelper
{
    public static function validateParametersSortAuthors(Request $request)
    {
        $params = $request->all();

        //バリデーションチェック
        if (empty($params['sort'])) {
            throw new InvalidArgumentException('Sort parameter is required.');
        }

        if (($params['sort'] == 'followers' || $params['sort'] == 'articles' || $params['sort'] == 'alphabetical') && !empty($params['period'])) {
            throw new InvalidArgumentException('Period parameter should not be provided for this sort type.');
        }

        if (($params['sort'] == 'trending_followers' || $params['sort'] == 'trending_articles') && empty($params['period'])) {
            throw new InvalidArgumentException('Period parameter is required for trending sort.');
        }
    }
}
