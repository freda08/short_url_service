<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property string $url
 * @property string $url_code
 * @property string $created_at
 * @property int|null $visit_count
 *
 * @property UrlLog[] $urlLogs
 */
class Url extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_count'], 'default', 'value' => 0],
            [['url', 'url_code', 'created_at'], 'required'],
            [['created_at'], 'safe'],
            [['visit_count'], 'integer'],
            [['url'], 'string', 'max' => 2048],
            [['url_code'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'url_code' => 'Url Code',
            'created_at' => 'Created At',
            'visit_count' => 'Visit Count',
        ];
    }

    /**
     * Gets query for [[UrlLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrlLogs()
    {
        return $this->hasMany(UrlLog::class, ['url_id' => 'id']);
    }

}
