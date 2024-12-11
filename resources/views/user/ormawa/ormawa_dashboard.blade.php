@extends('layouts.ormawa')
@section('title', 'Dashboard Ormawa')
@section('content')
<div class="container flex-grow px-4 mx-auto mt-8 max-w-5xl">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Dokumen Diajukan -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'diajukan']) }}" class="block">
            <div class="p-6 bg-yellow-400 rounded-xl shadow-lg hover:shadow-xl hover:bg-yellow-500  transition-color duration-300">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Dokumen Diajukan</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countDiajukan }}</span>
                    </div>
                </div>
            </div>
        </a>
        <!-- Dokumen Tertanda -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'disahkan']) }}" class="block">
            <div class="p-6 bg-green-400 rounded-xl shadow-lg hover:shadow-xl hover:bg-green-500  transition-color duration-300">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Dokumen Tertanda</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countDisahkan }}</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Perlu Direvisi -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'butuh_revisi']) }}" class="block">
            <div class="p-6 bg-red-400 rounded-xl shadow-lg hover:shadow-xl hover:bg-red-500  transition-color duration-300">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Perlu Direvisi</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countButuhRevisi }}</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Sudah Direvisi -->
        <a href="{{ route('ormawa.riwayat', ['status' => 'direvisi']) }}" class="block">
            <div class="p-6 bg-blue-400 rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-500  transition-color duration-300">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <h2 class="text-xl font-bold text-white">Sudah Direvisi</h2>
                        </div>
                        <span class="text-4xl font-bold text-white">{{ $countRevisi }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="mt-8">
      <div class="flex flex-col justify-between items-center mb-4 space-y-2 md:flex-row md:space-y-0">
        <div class="relative w-full md:w-64">
          <form method="GET" action="{{ route('ormawa.dashboard') }}" class="flex">
            <div class="relative flex-grow">
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Surat" class="py-2 pr-4 pl-10 w-full rounded-l-lg border">
              <svg class="absolute top-3 left-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-r-lg hover:bg-blue-600">
              Cari
            </button>
          </form>
        </div>
        <div>
          <form method="GET" action="{{ route('ormawa.dashboard') }}">
              <div>
                  <select name="status" class="px-4 py-2 rounded-lg border" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                      <option value="disahkan" {{ request('status') == 'disahkan' ? 'selected' : '' }}>Disahkan</option>
                      <option value="butuh_revisi" {{ request('status') == 'butuh_revisi' ? 'selected' : '' }}>Perlu Direvisi</option>
                      <option value="direvisi" {{ request('status') == 'direvisi' ? 'selected' : '' }}>Revisi</option>
                  </select>
              </div>
          </form>
      </div>
      </div>
      <div class="p-4 bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No. Surat</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Pengajuan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Hal</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kepada/Tujuan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($dokumens->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center">
                                <div class="flex flex-col justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V12M12 16H12.01M18.364 5.636L15 9M21 12H15M9 12H3M6.636 5.636L10 9M12 12L12 20M6.636 18.364L10 15M18.364 18.364L15 15"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-600">Anda belum memiliki pengajuan surat.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                @foreach($dokumens as $dokumen)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->nomor_surat }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->tanggal_pengajuan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->perihal }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->dosen->nama_dosen }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($dokumen->status_dokumen) {
                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                'disahkan' => 'bg-green-100 text-green-800',
                                'butuh_revisi' => 'bg-red-100 text-red-800',
                                'direvisi' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($dokumen->status_dokumen) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900" onclick="showModal({{ $dokumen->id }}, '{{ asset('storage/' . $dokumen->file) }}')">Lihat Detail</a>
                    </td>
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="detailModal" class="hidden overflow-y-auto fixed inset-0 z-50">
      <div class="flex justify-center items-center px-4 min-h-screen">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

          <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
              <div class="px-6 py-4 border-b border-gray-200">
                  <h3 class="text-lg font-medium text-gray-900">Detail Dokumen</h3>
                  <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Close</span>
                      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>

              <div class="px-6 py-4" id="modalContent">
                  <!-- Content will be loaded here -->
              </div>

              <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200">
                  <button onclick="downloadDocument()"
                          class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                      Download
                  </button>
                  <button onclick="viewDocument()"
                          class="px-4 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                      Lihat
                  </button>
                  <button onclick="closeModal()"
                          class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                      Tutup
                  </button>
              </div>
          </div>
      </div>
  </div>

  <script>
      let currentDocumentId = null;
      let currentFileUrl = null;

      function showModal(documentId) {
        currentDocumentId = documentId;

        fetch(`/ormawa/dokumen/${documentId}`)
            .then(response => response.json())
            .then(data => {
                currentFileUrl = data.file_url;
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <iframe
                            src="${data.file_url}"
                            class="w-full h-[500px]"
                            frameborder="0"
                        ></iframe>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nomor Surat</p>
                            <p class="mt-1">${data.nomor_surat}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                            <p class="mt-1">${data.tanggal_pengajuan}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Perihal</p>
                            <p class="mt-1">${data.perihal}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">${data.status_dokumen}</p>
                        </div>
                        ${data.status_dokumen === 'butuh_revisi' ? `
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400">
                                <p class="text-sm font-medium text-yellow-800">Keterangan Revisi:</p>
                                <p class="mt-1 text-yellow-700">${data.keterangan_revisi || '-'}</p>
                            </div>
                            <div class="mt-4">
                                <form id="updateDokumenForm" class="space-y-4" enctype="multipart/form-data">
                                    <input type="file" name="dokumen" accept=".pdf" class="w-full p-2 border rounded-lg" required>
                                    <button type="submit" class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                        Update Dokumen
                                    </button>
                                </form>
                            </div>
                        ` : ''}
                    </div>
                `;

                // Add event listener for form submission
                if (data.status_dokumen === 'butuh_revisi') {
                    document.getElementById('updateDokumenForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        updateDokumen(documentId, this);
                    });
                }

                document.getElementById('detailModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat detail dokumen');
            });
}

      function closeModal() {
          document.getElementById('detailModal').classList.add('hidden');
          currentDocumentId = null;
          currentFileUrl = null;
      }

      function downloadDocument() {
          if (currentFileUrl) {
              const link = document.createElement('a');
              link.href = currentFileUrl;
              link.download = currentFileUrl.split('/').pop();
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
          }
      }

      function viewDocument() {
          if (currentFileUrl) {
              window.open(currentFileUrl, '_blank');
          }
      }

      function updateDokumen(documentId, form) {
          const formData = new FormData(form);

          // Tambahkan CSRF token
          formData.append('_token', '{{ csrf_token() }}');

          fetch(`/ormawa/dokumen/${documentId}/update`, {
              method: 'POST',
              body: formData,
              headers: {
                  'X-Requested-With': 'XMLHttpRequest'
              }
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Dokumen berhasil diupdate!');
                  window.location.reload();
              } else {
                  throw new Error(data.message || 'Terjadi kesalahan saat mengupdate dokumen');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert(error.message || 'Terjadi kesalahan saat mengupdate dokumen');
          });
      }

      // Close modal when clicking outside
      document.getElementById('detailModal').addEventListener('click', function(e) {
          if (e.target === this) {
              closeModal();
          }
      });
  </script>
@endsection
