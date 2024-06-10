<div id="editBookModal" class="modal" style="display:none;">
    <div class="modal-content" id="editBookModalContent">
        <span class="close" onclick="closeEditBookModal()">&times;</span>
        <h2>Edit Book</h2>
        <form id="editBookForm" method="POST" enctype="multipart/form-data" onsubmit="return validateEditBookForm()">
            <div class="form-group">
                <label for="editTitle">Title:</label>
                <input type="text" id="editTitle" name="editTitle" required>
                <div id="editTitleError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editAuthor">Author:</label>
                <input type="text" id="editAuthor" name="editAuthor" required>
                <div id="editAuthorError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editGenre">Genre:</label>
                <select id="editGenre" name="editGenre" required>
                    <option value="">Select Genre</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Horror">Horror</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Biography">Biography</option>
                    <option value="History">History</option>
                    <option value="Romance">Romance</option>
                    <option value="Poetry">Poetry</option>
                </select>
                <div id="editGenreError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editDescription">Description:</label>
                <textarea id="editDescription" name="editDescription" required></textarea>
                <div id="editDescriptionError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editQuantity">Quantity:</label>
                <input type="number" id="editQuantity" name="editQuantity" min="0" required>
                <div id="editQuantityError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editPrice">Price:</label>
                <input type="number" step="0.01" id="editPrice" name="editPrice" required>
                <div id="editPriceError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="editBookImage">Book Image:</label>
                <input type="file" id="editBookImage" name="editBookImage">
            </div>
            <input type="hidden" id="editBookId" name="editBookId">
            <div class="form-actions">
                <button class="submit-button" type="submit">Save Changes</button>
                <button type="button" class="delete-btn delete-book-btn" onclick="confirmDelete($('#editBookId').val())">Delete Book</button>
            </div>
        </form>
    </div>
</div>

<script>

    
function validateEditBookForm() {
    const editTitle = document.getElementById("editTitle");
    const editAuthor = document.getElementById("editAuthor");
    const editGenre = document.getElementById("editGenre");
    const editDescription = document.getElementById("editDescription");
    const editQuantity = document.getElementById("editQuantity");
    const editPrice = document.getElementById("editPrice");

    document.querySelectorAll('.error-message').forEach(element => {
        element.textContent = '';
        element.style.color = 'red';  
    });

    let isValid = true;

// Validate each input for the edit book modal

    if (editTitle.value.trim() === '') {
        document.getElementById("editTitleError").textContent = "Title is required.";
        isValid = false;
    }

    if (editAuthor.value.trim() === '') {
        document.getElementById("editAuthorError").textContent = "Author is required.";
        isValid = false;
    }

    if (editGenre.value === '') {
        document.getElementById("editGenreError").textContent = "Genre is required.";
        isValid = false;
    }

    if (editDescription.value.trim() === '') {
        document.getElementById("editDescriptionError").textContent = "Description is required.";
        isValid = false;
    }

    if (editQuantity.value.trim() === '') {
        document.getElementById("editQuantityError").textContent = "Quantity is required.";
        isValid = false;
    }

    if (editPrice.value.trim() === '') {
        document.getElementById("editPriceError").textContent = "Price is required.";
        isValid = false;
    }

    if (isNaN(editQuantity.value) || isNaN(editPrice.value)) {
        document.getElementById("editQuantityError").textContent = "Quantity must be a number.";
        document.getElementById("editPriceError").textContent = "Price must be a number.";
        isValid = false;
    }

    return isValid;
}

 
 
</script>
