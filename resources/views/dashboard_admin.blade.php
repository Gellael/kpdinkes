<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin (Helmi Program)</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4">Buat Akun Baru (Puskesmas / Ambulan)</h3>
                <form action="{{ route('admin.create_user') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama User" class="border p-2 rounded" required>
                    <input type="email" name="email" placeholder="Email" class="border p-2 rounded" required>
                    <input type="password" name="password" placeholder="Password" class="border p-2 rounded" required>
                    <select name="role" class="border p-2 rounded">
                        <option value="puskesmas">Puskesmas (Input BPJS)</option>
                        <option value="ambulan">Supir Ambulan</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Buat User</button>
                </form>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4">Monitoring Data BPJS Gratis</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3">Nama Warga</th>
                            <th class="p-3">NIK</th>
                            <th class="p-3">Diinput Oleh</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bpjsData as $data)
                        <tr class="border-b">
                            <td class="p-3">{{ $data->nama_warga }}</td>
                            <td class="p-3">{{ $data->nik }}</td>
                            <td class="p-3">{{ $data->petugas->name }}</td>
                            <td class="p-3"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">{{ $data->status_verifikasi }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>