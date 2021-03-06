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
        $this->model->title = $model['title'];
        $this->model->type = $model['type'];
        $this->model->content = $model['content'];
        $this->model->detail = $model['detail'];
        $this->model->star = 0;
        $this->model->create_at = date('Y-m-d H:i:s', time());
        var_dump(date('Y-m-d H:i:s', time()));
        $this->model->update_at = date('Y-m-d H:i:s', time());
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
     * Get the day with records.
     *
     * @param  array $parameters
     * @return Array
     */
    public function getDayHasRecord($parameters)
    {
        $user = \JWTAuth::user();

        $timezone = new \DateTimeZone(config('app.timezone', 'PRC'));
        if (!$this->isNull($parameters['year']) && !$this->isNull($parameters['month'])) {
            $beginTime = new \DateTime($parameters['year'].'-'.$parameters['month'].'-01 00:00:00', $timezone);
            $endTime = (new \DateTime($parameters['year'].'-'.$parameters['month'].'-01 00:00:00', $timezone))->add(new \DateInterval('P1M'));
        } else if (!$this->isNull($parameters['year'])) {
            $beginTime = new \DateTime($parameters['year'].'-01-01 00:00:00', $timezone);
            $endTime = (new \DateTime($parameters['year'].'-01-01 00:00:00', $timezone))->add(new \DateInterval('P1Y'));
        }

        $builder = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()));
        $this->isNull($beginTime) || $builder = $builder->where('create_at', '>=', $beginTime->format('Y-m-d H:i:s'));
        $this->isNull($endTime) || $builder = $builder->where('create_at', '<', $endTime->format('Y-m-d H:i:s'));

        return $builder->groupBy(\DB::raw('DATE_FORMAT(create_at, "%Y-%m-%d")'))->select(\DB::raw('DATE_FORMAT(create_at, "%Y-%m-%d") AS day'))->pluck('day');
    }

    /**
     * Get records.
     *
     * @param  array $parameters
     * @return Array
     */
    public function getList($parameters)
    {
        $user = \JWTAuth::user();

        $timezone = new \DateTimeZone(config('app.timezone', 'PRC'));
        if (!$this->isNull($parameters['year']) && !$this->isNull($parameters['month']) && !$this->isNull($parameters['day'])) {
            $beginTime = new \DateTime($parameters['year'].'-'.$parameters['month'].'-'.$parameters['day'].' 00:00:00', $timezone);
            $endTime = (new \DateTime($parameters['year'].'-'.$parameters['month'].'-'.$parameters['day'].' 00:00:00', $timezone))->add(new \DateInterval('P1D'));
        } else if (!$this->isNull($parameters['year']) && !$this->isNull($parameters['month'])) {
            $beginTime = new \DateTime($parameters['year'].'-'.$parameters['month'].'-01 00:00:00', $timezone);
            $endTime = (new \DateTime($parameters['year'].'-'.$parameters['month'].'-01 00:00:00', $timezone))->add(new \DateInterval('P1M'));
        } else if (!$this->isNull($parameters['year'])) {
            $beginTime = new \DateTime($parameters['year'].'-01-01 00:00:00', $timezone);
            $endTime = (new \DateTime($parameters['year'].'-01-01 00:00:00', $timezone))->add(new \DateInterval('P1Y'));
        }

        $builder = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()));
        $this->isNull($beginTime) || $builder = $builder->where('create_at', '>=', $beginTime->format('Y-m-d H:i:s'));
        $this->isNull($endTime) || $builder = $builder->where('create_at', '<', $endTime->format('Y-m-d H:i:s'));

        return $builder->orderBy('create_at', 'desc')->get();
    }

    /**
     * delete the record.
     * 
     * @param  Illuminate\Http\Request $request
     * @return App\Models\Diary
     */
    public function remove($id)
    {
        $user = \JWTAuth::user();

        $this->model->where('user', '=', $user->getAttribute($user->getKeyName()))->where($this->model->getKeyName(), '=', $id)->delete();
    }

    /**
     * Get records.
     *
     * @param  array $parameters
     * @return
     */
    public function search($parameters)
    {
        $user = \JWTAuth::user();
        $builder = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()));

        $this->isNull($parameters['searchKey']) || $builder = $builder->whereRaw("CONCAT(IFNULL(title,''),',',IFNULL(content,'')) like '%".$parameters['searchKey']."%'");
        $this->isNull($parameters['isStar']) || $parameters['isStar'] == -1 || $builder = $builder->where("star", "=", $parameters['isStar']);

        $builder = $this->isNull($parameters['sortColumn'])? $builder->orderBy('create_at', 'desc'): $builder->orderBy($parameters['sortColumn'], $parameters['sort']);
        $builder = $builder->offset($parameters['offset'])->limit($parameters['limit']);
        return [ 'list' => $builder->get(), 'total' => $builder->count() ];
    }

    /**
     * Update the record.
     * 
     * @param  Illuminate\Http\Request $request
     * @return App\Models\Diary
     */
    public function update($model)
    {
        $user = \JWTAuth::user();

        $this->model = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()))->where($this->model->getKeyName(), '=', $model['id'])->firstOrFail();
        $this->model->title = $model['title'];
        $this->model->content = $model['content'];
        $this->model->detail = $model['detail'];
        $this->model->update_at = date('Y-m-d H:i:s', time());
        $this->model->save();

        return $this->getById($this->model->getAttribute($this->model->getKeyName()));
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
        $user = \JWTAuth::user();

        $this->model = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()))->where($this->model->getKeyName(), '=', $id)->firstOrFail();
        $this->model->star = $state;
        $this->model->update_at = date('Y-m-d H:i:s', time());
        $this->model->save();

        return $this->getById($this->model->getAttribute($this->model->getKeyName()));
    }

    /**
     * Update the title of the record.
     * 
     * @param  int $id
     * @param  string $title
     * @return App\Models\Diary
     */
    public function updateTitle($id, $title)
    {
        $user = \JWTAuth::user();

        $this->model = $this->model->where('user', '=', $user->getAttribute($user->getKeyName()))->where($this->model->getKeyName(), '=', $id)->firstOrFail();
        $this->model->title = $title;
        $this->model->update_at = date('Y-m-d H:i:s', time());
        $this->model->save();

        return $this->getById($this->model->getAttribute($this->model->getKeyName()));
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
