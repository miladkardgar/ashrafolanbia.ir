<?php

namespace App\Http\Controllers\panel;

use App\ApplicationSetting;
use App\bank;
use App\blog;
use App\blog_categories;
use App\blog_option;
use App\building_item;
use App\building_project;
use App\building_ticket;
use App\building_type;
use App\building_type_itme;
use App\building_user;
use App\c_store_setting;
use App\caravan_doc;
use App\caravan_host;
use App\category;
use App\champion_transaction;
use App\charity_champion;
use App\charity_payment_patern;
use App\charity_payment_title;
use App\charity_period;
use App\charity_periods_transaction;
use App\charity_transaction;
use App\charityPaymentPT;
use App\city;
use App\Events\c_storePaymentAlert;
use App\Events\payToCharityMoney;
use App\Exports\InvoicesExport_charity_other_payments;
use App\Exports\InvoicesExport_charity_routine;
use App\gallery_category;
use App\gateway;
use App\gateway_transaction;
use App\Mail\payment_confirmation;
use App\Mail\userRegisterMail;
use App\notification_template;
use App\order;
use App\period;
use App\Permission;
use App\person;
use App\product_category;
use App\person_caravan;
use App\Role;
use App\setting_transportation;
use App\store_category;
use App\store_discount_code;
use App\store_item;
use App\store_item_category;
use App\store_product;
use App\Team;
use App\User;
use App\caravan;
use App\users_address;
use App\video_gallery;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Models\LaratrustRole;
use Maatwebsite\Excel\Facades\Excel;
use mysql_xdevapi\Collection;
use phpDocumentor\Reflection\Types\Array_;
use WebDevEtc\BlogEtc\Models\BlogEtcComment;
use WebDevEtc\BlogEtc\Models\BlogEtcPost;

class panel_view extends Controller
{
    //
    public function dashboard()
    {
//        return request()->segment(1);
        $info = User::find(\Auth::id());

        $trans = DB::table('gateway_transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(price) as price'), DB::raw('module'))
            ->whereIn('module', ['charity_donate', 'charity_vow', 'charity_period'])
            ->where('status', '=', 'SUCCEED')
            ->groupBy('module')
            ->get();
        $trans = json_decode($trans, true);
        $l = [];
        $legend = '';
        $date = '';
        foreach ($trans as $tran => $value) {
            if (!in_array($value['module'], $l)) {
                $legend .= "'" . __("messages." . $value['module']) . "',";
                array_push($l, $value['module']);
            }
        }
        $dates = strtotime('-20 day', time());

        for ($dates; $dates < time(); $dates = strtotime("+1 day", $dates)) {
            $date .= "'" . jdate("Y-m-d", $dates, '', '', 'en') . "',";
        }
        foreach ($trans as $tran => $value) {
            $dates = strtotime('-20 day', time());
            for ($dates; $dates < time(); $dates = strtotime("+1 day", $dates)) {
                $date2 = Carbon::parse(date("Y-m-d", $dates));
                $now = Carbon::parse(date("Y-m-d", strtotime($value['date'])));
                $diff = $date2->diffInDays($now);
                if ($diff == 0) {
                    if (isset($final[$value['module']]) and $final[$value['module']] != "") {
                        $final[$value['module']] .= number_format($value['price']) . ",";
                    } else {
                        $final[$value['module']] = number_format($value['price']) . ",";
                    }
                } else {
                    if (isset($final[$value['module']]) and $final[$value['module']] != "") {
                        $final[$value['module']] .= rand(10000, 90000) . ",";
                    } else {
                        $final[$value['module']] = "0,";
                    }
                }
            }
        }

        $legend = trim($legend, ",");
        $date = trim($date, ",");


        $caravans_query = caravan::query();
        $caravans_query->with('caravan_docs.doc');
        $caravans_query->whereIn('status', [1, 2, 3, 4]);
        $caravans = $caravans_query->get();

        $postCount = BlogEtcPost::query();
        $postCount->where('is_published', 1);
        $postCount = $postCount->count();

        $userCount = User::query();
        $userCount->where('disabled', 0);
        $userCount = $userCount->count();

        $commentCount = BlogEtcComment::query();
        $commentCount->where('approved', 1);
        $commentCount = $commentCount->count();

