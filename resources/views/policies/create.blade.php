@extends('admin.admin_layout.admin_master_layout')

@section('content')


<style>
    .alert.text-error {
        color: red;
        font-size: 16px;
        font-weight: 700;
    }

    .alert.text-success {
        color: green;
        font-size: 16px;
        font-weight: 700;
    }

    div#contactFields {
        padding: 10px;
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
                        <h3 class="card-title">Add Privacy Policy</h3>
                    </div>

                    @if (session('success'))
                    <div class="alert text-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert text-error">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('policies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="container mt-4">
                            <div class="row">

                                {{-- Type --}}
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Policy Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">-- Select Policy Type --</option>
                                        <option value="privacy">Privacy</option>
                                        <option value="terms">Terms & Conditions</option>
                                        <option value="refund">Refund</option>
                                        <option value="contact-us">Contact Us</option>
                                        <option value="about-us">About Us</option>
                                        <option value="home-page">Home Page</option>
                                    </select>
                                </div>

                                {{-- Title --}}
                                <div class="col-md-4 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Privacy Policy" required>
                                </div>

                                {{-- Content --}}
                                <div class="col-md-12 mb-3" id="contentField">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="5" placeholder="Write policy content..." required></textarea>
                                </div>

                                {{-- Contact & Social Fields (Show only when type = contact-us) --}}
                                <div id="contactFields" style="display:none;">
                                    <div class="row mt-3">

                                        {{-- Email --}}
                                        <div class="col-md-4 mb-3">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>Email 2</label>
                                            <input type="email" name="email2" class="form-control">
                                        </div>

                                        {{-- Address --}}
                                        <div class="col-md-12 mb-3">
                                            <label>Address</label>
                                            <textarea name="address" class="form-control" rows="3"></textarea>
                                        </div>

                                        {{-- Contact Numbers --}}
                                        <div class="col-md-4 mb-3">
                                            <label>Contact Number 1</label>
                                            <input type="text" name="contact1" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>Contact Number 2</label>
                                            <input type="text" name="contact2" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                        <label>Website Link</label>
                                        <input type="text" name="website_link" value="{{ old('website_link') }}" class="form-control">
                                        </div>
                                        
                                        </div>

                                        <hr>

                                       <div class="row">
                                         {{-- FACEBOOK --}}
                                        <div class="col-md-6 mb-3">
                                            <label>Facebook URL</label>
                                            <input type="text" name="facebook" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Facebook Icon (PNG/JPG/SVG)</label>
                                            <input type="file" name="facebook_icon" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg">
                                        </div>

                                        {{-- INSTAGRAM --}}
                                        <div class="col-md-4 mb-3">
                                            <label>Instagram URL</label>
                                            <input type="text" name="instagram" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>Instagram Icon</label>
                                            <input type="file" name="instagram_icon" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg">
                                        </div>

                                        {{-- X / TWITTER --}}
                                        <div class="col-md-4 mb-3">
                                            <label>X (Twitter) URL</label>
                                            <input type="text" name="twitter" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>X (Twitter) Icon</label>
                                            <input type="file" name="twitter_icon" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg">
                                        </div>

                                        {{-- LINKEDIN --}}
                                        <div class="col-md-4 mb-3">
                                            <label>LinkedIn URL</label>
                                            <input type="text" name="linkedin" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>LinkedIn Icon</label>
                                            <input type="file" name="linkedin_icon" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg">
                                        </div>

                                        {{-- YOUTUBE --}}
                                        <div class="col-md-4 mb-3">
                                            <label>YouTube URL</label>
                                            <input type="text" name="youtube" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>YouTube Icon</label>
                                            <input type="file" name="youtube_icon" class="form-control"
                                                accept=".png,.jpg,.jpeg,.svg">
                                        </div>


                                         {{-- Share Location Url --}}
                                        <div class="col-md-4 mb-3">
                                            <label>Share Location Url</label>
                                            <input type="text" name="map_location_link" class="form-control">
                                        </div>


                                         {{-- Share App Link --}}
                                        <div class="col-md-4 mb-3">
                                            <label>Share App Link</label>
                                            <input type="text" name="share_app_link" class="form-control">
                                        </div>
                                       </div>

                                    <!-- </div> -->
                                </div>


                                {{-- Home Page Fields --}}
                            <div id="homePageFields" style="display:none;">
                                <hr>

                                <div class="row mt-3">

                                     <div class="col-md-6 mb-3">
                                        <label>Call Us Number</label>
                                        <input type="text" name="call_number" class="form-control"
                                            placeholder="+1 234 567 890">
                                     </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Chat With Us Number</label>
                                        <input type="text" name="chat_number" class="form-control"
                                            placeholder="+1 234 567 890">
                                    </div>
                                </div>
                                </div>
                                </div>
                                </div>

                        <button type="submit" class="btn btn-primary ml-4 btn-sm mb-4">Submit</button>
                    </form>

                </div>
                <!-- /.card -->
            </div>

            <!-- /.card -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- /.card -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Record</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <!-- <th>Content</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($policies as $policy)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $policy->type }}</td>
                                    <td>{{ $policy->title }}</td>
                                    <!-- <td>{{ $policy->content }}</td> -->
                                    <td>
                                        <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                        <form action="{{ route('policies.destroy', $policy->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                <i class='fas fa-trash-alt' style='font-size:15px;'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
</section>
<!-- <script>
    document.getElementById('type').addEventListener('change', function() {
        if (this.value === 'contact-us') {
            document.getElementById('contactFields').style.display = 'block';
        } else {
            document.getElementById('contactFields').style.display = 'none';
        }
    });
</script> -->
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