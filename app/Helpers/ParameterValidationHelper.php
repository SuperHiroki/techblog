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
            throw new InvalidArgumentException('WWWWWWWW Sort parameter is required.');
        }

        if (($params['sort'] == 'followers' || $params['sort'] == 'articles' || $params['sort'] == 'alphabetical') && !empty($params['period'])) {
            throw new InvalidArgumentException('WWWWWWWW Period parameter should not be provided for this sort type.');
        }

        if (($params['sort'] == 'trending_followers' || $params['sort'] == 'trending_articles') && empty($params['period'])) {
            throw new InvalidArgumentException('WWWWWWWW Period parameter is required for trending sort.');
        }
    }

    public static function validateParametersSortArticles(Request $request)
    {
        $params = $request->all();

        //バリデーションチェック
        if (empty($params['sort'])) {
            throw new InvalidArgumentException('TTTTTTTTT Sort parameter is required.');
        }

        if (($params['sort'] == 'likes' || $params['sort'] == 'bookmarks' || $params['sort'] == 'archives' || $params['sort'] == 'newest') && !empty($params['period'])) {
            throw new InvalidArgumentException('TTTTTTTTT Period parameter should not be provided for this sort type.');
        }

        if (($params['sort'] == 'trending_likes' || $params['sort'] == 'trending_bookmarks' || $params['sort'] == 'trending_archives') && empty($params['period'])) {
            throw new InvalidArgumentException('TTTTTTTTT Period parameter is required for trending sort.');
        }
    }
}