        $caravansCount = caravan::query();
        $caravansCount = $caravansCount->count();
        return view('panel.dashboard', compact('info', 'legend', 'date', 'final', 'caravans', 'postCount', 'userCount', 'commentCount', 'caravansCount'));
    }

    public function users_list(Request $request)
    {
        $user_query = User::query();
        $query = null;
        $type = null;
        if ($request['type']) {
            switch ($request['type']) {
                case 'active':
                    $type = 'کاربران فعال';
                    $user_query->where('disabled', 0);
                    break;
                case 'inactive':
                    $type = 'کاربران غیرفعال';
                    $user_query->where('disabled', 1);
                    break;
                case 'admin':
                    $type = 'کاربران ادمین';
                    $user_query->where(function ($q) use ($query) {
                        $q->whereHas('role_user')->orWhereHas('permission_user');
                    });
                    break;
                default:

            }

        }
        if ($request['q']) {
            $query = $request['q'];
            $user_query->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhereHas('people', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%')
                            ->orWhere('family', 'like', '%' . $query . '%')
                            ->orWhere('en_name', 'like', '%' . $query . '%')
                            ->orWhere('en_family', 'like', '%' . $query . '%');
                    });
            });
        }
        $users = $user_query->with('people');
        $users = $user_query->paginate(20);
        $count = $user_query->count();

        $active_users = User::where('disabled', 0)->count();
        $inactive_users = User::where('disabled', 1)->count();
        $admin_users = User::whereHas('role_user')->orWhereHas('permission_user')->count();
        return view('panel.user_manager.users_list', compact('admin_users', 'users', 'count', 'type', 'active_users', 'inactive_users', 'query'));
    }

    public function permission_assign($permission_id)
    {
        $users = User::get();
        $permission = Permission::with('users', 'roles')->find($permission_id);
        $teams_roles = [];
        $teamForeignKey = Config::get('laratrust.foreign_keys.team');
        foreach ($permission['roles'] as $role) {
            $teams_roles[$role['pivot'][$teamForeignKey]][] = $role;
        }
        return view('panel.user_manager.permission_assign_page', compact('permission', 'users', 'teams_roles'));
    }

    public function user_permission_assign($user_id)
    {
        $user = user::with('permissions', 'roles')->find($user_id);
        $checked_permissions = [];
        foreach ($user['permissions'] as $permission) {
            $checked_permissions[] = $permission['id'];
        }
        $categories = Permission::groupBy('category')->get(['category']);
        $categories_permissions = [];
        foreach ($categories as $category) {
            $category_permissions = Permission::where('category', $category['category'])->get();
            $categories_permissions[$category['category']] = $category_permissions;
        }

        return view('panel.user_manager.user_permission_page', compact('user', 'categories_permissions', 'checked_permissions'));
    }

    public function assign_user_to_permission_form($permission_id)
    {
        $users = User::get();
        return view('panel.user_manager.assign_user_to_permission_form', compact('permission_id', 'users'));
    }

    public function assign_role_to_permission_form($permission_id, $old = null, $team_id = null)
    {
        $roles = Role::get();
        $teams = Team::all();
        $checked_roles = [];
        $current_roles = Permission::with('roles')->find($permission_id);
        $checked_team = null;
        $old_team = [];
        if ($old and !empty($current_roles['roles'])) {
            $checked_team = (empty($team_id) ? "0" : $team_id);
            foreach ($current_roles['roles'] as $current_role) {

                if ($current_role['pivot'][Config::get('laratrust.foreign_keys.team')] == $team_id) {
                    $checked_roles[] = $current_role['id'];
                    $old_team[] = $team_id . "-" . $current_role['id'];

                }
            }
        }
        return view('panel.user_manager.assign_role_to_permission_form', compact('permission_id', 'roles', 'teams', 'checked_roles', 'checked_team', 'old_team'));
    }

    public function assign_role_to_user_form($user_id)
    {
        $roles = Role::get();
        $user = user::with('permissions', 'roles')->find($user_id);
        $checked_roles = [];

        foreach ($user['roles'] as $role) {
            $checked_roles[] = $role['id'];
        }
        return view('panel.user_manager.assign_role_to_user_form', compact('user_id', 'roles', 'checked_roles'));
    }

    public function register_form()
    {
        return view('panel.user_manager.user_register_form');
    }

    public function permissions_list()
    {
        $categories = Permission::groupBy('category')->get(['category']);
        $categories_permissions = [];
        foreach ($categories as $category) {
            $category_permissions = Permission::where('category', $category['category'])->orderBy("id", "ASC")->get();
            $categories_permissions[$category['category']] = $category_permissions;
        }
        return view('panel.user_manager.permissions_list', compact('categories_permissions'));
    }

    public function register_permission_form()
    {
        $categories = Permission::groupBy('category')->get(['category']);
        return view('panel.user_manager.permission_register_form', compact('categories'));
    }

    public function roles_list()
    {
        $roles = Role::all();
        return view('panel.user_manager.roles_list', compact('roles'));
    }

    public function register_role_form()
    {
        return view('panel.user_manager.role_register_form');
    }

    public function teams_list()
    {
        $teams = Team::all();
        return view('panel.user_manager.teams_list', compact('teams'));
    }

    public function register_team_form()
    {
        return view('panel.user_manager.team_register_form');
    }

    public function form_notification()
    {
        return view('panel.materials.form_notification');
    }


    public function permissions_team_list(Request $request)
    {
        $teamInfo = Team::find($request['team_id']);
        $permissionRoles = Permission::with('roles')->get();
        $teams_roles = [];
        $teamForeignKey = Config::get('laratrust.foreign_keys.team');
        foreach ($permissionRoles['roles'] as $role) {
            if ($request->team_id == $role['pivot']['team_id']) {
                $teams_roles[$role['pivot'][$teamForeignKey]][] = $role;
            }

        }
        return view('panel.user_manager.teams_list_permissions', compact('teams_roles', 'permissionRoles', 'teamInfo'));
    }


    public function role_edit(Role $id)
    {
        return view('panel.user_manager.role_edit', compact('id'));
    }

    public function team_edit(Team $id)
    {
        return view('panel.user_manager.team_edit', compact('id'));
    }

    public function role_update(Role $role)
    {
        $data = \request()->validate(
            [
                'name' => 'required|min:3',
                'display_name' => 'required|min:3',
                'description' => ''
            ]
        );
        $role->update($data);
        return back_normal(\request(), __('messages.item_updated'));
    }

    public function team_update(Team $team)
    {
        $data = \request()->validate(
            [
                'name' => 'required|min:3',
                'display_name' => 'required|min:3',
                'description' => ''
            ]
        );
        $team->update($data);
        return back_normal(\request(), __('messages.item_updated'));
    }

    public function users_list_info_edit(User $userInfo)
    {
        return view('panel.user_manager.index', compact('userInfo'));
    }
//end users module

//blog module
    public function post_add()
    {
        $cats = category::all();
        return view('panel.blog.post_add', compact('cats'));
    }

    public function post_list()
    {
        $posts = \App\blog::with('blog_categories.category')->get();
        $postCount = blog::count();
        return view('panel.blog.post_list', compact('posts', 'postCount'));
    }

    public function post_edit_form(Request $request)
    {

        $post = \App\blog::with(['blog_categories.category', 'blog_tag'])->find($request['post_id']);
        $cats = category::all();
        return view('panel.blog.post_edit', compact('post', 'cats'));
    }

    public function category_list()
    {
        $cats = category::all();
        return view('panel.blog.category_list', compact('cats'));
    }

    public function category_add_form()
    {
        return view('panel.blog.category_add');
    }

    public function category_edit_form(Request $request)
    {
        $cat_info = category::find($request['cat_id']);
        return view('panel.blog.category_edit', compact('cat_info'));
    }

    public function display_statistics(Request $request)
    {
        $statistics = blog_option::where('name', 'display_statistic')->get();
        return view('panel.blog_setting.display_statistics', compact('statistics'));

    }

    public function load_display_statistics_form($option_id = null, Request $request)
    {
        $icons = [];
        $handle = fopen(url("public/assets/global/css/pe-icon-7-stroke.css"), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/\.(.*?)(:before)/', $line, $matches)) {
                    $icons[] = $matches[1];
                }
            }
            fclose($handle);
        } else {
            // error opening the file.
        }

        if ($option_id) {
            $option = blog_option::find($option_id);
        } else {
            $option = null;
        }
        return view('panel.blog_setting.materials.display_statistic_form', compact('option', 'icons'));
    }

    public function adv_links(Request $request)
    {
        $adv_links = blog_option::where('name', 'adv_link')->get();
        return view('panel.blog_setting.adv_links', compact('adv_links'));
    }

    public function load_adv_card_form($option_id = null, Request $request)
    {

        if ($option_id) {
            $option = blog_option::find($option_id);
        } else {
            $option = null;
        }
        return view('panel.blog_setting.materials.adv_card_form', compact('option'));
    }

    public function load_adv_bar_form($option_id = null, Request $request)
    {

        if ($option_id) {
            $option = blog_option::find($option_id);
        } else {
            $option = null;
        }
        return view('panel.blog_setting.materials.adv_bar_form', compact('option'));
    }

    public function more_blog_setting(Request $request)
    {
        return view('panel.blog_setting.more_setting');
    }


