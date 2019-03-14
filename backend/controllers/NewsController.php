<?php

namespace app\controllers;

use Yii;
use app\models\News;
use app\models\TopNews;
use app\utils\MongodbUtil;
use app\utils\CurlUtil;
use app\models\Config;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;

class NewsController extends BaseController
{
    public function actionSaveNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['title']) && empty($params['img']) && empty($params['newsUrl'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $news = new News();
        $news->title = $params['title'];
        $news->img = $params['img'];
        $news->newsUrl = $params['newsUrl'];
        $result = $news->save();
        if(!$news->save()) {
          return ['code' => 200, 'message' => 'failed'];
        }
        return ['code' => 200, 'message' => 'success'];
    }

    public function actionEditNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id']) && empty($params['title']) && empty($params['img']) && empty($params['newsUrl'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $news = News::findOne(['_id' => $params['id']]);
        $news->title = $params['title'];
        $news->img = $params['img'];
        $news->newsUrl = $params['newsUrl'];
        if(!$news->save()) {
          return ['code' => 200, 'message' => 'failed'];
        }
        return ['code' => 200, 'message' => 'success'];
    }

    public function actionDeleteNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $news = News::findOne(['_id' => MongodbUtil::convertToMongoId($params['id'])]);
        if ( !$news->delete() ) {
            throw new ServerErrorHttpException('deleted project failed');
        }

        return ['code' => 200];
    }

    public function actionSaveTopNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['title']) && empty($params['img']) && empty($params['newsUrl'])) {
            throw new BadRequestHttpException('miss parameter');
        }
        $count = TopNews::find()->where(['isDeleted' => TopNews::NOT_DELETED])->count();
        if ($count >= 5){
            return ['code' => 200, 'message' => 'failed'];
        }

        $topNews = new TopNews();
        $topNews->title = $params['title'];
        $topNews->img = $params['img'];
        $topNews->newsUrl = $params['newsUrl'];
        if(!$topNews->save()) {
          return ['code' => 200, 'message' => 'failed'];
        }
        return ['code' => 200, 'message' => 'success'];
    }

    public function actionEditTopNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id']) && empty($params['title']) && empty($params['img']) && empty($params['newsUrl'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $topNews = TopNews::findOne(['_id' => $params['id']]);
        $topNews->title = $params['title'];
        $topNews->img = $params['img'];
        $topNews->newsUrl = $params['newsUrl'];
        if(!$topNews->save()) {
          return ['code' => 200, 'message' => 'failed'];
        }
        return ['code' => 200, 'message' => 'success'];
    }

    public function actionDeleteTopNews()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $topNews = TopNews::findOne(['_id' => MongodbUtil::convertToMongoId($params['id'])]);
        if ( !$topNews->delete() ) {
            throw new ServerErrorHttpException('deleted project failed');
        }

        return ['code' => 200];
    }

    public function actionViewTop()
    {
        $query = TopNews::find()->where(['isDeleted' => TopNews::NOT_DELETED]);
        $count = $query->count();
        $orderBy['createdAt'] = SORT_ASC;
        $query = $query->orderBy($orderBy);
        $res = $query->all();
        return [
            'code' => 200,
            'data' => $res,
            'count' => $count
        ];
    }

    public function actionNews()
    {
        $query = News::find()->where(['isDeleted' => News::NOT_DELETED]);
        $count = $query->count();
        $orderBy['createdAt'] = SORT_DESC;
        $query = $query->orderBy($orderBy);
        $pagination = new Pagination(['totalCount' => $count]);
        $news = $query->offset($pagination->offset)->limit($pagination->limit)->all();

        return [
            'code' => 200,
            'data' => [
                'items' => $news,
                'currentPage' => $pagination->getPage(),
                'pageCount' => $pagination->getPageCount(),
                'perPage' => $pagination->getPageSize(),
                'totalCount' => $count

            ],
            'message' => 'success'
        ];
    }

    /**
     * For h5 page
     */
    public function actionIndex()
    {
        $query = News::find()->where(['isDeleted' => News::NOT_DELETED]);
        $count = $query->count();
        $orderBy['createdAt'] = SORT_DESC;
        $query = $query->orderBy($orderBy);
        $pagination = new Pagination(['totalCount' => $count]);
        $newsList = $query->offset($pagination->offset)->limit($pagination->limit)->all();

        $config = Config::findOne();
        if ($config['switch'] === 'false') {
            $newsList = [];
        }
        return [
            'code' => 200,
            'data' => [
                'items' => $newsList,
                'currentPage' => $pagination->getPage(),
                'pageCount' => $pagination->getPageCount(),
                'perPage' => $pagination->getPageSize(),
                'totalCount' => $count

            ],
            'message' => 'success'
        ];
    }
}
