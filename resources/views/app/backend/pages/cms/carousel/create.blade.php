 <form id="ajxForm" enctype="multipart/form-data" method="post" action="{{ route('cms.carousel.store') }}">
     @csrf
     <div class="card-body p-4 shadow-sm mb-3 bg-body rounded">
         <div class="row g-4">
             <div class="col-lg-6">
                 <h6 class="fw-bold mb-3 text-primary">
                     <i class="fas fa-info-circle me-2"></i>Informasi Carousel
                 </h6>

                 <div class="mb-3">
                     <label for="title" class="form-label fw-semibold">
                         Title <span class="text-danger">*</span>
                     </label>
                     <input type="text" class="form-control" id="title" name="title" required
                         placeholder="Masukkan judul carousel">
                     <div class="invalid-feedback">Title wajib diisi.</div>
                 </div>

                 <div class="mb-3">
                     <label for="subtitle" class="form-label fw-semibold">Subtitle</label>
                     <textarea class="form-control" id="subtitle" name="subtitle" rows="3"
                         placeholder="Deskripsi atau subtitle carousel (opsional)"></textarea>
                 </div>

                 <div class="mb-3">
                     <label for="button_text" class="form-label fw-semibold">Button Text</label>
                     <input type="text" class="form-control" id="button_text" name="button_text"
                         placeholder="e.g., Pelajari Lebih Lanjut">
                     <small class="text-muted">Teks yang akan ditampilkan pada tombol</small>
                 </div>

                 <div class="mb-3">
                     <label for="button_url" class="form-label fw-semibold">Button URL</label>
                     <input type="url" class="form-control" id="button_url" name="button_url"
                         placeholder="https://example.com">
                     <small class="text-muted">Link tujuan saat tombol diklik</small>
                 </div>

                 <div class="row">
                     <div class="col-6">
                         <label for="order" class="form-label fw-semibold">Urutan</label>
                         <input type="number" class="form-control" id="order" name="order" value="0"
                             min="0">
                     </div>
                     <div class="col-6">
                         <label class="form-label fw-semibold d-block">Status</label>
                         <div class="form-check form-switch mt-2">
                             <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                             <label class="form-check-label" for="is_active">
                                 <span class="badge bg-success">Active</span>
                             </label>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="col-lg-6">
                 <h6 class="fw-bold mb-3 text-primary">
                     <i class="fas fa-images me-2"></i>Media Carousel
                 </h6>

                 <div class="mb-4">
                     <label for="background_image" class="form-label fw-semibold">
                         Background Image <span class="text-danger">*</span>
                     </label>
                     <div class="upload-area" id="bgUploadArea">
                         <input type="file" class="form-control" id="background_image" name="background_image"
                             accept="image/*">
                     </div>
                 </div>

                 <div class="mb-3">
                     <label for="foreground_image" class="form-label fw-semibold">
                         Foreground Image <span class="badge bg-secondary">Optional</span>
                     </label>
                     <div class="upload-area" id="fgUploadArea">
                         <input type="file" class="form-control" id="foreground_image" name="foreground_image"
                             accept="image/*">
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="card-footer text-end">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
             <i class="fas fa-times me-2"></i>Batal
         </button>
         <button type="submit" class="btn btn-primary">
             <i class="fas fa-save me-2"></i>Simpan Carousel
         </button>
     </div>
 </form>
