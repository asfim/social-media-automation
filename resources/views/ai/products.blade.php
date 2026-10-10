@extends('layouts.app')

@section('title', 'Products & Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Products & Services Catalog</h3>
        <p class="text-muted mb-0">If the AI knows your pricing and stock, it can sell on your behalf.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-plus me-2"></i> Add Item
    </button>
</div>

<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="bg-light text-center p-0 border-bottom overflow-hidden position-relative" style="height: 180px;">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <i class="fa-solid fa-box text-primary" style="font-size: 4rem;"></i>
                    </div>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold mb-0">{{ $product->name }}</h5>
                    <span class="badge bg-success rounded-pill">Available</span>
                </div>
                <h4 class="text-primary fw-bold mb-3">৳{{ number_format($product->price) }}</h4>
                <p class="text-muted small mb-4">{{ Str::limit($product->description, 100) }}</p>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill flex-grow-1" onclick="editProduct({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ addslashes($product->description) }}', '{{ addslashes($product->image ?? '') }}')">Edit Details</button>
                    <button class="btn btn-outline-danger rounded-pill" onclick="deleteProduct({{ $product->id }})" title="Delete Product"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted mt-5">
        <i class="fa-solid fa-box-open fs-1 mb-3"></i>
        <h5>No products added yet</h5>
        <p>Click "Add Item" to teach the AI about your products.</p>
    </div>
    @endforelse
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="/api/internal/ai/products" method="POST" id="addProductForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Product / Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. E-commerce Website Development">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Price (BDT)</label>
                        <input type="number" name="price" class="form-control rounded-3" required placeholder="e.g. 15000">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-upload me-1 text-primary"></i> Upload Image File</label>
                        <input type="file" name="image_file" class="form-control rounded-3" accept="image/*">
                        <div class="form-text">Choose a photo from your computer or phone.</div>
                    </div>
                    
                    <div class="text-center text-muted my-2 small fw-bold">— OR USE IMAGE LINK —</div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-link me-1 text-primary"></i> Image URL <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="url" name="image" class="form-control rounded-3" placeholder="https://example.com/product.jpg">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control rounded-3" rows="3" required placeholder="Describe the product so the AI can explain it to customers..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" id="editProductForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_product_id">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Product / Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Price (BDT)</label>
                        <input type="number" name="price" id="edit_price" class="form-control rounded-3" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-upload me-1 text-primary"></i> Upload New Image File</label>
                        <input type="file" name="image_file" class="form-control rounded-3" accept="image/*">
                        <div class="form-text">Upload a new photo to replace the current image.</div>
                    </div>
                    
                    <div class="text-center text-muted my-2 small fw-bold">— OR USE IMAGE LINK —</div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-link me-1 text-primary"></i> Image URL</label>
                        <input type="url" name="image" id="edit_image" class="form-control rounded-3" placeholder="https://example.com/product.jpg">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/api/internal/ai/products', {
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
            alert('Error adding product.');
        }
    });
});

function editProduct(id, name, price, description, image) {
    document.getElementById('edit_product_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_image').value = image || '';
    
    new bootstrap.Modal(document.getElementById('editProductModal')).show();
}

document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_product_id').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(`/api/internal/ai/products/${id}`, {
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
    });
});

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/api/internal/ai/products/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            } else {
                alert('Error deleting product.');
            }
        });
    }
}
</script>
@endsection