//end blog module


//caravan module
    public function caravan_dashboard()
    {
        $caravans_query = caravan::query();
        $caravans_query->with('caravan_docs.doc');
        $caravans_query->whereIn('status', [1, 2, 3, 4]);
        $caravans = $caravans_query->get();
        return view('panel.caravan.dashboard', compact('caravans'));
    }

    public function hosts_list()
    {
        $hosts = caravan_host::with('media')->get();

        return view('panel.caravan.hosts_list', compact('hosts'));
    }

    public function load_host_form($host_id = null)
    {
        if ($host_id) {
            $host = caravan_host::find($host_id);
        } else {
            $host = null;
        }
        return view('panel.caravan.materials.add_new_host_form', compact('host'));
    }

    public function add_caravan_page($caravan_id = null)
    {
        if ($caravan_id) {
            $caravan = caravan::find($caravan_id);
        } else {
            $caravan = null;
        }

        $caravan_hosts = caravan_host::get();
        $users = User::get();
        return view('panel.caravan.add_caravan_page', compact('caravan', 'caravan_hosts', 'users'));
    }

    public function caravans_list(Request $request)
    {
        $caravans_query = caravan::query();

        $status_array = $request->input('status');

        if (is_array($status_array)) {
            foreach ($status_array as $status) {
                $caravans_query->where('status', $status);
            }
        } elseif (is_numeric($status_array)) {
            $caravans_query->where('status', $status_array);
        }
        $caravans = $caravans_query->get();
        return view('panel.caravan.caravans_list', compact('caravans'));
    }

    public function caravan($caravan_id)
    {
        $caravan = caravan::with('host', 'workflow', 'persons.person')->find($caravan_id);
        return view('panel.caravan.view_caravan', compact('caravan'));
    }

    public function register_to_caravan($caravan_id, $person_caravan_id = null)
    {
        $caravan = caravan::find($caravan_id);
        $person = null;
        if (!empty($person_caravan_id)) {
            $person = person_caravan::with('person')->find($person_caravan_id);
        }
        return view('panel.caravan.register_to_caravan_form', compact('caravan', 'person'));
    }

    public function register_to_caravan_post(Request $request)
    {
        $this->validate($request, [
            'caravan_id' => 'required',
            'national_code' => 'required',
        ]);
        $national_validate = national_code_validation($request['national_code']);
        if (!$national_validate) {
            $errors[] = trans('messages.national_code_error');
            return back_error($request, $errors);
        }
        $caravan = caravan::find($request['caravan_id']);
        $national_code = $request['national_code'];
        $person = person::where('national_code', $national_code)->first();

        return view('panel.caravan.register_to_caravan_form', compact('caravan', 'national_code', 'person'));
    }

    public function change_caravan_status_form($caravan_id, $status)
    {
        //$status "back" "next" "cancel"
        $caravan = caravan::find($caravan_id);
        return view('panel.caravan.materials.change_caravan_status', compact('caravan', 'status'));
    }

    public function action_to_person_caravan_status($person_caravan_id)
    {
        //$status "back" "next" "cancel"
        $person_caravan = person_caravan::find($person_caravan_id);
        $person_history = person_caravan::with('caravan')->where('person_id', $person_caravan['person_id'])
            ->where('id', '!=', $person_caravan_id)
            ->get();

        return view('panel.caravan.materials.caravan_person_action', compact('person_caravan', 'person_history'));
    }

    public function caravan_upload_doc($caravan_id, $caravan_doc_id = null)
    {

        $caravan = caravan::find($caravan_id);
        $caravan_doc = null;
        if ($caravan_doc_id) {
            $caravan_doc = caravan_doc::find($caravan_doc_id);
        }

        return view('panel.caravan.materials.upload_doc_form', compact('caravan', 'caravan_doc'));
    }

    public function caravans_echart_data()
    {
        $hosts = caravan_host::get();
        $now_date = date('Y-m-d H:i:s');
        $start_date = date('Y-m-d H:i:s', strtotime('-1 years'));
        $first_date = $start_date;
        $this_end = $start_date;
        $info = [];
        foreach ($hosts as $host) {
            $host_count = caravan::where('caravan_host_id', $host['id'])->whereBetween('start', [$first_date, $now_date])->where('status', '5')->count();
//            if ($host_count>0) {
            for ($i = 1; $i <= 12; $i++) {
                $this_start = $this_end;
                $this_end = date('Y-m-d H:i:s', strtotime('+1 months', strtotime($this_start)));
                $caravans_count = caravan::where('caravan_host_id', $host['id'])->whereBetween('start', [$this_start, $this_end])->where('status', '5')->count();
                $info[$host['name']][jdate('Y F', strtotime($this_start))] = $caravans_count;
//                }
            }
        }
//        return response()->json($info);
        return $info;
    }


//end caravan module


