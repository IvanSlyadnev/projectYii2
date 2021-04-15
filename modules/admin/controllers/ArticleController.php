<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ImageApload;
use yii\web\UploadedFile;
use app\models\Category;
use app\models\Tag;

class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetImage($id) {

      $model = new ImageApload;

      if (Yii::$app->request->isPost) {
        $article = $this->findModel($id);
        $file = UploadedFile::getInstance($model, 'image');

        if ($article->saveImage($model->uploadFile($file,$article->image))) {
          return $this->redirect(['view', 'id' => $id]);
        }
      }

      return $this->render('image', ['model' => $model]);
    }

    public function actionSetSubcategory($id) {
        $article = $this->findModel($id);
        $a = 10;
        $subcategories =ArrayHelper::map($article->category->subcategory, 'id', 'title');
        $selected = $article->category->subcategory->id;
        if (Yii::$app->request->isPost) {
            $subcategory = Yii::$app->request->post('subcategory');
            if ($article->saveSubcategory($subcategory)) {
                return $this->redirect(['view', 'id' =>$id]);
            }
        }

        return $this->render('subcategory', [
            'selected' => $selected,
            'subcategories' =>$subcategories
        ]);

    }

    public function actionSetCategory ($id) {
        $article = $this->findModel($id);
        //var_dump($article->category->articles);
        $selectedCategory = $article->category->id;
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        if (Yii::$app->request->isPost) {
            $category = Yii::$app->request->post('category');
            if($article->saveCategory($category)) {
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('category', [
            'article' => $article,
            'selectedCategory' => $selectedCategory,
            'categories' => $categories
        ]);
    }

    public function actionSetTags($id) {
        $article = $this->findModel($id);
        $tags = Tag::find()->all();
        $tags = ArrayHelper::map($tags, 'id', 'title');
        $selectedTags = ArrayHelper::getColumn($article->tags, 'id');
        $selectedTags_ = ArrayHelper::getColumn($article->getTags()->select('id')->asArray()->all(), 'id') ;
        //var_dump($selectedTags);
        //var_dump(ArrayHelper::getColumn($selectedTags_, 'id'));
        if (Yii::$app->request->isPost) {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);
            return $this->redirect(['view', 'id' =>$id]);
        }
        return $this->render('tag' ,
        [
            'selectedTags' =>$selectedTags,
            'tags' => $tags
        ]);
    }
    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
