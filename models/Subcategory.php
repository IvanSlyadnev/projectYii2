<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "subcategory".
 *
 * @property int $id
 * @property string $title
 * @property int $category_id
 *
 * @property Category $category
 */
class Subcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subcategory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['category_id'], 'integer'],
            [['title'], 'string', 'max' => 32],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public static function getArticles ($id) {
        $subcategory = Subcategory::findOne($id);
        $query = Article::find()->where(['subcategory_id' => $id, 'category_id' =>$subcategory->category_id]);

        $count = $query->count();
        $pagination = new Pagination (['totalCount' => $count, 'pageSize' =>2]);

        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return ['articles' =>$articles, 'pagination' =>$pagination];


    }

    public function saveCategory($category_id) {
        $category = Category::findOne($category_id);

        if ($category!= null) {
            $this->link('category', $category);
            return true;
        }
    }
}
