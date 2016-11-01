<?php

namespace altiore\article\models;

use common\models\Image;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property integer      $id
 * @property string       $title
 * @property string       $text
 * @property integer      $creator_id
 * @property integer      $created_at
 * @property integer      $updater_id
 * @property integer      $updated_at
 * @property ActiveRecord $creator
 * @property ActiveRecord $updater
 * @property Image        $mainImage
 */
class Article extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'creator_id', 'updater_id', 'main_img_id'], 'safe'],
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'text',
            'creator',
            'comments',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'title'       => 'Title',
            'text'        => 'Text',
            'main_img_id' => 'Image ID',
            'creator_id'  => 'Creator ID',
            'created_at'  => 'Created At',
            'updater_id'  => 'Updater ID',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class'              => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImage()
    {
        return $this->hasOne(Yii::$app->getModule('article')->imgModel, ['id' => 'main_img_id']);
    }
}