//building module
    public function building_dashboard(Request $request)
    {

        $projects_query = building_project::query();
        $projects_query->with('media', 'city.all_provinces');
        $lvl = "city_id";
        if ($request['city']) {
            $this_city = city::find($request['city']);
            switch ($this_city['lvl']) {
                case 1 :
                    $projects_query->select('city_id_2', DB::raw('count(*) as total'));
                    $projects_query->where('city_id', $request['city']);
                    $projects_query->groupBy('city_id_2');
                    $lvl = "city_id_2";
                    break;
                case 2 :
                    $projects_query->select('city_id_3', DB::raw('count(*) as total'));
                    $projects_query->where('city_id_2', $request['city']);
                    $projects_query->groupBy('city_id_3');
                    $lvl = "city_id_3";
                    break;
                case 3 :
                    $projects_query->where('city_id_3', $request['city']);
                    $lvl = "project";
                    break;
                default:
                    $projects_query->select('city_id', DB::raw('count(*) as total'));
                    $projects_query->groupBy('city_id');
                    $lvl = "city_id";
            }
        } else {
            $projects_query->select('city_id', DB::raw('count(*) as total'));
            $projects_query->groupBy('city_id');
            $lvl = "city_id";

        }
        $selected_city = $request['city'];
        $projects = $projects_query->get();
        $provinces = city::where('parent', '=', 0)->whereHas('province_project')->get()->map(function ($city) {

            return [
                'name' => $city->name,
                'id' => $city->id,
                'parent' => $city->parent,
                'openProjects' => building_project::where('archived', 0)->where(function ($q) use ($city) {
                    $q->where('city_id', $city->id)->orWhere('city_id_2', $city->id)->orWhere('city_id_3', $city->id);
                })->count(),
                'archivedProjects' => building_project::where('archived', 1)->where(function ($q) use ($city) {
                    $q->where('city_id', $city->id)->orWhere('city_id_2', $city->id)->orWhere('city_id_3', $city->id);
                })->count(),
                'cities' => $this->cityLoop($city->id)
            ];
        });
        return view('panel.building.dashboard', compact('projects', 'lvl', 'provinces', 'selected_city'));
    }

    private function cityLoop($cityId)
    {
        $subCities = city::where('parent', $cityId)->get();
        $mapSubCities = [];
        foreach ($subCities as $subCity) {
            $mapSubCities[] = [
                'name' => $subCity->name,
                'id' => $subCity->id,
                'parent' => $subCity->parent,
                'openProjects' => building_project::where('archived', 0)->where(function ($q) use ($subCity) {
                    $q->where('city_id', $subCity->id)->orWhere('city_id_2', $subCity->id)->orWhere('city_id_3', $subCity->id);
                })->count(),
                'archivedProjects' => building_project::where('archived', 1)->where(function ($q) use ($subCity) {
                    $q->where('city_id', $subCity->id)->orWhere('city_id_2', $subCity->id)->orWhere('city_id_3', $subCity->id);
                })->count(),
                'cities' => $this->cityLoop($subCity->id)
            ];
        }
        return $mapSubCities;

    }

    public function building_project($project_id, Request $request)
    {
        $ticket_item_checkbox = $request->input('ticket_item_checkbox');
        $ticket_item_filter = $request->input('ticket_item_filter');
        $ticket_status_checkbox = $request->input('ticket_status_checkbox');
        $ticket_status_filter = $request->input('ticket_status_filter');


        $projects = building_project::with('gallery', 'media', 'building_items', 'building_users')->find($project_id);
        $progress_tickets = building_ticket::where('ticket_type', '0')
            ->where('building_id', $project_id)->get();
        $building_items_obj = building_item::where('building_id', $project_id)->get();
        $building_items = [];
        foreach ($building_items_obj as $item) {
            $building_items[$item['id']] = $item->toArray();
        }
        $total_progress = 0;
        $items_progress = [];
        foreach ($progress_tickets as $progress_ticket) {
            if ($progress_ticket['closed']) {
                if ($progress_ticket['actual_percent'] > 0) {
                    $total_progress += ($progress_ticket['actual_percent'] * $building_items[$progress_ticket['item_id']]['percent'] / 100);
                    if (isset($items_progress[$progress_ticket['item_id']]['actual'])) {
                        $items_progress[$progress_ticket['item_id']]['actual'] += $progress_ticket['actual_percent'];
                    } else {
                        $items_progress[$progress_ticket['item_id']]['actual'] = $progress_ticket['actual_percent'];
                    }
                }
            } else {
                if ($progress_ticket['predict_percent'] > 0) {
                    if (isset($items_progress[$progress_ticket['item_id']]['predict'])) {
                        $items_progress[$progress_ticket['item_id']]['predict'] += $progress_ticket['predict_percent'];

                    } else {
                        $items_progress[$progress_ticket['item_id']]['predict'] = $progress_ticket['predict_percent'];
                    }
                }
            }
        }

        return view('panel.building.building_project_page', compact('projects', 'total_progress', 'items_progress',
            'ticket_item_checkbox', 'ticket_item_filter', 'ticket_status_checkbox', 'ticket_status_filter'));
    }

    public function building_tree_view()
    {
        $provinces = city::where('parent', '=', 0)->get();
        $all_cities = city::pluck('name', 'id')->all();
        return view('panel.building.materials.tree_view', compact('provinces', 'all_cities'));
    }


    public function building_types()
    {
        $building_types = building_type::get();
        return view('panel.building.building_types', compact('building_types'));
    }

    public function building_type_page($building_type_id)
    {

        $building_type = building_type::with('building_type_items')->find($building_type_id);
        return view('panel.building.building_type_page', compact('building_type'));
    }

    public function building_archive()
    {
        return view('panel.building.building_archive');
    }

    public function load_building_type_form($building_type_id = null)
    {
        if ($building_type_id) {
            $building_type = building_type::find($building_type_id);
        } else {
            $building_type = null;
        }
        return view('panel.building.materials.add_new_building_type_form', compact('building_type'));
    }

    public function load_new_building_form($project_id = null)
    {
        if ($project_id) {
            $project = building_project::find($project_id);
        } else {
            $project = null;
        }
        return view('panel.building.materials.add_new_project_form', compact('project'));
    }

    public function load_building_items_form($project_id)
    {

        $building_items = building_item::where('building_id', $project_id)->get();

        return view('panel.building.materials.project_items_form', compact('building_items', 'project_id'));
    }

    public function load_building_users_form($project_id)
    {

        $building_users = building_user::where('building_id', $project_id)->get();
        $users = User::with(['building_users' => function ($q) use ($project_id) {
            $q->where('building_id', $project_id);
        }])->get();
        return view('panel.building.materials.project_users_form', compact('building_users', 'project_id', 'users'));
    }

    public function building_type_item_add_form($type_id, $item_id = null)
    {
        if ($item_id) {
            $type_item = building_type_itme::find($item_id);
        } else {
            $type_item = null;
        }
        return view('panel.building.materials.add_new_type_item_form', compact('type_id', 'type_item'));
    }

    public function new_ticket($project_id)
    {
        return view('panel.building.subpages.new_ticket', compact('project_id'));
    }

    public function ticket_page($ticket_id)
    {
        $ticket = building_ticket::with('histories.note.files')->find($ticket_id);
        $project = building_project::find($ticket['building_id']);
        return view('panel.building.subpages.ticket_page', compact('project', 'ticket', 'ticket_id'));
    }

    public function load_building_ticket_close_form($ticket_id)
    {
        $ticket = building_ticket::find($ticket_id);
        return view('panel.building.materials.close_ticket_form', compact('ticket_id', 'ticket'));
    }

    public function building_gallery_view(Request $request)
    {
        $catInfo = building_project::find($request['id']);
        $medias = \App\media::where(
            [
                ['category_id', '=', $request['id']],
                ['module', '=', 'building'],
            ])->get();
        return view('panel.building.gallery.view', compact('medias', 'catInfo'));
    }

    public function building_project_finish_form($id)
    {
        $building = building_project::find($id);
        return view('panel.building.materials.finish_form', compact('building'));
    }
//end building module

