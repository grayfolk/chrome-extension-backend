<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\db\Expression;
use app\models\News;

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
        
        file_put_contents(__DIR__ . '/api.log', print_r($request->getHeaders(), true), FILE_APPEND);
        file_put_contents(__DIR__ . '/api.log', print_r($request->getHeaders()->get('Interests'), true), FILE_APPEND);
        return News::find()->orderBy(new Expression('rand()'))->one();
    }
}
