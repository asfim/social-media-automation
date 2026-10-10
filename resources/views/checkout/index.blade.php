<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সহজ ক্যাশ-অন-ডেলিভারি চেকআউট | Checkout</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
        }
        .checkout-wrapper {
            max-width: 960px;
            margin: 40px auto;
        }
        .checkout-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }
        .checkout-header {
            background: linear-gradient(135deg, #4F46E5 0%, #3730A3 100%);
            color: white;
            padding: 24px 30px;
        }
        .product-preview-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .btn-order-now {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 14px;
            border-radius: 30px;
            border: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
        }
        .btn-order-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }
        .badge-cod {
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #A7F3D0;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="container checkout-wrapper px-3">
    
    <!-- Success Banner -->
    @if(session('order_success') || request('success'))
        @php 
            $order = session('order_success') ?? \App\Models\Order::where('order_number', request('order'))->first();
        @endphp
        <div class="card border-0 shadow-lg rounded-4 mb-4 text-center p-4 bg-white">
            <div class="mb-3 text-success fs-1">
                <i class="fa-solid fa-circle-check fa-bounce"></i>
            </div>
            <h3 class="fw-bold text-success mb-2">ধন্যবাদ! আপনার অর্ডারটি গ্রহণ করা হয়েছে।</h3>
            <p class="text-muted fs-5 mb-3">অর্ডার আইডি: <strong class="text-dark">#{{ $order->order_number ?? request('order') }}</strong></p>
            <div class="bg-light rounded-3 p-3 text-start mx-auto" style="max-width: 480px;">
                <p class="mb-1"><strong>গ্রাহকের নাম:</strong> {{ $order->customer_name ?? '' }}</p>
                <p class="mb-1"><strong>ফোন নম্বর:</strong> {{ $order->customer_phone ?? '' }}</p>
                <p class="mb-1"><strong>ডেলিভারি ঠিকানা:</strong> {{ $order->customer_address ?? '' }}</p>
                <p class="mb-1"><strong>পণ্য:</strong> {{ $order->product_name ?? '' }} (x{{ $order->quantity ?? 1 }})</p>
                <p class="mb-0 text-success fw-bold"><strong>মোট দেয় মূল্য:</strong> ৳{{ number_format($order->total_amount ?? 0, 2) }} (ক্যাশ অন ডেলিভারি)</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('inbox.messenger') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-comments me-2"></i> মেসেঞ্জারে ফিরে যান
                </a>
            </div>
        </div>
    @else

    <div class="checkout-card">
        <div class="checkout-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><i class="fa-solid fa-bag-shopping me-2"></i> কুইক চেকআউট (ক্যাশ অন ডেলিভারি)</h4>
                <p class="mb-0 opacity-75 small">অর্ডার করতে আপনার নাম, ফোন নম্বর ও সঠিক ঠিকানা দিন।</p>
            </div>
            <span class="badge-cod d-none d-md-inline-block"><i class="fa-solid fa-truck-fast me-1"></i> সারা বাংলাদেশে ক্যাশ অন ডেলিভারি</span>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversationId ?? request('conversation_id') }}">
                
                <div class="row g-4">
                    <!-- Left: Product Details -->
                    <div class="col-12 col-md-5">
                        <div class="card border-0 bg-light rounded-4 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-box text-primary me-2"></i> পণ্য বিবরণ</h6>
                            
                            @if($product)
                                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-preview-img mb-3" id="product_img">
                                <h5 class="fw-bold text-dark mb-2" id="product_title">{{ $product->name }}</h5>
                                <p class="text-muted small mb-3" id="product_desc">{{ $product->description }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded-3 border">
                                    <span class="fw-semibold text-muted">মূল্য:</span>
                                    <div>
                                        @if($product->discount_price && $product->discount_price > 0)
                                            <span class="text-muted text-decoration-line-through me-2">৳{{ number_format($product->price, 0) }}</span>
                                            <span class="fw-bold fs-4 text-success" id="product_price">৳{{ number_format($product->discount_price, 0) }}</span>
                                        @else
                                            <span class="fw-bold fs-4 text-success" id="product_price">৳{{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <label class="form-label fw-bold">পণ্য নির্বাচন করুন:</label>
                                    <select name="product_id" class="form-select" id="product_select">
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->discount_price ?: $p->price }}" data-img="{{ $p->image }}">{{ $p->name }} - ৳{{ number_format($p->discount_price ?: $p->price, 0) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="mt-3">
                                <label class="form-label fw-bold">পরিমাণ (Quantity):</label>
                                <input type="number" name="quantity" value="1" min="1" max="10" class="form-control text-center fw-bold" id="quantity_input">
                            </div>
                        </div>
                    </div>

                    <!-- Right: Customer Info Form -->
                    <div class="col-12 col-md-7">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-address-card text-primary me-2"></i> আপনার ডেলিভারি তথ্য দিন</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">আপনার নাম (Full Name) <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="উদাহরণ: মো: আরিফুল ইসলাম" required value="{{ old('customer_name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">মোবাইল নম্বর (Phone Number) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-phone text-muted"></i></span>
                                <input type="tel" name="customer_phone" class="form-control" placeholder="017XXXXXXXX" required pattern="[0-9]{11}" title="১১ ডিজিটের মোবাইল নম্বর দিন" value="{{ old('customer_phone') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">সম্পূর্ণ ডেলিভারি ঠিকানা (Full Address) <span class="text-danger">*</span></label>
                            <textarea name="customer_address" class="form-control form-control-lg" rows="3" placeholder="বাসা নম্বর, রোড নম্বর, থানা, জেলা (উদাহরণ: বাসা ১২, রোড ৫, মিরপুর ১০, ঢাকা)" required>{{ old('customer_address') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">নোট / বিশেষ নির্দেশাবলী (ঐচ্ছিক)</label>
                            <input type="text" name="notes" class="form-control" placeholder="যেমন: ডেলিভারির আগে কল দিন">
                        </div>

                        <div class="p-3 bg-soft-success rounded-3 border border-success border-opacity-25 mb-4 d-flex align-items-center gap-3">
                            <i class="fa-solid fa-hand-holding-dollar fs-2 text-success"></i>
                            <div>
                                <h6 class="fw-bold text-success mb-0">ক্যাশ অন ডেলিভারি (Cash on Delivery)</h6>
                                <small class="text-muted">পণ্য হাতে পেয়ে দেখে টাকা পরিশোধ করুন। কোনো অগ্রিম টাকা দেওয়ার প্রয়োজন নেই।</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-order-now w-100">
                            <i class="fa-solid fa-check-circle me-2"></i> অর্ডার নিশ্চিত করুন (Confirm Order)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
