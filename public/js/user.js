// Function to create a user
function createUser() {
    let userData = {
        username: $('#usernameInput').val(),
        password: $('#passwordInput').val(),
        email: $('#emailInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}/api/create_user`,
               type: 'POST',
               contentType: 'application/json',
               data: JSON.stringify(userData),
               success: function(response) {
                   alert('User created successfully!');
                   resetForm();
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to create user. Please check the console for more details.');
               }
           });
}

// Function to update a user
function updateUser() {
    let userData = {
        id: $('#userIdInput').val(),
        username: $('#usernameInput').val(),
        password: $('#passwordInput').val(),
        email: $('#emailInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}/api/update_user`,
               type: 'PUT', //
               contentType: 'application/json',
               data: JSON.stringify(userData),
               success: function(response) {
                   alert('User updated successfully!');
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to update user.');
               }
           });
}

// Function to delete a user
function deleteUser() {
    let userId = $('#userIdInput').val();
    
    $.ajax({
               url: `${baseUrl}/api/delete_user`,
               type: 'DELETE',
               data: JSON.stringify({ id: userId }),
               contentType: 'application/json',
               success: function(response) {
                   alert('User deleted successfully!');
                   resetForm(); // Reset form after deletion
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to delete user.');
               }
           });
}

// Function to search users by ID
function searchByUserId() {
    let userId = $('#userIdSelect').val();
    
    $.ajax({
               url: `${baseUrl}/api/search_user/${userId}`,
               type: 'GET',
               success: function(response) {
                   $('#searchResults').html(response);
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to search users.');
               }
           });
}

// Function to fetch all users
function fetchAllUsers() {
    $.ajax({
               url: `${baseUrl}/api/get_all_users`,
               type: 'GET',
               success: function(response) {
                   console.log(response); // delete lateeeeeer
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to fetch users.');
               }
           });
}

// Reset form fields
function resetForm() {
    $('#userCrudForm').find('input[type=text], input[type=password], input[type=email]').val('');
}

$(document).ready(function () {
    fetchAllUsers(); // Fetch user IDs on load
});

// Populate user IDs // not using
/*function fetchUserIds() {
    $.get(baseUrl + 'api/get_user_ids', function(data) {
        /!*console.log(data);*!/  // onlyyy for checkingggg please remember to delete!!
        var select = $('#userIdSelect');
        select.empty();
        data.forEach(function(user) {
            select.append($('<option></option>').attr('value', user.id).text(user.id));
        });
    }).fail(function() {
        alert('Failed to fetch user IDs.');
    });
    
}*/

function fetchUserNames() {
    $.get(`${baseUrl}/api/get_user_names`, data => {
        var select = $('#userIdSelect');
        select.empty();
        if (Array.isArray(data)) {
            data.forEach(function (user) {
                select.append($('<option></option>').attr('value', user.id).text(user.name));
            });
        } else {
            console.error('Invalid data type:', data);
        }
    }).fail(() => {
        alert('Failed to fetch user names.');
    });
}

$(document).ready(() => {
    fetchUserNames();
});


function searchByUserName() {
    let userId = $('#userIdSelect').val();
    $.ajax({
               url: `${baseUrl}/api/search_user/${userId}`,
               type: 'GET',
               success: response => {
                   $('#searchResults').html(response);
               },
               error: xhr => {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to search users.');
               }
           });
}

