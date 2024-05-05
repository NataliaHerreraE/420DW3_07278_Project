$(document).ready(function() {
    fetchGroupIds();
});

function createGroup() {
    let groupData = {
        groupname: $('#groupnameInput').val(),
        description: $('#descriptionInput').val()
    };
    
    $.ajax({
               url: `${baseUrl}api/manage_group`,
               type: 'POST',
               contentType: 'application/json',
               data: JSON.stringify(groupData),
               success: function(response) {
                   alert('Group created successfully!');
                   resetGroupForm();
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to create group.');
               }
           });
}


function updateGroup() {
    let comboBox = document.getElementById("groupIdSelect");
    let selectedValue = comboBox.value;
    let selectedId = parseInt(selectedValue, 10); // Cast the selected value to an integer
    
    if (isNaN(selectedId)) {
        console.error("The selected value is not a valid number.");
    } else {
        console.log("The selected ID is: ", selectedId);
        let groupId = selectedId;
        let groupname = $('#groupnameInput').val();
        let description = $('#descriptionInput').val();
        
        $.ajax({
                   url: `${baseUrl}api/manage_group`,
                   type: 'PUT',
                   contentType: 'application/json',
                   data: JSON.stringify({group_id: groupId, groupname: groupname, description: description}),
                   success: function (response) {
                       alert('Group updated successfully!');
                   },
                   error: function (xhr) {
                       console.error('Error:', xhr.responseText);
                       alert('Failed to update group.');
                   }
               });
    }
}

function deleteGroup() {
    let groupId = $('#groupIdSelect').val();
    
    $.ajax({
               url: `${baseUrl}api/manage_group`,  // Ensure this is the correct endpoint
               type: 'PUT',  // Using PUT for update operation to set deletion flag
               contentType: 'application/json',
               data: JSON.stringify({
                                        group_id: groupId,
                                        is_deleted: 1  // Indicate deletion
                                    }),
               success: function(response) {
                   alert('Group deleted successfully!');
                   // Refresh your group list or UI components if necessary
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to delete group.');
               }
           });

}

function fetchAllGroups() {
    $.ajax({
               url: `${baseUrl}api/get_all_groups`,
               type: 'GET',
               dataType: "json",
               success: function (response) {
                   if (response && Array.isArray(response.data)) {
                       populateGroupTable(response.data);
                   } else {
                       console.error('No groups data or incorrect format:', response);
                       alert('Failed to fetch groups.');
                   }
               },
               error: function (xhr) {
                   console.error('Error fetching groups:', xhr.responseText);
                   alert('Failed to fetch groups.');
               }
           });
}

function fetchGroupIds() {
    $.ajax({
               url: `${baseUrl}api/get_all_groups`,
               type: 'GET',
               dataType: 'json',
               success: function(response) {
                   if (response.success && Array.isArray(response.data)) {
                       var groupIdSelect = $('#groupIdSelect');
                       groupIdSelect.empty();
                       response.data.forEach(group => {
                           groupIdSelect.append(`<option value="${group.group_id}">${group.group_id}</option>`);
                       });
                   } else {
                       console.error('Failed to fetch or parse group data:', response);
                       alert('Failed to fetch group.');
                   }
               },
               error: function(xhr) {
                   console.error('Error:', xhr.responseText);
                   alert('Failed to fetch group.');
               }
           });
}

function searchByGroupId() {
    let groupId = $('#groupIdSelect').val();
    $.ajax({
               url: `${baseUrl}api/manage_group`,
               type: 'GET',
               data: {group_id: groupId},
               dataType: 'json',
               success: function(response) {
                   if (response && response.success && response.data) {
                       populateGroupForm(response.data);
                   } else {
                       alert('No data found for group.');
                   }
               },
               error: function(xhr) {
                   alert('Failed to search group.');
               }
           });
}

function populateGroupForm(group) {
    console.log("Populating form with group data:", group);
    $('#groupIdInput').val(group.group_id || group.id);
    $('#groupnameInput').val(group.group_name || group.groupName);
    $('#descriptionInput').val(group.description);
}


function populateGroupIdSelect(groups) {
    var groupIdSelect = $('#groupIdSelect');
    groupIdSelect.empty(); // Clear existing options
    groups.forEach(group => {
        groupIdSelect.append(`<option value="${group.group_id}">${group.group_id}</option>`);
    });
}

/*
$(document).ready(function() {
    fetchGroupIds();
});
*/

function populateGroupTable(groups) {
    console.log('Groups received:', groups);  // This will log the data received from fetchAllGroups
    var tableBody = $('#allgroupsBody');
    tableBody.empty();  // Clear any existing rows
    
    groups.forEach(function(group) {
        var row = `<tr>
                       <td>${group.group_id}</td>
                       <td>${group.group_name}</td>
                       <td>${group.description}</td>
                   </tr>`;
        tableBody.append(row);
    });
}



function resetGroupForm() {
    $('#groupCrudForm').find('input[type=text], input[type=description]').val('');
}

function populateDeletedgroupTable(groups) {
    var tableBody = $('#deletedGroupsBody');
    tableBody.empty();
    groups.forEach(group => {
        var row = `<tr>
                     <td>${group.group_id}</td>
                     <td>${group.group_name}</td>
                     <td>${group.description}</td>
                   </tr>`;
        tableBody.append(row);
    });
}

function fetchDeletedGroups() {
    $.ajax({
               url: `${baseUrl}api/manage_group`,
               type: 'GET',
               data: {has_deleted: 1},
               dataType: 'json',
               success: function(response) {
                   if (response && response.data) {
                       populateDeletedgroupTable(response.data);
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