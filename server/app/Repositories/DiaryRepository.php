<?php

namespace App\Repositories;

use Exception;
use App\Models\Diary;

class DiaryRepository
{
    private $model;

    public function __construct(Diary $model)
    {
        $this->model = $model;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request $request
     * @return App\Models\Diary
     */
    public function add($model)
    {
        $user = \JWTAuth::user();

        $this->model->user = $user->getAttribute($user->getKeyName());
        $this->model->title = $model->title;
        $this->model->type = $model->type;
        $this->model->content = $model->content;
        $this->model->star = 0;
        $this->model->create_at = time();
        $this->model->update_at = time();
        $this->model->save();

        return $this->getById($this->model->getAttribute($this->model->getKeyName()));
    }

    /**
     * Get the record.
     *
     * @param  int $id
     * @return App\Models\Diary
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get number of the records.
     *
     * @param  array $parameters
     * @return Paginate
     */
    public function page($parameters)
    {
        $builder = $this->model->getInitModel($parameters['search_key']);

        $this->isNull($parameters['category_id']) || $parameters['category_id'] === -1 || $builder = $builder->where($this->model->getTable().".category_id", "=", $parameters['category_id']);
        $this->isNull($parameters['closed']) || $parameters['closed'] === -1 || $builder = $builder->where($this->model->getTable().".closed", "=", $parameters['closed']);
        $this->isNull($parameters['start_time']) || $builder = $builder->where("publish_time", ">=", $parameters['start_time']);
        $this->isNull($parameters['end_time']) || $builder = $builder->where("publish_time", "<=", $parameters['end_time']);

        $builder = $this->isNull($parameters['sort_column'])? $builder->orderBy('create_at', 'desc'): $builder->orderBy($parameters['sort_column'], $parameters['sort']);
        return $builder->paginate($parameters['limit'], ['*'], 'current');
    }

    /**
     * Update the star state of the record.
     * 
     * @param  int $id
     * @param  boolean $state
     * @return App\Models\Diary
     */
    public function updateStar($id, $state)
    {
        $this->model = $this->model->findOrFail($id);
        $this->model->star = $state;
        $this->model->update_at = time();
        $this->model->save();

        return $this->getById($id);
    }

    /**
     * Update the title of the record.
     * 
     * @param  int $id
     * @param  string $name
     * @return App\Models\Diary
     */
    public function updateTitle($id, $name)
    {
        $this->model = $this->model->findOrFail($id);
        $this->model->title = $name;
        $this->model->update_at = time();
        $this->model->save();

        return $this->getById($id);
    }

    /**
     * Save the file to upload directory.
     * 
     * @param  Object $file
     * @return
     */
    public function uploadFile($file)
    {
        $destPath = 'public/upload/';
        $sha1 = sha1_file($file->getPathname());
        $extension = $file->getClientOriginalExtension();
        $fileName = $sha1.'.'.$extension;
        $file->move($destPath, $fileName);
        return [ 'path' => $destPath.$fileName ];
    }

    private function isNull(&$value) {
        return !isset($value) || is_null($value) || $value === '';
    }
}
