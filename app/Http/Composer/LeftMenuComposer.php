<?php
 
namespace App\Http\Composer;

use App\Models\User;
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
        $item = [
            
            'Thống kê' => [
                'id' => '1',
                'name' => trans('market.summary'),
                'url' => route('dashboard'),
                'url_name'=> 'dashboard',
                'is_open' => 1,
                'icon' => '<i class="fas fa-tachometer-alt"></i>',
                // 'permission' => User::isRoleManager(),
                'url_name' => 'dashboard',
                'url_child' => [],
            ],
            'manager_products_rents_house' => [
                'id' => 2,
                'name' => trans('market.manager_products_rent'),
                'url' => '', 
                 'is_open' => '',
                 'url_name'=> 'product_rent',
                'icon' => '<i class="fas fa-home"></i>',
                // 'permission' => ,
                'url_item_child' => ['info_products_rent', 'product_family_create'],
                'item_childs' => [
                    [
                        'name' => trans('market.info_products_rent'),
                        'url' =>'',
                        'url_name'=> 'product_rent',
                        'icon' => '<i class="fas fa-house-user"></i>',
                        // 'permission' => User::canPermissionCompetencyReport(),
                    ],
                ],
            ],
            'manager_post_sold' => [
                'id' => 4,
                'name' => trans('market.manager_post_sold'),
                'url' => '',
                 'is_open' => '',
                 'url_name'=> 'products',
                'icon' => '<i class="fas fa-atom"></i>',
                // 'permission' => ,
                'url_item_child' => ['product_family_info', 'product_family_create'],
                'item_childs' => [
                    [
                        'name' => trans('market.product_electronics_manager'),
                        'url' =>'',
                        'url_name'=> 'products',
                        'icon' => '<i class="fas fa-atom"></i>',
                        'item_childs' => [
                            [
                                'name' => trans('market.product_electronics'),
                                'url' => route('products.electronic'),
                                'url_name'=> 'electronic',
                                'icon' => '<i class="fas fa-atom"></i>',
                            ],
                            [
                                'name' => trans('market.product_electronics_category'),
                                'url' => route('products.electronic'),
                                'url_name'=> 'electronic/categories',
                                'icon' => '<i class="fas fa-atom"></i>',
                            ]
                        ]
                    ],
                ],
            ],
            'manager_users' => [
                'id' => 3,
                'name' => trans('market.manager_provider'),
                'url' => '',
                 'is_open' => '',
                 'url_name'=> 'manager-user',
                'icon' => '<i class="fas fa-users"></i>',
                // 'permission' => ,
                'url_item_child' => ['product_family_info', 'product_family_create'],
                // 'item_childs' => [
                //     [
                //         'name' => trans('market.manger_provider_info'),
                //         'url' =>'',
                //         'icon' => 'fa fa-archive',
                //         // 'permission' => User::canPermissionCompetencyReport(),
                //     ],
                // ],
            ],
            'categories' => [
                'id' => 4,
                'name' => trans('market.categories'),
                'url' => route('categories'),
                'is_open' => '',
                'url_name' => 'categories',
                'icon' => '<i class="fas fa-suitcase"></i>',
                // 'permission' => ,
                'url_item_child' => ['product_family_info', 'product_family_create'],
                // 'item_childs' => [
                //     [
                //         'name' => trans('market.manager_sale_man_info'),
                //         'url' =>'',
                //         'icon' => 'fa fa-archive',
                //         // 'permission' => User::canPermissionCompetencyReport(),
                //     ],
                // ],
            ],
            'categories' => [
                'id' => 4,
                'name' => trans('market.permission_role_manager'),
                'url' => route('categories'),
                'is_open' => '',
                'url_name' => 'permissions',
                'icon' => '<i class="fas fa-suitcase"></i>',
                // 'permission' => ,
                'url_item_child' => ['role', 'permissions'],
                'item_childs' => [
                    [
                        'name' => trans('market.permission_manager'),
                        'url_name' =>'permissions',
                         'url' => route('permission.index'),
                      'icon' => '<i class="fas fa-suitcase"></i>',
                        // 'permission' => User::canPermissionCompetencyReport(),
                    ],
                    [
                        'name' => trans('market.role_manager'),
                        'url_name' =>'permissions/role',
                         'url' => route('categories'),
                        'icon' => '<i class="fas fa-suitcase"></i>',
                        // 'permission' => User::canPermissionCompetencyReport(),
                    ],
                ],
            ],
        ];
        $this->leftSideMenu = $item;
        
        return $this->leftSideMenu;

    }
}