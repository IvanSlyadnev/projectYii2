<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageApload extends Model{

  public $image;

  public function rules () {
    return [
        [['image'], 'file', 'extensions' => 'jpg,png']
    ];
  }

  public function uploadFile (UploadedFile $file, $currentImage) {
    $this->image = $file;

    if ($this->validate())
    {
      $this->deleteCurrentImage($currentImage);

      return $this->saveImage();
    }
  }

  public function uploadPhoto(UploadedFile $file = null)
  {
    $this->image = $file;
    if ($this->validate() && $this->image!=null) {
      return $this->savePhoto();
    }
  }

  private function getFolder() {
    return Yii::getAlias('') . 'uploads/';
  }

  private function genereteFileName ($file) {
    return strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
  }


  public function deleteCurrentImage($currentImage) {
    if ($this->fileIsExists($currentImage))
    {
        unlink($this->getFolder() . $currentImage);
    }
  }

  public function fileIsExists($currentImage) {
    if (!empty($currentImage) && $currentImage != null) {
      return file_exists($this->getFolder() . $currentImage);
    }
  }

  public function saveImage() {
    $filename = $this->genereteFileName($this->image);

    $this->image->saveAs(Yii::getAlias('') . 'uploads/' . $filename);

    return $filename;
  }

  public function savePhoto() {
    $filename = $this->genereteFileName($this->image);

    $this->image->saveAs(Yii::getAlias('') . 'photos/' . $filename);

    return $filename;
  }
}

/*

*/

/*
public function uploadFile (UploadedFile $file, $currentImage) {
  $this->image = $file;

  if ($this->validate()) {
    if (file_exists(Yii::getAlias('') . 'uploads/' . $currentImage))
    {
        unlink(Yii::getAlias('') . 'uploads/' . $currentImage);
    }


    $filename = strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);

    $file->saveAs(Yii::getAlias('') . 'uploads/' . $filename);

    return $filename;
  }
}
}
*/
?>
