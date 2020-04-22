<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id
 * @property int $site_id
 * @property string $title
 * @property string|null $description
 * @property string $link
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Site $site
 */
class News extends \yii\db\ActiveRecord
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function tableName()
    {
        return '{{%news}}';
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
                    'site_id',
                    'title',
                    'link'
                ],
                'required'
            ],
            [
                [
                    'site_id',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'link'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'description'
                ],
                'string',
                'max' => 512
            ],
            [
                [
                    'site_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Site::className(),
                'targetAttribute' => [
                    'site_id' => 'id'
                ]
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
            'title',
            'description',
            'link'
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
            'site_id' => Yii::t('app', 'Site ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'link' => Yii::t('app', 'Link'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * Gets query for [[Site]].
     *
     * @return \yii\db\ActiveQuery|SiteQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), [
            'id' => 'site_id'
        ]);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }
}
