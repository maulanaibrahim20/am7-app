 <form id="ajxForm" action="{{ route('cms.feature.store') }}" method="post">
     @csrf
     <div class="card-body">
         <div class="row">
             <!-- Icon -->
             <div class="col-md-6 mb-3">
                 <label for="icon" class="form-label">Icon Class <span class="text-danger">*</span></label>
                 <input type="text" class="form-control" name="icon" placeholder="e.g., fas fa-cog">
                 <small class="text-muted">
                     Use <strong>Font Awesome 6</strong> icons:
                     <a href="https://fontawesome.com/search?ic=free&o=r" target="_blank">Browse Icons</a><br>
                     Example: <code>fas fa-car</code>, <code>fas fa-tools</code>, <code>fas fa-truck</code>
                 </small>
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Title -->
             <div class="col-md-6 mb-3">
                 <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                 <input type="text" class="form-control" name="title" placeholder="Feature title">
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Description -->
             <div class="col-12 mb-3">
                 <label for="description" class="form-label">Description</label>
                 <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the feature"></textarea>
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Link Text -->
             <div class="col-md-6 mb-3">
                 <label for="link_text" class="form-label">Link Text</label>
                 <input type="text" class="form-control" name="link_text" placeholder="e.g., Learn More">
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Link URL -->
             <div class="col-md-6 mb-3">
                 <label for="link_url" class="form-label">Link URL</label>
                 <input type="url" class="form-control" name="link_url" placeholder="https://example.com">
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Background Style -->
             <div class="col-md-6 mb-3">
                 <label for="background_style" class="form-label">Background Style</label>
                 <select class="form-select" name="background_style">
                     <option value="bg-light">Light</option>
                     <option value="bg-white">White</option>
                     <option value="bg-primary bg-opacity-10">Primary Light</option>
                     <option value="bg-success bg-opacity-10">Success Light</option>
                     <option value="bg-info bg-opacity-10">Info Light</option>
                 </select>
                 <div class="invalid-feedback"></div>
             </div>

             <!-- Is Active -->
             <div class="col-md-6 mb-3">
                 <label class="form-label d-block">Status</label>
                 <div class="form-check form-switch mt-2">
                     <input type="hidden" name="is_active" value="0"> {{-- <-- tambahkan ini --}}
                     <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                         checked>
                     <label class="form-check-label" for="is_active">Active</label>
                 </div>
             </div>
         </div>
     </div>

     <div class="card-footer text-end">
         <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
         <button type="submit" class="btn btn-primary">
             <i class="bx bx-save me-2"></i>Save Feature
         </button>
     </div>
 </form>
