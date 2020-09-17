<?php
return [
    'dashboard' => [
        'title' => 'messages.dashboard',
        'link' => 'dashboard',
        'icon' => 'icon-home4',
        'permission' => '',
        'badge' => '',
        'child' => []
    ],
    'blog' => [
        'title' => 'messages.blog',
        'link' => '',
        'icon' => 'icon-blogger',
        'permission' => 'manage_weblog',
        'badge' => 'comments_count',
        'child' => [
            'blog_posts' => [
                'title' => 'messages.blog',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => [
                    'blog_posts_list' => [
                        'title' => 'messages.post_list',
                        'link' => 'blogetc.admin.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'blog_posts_add' => [
                        'title' => 'messages.post_add',
                        'link' => 'blogetc.admin.create_post',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ]
                ]
            ],
            'blog_comments' => [
                'title' => 'messages.blog_comments',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => 'comments_count',
                'child' => [
                    'all_blog_comments' => [
                        'title' => 'messages.all_blog_comments',
                        'link' => 'blogetc.admin.comments.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'pending_blog_comments' => [
                        'title' => 'messages.pending_blog_comments',
                        'link' => 'blogetc.admin.comments.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => 'comments_count',
                        'child' => []
                    ]
                ]
            ],
            'blog_categories' => [
                'title' => 'messages.blog_categories',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => [
                    'all_blog_categories' => [
                        'title' => 'messages.all_blog_categories',
                        'link' => 'blogetc.admin.categories.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
            'blog_Specific_page' => [
                'title' => 'messages.Specific_page',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => [
                    'list' => [
                        'title' => 'messages.specific_categories_list',
                        'link' => 'blogetc.admin.SpecificPages.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'pages' => [
                        'title' => 'messages.pages_list',
                        'link' => 'pages.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
            'blog_images' => [
                'title' => 'messages.blog_images',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => [
                    'blog_images_list' => [
                        'title' => 'messages.all_blog_images',
                        'link' => 'blogetc.admin.images.all',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'add_blog_images' => [
                        'title' => 'messages.add_blog_images',
                        'link' => 'blogetc.admin.images.upload',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
            'blog_setting' => [
                'title' => 'messages.blog_setting',
                'link' => '',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => [
                    'display_statistics' => [
                        'title' => 'messages.display_statistics',
                        'link' => 'display_statistics',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'adv_links' => [
                        'title' => 'messages.adv_links',
                        'link' => 'adv_links',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'faq' => [
                        'title' => 'messages.FAQ',
                        'link' => 'faq.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'menu' => [
                        'title' => 'messages.menu',
                        'link' => 'menu.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'more_blog_setting' => [
                        'title' => 'messages.more_setting',
                        'link' => 'more_blog_setting',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
            'blog_slider' => [
                'title' => 'messages.blog_slider',
                'link' => 'blog_slider',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ]
    ],
    'store' => [
        'title' => 'messages.store',
        'link' => '',
        'icon' => 'icon-cart',
        'permission' => 'manage_store',
        'badge' => 'orders_count',
        'child' => [
            'product_add' => [
                'title' => 'messages.product_add',
                'link' => 'product_add',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'product_list' => [
                'title' => 'messages.product_list',
                'link' => 'product_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'store_category' => [
                'title' => 'messages.store_category',
                'link' => 'store_category',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'store_items' => [
                'title' => 'messages.store_items',
                'link' => 'store_items',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'discount_code' => [
                'title' => 'messages.discount_code',
                'link' => 'discount_code',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'manage_orders' => [
                'title' => 'messages.manage_orders',
                'link' => 'manage_orders',
                'icon' => '',
                'permission' => '',
                'badge' => 'orders_count',
                'child' => []
            ],
        ],
    ],
    'c_store' => [
        'title' => 'تاج گل و استند',
        'link' => '',
        'icon' => 'icon-cart4',
        'permission' => 'manage_store',
        'badge' => 'orders_count',
        'child' => [
            'c_store_orders' => [
                'title' => 'سفارشات',
                'link' => 'c_store.orders_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'c_store_list' => [
                'title' => 'محصولات',
                'link' => 'c_store.product_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'c_store_setting' => [
                'title' => 'تنظیمات فروش تاج گل',
                'link' => 'c_store.setting_show',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'gallery' => [
        'title' => 'messages.gallery',
        'link' => '',
        'icon' => 'icon-gallery',
        'permission' => 'manage_gallery',
        'badge' => '',
        'child' => [
            'gallery_add' => [
                'title' => 'messages.photos',
                'link' => 'gallery_add',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'list_video_galleries' => [
                'title' => 'messages.videos',
                'link' => 'list_video_galleries',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'caravans' => [
        'title' => 'messages.caravans',
        'link' => '',
        'icon' => 'icon-train2',
        'permission' => 'manage_carevan',
        'badge' => '',
        'child' => [
            'caravans_dashboard' => [
                'title' => 'messages.dashboard',
                'link' => 'gallery_add',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'caravans_list' => [
                'title' => 'messages.caravans_list',
                'link' => 'caravans_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'hosts_list' => [
                'title' => 'messages.hosts_list',
                'link' => 'hosts_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'building' => [
        'title' => 'messages.building_projects',
        'link' => '',
        'icon' => 'icon-quill4',
        'permission' => 'manage_rezvan',
        'badge' => '',
        'child' => [
            'building_dashboard' => [
                'title' => 'messages.building_dashboard',
                'link' => 'building_dashboard',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'building_types' => [
                'title' => 'messages.building_types',
                'link' => 'building_types',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'building_archive' => [
                'title' => 'messages.building_archive',
                'link' => 'building_archive',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'charity' => [
        'title' => 'messages.charity_titel',
        'link' => '',
        'icon' => 'icon-umbrella',
        'permission' => 'manage_charity',
        'badge' => 's_form_count',
        'child' => [
            'charity_period' => [
                'title' => 'messages.periodic_payment',
                'link' => '',
                'icon' => '',
                'permission' => 'charity_periodic',
                'badge' => '',
                'child' => [
                    'charity_period_list' => [
                        'title' => 'messages.period_payment_list',
                        'link' => 'charity_period_list',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'charity_period_status' => [
                        'title' => 'messages.payment_status',
                        'link' => 'charity_period_status',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
            'charity_list' => [
                'title' => 'messages.other_payments',
                'link' => 'charity_payment_list',
                'icon' => '',
                'permission' => 'charity_payment_list',
                'badge' => '',
                'child' => []
            ],
            'charity_champion_payments_list' => [
                'title' => 'messages.champions_payments',
                'link' => 'charity_champion_payments',
                'icon' => '',
                'permission' => 'charity_champion_payments',
                'badge' => '',
                'child' => []
            ],
            'support_form' => [
                'title' => 'messages.support_forms_list',
                'link' => 'sform_reports',
                'icon' => '',
                'permission' => 'charity_sform_reports',
                'badge' => 's_form_count',
                'child' => []
            ],
            'charity_report' => [
                'title' => 'messages.reports',
                'link' => 'charity_reports',
                'icon' => '',
                'permission' => 'charity_reports',
                'badge' => '',
                'child' => []
            ],
            'charity_setting' => [
                'title' => 'messages.charity_setting',
                'link' => '',
                'icon' => '',
                'permission' => 'charity_setting',
                'badge' => '',
                'child' => [
                    'charity_payment_titles' => [
                        'title' => 'messages.payment_titles',
                        'link' => 'charity_payment_title',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                    'charity_support_title' => [
                        'title' => 'messages.charity_support_title',
                        'link' => 'sForm.index',
                        'icon' => '',
                        'permission' => '',
                        'badge' => '',
                        'child' => []
                    ],
                ]
            ],
        ],
    ],
    'user_manager' => [
        'title' => 'messages.users_management',
        'link' => '',
        'icon' => 'icon-users4',
        'permission' => 'manage_users',
        'badge' => '',
        'child' => [
            'users_list' => [
                'title' => 'messages.users_list',
                'link' => 'users_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'permissions_list' => [
                'title' => 'messages.permissions_list',
                'link' => 'permissions_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'roles_list' => [
                'title' => 'messages.roles_list',
                'link' => 'roles_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'teams_list' => [
                'title' => 'messages.teams_list',
                'link' => 'teams_list',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'setting' => [
        'title' => 'messages.setting',
        'link' => '',
        'icon' => 'icon-gear',
        'permission' => 'manage_setting',
        'badge' => 'contact_msgs',
        'child' => [
            'contact' => [
                'title' => 'messages.contact_to_we',
                'link' => 'contact.index',
                'icon' => '',
                'permission' => '',
                'badge' => 'contact_msgs',
                'child' => []
            ],
            'notification_template' => [
                'title' => 'messages.notification_template',
                'link' => 'notification_template.index',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'translations' => [
                'title' => 'messages.translation_maganger',
                'link' => '',
                'url' => '/panel/translations',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'cities_list' => [
                'title' => 'messages.cities',
                'link' => 'cities.index',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'gateway_setting' => [
                'title' => 'messages.gateway_pay',
                'link' => 'gateway_setting',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
            'setting_how_to_send' => [
                'title' => 'messages.how_to_send',
                'link' => 'setting_how_to_send',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
    'mobile-app' => [
        'title' => 'messages.mobile-app',
        'link' => '',
        'icon' => 'icon-mobile',
        'permission' => '',
        'badge' => '',
        'child' => [
            'mobile-app-manage' => [
                'title' => 'messages.mobile-app-manage',
                'link' => 'mobile_app_index',
                'icon' => '',
                'permission' => '',
                'badge' => '',
                'child' => []
            ],
        ],
    ],
];