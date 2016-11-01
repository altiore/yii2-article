<?php

namespace altiore\article\models;

use Yii;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property integer $id
 * @property integer $chanel_id
 * @property string  $url
 * @property string  $title
 * @property string  $desc
 * @property integer $published_at
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chanel_id', 'url', 'published_at'], 'required'],
            [['chanel_id', 'published_at'], 'integer'],
            [['desc'], 'string'],
            [['url', 'title'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'chanel_id'    => Yii::t('app', 'Chanel ID'),
            'url'          => Yii::t('app', 'Url'),
            'title'        => Yii::t('app', 'Title'),
            'desc'         => Yii::t('app', 'Desc'),
            'published_at' => Yii::t('app', 'Published At'),
        ];
    }
}
