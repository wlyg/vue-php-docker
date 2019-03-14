<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;
use app\models\Project;
use app\models\Advertisement;
use yii\helpers\Json;
use yii\web\ServerErrorHttpException;
use yii\data\Pagination;
use app\models\Config;
use yii\data\ActiveDataProvider;
use app\utils\MongodbUtil;

class ProjectController extends BaseController
{
    public function actionCreateProject()
    {
        $params = Yii::$app->request->post();
        if (empty($params['formItem']) && empty($params['coverImg']) && empty($params['panoramaImg']) && empty($params['infoImg']) && empty($params['houseImg'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $project = new Project;
        $project->code = $params['formItem']['code'];
        $project->name = $params['formItem']['name'];
        $project->averagePrice = $params['formItem']['averagePrice'];
        $project->connect = $params['formItem']['connect'];
        $project->location = $params['formItem']['location'];
        $project->position = [
            'latitude' => $params['formItem']['latitude'],
            'longitude' => $params['formItem']['longitude']
        ];
        $project->type = $params['formItem']['type'];
        $project->phone = $params['formItem']['phone'];
        $project->characteristic = $params['formItem']['characteristic'];
        $project->houseType = $params['formItem']['houseType'];
        $project->traffic = $params['formItem']['traffic'];
        $project->facilities = $params['formItem']['facilities'];
        $project->periphery = $params['formItem']['periphery'];
        $project->award = $params['formItem']['award'];
        $project->fixedYear = $params['formItem']['fixedYear'];
        $project->makeHouse = $params['formItem']['makeHouse'];
        $project->parking = $params['formItem']['parking'];
        $project->green = $params['formItem']['green'];
        $project->coverImg = $params['coverImg'];
        $project->panoramaImg = $params['panoramaImg'];
        $project->infoImg = $params['infoImg'];
        $project->houseImg = $params['houseImg'];
        $project->designerImg = $params['designerImg'];

        if (!$project->save()) {
            throw new ServerErrorHttpException('save project failed');
        }

        return [
           'code' => 200,
           'message' => 'ok'
        ];
    }

    public function actionEditProject()
    {
        $params = Yii::$app->request->post();
        if (empty($params['formItem']) && empty($params['coverImg']) && empty($params['panoramaImg']) && empty($params['infoImg']) && empty($params['houseImg'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $project = Project::findOne(['_id' => $params['formItem']['id']['$oid']]);
        $project->code = $params['formItem']['code'];
        $project->name = $params['formItem']['name'];
        $project->averagePrice = $params['formItem']['averagePrice'];
        $project->connect = $params['formItem']['connect'];
        $project->location = $params['formItem']['location'];
        $project->position = [
            'latitude' => $params['formItem']['latitude'],
            'longitude' => $params['formItem']['longitude']
        ];
        $project->type = $params['formItem']['type'];
        $project->phone = $params['formItem']['phone'];
        $project->characteristic = $params['formItem']['characteristic'];
        $project->houseType = $params['formItem']['houseType'];
        $project->traffic = $params['formItem']['traffic'];
        $project->facilities = $params['formItem']['facilities'];
        $project->periphery = $params['formItem']['periphery'];
        $project->award = $params['formItem']['award'];
        $project->fixedYear = $params['formItem']['fixedYear'];
        $project->makeHouse = $params['formItem']['makeHouse'];
        $project->parking = $params['formItem']['parking'];
        $project->green = $params['formItem']['green'];
        $project->coverImg = $params['coverImg'];
        $project->panoramaImg = $params['panoramaImg'];
        $project->infoImg = $params['infoImg'];
        $project->houseImg = $params['houseImg'];
        $project->designerImg = $params['designerImg'];

        if (!$project->save()) {
            throw new ServerErrorHttpException('edit project failed');
        }

        return [
           'code' => 200,
           'message' => 'ok'
        ];
    }

    public function actionCodeAndView()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id']) && empty($params['isPublished']) && empty($params['code'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $project = Project::findOne(['_id' => $params['id']['$oid']]);
        $project->isPublished = $params['isPublished'];
        $project->code = $params['code'];


        if (!$project->save()) {
            throw new ServerErrorHttpException('edit project failed');
        }

        return [
           'code' => 200,
           'message' => 'ok'
        ];
    }

    public function actionGetProject()
    {
        $params = Yii::$app->request->get();

        $project = Project::search($params);
        if(!$project){
            return [
                'code' => 300,
                'message' => 'noData'
            ];
        }

        return [
            'code' => 200,
            'data' => $project
        ];
    }

    public function actionDeleteProject()
    {
        $params = Yii::$app->request->post();
        if (empty($params['id'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $project = Project::findOne(['_id' => $params['id']['$oid']]);
        if ( !$project->delete() ) {
            throw new ServerErrorHttpException('deleted project failed');
        }

        return ['code' => 200];
    }

    public function actionGetAdvertisement()
    {
        $advertisement = Advertisement::findOne();
        if ( !$advertisement ) {
            return [
                'code' => 200,
                'message' => 'failed'
            ];
        }
        return [
            'code' => 200,
            'data' => $advertisement,
            'message' => 'success'
        ];
    }

    public function actionSaveAdvertisement()
    {
        $params = Yii::$app->request->post();
        if (empty($params['adImg'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $advertisement = Advertisement::findOne();
        if ( !$advertisement ) {
            $advertisement = new Advertisement;
        }
        $advertisement->adImg = $params['adImg'];

        if (!$advertisement->save()) {
            throw new ServerErrorHttpException('save advertisement failed');
        }
        return [
           'code' => 200,
           'message' => 'ok'
        ];
    }

    public function actionSavePicture()
    {
        $params = Yii::$app->request->post();
        $file = $params['file'];
        $imgData = substr($file['url'],strpos($file['url'],',')+1);
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz
            ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $fileName = $file['uid'].'-';
        for($i=0;$i<20;$i++)   {
            $fileName.= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        preg_match("@data:image/(.*);base64@", substr($file['url'],0,50), $imgType);
        $decodedData = base64_decode($imgData);
        if(!file_exists('../web/upload/')){
            mkdir('../web/upload/');
            chmod('../web/upload/',0777);
        }
        if (file_put_contents('../web/upload/'.$fileName.'.'.$imgType[1],$decodedData)){
            return [
                'code' => 200,
                'fileName' => 'https://api.wanliyangguang.cn/upload/'.$fileName.'.'.$imgType[1]
            ];
        }
    }


    /**
     * For h5 page
     */
    public function actionIndex()
    {
        $query = Project::find()->where(['isDeleted' => Project::NOT_DELETED, 'isPublished' => true]);
        $count = $query->count();
        $orderBy['code'] = SORT_ASC;
        $query = $query->orderBy($orderBy);
        $pagination = new Pagination(['totalCount' => $count]);
        $projects = $query->offset($pagination->offset)->limit($pagination->limit)->all();

        $config = Config::findOne();
        if ($config['switch'] === 'false') {
            $projects = [];
        }

        return [
            'code' => 200,
            'data' => [
                'items' => $projects,
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
    public function actionView($id)
    {
        if (empty($id)) {
            throw new BadRequestHttpException('miss parameter');
        }
        $id = MongodbUtil::convertToMongoId($id);
        $project = Project::findByPk($id);

        return [
            'code' => 200,
            'data' => $project,
            'message' => 'success'

        ];
    }
}
