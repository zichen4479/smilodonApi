<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addProjectRequest;
use App\Models\File\File;
use App\Models\Projects\Project;
use Illuminate\Http\Request;

class ProjectController extends ESUBaseController
{

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function showProject($id)
    {
        $data = $this->getProject($id);
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\SystemErrorExcept
     */
    private function getProject($id)
    {
        $project = Project::getProject($id);
        if (!$project) {
            $code = 20021;
            $msg = "项目不存在";
            $this->error($msg, $code);
        }
        return $project;
    }
}
