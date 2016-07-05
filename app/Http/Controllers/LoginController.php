<?php

namespace App\Http\Controllers;

use App\Libraries\LoadAssistant;
use App\Models\User;
use App\Models\UsersList;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

class LoginController extends Controller
{
    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request){

        $postData = [
            'name' => $request->input('name'),
            'password' => $request->input('pass'),
        ];
        if (Auth::attempt($postData, $request->input('remember'))) {
            return redirect()->intended('dash');
        }else{
            return redirect('/login');
        }
    }

    /**
     * 退出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        Auth::logout();
        return   redirect('/login');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->input('key') == 'ltbl2016'){
            return User::create([
                'name' => $request->input('n'),
                'email' => $request->input('e'),
                'password' => \Hash::make($request->input('p'))
            ]);
        }else{
            return [
                'key' => $request->input('key'),
                'name' => $request->input('n'),
                'email' => $request->input('e'),
                'password' => $request->input('p')
            ];
        }
    }

    public function test(Request $request){





//
////        $item = [
////            [
////                'user_id'=>2,
////                'snapshot' => '[["Android \u7cfb\u7edf","android","23","9F587D4D27FC0953CA92617D72DE7D11"],["\u4e2d\u534e\u4e07\u5e74\u5386\u65e5\u5386","cn.etouch.ecalendar","654",null],["\u9177\u6211\u97f3\u4e50","cn.kuwo.player","6590","9AEAE72D0B407473CC4B30E2DCD0C61C"],["\u5e94\u7528\u5e02\u573a","cn.lt.appstore","40001","6C3311AE5EF2299CC2C4705A67809185"],["\u6e38\u620f\u4e2d\u5fc3","cn.lt.game","202000015","772CC863FDB26B4CBD0BF8FEC3806F73"],["UC \u6d4f\u89c8\u5668","com.UCMobile","173",null],["\u54aa\u5495\u9605\u8bfb","com.andreader.prein","100",null],["\u5730\u56fe","com.baidu.BaiduMap","689",null],["\u767e\u5ea6\u624b\u673a\u52a9\u624b","com.baidu.appsearch","16785257",null],["\u767e\u5ea6\u6d4f\u89c8\u5668","com.baidu.browser.apps","142",null],["\u767e\u5ea6\u8f93\u5165\u6cd5","com.baidu.input","79",null],["\u7f51\u7edc\u4f4d\u7f6e","com.baidu.map.location","30","F928D87BDB7B9A4C59CE0594BAFEB620"],["\u624b\u673a\u767e\u5ea6","com.baidu.searchbox","16788745",null],["\u624b\u673a\u7ec4\u4ef6\u4fe1\u606f","com.dev.componentinfo","1","E1A251E8BCA3029E543E38E44E16B836"],["\u767e\u5ea6\u624b\u673a\u536b\u58eb","com.dianxinos.optimizer.channel","1590",null],["Dolby Service","com.dolby","2","9BC8A227F094EB8AE475F20744BF5C02"],["Dolby","com.dolby.daxappUI","2","7CDD855C72C92B8FF07E349D5B0CAAA9"],["ANT HAL Service","com.dsi.ant.server","30200","FD6DE6A364408ABBB3519A03DFC2AE38"],["HonestarIRtest","com.irtest.letv","4","8406E4F51F6B14574ADA27F3ECD43E6A"],["\u4e502","com.le.share.x6","1","7EFCA6CAE18CC9FB46B260570A1ACA4E"],["\u4e50\u89c6\u4f53\u80b2","com.lesports.glivesports","116","3B71093AB0BAB51BEE6E53114E04D32C"],["AgnesService","com.letv.agnes","1","7F2FB1279239151CAA76E6F9937CA108"],["DebugUtil","com.letv.android.DebugUtils","1","5F610F9E7E8F664A547AAF6CAFCACE26"],["shareDemo","com.letv.android.LetvShareProvider","1","E3813D97115974EC94BD5D36B7E0ED84"],["\u6211\u7684\u4e50\u89c6","com.letv.android.account","1","609B731D739EE6CC73F000F82E2FF386"],["com.letv.android.accountinfo","com.letv.android.accountinfo","1","957CA19D8F29A0ED99DAA5814D9278D6"],["\u4e50\u89c6\u767b\u5f55","com.letv.android.agent","1","23657C25CF94930B2BD635579F4B1A16"],["\u95ee\u9898\u53cd\u9988","com.letv.android.bugreporter","1","E23A465268E705420CC0281124790B50"],["\u4e50\u89c6\u89c6\u9891","com.letv.android.client","18000","52976A1FAB9049B9603DF22689B3E5F6"],["LeCloud","com.letv.android.cloudservice","37","34AFB17080EC06E009D19F1314AB1F25"],["\u6307\u5357\u9488","com.letv.android.compass","1","F5A64A344EEB23905C9195E5923549A2"],["LetvEcoProvider","com.letv.android.ecoProvider","1","E3813D97115974EC94BD5D36B7E0ED84"],["\u6587\u4ef6\u7ba1\u7406","com.letv.android.filemanager","102","58A5C5A0E048A21596E997C03450A54E"],["FingerprintProvider","com.letv.android.fingerprint","1","2D900491BD4E54A0938C38A312044FA0"],["\u514d\u6d41\u91cf\u6570\u636e\u670d\u52a1","com.letv.android.freeflow","1","641B95D3DBF30B82EC678827E9C1F795"],["LIVE","com.letv.android.letvlive","51","288369E87676A55910D064BB3D64490B"],["\u9690\u79c1\u6388\u6743","com.letv.android.letvsafe","100","49647B497A1D0F4BA5804B1993BE79DA"],["\u4fbf\u7b7e","com.letv.android.note","3","406641D035A4F9848C2F333F8A1E7E05"],["\u7cfb\u7edf\u66f4\u65b0","com.letv.android.ota","2","97ED48F65875904742661641BA04AC56"],["\u67e5\u627e\u8bbe\u5907","com.letv.android.phonecontrol","4","5B84FC50633696DD446A5FF7C64E53A2"],["com.letv.android.powercontroller","com.letv.android.powercontroller","1","16745A9F334F57FDE66A14CDDBAFC91C"],["\u4e07\u8c61\u641c\u7d22","com.letv.android.quicksearchbox","111","E08BDF9ABCD8C7A5B69FE49BC367AB35"],["\u5f55\u97f3\u673a","com.letv.android.recorder","1","863CCFD7846CDB9BA9D7F5B9D6E115B0"],["\u9065\u63a7","com.letv.android.remotecontrol","12","9A40C30BC84704EBD735D5526BC26DAE"],["Dlna \u670d\u52a1","com.letv.android.remotedevice","1","D7166F096A10C82D6C52A05E57F37508"],["\u8bbe\u7f6e\u5411\u5bfc","com.letv.android.setupwizard","130","70FFD73C693CD81D3135B6DDEC642F27"],["leuiTheme","com.letv.android.theme","1","5F610F9E7E8F664A547AAF6CAFCACE26"],["\u58c1\u7eb8\u548c\u4e3b\u9898","com.letv.android.themesettings","23","C33762B2DAC6D03D5A086226836C5B43"],["\u64ad\u653e\u5668","com.letv.android.videoplayer","140","8A9E2270988562DF0257F8EDCD47F7E6"],["\u58c1\u7eb8\u8bbe\u7f6e","com.letv.android.wallpaper","23","1880423669109DEBCCC716970B75D565"],["\u58c1\u7eb8","com.letv.android.wallpaperonline","7","9425A78CC9B196B88C1E581A2CBD0A7E"],["\u4e50\u8ff7\u793e\u533a","com.letv.bbs","10",null],["CrashHandler","com.letv.bsp.qccrashhandler","1","E3813D97115974EC94BD5D36B7E0ED84"],["BugPostbox","com.letv.bugpostbox","1","926855C3BA538DF564DF38F00B659CBE"],["\u4e50\u770b\u641c\u7d22","com.letv.lesophoneclient","1038","2F5452B08BD5D67E1090946D2ECBDA6A"],["\u4e50\u89c6\u5546\u57ce","com.letv.letvshop","10140","9E223721F1E25C0B80FF30A96D5DB417"],["\u5f00\u5173\u673a\u8bbe\u7f6e","com.letv.leui.schpwronoff","23","6C735FFC4F80016B272D552379234CCC"],["\u5b89\u5168\u9632\u62a4","com.qapp.secprotect","1","CCC76D3818A51F5AA8A30540FE37895F"],["\u8bbe\u5907\u4fe1\u606f","com.qrd.engineeringmode","23","7877DE6585438953816AC79921BA359C"],["com.qrd.frameworks.telresources","com.qrd.frameworks.telresources","23","F3F6EB1407721CF07F3FA056BCE37FF8"],["Backup Agent","com.qti.backupagent","1","8CD75282D780F749D1557CAFECFA3CBB"],["com.qti.dpmserviceapp","com.qti.dpmserviceapp","23","1265F3D736969C29C914832346E6B594"],["com.qti.primarycardcontroller","com.qti.primarycardcontroller","23","1807B6AA03B09DF445D98A0F1D27AB21"],["com.qti.service.colorservice","com.qti.service.colorservice","1","C5EE6A0DEDFB5747661A194A7D4A3528"],["com.qti.xdivert","com.qti.xdivert","23","D2C4BE965165CBB375472BF5C9C25A14"],["com.quicinc.cne.CNEService","com.quicinc.cne.CNEService","1","E2ACAF6E00FFBCA9019B7D5E7C40C833"],["\u4eba\u4eba-\u7f8e\u56fe\u7f8e\u989c\u76f4\u64ad","com.renren.mobile.android","8050800",null],["\u7f8e\u56e2","com.sankuai.meituan","351",null],["\u641c\u72d7\u8f93\u5165\u6cd5","com.sohu.inputmethod.sogou","540","57D7A06044BECB62255F58A5EE7393A8"],["\u4eca\u65e5\u5934\u6761","com.ss.android.article.news","537",null],["\u63a8\u9001\u670d\u52a1","com.stv.stvpush","1500","E48FD55F1A71ACCF7CCF1CEADA7CA67C"],["Pico TTS","com.svox.pico","1","A276308106921EDA4A77DF9614A530EF"],["\u5e94\u7528\u5b9d","com.tencent.android.qqdownloader","6502130",null],["QQ\u6d4f\u89c8\u5668","com.tencent.mtt","662380","102DC1474F6D2F5A211619A7D0B173BA"],["\u817e\u8baf\u65b0\u95fb","com.tencent.news","479",null],["\u817e\u8baf\u89c6\u9891","com.tencent.qqlive","10223",null]]',
////            ],  
////            [
////                'user_id'=>1,
////                'snapshot' => '[["Android \u7cfb\u7edf","android","23","9F587D4D27FC0953CA92617D72DE7D11"],["\u4e2d\u534e\u4e07\u5e74\u5386\u65e5\u5386","cn.etouch.ecalendar","654",null],["\u9177\u6211\u97f3\u4e50","cn.kuwo.player","6590","9AEAE72D0B407473CC4B30E2DCD0C61C"],["\u5e94\u7528\u5e02\u573a","cn.lt.appstore","40001","6C3311AE5EF2299CC2C4705A67809185"],["\u6e38\u620f\u4e2d\u5fc3","cn.lt.game","202000015","772CC863FDB26B4CBD0BF8FEC3806F73"],["UC \u6d4f\u89c8\u5668","com.UCMobile","173",null],["\u54aa\u5495\u9605\u8bfb","com.andreader.prein","100",null],["\u5730\u56fe","com.baidu.BaiduMap","689",null],["\u767e\u5ea6\u624b\u673a\u52a9\u624b","com.baidu.appsearch","16785257",null],["\u767e\u5ea6\u6d4f\u89c8\u5668","com.baidu.browser.apps","142",null],["\u767e\u5ea6\u8f93\u5165\u6cd5","com.baidu.input","79",null],["\u7f51\u7edc\u4f4d\u7f6e","com.baidu.map.location","30","F928D87BDB7B9A4C59CE0594BAFEB620"],["\u624b\u673a\u767e\u5ea6","com.baidu.searchbox","16788745",null],["\u624b\u673a\u7ec4\u4ef6\u4fe1\u606f","com.dev.componentinfo","1","E1A251E8BCA3029E543E38E44E16B836"],["\u767e\u5ea6\u624b\u673a\u536b\u58eb","com.dianxinos.optimizer.channel","1590",null],["Dolby Service","com.dolby","2","9BC8A227F094EB8AE475F20744BF5C02"],["Dolby","com.dolby.daxappUI","2","7CDD855C72C92B8FF07E349D5B0CAAA9"],["ANT HAL Service","com.dsi.ant.server","30200","FD6DE6A364408ABBB3519A03DFC2AE38"],["HonestarIRtest","com.irtest.letv","4","8406E4F51F6B14574ADA27F3ECD43E6A"],["\u4e502","com.le.share.x6","1","7EFCA6CAE18CC9FB46B260570A1ACA4E"],["\u4e50\u89c6\u4f53\u80b2","com.lesports.glivesports","116","3B71093AB0BAB51BEE6E53114E04D32C"],["AgnesService","com.letv.agnes","1","7F2FB1279239151CAA76E6F9937CA108"],["DebugUtil","com.letv.android.DebugUtils","1","5F610F9E7E8F664A547AAF6CAFCACE26"],["shareDemo","com.letv.android.LetvShareProvider","1","E3813D97115974EC94BD5D36B7E0ED84"],["\u6211\u7684\u4e50\u89c6","com.letv.android.account","1","609B731D739EE6CC73F000F82E2FF386"],["com.letv.android.accountinfo","com.letv.android.accountinfo","1","957CA19D8F29A0ED99DAA5814D9278D6"],["\u4e50\u89c6\u767b\u5f55","com.letv.android.agent","1","23657C25CF94930B2BD635579F4B1A16"],["\u95ee\u9898\u53cd\u9988","com.letv.android.bugreporter","1","E23A465268E705420CC0281124790B50"],["\u4e50\u89c6\u89c6\u9891","com.letv.android.client","18000","52976A1FAB9049B9603DF22689B3E5F6"],["LeCloud","com.letv.android.cloudservice","37","34AFB17080EC06E009D19F1314AB1F25"],["\u6307\u5357\u9488","com.letv.android.compass","1","F5A64A344EEB23905C9195E5923549A2"],["LetvEcoProvider","com.letv.android.ecoProvider","1","E3813D97115974EC94BD5D36B7E0ED84"],["\u6587\u4ef6\u7ba1\u7406","com.letv.android.filemanager","102","58A5C5A0E048A21596E997C03450A54E"],["FingerprintProvider","com.letv.android.fingerprint","1","2D900491BD4E54A0938C38A312044FA0"],["\u514d\u6d41\u91cf\u6570\u636e\u670d\u52a1","com.letv.android.freeflow","1","641B95D3DBF30B82EC678827E9C1F795"],["LIVE","com.letv.android.letvlive","51","288369E87676A55910D064BB3D64490B"],["\u9690\u79c1\u6388\u6743","com.letv.android.letvsafe","100","49647B497A1D0F4BA5804B1993BE79DA"],["\u4fbf\u7b7e","com.letv.android.note","3","406641D035A4F9848C2F333F8A1E7E05"],["\u7cfb\u7edf\u66f4\u65b0","com.letv.android.ota","2","97ED48F65875904742661641BA04AC56"],["\u67e5\u627e\u8bbe\u5907","com.letv.android.phonecontrol","4","5B84FC50633696DD446A5FF7C64E53A2"],["com.letv.android.powercontroller","com.letv.android.powercontroller","1","16745A9F334F57FDE66A14CDDBAFC91C"],["\u4e07\u8c61\u641c\u7d22","com.letv.android.quicksearchbox","111","E08BDF9ABCD8C7A5B69FE49BC367AB35"],["\u5f55\u97f3\u673a","com.letv.android.recorder","1","863CCFD7846CDB9BA9D7F5B9D6E115B0"],["\u9065\u63a7","com.letv.android.remotecontrol","12","9A40C30BC84704EBD735D5526BC26DAE"],["Dlna \u670d\u52a1","com.letv.android.remotedevice","1","D7166F096A10C82D6C52A05E57F37508"],["\u8bbe\u7f6e\u5411\u5bfc","com.letv.android.setupwizard","130","70FFD73C693CD81D3135B6DDEC642F27"],["leuiTheme","com.letv.android.theme","1","5F610F9E7E8F664A547AAF6CAFCACE26"],["\u58c1\u7eb8\u548c\u4e3b\u9898","com.letv.android.themesettings","23","C33762B2DAC6D03D5A086226836C5B43"],["\u64ad\u653e\u5668","com.letv.android.videoplayer","140","8A9E2270988562DF0257F8EDCD47F7E6"],["\u58c1\u7eb8\u8bbe\u7f6e","com.letv.android.wallpaper","23","1880423669109DEBCCC716970B75D565"],["\u58c1\u7eb8","com.letv.android.wallpaperonline","7","9425A78CC9B196B88C1E581A2CBD0A7E"],["\u4e50\u8ff7\u793e\u533a","com.letv.bbs","10",null],["CrashHandler","com.letv.bsp.qccrashhandler","1","E3813D97115974EC94BD5D36B7E0ED84"],["BugPostbox","com.letv.bugpostbox","1","926855C3BA538DF564DF38F00B659CBE"],["\u4e50\u770b\u641c\u7d22","com.letv.lesophoneclient","1038","2F5452B08BD5D67E1090946D2ECBDA6A"],["\u4e50\u89c6\u5546\u57ce","com.letv.letvshop","10140","9E223721F1E25C0B80FF30A96D5DB417"],["\u5f00\u5173\u673a\u8bbe\u7f6e","com.letv.leui.schpwronoff","23","6C735FFC4F80016B272D552379234CCC"],["\u5b89\u5168\u9632\u62a4","com.qapp.secprotect","1","CCC76D3818A51F5AA8A30540FE37895F"],["\u8bbe\u5907\u4fe1\u606f","com.qrd.engineeringmode","23","7877DE6585438953816AC79921BA359C"],["com.qrd.frameworks.telresources","com.qrd.frameworks.telresources","23","F3F6EB1407721CF07F3FA056BCE37FF8"],["Backup Agent","com.qti.backupagent","1","8CD75282D780F749D1557CAFECFA3CBB"],["com.qti.dpmserviceapp","com.qti.dpmserviceapp","23","1265F3D736969C29C914832346E6B594"],["com.qti.primarycardcontroller","com.qti.primarycardcontroller","23","1807B6AA03B09DF445D98A0F1D27AB21"],["com.qti.service.colorservice","com.qti.service.colorservice","1","C5EE6A0DEDFB5747661A194A7D4A3528"],["com.qti.xdivert","com.qti.xdivert","23","D2C4BE965165CBB375472BF5C9C25A14"],["com.quicinc.cne.CNEService","com.quicinc.cne.CNEService","1","E2ACAF6E00FFBCA9019B7D5E7C40C833"],["\u4eba\u4eba-\u7f8e\u56fe\u7f8e\u989c\u76f4\u64ad","com.renren.mobile.android","8050800",null],["\u7f8e\u56e2","com.sankuai.meituan","351",null],["\u641c\u72d7\u8f93\u5165\u6cd5","com.sohu.inputmethod.sogou","540","57D7A06044BECB62255F58A5EE7393A8"],["\u4eca\u65e5\u5934\u6761","com.ss.android.article.news","537",null],["\u63a8\u9001\u670d\u52a1","com.stv.stvpush","1500","E48FD55F1A71ACCF7CCF1CEADA7CA67C"],["Pico TTS","com.svox.pico","1","A276308106921EDA4A77DF9614A530EF"],["\u5e94\u7528\u5b9d","com.tencent.android.qqdownloader","6502130",null],["QQ\u6d4f\u89c8\u5668","com.tencent.mtt","662380","102DC1474F6D2F5A211619A7D0B173BA"],["\u817e\u8baf\u65b0\u95fb","com.tencent.news","479",null],["\u817e\u8baf\u89c6\u9891","com.tencent.qqlive","10223",null]]',
////            ],
////
////        ];
////        $list = new \App\Models\UsersList();
////       \DB::beginTransaction();
////        foreach($item as $it) {
////            $list->updatePackageItem($it);
////        }
////       \DB::commit();
////return '123';
//        \App\Libraries\Cache::getInstance()->redis->flushdb();
//
//        $u = new \App\Models\Assistant\User();
//        $ul = new \App\Models\Assistant\UserLocation();
//   //     $usp = new \App\Models\Assistant\UserSnapshots();
//       $us = new \App\Models\Assistant\UserState();
////
////        $s = time();
////
//       $users = $u->orderBy('id', 'desc')->get();
////
//         $usersLocation = $ul->orderBy('user_id', 'desc')->get();
////  $usersSnapshot = $usp->orderBy('user_id', 'desc')->take(1000)->get();
//        $usersState = $us->orderBy('user_id', 'desc')->get();
////
//       \App\Libraries\Cache::getInstance()->load('user',$users);
////
//        \App\Libraries\Cache::getInstance()->load('user_location',$usersLocation);
// // \App\Libraries\Cache::getInstance()->load('user_snapshot',$usersSnapshot);
//        \App\Libraries\Cache::getInstance()->load('user_state',$usersState);
//
//       //  $e = new \App\Models\Assistant\UserEvent();
//        // \App\Libraries\Cache::getInstance()->load('user_event',$e->take(1000)->get());

    }
}
