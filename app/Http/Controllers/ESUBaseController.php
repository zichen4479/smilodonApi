<?php

namespace App\Http\Controllers;

use App\Exceptions\ESUException;
use App\Exceptions\SystemErrorExcept;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ESUBaseController extends Controller
{
    protected $error;

    protected $statusCode = 200;

    /**
     * @param int $code
     * @param null $data
     * @param null $msg
     * @return mixed
     */
    public function response($code, $data = null, $msg = null)
    {
        return response()->eshopsunion($code, $data, $msg)->setStatusCode($this->statusCode);
    }

    /**
     * @param null $msg
     * @param $code
     * @throws SystemErrorExcept
     */
    public function error($msg, $code)
    {
        throw new SystemErrorExcept($msg, $code);
    }

    public function pagination(Request $request, $data, $perPage = 20)
    {
        $page = $request->page ?: 1;
        $offset = ($page * $perPage) - $perPage;
        if (isset($data['total'])) {
            $total = $data['total'];
        } else {
            $total = count($data);
        }
        $data = new LengthAwarePaginator(array_slice($data, $offset, $perPage, true), $total, $perPage,
            $page, ['path' => $request->url(), 'query' => $request->query()]);
        return $data;
    }
}
