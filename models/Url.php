<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

use app\models\UrlLog;
/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property string $url
 * @property string $short_url
 * @property string $created_at
 * @property int|null $visit_count
 */
class Url extends ActiveRecord
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
            [['url', 'url_code'], 'required'],
            [['url'], 'url', 'validSchemes' => ['http', 'https']],
            [['visit_count'], 'integer'],
            [['url'], 'string', 'max' => 2048],
            [['url_code'], 'string', 'max' => 8],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public function logAccess($ip)
    {
        $log = new UrlLog();
        $log->url_id = $this->id;
        $log->ip_address = $ip;
        $log->save();

        $this->visit_count++;
        $this->save(false);
    }
}
