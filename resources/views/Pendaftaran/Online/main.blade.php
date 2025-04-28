<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien</title>
    <!-- Link ke file CSS yang sudah di-compile (Tailwind CSS) -->
    @vite('resources/css/app.css')

    <style>
        body {
            background: url("{{ asset('img/halaman kkpm.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="max-w-4xl mx-auto p-6 bg-transparent rounded-lg shadow-md mt-10">
        <h2 class="text-2xl font-bold text-center mb-6">Pendaftaran Pasien</h2>

        <!-- Menampilkan pesan sukses jika ada -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulir pendaftaran pasien -->
        <form action="" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('dob')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select id="gender" name="gender"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="address" name="address" rows="4"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700">
                    Daftar Pasien
                </button>
            </div>
        </form>
    </div>

    <!-- Link ke file JS (untuk interaksi lainnya jika diperlukan) -->
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
