<?php

namespace altiore\article\models;

use Yii;

/**
 * This is the model class for table "{{%post_type}}".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property Post[]  $posts
 */
class PostType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'name'        => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return array
     */
    public static function column()
    {
        return static::find()->select(['name'])->indexBy('id')->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['type_id' => 'id']);
    }
}
