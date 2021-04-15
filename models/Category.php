<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 100],
        ];
    }
    public function getArticles () {
        return $this->hasMany(Article::className(), ['category_id'=> 'id']);
    }

    public function getArticlesCount() {
        return $this->getArticles()->count();
    }

    public static function getAll() {
        return Category::find()->all();
    }

    public static function getArticlesByCategory($id) {
        $query = Article::find()->where(['category_id' =>$id]);
        $count = $query->count();

        $pagination = new Pagination (['totalCount' => $count, 'pageSize' =>2]);

        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return ['articles'=>$articles, 'pagination'=>$pagination];
    }

    public function getSubcategory () {
        return $this->hasMany(Subcategory::className(), ['category_id'=> 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }


}
