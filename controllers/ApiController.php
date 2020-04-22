<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\db\Expression;
use app\models\News;
use app\models\Site;
use app\models\SiteInterest;
use app\models\Interest;

class ApiController extends Controller
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \yii\rest\Controller::behaviors()
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        
        if (! $request->get('interests'))
            return News::find()->orderBy(new Expression('rand()'))->one();
            
            // Request with interests id
            // SQL query with move news w/o interests to end of results
            // SELECT n.* FROM `news` n join site s on n.site_id = s.id left join site_interest si on s.id = si.site_id left join interest i on si.interest_id = i.id where i.id in(1, 2) or i.id is null order by case when i.id is null then 1 else 0 end, rand()
        
        return (new \yii\db\Query())->select([
            News::tableName() . '.*'
        ])
        ->from(News::tableName())
        ->leftJoin(Site::tableName(), Site::tableName() . '.[[id]] = ' . News::tableName() . '.[[site_id]]')
        ->leftJoin(SiteInterest::tableName(), SiteInterest::tableName() . '.[[site_id]] = ' . Site::tableName() . '.[[id]]')
        ->leftJoin(Interest::tableName(), Interest::tableName() . '.[[id]] = ' . SiteInterest::tableName() . '.[[interest_id]]')
        ->where([
            'in',
            Interest::tableName() . '.[[id]]',
            $request->get('interests')
        ])->orWhere([
            Interest::tableName() . '.[[id]]' => null
        ])->orderBy(new Expression('case when ' . Interest::tableName() . '.[[id]] is null then 1 else 0 end, rand()'))->one();
    }
}
