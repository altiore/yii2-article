<?php

namespace altiore\article;

use Faker\Provider\Image;
use yii\base\Module;

/**
 * article module definition class
 */
class ArticleModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'altiore\article\controllers';
    /**
     * @var string
     */
    public $defaultRoute = 'article';
    /**
     * @var string
     */
    public $commentModel = '';
    /**
     * @var string
     */
    public $userModel = '';

    public $imgModel = \common\models\Image::class;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
