@extends('layouts.admin.app')

@section('title', 'Ormawa')

@section('content')
<div class="container overflow-hidden px-4 py-8 mx-auto max-w-full sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.adminDashboard') }}" class="inline-flex items-center text-gray-700 hover:text-blue-600">
                    <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 font-medium text-gray-500 md:ml-2">Ormawa</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6 sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Ormawa</h1>

        <!-- Alert Success with Countdown -->
        @if(session('success'))
        <div id="alert-success" class="flex fixed top-4 right-4 items-center p-4 text-green-700 bg-green-100 rounded-lg border border-green-400" role="alert">
            <div class="flex items-center">
                <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>

            <!-- Countdown Timer -->
            <div class="pl-4 ml-4 border-l border-green-400">
                <span id="countdown" class="font-mono text-sm">5</span>
            </div>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-green-500 transition-all duration-1000" id="progress-bar" style="width: 100%"></div>
        </div>
        @endif

        <a href="{{ route('admin.ormawa.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Ormawa
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <div class="min-w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Ormawa</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">NIM</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No. HP</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ormawas as $index => $ormawa)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ormawa->namaMahasiswa }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ormawa->namaOrmawa }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ormawa->nim }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ormawa->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ormawa->noHp }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('admin.ormawa.edit', $ormawa->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">
                                        <svg class="mr-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.ormawa.destroy', $ormawa->id) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="showDeleteAlert(this, '{{ $ormawa->namaMahasiswa }}')"
                                                class="inline-flex items-center px-3 py-1.5 text-red-700 bg-red-100 rounded-md hover:bg-red-200">
                                            <svg class="mr-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data Ormawa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($ormawas->hasPages())
        <div class="px-6 py-4 bg-gray-50">
            {{ $ormawas->links() }}
        </div>
    @endif
</div>

<!-- Alert Konfirmasi Hapus -->
<div id="deleteAlert" class="hidden fixed inset-0 z-50">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <!-- Alert Container -->
    <div class="overflow-y-auto fixed inset-0 z-10">
        <div class="flex justify-center items-end p-4 min-h-full text-center sm:items-center sm:p-0">
            <div class="overflow-hidden relative text-left bg-white rounded-lg shadow-xl opacity-0 transition-all duration-300 ease-out transform translate-y-4 sm:my-8 sm:w-full sm:max-w-lg sm:translate-y-0 sm:scale-95"
                 id="alertContent">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Warning Icon -->
                        <div class="flex flex-shrink-0 justify-center items-center mx-auto w-12 h-12 bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <!-- Content -->
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus data <span id="deleteName" class="font-medium text-gray-900"></span>?
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="px-4 py-3 bg-gray-50 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="hideDeleteAlert()"
                            class="inline-flex justify-center px-3 py-2 w-full text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:mr-2">
                        Batal
                    </button>
                    <div class="w-4 sm:w-4"></div> <!-- Spacer element to provide space between buttons -->
                    <button type="button" id="confirmDelete"
                            class="inline-flex justify-center px-3 py-2 w-full text-sm font-semibold text-white bg-red-600 rounded-md shadow-sm hover:bg-red-500 sm:ml-2 sm:w-auto">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include SweetAlert2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Alert countdown and auto-hide
if (document.getElementById('alert-success')) {
    let timeLeft = 5;
    const countdownEl = document.getElementById('countdown');
    const progressBar = document.getElementById('progress-bar');
    const alertEl = document.getElementById('alert-success');

    const countdown = setInterval(() => {
        timeLeft--;
        countdownEl.textContent = timeLeft;
        progressBar.style.width = (timeLeft * 20) + '%';

        if (timeLeft <= 0) {
            clearInterval(countdown);
            alertEl.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                alertEl.remove();
            }, 300);
        }
    }, 1000);

    // Add transition classes
    alertEl.classList.add('transition-all', 'duration-300', 'transform');
}

let deleteForm;

function showDeleteAlert(button, name) {
    const alert = document.getElementById('deleteAlert');
    const alertContent = document.getElementById('alertContent');
    const nameSpan = document.getElementById('deleteName');
    deleteForm = button.closest('form');

    // Set nama yang akan dihapus
    nameSpan.textContent = name;

    // Tampilkan alert
    alert.classList.remove('hidden');

    // Trigger animation
    setTimeout(() => {
        alertContent.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        alertContent.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);

    // Setup confirm button
    document.getElementById('confirmDelete').onclick = function() {
        if (deleteForm) {
            deleteForm.submit();
        }
    };
}

function hideDeleteAlert() {
    const alert = document.getElementById('deleteAlert');
    const alertContent = document.getElementById('alertContent');

    // Trigger animation
    alertContent.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    alertContent.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

    // Hide alert after animation
    setTimeout(() => {
        alert.classList.add('hidden');
    }, 300);
}

// Close when clicking outside
document.getElementById('deleteAlert').addEventListener('click', function(event) {
    if (event.target === this) {
        hideDeleteAlert();
    }
});
</script>
@endpush
