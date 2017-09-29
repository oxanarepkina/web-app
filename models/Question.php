<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $title
 * @property integer $author_id
 * @property string $date
 * @property Answer[] $answers
 * @property User $author
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['author_id', 'title'], 'required'],
            [['author_id'], 'integer'],
            [['date'], 'safe'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Your question',
            'author_id' => 'Author ID',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * Get question`s answers
     *
     * @param $pageSize
     * @return mixed
     */
    public function getQuestionAnswers($pageSize)
    {
        // build a DB query to get all answers sorted by date
        $query = $this->getAnswers()->orderBy(['date' => SORT_DESC]);

        // get the total number of answers
        $count = $query->count();

        // create a pagination
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        // limit the query using pagination
        $answers = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['answers'] = $answers;
        $data['pagination'] = $pagination;
        $data['count'] = $count;

        return $data;
    }

    /**
     * Get answers count
     *
     * @return int|string
     */
    public function getAnswersCount()
    {
        return $this->getAnswers()->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * Get all questions from DB
     *
     * @param $pageSize
     * @return mixed
     */
    public static function getAllQuestions($pageSize)
    {
        // build a DB query to get all questions
        $query = Question::find();

        // get the total number of questions
        $count = $query->count();

        // create a pagination
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        // limit the query using the pagination
        $questions = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['date' => SORT_DESC])
            ->all();

        $data['questions'] = $questions;
        $data['pagination'] = $pagination;

        return $data;
    }
}
