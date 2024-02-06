<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\ESUBaseController;
use App\Http\Requests\addProjectRequest;
use App\Models\File\File;
use App\Models\Projects\Project;
use Illuminate\Http\Request;

class ProjectController extends ESUBaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function listProject(Request $request)
    {
        $data = Project::getProjects();
//        $data = array();
//        foreach ($projects->getCollection() as $project) {
//            $content = json_decode($project->content, true);
//            
//            $file = File::getFile($project->image);
//            $data[] = array(
//                'id' => $project->id,
//                'title' => $project->title,
//                'thumb' => config('variable.image_domain').$file->cdn_url,
//                'created_at' => $project->created_at
//            );
//        }
//        $data = $projects->setCollection(collect($data));
        $code = 1;
        $msg = "获取成功";
        return $this->response($code, $data, $msg);
    }

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
     * @param  Request  $request
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function addProject(addProjectRequest $request)
    {
        $id = Project::addProject($request);
        $data = $this->getProject($id);
        $code = 1;
        $msg = "添加成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function editProject(addProjectRequest $request, $id)
    {
        $this->getProject($id);
        Project::editProject($id, $request);
        $data = $this->getProject($id);
        $code = 1;
        $msg = "修改成功";
        return $this->response($code, $data, $msg);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function deleteProject($id)
    {
        $this->getProject($id);
        Project::deleteProject($id);
        $code = 1;
        $msg = "删除成功";
        return $this->response($code, $data = null, $msg);
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
