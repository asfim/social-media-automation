$views = @{
    "inbox/messenger.blade.php" = "Messenger Inbox"
    "inbox/comments.blade.php" = "Social Comments"
    "inbox/conversations.blade.php" = "All Conversations"
    
    "automation/auto-reply.blade.php" = "Auto Reply Rules"
    "automation/auto-comment.blade.php" = "Auto Comment Rules"
    "automation/keyword-rules.blade.php" = "Keyword Rules"
    "automation/ai-rules.blade.php" = "AI Response Rules"
    "automation/spam.blade.php" = "Spam Protection"
    
    "ai/chat.blade.php" = "Internal AI Chat"
    "ai/knowledge.blade.php" = "Business Knowledge Base"
    "ai/faq.blade.php" = "Frequently Asked Questions"
    "ai/products.blade.php" = "Products & Services"
    "ai/settings.blade.php" = "AI Engine Settings"
    
    "leads/all.blade.php" = "All Leads"
    "leads/new.blade.php" = "New Leads"
    "leads/hot.blade.php" = "Hot Leads"
    "leads/follow-up.blade.php" = "Follow Up Scheduler"
    "leads/pipeline.blade.php" = "Sales Pipeline (Kanban)"
    
    "settings/business.blade.php" = "Business Profile Settings"
    "settings/facebook.blade.php" = "Facebook Integration"
    "settings/instagram.blade.php" = "Instagram Integration"
    "settings/general.blade.php" = "General System Settings"
}

$basePath = "C:\xampp\htdocs\Atomation\resources\views"

foreach ($view in $views.GetEnumerator()) {
    $filePath = "$basePath\$($view.Key)"
    $dir = Split-Path $filePath -Parent
    
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
    }
    
    $title = $view.Value
    
    $content = "@extends('layouts.app')

@section('title', '$title')

@section('content')
<div class='d-flex justify-content-between align-items-center mb-4'>
    <h3 class='mb-0'>$title</h3>
    <button class='btn btn-primary rounded-pill px-4'>
        <i class='fa-solid fa-plus me-2'></i> Add New
    </button>
</div>

<div class='card border-0 shadow-sm rounded-4'>
    <div class='card-body p-0'>
        <div class='table-responsive'>
            <table class='table table-hover align-middle mb-0'>
                <thead class='bg-light'>
                    <tr>
                        <th class='ps-4 py-3'>ID</th>
                        <th class='py-3'>Name / Details</th>
                        <th class='py-3'>Status</th>
                        <th class='py-3'>Date</th>
                        <th class='text-end pe-4 py-3'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='ps-4'>#1042</td>
                        <td>
                            <div class='fw-medium'>Sample Data Entry</div>
                            <small class='text-muted'>This is a placeholder row for $title</small>
                        </td>
                        <td><span class='badge bg-soft-success'>Active</span></td>
                        <td class='text-muted small'>Just now</td>
                        <td class='text-end pe-4'>
                            <button class='btn btn-sm btn-light rounded-circle'><i class='fa-solid fa-pen'></i></button>
                            <button class='btn btn-sm btn-light rounded-circle text-danger ms-1'><i class='fa-solid fa-trash'></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class='ps-4'>#1041</td>
                        <td>
                            <div class='fw-medium'>Another Record</div>
                            <small class='text-muted'>System generated</small>
                        </td>
                        <td><span class='badge bg-soft-warning'>Pending</span></td>
                        <td class='text-muted small'>2 hours ago</td>
                        <td class='text-end pe-4'>
                            <button class='btn btn-sm btn-light rounded-circle'><i class='fa-solid fa-pen'></i></button>
                            <button class='btn btn-sm btn-light rounded-circle text-danger ms-1'><i class='fa-solid fa-trash'></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class='card-footer bg-white border-top-0 p-4'>
        <nav aria-label='Page navigation'>
            <ul class='pagination pagination-sm justify-content-end mb-0'>
                <li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>
                <li class='page-item active'><a class='page-link' href='#'>1</a></li>
                <li class='page-item'><a class='page-link' href='#'>2</a></li>
                <li class='page-item'><a class='page-link' href='#'>Next</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
"
    Set-Content -Path $filePath -Value $content -Encoding UTF8
}
