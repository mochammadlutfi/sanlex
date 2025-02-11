<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;

class MenuHelper
{
    public static function get(){

        $menuData = Collect([]);

        $menuData->push([
            "icon" => "fluent:home-24-regular",
            "name" => "Dashboard",
            "to" => "/dashboard",
        ]);

        $menuData->push([
            "name" => "Master Produk",
            "icon" => 'fluent:box-multiple-24-regular',
            "subActivePaths" => '/product',
            "sub" => [
                [
                    "name" => "Produk",
                    "to" => '/product',
                    "active" => '/product'
                ],
                [
                    "name" => "Kategori",
                    "to" => '/product/category',
                    "active" => '/product/category'
                ],
                [
                    "name" => "Merk",
                    "to" => '/product/brand',
                    "active" => '/product/brand'
                ],
                [
                    "name" => "Warna",
                    "to" => '/product/color',
                    "active" => '/product/color'
                ],
                [
                    "name" => "Kemasan",
                    "to" => '/product/packaging',
                    "active" => '/product/packaging'
                ],
            ]
        ]);

        $menuData->push([
            "name" => "Artikel",
            "icon" => 'fluent:news-24-regular',
            "subActivePaths" => '/post',
            "sub" => [
                [
                    "name" => "Artikel",
                    "to" => '/post',
                    "active" => '/post'
                ],
                [
                    "name" => "Kategori",
                    "to" => '/product/category',
                    "active" => '/product/category'
                ],
            ]
        ]);

        $menuData->push([
            "icon" => "fluent:building-48-regular",
            "name" => "Cabang",
            "to" => "/branch",
        ]);

        $menuData->push([
            "icon" => "fluent:people-48-regular",
            "name" => "Customer",
            "to" => "/customer",
        ]);


        $menuData->push([
            "icon" => "fluent:certificate-24-regular",
            "name" => "Sertifikasi",
            "to" => "/certification",
        ]);

        $menuData->push([
            "name" => "Project",
            "icon" => 'fluent:briefcase-48-regular',
            "subActivePaths" => '/project',
            "sub" => [
                [
                    "name" => "Portofolio",
                    "to" => '/project',
                    "active" => '/project'
                ],
                [
                    "name" => "Kontak",
                    "to" => '/project/contact',
                    "active" => '/project/contact'
                ],
            ]
        ]);

        return $menuData->all();
    }

    public static function permission()
    {
        $data = auth()->guard('admin')->user()->getAllPermissions()->toArray();
        $permission = array();
        foreach ($data as $element) {
            $permission[$element['module']][] = $element['name'];
        }

        return $permission;
    }
}
