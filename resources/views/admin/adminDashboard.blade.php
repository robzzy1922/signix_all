@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.dokumen.index', ['status' => 'diajukan']) }}" class="bg-[#fd7e14] text-white rounded-xl p-4 sm:p-6 hover:bg-[#e76b0a] transition-all duration-300 transform hover:scale-100 animate-slideInFromLeft">
            <div class="flex flex-col items-center sm:flex-row">
                <div class="p-3 mb-4 rounded-full bg-white/20 sm:mb-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-center sm:text-left sm:ml-4">
                    <h2 class="text-2xl font-bold sm:text-3xl">{{ $diajukanDocuments }}</h2>
                    <p class="text-white/80">Surat yang diajukan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.dokumen.index', ['status' => 'disahkan']) }}" class="bg-[#28a745] text-white rounded-xl p-6 hover:bg-[#218838] transition-all duration-300 transform hover:scale-100 animate-slideInFromTop">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold">{{ $disahkanDocuments }}</h2>
                    <p class="text-white/80">Surat sudah disahkan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.dokumen.index', ['status' => 'direvisi']) }}" class="bg-[#4e73df] text-white rounded-xl p-6 hover:bg-[#4262c5] transition-all duration-300 transform hover:scale-100 animate-slideInFromRight">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-3xl font-bold">{{ $direvisiDocuments }}</h2>
                    <p class="text-white/80">Surat perlu direvisi</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 animate-slideInFromBottom">
        <!-- User Data -->
        <div class="overflow-hidden bg-white rounded-xl shadow-lg">
            <div class="flex flex-col justify-between items-center p-6 border-b border-gray-200 sm:flex-row">
                <h2 class="mb-4 text-xl font-bold text-gray-800 sm:mb-0">Pengguna Terbaru</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.ormawa.index') }}" class="px-4 py-2 text-sm font-medium text-blue-600 rounded-lg transition-colors duration-200 hover:text-blue-700 hover:bg-blue-50">
                        Ormawa
                    </a>
                    <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 text-sm font-medium text-blue-600 rounded-lg transition-colors duration-200 hover:text-blue-700 hover:bg-blue-50">
                        Dosen
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-4 py-3 font-semibold tracking-wider text-gray-600 sm:px-6">Nama</th>
                            <th class="hidden px-6 py-3 font-semibold tracking-wider text-gray-600 sm:table-cell">Email</th>
                            <th class="px-4 py-3 font-semibold tracking-wider text-gray-600 sm:px-6">Role</th>
                            <th class="hidden px-6 py-3 font-semibold tracking-wider text-gray-600 sm:table-cell">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentUsers as $user)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex justify-center items-center w-8 h-8 bg-gray-200 rounded-full">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ substr($user['name'], 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $user['name'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $user['email'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $user['role'] === 'Dosen' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $user['role'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $user['created_at']->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="mb-2 w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-gray-600">Tidak ada data user terbaru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Aktivitas Dokumen Terbaru</h2>
                <a href="{{ route('admin.dokumen.index') }}" class="px-4 py-2 text-sm text-blue-500 hover:text-blue-600 hover:underline">
                    Lihat Semua
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-center">
                            <div class="flex justify-center items-center w-8 h-8 rounded-full
                                @if($activity->status_dokumen === 'disahkan')
                                    text-green-500 bg-green-100
                                @elseif($activity->status_dokumen === 'direvisi')
                                    text-blue-500 bg-blue-100
                                @else
                                    text-yellow-500 bg-yellow-100
                                @endif">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($activity->status_dokumen === 'disahkan')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @elseif($activity->status_dokumen === 'direvisi')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    @endif
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity->ormawa->namaOrmawa }} - {{ $activity->perihal }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Status: <span class="font-medium">{{ ucfirst($activity->status_dokumen) }}</span>
                                    @if($activity->status_dokumen === 'disahkan')
                                        oleh {{ $activity->dosen->nama_dosen }}
                                    @elseif($activity->status_dokumen === 'diajukan')
                                        kepada {{ $activity->dosen->nama_dosen }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">
                            Tidak ada aktivitas dokumen terbaru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
