@include('layouts.app')

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <b>Profile Image</b><br>
            @if($user_image[0] !='' && $user_image[0] !==null)
            <img src="/images/user_images/{{$user_image[0]}}" class="img-fluid profile_image mt-4" />
            @else
            <img src="/images/user_images/default_profile_picture.jpg" class="img-fluid profile_image mt-4" />
            @endif
        </div>
        <div class="col-md-8">
            <form action="{{url('/update-user-profile')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach($user_info as $info)
                <div class="mb-3">
                    <label for="name">Nickname</label>
                    <input type="text" class="form-control" id="name" value="{{$info->name}}" readonly>
                </div>
                <div class="mb-3">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{$info->location}}" maxlength="30" required>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Profile Picture<b class="text-danger"> ( 90 x 120 pixels)</b></label>
                    <input class="form-control" type="file" id="formFile" name="profile_picture">
                </div>
                <div class="mb-3">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" cols="114" rows="10" maxlength="700" value="{{$info->bio}}" required> {{$info->bio}}</textarea>
                    <small class="text-danger text-bio">0/700</small> <br>
                    <small><b>Note </b>Please write your bio in the third person (John has a background in sports). You may write about your background, expertise, passions, mission and accomplishments. Add a personal touch. Use this section as an opportunity to connect with your audience and give them a glimpse into your personality and expertise.</small>
                </div>

                @endforeach
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@include('footer.footer')
<script>
    $('#bio').on('keyup', function() {
        $('.text-bio').empty();
        $('.text-bio').html($(this).val().length + "/700");
    })
</script>