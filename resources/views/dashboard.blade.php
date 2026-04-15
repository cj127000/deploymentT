<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Station</h2>
    </x-slot>

    <div class="py-12 text-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div style="padding: 15px; background-color: #16a34a; color: white; border-radius: 5px; margin-bottom: 20px; font-weight: bold;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow border border-gray-200">
                <h3 class="font-bold mb-4 text-xl text-gray-800 uppercase">Rice</h3>
                <form action="{{ route('rice.store') }}" method="POST" class="flex flex-wrap gap-4 mb-6">
                    @csrf
                    <input type="text" name="name" placeholder="Rice Name" class="border p-2 rounded" required>
                    <input type="number" name="price_per_kg" step="0.01" placeholder="Price/kg" class="border p-2 rounded" required>
                    <input type="number" name="stock_qty" placeholder="Stock" class="border p-2 rounded" required>
                    <button type="submit" style="background-color: #2563eb; color: white; padding: 10px 25px; border-radius: 6px; font-weight: bold; border: none; cursor: pointer;">SAVE RICE</button>
                </form>

                <table class="w-full text-left border">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="p-3 border">Rice Name</th>
                            <th class="p-3 border">Price</th>
                            <th class="p-3 border">Stock</th>
                            <th class="p-3 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Rice::all() as $item)
                        <tr>
                            <form action="{{ route('rice.update', $item->id) }}" method="POST">
                                @csrf @method('PUT')
                                <td class="p-2 border"><input type="text" name="name" value="{{ $item->name }}" class="w-full border p-1 rounded"></td>
                                <td class="p-2 border"><input type="number" name="price_per_kg" step="0.01" value="{{ $item->price_per_kg }}" class="w-full border p-1 rounded"></td>
                                <td class="p-2 border"><input type="number" name="stock_qty" value="{{ $item->stock_qty }}" class="w-full border p-1 rounded"></td>
                                <td class="p-2 border flex justify-center gap-2">
                                    <button type="submit" style="background-color: #16a34a; color: white; padding: 5px 10px; border-radius: 4px; font-size: 11px; border: none; cursor: pointer;">UPDATE</button>
                            </form>
                                    <form action="{{ route('rice.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background-color: #dc2626; color: white; padding: 5px 10px; border-radius: 4px; font-size: 11px; border: none; cursor: pointer;">DELETE</button>
                                    </form>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded shadow border border-gray-200">
                <h3 class="font-bold mb-4 text-xl text-gray-800 uppercase">Order</h3>
                <form action="{{ route('orders.store') }}" method="POST" class="flex flex-wrap gap-4 mb-6">
                    @csrf
                    <select name="rice_id" class="border p-2 rounded" style="min-width: 250px;" required>
                        <option value="">Select Rice Item</option>
                        @foreach(\App\Models\Rice::all() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} (₱{{ $item->price_per_kg }}/kg)</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" placeholder="Qty (kg)" class="border p-2 rounded w-40" required>
                    <button type="submit" style="background-color: #f97316; color: white; padding: 10px 25px; border-radius: 6px; font-weight: bold; border: none; cursor: pointer;">CREATE ORDER</button>
                </form>

                <table class="w-full text-left border text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase">
                        <tr>
                            <th class="p-3 border">Rice Name</th>
                            <th class="p-3 border">Quantity</th>
                            <th class="p-3 border">Price</th>
                            <th class="p-3 border">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Order::with('rice')->latest()->get() as $order)
                        <tr>
                            <td class="p-3 border">{{ $order->rice->name ?? 'N/A' }}</td>
                            <td class="p-3 border">{{ $order->quantity }} kg</td>
                            <td class="p-3 border">₱{{ number_format($order->rice->price_per_kg ?? 0, 2) }}</td>
                            <td class="p-3 border font-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded shadow border border-gray-200">
                <h3 class="font-bold mb-4 text-xl text-gray-800 uppercase">Payments</h3>
                <table class="w-full text-left border text-sm">
                    <thead class="bg-gray-100 uppercase text-gray-600">
                        <tr>
                            <th class="p-3 border">Order ID</th>
                            <th class="p-3 border">Date</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Actions</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Payment::latest()->get() as $p)
                        <tr>
                            <td class="p-3 border">#{{ $p->order_id }}</td>
                            <td class="p-3 border">{{ $p->created_at->format('M d, Y') }}</td>
                            <td class="p-3 border">
                                <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; {{ $p->status == 'Paid' ? 'background-color: #dcfce7; color: #166534;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="p-3 border">
                                @if($p->status == 'Unpaid')
                                <form action="{{ route('payments.update', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background-color: #4f46e5; color: white; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: bold; border: none; cursor: pointer;">PAY NOW</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>