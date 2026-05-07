@extends('layouts.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}
        </h4>

        <div class="card">
            <h5 class="card-header">SEO & System Settings</h5>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label class="form-label" for="site_title">Site Title</label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_title"
                            name="site_title"
                            value="{{ old('site_title', $settings['site_title'] ?? '') }}"
                            required
                        />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="site_description">Site Description</label>
                        <textarea
                            class="form-control"
                            id="site_description"
                            name="site_description"
                            rows="3"
                        >{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="site_keywords">Site Keywords (pisahkan koma)</label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_keywords"
                            name="site_keywords"
                            value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}"
                        />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="site_name">Site Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="site_name"
                            name="site_name"
                            value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                        />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="contact_email">Contact Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="contact_email"
                            name="contact_email"
                            value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        />
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="contact_phone">Contact Phone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="contact_phone"
                            name="contact_phone"
                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                        />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="site_favicon">Site Favicon</label>
                        <input
                            type="file"
                            class="form-control"
                            id="site_favicon"
                            name="site_favicon"
                            accept=".png,.jpg,.jpeg,.webp,.ico"
                        />
                        <small class="text-muted">Maksimal 2MB. Format: png, jpg, jpeg, webp, ico.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Favicon Saat Ini</label>
                        <img
                            src="{{ get_system_file('site_favicon', 'assets/img/favicon/favicon.ico') }}"
                            alt="Current Favicon"
                            style="width: 48px; height: 48px; object-fit: contain;"
                        />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
