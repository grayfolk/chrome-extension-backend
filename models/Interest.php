<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%interest}}".
 *
 * @property int $id
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SiteInterest[] $siteInterests
 * @property Site[] $sites
 */
class Interest extends \yii\db\ActiveRecord
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function tableName()
    {
        return '{{%interest}}';
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [
                        'created_at',
                        'updated_at'
                    ],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'updated_at'
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function rules()
    {
        return [
            [
                [
                    'title'
                ],
                'required'
            ],
            [
                [
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'title'
                ],
                'string',
                'max' => 255
            ]
        ];
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \yii\db\BaseActiveRecord::fields()
     */
    public function fields()
    {
        return [
            'id',
            'title'
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * Gets query for [[SiteInterests]].
     *
     * @return \yii\db\ActiveQuery|SiteInterestQuery
     */
    public function getSiteInterests()
    {
        return $this->hasMany(SiteInterest::class, [
            'interest_id' => 'id'
        ]);
    }

    /**
     * Gets query for [[Sites]].
     *
     * @return \yii\db\ActiveQuery|SiteQuery
     */
    public function getSites()
    {
        return $this->hasMany(Site::class, [
            'id' => 'site_id'
        ])->viaTable('{{%site_interest}}', [
            'interest_id' => 'id'
        ]);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return InterestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InterestQuery(get_called_class());
    }
}
