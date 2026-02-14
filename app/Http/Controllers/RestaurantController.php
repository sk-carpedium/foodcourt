<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function menu($restaurantSlug)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.restaurant-menu', compact('restaurant'));
    }

    public function checkout($restaurantSlug)
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.checkout', compact('restaurant'));
    }
}