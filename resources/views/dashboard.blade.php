<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight">Master Station</h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-600 text-white rounded-lg shadow-md border-l-4 border-emerald-800 animate-fade-in font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100">
                <h3 class="font-black mb-6 text-sm tracking-widest text-emerald-700 uppercase flex items-center gap-2">
                    <span class="p-1 bg-emerald-100 rounded text-emerald-600">📦</span> Rice Inventory
                </h3>
                
                <form action="{{ route('rice.store') }}" method="POST" class="flex flex-wrap gap-4 mb-8">
                    @csrf
                    <input type="text" name="name" placeholder="Rice Name" class="border-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2 rounded-lg flex-1" required>
                    <input type="number" name="price_per_kg" step="0.01" placeholder="Price/kg" class="border-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2 rounded-lg w-32" required>
                    <input type="number" name="stock_qty" placeholder="Stock" class="border-slate-200 focus:ring-emerald-500 focus:border-emerald-500 p-2 rounded-lg w-32" required>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-bold transition-all shadow-sm">SAVE RICE</button>
                </form>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-tighter">
                            <th class="p-4 border-b border-slate-100">Rice Name</th>
                            <th class="p-4 border-b border-slate-100">Price</th>
                            <th class="p-4 border-b border-slate-100">Stock</th>
                            <th class="p-4 border-b border-slate-100 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @foreach(\App\Models\Rice::all() as $item)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <form action="{{ route('rice.update', $item->id) }}" method="POST">
                                @csrf @method('PUT')
                                <td class="p-3 border-b border-slate-50"><input type="text" name="name" value="{{ $item->name }}" class="w-full border-none bg-transparent focus:ring-1 focus:ring-emerald-400 rounded p-1"></td>
                                <td class="p-3 border-b border-slate-50"><input type="number" name="price_per_kg" step="0.01" value="{{ $item->price_per_kg }}" class="w-full border-none bg-transparent focus:ring-1 focus:ring-emerald-400 rounded p-1"></td>
                                <td class="p-3 border-b border-slate-50"><input type="number" name="stock_qty" value="{{ $item->stock_qty }}" class="w-full border-none bg-transparent focus:ring-1 focus:ring-emerald-400 rounded p-1"></td>
                                <td class="p-3 border-b border-slate-50 flex justify-center gap-2">
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs p-2 uppercase">Update</button>
                            </form>
                                    <form action="{{ route('rice.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs p-2 uppercase">Delete</button>
                                    </form>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-800 p-6 rounded-xl shadow-xl border border-slate-700">
                <h3 class="font-black mb-6 text-sm tracking-widest text-slate-300 uppercase">🛒 Create Order</h3>
                <form action="{{ route('orders.store') }}" method="POST" class="flex flex-wrap gap-4 mb-8">
                    @csrf
                    <select name="rice_id" class="bg-slate-700 border-slate-600 text-white p-2 rounded-lg focus:ring-emerald-500 flex-1" required>
                        <option value="">Select Rice Item</option>
                        @foreach(\App\Models\Rice::all() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} (₱{{ $item->price_per_kg }}/kg)</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" placeholder="Qty (kg)" class="bg-slate-700 border-slate-600 text-white p-2 rounded-lg w-40" required>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-900 px-6 py-2 rounded-lg font-black transition-all">CREATE ORDER</button>
                </form>

                <table class="w-full text-left text-slate-300 text-sm">
                    <thead class="text-slate-500 uppercase text-xs border-b border-slate-700">
                        <tr>
                            <th class="p-4">Rice Name</th>
                            <th class="p-4 text-center">Quantity</th>
                            <th class="p-4">Price</th>
                            <th class="p-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Order::with('rice')->latest()->get() as $order)
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30">
                            <td class="p-4 font-medium">{{ $order->rice->name ?? 'N/A' }}</td>
                            <td class="p-4 text-center">{{ $order->quantity }} kg</td>
                            <td class="p-4 text-slate-400">₱{{ number_format($order->rice->price_per_kg ?? 0, 2) }}</td>
                            <td class="p-4 text-right font-bold text-emerald-400">₱{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <h3 class="font-black mb-6 text-sm tracking-widest text-indigo-700 uppercase">💳 Payment Status</h3>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="p-4 border-b">Order ID</th>
                            <th class="p-4 border-b">Date</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Payment::latest()->get() as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 border-b font-mono text-slate-500">#{{ $p->order_id }}</td>
                            <td class="p-4 border-b">{{ $p->created_at->format('M d, Y') }}</td>
                            <td class="p-4 border-b">
                                @if($p->status == 'Paid')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700">Paid</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-700">Unpaid</span>
                                @endif
                            </td>
                            <td class="p-4 border-b text-right">
                                @if($p->status == 'Unpaid')
                                <form action="{{ route('payments.update', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1 rounded text-[10px] font-bold uppercase shadow-sm">PAY NOW</button>
                                </form>
                                @else
                                    <span class="text-slate-300 italic text-xs">Settled</span>
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
