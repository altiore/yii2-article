<?php

namespace altiore\article\controllers;

use linslin\yii2\curl\Curl;
use Yii;
use altiore\article\models\Video;
use altiore\article\models\VideoSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionLoad()
    {
        $params = Yii::$app->params['google_auth'];
        $url = 'https://accounts.google.com/o/oauth2/auth';
        $queryParams = implode('&', [
            'client_id=' . $params['client_id'],
            'redirect_uri=' . urlencode(Yii::$app->urlManager->createAbsoluteUrl(['video/callback'])),
            'scope=' . $params['scope'],
            'response_type=code',
        ]);

        return $this->redirect($url . '?' . $queryParams);
    }

    public function actionCallback()
    {
        $token = $this->getToken();

        $curl = new Curl();
        $videos = [];
        $count = 1;
        $next = false;
        while (count($videos) < $count) {
            $data = $this->getData($curl, $token, $next);
            $next = property_exists($data, 'nextPageToken') ? $data->nextPageToken : false;
            $newVideos = $data->items;
            $videos = ArrayHelper::merge($videos, $newVideos);
            $count = $data->pageInfo->totalResults;
            foreach ($newVideos as $video) {
                $videoExists = Video::find()
                    ->where([
                        'url' => 'https://www.youtube.com/embed/' . $video->id->videoId,
                    ])
                    ->exists();
                if ($videoExists) {
                    $count = 0;
                    break;
                }
                $video = new Video([
                    'chanel_id'    => 1,
                    'url'          => 'https://www.youtube.com/embed/' . $video->id->videoId,
                    'title'        => $video->snippet->title,
                    'desc'         => $video->snippet->description,
                    'published_at' => strtotime($video->snippet->publishedAt),
                ]);
                $video->save();
            }
        }

        $this->layout = false;

        return $this->renderContent(json_encode($data));
    }

    protected function getToken()
    {
        $curl = new Curl();

        $params = Yii::$app->params['google_auth'];

        $url = 'https://accounts.google.com/o/oauth2/token';
        $response = $curl
            ->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query([
                        'code'          => Yii::$app->getRequest()->get('code'),
                        'client_id'     => $params['client_id'],
                        'client_secret' => $params['client_secret'],
                        'redirect_uri'  => Yii::$app->urlManager->createAbsoluteUrl(['video/callback']),
                        'grant_type'    => 'authorization_code',
                    ]
                )
            )
            ->post($url);

        $responseStdClass = json_decode($response);

        return $responseStdClass->access_token;
    }

    /**
     * @param Curl    $curl
     * @param  string $token
     * @param bool    $next
     *
     * @return mixed
     */
    protected function getData($curl, $token, $next = false)
    {
        $queryParams = [
            'channelId'    => 'UCugGQpv1WWxW_hqSQSURVDA',
            'part'         => 'snippet',
            'type'         => 'video',
            //'type'         => 'playlist',
            'order'        => 'date',
            'access_token' => $token,
        ];

        if ($next) {
            $queryParams['pageToken'] = $next;
        }

        $response = $curl
            ->get('https://www.googleapis.com/youtube/v3/search?' . $this->prepareQueryParams($queryParams));

        return json_decode($response);
    }

    protected function prepareQueryParams($queryParamsArray = [])
    {
        $prepareQuery = '';
        foreach ($queryParamsArray as $param => $value) {
            $prepareQuery .= $param . '=' . $value . '&';
        }

        return $prepareQuery;
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
