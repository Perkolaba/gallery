<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.04.2018
 * Time: 13:45
 */

namespace App\Services;


use Intervention\Image\ImageManager;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class PhotoService
{

    private $image;
    private $queryBuilder;

    public function __construct(ImageManager $image, QueryBuilder $queryBuilder)
    {

        $this->image = $image;
        $this->queryBuilder = $queryBuilder;

    }

    public function upload()
    {

        $this->validate();

        $category_name = $_POST['category_id'];
        $filename = str_random(10) . '.' . pathinfo($_FILES[img][name],PATHINFO_EXTENSION);

        $img = $this->image->make($_FILES['img']['tmp_name']);
        $img->save('uploads/' . $filename);
        $imgPath = 'uploads/' . $filename;

        $category_id = $this->queryBuilder->findCategoryId('category', $category_name);
//        $category_id = $category_id['id'];

        $_POST['category_id'] = $category_id;

        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $category_id,
            'user_id' => auth()->getUserId(),
            'img' => $imgPath
        ];

        $this->queryBuilder->create('image', $data);
        flash()->success(['Спасибо! Картинка загружена']);

        return redirect('/photos');
    }


    private function validate() {
        $validator = v::key('title', v::stringType()->notEmpty())
            ->key('description', v::stringType()->notEmpty())
            ->key('category_id', v::notEmpty())
            ->keyNested('img.tmp_name', v::image());

        try {
            $validator->assert(array_merge($_POST, $_FILES));
        }
        catch (ValidationException $exception) {
            $exception->findMessages($this->getMessages());
            flash()->error($exception->getMessages());

            return redirect('upload');
        }
    }

    private function getMessages()
    {
        return [
            'title'   =>  'Введите название картинки',
            'description' => 'Введите описание картинки',
            'category_id'  =>  'Выберите категорию',
            'image' =>  'Добавьте картинку'
        ];
    }

}