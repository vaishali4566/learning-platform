<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <style>
        form { max-width: 500px; margin: 20px auto; }
        input, textarea { width: 100%; margin: 10px 0; padding: 8px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Update Your Profile</h1>
    <form id="profile-form">
        <textarea name="bio" placeholder="Enter bio">{{ auth()->user()->bio ?? '' }}</textarea>
        <input type="text" name="city" placeholder="City" value="{{ auth()->user()->city ?? '' }}">
        <input type="text" name="country" placeholder="Country" value="{{ auth()->user()->country ?? '' }}">
        <input type="file" name="profile_image">
        <button type="submit">Save</button>
    </form>
    <div id="message"></div>

    <script>
        $(document).ready(function() {
            $('#profile-form').on('submit', function(e) {
                e.preventDefault();

                // Form data banaye
                var formData = new FormData(this);

                // API call with token
                $.ajax({
                    url: '/api/user/profile',
                    type: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ auth()->user()->currentAccessToken() ? auth()->user()->currentAccessToken()->token : '' }}'
                    },
                    data: formData,
                    processData: false, // File upload ke liye
                    contentType: false, // File upload ke liye
                    success: function(response) {
                        $('#message').html('<p style="color: green">' + response.message + '</p>');
                    },
                    error: function(xhr) {
                        $('#message').html('<p style="color: red">Error updating profile</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>