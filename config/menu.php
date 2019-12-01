<?php
    return [
        [
            'icon' => '<i class="fa fa-home"></i>',
            'title' => 'Trang chủ',
            'url' => '/admin',
        ],
        [
            'icon' => '<i class="fa fa-shopping-cart"></i>',
            'title' => 'Đơn hàng',
            'url' => '/admin/orders',
            'children' => [
                [
                    'icon' => '',
                    'title' => 'Danh sách đơn hàng',
                    'url' => '/admin/orders',
                ],
                // [
                //     'icon' => '',
                //     'title' => 'Tạo đơn hàng',
                //     'url' => '/admin/draft_orders',
                // ],
                // [
                //     'icon' => '',
                //     'title' => 'Đơn hàng nháp',
                //     'url' => '/admin/checkouts',
                // ],
            ]
        ],
        [
            'icon' => '<i class="fa fa-tags"></i>',
            'title' => 'Sản phẩm',
            'url' => '/admin/products',
            'children' => [
                [
                    'icon' => '',
                    'title' => 'Danh sách sản phẩm',
                    'url' => '/admin/products',
                ],
                // [
                //     'icon' => '',
                //     'title' => 'Bộ lọc sản phẩm',
                //     'url' => '/admin/filters',
                // ],
                // [
                //     'icon' => '',
                //     'title' => 'Nhập hàng',
                //     'url' => '/admin/transfers',
                // ],
                // [
                //     'icon' => '',
                //     'title' => 'Kho hàng',
                //     'url' => '/admin/inventory',
                // ],
                [
                    'icon' => '',
                    'title' => 'Danh mục',
                    'url' => '/admin/products/categories',
                ],
                [
                    'icon' => '',
                    'title' => 'Thương hiệu',
                    'url' => '/admin/products/brand',
                ],
            ]
        ],
        [
            'icon' => '<i class="fa fa-users"></i>',
            'title' => 'Khách hàng',
            'url' => '/admin/customers',
        ],
        // [
        //     'icon' => '<i class="fa fa-file-text"></i>',
        //     'title' => 'Bài viết',
        //     'url' => '/admin/posts',
        //     'children' => [
        //         [
        //             'icon' => '',
        //             'title' => 'Danh sách bài viết',
        //             'url' => '/admin/posts',
        //         ],
        //         [
        //             'icon' => '',
        //             'title' => 'Tạo bài viết',
        //             'url' => '/admin/posts/new',
        //         ],
        //         [
        //             'icon' => '',
        //             'title' => 'Danh mục',
        //             'url' => '/admin/posts/categories',
        //         ],
        //     ]
        // ],
        // [
        //     'icon' => '<i class="fa fa-line-chart"></i>',
        //     'title' => 'Báo cáo & Thống kê',
        //     'url' => '/admin/reports',
        //     'children' => [
        //         [
        //             'icon' => '',
        //             'title' => 'Biểu đồ',
        //             'url' => '/admin/dashboards',
        //         ],
        //         [
        //             'icon' => '',
        //             'title' => 'Báo cáo',
        //             'url' => '/admin/reports',
        //         ],
        //     ]
        // ],
        // [
        //     'icon' => '<i class="fa fa-bullhorn" aria-hidden="true"></i>',
        //     'title' => 'Marketing',
        //     'url' => '/admin/marketing',
        // ],
        // [
        //     'icon' => '<i class="fa fa-percent" aria-hidden="true"></i>',
        //     'title' => 'Mã giảm giá',
        //     'url' => '/admin/discounts',
        // ],
        // [
        //     'icon' => '<i class="fa fa-puzzle-piece" aria-hidden="true"></i>',
        //     'title' => 'App Store',
        //     'url' => '/admin/appstore',
        // ],
        /*[
            'title' => "KÊNH BÁN HÀNG"
        ],*/
        /*[
            'icon' => '<i class="fa fa-cart-plus" aria-hidden="true"></i>',
            'title' => 'Cửa hàng của bạn',
            'url' => '#',
            'children' => [
                [
                    'icon' => '',
                    'title' => 'Giao diện',
                    'url' => '/admin/themes',
                ],

                // [
                //     'icon' => '',
                //     'title' => 'Trang',
                //     'url' => '/admin/pages',
                // ],
                // [
                //     'icon' => '',
                //     'title' => 'Menu',
                //     'url' => '/admin/menu',
                // ],
                [
                    'icon' => '',
                    'title' => 'Tên miền',
                    'url' => 'admin/settings/domain',
                ],
                // [
                //     'icon' => '',
                //     'title' => 'Tùy chỉnh',
                //     'url' => '/admin/preferences',
                // ],

            ]
        ],*/
        /*[
            'title' => "CÀI ĐẶT"
        ],*/
        // [
        //     'icon' => '<i class="fa fa-puzzle-piece"></i>',
        //     'title' => 'Ứng dụng',
        //     'url' => '/admin/app',
        // ],
        // [
        //     'icon' => '<i class="fa fa-gear" aria-hidden="true"></i>',
        //     'title' => 'Cài đặt',
        //     'url' => '/admin/settings',
        // ],
    ];
