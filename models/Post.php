<?php

namespace altiore\article\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer  $id
 * @property integer  $type_id
 * @property integer  $resource_id
 * @property integer  $created_at
 * @property integer  $updated_at
 * @property PostType $type
 */
class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['type_id', 'resource_id'], 'integer'],
            [
                ['type_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => PostType::className(),
                'targetAttribute' => ['type_id' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'type_id'     => Yii::t('app', 'Type ID'),
            'resource_id' => Yii::t('app', 'Resource ID'),
            'created_at'  => Yii::t('app', 'Created At'),
            'updated_at'  => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(PostType::className(), ['id' => 'type_id']);
    }
}
