@extends('layouts.app')

@section('title', 'Business Knowledge Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Business Knowledge</h3>
        <p class="text-muted mb-0">Feed the AI with your company's documents, policies, and history.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addKnowledgeModal">
        <i class="fa-solid fa-plus me-2"></i> Add Document
    </button>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Document Title</th>
                                <th class="py-3">Type</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($knowledge as $doc)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $doc->title }}</div>
                                    <small class="text-muted">Added {{ $doc->created_at->diffForHumans() }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><i class="fa-solid fa-align-left text-primary me-1"></i> Text Content</span></td>
                                <td>
                                    @if($doc->is_active)
                                    <span class="badge bg-success rounded-pill px-3">Trained</span>
                                    @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3"><i class="fa-solid fa-spinner fa-spin me-1"></i> Training...</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light rounded-circle text-primary me-1" onclick="editKnowledge({{ $doc->id }}, '{{ addslashes($doc->title) }}', '{{ addslashes($doc->content) }}')"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-sm btn-light rounded-circle text-danger" onclick="deleteKnowledge({{ $doc->id }})"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fs-1 mb-3"></i>
                                    <h5>No documents added</h5>
                                    <p>Add some text documents to train the AI.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-brain" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Vector Database Status</h5>
                <p class="small text-white-50 mb-4">The AI converts your text into vectors to instantly retrieve answers.</p>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Documents Trained</span>
                    <span class="fw-bold">{{ count($knowledge) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vector Embeddings</span>
                    <span class="fw-bold">{{ count($knowledge) * 125 }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Storage Used</span>
                    <span class="fw-bold">{{ count($knowledge) > 0 ? '1.2 MB' : '0 MB' }}</span>
                </div>
                
                <button class="btn btn-light w-100 rounded-pill fw-bold text-primary">
                    <i class="fa-solid fa-arrows-rotate me-2"></i> Retrain Entire Model
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Knowledge Modal -->
<div class="modal fade" id="addKnowledgeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="/api/internal/ai/knowledge" method="POST" id="addKnowledgeForm">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Text Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Document Title</label>
                        <input type="text" name="title" class="form-control rounded-3" required placeholder="e.g. Return Policy">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Content</label>
                        <textarea name="content" class="form-control rounded-3" rows="6" required placeholder="Paste the text content here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save & Train AI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Knowledge Modal -->
<div class="modal fade" id="editKnowledgeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" id="editKnowledgeForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_knowledge_id">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Text Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Document Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Content</label>
                        <textarea name="content" id="edit_content" class="form-control rounded-3" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update & Train AI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addKnowledgeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/api/internal/ai/knowledge', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert('Error adding document.');
        }
    });
});

function editKnowledge(id, title, content) {
    document.getElementById('edit_knowledge_id').value = id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_content').value = content;
    
    new bootstrap.Modal(document.getElementById('editKnowledgeModal')).show();
}

document.getElementById('editKnowledgeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_knowledge_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch(`/api/internal/ai/knowledge/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert('Error updating document.');
        }
    });
});

function deleteKnowledge(id) {
    if (confirm('Are you sure you want to delete this document?')) {
        fetch(`/api/internal/ai/knowledge/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            } else {
                alert('Error deleting document.');
            }
        });
    }
}
</script>
@endsection
