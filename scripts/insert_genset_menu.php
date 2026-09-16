<?php

use App\Models\Menu;

$menu = Menu::where('name', 'Genset')->first();

if (!$menu) {
    echo "Menu Genset tidak ditemukan!\n";
    exit;
}

$menu->update(['route' => null]);
echo "Berhasil! Menu Genset (ID: {$menu->id}) route diset ke null.\n";
