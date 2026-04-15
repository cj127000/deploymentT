<?php

namespace App\Http\Controllers;

use App\Models\Rice;
use Illuminate\Http\Request;

class RiceController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'price_per_kg' => 'required|numeric',
            'stock_qty' => 'required|integer',
        ]);

        Rice::create($data);
        return back()->with('success', 'Rice variety added!');
    }

    public function update(Request $request, Rice $rice) {
        $data = $request->validate([
            'name' => 'required',
            'price_per_kg' => 'required|numeric',
            'stock_qty' => 'required|integer',
        ]);

        $rice->update($data);
        return back()->with('success', 'Rice updated successfully!');
    }

    public function destroy(Rice $rice) {
        $rice->delete();
        return back()->with('success', 'Rice variety deleted!');
    }
}