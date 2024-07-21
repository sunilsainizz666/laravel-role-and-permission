@extends('backend.auth.auth_master')

@section('auth_title')
    Register | Admin Panel
@endsection

@section('auth-content')
    <div class="register-area">
        <div class="container">
            <div class="register-box ptb--100">
                <form id="registerForm" method="POST" action="{{ route('admin.register.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="register-form-head">
                        <h4>Sign Up</h4>
                        <p>Hello there, Sign Up and start managing your Admin Panel</p>
                    </div>
                    <div class="register-form-body">
                        @include('backend.layouts.partials.messages')
                        <div class="form-gp">
                            <label for="exampleInputName1">Name</label>
                            <input type="text" id="exampleInputName1" name="name" required>
                            <i class="ti-user"></i>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" id="exampleInputEmail1" name="email" required>
                            <i class="ti-email"></i>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputPhone1">Phone</label>
                            <input type="text" id="exampleInputPhone1" name="phone" required pattern="[0-9]{10}">
                            <i class="ti-mobile"></i>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputDescription1">Description</label>
                            <textarea id="exampleInputDescription1" name="description" required></textarea>
                            {{-- <i class="ti-pencil-alt"></i> --}}
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            {{-- <label for="exampleInputRole1" >Role</label> --}}
                            <select id="exampleInputRole1" name="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            {{-- <label for="exampleInputProfileImage1">Profile Image</label> --}}
                            <input type="file" id="exampleInputProfileImage1" name="profile_image" accept="image/*">
                            <i class="ti-image"></i>
                            @error('profile_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            <i class="ti-lock"></i>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-gp">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <i class="ti-lock"></i>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Sign Up <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="submit-btn-area" style="margin-top: 10px;">
                            <a href="{{route('admin.login')}}">Sign In <i class="ti-arrow-right"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table id="userTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Description</th>
                <th>Profile Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->description }}</td>
                    <td><img src="{{ asset($user->profile_image) }}" alt="Profile Image" width="50"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = e.target;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            // Handle errors
            for (const [key, value] of Object.entries(data.errors)) {
                let input = document.querySelector(`[name="${key}"]`);
                input.classList.add('is-invalid');
                input.nextElementSibling.innerHTML = value[0];
            }
        } else {
            // Clear form
            form.reset();
            // Remove error messages
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerHTML = '');

            // Add new user to the table
            let table = document.querySelector('#userTable tbody');
            let newRow = table.insertRow();
            newRow.innerHTML = `
                <td>${data.user.name}</td>
                <td>${data.user.email}</td>
                <td>${data.user.phone}</td>
                <td>${data.user.description}</td>
                <td>${data.user.role.name}</td>
                <td><img src="${data.user.profile_image}" alt="Profile Image" width="50"></td>
            `;
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>