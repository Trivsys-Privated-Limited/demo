<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Table {{ $table->table_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Welcome!</h2>
                    <p class="text-muted">Please enter your details to view the menu</p>
                </div>

                <div class="card p-4">
                    <div class="card-body">
                        <form action="{{ route('menu.register', $table->qr_token) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Your Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="John Doe" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
    <label for="phone" class="form-label fw-semibold">Phone Number</label>
    <input type="tel" 
           class="form-control @error('phone') is-invalid @enderror" 
           id="phone" 
           name="phone" 
           placeholder="03001234567" 
           value="{{ old('phone') }}"
           pattern="^((\+92)|(0))?3[0-9]{9}$"
           title="Please enter a valid Pakistani phone number (e.g., 03001234567 or +923001234567)"
           maxlength="13"
           required>
    
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                            <div class="alert alert-info text-center py-2 mb-4" role="alert">
                                📍 You are at <strong>Table {{ $table->table_number }}</strong>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">View Menu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
