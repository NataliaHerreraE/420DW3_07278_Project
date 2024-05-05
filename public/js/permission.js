$(document).ready(function () {
    fetchPermissionIds();
});

function fetchAllPermissions() {
    $.ajax({
               url: `${baseUrl}api/get_all_permissions`,
               type: 'GET',
               dataType: 'json',
               success: function (response) {
                   if (Array.isArray(response.data)) {
                       console.log("Data is array, now populating table.");
                       populatePermissionIdSelect(response.data);
                       populatePermissionTable(response.data);
                       console.log("AJAX call successful: ", response.data);
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('Failed to fetch users.');
                   }
                   
               },
               error: function (xhr) {
                   console.error('Error fetching permissions:', xhr.responseText);
                   alert('Failed to fetch permissions.');
               }
           });
}

function fetchPermissionIds() {
    $.ajax({
               url: `${baseUrl}api/get_all_permissions`,
               type: 'GET',
               dataType: 'json',
               success: function (response) {
                   if (response.success && Array.isArray(response.data)) {
                       var permissionIdSelect = $('#permissionIdSelect');
                       permissionIdSelect.empty();
                       response.data.forEach(function(permission) {
                           permissionIdSelect.append(`<option value="${permission.id}">${permission.name}</option>`);
                       });
                   } else {
                       console.error('Failed to fetch or parse permission data:', response);
                       alert('Failed to fetch permissions.');
                   }
               },
               error: function (xhr) {
                   console.error('Error fetching permission IDs:', xhr.responseText);
                   alert('Failed to fetch permission IDs.');
               }
           });
}

function createPermission() {
    let permissionData = {
        key: $('#permissionKeyInput').val(),
        name: $('#nameInput').val(),
        description: $('#descriptionInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}api/manage_permission`,
               type: 'POST',
               contentType: 'application/json',
               data: JSON.stringify(permissionData),
               dataType: 'json',
               success: function (response) {
                   alert('Permission created successfully!');
                   resetForm();
                   fetchPermissionIds();
               },
               error: function (xhr) {
                   console.error('Error creating permission:', xhr.responseText);
                   alert('Failed to create permission.');
               }
           });
}

function updatePermission() {
    let permissionId = $('#permissionIdSelect').val();
    let permissionData = {
        id: permissionId,
        key: $('#permissionKeyInput').val(),
        name: $('#nameInput').val(),
        description: $('#descriptionInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}api/manage_permission`,
               type: 'PUT',
               contentType: 'application/json',
               data: JSON.stringify(permissionData),
               success: function (response) {
                   alert('Permission updated successfully!');
                   fetchAllPermissions();
               },
               error: function (xhr) {
                   console.error('Error updating permission:', xhr.responseText);
                   alert('Failed to update permission.');
               }
           });
}

function deletePermission() {
    let permissionId = $('#permissionIdSelect').val();
    
    $.ajax({
               url: `${baseUrl}api/manage_permission`,
               type: 'DELETE',
               data: { id: permissionId },
               success: function (response) {
                   alert('Permission deleted successfully!');
                   fetchAllPermissions();
               },
               error: function (xhr) {
                   console.error('Error deleting permission:', xhr.responseText);
                   alert('Failed to delete permission.');
               }
           });
}

function searchByPermissionId() {
    let permissionId = $('#permissionIdSelect').val();
    
    $.ajax({
               url: `${baseUrl}api/manage_permission`,
               type: 'GET',
               data: {id: permissionId},
               dataType: 'json',
               success: function (response) {
                   if (response && typeof response === 'object' && Object.keys(response).length > 0) {
                       let permission = {
                           id: response.id,
                           permissionKey: response.permissionKey,
                           name: response.name,
                           description: response.description
                       };
                       populatePermissionForm(permission);  // Populate the form with the user data
                   } else {
                       console.error('Failed to fetch or parse user data:', response);
                       alert('No data found for user.');
                   }
               },
               error: function (xhr) {
                   console.error('Error searching permission:', xhr.responseText);
                   alert('Failed to search permission.');
               }
           });
}

function populatePermissionForm(permission) {
    $('#permissionKeyInput').val(permission.permissionKey);
    $('#nameInput').val(permission.name);
    $('#descriptionInput').val(permission.description);

}

function populatePermissionTable(permissions) {
    let tableBody = $('#allPermissionsBody');
    tableBody.empty();
    
    permissions.forEach(permission => {
        let row = `<tr>
            <td>${permission.id}</td>
            <td>${permission.key}</td>
            <td>${permission.name}</td>
            <td>${permission.description}</td>
        </tr>`;
        tableBody.append(row);
    });
}

function populatePermissionIdSelect(permissions) {
    let permissionSelect = $('#permissionIdSelect');
    permissionSelect.empty();  // Clear existing options
    
    permissions.forEach(permission => {
        permissionSelect.append(`<option value="${permission.id}">${permission.name}</option>`);
    });
}

function resetForm() {
    $('#permissionKeyInput').val('');
    $('#nameInput').val('');
    $('#descriptionInput').val('');
}
