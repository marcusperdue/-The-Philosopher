 <div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditUserModal()">&times;</span>
        <h2>Edit User</h2>
        
        <form id="editUserForm" method="POST" onsubmit="return validateEditUserForm()">
            <input type="hidden" id="editUserId" name="editUserId">
            <div class="form-group">
                <label for="editUserFirstName">First Name:</label>
                <input type="text" id="editUserFirstName" name="editUserFirstName" required>
            </div>
            <div class="form-group">
                <label for="editUserLastName">Last Name:</label>
                <input type="text" id="editUserLastName" name="editUserLastName" required>
            </div>
            <div class="form-group">
                <label for="editUserEmail">Email:</label>
                <input type="email" id="editUserEmail" name="editUserEmail" required>
            </div>
            <div class="form-group">
                <label for="editUserUsername">Username:</label>
                <input type="text" id="editUserUsername" name="editUserUsername" required>
            </div>
            <button class="submit-button" type="submit">Save Changes</button>
        </form>

        <form id="deleteUserForm" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" id="deleteUserId" name="deleteUserId">
            <button type="submit" class="delete-btn">DELETE</button>
        </form>
    </div>
</div>

<script>

        const editUserModal = document.getElementById('editUserModal');

// Function to open the edit user modal and populate fields
        function openEditUserModal(userId, firstName, lastName, email, username) {
            editUserModal.style.display = "block";
            document.getElementById("editUserId").value = userId;
            document.getElementById("editUserFirstName").value = firstName;
            document.getElementById("editUserLastName").value = lastName;
            document.getElementById("editUserEmail").value = email;
            document.getElementById("editUserUsername").value = username;
        }

// Function to close the edit user modal
        function closeEditUserModal() {
            editUserModal.style.display = "none";
        }
// Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == editUserModal) {
                closeEditUserModal();
            }
        }

// Function to confirm user deletion
        function confirmDeleteUser() {
            var confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                document.getElementById("deleteUserForm").submit();
            }
        }

// Function to validate the edit user form
        function validateEditUserForm() {
            const firstName = document.getElementById("editUserFirstName").value.trim();
            const lastName = document.getElementById("editUserLastName").value.trim();
            const email = document.getElementById("editUserEmail").value.trim();
            const username = document.getElementById("editUserUsername").value.trim();
            let isValid = true;

            document.getElementById("editUserFirstNameError").textContent = "";
            document.getElementById("editUserLastNameError").textContent = "";
            document.getElementById("editUserEmailError").textContent = "";
            document.getElementById("editUserUsernameError").textContent = "";

// Validate each input
            if (firstName === "") {
                document.getElementById("editUserFirstNameError").textContent = "First name is required.";
                isValid = false;
            }

            if (lastName === "") {
                document.getElementById("editUserLastNameError").textContent = "Last name is required.";
                isValid = false;
            }

            if (email === "") {
                document.getElementById("editUserEmailError").textContent = "Email is required.";
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById("editUserEmailError").textContent = "Invalid email format.";
                isValid = false;
            }

            if (username === "") {
                document.getElementById("editUserUsernameError").textContent = "Username is required.";
                isValid = false;
            }

            return isValid;
        }
    </script>