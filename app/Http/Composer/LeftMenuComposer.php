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
                 'url_name'=> 'product_sold',
                'icon' => '<i class="fas fa-atom"></i>',
                // 'permission' => ,
                'url_item_child' => ['product_family_info', 'product_family_create'],
                // 'item_childs' => [
                //     [
                //         'name' => trans('market.manager_products_info'),
                //         'url' =>'',
                //         'icon' => 'fa fa-archive',
                //         // 'permission' => User::canPermissionCompetencyReport(),
                //     ],
                // ],
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
        ];
        $this->leftSideMenu = $item;
        
        return $this->leftSideMenu;

    }
}