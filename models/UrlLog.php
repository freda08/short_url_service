<?php

namespace app\models;

use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "url_log".
 *
 * @property int $id
 * @property int $url_id
 * @property string $ip_address
 * @property string|null $visited_at
 *
 * @property Url $url
 */
class UrlLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_id', 'ip_address'], 'required'],
            [['url_id'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::class, 'targetAttribute' => ['url_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'visited_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::class, ['id' => 'url_id']);
    }

}
