<?php
namespace app\commands;

use yii\console\Controller;
use app\models\Site;
use app\helpers\FeedHelper;

class ParserController extends Controller
{

    public function actionIndex()
    {
        $site = Site::find()->where([
            '<',
            'parsed_at',
            time() - (60 * 5)
        ])->orderBy([
            'parsed_at' => SORT_ASC
        ])->one();
        
        if (! $site)
            return;
        
        FeedHelper::parseFeed($site);
        
        $site->touch('parsed_at');
    }
}
