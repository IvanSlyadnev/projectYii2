<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property int $viewed
 * @property int $user_id
 * @property int $status
 * @property int $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['title'], 'required'],
          [['title', 'description', 'content'], 'string'],
          [['date'], 'date', 'format'=>'php:Y-m-d'],
          [['date'], 'default', 'value' => date('Y-m-d')],
          [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    public function saveImage($fileName) {
      $this->image = $fileName;
      return $this->save(false);
    }

    public function getImage() {
      return ($this->image) ? '/project/web/uploads/' . $this->image : '/project/web/uploads/no-image.png';
    }

   public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    public function getTags() {
        return $this->hasMany(Tag::className(),['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getComments() {
        return $this->hasMany(Comment::className(), ['article_id' =>'id']);
    }

    public function getArticleComments () {
        return $this->getComments()->where(['status' =>1])->all();
    }

    public function saveTags($tags) {
        if (is_array($tags)) {
            ArticleTag::deleteAll(['article_id' => $this->id]);//Удалание текузего тега
            foreach ($tags as $tag_id) {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }

    public function saveSubcategory($subcategory_id) {
        $subcategory = Subcategory::findOne($subcategory_id);
        if ($subcategory!= null) {
            $this->subcategory_id = $subcategory_id;
            $this->save();
            return true;
        }
    }

    public function saveCategory($category_id) {
        $category = Category::findOne($category_id);

        if ($category != null) {
            $this->link('category', $category);
            return true;
        }
        /* Тоже самое что и
            $this->category_id = $category_id;
            $this->save();
         */
    }

    public function saveArticle() {
        $this->user_id = Yii::$app->id;
        $this->title_len = strlen($this->title);
        return $this->save();
    }

    public function getAuthor() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function deleteImage() {
      $uploadedImage = new ImageApload;
      $uploadedImage->deleteCurrentImage($this->image);
    }

    public function beforeDelete() {
      $this->deleteImage();
      return parent::beforeDelete();
    }
    public function getDate() {
        return Yii::$app->formatter->asDate($this->date);
    }

    public static function getAll($sort = null) {

        $query = Article::find();
        $count = $query->count();

        $pagination = new Pagination (['totalCount' => $count, 'pageSize' =>2]);

        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
          //  ->where(['status' =>1])
            //->orderBy('viewed desc')
            ->all();
        if ($sort == 0) {
            $articles = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('title asc')->all();
        } else if ($sort == 1) {
            $articles = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('viewed desc')->all();
        } else if ($sort == 2) {
            $articles = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('title_len desc')->all();
        }
        return ['articles'=>$articles, 'pagination'=>$pagination];
    }
    public static function getPopular() {
        return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static  function getRecent() {
        return Article::find()->orderBy('viewed asc')->limit(4)->all();
    }

    public function viewedCounter() {
        $this->viewed++;
        return $this->save(false);
    }
}
