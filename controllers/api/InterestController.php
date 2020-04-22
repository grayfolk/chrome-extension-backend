<?php
namespace app\controllers\api;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;

class InterestController extends ActiveController
{

    public $modelClass = 'app\models\Interest';

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
     *
     * {@inheritDoc}
     *
     * @see \yii\rest\ActiveController::actions()
     */
    public function actions(): array
    {
        $actions = parent::actions();
        
        unset($actions['delete'], $actions['create'], $actions['view'], $actions['update']);
        
        return $actions;
    }
}
