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
                                        <option value="home-page" {{ $policy->type == 'home-page' ? 'selected' : '' }}>Home Page</option>
                                    </select>
                                </div>

                                {{-- Title --}}
                                <div class="col-md-4 mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $policy->title }}" class="form-control" required>
                                </div>

                                {{-- Content --}}
                                <div class="col-md-12 mb-3" id="contentField">
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
                                        <textarea name="address" id="address" class="form-control" rows="5" placeholder="Write address ...">{{ $policy->address }}</textarea>
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
                                    <label>Website Link</label>
                                    <input type="text" name="website_link" value="{{ $policy->website_link }}" class="form-control">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Facebook</label>
                                        <input type="text" name="facebook" value="{{ $policy->facebook }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                    <label>Facebook Icon</label>
                                    <input type="file" name="facebook_icon" class="form-control">
                                    @if($policy->facebook_icon)
                                        <img src="{{ asset($policy->facebook_icon) }}" alt="Facebook Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Instagram</label>
                                        <input type="text" name="instagram" value="{{ $policy->instagram }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                    <label>Instagram Icon</label>
                                    <input type="file" name="instagram_icon" class="form-control">
                                    @if($policy->instagram_icon)
                                        <img src="{{ asset($policy->instagram_icon) }}" alt="Instagram Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Twitter</label>
                                        <input type="text" name="twitter" value="{{ $policy->twitter }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                    <label>Twitter Icon</label>
                                    <input type="file" name="twitter_icon" class="form-control">
                                    @if($policy->twitter_icon)
                                        <img src="{{ asset($policy->twitter_icon) }}" alt="Twitter Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-6 mb-3">
                                        <label>LinkedIn</label>
                                        <input type="text" name="linkedin" value="{{ $policy->linkedin }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                    <label>LinkedIn Icon</label>
                                    <input type="file" name="linkedin_icon" class="form-control">
                                    @if($policy->linkedin_icon)
                                        <img src="{{ asset($policy->linkedin_icon) }}" alt="LinkedIn Icon" width="50">
                                    @endif
                                </div>

                                    <div class="col-md-6 mb-3">
                                        <label>YouTube</label>
                                        <input type="text" name="youtube" value="{{ $policy->youtube }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                    <label>YouTube Icon</label>
                                    <input type="file" name="youtube_icon" class="form-control">
                                    @if($policy->youtube_icon)
                                        <img src="{{ asset($policy->youtube_icon) }}" alt="YouTube Icon" width="50">
                                    @endif
                                </div>

                                {{-- Share Location Url --}}
                                <div class="col-md-6 mb-3">
                                    <label>Share Location Url</label>
                                    <input type="text" name="map_location_link" value="{{ $policy->map_location_link }}" class="form-control">
                                </div>

                                    {{-- Share App Link --}}
                                <div class="col-md-6 mb-3">
                                    <label>Share App Link</label>
                                    <input type="text" name="share_app_link" value="{{ $policy->share_app_link }}" class="form-control">
                                </div>
                                </div>
                            </div>

                                {{-- Home Page Fields --}}
                            <div id="homePageFields" style="display:none;">
                                <hr>

                                <div class="row mt-3">

                                     <div class="col-md-6 mb-3">
                                        <label>Call Us Number</label>
                                        <input type="text" name="call_number" value="{{ $policy->call_number }}" class="form-control"
                                            placeholder="+1 234 567 890">
                                     </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Chat With Us Number</label>
                                        <input type="text" name="chat_number" value="{{ $policy->chat_number }}" class="form-control"
                                            placeholder="+1 234 567 890">
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


<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    function togglePolicyFields() {
        const typeSelect = document.getElementById('type');
        const type = typeSelect.value;

        const contactFields = document.getElementById('contactFields');
        const homePageFields = document.getElementById('homePageFields');
        const contentField = document.getElementById('contentField');
        const contentInput = document.getElementById('content');
        const titleInput = document.getElementById('title');

        // Reset all
        contactFields.style.display = 'none';
        homePageFields.style.display = 'none';
        contentField.style.display = 'block';
        contentInput.required = true;

        if (type === 'contact-us') {
            contactFields.style.display = 'block';
            contentField.style.display = 'none';
            contentInput.required = false;
        }

        if (type === 'home-page') {
            homePageFields.style.display = 'block';
            contentField.style.display = 'none';
            contentInput.required = false;
        }

        // Auto title
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        if (selectedOption?.dataset.title && !titleInput.value) {
            titleInput.value = selectedOption.dataset.title;
        }
    }

    document.getElementById('type').addEventListener('change', togglePolicyFields);
    document.addEventListener('DOMContentLoaded', togglePolicyFields);
</script>


@endsection