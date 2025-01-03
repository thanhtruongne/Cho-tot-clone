<?php

namespace App\Http\Composer;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LeftMenuComposer
{
    protected $leftSideMenu;
    /**
     * Create a new profile composer.
     */
    public function __construct() {
        $this->getLeftMenu();
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('leftMenuBackend', $this->leftSideMenu);
    }

    public function getLeftMenu(){

        $item = Cache::rememberForever('menu_composer',function(){
            return [

                'manager_products_rents_house' => [
                    'id' => 1,
                    'name' => trans('market.manager_products_rent'),
                    'url' => '',
                     'is_open' => '',
                     'url_name'=> 'product_rent',
                    'icon' => '<i class="fas fa-home"></i>',
                    // 'permission' => ,
                    'url_item_child' => ['info_products_rent', 'product_family_create'],
                    'item_childs' => [
                        // [
                        //     'name' => trans('market.info_products_rent'),
                        //     'url' =>'',
                        //     'url_name'=> 'product_rent',
                        //     'icon' => '<i class="fas fa-house-user"></i>',
                        //     // 'permission' => User::canPermissionCompetencyReport(),
                        // ],
                        [
                            'name' => trans('Quản lí đăng tin'),
                            'url_name' =>'permissions/role',
                             'url' => route('manage-postings'),
                            'icon' => '<i class="fas fa-suitcase"></i>',
                            // 'permission' => User::canPermissionCompetencyReport(),
                        ],
                        // [
                        //     'name' => trans('Quản lí người dùng'),
                        //     'url_name' =>'user',
                        //      'url' => route('manage-users'),
                        //     'icon' => '<i class="fas fa-suitcase"></i>',
                        //     // 'permission' => User::canPermissionCompetencyReport(),
                        // ],
                        [
                            'name' => trans('Thống kê đăng tin'),
                            'url_name' =>'permissions/role',
                             'url' => route('thongke'),
                            'icon' => '<i class="fas fa-suitcase"></i>',
                            // 'permission' => User::canPermissionCompetencyReport(),
                        ],
                        [
                            'name' => trans('Quản lí gói tin'),
                            'url_name' =>'permissions/role',
                             'url' => route('type-posting'),
                            'icon' => '<i class="fas fa-suitcase"></i>',
                            // 'permission' => User::canPermissionCompetencyReport(),
                        ],
                    ],
                ],
                // 'manager_post_sold' => [
                //     'id' => 2,
                //     'name' => trans('market.manager_post_sold'),
                //     'url' => '',
                //      'is_open' => '',
                //      'url_name'=> 'products',
                //     'icon' => '<i class="fas fa-atom"></i>',
                //     // 'permission' => ,
                //     'url_item_child' => ['product_family_info', 'product_family_create'],
                //     'item_childs' => [
                //         [
                //             'name' => trans('market.product_electronics_manager'),
                //             'url' =>'',
                //             'url_name'=> 'products',
                //             'icon' => '<i class="fas fa-atom"></i>',
                //             'item_childs' => [
                //                 [
                //                     'name' => trans('market.product_electronics'),
                //                     'url' => route('products.electronic'),
                //                     'url_name'=> 'electronic',
                //                     'icon' => '<i class="fas fa-atom"></i>',
                //                 ],
                //
                //             ]
                //         ],
                //     ],
                // ],
                'manager_users' => [
                    'id' => 3,
                    'name' => trans('market.manager_users'),
                    'url' => route('manage-users'),
                     'is_open' => '',
                     'url_name'=> 'manager-user',
                    'icon' => '<i class="fas fa-users"></i>',
                ],
                'categories' => [
                    'id' => 4,
                    'name' => trans('market.categories'),
                    'url' => route('categories'),
                    'is_open' => '',
                    'url_name' => 'categories',
                    'icon' => '<i class="fas fa-suitcase"></i>',
                ],
                // ],


            ];
        });
        $this->leftSideMenu = $item;

        return $this->leftSideMenu;

    }
}
