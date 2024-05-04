$(document).ready(function () {
    fetchUserIds();
});

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
    /*let userData = {
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
           });*/
    
    let comboBox = document.getElementById("userIdSelect");
    let selectedValue = comboBox.value;
    let selectedId = parseInt(selectedValue, 10); // Cast the selected value to an integer
    
    if (isNaN(selectedId)) {
        console.error("The selected value is not a valid number.");
    } else {
        console.log("The selected ID is: ", selectedId);
        let userId = selectedId;
        let username = $('#usernameInput').val();
        let password = $('#passwordInput').val();
        let email = $('#emailInput').val();
        
        $.ajax({
                   url: `${baseUrl}api/manage_user`,
                   type: 'PUT',
                   data: JSON.stringify({user_id: userId, username: username, password: password, email: email}),
                   contentType: 'application/json',
                   success: function(response) {
                       alert('User updated successfully!');
                   },
                   error: function(xhr) {
                       console.error('Error:', xhr.responseText);
                       alert('Failed to update user.');
                   }
               });
    }
}

// Function to delete a user
function deleteUser() {
    let comboBox = document.getElementById("userIdSelect");
    let selectedValue = comboBox.value;
    let selectedId = parseInt(selectedValue, 10); // Ensure it's an integer
    
    if (isNaN(selectedId)) {
        console.error("The selected value is not a valid number.");
        alert('Invalid user ID.');
    } else {
        console.log("The selected ID is: ", selectedId);
        $.ajax({
                   url: `${baseUrl}api/manage_user`,
                   type: 'DELETE',
                   contentType: 'application/json',
                   data: JSON.stringify({user_id: selectedId}), // Make sure the key matches the backend expectation
                   dataType: "json",
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
}

function fetchUserIds() {
    $.ajax({
               url: `${baseUrl}api/get_all_users`,
               type: 'GET',
               dataType: "json",
               success: function(response) {
                   if (Array.isArray(response.data)) {
                       populateUserIdSelect(response.data);
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('Failed to fetch users.');
                   }
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to fetch users.');
               }
           });
}

function searchByUserId() {
    let userId = $('#userIdSelect').val();
 /*
    $.ajax({
               url: `${baseUrl}api/search_user/${userId}`,
               type: 'GET',
               dataType: "json",
               success: function(response) {
                   $("#debugContents").html(`<pre>${response.responseText}</pre>`);
                   $('#searchResults').html(response);
                   if (Array.isArray(response.data)) {
                       populateUserTable(response.data);
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('Failed to fetch users.');
                   }
                   
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to search users.');
               }
           });*/
    
    
    $.ajax({
               url: `${baseUrl}api/manage_user`,
               type: 'GET',
               data: {user_id: userId},
               dataType: "json",
               success: function(response) {
                   console.log('Type of response:', typeof response);  // Log the type of response
                   console.log('Content of response:', response);  // Log the content of response
                   
                   if (response && typeof response === 'object' && Object.keys(response).length > 0) {
                       let user = {
                           id: response.id,
                           username: response.username,
                           password: response.password,
                           email: response.email
                       };
                       populateUserForm(user);  // Populate the form with the user data
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('No data found for user.');
                   }
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to search users.');
               }
           });
}

function populateUserForm(user) {
    console.log("Populating form with user:", user);
    $('#userIdInput').val(user.id);
    $('#usernameInput').val(user.username);
    $('#passwordInput').val(user.password);
    $('#emailInput').val(user.email);
}


function fetchAllUsers() {
    $.ajax({
               url: `${baseUrl}api/get_all_users`,
               type: 'GET',
               dataType: "json",
               success: function(response, statusText, xhr) {
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   /*console.log(response); // delete lateeeeeer
                   console.log(statusText); // delete lateeeeeer
                   console.log(xhr); // delete lateeeeeer*/
                   console.log("Users variable:", response.data);
                   console.log("Type of users variable:", typeof response.data);
                   if (Array.isArray(response.data)) {
                       console.log("Data is array, now populating table.");
                       populateUserIdSelect(response.data);
                       populateUserTable(response.data);
                       console.log("AJAX call successful: ", response.data);
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('Failed to fetch users.');
                   }
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   $("#debugContents").html(`<pre>${xhr.responseText}</pre>`);
                   alert('Failed to fetch users.');
               }
           });
}
function populateUserIdSelect(users) {
    var userIdSelect = $('#userIdSelect');
    userIdSelect.empty();  // Clear existing options
    users.forEach(user => {
        userIdSelect.append(`<option value="${user.id}">${user.id}</option>`);
    });
}

function populateUserTable(users) {
    console.log("Received users for table:", users); // Make sure this log shows the expected array
    
    var tableBody = $('#allUsersBody');
    console.log("Table body found:", tableBody.length); // Check if the table body is found
    
    tableBody.empty(); // Clear existing table rows.
    
    users.forEach(user => {
        console.log("Adding user:", user); // This should log each user object
        
        var row = `<tr>
                     <td>${user.id}</td>
                     <td>${user.username}</td>
                     <td>${user.email}</td>
                   </tr>`;
        tableBody.append(row);
    });
    
    console.log("Table after adding rows:", $('#allUsersBody').html()); // Log the inner HTML to verify rows are added
}

function resetForm() {
    $('#userCrudForm').find('input[type=text], input[type=password], input[type=email]').val('');
}


function addUserToGroup() {
    let userId = $('#userIdInput').val();
    let groupId = $('#groupIdInput').val();
    $.ajax({
               url: `${baseUrl}api/add_user_to_group`,
               type: 'POST',
               data: JSON.stringify({userId: userId, groupId: groupId}),
               contentType: 'application/json',
               success: function(response) {
                   alert('User added to group successfully!');
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to add user to group.');
               }
           });
}

function removeUserFromGroup() {
    let userId = $('#userIdInput').val();
    let groupId = $('#groupIdInput').val();
    $.ajax({
               url: `${baseUrl}api/remove_user_from_group`,
               type: 'POST',
               data: JSON.stringify({userId: userId, groupId: groupId}),
               contentType: 'application/json',
               success: function(response) {
                   alert('User removed from group successfully!');
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to remove user from group.');
               }
           });
}

function fetchDeletedUsers() {
    $.ajax({
               url: `${baseUrl}api/get_deleted_users`, // Adjust the URL based on your API endpoint
               type: 'GET',
               dataType: "json",
               success: function(response) {
                   if (response && response.data) {
                       populateDeletedUserTable(response.data);
                   } else {
                       console.error('Failed to fetch or parse deleted user data:', response);
                       alert('Failed to fetch deleted users.');
                   }
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to fetch deleted users.');
               }
           });
}

function populateDeletedUsersTable(users) {
    var tableBody = $('#deletedUsersBody');
    tableBody.empty(); // Clear existing table rows.
    
    users.forEach(user => {
        var row = `<tr>
                     <td>${user.id}</td>
                     <td>${user.username}</td>
                     <td>${user.email}</td>
                   </tr>`;
        tableBody.append(row);
    });
}