//charity module

    public function charity_payment_title()
    {
        $periodic_title = charity_payment_patern::with('titles')->where('system', 1)->where('periodic', 1)->first();
        $system_title = charity_payment_patern::with('titles')->where('system', 1)->where('periodic', 0)->first();
        $deleted_titles = charity_payment_title::where('ch_pay_pattern_id', $system_title['id'])->onlyTrashed()->get();
        $other_titles = charity_payment_patern::with('titles')->with('fields')->where('system', 0)->where('periodic', 0)->get();
        $champion_titles = charity_payment_patern::with('titles')->where('type', '=', 'champion')->first();
        $champions = charity_champion::with('image')->where('status', '=', 1)->get();
        $banks = bank::groupBy('name')->get();
        return view('panel.charity.setting.payment_titles', compact('periodic_title', 'system_title', 'other_titles', 'deleted_titles', 'champion_titles', 'banks', 'champions'));
    }
    public function charity_payment_title_titles()
    {
        $titles = charity_payment_title::all();
        return view('panel.charity.setting.titles',compact('titles'));
    }


    private function charity_period_list_data(Request $request, $paginate = 100)
    {
        $user_query = User::query();
        $user_query->with('routine');
        if ($request['q']) {
            $quesry = $request['q'];
            $user_query->where(function ($q) use ($quesry) {
                $q->where('name', 'like', '%' . $quesry . '%')
                    ->orWhere('phone', 'like', '%' . $quesry . '%')
                    ->orWhere('email', 'like', '%' . $quesry . '%');
            });
        }
//        if ($request['from']) {
//            $from_date = shamsi_to_miladi(latin_num($request['from']));
//            $user_query->where('created_at',">=",$from_date);
//        }
//        if ($request['to']) {
//            $to_date = shamsi_to_miladi(latin_num($request['to']));
//            $user_query->where('created_at',"<=",$to_date);
//
//        }
        if ($request['status']) {
            switch ($request['status']) {
                case 'all':
                    break;
                case 'active':
                    $user_query->whereHas('routine');
                    break;
                case 'inactive':
                    $user_query->whereDoesntHave('routine');
                    break;
            }
        } else {
            $user_query->whereHas('routine');
        }
        if ($request['sort']) {
            switch ($request['sort']) {
                case 'date-a':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'paid');
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('MAX(charity_periods_transactions.pay_date) as pay_date')]));
                    $user_query->orderBy('pay_date', 'DESC');
                    break;
                case 'date-d':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'paid');
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('MAX(charity_periods_transactions.pay_date) as pay_date')]));
                    $user_query->orderBy('pay_date', 'ASC');
                    break;
                case 'count-a':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'unpaid')
                            ->where('charity_periods_transactions.deleted_at', '=', NULL)
                            ->where('charity_periods_transactions.group_pay', '=', 0);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('COUNT(charity_periods_transactions.id) as count')]));
                    $user_query->orderBy('count', 'ASC');
                    break;
                case 'count-d':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'unpaid')
                            ->where('charity_periods_transactions.deleted_at', '=', NULL)
                            ->where('charity_periods_transactions.group_pay', '=', 0);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('COUNT(charity_periods_transactions.id) as count')]));
                    $user_query->orderBy('count', 'DESC');
                    break;
                case 'count-p-a':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'paid')
                            ->where('charity_periods_transactions.deleted_at', '=', NULL)
                            ->where('charity_periods_transactions.group_pay', '=', 0);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('COUNT(charity_periods_transactions.id) as count')]));
                    $user_query->orderBy('count', 'ASC');
                    break;
                case 'count-p-d':
                    $user_query->join('charity_periods_transactions', function ($join) {
                        $join->on('charity_periods_transactions.user_id', '=', 'users.id')
                            ->where('charity_periods_transactions.status', '=', 'paid')
                            ->where('charity_periods_transactions.deleted_at', '=', NULL)
                            ->where('charity_periods_transactions.group_pay', '=', 0);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('COUNT(charity_periods_transactions.id) as count')]));
                    $user_query->orderBy('count', 'DESC');
                    break;
                case 'amount-a':
                    $user_query->join('charity_periods', function ($join) {
                        $join->on('charity_periods.user_id', 'users.id')
                            ->where('charity_periods.status', 'active')
                            ->where('charity_periods.deleted_at', NULL);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('sum(charity_periods.amount) as amount')]));
                    $user_query->orderBy('amount', 'DESC');
                    break;
                case 'amount-d':
                    $user_query->join('charity_periods', function ($join) {
                        $join->on('charity_periods.user_id', 'users.id')
                            ->where('charity_periods.status', 'active')
                            ->where('charity_periods.deleted_at', NULL);
                    });
                    $user_query->groupBy('users.id');
                    $user_query->select((['users.*', DB::raw('sum(charity_periods.amount) as amount')]));
                    $user_query->orderBy('amount', 'ASC');
                    break;
                case 'date-r-d':
                    $user_query->orderBy('id', 'ASC');
                    break;
                default :
                    $user_query->orderBy('id', 'DESC');
                    break;
            }
        } else {
            $user_query->orderBy('id', 'DESC');
        }
        if ($paginate > 0) {
            $users = $user_query->paginate($paginate);
        } else {
            $users = $user_query->get();
        }

        $users->transform(function ($user) {
            $response = [
                'id' => $user->id,
                'name' => get_name($user->id),
                'phone' => $user->phone,
                'routine_status' => $user->routine ? true : false,
                'routine_type' => "",
                'routine_amount' => "",
                'unpaid' => $user->count,
                'paid' => 0,
                'last_paid' => "",
                'created_at' => $user->created_at,
            ];
            if ($user->routine) {
                $response['routine_type'] = \config('charity.routine_types.' . $user->routine->period . '.title');
                $response['routine_amount'] = $user->routine->amount;
            }

            $unpaid_count = charity_periods_transaction::where('status', 'unpaid')->where('user_id', $user->id)->count();
            $response['unpaid'] = $unpaid_count;

            $paid_count = charity_periods_transaction::where('status', 'paid')->where('user_id', $user->id)->count();
            $response['paid'] = $paid_count;

            $last_paid = $response['last_paid'] = charity_periods_transaction::where('status', 'paid')->where('user_id', $user->id)->orderBy('pay_date', 'DESC')->first();
            if ($last_paid) {
                $response['last_paid'] = jdate('Y/m/d', strtotime($last_paid->pay_date));
            }
            return $response;
        });

        return $users;
    }

    public function charity_period_list(Request $request)
    {
        if ($request['excel']) {

            $users = $this->charity_period_list_data($request, 0);

            $export = new InvoicesExport_charity_routine([
                'users' => $users,
            ]);

            return Excel::download($export, 'Report.xlsx');
        } else {
            $users = $this->charity_period_list_data($request, 100);
            $count = $users->total();
            $active_users = User::whereHas('routine')->count();
            $inactive_users = User::whereDoesntHave('routine')->count();
            $paid_routine = charity_periods_transaction::whereNotNull('pay_date')->count();
            $unpaid_routine = charity_periods_transaction::whereNull('pay_date')->count();

            $query = "";
            $sort = "";
            $status = "";
            if ($request['q']) {
                $query = $request['q'];
            }
            if ($request['status']) {
                switch ($request['status']) {
                    case 'all':

                        break;
                    case 'active':
                        $status = "دارای کمک ماهانه فعال";
                        break;
                    case 'inactive':
                        $status = "بدون کمک ماهانه";
                        break;
                }
            }
            if ($request['sort']) {
                switch ($request['sort']) {
                    case "date-a":
                        $sort = "نزدیکترین تاریخ پرداخت";
                        break;
                    case "date-d":
                        $sort = "دور ترین تاریخ پرداخت";
                        break;
                    case "date-r-a":
                        $sort = "نزدیکترین تاریخ عضویت";
                        break;
                    case "date-r-d":
                        $sort = "دور ترین تاریخ عضویت";
                        break;
                    case "count-a":
                        $sort = " بیشترین در انتظار پرداخت";
                        break;
                    case "count-d":
                        $sort = "  کمترین در انتظار پرداخت";
                        break;
                    case "count-p-a":
                        $sort = "  کمترین پرداخت شده";
                        break;
                    case "count-p-d":
                        $sort = "بیشترین پرداخت شده";
                        break;
                    case "amount-a":
                        $sort = "بیشترین مبلغ";
                        break;
                    case "amount-d":
                        $sort = "کمترین مبلغ";
                        break;
                }
            }

            return view('panel.charity.period.list', compact('users', 'active_users'
                , 'inactive_users', 'paid_routine', 'unpaid_routine', 'query', 'sort', 'status', 'count'));
        }

    }


    public function charity_period_status()
    {

        $payments = charity_periods_transaction::with('period', 'gateway')->get();
        return view('panel.charity.period.status', compact('payments'));
    }

    public function charity_period_status_show(Request $request)
    {
        $periodInfo = charity_periods_transaction::with('tranInfo', 'user')->find($request['id']);

        return view('panel.charity.period.status_show', compact('periodInfo'));
    }

    public function charity_payment_title_add($payment_pattern_id, $payment_title_id = null)
    {
        $payment_title = null;
        $payment_pattern = charity_payment_patern::find($payment_pattern_id);
        if ($payment_title_id) {
            $payment_title = charity_payment_title::find($payment_title_id);
        }
        return view('panel.charity.setting.module.add_new_payment_title_form', compact('payment_title', 'payment_pattern'));
    }

    public function charity_payment_title_recover($payment_pattern_id, $payment_title_id)
    {
        $payment_pattern = charity_payment_patern::find($payment_pattern_id);
        $payment_title = charity_payment_title::withTrashed()->find($payment_title_id);

        return view('panel.charity.setting.module.recover_new_payment_title_form', compact('payment_title', 'payment_pattern'));
    }

    public function charity_payment_pattern_add($payment_pattern_id = null)
    {

        $payment_pattern = null;
        if ($payment_pattern_id) {
            $payment_pattern = charity_payment_patern::with('fields')->find($payment_pattern_id);
        }
        $titles = $payment_pattern->titles()->get()->mapWithKeys(function ($title) {
            return [
                $title['id'] => $title['id']
            ];
        })->toArray();
        return view('panel.charity.setting.module.add_new_payment_pattern_form', compact('payment_pattern', 'titles'));
    }

    public function charity_payment_list(Request $request)
    {
        $avg_30 = charity_transaction::where('payment_date', '>=', date("Y-m-d", strtotime(date("Y-m-d H:i:s") . " -1 year")))->where('status', 'success')->count() / 12;
        $last_30 = charity_transaction::where('payment_date', '>=', date("Y-m-d", strtotime(date("Y-m-d H:i:s") . " -30 days")))->where('status', 'success')->count();
        $price_30 = charity_transaction::where('payment_date', '>=', date("Y-m-d", strtotime(date("Y-m-d H:i:s") . " -30 days")))->where('status', 'success')->sum('amount');
        $faild_30 = charity_transaction::where('created_at', '>=', date("Y-m-d", strtotime(date("Y-m-d H:i:s") . " -30 days")))->where('status', "!=", 'success')->count();
        $query = "";
        $status = null;
        $sort = null;
        $otherPayments_query = charity_transaction::query();
        if ($request['q']) {
            $query = $request['q'];
            $otherPayments_query->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            });
        }
        if ($request['sort']) {
            switch ($request['sort']) {
                case 'date-a':
                    $sort = "نزدیکترین تاریخ";
                    $otherPayments_query->orderBy('payment_date', 'DESC');
                    break;
                case 'date-d':
                    $sort = "دورترین تاریخ";
                    $otherPayments_query->orderBy('payment_date', 'ASC');
                    break;
                case 'amount-a':
                    $sort = "بیشترین مبلغ";
                    $otherPayments_query->orderBy('amount', 'DESC');
                    break;
                case 'amount-d':
                    $sort = "کمترین مبلغ";
                    $otherPayments_query->orderBy('amount', 'ASC');
                    break;
                default:
                    $otherPayments_query->orderBy('created_at', 'DESC');

            }
        } else {
            $otherPayments_query->orderBy('created_at', 'DESC');

        }
        if ($request['status']) {
            switch ($request['status']) {
                case 'success':
                    $status = "موفق";
                    $otherPayments_query->where('status', 'success');
                    break;
                case 'pending':
                    $status = "نامشخص";
                    $otherPayments_query->where('status', 'pending');
                    break;
                case 'fail':
                    $status = "ناموفق";
                    $otherPayments_query->where('status', 'fail');
                    break;
            }
        }

        $otherPayments_query->with('values', 'user', 'patern', 'title', 'tranInfo');
        $count = $otherPayments_query->count();

        if ($request['excel']) {
            $otherPayments = $otherPayments_query->get();

            $export = new InvoicesExport_charity_other_payments([
                'otherPayments' => $otherPayments,
            ]);
            return Excel::download($export, 'Report.xlsx');
        } else {
            $otherPayments = $otherPayments_query->paginate(50);

            return view('panel.charity.other_payment.list', compact('avg_30', 'last_30', 'faild_30', 'price_30', 'otherPayments', 'query', 'count', 'status', 'sort'));
        }
    }


    public function charity_champion_add()
    {
        $projects = building_project::where('archived', false)->get();
        return view('panel.charity.setting.module.add_champion', compact('projects'));
    }


    public function charity_payment_list_vow_show(Request $request)
    {

        $info = charity_transaction::with('tranInfo', 'patern', 'user', 'values', 'gateway', 'title')->findOrFail($request['id']);
        return view('panel.charity.other_payment.show', compact('info'));
    }


    public function charity_champion_edit($id)
    {

        if ($champion = charity_champion::with('tag', 'image', 'projects')->find($id)) {
            $projects = building_project::where('archived', false)->get();
            return view('panel.charity.setting.module.edit_champion', compact('champion', 'projects'));
        };
    }

    public function charity_champion_payments_list()
    {

        if ($champion = champion_transaction::with('champion', 'user', 'gateway')->get()) {
            return view('panel.charity.champion.list', compact('champion'));
        };
    }

    public function charity_champion_payments_show(Request $request)
    {
        $info = champion_transaction::with('tranInfo', 'user', 'gateway')->findOrFail($request['id']);
        return view('panel.charity.champion.show', compact('info'));
    }

    public function charity_reportsDEPRICATED()
    {

        $gateway = DB::table('gateway_transactions')->select(DB::raw('port'))->groupBy('port')->get();
        $titles = charity_payment_title::get();
        $pat = charity_payment_patern::get();
        $gateway = json_decode($gateway, true);
        return view('panel.charity.reports.report', compact('gateway', 'titles', 'pat'));
    }

    public function charity_reports(Request $request)
    {
        $selected_titles = charity_payment_title::limit(1)->get()->mapWithKeys(function ($title) {
            return [$title['id'] => $title['id']];
        })->toArray();


        $start_date = date("Y-m-d", strtotime(date('Y-m-d') . " -1 month"));
        $end_date = date("Y-m-d");
        $with_fails = $request->with_fails;
        if ($request->start_date) {
            $start_date = shamsi_to_miladi($request->start_date);
        }
        if ($request->end_date) {
            $end_date = shamsi_to_miladi($request->end_date);
        }
        if ($request->titles) {
            $selected_titles = $request->titles;
        }
        $sum_data = $this->get_charity_sum_report($start_date,$end_date,$selected_titles);
        $bank_balances = $this->get_bank_sum_report($start_date,$end_date,$selected_titles);
        $charity_titles = $this->get_title_sum_report($start_date,$end_date);
        $other_vow_query = charity_transaction::query();
        $other_vow_query->whereIn('title_id',$selected_titles);
        $other_vow_query->whereNotIn('charity_id', [3]);
        $other_vow_query->with('patern', 'title');
        $other_vow_query->whereBetween('payment_date', [$start_date, $end_date]);
        if (!$with_fails){
            $other_vow_query->where('status', 'success');
        }
        $other_vows = $other_vow_query->orderBy('id','desc')->paginate('33');
        $other_vows->getCollection();
        $other_vows->transform(function ($vow){

        $vow->gateway = gateway::find($vow['gateway_id'])['title'];
        $vow->status = __('messages.' . $vow['status']);
        $vow->payDate = miladi_to_shamsi_date($vow['payment_date']);

        return $vow;
        });
        return view('panel.charity.reports.megaReport',
            compact('charity_titles', 'with_fails', 'selected_titles', 'start_date',
                'end_date','sum_data','bank_balances','other_vows')
        );
    }

    private function get_charity_sum_report($start_date, $end_date,$titles)
    {
        $system_vow = charity_transaction::whereIn('title_id',$titles)->where('charity_id', 2)->whereBetween('payment_date', [$start_date, $end_date])->where('status', 'success')->sum('amount');
        $other_vow = charity_transaction::whereIn('title_id',$titles)->whereNotIn('charity_id', [1,2,3])->whereBetween('payment_date', [$start_date, $end_date])->where('status', 'success')->sum('amount');
        $routine_vow = charity_periods_transaction::whereIn('title_id',$titles)->whereBetween('pay_date', [$start_date, $end_date])->where('group_pay', 0)->where('status', 'paid')->sum('amount');
        return [
            "system_vow" => $system_vow,
            "other_vow" => $other_vow,
            "routine_vow" => $routine_vow,
        ];
    }
    private function get_bank_sum_report($start_date, $end_date,$titles)
    {
        $gateways = gateway::get()->transform(function ($gateway)use ($start_date, $end_date,$titles){
            $charity_vow = charity_transaction::whereIn('title_id',$titles)->where('gateway_id',$gateway['id'])->whereNotIn('charity_id', [1,3])->whereBetween('payment_date', [$start_date, $end_date])->where('status', 'success')->sum('amount');
            $routine_vow = charity_periods_transaction::whereIn('title_id',$titles)->where('gateway_id',$gateway['id'])->whereBetween('pay_date', [$start_date, $end_date])->where('group_pay', 0)->where('status', 'paid')->sum('amount');

            $gateway->balance = $charity_vow + $routine_vow;
            return $gateway;
        });
        return $gateways;
    }

    private function get_title_sum_report($start_date, $end_date)
    {
        $charity_titles = charity_payment_title::get()->transform(function ($title)use ($start_date,$end_date){
            $charity_vow = charity_transaction::where('title_id',$title['id'])->whereNotIn('charity_id', [3])->whereBetween('payment_date', [$start_date, $end_date])->where('status', 'success')->sum('amount');
            $routine_vow = 0;
            $title->balance = $charity_vow + $routine_vow;
            return $title;
        });

        return $charity_titles;
    }

