<?php

namespace App\Http\Controllers;

use Exception;
use App\Repositories\DiaryRepository;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    protected $diaryRepository;

    public function __construct(DiaryRepository $diaryRepository)
    {
        parent::__construct();

        $this->diaryRepository = $diaryRepository;
    }

    public function queryDiary(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);

        try {
            return $this->response->success($this->diaryRepository->getById($request->id));
        } catch (Exception $e) {
            return $this->response->withBadRequest($e->getMessage());
        }
    }

    public function submitDiary(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|integer'
        ]);

        try {
            return $this->response->success($this->diaryRepository->add($request));
        } catch (Exception $e) {
            return $this->response->withBadRequest($e->getMessage());
        }
    }

    public function submitRename(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        
        try {
            return $this->response->success($this->diaryRepository->updateTitle($request->id, $request->title));
        } catch (Exception $e) {
            return $this->response->withBadRequest($e->getMessage());
        }
    }

    public function submitStar(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
            'isStar' => 'required|boolean'
        ]);

        try {
            return $this->response->success($this->diaryRepository->updateStar($request->id, $request->isStar));
        } catch (Exception $e) {
            return $this->response->withBadRequest($e->getMessage());
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            if(!$request->hasFile('file'))
                throw new Exception("上传文件为空");
            $file = $request->file('file');
            if(!$file->isValid())
                throw new Exception("文件上传出错");
            
            return $this->response->success($this->diaryRepository->uploadFile($file));
        } catch (Exception $e) {
            return $this->response->withBadRequest($e->getMessage());
        }
    }
}
