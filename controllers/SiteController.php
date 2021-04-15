<?php

namespace app\controllers;

use app\models\Category;
use app\models\CommentForm;
use app\models\ImageApload;
use app\models\Login;
use app\models\SortModel;
use app\models\Subcategory;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Article;
use yii\data\Pagination;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */

    public function actionIndex()
    {

        $sortModel = new SortModel();
        $sortModel->index = Yii::$app->session->getFlash('sort');

        if (Yii::$app->request->isPost) {
            $sortModel->index = (int)Yii::$app->request->post('sort');
            Yii::$app->getSession()->setFlash('sort', $sortModel->index);
        }
        $data = Article::getAll($sortModel->index);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();

        //Получение популярных статей остортированных по возрастанию просмотров
        return $this->render('index', [
            'articles' =>$data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' =>$recent,
            'categories' =>$categories,
            'sortModel' => $sortModel
        ]);
    }

    public function actionAddImage() {

        $model = new ImageApload();
        $user = User::findOne(Yii::$app->user->identity->id);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($model, 'image');
            if ($user->saveImage($model->uploadPhoto($file))) {
                return $this->redirect('index');
            }
        }
        return $this->render('addImage', ['model'=>$model]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionView($id) {
        $data = Article::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $article = Article::findOne($id);
        //$comments = $article->comments;
        $comments = $article->getArticleComments();
        $commentForm = new CommentForm();
        $article->viewedCounter();
        $tags = $article->tags;


        return $this->render('single',
        [
            'article' =>$article,
            'articles' =>$data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' =>$recent,
            'categories' =>$categories,
            'comments' => $comments,
            'commentForm' =>$commentForm,
            'tags' =>$tags
        ]);
    }

    public function actionCategory($id) {
        $data = Category::getArticlesByCategory($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        return $this->render('category', [
            'articles' =>$data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' =>$recent,
            'categories' =>$categories
        ]);
    }

    public function actionSubcategory($id) {
        $data = Subcategory::getArticles($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        return $this->render('category', [
            'articles' =>$data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' =>$recent,
            'categories' =>$categories
        ]);
    }

    public function actionComment ($id) {
        $model = new commentForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->saveComment($id)) {
                Yii::$app->getSession()->setFlash('comment', 'Your comment was added');
                return $this->redirect(['site/view', 'id' =>$id]);
            }
        }
    }
}