//end charity module


//setting module
//    public function cities_list()
//    {
//        $cities = city::where('parent', '0')->orderBy('name')->paginate(32);
//        return view('panel.setting.cities_list', compact('cities'));
//    }

    public function gateway_setting()
    {
        $gateways = gateway::get();
        $banks = bank::groupBy('name')->get();

        return view('panel.setting.gateway_setting', compact('gateways', 'banks'));
    }

    public function gateway_add()
    {
        $banks = bank::groupBy('name')->get();
        return view('panel.setting.gateway.gateway_add', compact('banks'));
    }

    public function gateway_edit(Request $request)
    {
        $banks = bank::groupBy('name')->get();
        $info = gateway::find($request['gat_id']);
        return view('panel.setting.gateway.gateway_edit', compact('banks', 'info'));
    }

    public function setting_how_to_send()
    {
        $trans = setting_transportation::all();
        return view('panel.setting.how_to_send', compact('trans'));
    }

    public function setting_how_to_send_add()
    {
        $province = city::where('parent', 0)->get();
        return view('panel.setting.transportation.transportation_add', compact('province'));
    }

    public function setting_how_to_send_edit(Request $request)
    {
        $tran = setting_transportation::find($request['t_id']);
        $province = city::where('parent', 0)->get();

        return view('panel.setting.transportation.transportation_edit', compact('tran', 'province'));
    }
