<?php


namespace app\controllers;
use app\models\User;
use Yii;
use app\models\Message;
use yii\web\Controller;

class ChatController extends Controller
{
    public function actionCheck() {
        if (Yii::$app->request->isAjax) {
            $data = ['searchname' =>1, 'searchby' =>2];
            $searchname= explode(":", $data['searchname']);
            $searchby= explode(":", $data['searchby']);
            $searchname= $searchname[0];
            $searchby= $searchby[0];
            $search = // your logic;
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'search' => $search,
                'code' => 100,
            ];
        }
    }
    public function actionIndex() {

        //var_dump(date('Y-m-d H:i:s'));
        $model = new Message();
        $adminstrator = User::find()->where(['isAdmin'=>1])->one();

        /*$adminMessages = Message::find()->where(['id_sender'=>$adminstrator->id, 'id_getter'=>Yii::$app->user->identity->id])->all();
        $userMessages = Message::find()->where(['id_sender' =>Yii::$app->user->identity->id])->all();
        $messeages = array_merge($adminMessages, $userMessages);*/
        $messeages = Message::find()->where([
            'id_sender' =>Yii::$app->user->identity->id
        ])->orWhere(['id_sender'=>$adminstrator->id,
                    'id_getter'=>Yii::$app->user->identity->id
                ]
        )->orderBy('date asc')->all();



        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->saveMessage($adminstrator->id)){
                return $this->redirect(['index']);
            }
        }

        return $this->render('index',[
            'administrator' =>$adminstrator,
            'messages' =>$messeages,
            'model' =>$model
        ]);
    }
}