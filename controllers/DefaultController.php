<?php

namespace altiore\article\controllers;

use altiore\article\models\Article;
use common\components\ActiveController;
use altiore\article\models\Comment;

/**
 * Class ArticleController
 * @package frontend\controllers
 */
class DefaultController extends ActiveController
{
    /** @var string */
    public $modelClass = Article::class;

    /**
     * Комментраии для одной статьи
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionArticleComments($id)
    {
        $sort = \Yii::$app->request->get('sort');

        switch ($sort) {
            case 'desc' :
                $sort = SORT_DESC;
                break;
            default :
                $sort = SORT_ASC;
        }

        return Comment::find()->where(['article_id' => $id])->orderBy(['id' => $sort])->all();
    }
}