//end setting module

//store module
    public function product_add()
    {
        $items_cats = store_item_category::all();
        $gatewaysOnline = gateway::where('online', 1)->get();
        $gatewaysCard = gateway::where('cart', 1)->get();
        $gatewaysAccount = gateway::where('account', 1)->get();
        return view('panel.store.product.product_add', compact('gatewaysOnline', 'gatewaysCard', 'gatewaysAccount', 'items_cats'));
    }

    public function store_product_edit(Request $request)
    {
        $items_cats = store_item_category::all();
        $gatewaysOnline = gateway::where('online', 1)->get();
        $gatewaysCard = gateway::where('cart', 1)->get();
        $gatewaysAccount = gateway::where('account', 1)->get();
        $product = store_product::with('store_product_gateway', 'store_product_inventory')->find($request['pro_id']);
        return view('panel.store.product.product_edit', compact('gatewaysOnline', 'gatewaysCard', 'gatewaysAccount', 'items_cats', 'product'));
    }

    public function product_list()
    {
        $products = store_product::get();
        return view('panel.store.product_list', compact('products'));
    }

    public function discount_code()
    {
        $codes = store_discount_code::get();
        return view('panel.store.discount_code', compact('codes'));
    }

    public function discount_add_form()
    {
        return view('panel.store.discount.discount_add_form');
    }

    public function discount_code_edit_form(Request $request)
    {
        $dis_info = store_discount_code::find($request['dis_id']);
        return view('panel.store.discount.discount_edit_form', compact('dis_info'));
    }

    public function manage_orders()
    {
        $orders = order::with('people', 'gateway')->get();
        return view('panel.store.manage_orders', compact('orders'));
    }

    public function manage_orders_detail(Request $request)
    {
        $orders = order::with('items', 'address', 'people', 'gateway')->find($request['id']);
        $transInfo = gateway_transaction::where(
            [
                ['module', '=', 'shop'],
                ['module_id', '=', $request['id']],
            ]
        )->get();
        return view('panel.store.manage.show', compact('orders', 'transInfo'));
    }

    public function store_setting()
    {
        return view('panel.store.store_setting');
    }

    public function store_category()
    {

        $product_categories = store_category::get();
        return view('panel.store.store_category', compact('product_categories'));
    }

    public function store_category_add()
    {
        return view('panel.store.category.store_category_add');
    }

    public function store_category_edit_form(Request $request)
    {
        $cat_info = store_category::find($request['cat_id']);
        return view('panel.store.category.store_category_edit', compact('cat_info'));
    }

    public function store_items()
    {
        $items_category = store_item_category::get();
        $items = store_item::get();
        return view('panel.store.store_items', compact('items_category', 'items'));
    }

    public function store_items_add_form()
    {
        $items_category = store_item_category::get();
        return view('panel.store.items.store_items_add', compact('items_category'));
    }

    public function store_items_edit_form(Request $request)
    {
        $info = store_item::find($request['item_id']);
        $items_category = store_item_category::get();
        return view('panel.store.items.store_items_edit', compact('info', 'items_category'));
    }

    public function store_items_category_add_form()
    {
        return view('panel.store.items.store_items_category_add');
    }

    public function store_items_category_edit_form(Request $request)
    {
        $info = store_item_category::find($request['cat_id']);
        return view('panel.store.items.store_items_category_edit', compact('info'));
    }


    public function list_video_galleries()
    {
        $videos = video_gallery::get();
        return view('panel.gallery.video_gallery_list', compact('videos'));
    }

    public function add_video_galleries_modal()
    {
        return view('panel.gallery.ajax.add_video');
    }

    public function gallery_add()
    {
        $categories = gallery_category::with('media')->get();
        return view('panel.gallery.gallery_add', compact('categories'));
    }

    public function gallery_add_modal()
    {
        return view('panel.gallery.ajax.add_category');
    }

    public function gallery_edit_modal($id)
    {
        $info = gallery_category::find($id);
        return view('panel.gallery.ajax.edit_category', compact('info'));
    }

    public function gallery_category_view(Request $request)
    {
        $catInfo = gallery_category::find($request['id']);
        $medias = \App\media::where(
            [
                ['category_id', '=', $request['id']],
                ['module', '=', 'gallery'],
            ])->get();
        return view('panel.gallery.view', compact('medias', 'catInfo'));
    }

