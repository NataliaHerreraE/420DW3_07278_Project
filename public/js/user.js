// Function to create a user

function getUserById(id) {
    
    $.ajax({
               url: `${baseUrl}api/manage_user`,
               type: 'GET',
               contentType: 'application/json',
               data: JSON.stringify({id: id}),
               dataType: "json", // Do not forget to add this when you expect JSON data back. The automatic exception outputter will adapt
               success: function(response) {
                   $("#debugContents").html(`<pre>${response.responseText}</pre>`);
                   $('#searchResults').html(response);
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to search users.');
               }
           });
}


function createUser() {
    let userData = {
        username: $('#usernameInput').val(),
        password: $('#passwordInput').val(),
        email: $('#emailInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}api/manage_user`,
               type: 'POST',
               contentType: 'application/json',
               data: JSON.stringify(userData),
               dataType: "json",
               success: function(response) {
                   alert('User created successfully!');
                   resetForm();
                   // TODO fill form?
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
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
               url: `${baseUrl}api/manage_user`,
               type: 'PUT',
               contentType: 'application/json',
               data: JSON.stringify(userData),
               dataType: "json",
               success: function(response) {
                   $("#debugContents").html(`<pre>${response.responseText}</pre>`);
                   alert('User updated successfully!');
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to update user.');
               }
           });
}

// Function to delete a user
function deleteUser() {
    let userId = $('#userIdInput').val();
    
    $.ajax({
               url: `${baseUrl}api/manage_user`,
               type: 'DELETE',
               data: JSON.stringify({id: userId}),
               contentType: 'application/json',
               dataType: "json",
               success: function(response) {
                   alert('User deleted successfully!');
                   $("#debugContents").html(`<pre>${response.responseText}</pre>`);
                   resetForm(); // Reset form after deletion
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to delete user.');
               }
           });
}

// Function to search users by ID
function searchByUserId() {
    let userId = $('#userIdSelect').val();
    
    $.ajax({
               url: `${baseUrl}api/search_user/${userId}`,
               type: 'GET',
               dataType: "json",
               success: function(response) {
                   $("#debugContents").html(`<pre>${response.responseText}</pre>`);
                   $('#searchResults').html(response);
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to search users.');
               }
           });
}

// Function to fetch all users
function fetchAllUsers() {
    $.ajax({
               url: `${baseUrl}api/get_all_users`,
               type: 'GET',
               dataType: "json",
               success: function(response, statusText, xhr) {
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   console.log(response); // delete lateeeeeer
                   console.log(statusText); // delete lateeeeeer
                   console.log(xhr); // delete lateeeeeer
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
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
    $.get(`${baseUrl}api/get_user_names`, data => {
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
               url: `${baseUrl}api/search_user/${userId}`,
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

