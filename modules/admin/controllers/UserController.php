<?php


namespace app\modules\admin\controllers;

use app\models\User;
use app\models\Message;
use yii\web\Controller;
use Yii;

class UserController extends Controller
{
    public function actionIndex() {
        $users = User::find()->all();
        return $this->render('index', ['users' =>$users]);
    }

    public function actionView($id) {
        $user = User::findOne($id);
        $model = new Message();
        $messages = Message::find()->where([
            'id_sender' =>$id
        ])->orWhere([
            'id_sender' =>Yii::$app->user->identity->id,
            'id_getter' =>$id
        ])->orderBy('date asc')->all();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->saveMessage($id)){
                return $this->redirect(['view', 'id'=>$id]);
            }
        }

        return $this->render('view', [
            'user' => $user,
            'model' =>$model,
            'messages' =>$messages
        ]);
    }
}