//end store module


    public function blog_setting_more_setting(Request $request)
    {

        foreach ($request->all() as $item => $value) {
            if ($item != '_token' && $item != '_method' && isset($value) && $value != '' && $item != "files") {
                $k = '.social_media.' . $item . '.link';
                self::updateConfig('blog_setting', $k, trim($value['link']));
            }
        }
        Artisan::call('config:cache');
        sleep(6);
        session()->flash('type', 'success');
        session()->flash('message', 'تنظمیات ویرایش گردید.');
        return redirect()->back();
    }

    public static function updateConfig($configFile, $configKey, $newValue)
    {
        config([$configFile . $configKey => $newValue]);
        $export = var_export(config($configFile), TRUE);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        $output = "<?php return " . $export . ';';
        file_put_contents(config_path($configFile . '.php'), $output);

    }

    public function updateDate()
    {
        $d = array();
        $i = 1;
        foreach ($d as $item) {
            if ($item[11] == 1) {
                $date = date("Y-m-d H:i:s", $item[12]);
                $lang = "fa";
                if ($item[9] == 2)
                    $lang = "en";

                try {
                    $info = DB::table('blog_etc_posts')->insertGetId([
                        'user_id' => '1',
                        'slug' => str_slug_persian($item[0]),
                        'title' => $item[0],
                        'subtitle' => $item[1],
                        'post_body' => $item[2],
                        'lang' => $lang,
                        'posted_at' => $date,
                        'image_thumbnail' => $item[3],
                        'created_at' => $date,
                    ]);
                } catch (\Exception $e) {
                    $info = DB::table('blog_etc_posts')->insertGetId([
                        'user_id' => '1',
                        'slug' => str_slug_persian($item[0]) . "-" . Str::random('12'),
                        'title' => $item[0],
                        'subtitle' => $item[1],
                        'post_body' => $item[2],
                        'lang' => $lang,
                        'posted_at' => $date,
                        'image_thumbnail' => $item[3],
                        'created_at' => $date,
                    ]);
                }
                $cat = DB::table('blog_etc_post_categories')->insert([
                    'blog_etc_post_id' => $info,
                    'blog_etc_category_id' => $item[7]
                ]);
                echo $i . "<br>";

                $i++;

            }

        }
    }

    public function mobile_app_index()
    {
        $notice = ApplicationSetting::where('key', "main_page_notification")->first();
        $notification = json_decode($notice['value']);

        $links = ApplicationSetting::where('key', 'main_page_links')->get()->map(function ($link) {
            $data = json_decode($link['value']);
            $image = \App\media::find($data->image);

            return [
                'id' => $link->id,
                'title' => $data->title,
                'link' => $data->link,
                'image' => $image['url'],
            ];
        });
        return view('panel.app.index', compact('notification', 'links'));
    }

    public function test()
    {
        Artisan::call('config:cache');

//        $titles = charity_payment_title::get();
//        foreach ($titles as $title){
//            $tp = new charityPaymentPT();
//            $tp->pattern_id =$title->ch_pay_pattern_id;
//            $tp->title_id =$title->id;
//            $tp->save();
//        }
//        dd('d');
    }
}
