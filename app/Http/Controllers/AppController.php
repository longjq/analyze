<?php

namespace App\Http\Controllers;

use App\Libraries\DBQueryHelper;
use App\Libraries\ExcelHelper;
use App\Models\Package;
use Illuminate\Http\Request;

use App\Http\Requests;
use Excel;

class AppController extends Controller
{
    private $package;

    public function __construct()
    {
        $this->package = new Package();
    }

    /**
     * 根据包名或用户id查询
     * app/packages
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function packages(Request $request)
    {
        if ($request->isMethod('get')) {
            $packages = $this->package->orderBy('user_count', 'desc')->paginate(15);
            return view('app_packages', compact('packages'));
        }
        $userId = trim($request->input('user_id'));
        $package = trim($request->input('package'));
        $name = trim($request->input('name'));
        if(empty($userId) && empty($package) && empty($name)) return redirect('app/packages');
        if(!empty($userId)){
            $packages = $this->package->packagesListByUid($userId);
        }else{
            $packages = $this->package->packagesList($package, $name);
        }
        if(is_null($packages)) return redirect('app/packages');
        return view('app_packages', compact('packages', 'package', 'name', 'userId'));
    }

    public function packageDetail(Request $request)
    {
        if($request->ajax()){
            $packageId = $request->input('pid');
            $package = Package::find($packageId);
            return response()->json(['data'=>$package->users()->count()],200);
            //return response()->json(['data'=>6],200);
        }
        return response()->json([],400);

    }

    /**
     * 包名下载用户id
     * /app/download_users
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadUsers(Request $request)
    {
        $filePath = storage_path('app/exports' . DIRECTORY_SEPARATOR . $request->input('path'));
        if (file_exists($filePath)) {
            return response()->download($filePath);
        };
        return redirect('app/users')->withErrors(['xls文件没有生成，请重新生成！']);
    }

    /**
     * 应用用户统计
     * /app/users
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('app_users');
        }
        $where = $request->input('where');

        $and = DBQueryHelper::inOperator($where, 'and');
        $and[] = array_shift($where)['col'];
        $andItems = $this->package->whereIn('package', $and)->get(['users'])->toArray();
        $ids = DBQueryHelper::explodeIds($andItems);

        $or = DBQueryHelper::inOperator($where, 'or');
        if(count($or) != 0){
            $orItems = $this->package->whereIn('package', $or)->get(['users']);
            $ids .= ','. DBQueryHelper::explodeIds($orItems);
        }
        $ids = array_filter(array_unique(explode(',', $ids)));
        $ids = array_map(function($id){
            return [$id];
        }, $ids);

        if (count($ids) > 0) {
            array_unshift($ids, ['用户ID']);
            $excel = new ExcelHelper('package_' . date('Y-m-d'));
            $path = $excel->exportToStore($ids)['file'];
        }
        return view('app_users', compact('ids', 'where', 'path'));
    }


    /**
     * 下载应用事件xls
     * app/download_events
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadEvents(Request $request)
    {
        $filePath = storage_path('app/exports' . DIRECTORY_SEPARATOR . $request->input('path'));
        if (file_exists($filePath)) {
            return response()->download($filePath);
        };
        return redirect('app/events')->withErrors(['xls文件没有生成，请重新生成！']);
    }

    /**
     * 查询用户事件类型统计
     * todo...待修改，从cache读出数据
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function events(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('app_events');
        }
        $this->validate($request, [
            'p' => 'required',
            'e' => 'required|in:inst,uninst',
        ], [
            'p.required' => '包名字段不能为空',
            'e.required' => '事件类型必须选择',
            'e.in'       => '请选择正确的事件类型',
        ]);

        $package = $request->input('p');
        $event = $request->input('e'); // inst | uninst

        $userEvent = new \App\Models\Assistant\UserEvent();
        $userEventList = $userEvent->userEventList($package, $event)->toArray();
        $userEventCount = $userEvent->userEventCount($userEventList);

        if ($userEventCount > 0) {
            array_unshift($userEventList, ['用户ID']);
            $excel = new \App\Libraries\ExcelHelper($package . '_' . $event . '_' . date('Y-m-d'));
            $path = $excel->exportToStore($userEventList);
        } else {
            $userEventCount = 0;
            $path = ['file' => 'no'];
        }
        return view('app_events')
            ->with('count', $userEventCount)
            ->with('path', $path['file'])
            ->with('p', $package)
            ->with('e', $event);

    }
}


