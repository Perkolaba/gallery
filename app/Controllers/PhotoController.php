<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 16:24
 */

namespace App\Controllers;


use App\Services\PhotoService;
use App\Services\QueryBuilder;
use League\Plates\Engine;

class PhotoController extends Controller
{

    private $photo;

    public function __construct(Engine $engine, PhotoService $photo, QueryBuilder $queryBuilder)
    {
        parent::__construct($engine, $queryBuilder);
        $this->photo = $photo;
    }

    public function show($id) //id
    {

        $photo = $this->queryBuilder->find('image', $id);
        $photoOwner = $this->queryBuilder->getPhotoOwner($photo['id']);

        echo $this->view->render('photos/photo', ['photo' => $photo, 'photoOwner' => $photoOwner]);

    }

    public function showMyPhotos()
    {

        $photos = $this->queryBuilder->getUsersPhotos('image', auth()->getUserId());
        echo $this->view->render('photos/photos', ['photos' => $photos]);

    }

    public function showUploadForm()
    {

        echo $this->view->render('photos/upload');

    }

    public function upload()
    {

        $this->photo->upload();

    }

}