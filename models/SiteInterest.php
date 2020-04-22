<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%site_interest}}".
 *
 * @property int $site_id
 * @property int $interest_id
 *
 * @property Site $site
 * @property Interest $interest
 */
class SiteInterest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%site_interest}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_id', 'interest_id'], 'required'],
            [['site_id', 'interest_id'], 'integer'],
            [['site_id', 'interest_id'], 'unique', 'targetAttribute' => ['site_id', 'interest_id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
            [['interest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interest::className(), 'targetAttribute' => ['interest_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'site_id' => Yii::t('app', 'Site ID'),
            'interest_id' => Yii::t('app', 'Interest ID'),
        ];
    }

    /**
     * Gets query for [[Site]].
     *
     * @return \yii\db\ActiveQuery|SiteQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }

    /**
     * Gets query for [[Interest]].
     *
     * @return \yii\db\ActiveQuery|InterestQuery
     */
    public function getInterest()
    {
        return $this->hasOne(Interest::className(), ['id' => 'interest_id']);
    }

    /**
     * {@inheritdoc}
     * @return SiteInterestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteInterestQuery(get_called_class());
    }
}
