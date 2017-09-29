<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $photo_img;
    public $photo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['photo', 'string'],
            [['photo_img'], 'file', 'extensions' => 'jpg,png,jpeg'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->photo = $this->uploadFile($this->photo_img, 'photo_img');
        return $user->save() ? $user : null;
    }

    /**
     * Upload user photo and save it to the project directory
     *
     * @param $uploaded_file
     * @param $file_input_name
     * @return string
     */
    public function uploadFile($uploaded_file, $file_input_name)
    {
        $uploaded_file = UploadedFile::getInstance($this, $file_input_name);

        if (!empty($uploaded_file)) {
            $imageName = strtolower(md5(uniqid($uploaded_file->baseName))) . '.' . $uploaded_file->extension;
            // check if $model->file is not empty
            if ($uploaded_file && $this->validate()) {

                $uploaded_file->saveAs('uploads/' . $imageName);

                // save the path in the db column
                return 'uploads/' . $imageName;
            }
        }
    }
}