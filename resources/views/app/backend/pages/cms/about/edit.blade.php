<form id="ajxForm" action="{{ route('cms.about.updateFeature', $feature->id) }}" method="post">
    @csrf
    @method('PUT')

    <div class="card-body">

        <div class="mb-3">
            <label for="feature_title" class="form-label">
                Title <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" name="title" value="{{ old('title', $feature->title) }}"
                placeholder="Feature title">
        </div>

        <div class="mb-3">
            <label for="feature_description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Brief description">{{ old('description', $feature->description) }}</textarea>
        </div>
    </div>

    <div class="card-footer text-end">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-save me-2"></i>Update Feature
        </button>
    </div>
</form>
