<?php

namespace App\Http\Controllers;

use App\Libraries\DateHelper;
use App\Libraries\ExcelHelper;
use App\Models\Cache;
use App\Models\UsersList;
use App\Models\UsersLive;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{

    private $usersLive = null;
    private $userList = null;

    public function __construct()
    {
        $this->usersLive = new UsersLive();
        $this->userList = new UsersList();
    }

    /**
     * 首页加载缓存数据
     * /dash
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dash()
    {
        $caches = Cache::lists('value', 'key');
        return view('dash', compact('caches'));
    }

    /**
     * 查询用户统计数据
     * /user/data
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userData(Request $request)
    {
        if ($request->isMethod('post')) {
            $u = $request->input('u');
            $this->userList->groupCount($u);

            $fields = ['v', 'lang', 'brand', 'device', 'ov','country', 'area', 'region', 'city', 'isp'];
            $u = $request->input('u');
            $countName = $u . '_count';
            if (in_array($u, $fields)) {
                $data = $this->userList->groupCount($u)->toArray();
                $dataCount = array_sum(array_column($data, $countName));
                return view('user_data', compact('data', 'u', 'dataCount'));
            }
            abort('400', '非法操作');
        }
        return view('user_data');
    }

    /**
     * 用户资料导出
     * /user/data_export
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function userDataExport(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('user_query');
        }

        if (empty($request->input('where'))) {
            return redirect('/user/data_export')->with('danger', '请输出查询条件');
        }

        $wheres = $request->input('where');
        $usersBuilder = null;
        foreach ($wheres as $key => $where) {
            if ($key == 0) {
                $usersBuilder = UsersList::where($where['col'], $where['op'], $where['val'])->distinct();
            }
            if (isset($where['condition']) && $where['condition'] == 'and') {
                $usersBuilder->where($where['col'], $where['op'], $where['val']);
            } else if (isset($where['condition']) && $where['condition'] == 'or') {
                $usersBuilder->orWhere($where['col'], $where['op'], $where['val']);
            }
        }
        $items = $usersBuilder->get(['user_id'])->toArray();

        if (count($items) > 0) {
            array_unshift($items, ['用户ID']);
            $excel = new ExcelHelper('query_' . date('Y-m-d'));
            $path = $excel->exportToStore($items)['file'];
        }
        return view('user_query', compact('path', 'wheres', 'items'));
    }

    /**
     * 下载已生成的xls文件
     * /user/download_query
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadUserQuery(Request $request)
    {
        $filePath = storage_path('app/exports' . DIRECTORY_SEPARATOR . $request->input('path'));
        if (file_exists($filePath)) {
            return response()->download($filePath);
        };
        return redirect('user/data_export')->withErrors(['xls文件没有生成，请重新生成！']);
    }

}
