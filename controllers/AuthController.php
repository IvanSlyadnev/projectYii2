<?php


namespace app\controllers;
use app\models\ImageApload;
use app\models\Login;
use app\models\Signup;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use Yii;
class AuthController extends Controller
{
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Login();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model
        ]);

    }


    public function actionSignup()
    {
        $model = new Signup();
        $imageModel = new ImageApload();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $imageModel->image = $model->photo;
            $file = UploadedFile::getInstance($model, 'photo');
            if ($model->signup($imageModel->uploadPhoto($file))) {
                return $this->redirect(['auth/login']);
            }
        }
        return $this->render('signup', [
            'model' =>$model
        ]);
    }

    public function actionLogou() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->redirect(['site/index']);
        }
    }

    public function actionTest(){
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
}