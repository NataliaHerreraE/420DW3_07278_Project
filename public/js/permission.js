$(document).ready(function () {
    fetchPermissionIds();
});

function fetchAllPermissions() {
    $.ajax({
               url: `${baseUrl}api/get_all_permissions`,
               type: 'GET',
               dataType: 'json',
               success: function(response) {
                   if (response.success && Array.isArray(response.data)) {
                       populatePermissionTable(response.data);
                   } else {
                       console.error('Failed to fetch permissions:', response);
                       alert('Failed to fetch permissions.');
                   }
               },
               error: function(xhr) {
                   console.error('Error fetching permissions:', xhr.responseText);
                   alert('Failed to fetch permissions.');
               }
           });
}


function fetchPermissionIds() {
    $.ajax({
               url: `${baseUrl}api/get_all_permissions`,  // Confirm this URL is correct
               type: 'GET',
               dataType: 'json',
               success: function(response) {
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
               error: function(xhr) {
                   console.error('Error fetching permission IDs:', xhr.responseText);
                   alert('Failed to fetch permission IDs.');
               }
           });
}


function createPermission() {
    let permissionData = {
        permissionKey: $('#permissionKeyInput').val(),
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
        permission_id: permissionId,
        permissionKey: $('#permissionKeyInput').val(),
        name: $('#nameInput').val(),
        description: $('#descriptionInput').val()
    };
    
    console.log(permissionData);
    
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
               contentType: 'application/json',
               data: JSON.stringify({permission_id: permissionId}),
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
               url: `${baseUrl}api/manage_permission`,  // Make sure this URL correctly points to your API
               type: 'GET',
               data: {permission_id: permissionId},
               dataType: 'json',
               success: function(response) {
                   if (response && response.id) {  // Make sure to check for the right property
                       populateForm(response);
                   } else {
                       console.error('No data found or incomplete data for permission:', response);
                       alert('No data found for permission.');
                   }
               },
               error: function(xhr) {
                   console.error('Failed to fetch permission:', xhr.responseText);
                   alert('Failed to fetch permission.');
               }
           });
}

function populateForm(permissionData) {
    $('#permissionKeyInput').val(permissionData.permissionKey);
    $('#nameInput').val(permissionData.name);
    $('#descriptionInput').val(permissionData.description);
}


function populatePermissionTable(permissions) {
    let tableBody = $('#allPermissionsBody');
    tableBody.empty();
    permissions.forEach(permission => {
        let row = `<tr>
            <td>${permission.id}</td>
            <td>${permission.permissionKey}</td>
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
