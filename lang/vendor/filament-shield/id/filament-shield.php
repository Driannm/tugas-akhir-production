<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */
    'column.name' => 'Nama',
    'column.guard_name' => 'Tipe Otentikasi',
    'column.roles' => 'Peran',
    'column.permissions' => 'Hak Akses',
    'column.updated_at' => 'Terakhir Diubah',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nama',
    'field.guard_name' => 'Tipe Otentikasi',
    'field.permissions' => 'Hak Akses',
    'field.select_all.name' => 'Pilih Semua',
    'field.select_all.message' => 'Aktifin semua hak akses yang <span class="text-primary font-medium">tersedia</span> buat peran ini.',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Manajemen Pengguna',
    'nav.role.label' => 'Hak Akses',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Hak Akses',
    'resource.label.roles' => 'Hak Akses',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Item Utama',
    'resources' => 'Sumber Data',
    'widgets' => 'Widget',
    'pages' => 'Halaman',
    'custom' => 'Izin Tambahan',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Kamu tidak punya izin akses',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Lihat',
        'view_any' => 'Lihat Semua',
        'create' => 'Buat',
        'update' => 'Ubah',
        'delete' => 'Hapus',
        'delete_any' => 'Hapus Semua',
        'force_delete' => 'Hapus Permanen',
        'force_delete_any' => 'Hapus Semua Permanen',
        'restore' => 'Pulihkan',
        'restore_any' => 'Pulihkan Semua',
        'replicate' => 'Duplikasi',
        'reorder' => 'Atur Ulang',
    ],
];
