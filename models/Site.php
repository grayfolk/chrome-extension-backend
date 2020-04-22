<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\helpers\FeedHelper;

/**
 * This is the model class for table "{{%site}}".
 *
 * @property int $id
 * @property string $site
 * @property string $feed
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $parsed_at
 *
 * @property SiteInterest[] $siteInterests
 * @property Interest[] $interests
 */
class Site extends \yii\db\ActiveRecord
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public static function tableName()
    {
        return '{{%site}}';
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
                    'site'
                ],
                'required'
            ],
            [
                [
                    'site'
                ],
                'validateSite'
            ],
            [
                [
                    'created_at',
                    'updated_at',
                    'parsed_at'
                ],
                'integer'
            ],
            [
                [
                    'site',
                    'feed'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'site',
                    'feed'
                ],
                'url',
                'defaultScheme' => 'http'
            ]
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
            'site' => Yii::t('app', 'Site'),
            'feed' => Yii::t('app', 'Feed'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'parsed_at' => Yii::t('app', 'Parsed At')
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        $this->unlinkAll('interests', true);
        
        if (Yii::$app->request->post('interests') && is_array(Yii::$app->request->post('interests'))) {
            foreach (Yii::$app->request->post('interests') as $interest) {
                if ($i = Interest::findOne($interest)) {
                    $this->link('interests', $i);
                }
            }
        }
    }

    public function validateSite($attribute, $params)
    {
        if (! $this->isNewRecord) {
            return true;
        }
        
        if (parse_url($this->$attribute, PHP_URL_SCHEME) === null) {
            $this->$attribute = 'http://' . $this->$attribute;
        }
        
        // Trying to get RSS Feed from url
        $feed = FeedHelper::checkUrl($this->$attribute);
        
        if (! $feed)
            $this->addError($attribute, Yii::t('app', 'This site not contains RSS Feed. Please add RSS Feed url directly or add another site.'));
        
        else
            $this->feed = $feed;
    }

    /**
     * Gets query for [[SiteInterests]].
     *
     * @return \yii\db\ActiveQuery|SiteInterestQuery
     */
    public function getSiteInterests()
    {
        return $this->hasMany(SiteInterest::class, [
            'site_id' => 'id'
        ]);
    }

    /**
     * Gets query for [[Interests]].
     *
     * @return \yii\db\ActiveQuery|InterestQuery
     */
    public function getInterests()
    {
        return $this->hasMany(Interest::class, [
            'id' => 'interest_id'
        ])->viaTable('{{%site_interest}}', [
            'site_id' => 'id'
        ]);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @return SiteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SiteQuery(get_called_class());
    }
}
