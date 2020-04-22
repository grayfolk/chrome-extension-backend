<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SiteInterest]].
 *
 * @see SiteInterest
 */
class SiteInterestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SiteInterest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SiteInterest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
