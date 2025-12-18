@extends('admin.admin_layout.admin_master_layout')

@section('content')


<style>
    .alert.alert-error {
        color: red;
        font-size: 16px;
        font-weight: 700;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Privacy Policy</h3>
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif


                    @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('policies.update', $policy->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="container mt-4">
                            <div class="row">

                                {{-- Policy Type --}}
                                <div class="col-md-4 mb-3">
                                    <label>Policy Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">-- Select Policy Type --</option>
                                        <option value="privacy" {{ $policy->type == 'privacy' ? 'selected' : '' }}>Privacy</option>
                                        <option value="terms" {{ $policy->type == 'terms' ? 'selected' : '' }}>Terms & Conditions</option>
                                        <option value="refund" {{ $policy->type == 'refund' ? 'selected' : '' }}>Refund</option>
                                        <option value="contact-us" {{ $policy->type == 'contact-us' ? 'selected' : '' }}>Contact Us</option>
                                        <option value="about-us" {{ $policy->type == 'about-us' ? 'selected' : '' }}>About Us</option>
                                    </select>
                                </div>

                                {{-- Title --}}
                                <div class="col-md-4 mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $policy->title }}" class="form-control" required>
                                </div>

                                {{-- Content --}}
                                <div class="col-md-12 mb-3">
                                    <label>Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="5" required>{{ $policy->content }}</textarea>
                                </div>

                            </div>

                            {{-- Contact & Social Fields --}}
                            <div id="contactFields" style="display:none;">
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ $policy->email }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Email 2</label>
                                        <input type="email2" name="email2" value="{{ $policy->email2 }}" class="form-control">
                                    </div>
                                    {{-- address --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="content" class="form-label">Address</label>
                                        <textarea name="address" id="address" class="form-control" rows="5" placeholder="Write address ..." required>{{ $policy->address }}</textarea>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Contact Number 1</label>
                                        <input type="text" name="contact1" value="{{ $policy->contact1 }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Contact Number 2</label>
                                        <input type="text" name="contact2" value="{{ $policy->contact2 }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Facebook</label>
                                        <input type="text" name="facebook" value="{{ $policy->facebook }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                    <label>Facebook Icon</label>
                                    <input type="file" name="facebook_icon" class="form-control">
                                    @if($policy->facebook_icon)
                                        <img src="{{ asset($policy->facebook_icon) }}" alt="Facebook Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Instagram</label>
                                        <input type="text" name="instagram" value="{{ $policy->instagram }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                    <label>Instagram Icon</label>
                                    <input type="file" name="instagram_icon" class="form-control">
                                    @if($policy->instagram_icon)
                                        <img src="{{ asset($policy->instagram_icon) }}" alt="Instagram Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Twitter</label>
                                        <input type="text" name="twitter" value="{{ $policy->twitter }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                    <label>Twitter Icon</label>
                                    <input type="file" name="twitter_icon" class="form-control">
                                    @if($policy->twitter_icon)
                                        <img src="{{ asset($policy->twitter_icon) }}" alt="Twitter Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-4 mb-3">
                                        <label>LinkedIn</label>
                                        <input type="text" name="linkedin" value="{{ $policy->linkedin }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                    <label>LinkedIn Icon</label>
                                    <input type="file" name="linkedin_icon" class="form-control">
                                    @if($policy->linkedin_icon)
                                        <img src="{{ asset($policy->linkedin_icon) }}" alt="LinkedIn Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-4 mb-3">
                                        <label>YouTube</label>
                                        <input type="text" name="youtube" value="{{ $policy->youtube }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                    <label>YouTube Icon</label>
                                    <input type="file" name="youtube_icon" class="form-control">
                                    @if($policy->youtube_icon)
                                        <img src="{{ asset($policy->youtube_icon) }}" alt="YouTube Icon" width="50">
                                    @endif
                                </div>

                                {{-- YOUTUBE --}}
                                <div class="col-md-4 mb-3">
                                    <label>Share Location Url</label>
                                    <input type="text" name="map_location_link" value="{{ $policy->map_location_link }}" class="form-control">
                                </div>

                                    {{-- YOUTUBE --}}
                                <div class="col-md-4 mb-3">
                                    <label>Share App Link</label>
                                    <input type="text" name="share_app_link" value="{{ $policy->share_app_link }}" class="form-control">
                                </div>

                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary ml-4 btn-sm mb-2">Submit</button>
                        <a href="{{ route('policies.create') }}" class="btn btn-info btn-sm mb-2">Back</a>
                    </form>

                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->

        </div>

    </div>
</section>

<script>
    function toggleContactFields() {
    let type = document.getElementById('type').value;
    let fields = document.getElementById('contactFields');
    let inputs = fields.querySelectorAll("input, textarea, select");

    if (type === 'contact-us') {
        fields.style.display = 'block';
        inputs.forEach(i => i.disabled = false);  // ENABLE fields
    } else {
        fields.style.display = 'none';
        inputs.forEach(i => i.disabled = true);   // DISABLE fields
    }
}

document.getElementById('type').addEventListener('change', toggleContactFields);

// Run on page load
window.onload = function () {
    toggleContactFields();
};

</script>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>

@endsection