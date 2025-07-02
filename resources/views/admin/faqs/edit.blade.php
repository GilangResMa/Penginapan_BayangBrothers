<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit FAQ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/admin.css'])
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
<aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-shield-alt logo-icon"></i>
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="nav-item">
                    <i class="fas fa-bed"></i>
                    Manage Rooms
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-item active">
                    <i class="fas fa-question-circle"></i>
                    Manage FAQ
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item">
                    <i class="fas fa-credit-card"></i>
                    Payment Verification
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>


        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Edit FAQ</h1>
                <p>Update the frequently asked question</p>
            </header>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-container">
                <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" id="question" name="question" class="form-input" 
                               value="{{ old('question', $faq->question) }}" placeholder="Enter the frequently asked question..." required>
                    </div>

                    <div class="form-group">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea id="answer" name="answer" class="form-input form-textarea" 
                                  style="min-height: 150px;" placeholder="Enter the detailed answer..." required>{{ old('answer', $faq->answer) }}</textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update FAQ
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to FAQ
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
